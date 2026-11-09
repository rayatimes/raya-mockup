@extends('default::layouts.app', ['title' => $article['title'], 'description' => $article['excerpt']])

@php
    $blocks = $rt->contentBlocks($article);
    $related = $rt->related($article, 6);
    $readAlso = array_slice($related, 0, 2);
    $parent = $article['category']['parent'] ? $rt->category($article['category']['parent']) : null;
    $layout = $rt->articleLayout($article, request()->query('tata'));
    $sameCategory = array_slice(array_values(array_filter($rt->inCategory($article['category']['slug']), fn ($a) => $a['slug'] !== $article['slug'])), 0, 4);
@endphp

@section('content')
    <div class="fixed inset-x-0 top-0 z-[71] h-[3px] origin-left scale-x-0 bg-raya" id="rt-read-progress" aria-hidden="true"></div>

    @if ($layout === 'cover')
        {{-- Cover dulu: foto lebar di atas, judul dan penulis menyusul di bawahnya --}}
        <figure class="mx-auto max-w-7xl px-4 pt-6">
            <x-default::img :src="$article['image']['lg']" :alt="$article['title']" eager hero class="aspect-video lg:aspect-[21/9]" />
            @if ($article['caption'])<figcaption class="mt-2 text-xs text-muted">{{ $article['caption'] }}</figcaption>@endif
        </figure>
    @endif

    <div class="mx-auto grid max-w-7xl gap-10 px-4 pt-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <article class="min-w-0">
            @include($layout === 'cover' ? 'default::partials.article.head-cover' : 'default::partials.article.head-title')

            <div class="mx-auto mt-8 max-w-[44rem]">
                <div class="rt-prose" data-article-body>
                    @foreach ($blocks as $i => $block)
                        {!! $block !!}
                        @if (($i === 3 && isset($readAlso[0])) || ($i === 9 && isset($readAlso[1])))
                            @php $also = $readAlso[$i === 3 ? 0 : 1]; @endphp
                            <aside data-card class="group !my-8 flex items-center gap-4 border border-line border-l-4 border-l-raya bg-surface p-3">
                                <a href="{{ $also['url'] }}" class="block w-24 shrink-0" tabindex="-1" aria-hidden="true"><x-default::img :src="$also['image']['sm']" :alt="$also['title']" class="aspect-[4/3]" /></a>
                                <div>
                                    <p class="rt-kanal">Baca juga</p>
                                    <a href="{{ $also['url'] }}" class="rt-title mt-1 block text-base leading-snug !font-semibold !no-underline">{{ $also['title'] }}</a>
                                </div>
                            </aside>
                        @endif
                    @endforeach
                </div>

                <div class="mt-10 flex flex-wrap items-center gap-2 border-t border-line pt-6">
                    <button type="button" data-react data-on="ri-heart-fill" data-off="ri-heart-line" aria-pressed="false" class="rt-btn !py-2 aria-[pressed=true]:border-raya aria-[pressed=true]:text-accent">
                        <i class="ri-heart-line"></i><span data-count>{{ $article['views'] % 97 }}</span> Suka
                    </button>
                    <button type="button" data-react data-on="ri-bookmark-fill" data-off="ri-bookmark-line" aria-pressed="false" class="rt-btn !py-2 aria-[pressed=true]:border-raya aria-[pressed=true]:text-accent">
                        <i class="ri-bookmark-line"></i>Simpan
                    </button>
                    <p class="rt-meta ml-auto">{{ number_format($article['views'], 0, ',', '.') }} kali dibaca</p>
                </div>

                @if ($article['tag_list'])
                    <div class="mt-6">
                        <h2 class="text-xs font-bold text-muted">Topik terkait</h2>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($article['tag_list'] as $tag)
                                <a href="{{ $tag['url'] }}" class="rt-chip">#{{ $tag['name'] }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif

                @foreach ($article['authors'] as $author)
                    <div class="mt-8 flex gap-4 border border-line bg-sunk p-5">
                        <x-default::avatar :author="$author" size="size-16" class="text-2xl" />
                        <div>
                            <p class="text-xs text-muted">{{ $author['label'] }}</p>
                            <a href="{{ $author['url'] }}" class="font-serif text-lg font-semibold hover:text-accent">{{ $author['name'] }}</a>
                            <p class="mt-1 text-sm text-muted">{{ $author['bio'] ?: 'Menulis untuk RayaTimes: fakta yang utuh, konteks yang jernih.' }}</p>
                            <p class="mt-2 text-xs text-muted"><strong class="text-fg">{{ $author['followers'] }}</strong> pengikut</p>
                        </div>
                        <a href="{{ $author['url'] }}" class="rt-btn ml-auto hidden self-start !py-2 sm:inline-flex"><i class="ri-user-add-line"></i>Ikuti</a>
                    </div>
                @endforeach
            </div>

            <section class="mt-14">
                <x-default::section-head title="Berita terkait topik" />
                <div class="grid gap-x-6 gap-y-2 sm:grid-cols-2">
                    @foreach ($related as $item)
                        <x-default::card.row :article="$item" :rt="$rt" class="border-b border-line" />
                    @endforeach
                </div>
            </section>
        </article>

        <aside class="space-y-8 lg:sticky lg:top-16 lg:self-start">
            <x-default::widget.latest :rt="$rt" :exclude="[$article['slug']]" />
            <x-default::widget.trending :rt="$rt" :limit="5" />
            <x-default::widget.popular :rt="$rt" />
            @if ($sameCategory)
                <section class="rt-panel p-4">
                    <x-default::section-head :title="$article['category']['name']" :href="$article['category']['url']" more="Selengkapnya" class="!mb-1" />
                    <x-default::card.tile :article="$sameCategory[0]" :rt="$rt" :excerpt="false" :boxed="false" class="pt-3" />
                    <div class="divide-y divide-line">
                        @foreach (array_slice($sameCategory, 1) as $item)
                            <x-default::card.mini :article="$item" :rt="$rt" />
                        @endforeach
                    </div>
                </section>
            @endif
        </aside>
    </div>
@endsection
