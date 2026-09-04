<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Plain-language LegalDIY guidance about {{ strtolower($guide['eyebrow']) }}.">
    <meta name="theme-color" content="#12385f">
    <title>{{ $guide['eyebrow'] }} — LegalDIY Knowledge</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="knowledge-page">
    <a class="skip-link" href="#main-content">Skip to content</a>
    <main id="main-content" class="knowledge-article">
        <header class="knowledge-hero">
            <p class="eyebrow"><span></span>{{ $guide['eyebrow'] }}</p>
            <h1>{{ $guide['title'] }}</h1>
            <p>{{ $guide['intro'] }}</p>
        </header>

        <section class="knowledge-points" aria-label="Topics to discuss">
            @foreach ($guide['sections'] as [$title, $description])
                <article>
                    <span>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                    <div><h2>{{ $title }}</h2><p>{{ $description }}</p></div>
                </article>
            @endforeach
        </section>

        <aside class="knowledge-example">
            <p>Example situation</p>
            <strong>{{ $guide['example'] }}</strong>
            <small>This is an example for discussion, not legal advice or a required outcome.</small>
        </aside>

        <div class="knowledge-actions">
            <a class="button button-outline" href="{{ url('/#topics') }}">Explore other topics</a>
            <a class="button" href="{{ route('journey') }}">Start your journey <span>→</span></a>
        </div>
    </main>

    <footer class="knowledge-footer">LegalDIY provides general legal information, not legal advice.</footer>
</body>
</html>
