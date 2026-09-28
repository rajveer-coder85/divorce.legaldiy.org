<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.google-tag')
    @if ($reference)
        <!-- Event snippet for Submit lead form conversion page -->
        <script>
            gtag('event', 'conversion', {
                'send_to': 'AW-18365827534/_BJcCLq9-_ocEM6TwbVE',
                'value': 1.0,
                'currency': 'MYR',
                'transaction_id': {{ \Illuminate\Support\Js::from($reference) }}
            });
        </script>
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Your LegalDIY evaluation has been submitted for review.">
    <meta name="theme-color" content="#12385f">
    <title>Evaluation Submitted — LegalDIY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="application-thank-you-page">
    <header class="thank-you-header">
        <a class="brand" href="{{ url('/') }}" aria-label="LegalDIY home">
            <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo-new.png') }}" alt=""></span>
            <span class="brand-name">LEGAL <strong>DIY</strong></span>
        </a>
        <span class="thank-you-secure"><span aria-hidden="true">✓</span> Securely submitted</span>
    </header>

    <main class="thank-you-main">
        <section class="thank-you-card" aria-labelledby="thank-you-title">
            <div class="thank-you-check" aria-hidden="true">
                <svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg>
            </div>
            <p class="eyebrow"><span></span>Evaluation received</p>
            <h1 id="thank-you-title">Thank you. Your evaluation is with us.</h1>
            <p class="thank-you-lead">We’ve securely received your information. Our team will review it and contact you about the most suitable next step.</p>

            @if ($reference)
                <div class="thank-you-reference">
                    <span>Your submission reference</span>
                    <strong>{{ $reference }}</strong>
                    <small>Keep this number for future correspondence.</small>
                </div>
            @endif

            <div class="thank-you-next">
                <h2>What happens next?</h2>
                <ol>
                    <li><span>1</span><div><strong>We review your evaluation</strong><small>Our team checks the details and the matters you selected.</small></div></li>
                    <li><span>2</span><div><strong>We get in touch</strong><small>We’ll contact you using the email address or mobile number you provided.</small></div></li>
                    <li><span>3</span><div><strong>You decide how to proceed</strong><small>No court process has started and there is nothing else you need to do right now.</small></div></li>
                </ol>
            </div>

            <div class="thank-you-actions">
                <a class="button" href="{{ url('/') }}">Return to LegalDIY <span aria-hidden="true">→</span></a>
                <p>Need help? <a href="{{ url('/#inquiry') }}">Send us an enquiry</a></p>
            </div>
        </section>
    </main>

    <footer class="thank-you-footer">
        <p>General legal information only. Your submission does not begin court proceedings or create a solicitor-client relationship.</p>
    </footer>
</body>
</html>
