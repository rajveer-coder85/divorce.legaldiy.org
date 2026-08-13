@extends('joint-petition.layout')
@section('title', $page['seo'])
@section('description', $page['description'])
@push('head')<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}</script>@endpush
@section('content')
@php $index=$pages->search(fn($item)=>$item['slug']===$page['slug']); $previous=$index>0?$pages[$index-1]:null; $next=$index<$pages->count()-1?$pages[$index+1]:null; @endphp
<div class="container crumb"><a href="/joint-divorce">Joint Petition Knowledge Centre</a>@if($page['slug'] !== 'index')<span>/</span>{{ $page['title'] }}@endif</div>
<div class="progress-wrap"><div class="container"><div class="progress-meta"><span>Joint Petition learning journey</span><span>Step {{ $index+1 }} of {{ $pages->count() }}</span></div><div class="progress-track" role="progressbar" aria-label="Learning journey progress" aria-valuemin="1" aria-valuemax="{{ $pages->count() }}" aria-valuenow="{{ $index+1 }}"><div class="progress-value" style="width:{{ (($index+1)/$pages->count())*100 }}%"></div></div></div></div>
<section class="hero"><div class="container"><p class="eyebrow">Malaysia civil divorce</p><h1>{{ $page['title'] }}</h1><p class="lead">{{ $page['description'] }}</p></div></section>
<div class="container"><article class="content article page-{{ $page['slug'] }}">{!! Str::markdown($body) !!}</article></div>
<section class="next"><div class="container"><nav class="journey-nav" aria-label="Learning journey navigation">@if($previous)<a class="journey-link" href="{{ $previous['url'] }}"><small>Previous topic</small><strong>&larr; {{ $previous['title'] }}</strong></a>@else<span></span>@endif @if($next)<a class="journey-link" href="{{ $next['url'] }}"><small>Continue learning</small><strong>{{ $next['title'] }} &rarr;</strong></a>@endif</nav></div></section>
@endsection
