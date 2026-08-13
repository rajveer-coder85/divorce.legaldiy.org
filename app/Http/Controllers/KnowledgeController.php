<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class KnowledgeController extends Controller
{
    public function page(string $slug): View
    {
        abort_unless($slug === 'assessment', 404);

        return view('assessment');
    }
}
