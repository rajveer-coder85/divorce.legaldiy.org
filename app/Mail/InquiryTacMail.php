<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryTacMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public readonly string $code, public readonly ?string $recipientName = null) {}
    public function build(): self
    {
        return $this->mailer('brevo')->from(config('mail.mailers.brevo.from.address'), config('mail.mailers.brevo.from.name'))
            ->subject('Verify your LegalDIY enquiry')->view('emails.inquiry-tac');
    }
}
