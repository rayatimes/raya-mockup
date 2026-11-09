@extends('lensa::layouts.app', ['title' => 'Indeks Berita'])

@php $items = $rt->latest(12); @endphp

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <h1 class="ls-headline text-6xl lg:text-8xl">Terkini</h1>
        <p class="mt-3 text-dim">Semua cerita terbaru, diurutkan dari yang paling baru terbit.</p>
        <div class="mt-10 grid gap-12 lg:grid-cols-[minmax(0,1fr)_22rem]">
            <div>
                <div id="index-grid" class="grid gap-x-5 gap-y-10 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($items as $item)
                        <x-lensa::card.photo :article="$item" :rt="$rt" />
                    @endforeach
                </div>
                <x-lensa::more target="#index-grid" scope="latest" :offset="12" :per="9" />
            </div>
            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start"><x-lensa::trending :rt="$rt" :limit="5" /><x-lensa::rank :rt="$rt" :limit="5" /></aside>
        </div>
    </div>
@endsection
