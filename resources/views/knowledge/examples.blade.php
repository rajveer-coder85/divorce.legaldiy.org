<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Fictional examples about {{ strtolower($guide['eyebrow']) }}.">
    <meta name="theme-color" content="#12385f"><title>{{ $guide['eyebrow'] }} Stories — LegalDIY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="knowledge-page">
<a class="skip-link" href="#main-content">Skip to content</a>
<main id="main-content" class="children-learning story-library">
    <header class="knowledge-hero">
        <div class="knowledge-title-row"><p class="eyebrow"><span></span>{{ $guide['eyebrow'] }}</p><a class="knowledge-home" href="{{ url('/') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 10 8-7 8 7v10h-6v-6h-4v6H4Z"/></svg><span>Home</span></a></div>
        <h1>{{ $guide['story_title'] }}</h1><p>These fictional stories show how people might work through the questions. They do not prescribe your outcome.</p>
    </header>
    <div class="story-how-to"><strong>How to use these stories</strong><ol><li>Read the situation that feels most familiar.</li><li>Notice the information and questions used.</li><li>Take useful questions into your own discussion—not necessarily the outcome.</li></ol></div>
    <nav class="story-index" aria-label="Example stories">@foreach ($guide['stories'] as $story)<a href="#story-{{ $loop->iteration }}">Story {{ $loop->iteration }}</a>@endforeach</nav>
    <div class="story-list">
        @foreach ($guide['stories'] as [$title, $process, $questions])
            <article id="story-{{ $loop->iteration }}" class="story-article"><p>Story {{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</p><h2>{{ $title }}</h2><h3>How they work through it</h3><p>{{ $process }}</p><h3>Questions to take into your story</h3><p>{{ $questions }}</p></article>
        @endforeach
    </div>
    <aside class="knowledge-disclaimer"><strong>General information, not legal advice.</strong> These are fictional examples, not standard entitlements or required outcomes.</aside>
    <div class="knowledge-actions"><a class="button button-outline" href="{{ route('knowledge.show', $slug) }}">Back to learning topics</a><a class="button" href="{{ route('journey') }}">Start your journey <span>→</span></a></div>
</main>
<footer class="knowledge-footer">LegalDIY provides general legal information, not legal advice.</footer>
</body>
</html>
