@extends('wacana::layouts.app', ['title' => 'Berita Terbaru '.$tag['name']])

@php
    $items = $rt->withTag($tag['slug']);
    $first = end($items);
@endphp

@section('content')
    <div class="mx-auto mt-12 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="lg:col-span-8">
            <p class="text-sm font-semibold text-java">Topik</p>
            <h1 class="wc-ui-display mt-2 text-5xl lg:text-6xl">{{ $tag['name'] }}</h1>
            <p class="mt-4 text-lg text-graphite">
                {{ count($items) }} tulisan tentang {{ $tag['name'] }}@if ($first), pertama kali diberitakan {{ $rt->date($first['published'], false) }}@endif.
            </p>
            <div id="tag-list" class="mt-10 divide-y divide-ink/10 border-t-[3px] border-ink">
                @foreach (array_slice($items, 0, 10) as $item)
                    <x-wacana::entry :article="$item" :rt="$rt" />
                @endforeach
            </div>
            @if (count($items) > 10)
                <x-wacana::more target="#tag-list" :scope="'tag:'.$tag['slug']" :offset="10" />
            @endif
        </div>
        <div class="lg:col-span-4">@include('wacana::partials.aside')</div>
    </div>
@endsection
