@extends('wacana::layouts.app', ['title' => 'Halaman tidak ditemukan'])

@section('content')
    <div class="mx-auto mt-16 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="lg:col-span-7">
            <p class="text-sm font-semibold text-java">Galat 404</p>
            <h1 class="wc-display mt-3 text-6xl lg:text-7xl">Halaman ini tidak ada di arsip kami</h1>
            <p class="mt-5 max-w-xl font-serif text-xl text-graphite">Alamatnya mungkin salah ketik, atau tulisannya sudah dipindahkan. Telusuri lewat pencarian atau indeks topik.</p>
            <div class="mt-8 flex flex-wrap gap-3"><a href="{{ $rt->url('/') }}" class="wc-btn">Ke halaman depan</a><a href="{{ $rt->url('/tag') }}" class="wc-btn-line">Indeks topik</a></div>
        </div>
        <div class="lg:col-span-5"><x-wacana::popular :rt="$rt" :limit="5" /></div>
    </div>
@endsection
