@extends('default::layouts.app', ['title' => 'Halaman tidak ditemukan'])

@section('content')
    <div class="mx-auto max-w-7xl px-4 py-16">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div>
                <p class="font-serif text-[7rem] font-semibold leading-none text-raya">404</p>
                <h1 class="mt-4 font-serif text-3xl font-semibold">Halaman ini tidak ada atau sudah dipindahkan</h1>
                <p class="mt-3 text-muted">Periksa kembali alamatnya, atau cari berita yang Anda maksud.</p>
                <form action="{{ $rt->url('/search') }}" class="mt-6 flex max-w-md">
                    <label for="q-404" class="sr-only">Cari berita</label>
                    <input id="q-404" name="q" type="search" placeholder="Cari berita…" class="min-w-0 flex-1 border border-line bg-surface px-4 py-3 focus:border-raya focus:outline-none">
                    <button class="rt-btn rt-btn-solid">Cari</button>
                </form>
                <a href="{{ $rt->url('/') }}" class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-accent"><i class="ri-arrow-left-line"></i>Kembali ke beranda</a>
            </div>
            <div>
                <x-default::section-head title="Sedang banyak dibaca" />
                <ol class="divide-y divide-line">
                    @foreach ($rt->popular(5) as $i => $item)
                        <x-default::card.rank :article="$item" :rt="$rt" :rank="$i + 1" />
                    @endforeach
                </ol>
            </div>
        </div>
    </div>
@endsection
