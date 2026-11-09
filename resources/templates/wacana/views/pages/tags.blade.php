@extends('wacana::layouts.app', ['title' => 'Topik & Tag'])

@php
    $topics = $rt->hitTopics(8, 260);
    $groups = collect($rt->tags())->filter(fn ($t) => $t['count'] > 1)->sortBy('name', SORT_NATURAL | SORT_FLAG_CASE)
        ->groupBy(fn ($t) => ctype_alpha($c = mb_strtoupper(mb_substr($t['name'], 0, 1))) ? $c : '#');
@endphp

@section('content')
    <div class="mx-auto mt-12 max-w-[84rem] px-4 lg:px-8">
        <h1 class="wc-ui-display text-5xl lg:text-6xl">Topik</h1>
        <p class="mt-3 max-w-2xl text-lg text-graphite">Setiap topik menghimpun tulisan dari berbagai kanal agar satu isu bisa dibaca utuh.</p>

        <div class="mt-12 grid gap-x-10 gap-y-12 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($topics as $topic)
                <section data-kanal="{{ $topic['cover']['category']['slug'] }}">
                    <a href="{{ $topic['url'] }}" class="group block border-t-[6px] border-(--hue) pt-3">
                        <h2 class="wc-ui-display text-2xl group-hover:text-java">{{ $topic['name'] }}</h2>
                        <p class="wc-meta mt-1">{{ $topic['count'] }} tulisan</p>
                    </a>
                    <ul class="mt-4 space-y-3">
                        @foreach (array_slice($rt->withTag($topic['slug']), 0, 3) as $item)
                            <li><a href="{{ $item['url'] }}" class="font-serif text-[17px] leading-snug hover:text-java">{{ $item['title'] }}</a></li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>

        <section class="mt-20">
            <x-wacana::section title="Indeks topik A–Z" />
            <div class="columns-2 gap-10 sm:columns-3 lg:columns-5">
                @foreach ($groups as $letter => $tags)
                    <div class="mb-6 break-inside-avoid">
                        <p class="wc-ui-display text-2xl text-java">{{ $letter }}</p>
                        <ul class="mt-1 space-y-1 text-[15px]">
                            @foreach ($tags as $tag)<li><a href="{{ $tag['url'] }}" class="hover:text-java">{{ $tag['name'] }}</a> <span class="text-ash">{{ $tag['count'] }}</span></li>@endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </section>
    </div>
@endsection
