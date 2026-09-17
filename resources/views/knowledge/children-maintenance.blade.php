<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Interactive LegalDIY guide to children, custody, access, welfare, and child maintenance in a divorce.">
    <meta name="theme-color" content="#12385f">
    <title>Children &amp; Child Maintenance — LegalDIY Knowledge</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="knowledge-page">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <main id="main-content" class="children-learning" data-children-learning data-journey-url="{{ route('journey') }}" data-examples-url="{{ route('knowledge.children-maintenance.examples') }}">
        <header class="knowledge-hero children-learning-hero">
            <div class="knowledge-title-row"><p class="eyebrow"><span></span>Children &amp; child maintenance</p><a class="knowledge-home" href="{{ url('/') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 10 8-7 8 7v10h-6v-6h-4v6H4Z"/></svg><span>Home</span></a></div>
            <a class="text-link learning-examples-link" href="{{ route('knowledge.children-maintenance.examples') }}">Explore examples <span aria-hidden="true">→</span></a>
        </header>

        <aside class="law-scope"><strong>Legal scope</strong><p>This guide explains Part VIII of Malaysia’s Law Reform (Marriage and Divorce) Act 1976 (Act 164), principally for civil, non-Muslim marriages. It provides general legal education and does not replace advice on a particular family or court order.</p></aside>

        <nav class="learning-step-nav" aria-label="Children learning topics">
            @foreach (['Custody', 'Care & welfare', 'Access', 'Maintenance', 'Duration & changes', 'Agreement check'] as $label)
                <button type="button" data-learning-tab="{{ $loop->iteration }}" aria-current="{{ $loop->first ? 'step' : 'false' }}"><span>{{ $loop->iteration }}</span>{{ $label }}</button>
            @endforeach
        </nav>

        <div class="learning-progress"><span data-learning-label>Topic 1 of 6</span><div><i data-learning-progress></i></div></div>

        <section class="learning-panel" data-learning-panel="1">
            <p class="learning-kicker">Topic 1</p><h2>Custody and joint responsibility</h2>
            <div class="learning-grid">
                <article><h3>Who is a child?</h3><p>A child of the marriage is generally a person under 18. The duration of maintenance is a separate question and may extend beyond 18 in particular circumstances.</p></article>
                <article><h3>Who may have custody?</h3><p>The Court may place a child with the father, mother, or—exceptionally—another suitable relative, association, or person. The answer is not determined simply by which parent asks.</p></article>
                <article><h3>What does custody mean?</h3><p>Custody concerns responsibility and authority for the child, not only where the child sleeps. An order may include conditions about residence, upbringing, and parental arrangements.</p></article>
                <article><h3>Can custody be shared?</h3><p>Joint custody can preserve both parents’ involvement in important matters. It does not automatically require the child to spend exactly half their time with each parent.</p></article>
            </div>
            <details class="law-reference"><summary><span>Legal reference</span><strong>Sections 87 and 88(1)</strong><i>+</i></summary><div><h3>Who is a child?</h3><blockquote>“Child” has the meaning of “child of the marriage” as defined in section 2 who is under the age of eighteen years.</blockquote><p><b>Section 87</b> supplies the definition used in Part VIII. <b>Section 88(1)</b> gives the Court power to place a child with either parent or, in exceptional circumstances, another suitable relative, child-welfare association, or person.</p><p class="case-note"><b>Case note:</b> The scanned commentary discusses <i>Lee Wei Yen v Halim Berbar</i> [2011] 1 LNS 417 when distinguishing custody from broader parental responsibility and guardianship.</p></div></details>
        </section>

        <section class="learning-panel" data-learning-panel="2" hidden>
            <p class="learning-kicker">Topic 2</p><h2>Care, welfare, wishes, and stability</h2>
            <div class="principle-banner"><strong>The child’s welfare is the paramount consideration.</strong><p>The central question is “What arrangement is in the child’s welfare?”—not which parent has the stronger claim.</p></div>
            <div class="learning-grid">
                <article><h3>Care and control</h3><p>This concerns ordinary day-to-day life: the child’s principal home, supervision, school routine, meals, transport, activities, and practical care.</p></article>
                <article><h3>The child’s wishes</h3><p>The wishes of a child able to express an independent opinion may be considered, but there is no simple rule that a child chooses at a fixed age. Welfare remains paramount.</p></article>
                <article><h3>Children below seven</h3><p>There is a rebuttable presumption that being with the mother is for the good of a child below seven. It is not automatic and may be displaced according to the circumstances.</p></article>
                <article><h3>Routine and continuity</h3><p>Consider where the child lives now, who provides care, the existing routine, the proposed change, and whether that change would cause unnecessary disruption.</p></article>
                <article><h3>Brothers and sisters</h3><p>The Court is not required to place every sibling with the same person. Each child’s welfare may be considered separately.</p></article>
                <article><h3>Parents’ wishes</h3><p>The parents’ wishes are relevant, as may be the child’s independent wishes, but both remain subject to the overriding welfare principle.</p></article>
            </div>
            <details class="law-reference"><summary><span>Legal reference</span><strong>Section 88(2)–(4)</strong><i>+</i></summary><div><blockquote>“The paramount consideration shall be the welfare of the child.”</blockquote><p><b>Section 88(2)</b> makes welfare paramount and, subject to welfare, directs attention to the parents’ wishes and the wishes of a child old enough to express an independent opinion.</p><p><b>Section 88(3)</b> contains a rebuttable—not automatic—presumption concerning a child below seven and specifically mentions the undesirability of disturbing the child’s life through custody changes. <b>Section 88(4)</b> requires each sibling’s welfare to be considered independently.</p></div></details>
        </section>

        <section class="learning-panel" data-learning-panel="3" hidden>
            <p class="learning-kicker">Topic 3</p><h2>Access and maintaining relationships</h2>
            <p class="learning-lead">Access enables a parent without day-to-day care to maintain contact and a relationship with the child. There is no universal timetable.</p>
            <div class="discussion-chips access-chips" aria-label="Access matters to explore">@foreach (['weekdays' => 'Weekdays', 'weekends' => 'Weekends', 'overnights' => 'Overnight stays', 'school-holidays' => 'School holidays', 'public-holidays' => 'Public holidays', 'birthdays' => 'Birthdays', 'calls' => 'Calls & video calls', 'collection' => 'Collection', 'returns' => 'Return arrangements'] as $key => $item)<button type="button" data-access-example="{{ $key }}"><span class="access-chip-label">{{ $item }}</span><svg class="access-chip-icon" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 4v12M4 10h12"/></svg></button>@endforeach</div>
            <div class="learning-grid two-up">
                <article><h3>Conditions can be included</h3><p>A custody order may regulate residence, upbringing, access, and how arrangements between the child and parents will operate.</p></article>
                <article><h3>Travel and relocation</h3><p>Taking a child outside Malaysia can raise separate issues. A disputed permanent relocation should not be treated as an ordinary access question and may require legal advice.</p></article>
            </div>
            <aside class="learning-warning"><strong>Avoid fixed assumptions.</strong> Alternate weekends, half the school holidays, or any other schedule is not automatically suitable for every family.</aside>
            <details class="law-reference"><summary><span>Legal reference</span><strong>Section 89</strong><i>+</i></summary><div><h3>Orders may contain practical conditions</h3><p><b>Section 89(1)</b> permits custody orders to be made subject to conditions. <b>Section 89(2)</b> specifically identifies residence, education, religion, temporary care and control, visits, access at reasonable times and frequency, and a prohibition against taking the child out of Malaysia.</p><blockquote>An access arrangement is therefore not limited to naming the parent with custody; an order may explain how the arrangement is to operate.</blockquote></div></details>
        </section>

        <section class="learning-panel" data-learning-panel="4" hidden>
            <p class="learning-kicker">Topic 4</p><h2>Child maintenance and reasonable needs</h2>
            <div class="principle-banner"><strong>Ask what the child reasonably needs and how those needs will be met.</strong><p>Do not begin with an invented percentage of salary or a fixed amount per child.</p></div>
            <div class="learning-grid">
                <article><h3>Parental responsibility</h3><p>A parent does not automatically stop having maintenance responsibility because the child lives with the other parent. Contribution depends on the applicable agreement, order, means, and circumstances.</p></article>
                <article><h3>What maintenance covers</h3><p>Maintenance includes reasonable needs such as accommodation, clothing, food, and education, assessed with regard to the responsible parent’s means and station in life.</p></article>
                <article><h3>No universal formula</h3><p>There is no universal 10%, 20%, RM amount, or automatic 50/50 rule. The child’s needs and the parents’ circumstances matter.</p></article>
                <article><h3>Ways to contribute</h3><p>An arrangement may combine a regular payment with expenses paid directly, such as education, childcare, healthcare, or other child-related costs.</p></article>
            </div>
            <h3 class="expense-title">Build the child’s actual expense picture</h3>
            <div class="discussion-chips access-chips" aria-label="Child expense categories to explore">@foreach (['accommodation' => 'Accommodation', 'food' => 'Food', 'clothing' => 'Clothing', 'education' => 'Education', 'medical' => 'Medical needs', 'transport' => 'Transport', 'childcare' => 'Childcare', 'school-expenses' => 'School expenses', 'other-needs' => 'Other reasonable needs'] as $key => $item)<button type="button" data-expense-example="{{ $key }}"><span class="access-chip-label">{{ $item }}</span><svg class="access-chip-icon" viewBox="0 0 20 20" aria-hidden="true"><path d="M10 4v12M4 10h12"/></svg></button>@endforeach</div>
            <ol class="maintenance-steps"><li>Identify reasonable needs.</li><li>Record what each parent currently pays.</li><li>Identify expenses after divorce.</li><li>Consider each parent’s means and circumstances.</li><li>Agree how the needs will be met.</li></ol>
            <details class="law-reference"><summary><span>Legal reference</span><strong>Sections 92–94</strong><i>+</i></summary><div><h3>Duty, Court orders, and security</h3><blockquote>A parent must maintain or contribute to maintaining the child, whether the child is in that parent’s custody or another person’s custody.</blockquote><p><b>Section 92</b> expressly refers to reasonable accommodation, clothing, food, and education, having regard to the parent’s means and station in life, or payment of their cost.</p><p><b>Section 93</b> sets out the Court’s maintenance powers, including a corresponding power to order a woman to pay or contribute where reasonable having regard to her means. <b>Section 94</b> permits the Court, in its discretion, to order security for maintenance.</p></div></details>
        </section>

        <section class="learning-panel" data-learning-panel="5" hidden>
            <p class="learning-kicker">Topic 5</p><h2>Duration, higher education, disability, and changes</h2>
            <div class="learning-grid">
                <article><h3>The later applicable event</h3><p>Child maintenance does not necessarily end at 18. The order may continue until the child turns 18, completes further or higher education or training, or the relevant physical or mental disability ceases—whichever applicable event occurs later.</p></article>
                <article><h3>Tertiary education and training</h3><p>Further or higher education includes tertiary pathways such as college or university. Relevant vocational or other training may also be covered, with maintenance potentially continuing until that education or training is completed.</p></article>
                <article><h3>Physical or mental disability</h3><p>Where the child has a relevant physical or mental disability, maintenance may continue beyond 18 until that disability ceases, subject to the applicable order and circumstances.</p></article>
                <article><h3>Changing an order</h3><p>The Court can vary custody or maintenance orders. Employment, income, schooling costs, needs, or living arrangements may be relevant, but an existing order does not change automatically.</p></article>
            </div>
            <aside class="learning-warning"><strong>Do not simply ignore an existing Court order.</strong> If circumstances change, consider whether a formal variation is required.</aside>
            <details class="law-reference"><summary><span>Legal reference</span><strong>Sections 95 and 96</strong><i>+</i></summary><div><h3>Duration and variation</h3><p><b>Section 95, as amended:</b> the current framework addresses age 18, physical or mental disability, and further or higher education or training. The applicable later endpoint matters. The older wording reproduced in the scanned commentary predates the education amendment and should not be used by itself.</p><blockquote>Do not state that every child-maintenance order automatically ends on the eighteenth birthday.</blockquote><p><b>Section 96</b> allows the Court to vary or rescind a custody or maintenance order where it was based on misrepresentation or mistake of fact, or where there has been a material change in circumstances.</p></div></details>
        </section>

        <section class="learning-panel" data-learning-panel="6" hidden>
            <p class="learning-kicker">Topic 6</p><h2>What should parents discuss before a Joint Petition?</h2>
            <div class="agreement-questions">
                <article><span>Custody</span><p>Do you agree on joint custody or another arrangement and on responsibility for important decisions?</p></article>
                <article><span>Care & living</span><p>Where will the child principally live, and how will the normal routine work?</p></article>
                <article><span>Access</span><p>How will contact, weekends, holidays, occasions, collection, and return operate?</p></article>
                <article><span>Maintenance</span><p>What are the reasonable expenses, what contribution is proposed, and which costs will be paid directly?</p></article>
            </div>
            <aside class="principle-banner"><strong>Agreement to divorce does not resolve child arrangements automatically.</strong><p>A genuine dispute about custody, residence, access, relocation, safety, or maintenance may mean the arrangements are not yet suitable for a straightforward Joint Petition journey.</p></aside>
            <details class="law-reference"><summary><span>Legal map</span><strong>Part VIII of Act 164</strong><i>+</i></summary><div><ul><li><b>Section 87:</b> meaning of child.</li><li><b>Sections 88–91:</b> custody powers, conditions, fitness, and particular custody rules.</li><li><b>Sections 92–95:</b> maintenance duty, Court powers, security, and duration.</li><li><b>Section 96:</b> variation or rescission of custody and maintenance orders.</li></ul><p>The exact proposed terms and evidence required depend on the family’s circumstances.</p></div></details>
        </section>

        <div class="learning-actions"><button class="button button-outline" type="button" data-learning-previous hidden>Previous</button><button class="button" type="button" data-learning-next>Next topic <span>→</span></button></div>

        <aside class="knowledge-disclaimer"><strong>General information, not legal advice.</strong> The appropriate arrangement depends on the child and the family’s circumstances. Complex or disputed matters may require advice from a qualified lawyer.</aside>
        <dialog class="access-modal" data-access-modal aria-labelledby="access-modal-title"><div class="access-modal-head"><div><small data-modal-kicker>Possible arrangements</small><h2 id="access-modal-title" data-access-modal-title></h2></div><button class="access-modal-close" type="button" data-access-modal-close aria-label="Close information"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 6l12 12M18 6 6 18"/></svg></button></div><div class="access-modal-body"><p class="access-modal-intro" data-modal-intro></p><ul data-access-modal-list></ul><p class="access-modal-note" data-modal-note></p></div><div class="access-modal-actions"><button class="button" type="button" data-access-modal-done>Done</button></div></dialog>
    </main>
    <footer class="knowledge-footer">LegalDIY provides general legal information, not legal advice.</footer>
</body>
</html>
