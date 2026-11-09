@extends('lensa::layouts.app', ['title' => 'Halaman tidak ditemukan'])

@php $fallback = $rt->headlines(1)[0]; @endphp

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
                <p class="ls-headline text-[9rem] leading-none text-sun lg:text-[13rem]">404</p>
                <h1 class="ls-headline mt-2 text-4xl">Bingkai ini kosong</h1>
                <p class="mt-3 max-w-md text-dim">Halaman yang Anda cari tidak ada atau sudah dipindahkan. Coba cari, atau lanjut ke cerita utama hari ini.</p>
                <div class="mt-8 flex flex-wrap gap-3"><a href="{{ $rt->url('/') }}" class="ls-btn">Ke beranda</a><a href="{{ $rt->url('/search') }}" data-search-open class="ls-btn-ghost"><i class="ri-search-line"></i>Cari berita</a></div>
            </div>
            <x-lensa::card.cover :article="$fallback" :rt="$rt" size="text-3xl" large class="aspect-[4/5]" />
        </div>
    </div>
@endsection
