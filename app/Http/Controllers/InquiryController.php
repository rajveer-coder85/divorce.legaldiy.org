<?php

namespace App\Http\Controllers;

use App\Mail\InquiryTacMail;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class InquiryController extends Controller
{
    private const TAC_KEY = 'inquiry_tac';
    private const VERIFIED_KEY = 'inquiry_verified_email';

    public function sendTac(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email:rfc', 'max:254'], 'full_name' => ['required', 'string', 'min:2', 'max:150']]);
        $pending = $request->session()->get(self::TAC_KEY, []);
        if (($pending['resend_available_at'] ?? 0) > now()->timestamp) {
            return response()->json(['message' => 'Please wait before requesting another code.', 'retry_after' => $pending['resend_available_at'] - now()->timestamp], 429);
        }

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $email = Str::lower($data['email']);
        try {
            Mail::mailer('brevo')->to($email)->send(new InquiryTacMail($code, $data['full_name']));
        } catch (\Throwable $exception) {
            report($exception);
            return response()->json(['message' => 'We could not send the verification code. Please try again shortly.'], 503);
        }

        $request->session()->put(self::TAC_KEY, ['email' => $email, 'code_hash' => hash('sha256', $code), 'expires_at' => now()->addMinutes(10)->timestamp, 'resend_available_at' => now()->addSeconds(60)->timestamp, 'attempts' => 0]);
        $request->session()->forget(self::VERIFIED_KEY);
        return response()->json(['message' => 'A six-digit verification code has been sent.', 'resend_after' => 60]);
    }

    public function verifyTac(Request $request): JsonResponse
    {
        $data = $request->validate(['email' => ['required', 'email:rfc', 'max:254'], 'code' => ['required', 'digits:6']]);
        $pending = $request->session()->get(self::TAC_KEY);
        $email = Str::lower($data['email']);
        if (! is_array($pending) || ! hash_equals((string) ($pending['email'] ?? ''), $email)) return $this->error('code', 'Request a new code for this email address.');
        if (($pending['expires_at'] ?? 0) < now()->timestamp) { $request->session()->forget(self::TAC_KEY); return $this->error('code', 'This code has expired. Request a new one.'); }
        $pending['attempts'] = ($pending['attempts'] ?? 0) + 1;
        $request->session()->put(self::TAC_KEY, $pending);
        if ($pending['attempts'] > 5) { $request->session()->forget(self::TAC_KEY); return $this->error('code', 'Too many attempts. Request a new code.'); }
        if (! hash_equals($pending['code_hash'], hash('sha256', $data['code']))) return $this->error('code', 'The verification code is incorrect.');
        $request->session()->put(self::VERIFIED_KEY, $email); $request->session()->forget(self::TAC_KEY);
        return response()->json(['message' => 'Your email has been verified.']);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'min:2', 'max:150'], 'email' => ['required', 'email:rfc', 'max:254'],
            'topic' => ['required', Rule::in(['getting_started', 'children', 'property', 'spousal_maintenance', 'court_process', 'costs', 'technical', 'other'])],
            'message' => ['required', 'string', 'min:10', 'max:2000'], 'privacy_consent' => ['accepted'],
        ]);
        $email = Str::lower($data['email']);
        if (! hash_equals((string) $request->session()->get(self::VERIFIED_KEY), $email)) return $this->error('email', 'Verify this email address before sending your enquiry.');
        $inquiry = Inquiry::create([...$data, 'reference' => 'INQ-'.now()->format('ymd').'-'.Str::upper(Str::random(6)), 'email' => $email, 'email_verified_at' => now(), 'submitted_at' => now(), 'status' => 'new']);
        $request->session()->forget(self::VERIFIED_KEY);
        return response()->json(['message' => 'Your enquiry has been received.', 'reference' => $inquiry->reference], 201);
    }

    private function error(string $field, string $message): JsonResponse
    {
        return response()->json(['message' => 'Please check the information provided.', 'errors' => [$field => [$message]]], 422);
    }
}
