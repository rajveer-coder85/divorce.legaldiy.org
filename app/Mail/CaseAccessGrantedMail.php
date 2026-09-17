<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;use Illuminate\Mail\Mailable;use Illuminate\Queue\SerializesModels;
class CaseAccessGrantedMail extends Mailable{use Queueable,SerializesModels;public function __construct(public readonly string $recipientName,public readonly string $privateUrl,public readonly bool $firstAccess){}public function build():self{return $this->mailer('brevo')->from(config('mail.mailers.brevo.from.address'),config('mail.mailers.brevo.from.name'))->subject('Your private LegalDIY page is ready')->view('emails.case-access-granted');}}
