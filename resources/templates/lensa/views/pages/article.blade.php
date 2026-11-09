@extends('lensa::layouts.app', ['overlay' => $layout === 'cover', 'title' => $article['title'], 'description' => $article['excerpt']])

@php
    $blocks = $rt->contentBlocks($article);
    $related = $rt->related($article, 8);
    $outline = $rt->outline($article);
    $layout = $rt->articleLayout($article, request()->query('tata'));
@endphp

@section('content')
    @include($layout === 'cover' ? 'lensa::partials.article.head-cover' : 'lensa::partials.article.head-title')

    <div class="mx-auto mt-12 grid max-w-6xl gap-12 px-4 lg:grid-cols-[13rem_minmax(0,1fr)] lg:px-8">
        {{-- Rel kiri: bagikan & daftar isi --}}
        <aside class="order-2 lg:order-1 lg:sticky lg:top-28 lg:self-start">
            <div class="flex flex-wrap gap-2 lg:flex-col">
                <button type="button" data-toggle-press aria-pressed="false" class="ls-pill aria-[pressed=true]:bg-raya aria-[pressed=true]:text-ink"><i class="ri-heart-3-line"></i><span data-count>{{ $article['views'] % 97 }}</span></button>
                <button type="button" data-toggle-press aria-pressed="false" class="ls-pill aria-[pressed=true]:bg-sun aria-[pressed=true]:text-ground"><i class="ri-bookmark-line"></i>Simpan</button>
                <button type="button" data-copy class="ls-pill"><i class="ri-link"></i><span data-copy-label>Salin tautan</span></button>
                <a href="#" class="ls-pill"><i class="ri-whatsapp-line"></i>WhatsApp</a>
            </div>
            @if (count($outline) > 1)
                <nav class="mt-8 hidden lg:block" aria-label="Isi artikel">
                    <p class="text-xs font-semibold text-dim">Di artikel ini</p>
                    <ol class="mt-3 space-y-2.5 border-l border-edge text-sm">
                        @foreach ($outline as $heading)
                            <li class="-ml-px border-l-2 border-transparent pl-3 text-ink/70 hover:border-sun hover:text-ink">{{ $heading }}</li>
                        @endforeach
                    </ol>
                </nav>
            @endif
        </aside>

        <article class="order-1 min-w-0 lg:order-2">
            <div class="ls-read max-w-[42rem]" data-article-body>
                @foreach ($blocks as $i => $block)
                    {!! $block !!}
                    @if ($i === 4 && isset($related[0]))
                        <aside data-card data-theme="dark" class="group !my-10">
                            <a href="{{ $related[0]['url'] }}" class="relative isolate block aspect-[16/8] overflow-hidden rounded-frame">
                                <x-lensa::frame :src="$related[0]['image']['sm']" :alt="$related[0]['title']" class="absolute inset-0 -z-10" />
                                <span class="ls-shade absolute inset-0 -z-10"></span>
                                <span class="absolute inset-x-5 bottom-4 font-sans"><span class="ls-kanal">Baca juga</span><span class="ls-headline mt-1 block text-2xl">{{ $related[0]['title'] }}</span></span>
                            </a>
                        </aside>
                    @endif
                @endforeach
            </div>

            @if ($article['tag_list'])
                <div class="mt-12 flex flex-wrap gap-2">
                    @foreach ($article['tag_list'] as $tag)
                        <a href="{{ $tag['url'] }}" class="ls-pill">#{{ $tag['name'] }}</a>
                    @endforeach
                </div>
            @endif
        </article>
    </div>

    <div class="mt-24 space-y-16">
        <x-lensa::rail title="Cerita terkait" :items="array_slice($related, 1)" :rt="$rt" />
        <x-lensa::rail :title="'Lainnya di '.$article['category']['name']" :href="$article['category']['url']" :items="array_slice(array_values(array_filter($rt->inCategory($article['category']['slug']), fn ($a) => $a['slug'] !== $article['slug'])), 0, 10)" :rt="$rt" />
    </div>
@endsection
