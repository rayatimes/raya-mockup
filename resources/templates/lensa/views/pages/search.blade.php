@extends('lensa::layouts.app', ['title' => $query ? 'Cari “'.$query.'”' : 'Pencarian'])

@php
    $results = $query ? $rt->search($query) : [];
    $needle = mb_strtolower($query);
    $authors = $query ? array_values(array_filter($rt->authors(), fn ($a) => str_contains(mb_strtolower($a['name']), $needle))) : [];
    $tags = $query ? array_slice(array_values(array_filter($rt->tags(), fn ($t) => str_contains(mb_strtolower($t['name']), $needle))), 0, 20) : [];
@endphp

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <form action="{{ $rt->url('/search') }}" class="flex items-center gap-4 border-b-2 border-edge pb-4 focus-within:border-sun">
            <i class="ri-search-line text-3xl text-dim"></i>
            <label for="q-page" class="sr-only">Kata kunci</label>
            <input id="q-page" name="q" type="search" value="{{ $query }}" placeholder="Cari berita, topik, atau tokoh" class="ls-headline w-full bg-transparent text-4xl placeholder:text-dim/50 focus:outline-none lg:text-6xl">
            <button class="ls-btn shrink-0">Cari</button>
        </form>

        @if ($query)
            <p class="mt-4 text-dim"><strong class="text-ink">{{ count($results) }}</strong> cerita untuk “{{ $query }}”</p>
            @if ($authors || $tags)
                <div class="mt-6 flex flex-wrap gap-2">
                    @foreach ($authors as $author)<a href="{{ $author['url'] }}" class="ls-pill"><i class="ri-user-3-line"></i>{{ $author['name'] }}</a>@endforeach
                    @foreach ($tags as $tagItem)<a href="{{ $tagItem['url'] }}" class="ls-pill">#{{ $tagItem['name'] }}</a>@endforeach
                </div>
            @endif
            @if ($results)
                <div id="search-grid" class="mt-10 grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
                    @foreach (array_slice($results, 0, 12) as $item)
                        <x-lensa::card.photo :article="$item" :rt="$rt" />
                    @endforeach
                </div>
                @if (count($results) > 12)
                    <x-lensa::more target="#search-grid" :scope="'search:'.$query" :offset="12" />
                @endif
            @else
                <div class="mt-16 text-center">
                    <p class="ls-headline text-4xl">Belum ada cerita tentang “{{ $query }}”</p>
                    <p class="mt-2 text-dim">Coba kata kunci yang lebih umum, atau jelajahi topik yang sedang ramai.</p>
                </div>
            @endif
        @endif

        @if (! $query || ! $results)
            <section class="mt-14">
                <x-lensa::head title="Sedang ramai" />
                <div class="flex flex-wrap gap-2">
                    @foreach ($rt->hitTopics(14) as $topic)<a href="{{ $rt->url('/search?q='.urlencode($topic['name'])) }}" class="ls-pill">{{ $topic['name'] }}</a>@endforeach
                </div>
            </section>
        @endif
    </div>
@endsection
