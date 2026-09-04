<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <meta name="theme-color" content="#12385f">
    <title>Vetting Dashboard — LegalDIY</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="dashboard-page">
    <header class="dashboard-header">
        <div class="dashboard-shell dashboard-header-inner">
            <a class="brand" href="{{ url('/') }}" aria-label="LegalDIY home"><span class="brand-mark" aria-hidden="true"><img src="{{ asset('images/legal-diy-logo.png') }}" alt=""></span><span class="brand-name">LEGAL <strong>DIY</strong></span></a>
            <a class="dashboard-back" href="{{ route('journey') }}">View applicant form <span>→</span></a>
        </div>
    </header>

    <main class="dashboard-shell dashboard-main">
        <div class="dashboard-title-row">
            <div><p class="eyebrow"><span></span>Internal review</p><h1>Vetting dashboard</h1><p>Review applicant submissions and identify who may need additional guidance.</p></div>
            <span class="temporary-access">Temporary open access</span>
        </div>

        <aside class="dashboard-warning"><strong>No login is enabled yet.</strong> Identity numbers are masked. Add authentication before using this dashboard outside a controlled environment.</aside>

        <section class="dashboard-stats" aria-label="Submission overview">
            <article><span>Total submissions</span><strong>{{ number_format($stats['total']) }}</strong></article>
            <article><span>Pending review</span><strong>{{ number_format($stats['pending']) }}</strong></article>
            <article><span>Submitted today</span><strong>{{ number_format($stats['today']) }}</strong></article>
            <article><span>Both agree</span><strong>{{ number_format($stats['agreed']) }}</strong></article>
        </section>

        <form class="dashboard-filters" method="GET" action="{{ route('journey.dashboard') }}">
            <label><span class="sr-only">Search submissions</span><input type="search" name="search" value="{{ $search }}" placeholder="Search name, email, or reference"></label>
            <label><span class="sr-only">Filter by status</span><select name="status"><option value="all" @selected($status === 'all')>All statuses</option><option value="pending_review" @selected($status === 'pending_review')>Pending review</option><option value="reviewed" @selected($status === 'reviewed')>Reviewed</option></select></label>
            <button class="button" type="submit">Filter</button>
            @if ($search !== '' || $status !== 'all')<a class="dashboard-clear" href="{{ route('journey.dashboard') }}">Clear</a>@endif
        </form>

        <section class="submission-list" aria-label="Vetting submissions">
            @forelse ($submissions as $submission)
                <article class="submission-card">
                    <div class="submission-card-head">
                        <div><span class="submission-ref">{{ $submission->reference }}</span><h2>{{ $submission->full_name }}</h2><a href="mailto:{{ $submission->email }}">{{ $submission->email }}</a></div>
                        <div class="submission-card-status"><span class="status-chip {{ $submission->status }}">{{ str($submission->status)->replace('_', ' ')->title() }}</span><time datetime="{{ $submission->submitted_at->toIso8601String() }}">{{ $submission->submitted_at->format('d M Y, g:i A') }}</time></div>
                    </div>

                    <dl class="submission-details">
                        <div><dt>Phone</dt><dd>{{ $submission->phone ?: 'Not provided' }}</dd></div>
                        <div><dt>{{ $submission->identity_type === 'nric' ? 'NRIC' : 'Passport' }}</dt><dd>{{ $submission->masked_identity_number }}</dd></div>
                        <div><dt>Education</dt><dd>{{ str($submission->education_level)->replace('_', ' ')->title() }}</dd></div>
                        <div><dt>Employment</dt><dd>{{ str($submission->employment_status)->replace('_', ' ')->title() }}</dd></div>
                        <div><dt>Preferred language</dt><dd>{{ str($submission->preferred_language)->replace('_', ' ')->title() }}</dd></div>
                        <div><dt>Court experience</dt><dd>{{ str($submission->court_experience)->replace('_', ' ')->title() }}</dd></div>
                        <div><dt>Document confidence</dt><dd><span class="confidence-score">{{ $submission->legal_document_confidence }}/5</span></dd></div>
                        <div><dt>Divorce agreement</dt><dd>{{ $submission->agreement_status === 'agree' ? 'Both agree' : 'Not yet agreed' }}</dd></div>
                    </dl>

                    <div class="submission-topics"><span>Relevant topics</span><div>@foreach ($submission->selected_topics as $topic)<b>{{ str($topic)->replace('_', ' ')->title() }}</b>@endforeach</div></div>
                    @if ($submission->support_needs)<div class="submission-support"><span>Support notes</span><p>{{ $submission->support_needs }}</p></div>@endif
                </article>
            @empty
                <div class="dashboard-empty"><span>⌕</span><h2>No submissions found</h2><p>New verified vetting forms will appear here.</p></div>
            @endforelse
        </section>

        @if ($submissions->hasPages())
            <nav class="dashboard-pagination" aria-label="Submissions pagination">
                @if ($submissions->onFirstPage())<span>← Previous</span>@else<a href="{{ $submissions->previousPageUrl() }}">← Previous</a>@endif
                <b>Page {{ $submissions->currentPage() }} of {{ $submissions->lastPage() }}</b>
                @if ($submissions->hasMorePages())<a href="{{ $submissions->nextPageUrl() }}">Next →</a>@else<span>Next →</span>@endif
            </nav>
        @endif
    </main>
</body>
</html>
