@extends('default::layouts.app', ['title' => 'Indeks Berita'])

@php $items = $rt->latest(15); @endphp

@section('content')
    <div class="mx-auto grid max-w-7xl gap-10 px-4 pt-6 lg:grid-cols-[minmax(0,1fr)_20rem]">
        <div class="min-w-0">
            <header class="border-b border-line pb-5">
                <h1 class="rt-section-title !text-3xl">Indeks Berita</h1>
                <p class="mt-2 text-sm text-muted">Semua artikel terbaru, diurutkan berdasarkan waktu terbit.</p>
            </header>
            <div id="index-list" class="divide-y divide-line">
                @foreach ($items as $item)
                    <x-default::card.row :article="$item" :rt="$rt" excerpt />
                @endforeach
            </div>
            <x-default::loadmore target="#index-list" scope="latest" :offset="15" />
        </div>
        <aside class="space-y-8 lg:sticky lg:top-16 lg:self-start">
            <x-default::widget.trending :rt="$rt" />
            <x-default::widget.popular :rt="$rt" />
        </aside>
    </div>
@endsection
