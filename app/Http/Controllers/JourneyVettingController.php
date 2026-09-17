<?php

namespace App\Http\Controllers;

use App\Mail\JourneyTacMail;
use App\Mail\CaseAccessTacMail;
use App\Mail\CaseAccessGrantedMail;
use App\Models\JourneyVettingSubmission;
use App\Models\Inquiry;
use App\Models\ApplicantAccount;
use App\Support\PhoneNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class JourneyVettingController extends Controller
{
    private const TAC_SESSION_KEY = 'journey_vetting_tac';

    public function dashboard(Request $request): View
    {
        $tab = in_array($request->query('tab'), ['inquiries', 'submissions', 'slugs'], true) ? $request->query('tab') : 'inquiries';
        $search = trim((string) $request->query('search'));
        $status = (string) $request->query('status', 'all');

        $query = JourneyVettingSubmission::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('reference', 'like', "%{$search}%")
                        ->orWhere('full_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when(in_array($status, ['pending_review', 'reviewed'], true), fn ($query) => $query->where('status', $status));

        return view('vetting-dashboard', [
            'submissions' => $query->latest('submitted_at')->paginate(15)->withQueryString(),
            'search' => $search,
            'status' => $status,
            'tab' => $tab,
            'inquiries' => Inquiry::query()
                ->when($search !== '', fn ($query) => $query->where(fn ($query) => $query
                    ->where('reference', 'like', "%{$search}%")->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")->orWhere('message', 'like', "%{$search}%")))
                ->when(in_array($status, ['new', 'reviewed'], true), fn ($query) => $query->where('status', $status))
                ->latest('submitted_at')->paginate(15, ['*'], 'inquiry_page')->withQueryString(),
            'inquiryStats' => ['total' => Inquiry::count(), 'new' => Inquiry::where('status', 'new')->count(), 'today' => Inquiry::whereDate('submitted_at', today())->count()],
            'stats' => [
                'total' => JourneyVettingSubmission::count(),
                'pending' => JourneyVettingSubmission::where('status', 'pending_review')->count(),
                'today' => JourneyVettingSubmission::whereDate('submitted_at', today())->count(),
                'agreed' => JourneyVettingSubmission::where('agreement_status', 'agree')->count(),
            ],
        ]);
    }

    public function createSlug(JourneyVettingSubmission $submission): RedirectResponse
    {
        if (! $submission->access_slug) {
            $submission->update([
                'access_slug' => $submission->access_slug ?: 'case-'.Str::lower(Str::random(32)),
                'access_slug_created_at' => now(),
            ]);
        }

        return redirect()->route('journey.dashboard', ['tab' => 'slugs'])->with('slug_created', $submission->id);
    }

    public function toggleSlugAccess(Request $request, JourneyVettingSubmission $submission): RedirectResponse
    {
        abort_unless($submission->access_slug, 422, 'Create a URL before enabling access.');
        $enable = $request->boolean('enable');
        $submission->update(['access_enabled_at' => $enable ? now() : null]);
        if (! $enable) {
            $request->session()->forget(['case_access.'.$submission->id, 'case_access_tac.'.$submission->id]);
        }
        if ($enable) {
            try {
                Mail::mailer('brevo')->to($submission->email)->send(new CaseAccessGrantedMail($submission->full_name, route('journey.access', $submission->access_slug), ! ApplicantAccount::where('journey_vetting_submission_id', $submission->id)->exists()));
                $submission->update(['access_invited_at' => now()]);
            } catch (\Throwable $exception) {
                report($exception);
                return redirect()->route('journey.dashboard', ['tab' => 'slugs'])->withErrors(['access' => 'Access was enabled, but the invitation email could not be sent.']);
            }
        }
        Log::info($enable ? 'Case URL access enabled' : 'Case URL access revoked', ['submission' => $submission->reference, 'administrator' => $request->user()->id]);
        return redirect()->route('journey.dashboard', ['tab' => 'slugs'])->with('access_updated', $submission->id);
    }

    public function accessLink(Request $request, string $slug): View
    {
        $submission = JourneyVettingSubmission::where('access_slug', $slug)->firstOrFail();
        if (! $submission->access_enabled_at) return view('submission-access-pending');
        $access = $request->session()->get('case_access.'.$submission->id, []);
        $unlocked = is_array($access) && ($access['expires_at'] ?? 0) > now()->timestamp;
        if (! $unlocked) $request->session()->forget('case_access.'.$submission->id);
        $pending = $request->session()->get('case_access_tac.'.$submission->id);
        $tacPending = is_array($pending) && ($pending['expires_at'] ?? 0) > now()->timestamp;
        $tacExemptPending = $tacPending && (bool) ($pending['tac_exempt'] ?? false);
        $hasAccount = ApplicantAccount::where('journey_vetting_submission_id', $submission->id)->exists();
        return view('submission-access', compact('submission', 'unlocked', 'tacPending', 'tacExemptPending', 'hasAccount'));
    }

    public function sendAccessTac(Request $request, string $slug): RedirectResponse
    {
        $submission = JourneyVettingSubmission::where('access_slug', $slug)->firstOrFail();
        abort_unless($submission->access_enabled_at, 403);
        abort_if($submission->access_password, 409, 'Use your access password to sign in.');
        $data = $request->validate(['email' => ['required', 'email:rfc', 'max:254']]);
        $rateKey = 'case-tac-send:'.hash('sha256', $submission->access_slug);
        if (RateLimiter::tooManyAttempts($rateKey, 3)) {
            Log::warning('Case TAC send limit reached', ['submission' => $submission->reference, 'ip' => $request->ip()]);
            return back()->withErrors(['email' => 'Please wait before requesting another TAC.']);
        }
        RateLimiter::hit($rateKey, 600);
        $matches = hash_equals(Str::lower($submission->email), Str::lower($data['email']));
        if (! $matches) {
            Log::notice('Case TAC requested with unmatched email', ['submission' => $submission->reference, 'ip' => $request->ip()]);
            return redirect()->route('journey.access', $slug)->with('tac_request_received', true);
        }
        $tacExempt = in_array(Str::lower($submission->email), config('app.case_access_tac_exempt_emails', []), true);
        if ($tacExempt) {
            $request->session()->put('case_access_tac.'.$submission->id, [
                'tac_exempt' => true, 'expires_at' => now()->addMinutes(15)->timestamp, 'attempts' => 0,
            ]);
            Log::info('Case TAC exemption used', ['submission' => $submission->reference, 'ip' => $request->ip()]);
            return redirect()->route('journey.access', $slug)->with('access_identity_ready', true);
        }
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        try {
            Mail::mailer('brevo')->to($submission->email)->send(new CaseAccessTacMail($code, $submission->full_name));
        } catch (\Throwable $exception) {
            report($exception);
            return back()->withErrors(['email' => 'We could not send the TAC. Please try again shortly.']);
        }
        $request->session()->put('case_access_tac.'.$submission->id, [
            'code_hash' => hash_hmac('sha256', $code, (string) config('app.key')), 'expires_at' => now()->addMinutes(15)->timestamp, 'attempts' => 0,
        ]);
        Log::info('Case TAC sent', ['submission' => $submission->reference, 'ip' => $request->ip()]);
        return redirect()->route('journey.access', $slug)->with('tac_sent', true);
    }

    public function unlockAccessLink(Request $request, string $slug): RedirectResponse
    {
        $submission = JourneyVettingSubmission::where('access_slug', $slug)->firstOrFail();
        abort_unless($submission->access_enabled_at, 403);
        abort_if(ApplicantAccount::where('journey_vetting_submission_id', $submission->id)->exists(), 409, 'An applicant account already exists.');
        $data = $request->validate(['code' => ['nullable', 'digits:6'], 'identity_suffix' => ['required', 'regex:/^[A-Za-z0-9]{4}$/'], 'password' => ['required', 'confirmed', 'min:12'], 'terms' => ['accepted'], 'privacy' => ['accepted']]);
        $key = 'case_access_tac.'.$submission->id;
        $pending = $request->session()->get($key, []);
        if (($pending['expires_at'] ?? 0) <= now()->timestamp) {
            $request->session()->forget($key);
            return back()->withErrors(['code' => 'This TAC has expired. Request a new code.']);
        }
        if (($pending['attempts'] ?? 0) >= 5) {
            $request->session()->forget($key);
            return back()->withErrors(['code' => 'Too many attempts. Request a new TAC.']);
        }
        $identitySuffix = Str::lower(substr(preg_replace('/[^A-Za-z0-9]/', '', (string) $submission->identity_number), -4));
        $validTac = (bool) ($pending['tac_exempt'] ?? false)
            || (isset($pending['code_hash'], $data['code']) && hash_equals($pending['code_hash'], hash_hmac('sha256', $data['code'], (string) config('app.key'))));
        $validIdentity = strlen($identitySuffix) === 4 && hash_equals($identitySuffix, Str::lower($data['identity_suffix']));
        if (! $validTac || ! $validIdentity) {
            $pending['attempts'] = ($pending['attempts'] ?? 0) + 1;
            $request->session()->put($key, $pending);
            Log::notice('Case access verification failed', ['submission' => $submission->reference, 'ip' => $request->ip()]);
            return back()->withErrors(['code' => 'The TAC or identity digits are incorrect.']);
        }
        $request->session()->forget($key);
        ApplicantAccount::create(['journey_vetting_submission_id' => $submission->id, 'full_name' => $submission->full_name, 'email' => Str::lower($submission->email), 'phone' => $submission->phone, 'password' => $data['password'], 'email_verified_at' => now()]);
        $request->session()->put('case_access.'.$submission->id, ['expires_at' => now()->addHours(24)->timestamp]);
        $request->session()->regenerate();
        Log::info('Case access unlocked', ['submission' => $submission->reference, 'ip' => $request->ip()]);
        return redirect()->route('journey.access', $submission->access_slug);
    }

    public function loginAccessLink(Request $request, string $slug): RedirectResponse
    {
        $submission = JourneyVettingSubmission::where('access_slug', $slug)->firstOrFail();
        $account = ApplicantAccount::where('journey_vetting_submission_id', $submission->id)->first();
        abort_unless($submission->access_enabled_at && $account, 403);
        $data = $request->validate(['email' => ['required', 'email:rfc'], 'password' => ['required', 'string']]);
        $validEmail = hash_equals(Str::lower($submission->email), Str::lower($data['email']));
        if (! $validEmail || ! Hash::check($data['password'], $account->password)) {
            Log::notice('Case password login failed', ['submission' => $submission->reference, 'ip' => $request->ip()]);
            return back()->withErrors(['email' => 'The email or password is incorrect.'])->onlyInput('email');
        }
        $request->session()->regenerate();
        $request->session()->put('case_access.'.$submission->id, ['expires_at' => now()->addHours(24)->timestamp]);
        Log::info('Case password login succeeded', ['submission' => $submission->reference, 'ip' => $request->ip()]);
        return redirect()->route('journey.access', $slug);
    }

    public function terminateAccessLink(Request $request, string $slug): RedirectResponse
    {
        $submission = JourneyVettingSubmission::where('access_slug', $slug)->firstOrFail();
        $request->session()->forget(['case_access.'.$submission->id, 'case_access_tac.'.$submission->id]);
        $request->session()->regenerate();
        return redirect()->route('journey.access', $slug);
    }

    public function sendTac(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:254'],
            'full_name' => ['nullable', 'string', 'max:150'],
        ]);

        $pending = $request->session()->get(self::TAC_SESSION_KEY, []);
        $availableAt = (int) ($pending['resend_available_at'] ?? 0);

        if ($availableAt > now()->timestamp) {
            return response()->json([
                'message' => 'Please wait before requesting another code.',
                'retry_after' => $availableAt - now()->timestamp,
            ], 429);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $email = Str::lower($validated['email']);

        try {
            Mail::mailer('brevo')
                ->to($email)
                ->send(new JourneyTacMail($code, $validated['full_name'] ?? null));
        } catch (\Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'We could not send the verification code. Please try again shortly.',
            ], 503);
        }

        $request->session()->put(self::TAC_SESSION_KEY, [
            'email' => $email,
            'code_hash' => hash('sha256', $code),
            'expires_at' => now()->addMinutes(10)->timestamp,
            'resend_available_at' => now()->addSeconds(60)->timestamp,
            'attempts' => 0,
        ]);
        $request->session()->forget('journey_vetting_verified_email');

        return response()->json([
            'message' => 'A six-digit verification code has been sent to your email.',
            'resend_after' => 60,
        ]);
    }

    public function verifyTac(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email:rfc', 'max:254'],
            'code' => ['required', 'digits:6'],
        ]);

        $pending = $request->session()->get(self::TAC_SESSION_KEY);
        $email = Str::lower($validated['email']);

        if (! is_array($pending) || ! hash_equals((string) ($pending['email'] ?? ''), $email)) {
            return $this->validationError('code', 'Request a new code for this email address.');
        }

        if ((int) ($pending['expires_at'] ?? 0) < now()->timestamp) {
            $request->session()->forget(self::TAC_SESSION_KEY);
            return $this->validationError('code', 'This code has expired. Request a new one.');
        }

        $attempts = (int) ($pending['attempts'] ?? 0) + 1;
        $pending['attempts'] = $attempts;
        $request->session()->put(self::TAC_SESSION_KEY, $pending);

        if ($attempts > 5) {
            $request->session()->forget(self::TAC_SESSION_KEY);
            return $this->validationError('code', 'Too many attempts. Request a new code.');
        }

        if (! hash_equals((string) $pending['code_hash'], hash('sha256', $validated['code']))) {
            return $this->validationError('code', 'The verification code is incorrect.');
        }

        $request->session()->put('journey_vetting_verified_email', $email);
        $request->session()->forget(self::TAC_SESSION_KEY);

        return response()->json(['message' => 'Your email has been verified.']);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'min:2', 'max:150'],
            'email' => ['required', 'email:rfc', 'max:254'],
            'phone' => ['required', 'string', 'max:30'],
            'identity_type' => ['required', Rule::in(['nric', 'passport'])],
            'identity_number' => ['required', 'string', 'max:20'],
            'education_level' => ['required', Rule::in(['secondary', 'certificate_diploma', 'bachelors', 'postgraduate', 'other', 'prefer_not'])],
            'employment_status' => ['required', Rule::in(['employed', 'self_employed', 'not_employed', 'homemaker', 'retired', 'student', 'prefer_not'])],
            'monthly_income_range' => ['required', Rule::in(['none', 'under_2000', '2000_3999', '4000_5999', '6000_9999', '10000_plus', 'prefer_not'])],
            'preferred_language' => ['required', Rule::in(['english', 'bahasa_malaysia', 'mandarin', 'tamil', 'other'])],
            'court_experience' => ['required', Rule::in(['none', 'some', 'regular'])],
            'separation_status' => ['required', Rule::in(['not_separated', 'separated_same_home', 'separated_apart', 'uncertain'])],
            'separation_duration' => ['required', Rule::in(['not_applicable', 'under_3_months', '3_6_months', '7_12_months', '1_2_years', 'over_2_years'])],
            'divorce_stage' => ['required', Rule::in(['exploring', 'discussing', 'agreed', 'ready_documents', 'papers_filed', 'court_stage'])],
            'papers_filed' => ['required', Rule::in(['no', 'yes_joint', 'yes_single', 'unsure'])],
            'legal_document_confidence' => ['required', 'integer', 'between:1,5'],
            'support_needs' => ['nullable', 'string', 'max:1000'],
            'agreement_status' => ['required', Rule::in(['agree', 'unsure'])],
            'selected_topics' => ['required', 'array', 'min:1'],
            'selected_topics.*' => ['distinct', Rule::in(['children', 'property', 'maintenance', 'simple'])],
            'privacy_consent' => ['accepted'],
        ]);

        $email = Str::lower($validated['email']);
        if (! hash_equals((string) $request->session()->get('journey_vetting_verified_email'), $email)) {
            return $this->validationError('email', 'Verify this email address before submitting the form.');
        }

        if (in_array('simple', $validated['selected_topics'], true) && count($validated['selected_topics']) > 1) {
            return $this->validationError('selected_topics', 'Simple arrangements cannot be combined with other topics.');
        }

        $phone = PhoneNumber::normalize($validated['phone']);
        if ($phone === null) {
            return $this->validationError('phone', 'Enter a valid mobile number including its country code.');
        }

        if (JourneyVettingSubmission::where('email', $email)->exists()) {
            return $this->validationError('email', 'A submission has already been received for this email address.');
        }

        if (JourneyVettingSubmission::where('phone', $phone)->exists()) {
            return $this->validationError('phone', 'A submission has already been received for this mobile number.');
        }

        $identityNumber = strtoupper(trim($validated['identity_number']));
        if ($validated['identity_type'] === 'nric') {
            $digits = preg_replace('/\D/', '', $identityNumber);
            if (! preg_match('/^\d{12}$/', $digits)) {
                return $this->validationError('identity_number', 'Enter a valid 12-digit Malaysian NRIC number.');
            }
            $identityNumber = substr($digits, 0, 6).'-'.substr($digits, 6, 2).'-'.substr($digits, 8, 4);
        } elseif (! preg_match('/^[A-Z0-9]{6,20}$/', $identityNumber)) {
            return $this->validationError('identity_number', 'Enter a valid passport number using 6 to 20 letters or numbers.');
        }

        $submission = JourneyVettingSubmission::create([
            ...$validated,
            'reference' => 'LD-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
            'email' => $email,
            'phone' => $phone,
            'identity_number' => $identityNumber,
            'email_verified_at' => now(),
            'submitted_at' => now(),
            'status' => 'pending_review',
        ]);

        $request->session()->forget('journey_vetting_verified_email');

        return response()->json([
            'message' => 'Your details have been submitted for review.',
            'reference' => $submission->reference,
        ], 201);
    }

    private function validationError(string $field, string $message): JsonResponse
    {
        return response()->json([
            'message' => 'Please check the information provided.',
            'errors' => [$field => [$message]],
        ], 422);
    }
}
