<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Start your guided LegalDIY Joint Petition journey.">
    <meta name="theme-color" content="#12385f">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Start Your Journey — LegalDIY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="journey-page">
    <header class="journey-header">
        <a class="journey-back" href="{{ url('/') }}" aria-label="Back to homepage"><span aria-hidden="true">←</span> Back</a>
        <a class="brand" href="{{ url('/') }}" aria-label="LegalDIY home">
            <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo.png') }}" alt=""></span>
            <span class="brand-name">LEGAL <strong>DIY</strong></span>
        </a>
        <span class="journey-save">Secure vetting form</span>
    </header>

    <main class="journey-main" data-journey-flow data-tac-send-url="{{ route('journey.tac.send') }}" data-tac-verify-url="{{ route('journey.tac.verify') }}" data-submit-url="{{ route('journey.submit') }}">
        <div class="journey-progress" aria-label="Journey progress">
            <div class="journey-progress-copy"><span data-journey-step-label>Step 1 of 6</span><b data-journey-percent>17%</b></div>
            <div class="journey-progress-track"><i data-journey-progress></i></div>
        </div>

        <div class="journey-form-message" data-form-message role="status" aria-live="polite" hidden></div>

        <section class="journey-step active" data-journey-step="1">
            <p class="eyebrow"><span></span>Let’s start with the basics</p>
            <h1>Do both of you want to divorce?</h1>
            <p class="journey-intro">A Joint Petition requires both spouses to choose to proceed together.</p>
            <div class="journey-options">
                <button type="button" class="journey-option" data-journey-answer="agree" aria-pressed="false">
                    <span class="journey-option-icon">✓</span><span><strong>Yes, we both agree</strong><small>We both want to proceed with the divorce.</small></span><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span>
                </button>
                <button type="button" class="journey-option" data-journey-answer="unsure" aria-pressed="false">
                    <span class="journey-option-icon">…</span><span><strong>Not yet</strong><small>One of us is unsure or has not agreed.</small></span><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span>
                </button>
            </div>
            <aside class="journey-help"><span>i</span><p><strong>You do not need to know the law first.</strong> We will help you identify the practical matters worth discussing.</p></aside>
        </section>

        <section class="journey-step" data-journey-step="2" hidden>
            <p class="eyebrow"><span></span>About you</p>
            <h1>Tell us who you are.</h1>
            <p class="journey-intro">We use these details to vet your submission and contact you about the next steps.</p>

            <div class="journey-form-grid">
                <label class="journey-field journey-field-wide">
                    <span>Full legal name</span>
                    <input type="text" name="full_name" autocomplete="name" maxlength="150" required placeholder="As shown on your NRIC or passport">
                    <small>Please use your real legal name. It may be used in future official correspondence.</small>
                </label>
                <label class="journey-field journey-field-wide">
                    <span>Email address</span>
                    <input type="email" name="email" autocomplete="email" maxlength="254" required placeholder="you@example.com">
                    <small>We will send a six-digit TAC to verify this address.</small>
                </label>
                <label class="journey-field journey-field-wide">
                    <span>Mobile number</span>
                    <input type="tel" name="phone_display" autocomplete="tel-national" maxlength="30" required placeholder="012 345 6789" data-phone-input>
                    <input type="hidden" name="phone" data-phone-e164>
                    <small>Select your country code and enter a valid mobile number. Malaysia is selected by default.</small>
                </label>

                <fieldset class="journey-field journey-field-wide identity-fieldset">
                    <legend>Identification document</legend>
                    <div class="identity-switch" data-identity-switch>
                        <label><input type="radio" name="identity_type" value="nric" checked><span>Malaysian NRIC</span></label>
                        <label><input type="radio" name="identity_type" value="passport"><span>Use passport instead</span></label>
                    </div>
                </fieldset>
                <label class="journey-field journey-field-wide">
                    <span data-identity-label>NRIC number</span>
                    <input type="text" name="identity_number" autocomplete="off" maxlength="20" required inputmode="numeric" placeholder="e.g. 900101-14-5678" data-identity-number>
                    <small data-identity-help>Enter the 12 digits shown on your Malaysian identity card. We encrypt this number when it is saved.</small>
                </label>
            </div>

            <div class="journey-privacy-note"><span aria-hidden="true">⌁</span><p><strong>Your identity number is sensitive.</strong> It is encrypted when stored and is collected only for identity checking during vetting.</p></div>
            <div class="journey-actions"><button class="button button-outline" type="button" data-journey-previous>Back</button><button class="button" type="button" data-send-tac>Send Email TAC <span>→</span></button></div>
        </section>

        <section class="journey-step" data-journey-step="3" hidden>
            <p class="eyebrow"><span></span>Verify your email</p>
            <h1>Enter your six-digit TAC.</h1>
            <p class="journey-intro">We sent the code to <strong data-tac-email></strong>. It expires in 10 minutes.</p>
            <fieldset class="journey-field tac-field">
                <legend>Email verification code</legend>
                <div class="tac-boxes" data-tac-boxes>
                    @foreach (range(1, 6) as $digit)
                        <input type="text" inputmode="numeric" autocomplete="{{ $digit === 1 ? 'one-time-code' : 'off' }}" pattern="[0-9]" maxlength="1" required aria-label="Verification code digit {{ $digit }}" data-tac-digit>
                    @endforeach
                </div>
                <small>Enter the six digits from your email.</small>
            </fieldset>
            <div class="journey-resend"><button type="button" class="quiet-button" data-resend-tac disabled>Send another code <span data-resend-countdown>(60s)</span></button></div>
            <div class="journey-actions"><button class="button button-outline" type="button" data-journey-previous>Change details</button><button class="button" type="button" data-verify-tac>Verify &amp; Continue <span>→</span></button></div>
        </section>

        <section class="journey-step" data-journey-step="4" hidden>
            <p class="eyebrow"><span></span>Your background</p>
            <h1>Help us tailor the guidance.</h1>
            <p class="journey-intro">These questions help our team understand how much explanation and support may be useful.</p>
            <div class="journey-form-grid">
                <label class="journey-field">
                    <span>Highest education level</span>
                    <select name="education_level" required><option value="">Select one</option><option value="secondary">Secondary school</option><option value="certificate_diploma">Certificate or diploma</option><option value="bachelors">Bachelor’s degree</option><option value="postgraduate">Postgraduate degree</option><option value="other">Other</option><option value="prefer_not">Prefer not to say</option></select>
                </label>
                <label class="journey-field">
                    <span>Employment status</span>
                    <select name="employment_status" required><option value="">Select one</option><option value="employed">Employed</option><option value="self_employed">Self-employed</option><option value="not_employed">Not currently employed</option><option value="homemaker">Homemaker</option><option value="retired">Retired</option><option value="student">Student</option><option value="prefer_not">Prefer not to say</option></select>
                </label>
                <label class="journey-field">
                    <span>Your approximate monthly income</span>
                    <select name="monthly_income_range" required><option value="">Select one</option><option value="none">No current income</option><option value="under_2000">Below RM2,000</option><option value="2000_3999">RM2,000–RM3,999</option><option value="4000_5999">RM4,000–RM5,999</option><option value="6000_9999">RM6,000–RM9,999</option><option value="10000_plus">RM10,000 or more</option><option value="prefer_not">Prefer not to say</option></select>
                </label>
                <label class="journey-field">
                    <span>Preferred language</span>
                    <select name="preferred_language" required><option value="">Select one</option><option value="english">English</option><option value="bahasa_malaysia">Bahasa Malaysia</option><option value="mandarin">Mandarin</option><option value="tamil">Tamil</option><option value="other">Other</option></select>
                </label>
                <label class="journey-field">
                    <span>Experience with court matters</span>
                    <select name="court_experience" required><option value="">Select one</option><option value="none">None</option><option value="some">Some previous experience</option><option value="regular">Regular experience</option></select>
                </label>
                <label class="journey-field">
                    <span>Are you and your spouse already separated?</span>
                    <select name="separation_status" required><option value="">Select one</option><option value="not_separated">No, we still consider ourselves together</option><option value="separated_same_home">Yes, but living in the same home</option><option value="separated_apart">Yes, living separately</option><option value="uncertain">Not sure</option></select>
                </label>
                <label class="journey-field">
                    <span>How long have you been separated?</span>
                    <select name="separation_duration" required><option value="">Select one</option><option value="not_applicable">Not applicable</option><option value="under_3_months">Less than 3 months</option><option value="3_6_months">3–6 months</option><option value="7_12_months">7–12 months</option><option value="1_2_years">1–2 years</option><option value="over_2_years">More than 2 years</option></select>
                </label>
                <label class="journey-field">
                    <span>What stage are you at?</span>
                    <select name="divorce_stage" required><option value="">Select one</option><option value="exploring">Exploring whether to divorce</option><option value="discussing">Discussing arrangements</option><option value="agreed">Both agree and working out details</option><option value="ready_documents">Ready to prepare documents</option><option value="papers_filed">Divorce papers have already been filed</option><option value="court_stage">A Court date or order already exists</option></select>
                </label>
                <label class="journey-field">
                    <span>Have any divorce papers already been filed?</span>
                    <select name="papers_filed" required><option value="">Select one</option><option value="no">No</option><option value="yes_joint">Yes, a Joint Petition</option><option value="yes_single">Yes, a single or contested petition</option><option value="unsure">Not sure</option></select>
                </label>
                <fieldset class="journey-field journey-field-wide confidence-field">
                    <legend>How confident are you reading official or legal documents?</legend>
                    <div class="confidence-options" role="radiogroup" aria-label="Legal document confidence">
                        @foreach ([1 => 'Not confident', 2 => 'Slightly', 3 => 'Moderately', 4 => 'Confident', 5 => 'Very confident'] as $value => $label)
                            <label><input type="radio" name="legal_document_confidence" value="{{ $value }}" required><span>{{ $value }}</span><small>{{ $label }}</small></label>
                        @endforeach
                    </div>
                </fieldset>
                <label class="journey-field journey-field-wide">
                    <span>Anything that would help us support you? <em>Optional</em></span>
                    <textarea name="support_needs" rows="4" maxlength="1000" placeholder="For example: accessibility needs, difficulty reading forms, or a preferred way for us to explain the process."></textarea>
                </label>
            </div>
            <p class="journey-draft-note">Your non-sensitive questionnaire choices are saved in a first-party browser cookie for 30 days so you can continue later. Identity numbers, contact details, TAC codes, consent, and free-text notes are never saved in this cookie.</p>
            <div class="journey-actions"><button class="button button-outline" type="button" data-journey-previous>Back</button><button class="button" type="button" data-profile-next>Continue <span>→</span></button></div>
        </section>

        <section class="journey-step" data-journey-step="5" hidden>
            <p class="eyebrow"><span></span>Your situation</p>
            <h1>What applies to your family?</h1>
            <p class="journey-intro">Select everything relevant. You can change these answers later.</p>
            <div class="journey-options journey-topic-options">
                <button type="button" class="journey-option" data-journey-topic="children" aria-pressed="false"><span class="journey-option-icon">◌</span><span><strong>Children</strong><small>Care, access, schooling, expenses, and maintenance.</small></span><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span></button>
                <button type="button" class="journey-option" data-journey-topic="property" aria-pressed="false"><span class="journey-option-icon">◇</span><span><strong>Property</strong><small>Homes, assets, loans, ownership, or sale proceeds.</small></span><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span></button>
                <button type="button" class="journey-option" data-journey-topic="maintenance" aria-pressed="false"><span class="journey-option-icon">◎</span><span><strong>Spousal maintenance</strong><small>Whether financial support is needed and how it may work.</small></span><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span></button>
                <button type="button" class="journey-option" data-journey-topic="simple" aria-pressed="false"><span class="journey-option-icon">✓</span><span><strong>None of these</strong><small>Our arrangements are relatively simple.</small></span><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span></button>
            </div>
            <div class="journey-actions"><button class="button button-outline" type="button" data-journey-previous>Back</button><button class="button" type="button" data-journey-next disabled>Continue <span>→</span></button></div>
        </section>

        <section class="journey-step" data-journey-step="6" hidden>
            <p class="eyebrow"><span></span>Review and submit</p>
            <h1>Check your starting details.</h1>
            <p class="journey-intro">Review this summary before sending the form to our team for vetting.</p>
            <div class="journey-review" data-journey-review></div>
            <label class="consent-check"><input type="checkbox" name="privacy_consent" value="1" required><span class="journey-tick" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></span><span>I confirm these details are accurate and consent to LegalDIY storing them to review my submission and contact me about this journey.</span></label>
            <aside class="journey-help journey-help-ready"><span>✓</span><p><strong>This is not a court filing.</strong> Submitting this vetting form does not start divorce proceedings or create any final agreement.</p></aside>
            <div class="journey-actions"><button class="button button-outline" type="button" data-journey-previous>Back</button><button class="button" type="button" data-submit-vetting>Submit for Review <span>→</span></button></div>
        </section>

        <section class="journey-step journey-success" data-journey-step="success" hidden>
            <div class="success-mark" aria-hidden="true"><svg viewBox="0 0 24 24"><path d="m5 12.5 4.2 4.2L19 7.5"/></svg></div>
            <p class="eyebrow"><span></span>Submission received</p>
            <h1>Your details are ready for review.</h1>
            <p class="journey-intro">Keep this reference number for future correspondence:</p>
            <strong class="submission-reference" data-submission-reference></strong>
            <p class="success-note">Our team can now vet the information you provided. This submission does not begin a court process.</p>
            <a class="button" href="{{ url('/') }}">Return to LegalDIY</a>
        </section>

        <section class="journey-step journey-unsure" data-journey-step="unsure" hidden>
            <p class="eyebrow"><span></span>That’s okay</p><h1>You can still understand what may need discussing.</h1>
            <p class="journey-intro">A Joint Petition is only possible when both spouses agree. For now, you can explore the practical issues and see what the journey would involve.</p>
            <aside class="journey-help"><span>i</span><p>This website provides general information and does not replace advice from a qualified lawyer about your circumstances.</p></aside>
            <div class="journey-actions"><button class="button button-outline" type="button" data-journey-restart>Change answer</button><button class="button" type="button" data-journey-continue-unsure>Continue With Vetting <span>→</span></button></div>
        </section>
    </main>
</body>
</html>
