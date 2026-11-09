@extends('wacana::layouts.app', ['title' => 'Indeks Berita'])

@section('content')
    <div class="mx-auto mt-12 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="lg:col-span-8">
            <h1 class="wc-display text-6xl">Indeks</h1>
            <p class="mt-3 font-serif text-xl text-graphite">Semua tulisan terbaru, dari yang paling baru terbit.</p>
            <div id="index-list" class="mt-10 divide-y divide-ink/10 border-t-[3px] border-ink">
                @foreach ($rt->latest(12) as $item)
                    <x-wacana::entry :article="$item" :rt="$rt" />
                @endforeach
            </div>
            <x-wacana::more target="#index-list" scope="latest" :offset="12" />
        </div>
        <div class="lg:col-span-4">@include('wacana::partials.aside')</div>
    </div>
@endsection
