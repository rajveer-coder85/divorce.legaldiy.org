<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class InquiryDashboardController extends Controller
{
    public function show(Inquiry $inquiry): View
    {
        $inquiry->load(['communications' => fn ($query) => $query->oldest('sent_at'), 'communications.user']);

        return view('inquiry-dashboard-show', compact('inquiry'));
    }

    public function updateStatus(Request $request, Inquiry $inquiry): RedirectResponse
    {
        $data = $request->validate(['status' => ['required', Rule::in(['new', 'ongoing', 'completed'])]]);
        $inquiry->update($data);

        return back()->with('status_updated', true);
    }

}
