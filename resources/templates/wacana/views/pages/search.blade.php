@extends('wacana::layouts.app', ['title' => $query ? 'Cari “'.$query.'”' : 'Pencarian'])

@php
    $results = $query ? $rt->search($query) : [];
    $needle = mb_strtolower($query);
    $authors = $query ? array_values(array_filter($rt->authors(), fn ($a) => str_contains(mb_strtolower($a['name']), $needle))) : [];
    $tags = $query ? array_slice(array_values(array_filter($rt->tags(), fn ($t) => str_contains(mb_strtolower($t['name']), $needle))), 0, 12) : [];
@endphp

@section('content')
    <div class="mx-auto mt-12 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="lg:col-span-8">
            <form action="{{ $rt->url('/search') }}" class="flex items-end gap-3 border-b-[3px] border-ink pb-3">
                <label for="q-page" class="sr-only">Kata kunci</label>
                <input id="q-page" name="q" type="search" value="{{ $query }}" placeholder="Cari isu, tokoh, atau peristiwa…" class="wc-display w-full bg-transparent text-4xl placeholder:text-ash/50 focus:outline-none lg:text-5xl">
                <button class="wc-btn shrink-0">Cari</button>
            </form>
            @if ($query)
                <p class="mt-4 font-serif text-lg text-graphite">{{ count($results) }} tulisan memuat “{{ $query }}”.</p>
                @if ($tags || $authors)
                    <p class="mt-4 text-[15px] leading-loose">
                        <span class="text-ash">Mungkin maksud Anda:</span>
                        @foreach ($tags as $tagItem)<a href="{{ $tagItem['url'] }}" class="mr-3 font-semibold underline decoration-2 underline-offset-4 hover:text-java">{{ $tagItem['name'] }}</a>@endforeach
                        @foreach ($authors as $author)<a href="{{ $author['url'] }}" class="mr-3 font-semibold underline decoration-2 underline-offset-4 hover:text-java">{{ $author['name'] }} (penulis)</a>@endforeach
                    </p>
                @endif
                <div id="search-list" class="mt-8 divide-y divide-ink/10">
                    @forelse (array_slice($results, 0, 10) as $item)
                        <x-wacana::entry :article="$item" :rt="$rt" />
                    @empty
                        <div class="bg-stone p-8">
                            <p class="wc-display text-3xl">Belum ada tulisan tentang “{{ $query }}”</p>
                            <p class="mt-2 font-serif text-lg text-graphite">Coba kata yang lebih umum, atau telusuri indeks topik.</p>
                            <a href="{{ $rt->url('/tag') }}" class="wc-btn mt-5">Buka indeks topik</a>
                        </div>
                    @endforelse
                </div>
                @if (count($results) > 10)
                    <x-wacana::more target="#search-list" :scope="'search:'.$query" :offset="10" />
                @endif
            @else
                <div class="mt-10"><x-wacana::topics :rt="$rt" :limit="10" /></div>
            @endif
        </div>
        <div class="lg:col-span-4">@include('wacana::partials.aside')</div>
    </div>
@endsection
