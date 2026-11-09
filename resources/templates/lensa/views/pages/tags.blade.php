@extends('lensa::layouts.app', ['title' => 'Topik & Tag'])

@php
    $topics = $rt->hitTopics(9, 260);
    $all = collect($rt->tags())->sortByDesc('count')->take(60);
@endphp

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <h1 class="ls-headline text-6xl lg:text-8xl">Topik</h1>
        <p class="mt-3 max-w-xl text-dim">Isu yang sedang ramai, lengkap dengan cerita terbarunya.</p>
        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($topics as $i => $topic)
                <a href="{{ $topic['url'] }}" data-card data-theme="dark" @class(['group relative isolate block overflow-hidden rounded-frame bg-raise', 'aspect-[4/5] sm:col-span-2 sm:row-span-2 sm:aspect-auto' => $i === 0, 'aspect-[16/10]' => $i > 0])>
                    <x-lensa::frame :src="$topic['cover']['image'][$i === 0 ? 'lg' : 'sm']" alt="" class="absolute inset-0 -z-10" />
                    <span class="ls-shade absolute inset-0 -z-10"></span>
                    <span class="absolute inset-x-5 bottom-5">
                        <span class="text-xs font-semibold text-sun">{{ $topic['count'] }} berita</span>
                        <span @class(['ls-headline block', 'text-6xl' => $i === 0, 'text-3xl' => $i > 0])>#{{ $topic['name'] }}</span>
                        <span class="mt-2 block text-sm text-ink/75 line-clamp-1">Terbaru: {{ $topic['cover']['title'] }}</span>
                    </span>
                </a>
            @endforeach
        </div>
        <section class="mt-16">
            <x-lensa::head title="Semua topik" />
            <div class="flex flex-wrap gap-2">
                @foreach ($all as $tag)<a href="{{ $tag['url'] }}" class="ls-pill">#{{ $tag['name'] }} <span class="text-xs opacity-60">{{ $tag['count'] }}</span></a>@endforeach
            </div>
        </section>
    </div>
@endsection
