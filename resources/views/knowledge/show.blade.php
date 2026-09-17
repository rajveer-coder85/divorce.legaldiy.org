<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Interactive LegalDIY education about {{ strtolower($guide['eyebrow']) }}.">
    <meta name="theme-color" content="#12385f"><title>{{ $guide['eyebrow'] }} — LegalDIY Knowledge</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="knowledge-page">
<a class="skip-link" href="#main-content">Skip to content</a>
<main id="main-content" class="children-learning" data-legal-learning data-examples-url="{{ route('knowledge.examples', $slug) }}">
    <header class="knowledge-hero children-learning-hero">
        <div class="knowledge-title-row"><p class="eyebrow"><span></span>{{ $guide['eyebrow'] }}</p><a class="knowledge-home" href="{{ url('/') }}"><svg viewBox="0 0 24 24" aria-hidden="true"><path d="m4 10 8-7 8 7v10h-6v-6h-4v6H4Z"/></svg><span>Home</span></a></div>
        <a class="text-link learning-examples-link" href="{{ route('knowledge.examples', $slug) }}">Explore examples <span aria-hidden="true">→</span></a>
    </header>

    <aside class="law-scope"><strong>Legal scope</strong><p>{{ $guide['scope'] }} This is general legal education, not advice on a particular case or order.</p></aside>

    <nav class="learning-step-nav" aria-label="{{ $guide['eyebrow'] }} learning topics">
        @foreach ($guide['labels'] as $label)<button type="button" data-learning-tab="{{ $loop->iteration }}" aria-current="{{ $loop->first ? 'step' : 'false' }}"><span>{{ $loop->iteration }}</span>{{ $label }}</button>@endforeach
    </nav>
    <div class="learning-progress"><span data-learning-label>Topic 1 of 6</span><div><i data-learning-progress></i></div></div>

    @foreach ($guide['steps'] as $step)
        <section class="learning-panel" data-learning-panel="{{ $loop->iteration }}" @if (!$loop->first) hidden @endif>
            <p class="learning-kicker">Topic {{ $loop->iteration }}</p><h2>{{ $step['title'] }}</h2><p class="learning-lead">{{ $step['intro'] }}</p>
            <div class="learning-grid">
                @foreach ($step['cards'] as [$title, $description])<article><h3>{{ $title }}</h3><p>{{ $description }}</p></article>@endforeach
            </div>
            <details class="law-reference"><summary><span>Legal reference</span><strong>{{ $step['reference'] }}</strong><i>+</i></summary><div><p>{{ $step['law'] }}</p></div></details>
        </section>
    @endforeach

    <div class="learning-controls"><button type="button" class="button button-outline" data-learning-previous disabled><span>←</span> Previous</button><button type="button" class="button" data-learning-next>Next topic <span>→</span></button></div>
    <aside class="knowledge-disclaimer"><strong>General information, not legal advice.</strong> Outcomes depend on the evidence, applicable law, Court orders, and each family’s circumstances.</aside>
</main>
<footer class="knowledge-footer">LegalDIY provides general legal information, not legal advice.</footer>
</body>
</html>
