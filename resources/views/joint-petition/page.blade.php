@extends('joint-petition.layout')
@section('title', $page['seo'])
@section('description', $page['description'])
@push('head')<script type="application/ld+json">{!! json_encode($schema, JSON_UNESCAPED_SLASHES) !!}</script>@endpush
@section('content')
<div class="container crumb"><a href="/joint-petition">Joint Petition Knowledge Centre</a>@if($page['slug'] !== 'index')<span>/</span>{{ $page['h1'] }}@endif</div>
<section class="hero"><div class="container"><p class="eyebrow">Malaysia civil divorce</p><h1>{{ $page['h1'] }}</h1><p class="lead">{{ $page['description'] }}</p></div></section>
<div class="container"><article class="content article">{!! Str::markdown($body) !!}</article></div>
<section class="next"><div class="container"><h2>Continue Through the Joint Petition Centre</h2><nav class="page-grid">@foreach($pages->where('slug','!=',$page['slug']) as $item)<a class="page-card" href="{{ $item['url'] }}">{{ $item['h1'] }} &rarr;</a>@endforeach</nav></div></section>
@endsection
