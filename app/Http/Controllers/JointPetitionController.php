<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class JointPetitionController extends Controller
{
    public function show(string $slug = 'index'): View
    {
        $pages = collect(config('joint_divorce', []));
        $page = $pages->firstWhere('slug', $slug);
        abort_unless($page, 404);

        $contentPath = resource_path("content/joint-divorce/{$slug}.md");
        abort_unless(is_file($contentPath), 404);

        $body = file_get_contents($contentPath);
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $page['title'],
            'description' => $page['description'],
            'mainEntityOfPage' => url()->current(),
            'publisher' => ['@type' => 'Organization', 'name' => 'LegalDIY'],
        ];

        return view('joint-petition.page', compact('page', 'pages', 'body', 'schema'));
    }
}
