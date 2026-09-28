<!DOCTYPE html>
<html lang="en">
<head>
    @include('partials.google-tag')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Learn how a Joint Petition divorce works in Malaysia and apply to be assessed for subsidised legal assistance through counsel engaged by our organisation.">
    <meta name="theme-color" content="#12385f">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Subsidised Joint Petition Support — LegalDIY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <header class="site-header" data-header>
        <div class="nav-shell">
            <a class="brand" href="#top" aria-label="LegalDIY home">
                <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo-new.png') }}" alt=""></span>
                <span class="brand-name">LEGAL <strong>DIY</strong></span>
            </a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
                <span class="sr-only">Open navigation</span><span></span><span></span><span></span>
            </button>
            <nav class="primary-nav" id="primary-navigation" aria-label="Primary navigation" data-navigation>
                <a href="#your-journey">Your Journey</a><a href="#topics">Learn</a><a href="#cost">Cost</a><a href="#about">About</a>
                <a class="button button-small" href="{{ route('journey') }}">Apply for Support</a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <section class="hero hero-subsidy" id="top">
            <div class="hero-glow hero-glow-one" aria-hidden="true"></div><div class="hero-glow hero-glow-two" aria-hidden="true"></div>
            <div class="section-shell hero-grid">
                <div class="hero-copy reveal">
                    <p class="eyebrow"><span></span>Subsidised Joint Petition support</p>
                    <h1>Affordable legal support, <em>whatever your income.</em></h1>
                    <p class="hero-lead">Applications are open to all income levels—there is no maximum income limit for applying. If approved, you will pay no more than <strong>RM 2,000.00 in professional legal fees</strong>, and our organisation will subsidise the remaining legal fees.</p>
                    <p class="hero-support">Every application is assessed individually. Court filing charges are separate, and approval is not guaranteed.</p>
                    <div class="button-row">
                        <a class="button" href="{{ route('journey') }}">Apply for an Assessment <span aria-hidden="true">→</span></a>
                        <a class="text-link" href="#topics">Explore Free Legal Guides <span aria-hidden="true">↘</span></a>
                    </div>
                    <p class="hero-note"><span aria-hidden="true">✓</span> Free legal education is available to everyone.</p>
                </div>
                <div class="hero-visual reveal reveal-delay" aria-label="Apply, be assessed, and receive support if approved">
                    <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
                    <div class="journey-compass"><span>YOUR APPLICATION</span><strong>Apply</strong><i></i><strong>Assessment</strong><i></i><strong>Support</strong></div>
                    <div class="visual-card visual-card-top"><b>✓</b><p>No income ceiling to apply</p></div>
                    <div class="visual-card visual-card-bottom"><b>RM</b><p>Legal fees capped if approved</p></div>
                </div>
            </div>
        </section>

        <section class="big-picture section-pad" id="your-journey">
            <div class="section-shell personal-journey-layout">
                <div class="section-heading reveal">
                    <p class="eyebrow"><span></span>Your starting point</p>
                    <h2>Begin with your <em>personal journey.</em></h2>
                    <p>Understand what matters to both of you and identify the arrangements you may need to discuss before preparing a Joint Petition.</p>
                </div>
                <article class="journey-card personal-journey-card reveal reveal-delay">
                        <div class="card-number">01</div><div class="card-icon people-icon" aria-hidden="true"><span></span><span></span></div>
                        <p class="card-label">Your Personal Journey</p><h3>Can both of you reach an agreement?</h3>
                        <p>Use the guided assessment to identify the topics you may need to work through together.</p>
                        <ul class="topic-list"><li>Agreement to divorce</li><li>Children</li><li>Property</li><li>Maintenance</li></ul>
                        <a class="card-link" href="{{ route('journey') }}">Explore Your Personal Journey <span>→</span></a>
                </article>
            </div>
        </section>

        <section class="topics-section section-pad" id="topics">
            <div class="section-shell">
                <div class="section-heading split-heading reveal"><div><p class="eyebrow"><span></span>Knowledge and guidance</p><h2>What do we need to <em>work out?</em></h2></div><p>Understand the topics that commonly matter in a joint divorce, in plain language. Open any topic to learn about the practical questions worth discussing.</p></div>
                <div class="topic-grid">
                    <article class="topic-card reveal">
                        <div class="topic-top"><span class="line-icon" aria-hidden="true">◌</span></div>
                        <p class="topic-number">01</p><h3>Children &amp; Child Maintenance</h3><p class="topic-question">What arrangements may be needed for your children?</p><p>Learn about care arrangements, time with each parent, schooling, expenses, and financial support for children.</p><a href="{{ route('knowledge.show', 'children-maintenance') }}">Learn more <span>→</span></a>
                    </article>
                    <article class="topic-card reveal reveal-delay">
                        <div class="topic-top"><span class="line-icon" aria-hidden="true">◇</span></div>
                        <p class="topic-number">02</p><h3>Property</h3><p class="topic-question">How will shared assets be handled?</p><p>Understand the matrimonial home, other property, financing, ownership, transfer, sale, and division of proceeds.</p><a href="{{ route('knowledge.show', 'property') }}">Learn more <span>→</span></a>
                    </article>
                    <article class="topic-card reveal">
                        <div class="topic-top"><span class="line-icon" aria-hidden="true">◎</span></div>
                        <p class="topic-number">03</p><h3>Alimony</h3><p class="topic-question">Will either spouse provide ongoing financial support?</p><p>Learn what couples commonly consider when discussing alimony or spousal maintenance.</p><a href="{{ route('knowledge.show', 'alimony') }}">Learn more <span>→</span></a>
                    </article>
                    <article class="topic-card simple-card reveal reveal-delay">
                        <div class="topic-top"><span class="line-icon" aria-hidden="true">§</span></div>
                        <p class="topic-number">04</p><h3>Joint Petition &amp; Court Process</h3><p class="topic-question">What happens after both spouses agree?</p><p>See how agreed arrangements become documents and move through filing, the hearing, and finalisation.</p><a href="{{ route('knowledge.show', 'joint-petition') }}">Learn more <span>→</span></a>
                    </article>
                </div>
                <blockquote class="scenario-quote reveal">“These are the things we need to talk about.”</blockquote>
            </div>
        </section>

        <section class="cost-section section-pad" id="cost">
            <div class="section-shell">
                <div class="section-heading centered reveal"><p class="eyebrow"><span></span>Subsidised legal support</p><h2>Joint Petition <em>Divorce Cost</em></h2><p>Applications are open to people at all income levels—there is no maximum income limit for applying. Every application is assessed individually. <strong>If approved, you will pay no more than RM 2,000.00 in professional legal fees, excluding court filing charges.</strong> Our organisation will subsidise the remaining legal fees.</p></div>
                <div class="cost-calculator cost-calculator-simple reveal">
                    <aside class="cost-summary" aria-live="polite">
                        <div class="payment-card-grid">
                        <article class="payment-card payment-card-service">
                            <div class="payment-card-head"><span>Your legal-fee contribution</span><strong>If approved</strong></div>
                            <b>Maximum RM 2,000.00</b>
                            <p class="support-qualification"><strong>No income ceiling</strong> People from all income levels may apply. Support is granted following an individual eligibility assessment, and our organisation subsidises the remaining legal fees for approved applicants.</p>
                        </article>
                        <article class="payment-card payment-card-court">
                            <div class="payment-card-head"><span>Paid by filing stage</span><strong>Court-paper charges</strong></div>
                            <p class="payment-timing"><strong>Paid progressively</strong> Each charge is paid only when the relevant document is filed.</p>
                            <ul><li><span>Petisyen Perceraian Bersama</span><b>RM160.00</b></li><li><span>Afidavit</span><b>RM16.00</b></li><li><span>Penyata Kanak-Kanak<br><small>Only where children are involved</small></span><b>RM16.00</b></li><li><span>Decree Nisi</span><b>RM300.00</b></li><li><span>Perintah</span><b>RM300.00</b></li><li><span>Notis Permohonan Menjadikan Decree Nisi Mutlak</span><b>RM40.00</b></li><li><span>Sijil Menjadikan Decree Nisi Mutlak</span><b>RM40.00</b></li></ul>
                            <div class="payment-card-subtotal"><span>Estimated total paid across the filing stages</span><b>RM856.00–RM872.00</b></div>
                        </article>
                        </div>
                        <div class="cost-grand-total"><div><span>Maximum estimated amount you pay</span><small>RM 2,000 contribution + applicable filing charges</small></div><strong>RM 2,856–RM 2,872</strong></div>
                        <a class="button" href="{{ route('journey') }}">Apply for Subsidised Legal Support <span>→</span></a>
                    </aside>
                </div>
                <p class="cost-note reveal"><strong>About this support:</strong> People from all income levels may apply. Approval is based on an individual eligibility assessment and is not guaranteed. The RM 2,000.00 limit applies only to professional legal fees. Court-paper charges are paid when each relevant document is filed—not together upfront. The Penyata Kanak-Kanak charge applies only where children are involved. Exceptional applications, contested issues, valuations, transfers, taxes, translations, revised Court charges, and other third-party costs are separate.</p>
            </div>
        </section>

        <section class="inquiry-section section-pad" id="inquiry">
            <div class="section-shell inquiry-shell">
                <div class="section-heading reveal"><p class="eyebrow"><span></span>Have a question?</p><h2>Contact us <em>now.</em></h2><p>Complete the form below. When you click Contact Us Now, we will send a six-digit TAC to verify your email before submitting your enquiry.</p></div>
                <form class="inquiry-form reveal" data-inquiry-form data-send-url="{{ route('inquiry.tac.send') }}" data-verify-url="{{ route('inquiry.tac.verify') }}" data-submit-url="{{ route('inquiry.submit') }}" novalidate>
                    <div class="journey-form-message" data-inquiry-message hidden></div>
                    <div data-inquiry-stage="details">
                        <label><span>Full name</span><input type="text" name="full_name" autocomplete="name" maxlength="150" required></label>
                        <label><span>Email address</span><input type="email" name="email" autocomplete="email" maxlength="254" required></label>
                        <input type="hidden" name="topic" value="other">
                        <label><span>Your enquiry</span><textarea name="message" rows="5" minlength="10" maxlength="2000" required placeholder="Tell us what you would like help with."></textarea><small>10–2,000 characters</small></label>
                        <label class="inquiry-consent"><input type="checkbox" name="privacy_consent" value="1" required><span>I consent to LegalDIY storing these details to review and respond to my enquiry.</span></label>
                        <button class="button" type="button" data-inquiry-send>Contact Us Now <span>→</span></button>
                    </div>
                    <div data-inquiry-stage="verify" hidden>
                        <div class="inquiry-verify-heading"><span aria-hidden="true">✉</span><div><strong>Check your email</strong><p class="inquiry-stage-note">Enter the six-digit TAC sent to <b data-inquiry-email></b> to submit your enquiry.</p></div></div>
                        <div class="tac-boxes" data-inquiry-tac aria-label="Six-digit verification code">@for ($digit = 1; $digit <= 6; $digit++)<input type="text" inputmode="numeric" autocomplete="{{ $digit === 1 ? 'one-time-code' : 'off' }}" pattern="[0-9]" maxlength="1" required aria-label="Verification code digit {{ $digit }}" data-inquiry-digit>@endfor</div>
                        <div class="inquiry-button-row"><button class="button button-outline" type="button" data-inquiry-change>Edit details</button><button class="button" type="submit">Verify &amp; Submit <span>→</span></button></div>
                        <button class="quiet-button inquiry-resend" type="button" data-inquiry-resend>Send another TAC</button>
                    </div>
                    <div class="inquiry-success" data-inquiry-stage="success" hidden><span>✓</span><h3>Enquiry received.</h3><p>Thank you. Your reference is <strong data-inquiry-reference></strong>. Keep it for future correspondence.</p></div>
                </form>
            </div>
        </section>

        <section class="method-section section-pad" id="about">
            <div class="section-shell"><div class="section-heading centered reveal"><p class="eyebrow light"><span></span>How LegalDIY fits in</p><h2>Understand. Agree. <em>Prepare. Proceed.</em></h2><p>LegalDIY is organised around your actual journey — not a library of legal topics.</p></div>
                <div class="method-flow reveal"><div><span>01</span><strong>Understand</strong><small>Know what matters.</small></div><i>→</i><div><span>02</span><strong>Discuss</strong><small>Explore your situation.</small></div><i>→</i><div><span>03</span><strong>Agree</strong><small>Reach arrangements.</small></div><i>→</i><div><span>04</span><strong>Prepare</strong><small>Build the information.</small></div><i>→</i><div><span>05</span><strong>Proceed</strong><small>Follow each step.</small></div></div>
                <div class="center-action"><a class="button button-teal" href="{{ route('journey') }}">Apply for Subsidised Legal Support <span>→</span></a></div>
            </div>
        </section>

    </main>

    <footer class="site-footer"><div class="section-shell footer-grid"><div><a class="brand footer-brand" href="#top"><span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo-new.png') }}" alt=""></span><span class="brand-name">LEGAL <strong>DIY</strong></span></a><p>Learn. Empower. Take action.</p></div><div class="footer-links"><a href="#your-journey">Your Journey</a><a href="#topics">Learn</a><a href="#cost">Cost</a><a href="#about">About</a></div><p class="disclaimer">LegalDIY provides general legal information, not legal advice. Your circumstances may require advice from a qualified lawyer.</p></div><div class="section-shell footer-bottom"><span>© {{ date('Y') }} LegalDIY</span><span>Legal education for everyone, everywhere.</span></div></footer>
</body>
</html>
