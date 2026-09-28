<?php

namespace App\Http\Controllers;

use App\Mail\InquiryReplyMail;
use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InquiryDashboardController extends Controller
{
    public function show(Inquiry $inquiry): View
    {
        $inquiry->load(['communications' => fn ($query) => $query->oldest('sent_at'), 'communications.user']);

        return view('inquiry-dashboard-show', compact('inquiry'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['new', 'ongoing', 'completed'])]]);
        $inquiry->update($data);

        return back()->with('status_updated', true);
    }

    public function sendReply(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate([
            'subject' => ['required', 'string', 'max:200'],
            'body' => ['required', 'string', 'min:2', 'max:10000'],
        ]);

        try {
            Mail::mailer('brevo')->to($inquiry->email)->send(new InquiryReplyMail(
                $inquiry->full_name,
                $inquiry->reference,
                $data['subject'],
                $data['body'],
            ));
        } catch (\Throwable $exception) {
            report($exception);

            return back()->withInput()->withErrors(['reply' => 'The email could not be sent through Brevo. Please try again.']);
        }

        $inquiry->communications()->create([
            'user_id' => $request->user()->id,
            'direction' => 'outbound',
            'from_email' => config('mail.mailers.brevo.from.address'),
            'to_email' => $inquiry->email,
            'subject' => $data['subject'],
            'body' => $data['body'],
            'sent_at' => now(),
        ]);
        $inquiry->update(['status' => 'ongoing']);

        Log::info('Inquiry reply sent', ['inquiry' => $inquiry->reference, 'administrator' => $request->user()->id]);

        return back()->with('reply_sent', true);
    }
}
