@extends('wacana::layouts.app', ['title' => 'Berita '.$category['name'], 'description' => $category['description']])

@php
    $items = $rt->inCategory($category['slug']);
    $children = $rt->childCategories($category['slug']);
    $parent = $category['parent'] ? $rt->category($category['parent']) : null;
    $lead = $items[0] ?? null;
@endphp

@section('content')
    <header data-kanal="{{ $category['slug'] }}" class="bg-(--hue) text-(--on-hue)">
        <div class="mx-auto max-w-[84rem] px-4 py-14 lg:px-8 lg:py-20">
            @if ($parent)<a href="{{ $parent['url'] }}" class="text-sm font-semibold opacity-80 hover:opacity-100">{{ $parent['name'] }}</a>@endif
            <h1 class="wc-display text-6xl lg:text-8xl">{{ $category['name'] }}</h1>
            <p class="mt-4 max-w-2xl font-serif text-xl opacity-90">{{ $category['description'] }}</p>
            <div class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-2 text-sm">
                <button type="button" data-press aria-pressed="false" class="bg-paper px-4 py-2.5 font-semibold text-ink transition hover:bg-ink hover:text-paper aria-[pressed=true]:bg-ink aria-[pressed=true]:text-paper">Ikuti kanal</button>
                @foreach ($children as $child)<a href="{{ $child['url'] }}" class="font-semibold underline decoration-2 underline-offset-4 opacity-90 hover:opacity-100">{{ $child['name'] }}</a>@endforeach
            </div>
        </div>
    </header>

    <div class="mx-auto mt-12 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="lg:col-span-8">
            @if ($lead)
                <article data-card data-kanal="{{ $lead['category']['slug'] }}" class="group">
                    <a href="{{ $lead['url'] }}" tabindex="-1" aria-hidden="true"><x-wacana::photo :src="$lead['image']['lg']" :alt="$lead['title']" eager class="aspect-[16/9]" /></a>
                    <x-wacana::kanal :category="$lead['category']" class="mt-5" />
                    <h2 class="wc-display mt-2 text-4xl lg:text-5xl"><a href="{{ $lead['url'] }}" class="wc-link">{{ $lead['title'] }}</a></h2>
                    <p class="mt-3 font-serif text-xl leading-relaxed text-graphite">{{ $lead['excerpt'] }}</p>
                </article>
            @endif
            <div id="cat-list" class="mt-8 divide-y divide-ink/10 border-t-[3px] border-ink">
                @foreach (array_slice($items, 1, 10) as $item)
                    <x-wacana::entry :article="$item" :rt="$rt" />
                @endforeach
            </div>
            @if (count($items) > 11)
                <x-wacana::more target="#cat-list" :scope="'category:'.$category['slug']" :offset="11" />
            @endif
        </div>
        <div class="lg:col-span-4">@include('wacana::partials.aside')</div>
    </div>
@endsection
