<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Understand the divorce process through LegalDIY's structured education programme.">
    <title>Divorce Education Programme | LegalDIY</title>
    <style>
        :root {
            --navy: #12395d;
            --navy-deep: #0a2946;
            --teal: #319da2;
            --blue: #5799c7;
            --aqua: #91c6d0;
            --ice: #f2f8f9;
            --mist: #e4f1f3;
            --ink: #263640;
            --muted: #647680;
            --white: #fff;
            --line: #d5e4e7;
            --shadow: 0 18px 45px rgba(18, 57, 93, .10);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            color: var(--ink);
            background: var(--white);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            line-height: 1.65;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; }
        button { font: inherit; }
        .container { width: min(1120px, calc(100% - 40px)); margin-inline: auto; }
        .skip-link { position: fixed; left: 16px; top: -60px; z-index: 100; padding: 10px 16px; background: var(--navy); color: white; }
        .skip-link:focus { top: 16px; }

        header { position: sticky; top: 0; z-index: 20; background: rgba(255,255,255,.96); border-bottom: 1px solid rgba(213,228,231,.8); }
        .nav { min-height: 76px; display: flex; align-items: center; justify-content: space-between; gap: 30px; }
        .brand { display: flex; align-items: center; gap: 11px; color: var(--navy); font-weight: 800; letter-spacing: .08em; }
        .brand-mark { display: grid; place-items: center; width: 42px; height: 42px; border: 2px solid var(--navy); border-radius: 50% 50% 46% 46%; color: var(--teal); font-size: 13px; letter-spacing: -.05em; }
        .brand span:last-child em { color: var(--teal); font-style: normal; }
        nav { display: flex; align-items: center; gap: 30px; font-size: 14px; font-weight: 650; color: var(--navy); }
        nav a:not(.button):hover { color: var(--teal); }
        .button { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 48px; padding: 0 22px; border: 0; border-radius: 8px; background: var(--teal); color: var(--white); font-weight: 750; cursor: pointer; transition: transform .2s ease, background .2s ease; }
        .button:hover { transform: translateY(-2px); background: #278b90; }
        .button.secondary { background: transparent; color: var(--navy); border: 1px solid var(--aqua); }
        .button.secondary:hover { background: var(--ice); }
        .menu-button { display: none; width: 44px; height: 44px; border: 1px solid var(--line); border-radius: 8px; background: white; color: var(--navy); cursor: pointer; }

        .hero { overflow: hidden; padding: 92px 0 88px; background: var(--ice); border-bottom: 1px solid var(--line); }
        .hero-grid { display: grid; grid-template-columns: 1.08fr .92fr; align-items: center; gap: 70px; }
        .eyebrow { display: inline-flex; align-items: center; gap: 9px; margin: 0 0 20px; color: var(--teal); font-size: 13px; font-weight: 800; letter-spacing: .13em; text-transform: uppercase; }
        .eyebrow::before { content: ""; width: 28px; height: 2px; background: currentColor; }
        h1, h2, h3, p { margin-top: 0; }
        h1 { max-width: 720px; margin-bottom: 24px; color: var(--navy-deep); font-size: clamp(44px, 5.4vw, 72px); line-height: 1.06; letter-spacing: -.045em; }
        h2 { margin-bottom: 18px; color: var(--navy-deep); font-size: clamp(31px, 4vw, 48px); line-height: 1.13; letter-spacing: -.035em; }
        h3 { margin-bottom: 9px; color: var(--navy); font-size: 20px; line-height: 1.3; }
        .hero-copy > p:not(.eyebrow) { max-width: 650px; margin-bottom: 32px; color: #526771; font-size: 18px; }
        .hero-actions { display: flex; flex-wrap: wrap; gap: 12px; }
        .hero-note { margin: 18px 0 0 !important; font-size: 13px !important; color: var(--muted) !important; }

        .path-card { position: relative; padding: 34px; background: white; border: 1px solid var(--line); border-radius: 18px; box-shadow: var(--shadow); }
        .path-card::before { content: ""; position: absolute; inset: 12px 12px auto auto; width: 78px; height: 78px; border-radius: 50%; border: 18px solid var(--mist); }
        .card-kicker { position: relative; color: var(--teal); font-size: 12px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
        .path-card h3 { position: relative; margin: 10px 0 25px; font-size: 27px; }
        .path-list { position: relative; display: grid; gap: 10px; }
        .path-item { display: flex; align-items: center; gap: 14px; padding: 13px 15px; border-radius: 9px; background: var(--ice); color: var(--navy); font-weight: 700; }
        .path-number { display: grid; place-items: center; flex: 0 0 31px; height: 31px; border-radius: 50%; background: var(--navy); color: white; font-size: 12px; }

        .trust { border-bottom: 1px solid var(--line); }
        .trust-grid { display: grid; grid-template-columns: repeat(3, 1fr); }
        .trust-item { padding: 26px 28px; display: flex; align-items: center; gap: 13px; color: var(--navy); font-weight: 700; border-right: 1px solid var(--line); }
        .trust-item:first-child { padding-left: 0; }
        .trust-item:last-child { border-right: 0; }
        .check { display: grid; place-items: center; width: 26px; height: 26px; border-radius: 50%; background: var(--mist); color: var(--teal); }

        section { padding: 96px 0; }
        .section-head { max-width: 710px; margin-bottom: 46px; }
        .section-head > p:last-child { color: var(--muted); font-size: 17px; }
        .intro-grid { display: grid; grid-template-columns: .9fr 1.1fr; gap: 85px; align-items: start; }
        .statement { padding: 34px; border-left: 4px solid var(--teal); background: var(--ice); color: var(--navy); font-size: 22px; font-weight: 750; line-height: 1.45; }
        .learn-list { margin: 0; padding: 0; list-style: none; display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; }
        .learn-list li { position: relative; padding-left: 25px; color: #526771; }
        .learn-list li::before { content: ""; position: absolute; left: 0; top: .65em; width: 8px; height: 8px; border-radius: 50%; background: var(--teal); }

        .journey { background: var(--navy-deep); color: white; }
        .journey h2, .journey h3 { color: white; }
        .journey .eyebrow { color: var(--aqua); }
        .journey .section-head > p:last-child { color: #c7d9e2; }
        .steps { display: grid; grid-template-columns: repeat(5, 1fr); gap: 1px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.14); }
        .step { min-height: 250px; padding: 29px 25px; background: var(--navy-deep); }
        .step-number { color: var(--aqua); font-size: 13px; font-weight: 800; letter-spacing: .1em; }
        .step h3 { margin: 38px 0 12px; font-size: 23px; }
        .step p { margin: 0; color: #bfd1da; font-size: 14px; }

        .fit-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 70px; align-items: center; }
        .fit-copy > p { color: var(--muted); }
        .notice { margin-top: 27px; padding: 23px 25px; border-radius: 10px; background: var(--mist); color: var(--navy); font-weight: 750; }
        .risk-card { padding: 34px; border-radius: 16px; background: var(--ice); border: 1px solid var(--line); }
        .risk-card h3 { margin-bottom: 18px; }
        .risk-card ul { margin: 0; padding-left: 20px; color: #526771; columns: 2; column-gap: 34px; }
        .risk-card li { margin-bottom: 10px; break-inside: avoid; }

        .access { background: var(--ice); }
        .access-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; }
        .access-card { padding: 27px 23px; background: white; border: 1px solid var(--line); border-radius: 12px; }
        .access-card .num { display: grid; place-items: center; width: 38px; height: 38px; margin-bottom: 24px; border-radius: 8px; background: var(--navy); color: white; font-size: 13px; font-weight: 800; }
        .access-card p { margin: 0; color: var(--muted); font-size: 14px; }

        .values { display: grid; grid-template-columns: repeat(4, 1fr); margin-top: 48px; border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); }
        .value { padding: 30px 24px; border-right: 1px solid var(--line); }
        .value:first-child { padding-left: 0; }
        .value:last-child { border-right: 0; }
        .value p { margin: 0; color: var(--muted); font-size: 14px; }

        .cta { padding-top: 15px; }
        .cta-box { padding: 65px; text-align: center; border-radius: 18px; background: var(--teal); color: white; }
        .cta-box h2 { color: white; }
        .cta-box p { max-width: 690px; margin: 0 auto 28px; color: #eaf7f7; font-size: 17px; }
        .cta-box .button { background: white; color: var(--navy); }

        footer { margin-top: 80px; padding: 64px 0 30px; background: var(--navy-deep); color: #c5d4dc; }
        .footer-grid { display: grid; grid-template-columns: .7fr 1.3fr; gap: 80px; }
        footer .brand { color: white; }
        footer .brand-mark { border-color: var(--aqua); }
        .tagline { margin: 20px 0 0; font-size: 13px; letter-spacing: .12em; text-transform: uppercase; }
        .disclaimer h3 { color: white; font-size: 16px; }
        .disclaimer p { margin-bottom: 11px; font-size: 12px; line-height: 1.7; }
        .footer-bottom { display: flex; justify-content: space-between; gap: 20px; margin-top: 45px; padding-top: 23px; border-top: 1px solid rgba(255,255,255,.12); font-size: 12px; }

        @media (max-width: 900px) {
            .menu-button { display: block; }
            nav { display: none; position: absolute; inset: 76px 0 auto; padding: 22px 20px; background: white; border-bottom: 1px solid var(--line); box-shadow: var(--shadow); flex-direction: column; align-items: stretch; }
            nav.open { display: flex; }
            .hero-grid, .intro-grid, .fit-grid, .footer-grid { grid-template-columns: 1fr; gap: 45px; }
            .hero { padding-top: 65px; }
            .steps { grid-template-columns: repeat(2, 1fr); }
            .access-grid, .values { grid-template-columns: repeat(2, 1fr); }
            .value:nth-child(2) { border-right: 0; }
            .value:nth-child(-n+2) { border-bottom: 1px solid var(--line); }
        }

        @media (max-width: 620px) {
            .container { width: min(100% - 28px, 1120px); }
            section { padding: 70px 0; }
            h1 { font-size: 43px; }
            .hero { padding: 54px 0 65px; }
            .path-card, .risk-card { padding: 25px; }
            .trust-grid, .steps, .access-grid, .values, .learn-list { grid-template-columns: 1fr; }
            .trust-item { padding: 18px 0; border-right: 0; border-bottom: 1px solid var(--line); }
            .trust-item:last-child { border-bottom: 0; }
            .step { min-height: 190px; }
            .step h3 { margin-top: 25px; }
            .risk-card ul { columns: 1; }
            .value, .value:first-child { padding: 24px 0; border-right: 0; border-bottom: 1px solid var(--line); }
            .value:last-child { border-bottom: 0; }
            .cta-box { padding: 45px 22px; }
            .footer-bottom { flex-direction: column; }
        }
    </style>
</head>
<body>
    <a class="skip-link" href="#main">Skip to content</a>

    <header>
        <div class="container nav">
            <a class="brand" href="#" aria-label="LegalDIY home">
                <span class="brand-mark">LD</span>
                <span>LEGAL <em>DIY</em></span>
            </a>
            <button class="menu-button" type="button" aria-expanded="false" aria-controls="site-nav" aria-label="Open menu">☰</button>
            <nav id="site-nav" aria-label="Primary navigation">
                <a href="#programme">The programme</a>
                <a href="#journey">How it works</a>
                <a href="#suitability">Suitability</a>
                <a class="button" href="#assessment">Begin assessment <span aria-hidden="true">→</span></a>
            </nav>
        </div>
    </header>

    <main id="main">
        <section class="hero">
            <div class="container hero-grid">
                <div class="hero-copy">
                    <p class="eyebrow">A LegalDIY education initiative</p>
                    <h1>Understand the divorce process before you begin.</h1>
                    <p>A structured educational pathway to help you understand the process, prepare with confidence and decide whether navigating appropriate proceedings independently may be right for you.</p>
                    <div class="hero-actions">
                        <a class="button" href="#programme">Understand the process</a>
                        <a class="button secondary" href="#assessment">Check your suitability</a>
                    </div>
                    <p class="hero-note">Educational information only. LegalDIY is not a law firm.</p>
                </div>

                <aside class="path-card" aria-label="Programme pathway">
                    <span class="card-kicker">Your learning pathway</span>
                    <h3>Knowledge before action</h3>
                    <div class="path-list">
                        <div class="path-item"><span class="path-number">01</span> Understand</div>
                        <div class="path-item"><span class="path-number">02</span> Assess</div>
                        <div class="path-item"><span class="path-number">03</span> Prepare</div>
                        <div class="path-item"><span class="path-number">04</span> File &amp; navigate</div>
                    </div>
                </aside>
            </div>
        </section>

        <div class="trust">
            <div class="container trust-grid">
                <div class="trust-item"><span class="check">✓</span> Plain-language education</div>
                <div class="trust-item"><span class="check">✓</span> Structured step by step</div>
                <div class="trust-item"><span class="check">✓</span> Suitability assessed first</div>
            </div>
        </div>

        <section id="programme">
            <div class="container intro-grid">
                <div>
                    <p class="eyebrow">The programme</p>
                    <h2>You may be able to navigate your own divorce.</h2>
                    <div class="statement">Our purpose is to help you understand the process so that you can make informed decisions for yourself.</div>
                </div>
                <div>
                    <p>Not every divorce requires the same level of professional assistance. Where circumstances are relatively straightforward and the parties understand the decisions they are making, some individuals may choose to manage appropriate parts of the process themselves.</p>
                    <p>LegalDIY provides a structured educational pathway designed to help you understand:</p>
                    <ul class="learn-list">
                        <li>How the process generally works</li>
                        <li>The stages you may encounter</li>
                        <li>Information and documents required</li>
                        <li>How legal documents are structured</li>
                        <li>Filing and procedural requirements</li>
                        <li>What happens after filing</li>
                        <li>How to prepare at each stage</li>
                        <li>When professional help may be needed</li>
                    </ul>
                </div>
            </div>
        </section>

        <section class="journey" id="journey">
            <div class="container">
                <div class="section-head">
                    <p class="eyebrow">A guided approach</p>
                    <h2>A step-by-step learning journey</h2>
                    <p>Follow one structured pathway instead of searching across dozens of websites, forms and explanations.</p>
                </div>
                <div class="steps">
                    <article class="step"><span class="step-number">01</span><h3>Understand</h3><p>Learn the fundamental concepts and procedures relevant to divorce.</p></article>
                    <article class="step"><span class="step-number">02</span><h3>Assess</h3><p>Consider whether a self-directed approach may be appropriate.</p></article>
                    <article class="step"><span class="step-number">03</span><h3>Prepare</h3><p>Learn what documents and information may be required.</p></article>
                    <article class="step"><span class="step-number">04</span><h3>File</h3><p>Understand filing and the procedural steps that may follow.</p></article>
                    <article class="step"><span class="step-number">05</span><h3>Navigate</h3><p>Know what to expect as the matter progresses.</p></article>
                </div>
            </div>
        </section>

        <section id="suitability">
            <div class="container fit-grid">
                <div class="fit-copy">
                    <p class="eyebrow">Suitability comes first</p>
                    <h2>Is LegalDIY appropriate for everyone?</h2>
                    <p>No. Divorce circumstances can vary considerably, and some matters require independent professional legal assistance.</p>
                    <div class="notice">Every participant begins with a suitability assessment before complete programme access is provided.</div>
                </div>
                <aside class="risk-card">
                    <h3>Professional assistance may be important where a matter involves:</h3>
                    <ul>
                        <li>Disputes over children</li>
                        <li>Property disputes</li>
                        <li>Financial arrangements</li>
                        <li>Domestic violence</li>
                        <li>Jurisdiction issues</li>
                        <li>Complex assets</li>
                    </ul>
                </aside>
            </div>
        </section>

        <section class="access">
            <div class="container">
                <div class="section-head">
                    <p class="eyebrow">Programme access</p>
                    <h2>Start with a clear first step.</h2>
                    <p>Your assessment helps us understand your circumstances and whether the programme is appropriate for the type of matter you are seeking to navigate.</p>
                </div>
                <div class="access-grid">
                    <article class="access-card"><span class="num">1</span><h3>Complete</h3><p>Tell us about your circumstances through the online suitability assessment.</p></article>
                    <article class="access-card"><span class="num">2</span><h3>Review</h3><p>Your submission is considered against the programme's suitability criteria.</p></article>
                    <article class="access-card"><span class="num">3</span><h3>Outcome</h3><p>Receive an email explaining your outcome and the appropriate next step.</p></article>
                    <article class="access-card"><span class="num">4</span><h3>Learn</h3><p>Approved participants receive access to their educational pathway and resources.</p></article>
                </div>

                <div class="values">
                    <div class="value"><h3>Clarity</h3><p>Complex procedures explained in understandable language.</p></div>
                    <div class="value"><h3>Structure</h3><p>Information organised according to each stage.</p></div>
                    <div class="value"><h3>Independence</h3><p>Be better equipped to manage appropriate aspects yourself.</p></div>
                    <div class="value"><h3>Responsibility</h3><p>Recognise the limits of self-help and when to seek advice.</p></div>
                </div>
            </div>
        </section>

        <section class="cta" id="assessment">
            <div class="container">
                <div class="cta-box">
                    <h2>Start with understanding.</h2>
                    <p>You do not need to begin by completing legal documents. First understand your situation, the process ahead and whether a self-directed approach may be appropriate.</p>
                    <a class="button" href="mailto:hello@legaldiy.org?subject=Divorce%20Suitability%20Assessment">Begin your suitability assessment <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-grid">
                <div>
                    <a class="brand" href="#"><span class="brand-mark">LD</span><span>LEGAL <em>DIY</em></span></a>
                    <p class="tagline">Learn. Empower. Take action.</p>
                </div>
                <div class="disclaimer">
                    <h3>Important information</h3>
                    <p>LegalDIY provides legal education and self-help information. LegalDIY is not a law firm and does not represent participants in legal proceedings. Participation does not create a solicitor-client relationship.</p>
                    <p>Programme information is not a substitute for legal advice tailored to individual circumstances. Legal requirements differ between jurisdictions and may change. Participants remain responsible for following the requirements applicable to their matter.</p>
                </div>
            </div>
            <div class="footer-bottom"><span>© {{ date('Y') }} LegalDIY. All rights reserved.</span><span>Legal education for everyone, everywhere.</span></div>
        </div>
    </footer>

    <script>
        const menuButton = document.querySelector('.menu-button');
        const navigation = document.querySelector('#site-nav');
        menuButton.addEventListener('click', () => {
            const isOpen = navigation.classList.toggle('open');
            menuButton.setAttribute('aria-expanded', String(isOpen));
            menuButton.setAttribute('aria-label', isOpen ? 'Close menu' : 'Open menu');
        });
        navigation.querySelectorAll('a').forEach(link => link.addEventListener('click', () => {
            navigation.classList.remove('open');
            menuButton.setAttribute('aria-expanded', 'false');
        }));
    </script>
</body>
</html>
