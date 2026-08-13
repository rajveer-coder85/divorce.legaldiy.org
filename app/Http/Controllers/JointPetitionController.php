<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\View\View;

class JointPetitionController extends Controller
{
    public function show(string $slug = 'index'): View
    {
        $manifestPath = resource_path('content/joint-petition/pages.json');
        abort_unless(File::isFile($manifestPath), 404);

        $pages = collect(json_decode(File::get($manifestPath), true, flags: JSON_THROW_ON_ERROR));
        $page = $pages->firstWhere('slug', $slug);
        abort_unless($page, 404);

        $contentPath = resource_path("content/joint-petition/{$slug}.md");
        abort_unless(File::isFile($contentPath), 404);

        $body = File::get($contentPath);
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $page['h1'],
            'description' => $page['description'],
            'mainEntityOfPage' => url()->current(),
            'publisher' => ['@type' => 'Organization', 'name' => 'LegalDIY'],
        ];

        return view('joint-petition.page', compact('page', 'pages', 'body', 'schema'));
    }
}
