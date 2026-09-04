<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="LegalDIY helps Malaysian couples understand, agree, and prepare for the Joint Petition divorce journey.">
    <meta name="theme-color" content="#12385f">
    <title>LegalDIY — Your Joint Petition Journey</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <a class="skip-link" href="#main-content">Skip to content</a>
    <header class="site-header" data-header>
        <div class="nav-shell">
            <a class="brand" href="#top" aria-label="LegalDIY home">
                <span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo.png') }}" alt=""></span>
                <span class="brand-name">LEGAL <strong>DIY</strong></span>
            </a>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-controls="primary-navigation" data-menu-toggle>
                <span class="sr-only">Open navigation</span><span></span><span></span><span></span>
            </button>
            <nav class="primary-nav" id="primary-navigation" aria-label="Primary navigation" data-navigation>
                <a href="#your-journey">Your Journey</a><a href="#court-journey">Court Journey</a><a href="#topics">Learn</a><a href="#about">About</a>
                <a class="button button-small" href="{{ route('journey') }}">Start Your Journey</a>
            </nav>
        </div>
    </header>

    <main id="main-content">
        <section class="hero" id="top">
            <div class="hero-glow hero-glow-one" aria-hidden="true"></div><div class="hero-glow hero-glow-two" aria-hidden="true"></div>
            <div class="section-shell hero-grid">
                <div class="hero-copy reveal">
                    <p class="eyebrow"><span></span>A clearer way forward</p>
                    <h1>Thinking about <em>divorce?</em></h1>
                    <p class="hero-lead">Understand what you and your spouse need to agree on before proceeding with a Joint Petition.</p>
                    <p class="hero-support">LegalDIY guides you through the important decisions first — then explains the court journey that follows.</p>
                    <div class="button-row">
                        <a class="button" href="{{ route('journey') }}">Start Your Journey <span aria-hidden="true">→</span></a>
                        <a class="text-link" href="#court-journey">How a Joint Petition Works <span aria-hidden="true">↘</span></a>
                    </div>
                    <p class="hero-note"><span aria-hidden="true">✓</span> Clear information. No legal jargon.</p>
                </div>
                <div class="hero-visual reveal reveal-delay" aria-label="Your path from questions to a clear way forward">
                    <div class="orbit orbit-one"></div><div class="orbit orbit-two"></div>
                    <div class="journey-compass"><span>YOUR JOURNEY</span><strong>Understand</strong><i></i><strong>Discuss</strong><i></i><strong>Agree</strong></div>
                    <div class="visual-card visual-card-top"><b>01</b><p>Start with your situation</p></div>
                    <div class="visual-card visual-card-bottom"><b>02</b><p>Move forward together</p></div>
                </div>
            </div>
        </section>

        <section class="big-picture section-pad" id="your-journey">
            <div class="section-shell">
                <div class="section-heading centered reveal"><p class="eyebrow"><span></span>The big picture</p><h2>Your journey has <em>two parts.</em></h2><p>First, work out what matters to both of you. Then, understand how your agreement moves through court.</p></div>
                <div class="journey-pair">
                    <article class="journey-card reveal">
                        <div class="card-number">01</div><div class="card-icon people-icon" aria-hidden="true"><span></span><span></span></div>
                        <p class="card-label">Your Personal Journey</p><h3>Can both of you reach an agreement?</h3>
                        <p>Understand and discuss the arrangements that matter to your situation before preparing a Joint Petition.</p>
                        <ul class="topic-list"><li>Agreement to divorce</li><li>Children</li><li>Property</li><li>Maintenance</li></ul>
                        <a class="card-link" href="{{ route('journey') }}">Explore Your Personal Journey <span>→</span></a>
                    </article>
                    <div class="journey-connector" aria-hidden="true"><span>then</span></div>
                    <article class="journey-card journey-card-court reveal reveal-delay">
                        <div class="card-number">02</div><div class="card-icon court-icon" aria-hidden="true"><span></span></div>
                        <p class="card-label">Your Court Journey</p><h3>What happens once you are ready?</h3>
                        <p>See how your agreed arrangements move into document preparation, filing, court attendance, and finalisation.</p>
                        <div class="mini-route" aria-hidden="true"><span>Prepare</span><i></i><span>File</span><i></i><span>Finalise</span></div>
                        <a class="card-link" href="#court-journey">See the Court Journey <span>→</span></a>
                    </article>
                </div>
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

        <section class="transition-section" id="transition"><div class="section-shell transition-inner reveal"><span class="transition-check" aria-hidden="true">→</span><div><p>After both spouses agree</p><h2>The court journey can begin.</h2></div><a class="button button-light" href="#court-journey">See What Happens Next <span>↓</span></a></div></section>

        <section class="court-journey section-pad" id="court-journey">
            <div class="section-shell">
                <div class="section-heading split-heading reveal"><div><p class="eyebrow"><span></span>Your court journey</p><h2>From agreement to <em>finalisation.</em></h2></div><p>See the full process at a glance. Each stage will be explained when you are ready for it.</p></div>
                <ol class="court-timeline">
                    <li class="timeline-item reveal"><span>01</span><div><h3>Prepare Documents</h3><p>Agreed arrangements are incorporated into the Joint Petition documents.</p></div></li>
                    <li class="timeline-item reveal"><span>02</span><div><h3>Review & Signing</h3><p>Both spouses review and complete the required signing process.</p></div></li>
                    <li class="timeline-item reveal"><span>03</span><div><h3>Commissioner for Oaths</h3><p>Relevant documents are affirmed or sworn where required.</p></div></li>
                    <li class="timeline-item reveal"><span>04</span><div><h3>File With Court</h3><p>The petition and supporting documents enter the court process.</p></div></li>
                    <li class="timeline-item reveal"><span>05</span><div><h3>Attend Court</h3><p>Both spouses attend the required court hearing.</p></div></li>
                    <li class="timeline-item reveal"><span>06</span><div><h3>Draft Order</h3><p>The required draft court order is prepared and filed.</p></div></li>
                    <li class="timeline-item reveal"><span>07</span><div><h3>Decree Nisi</h3><p>The relevant court order is processed following the hearing.</p></div></li>
                    <li class="timeline-item reveal"><span>08</span><div><h3>Three-Month Period</h3><p>The required period is observed before finalisation.</p></div></li>
                    <li class="timeline-item reveal"><span>09</span><div><h3>Finalisation</h3><p>The necessary application is completed to make the divorce final.</p></div></li>
                    <li class="timeline-item reveal"><span>10</span><div><h3>JPN</h3><p>Documents are submitted to Jabatan Pendaftaran Negara where required.</p></div></li>
                </ol>
                <div class="center-action"><a class="button button-outline" href="#court-journey">Explore Full Court Journey <span>→</span></a></div>
            </div>
        </section>

        <section class="method-section section-pad" id="about">
            <div class="section-shell"><div class="section-heading centered reveal"><p class="eyebrow light"><span></span>How LegalDIY fits in</p><h2>Understand. Agree. <em>Prepare. Proceed.</em></h2><p>LegalDIY is organised around your actual journey — not a library of legal topics.</p></div>
                <div class="method-flow reveal"><div><span>01</span><strong>Understand</strong><small>Know what matters.</small></div><i>→</i><div><span>02</span><strong>Discuss</strong><small>Explore your situation.</small></div><i>→</i><div><span>03</span><strong>Agree</strong><small>Reach arrangements.</small></div><i>→</i><div><span>04</span><strong>Prepare</strong><small>Build the information.</small></div><i>→</i><div><span>05</span><strong>Proceed</strong><small>Follow each step.</small></div></div>
                <div class="center-action"><a class="button button-teal" href="{{ route('journey') }}">Start Your Journey <span>→</span></a></div>
            </div>
        </section>

    </main>

    <footer class="site-footer"><div class="section-shell footer-grid"><div><a class="brand footer-brand" href="#top"><span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo.png') }}" alt=""></span><span class="brand-name">LEGAL <strong>DIY</strong></span></a><p>Learn. Empower. Take action.</p></div><div class="footer-links"><a href="#your-journey">Your Journey</a><a href="#court-journey">Court Journey</a><a href="#topics">Learn</a><a href="#about">About</a></div><p class="disclaimer">LegalDIY provides general legal information, not legal advice. Your circumstances may require advice from a qualified lawyer.</p></div><div class="section-shell footer-bottom"><span>© {{ date('Y') }} LegalDIY</span><span>Legal education for everyone, everywhere.</span></div></footer>
</body>
</html>
