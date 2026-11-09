@extends('default::layouts.app', ['title' => 'Topik & Tag'])

@php
    $topics = $rt->hitTopics(10, 260);
    $all = collect($rt->tags())->sortByDesc('count')->take(80)->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE);
@endphp

@section('content')
    <div class="mx-auto grid max-w-7xl gap-10 px-4 pt-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0">
            <header class="border-b border-line pb-5">
                <h1 class="rt-section-title !text-3xl">Topik &amp; Tag</h1>
                <p class="mt-2 text-sm text-muted">Pantau isu yang sedang ramai dan berita terbaru di setiap topik.</p>
            </header>

            <div class="mt-8 space-y-12">
                @foreach ($topics as $topic)
                    @php $topicItems = array_slice($rt->withTag($topic['slug']), 0, 3); @endphp
                    <section>
                        <x-default::section-head :title="'#'.$topic['name']" :href="$topic['url']" :more="$topic['count'].' berita'" />
                        <div class="grid gap-5 sm:grid-cols-3">
                            @foreach ($topicItems as $item)
                                <x-default::card.tile :article="$item" :rt="$rt" :excerpt="false" :boxed="false" />
                            @endforeach
                        </div>
                    </section>
                @endforeach
            </div>

            <section class="mt-14">
                <x-default::section-head title="Semua topik" />
                <div class="flex flex-wrap gap-2">
                    @foreach ($all as $tag)
                        <a href="{{ $tag['url'] }}" class="rt-chip gap-1.5">{{ $tag['name'] }} <span class="text-muted">{{ $tag['count'] }}</span></a>
                    @endforeach
                </div>
            </section>
        </div>
        @include('default::partials.sidebar')
    </div>
@endsection
