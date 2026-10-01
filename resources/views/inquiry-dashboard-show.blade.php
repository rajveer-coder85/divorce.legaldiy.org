<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="robots" content="noindex,nofollow,noarchive">
    <title>{{ $inquiry->reference }} — LegalDIY</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="dashboard-page">
    <header class="dashboard-header">
        <div class="dashboard-shell dashboard-header-inner">
            <a class="brand" href="{{ route('journey.dashboard', ['tab' => 'inquiries']) }}"><span class="brand-mark"><img src="{{ asset('images/legal-diy-logo-new.png') }}" alt=""></span><span class="brand-name">LEGAL <strong>DIY</strong></span></a>
            <div class="dashboard-header-actions"><span class="dashboard-user">{{ auth()->user()->name }}</span><form method="POST" action="{{ route('dashboard.logout') }}">@csrf<button class="dashboard-logout">Log out</button></form></div>
        </div>
    </header>

    <main class="dashboard-shell dashboard-main inquiry-workspace">
        <a class="dashboard-back" href="{{ route('journey.dashboard', ['tab' => 'inquiries']) }}">← Back to enquiries</a>

        <section class="inquiry-workspace-head">
            <div><span class="submission-ref">{{ $inquiry->reference }}</span><h1>{{ $inquiry->full_name }}</h1><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></div>
            <form class="inquiry-status-form" method="POST" action="{{ route('inquiry.dashboard.status', $inquiry) }}">
                @csrf @method('PATCH')
                <label for="inquiry-status">Status</label>
                <select id="inquiry-status" name="status"><option value="new" @selected($inquiry->status==='new')>New</option><option value="ongoing" @selected($inquiry->status==='ongoing')>Ongoing</option><option value="completed" @selected($inquiry->status==='completed')>Completed</option></select>
                <button class="button button-small">Update</button>
            </form>
        </section>

        @if(session('status_updated'))<div class="case-success-note">Enquiry status updated.</div>@endif
        @if($errors->any())<div class="dashboard-login-error">{{ $errors->first() }}</div>@endif

        <section class="communication-section">
            <div class="dashboard-section-title"><div><p class="eyebrow"><span></span>Communication</p><h2>Customer history</h2></div><span>{{ $inquiry->communications->count() + 1 }} message{{ $inquiry->communications->count() === 0 ? '' : 's' }}</span></div>
            <div class="communication-table-wrap">
                <table class="communication-table">
                    <thead><tr><th>Date</th><th>Direction</th><th>From / To</th><th>Subject and message</th><th>Sent by</th></tr></thead>
                    <tbody>
                        <tr>
                            <td data-label="Date"><time>{{ $inquiry->submitted_at->format('d M Y, g:i A') }}</time></td>
                            <td data-label="Direction"><span class="direction-chip inbound">Customer</span></td>
                            <td data-label="From / To"><strong>From</strong><br>{{ $inquiry->email }}</td>
                            <td data-label="Message"><strong>Original enquiry</strong><p>{{ $inquiry->message }}</p></td>
                            <td data-label="Sent by">—</td>
                        </tr>
                        @foreach($inquiry->communications as $communication)
                            <tr>
                                <td data-label="Date"><time>{{ $communication->sent_at->format('d M Y, g:i A') }}</time></td>
                                <td data-label="Direction"><span class="direction-chip outbound">LegalDIY</span></td>
                                <td data-label="From / To"><strong>To</strong><br>{{ $communication->to_email }}</td>
                                <td data-label="Message"><strong>{{ $communication->subject }}</strong><p>{{ $communication->body }}</p></td>
                                <td data-label="Sent by">{{ $communication->user?->name ?? 'Administrator' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <p class="communication-note">This history records the original website enquiry and any emails previously sent from this dashboard.</p>
        </section>
    </main>
</body>
</html>
