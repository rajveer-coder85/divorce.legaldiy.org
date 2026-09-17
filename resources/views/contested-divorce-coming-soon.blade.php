<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LegalDIY limited contested-divorce support information is coming soon.">
    <meta name="theme-color" content="#12385f"><title>Contested Divorce Support — Coming Soon</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="coming-soon-page">
    <main class="coming-soon-main">
        <article class="coming-soon-card">
            <div class="knowledge-title-row"><p class="eyebrow"><span></span>Limited contested-divorce support</p><a class="knowledge-home" href="{{ url('/') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 10 8-7 8 7v10h-6v-6h-4v6H4Z"/></svg><span>Home</span></a></div>
            <div class="coming-soon-icon" aria-hidden="true">§</div>
            <h1>Detailed guidance is coming soon.</h1>
            <p>We are preparing information about the limited contested-divorce matters our organisation may assess, including certain missing-spouse cases. Support is not automatic and depends on eligibility, the facts, and the current scope of the programme.</p>
            <p>Cases involving a foreign spouse are not currently supported. If you have a question about eligibility, send us an enquiry and our team can review it.</p>
            <div class="knowledge-actions"><a class="button button-outline" href="{{ url('/') }}">Return home</a><button class="button" type="button" data-open-inquiry-modal>Send an enquiry <span>→</span></button></div>
        </article>
    </main>
    <dialog class="inquiry-modal" data-inquiry-modal aria-labelledby="inquiry-modal-title">
        <div class="inquiry-modal-head"><div><small>Limited contested-divorce support</small><h2 id="inquiry-modal-title">Send us an enquiry</h2></div><button type="button" data-close-inquiry-modal aria-label="Close enquiry form"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button></div>
        <form class="inquiry-form inquiry-modal-form" data-inquiry-form data-send-url="{{ route('inquiry.tac.send') }}" data-verify-url="{{ route('inquiry.tac.verify') }}" data-submit-url="{{ route('inquiry.submit') }}" novalidate>
            <div class="journey-form-message" data-inquiry-message hidden></div>
            <div data-inquiry-stage="details"><label><span>Full name</span><input type="text" name="full_name" autocomplete="name" maxlength="150" required></label><label><span>Email address</span><input type="email" name="email" autocomplete="email" maxlength="254" required></label><input type="hidden" name="topic" value="other"><label><span>Your enquiry</span><textarea name="message" rows="5" minlength="10" maxlength="2000" required placeholder="Tell us about your situation and what support you need."></textarea><small>10–2,000 characters</small></label><label class="inquiry-consent"><input type="checkbox" name="privacy_consent" value="1" required><span>I consent to LegalDIY storing these details to review and respond to my enquiry.</span></label><button class="button" type="button" data-inquiry-send>Contact Us Now <span>→</span></button></div>
            <div data-inquiry-stage="verify" hidden><div class="inquiry-verify-heading"><span aria-hidden="true">✉</span><div><strong>Check your email</strong><p class="inquiry-stage-note">Enter the six-digit TAC sent to <b data-inquiry-email></b> to submit your enquiry.</p></div></div><div class="tac-boxes" data-inquiry-tac aria-label="Six-digit verification code">@for ($digit = 1; $digit <= 6; $digit++)<input type="text" inputmode="numeric" autocomplete="{{ $digit === 1 ? 'one-time-code' : 'off' }}" pattern="[0-9]" maxlength="1" required aria-label="Verification code digit {{ $digit }}" data-inquiry-digit>@endfor</div><div class="inquiry-button-row"><button class="button button-outline" type="button" data-inquiry-change>Edit details</button><button class="button" type="submit">Verify &amp; Submit <span>→</span></button></div><button class="quiet-button inquiry-resend" type="button" data-inquiry-resend>Send another TAC</button></div>
            <div class="inquiry-success" data-inquiry-stage="success" hidden><span>✓</span><h3>Enquiry received.</h3><p>Thank you. Your reference is <strong data-inquiry-reference></strong>.</p><button class="button button-outline" type="button" data-close-inquiry-modal>Close</button></div>
        </form>
    </dialog>
    <footer class="knowledge-footer">LegalDIY provides general legal information, not legal advice.</footer>
</body>
</html>
