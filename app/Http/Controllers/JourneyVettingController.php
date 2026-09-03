<?php

namespace App\Http\Controllers;

use App\Mail\JourneyTacMail;
use App\Models\JourneyVettingSubmission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class JourneyVettingController extends Controller
{
    private const TAC_SESSION_KEY = 'journey_vetting_tac';

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
            'phone' => ['nullable', 'string', 'max:30', 'regex:/^\+?[0-9][0-9 ()-]{7,24}$/'],
            'identity_type' => ['required', Rule::in(['nric', 'passport'])],
            'identity_number' => ['required', 'string', 'max:20'],
            'education_level' => ['required', Rule::in(['secondary', 'certificate_diploma', 'bachelors', 'postgraduate', 'other', 'prefer_not'])],
            'employment_status' => ['required', Rule::in(['employed', 'self_employed', 'not_employed', 'homemaker', 'retired', 'student', 'prefer_not'])],
            'preferred_language' => ['required', Rule::in(['english', 'bahasa_malaysia', 'mandarin', 'tamil', 'other'])],
            'court_experience' => ['required', Rule::in(['none', 'some', 'regular'])],
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
