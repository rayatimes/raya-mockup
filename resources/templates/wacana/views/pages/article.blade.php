@extends('wacana::layouts.app', ['title' => $article['title'], 'description' => $article['excerpt']])

@php
    $blocks = $rt->contentBlocks($article);
    $related = $rt->related($article, 6);
    $sidenotes = array_slice($related, 0, 2);
    // Baca juga selalu masuk badan artikel (juga artikel pendek), dan tidak memisahkan subjudul dari paragrafnya.
    $lastBlock = max(count($blocks) - 1, 0);
    $noteSlot = function (int $target) use ($blocks, $lastBlock): int {
        for ($i = min($target, $lastBlock); $i < $lastBlock; $i++) {
            if (! preg_match('/^\s*<h[1-6]\b/i', $blocks[$i])) {
                return $i;
            }
        }

        return $lastBlock;
    };
    $inlineNotes = array_filter([$noteSlot(min(4, max($lastBlock - 1, 0))) => $sidenotes[0] ?? null] + [$noteSlot(10) => $sidenotes[1] ?? null]);
    $layout = $rt->articleLayout($article, request()->query('tata'));
@endphp

@section('content')
    <article data-kanal="{{ $article['category']['slug'] }}">
        @include($layout === 'cover' ? 'wacana::partials.article.head-cover' : 'wacana::partials.article.head-title')

        <div class="mx-auto mt-12 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-[minmax(0,1fr)_20rem] lg:px-8 xl:grid-cols-[10rem_minmax(0,40rem)_minmax(0,1fr)]">
            {{-- Kolom kiri (layar lebar): waktu baca dan kontrol baca --}}
            <aside class="hidden space-y-8 xl:sticky xl:top-20 xl:block xl:self-start">
                @if ($layout === 'judul')
                    <div class="border-t-[6px] border-(--hue) pt-4 [.is-focus_&]:hidden">
                        <p class="text-[13px] font-semibold text-ash">Waktu baca</p>
                        <p class="wc-ui-display text-2xl">{{ $article['reading_time'] }} menit</p>
                    </div>
                @endif
                <div class="space-y-2">
                    <p class="text-[13px] font-semibold text-ash">Tampilan baca</p>
                    @include('wacana::partials.article.reader-controls')
                </div>
            </aside>

            <div class="mx-auto w-full min-w-0 max-w-[44rem] xl:max-w-none">
                {{-- Bilah baca ringkas (layar kecil & sedang) --}}
                <div class="mb-8 flex flex-wrap items-center justify-between gap-x-6 gap-y-3 border-y border-ink/10 py-3 xl:hidden">
                    @if ($layout === 'judul')<p class="text-[13px] text-ash"><i class="ri-time-line"></i> {{ $article['reading_time'] }} menit baca</p>@endif
                    <div class="flex items-center gap-5">
                        @include('wacana::partials.article.reader-controls')
                    </div>
                </div>

                <div class="wc-prose" data-article-body>
                    @foreach ($blocks as $i => $block)
                        {!! $block !!}
                        @if (isset($inlineNotes[$i]))
                            @php $note = $inlineNotes[$i]; @endphp
                            <aside data-card data-kanal="{{ $note['category']['slug'] }}" class="group !my-10 border-y-[3px] border-(--hue) py-5 [.is-focus_&]:hidden">
                                <p class="text-[13px] font-semibold text-(--hue)">Baca juga</p>
                                <a href="{{ $note['url'] }}" class="wc-head wc-link mt-1 block text-2xl">{{ $note['title'] }}</a>
                            </aside>
                        @endif
                    @endforeach
                </div>

                <div class="mt-12 flex flex-wrap items-center gap-2 border-t-[3px] border-ink pt-6">
                    <button type="button" data-press aria-pressed="false" class="wc-btn-line aria-[pressed=true]:!bg-java aria-[pressed=true]:text-paper"><i class="ri-bookmark-line"></i>Simpan untuk nanti</button>
                    <button type="button" data-copy class="wc-btn-line"><i class="ri-link"></i><span>Salin tautan</span></button>
                    <a href="#" class="wc-btn-line"><i class="ri-whatsapp-line"></i>Bagikan</a>
                </div>

                @if ($article['tag_list'])
                    <p class="mt-8 text-[15px] leading-loose">
                        <span class="text-ash">Topik:</span>
                        @foreach ($article['tag_list'] as $tag)<a href="{{ $tag['url'] }}" class="mr-3 font-semibold underline decoration-ink/20 decoration-2 underline-offset-4 hover:decoration-java">{{ $tag['name'] }}</a>@endforeach
                    </p>
                @endif

                @foreach ($article['authors'] as $author)
                    <div class="mt-10 flex gap-5 bg-stone p-6">
                        @if ($author['avatar'])
                            <img src="{{ $author['avatar'] }}" alt="" class="size-16 shrink-0 object-cover grayscale">
                        @else
                            <span class="wc-ui-display grid size-16 shrink-0 place-items-center bg-ink text-3xl text-paper">{{ mb_substr($author['name'], 0, 1) }}</span>
                        @endif
                        <div>
                            <p class="text-[13px] text-ash">{{ $author['label'] }}</p>
                            <a href="{{ $author['url'] }}" class="wc-ui-head text-lg hover:text-java">{{ $author['name'] }}</a>
                            <p class="mt-1 text-[15px] text-graphite">{{ $author['bio'] ?: 'Menulis untuk RayaTimes: fakta yang utuh, konteks yang jernih.' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Kolom kanan: sama dengan kolom samping beranda --}}
            <div class="mx-auto w-full min-w-0 max-w-[44rem] lg:max-w-none [.is-focus_&]:hidden">
                @include('wacana::partials.aside')
            </div>
        </div>
    </article>

    <section class="mx-auto mt-24 max-w-[84rem] px-4 lg:px-8">
        <x-wacana::section title="Untuk memahami lebih utuh" lede="Tulisan lain dengan topik yang sama." />
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (array_slice($related, 2, 4) as $item)
                <x-wacana::story-card :article="$item" :rt="$rt" size="text-xl" />
            @endforeach
        </div>
    </section>
@endsection
