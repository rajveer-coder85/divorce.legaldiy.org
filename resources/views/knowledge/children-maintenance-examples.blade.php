<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fictional stories showing how families may discuss children and child maintenance arrangements.">
    <meta name="theme-color" content="#12385f">
    <title>Children &amp; Child Maintenance Stories — LegalDIY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="knowledge-page">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <main id="main-content" class="children-learning story-library">
        <header class="knowledge-hero">
            <div class="knowledge-title-row"><p class="eyebrow"><span></span>Children &amp; child maintenance</p><a class="knowledge-home" href="{{ url('/') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 10 8-7 8 7v10h-6v-6h-4v6H4Z"/></svg><span>Home</span></a></div>
            <h1>Stories from different families.</h1>
            <p>These fictional stories demonstrate a way of thinking through common questions. They do not prescribe the arrangement your family should use.</p>
        </header>

        <div class="story-how-to"><strong>How to use these stories</strong><ol><li>Read the situation that feels most familiar.</li><li>Notice the questions the parents work through.</li><li>Take useful questions into your own discussion—not necessarily their outcome.</li></ol></div>

        <nav class="story-index" aria-label="Family stories">
            <a href="#story-emily">Emily &amp; David</a><a href="#story-mei">Mei Ling &amp; Daniel</a><a href="#story-priya">Priya &amp; Arvind</a><a href="#story-rachel">Rachel &amp; Thomas</a>
        </nav>

        @php
            $stories = [
                ['story-emily', 'Story 01', 'Emily, David, and one familiar home', 'Their nine-year-old daughter, Sophie, has always lived close to school and her grandparents. Both parents want to remain involved, but David’s work makes an equal day-to-day schedule difficult.', 'They separate joint responsibility from daily care. Sophie keeps one principal home during the school week. Both parents discuss education and healthcare, share school information, and arrange David’s time around a predictable routine.', 'Which decisions should remain shared? Where should the principal home be? How will both parents receive information and stay involved?'],
                ['story-mei', 'Story 02', 'Mei Ling and Daniel build a routine', 'Their son lives mainly with Mei Ling. Both support regular contact with Daniel, but vague promises such as “reasonable access” keep causing misunderstandings.', 'They discuss ordinary weekdays first, then weekends, holidays, calls, collection, delays, and returns. Their proposed routine takes account of travel, school, sleep, and their son’s activities.', 'When will contact happen? Who handles transport? How much notice is needed for a change? What routine works for the child?'],
                ['story-priya', 'Story 03', 'Priya and Arvind list the real costs', 'They agree that both children must be supported, but each parent has a different idea of what a fair monthly contribution looks like.', 'They do not begin with a salary percentage. They list food, housing, school, transport, healthcare, childcare, and occasional costs, then record what each already provides and which expenses could be paid directly.', 'What does each child reasonably need? What is already being paid? Which costs are regular, exceptional, or better paid directly?'],
                ['story-rachel', 'Story 04', 'Rachel and Thomas face a major change', 'Rachel is considering work abroad with their child. Thomas objects because the move would affect school, regular contact, travel costs, stability, and the child’s relationship with him.', 'They recognise this is not simply an access-scheduling issue. International relocation affects custody, stability, travel, and whether a meaningful parent-child relationship can be maintained.', 'Is there a genuine dispute or safety concern? Would the move fundamentally change the current arrangement? Is individual legal advice needed?'],
            ];
        @endphp

        <div class="story-list">
            @foreach ($stories as [$id, $number, $title, $situation, $process, $questions])
                <article id="{{ $id }}" class="story-article">
                    <p>{{ $number }}</p><h2>{{ $title }}</h2>
                    <h3>Their situation</h3><p>{{ $situation }}</p>
                    <h3>How they work through it</h3><p>{{ $process }}</p>
                    <h3>Questions to take into your story</h3><p>{{ $questions }}</p>
                </article>
            @endforeach
        </div>

        <aside class="knowledge-disclaimer"><strong>General information, not legal advice.</strong> These are fictional examples, not standard entitlements or required outcomes.</aside>
        <div class="knowledge-actions"><a class="button button-outline" href="{{ route('knowledge.show', 'children-maintenance') }}">Back to learning topics</a><a class="button" href="{{ route('journey') }}">Start your journey <span>→</span></a></div>
    </main>
    <footer class="knowledge-footer">LegalDIY provides general legal information, not legal advice.</footer>
</body>
</html>
