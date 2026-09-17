<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CaseAccessTacMail extends Mailable
{
    use Queueable, SerializesModels;
    public function __construct(public readonly string $code, public readonly string $recipientName) {}
    public function build(): self
    {
        return $this->mailer('brevo')->from(config('mail.mailers.brevo.from.address'), config('mail.mailers.brevo.from.name'))
            ->subject('Your LegalDIY secure access code')->view('emails.case-access-tac');
    }
}
