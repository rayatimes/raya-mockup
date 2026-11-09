@extends('default::layouts.app', ['title' => $query ? 'Cari “'.$query.'”' : 'Pencarian'])

@php
    $results = $query ? $rt->search($query) : [];
    $needle = mb_strtolower($query);
    $authors = $query ? array_values(array_filter($rt->authors(), fn ($a) => str_contains(mb_strtolower($a['name'].' '.$a['username']), $needle))) : [];
    $categories = $query ? array_values(array_filter($rt->categories(), fn ($c) => str_contains(mb_strtolower($c['name']), $needle))) : [];
    $tags = $query ? array_slice(array_values(array_filter($rt->tags(), fn ($t) => str_contains(mb_strtolower($t['name']), $needle))), 0, 30) : [];
    $tabs = ['berita' => ['Berita', count($results)], 'penulis' => ['Penulis', count($authors)], 'kanal' => ['Kanal', count($categories)], 'topik' => ['Topik', count($tags)]];
    $tab = isset($tabs[$tab]) ? $tab : 'berita';
@endphp

@section('content')
    <div class="mx-auto grid max-w-7xl gap-10 px-4 pt-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0">
            <header class="border border-line bg-surface p-6">
                <p class="rt-kanal flex items-center gap-1.5"><i class="ri-search-line"></i>Hasil pencarian</p>
                <form action="{{ $rt->url('/search') }}" class="mt-3 flex">
                    <label for="q-page" class="sr-only">Kata kunci</label>
                    <input id="q-page" name="q" type="search" value="{{ $query }}" placeholder="Ketik kata kunci, misalnya: karhutla" class="min-w-0 flex-1 border border-line bg-paper px-4 py-3 font-serif text-xl focus:border-raya focus:outline-none">
                    <button class="rt-btn rt-btn-solid !px-6">Cari</button>
                </form>
                @if ($query)<p class="rt-meta mt-3">{{ count($results) }} berita cocok dengan “{{ $query }}”.</p>@endif
            </header>

            @if ($query)
                <nav class="no-scrollbar mt-6 flex overflow-x-auto border-b border-line" aria-label="Jenis hasil">
                    @foreach ($tabs as $key => [$label, $count])
                        <a href="{{ $rt->url('/search?q='.urlencode($query).'&tab='.$key) }}" @if ($tab === $key) aria-current="page" @endif
                            class="-mb-px whitespace-nowrap border-b-2 border-transparent px-4 py-3 text-sm font-semibold text-muted transition hover:text-fg aria-[current=page]:border-raya aria-[current=page]:text-fg">
                            {{ $label }} <span class="ml-1 bg-sunk px-1.5 text-xs">{{ $count }}</span>
                        </a>
                    @endforeach
                </nav>

                <div class="mt-6">
                    @if ($tab === 'berita')
                        @if ($results)
                            <div id="search-list" class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                                @foreach (array_slice($results, 0, 12) as $item)
                                    <x-default::card.tile :article="$item" :rt="$rt" />
                                @endforeach
                            </div>
                            @if (count($results) > 12)
                                <x-default::loadmore target="#search-list" :scope="'search:'.$query" :offset="12" :per="6" template="tpl-tile" />
                            @endif
                        @else
                            <div class="border border-dashed border-line p-10 text-center">
                                <i class="ri-search-eye-line text-4xl text-muted"></i>
                                <p class="mt-3 font-serif text-xl font-semibold">Belum ada berita tentang “{{ $query }}”</p>
                                <p class="mt-1 text-sm text-muted">Periksa ejaan atau coba kata yang lebih umum.</p>
                            </div>
                        @endif
                    @elseif ($tab === 'penulis')
                        <div class="grid gap-4 sm:grid-cols-2">
                            @forelse ($authors as $author)
                                <a href="{{ $author['url'] }}" class="flex items-center gap-4 border border-line bg-surface p-4 transition hover:border-raya">
                                    <x-default::avatar :author="$author" size="size-12" class="text-lg" />
                                    <span><span class="block font-serif text-lg font-semibold">{{ $author['name'] }}</span><span class="rt-meta">{{ $author['label'] }} · {{ count($rt->byAuthor($author['username'])) }} tulisan</span></span>
                                </a>
                            @empty
                                <p class="text-sm text-muted">Tidak ada penulis dengan nama “{{ $query }}”.</p>
                            @endforelse
                        </div>
                    @elseif ($tab === 'kanal')
                        <div class="flex flex-wrap gap-2">
                            @forelse ($categories as $cat)<a href="{{ $cat['url'] }}" class="rt-chip !px-4 !py-2 !text-sm">{{ $cat['name'] }}</a>@empty<p class="text-sm text-muted">Tidak ada kanal dengan nama “{{ $query }}”.</p>@endforelse
                        </div>
                    @else
                        <div class="flex flex-wrap gap-2">
                            @forelse ($tags as $tagItem)<a href="{{ $tagItem['url'] }}" class="rt-chip">#{{ $tagItem['name'] }}</a>@empty<p class="text-sm text-muted">Tidak ada topik yang cocok dengan “{{ $query }}”.</p>@endforelse
                        </div>
                    @endif
                </div>
            @else
                <section class="mt-8">
                    <x-default::section-head title="Topik yang sedang dicari" />
                    <div class="flex flex-wrap gap-2">
                        @foreach ($rt->hitTopics(16) as $topic)<a href="{{ $rt->url('/search?q='.urlencode($topic['name'])) }}" class="rt-chip">{{ $topic['name'] }}</a>@endforeach
                    </div>
                </section>
            @endif
        </div>
        @include('default::partials.sidebar')
    </div>
@endsection
