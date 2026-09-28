<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InquiryReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly string $recipientName,
        public readonly string $reference,
        public readonly string $replySubject,
        public readonly string $replyBody,
    ) {}

    public function build(): self
    {
        return $this->mailer('brevo')
            ->from(config('mail.mailers.brevo.from.address'), config('mail.mailers.brevo.from.name'))
            ->subject($this->replySubject)
            ->view('emails.inquiry-reply');
    }
}
