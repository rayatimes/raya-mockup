@extends('wacana::layouts.app', ['title' => 'Berita '.$category['name'], 'description' => $category['description']])

@php
    $items = $rt->inCategory($category['slug']);
    $children = $rt->childCategories($category['slug']);
    $parent = $category['parent'] ? $rt->category($category['parent']) : null;
    $lead = $items[0] ?? null;
@endphp

@section('content')
    <header data-kanal="{{ $category['slug'] }}" class="relative isolate overflow-hidden bg-(--hue) text-(--on-hue)">
        <x-wacana::kanal-art :slug="$category['slug']" class="absolute right-0 top-0 -z-10 aspect-[1600/420] h-44 max-w-none lg:bottom-0 lg:top-auto lg:h-2/3 xl:h-full" />
        <div class="mx-auto max-w-[84rem] px-4 pb-10 pt-48 lg:px-8 lg:py-20">
            @if ($parent)<a href="{{ $parent['url'] }}" class="text-sm font-semibold opacity-80 hover:opacity-100">{{ $parent['name'] }}</a>@endif
            <h1 class="wc-ui-display text-5xl lg:text-7xl">{{ $category['name'] }}</h1>
            <p class="mt-4 max-w-xl text-lg opacity-90 2xl:max-w-2xl">{{ $category['description'] }}</p>
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
                    <p class="mt-3 text-lg leading-relaxed text-graphite">{{ $lead['excerpt'] }}</p>
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
