@extends('default::layouts.app', ['title' => 'Galeri Media'])

@section('content')
    <div class="mx-auto max-w-7xl px-4 pt-6">
        <header class="bg-ink p-6 text-white lg:p-10">
            <h1 class="font-serif text-4xl font-semibold">Galeri Media</h1>
            <p class="mt-2 text-white/70">Infografis, foto, dan video pilihan dari lapangan.</p>
        </header>

        <section class="mt-10">
            <x-default::section-head title="Foto & infografis" />
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($rt->media() as $item)
                    <a href="{{ $item['url'] }}" data-card class="group block">
                        <x-default::img :src="$item['images'][0]['sm']" :alt="$item['title']" class="aspect-[4/5]" />
                        <p class="rt-meta mt-3">{{ $rt->ago($item['published']) }} · {{ count($item['images']) }} foto</p>
                        <h2 class="mt-1 text-lg leading-snug"><span class="rt-title">{{ $item['title'] }}</span></h2>
                    </a>
                @endforeach
            </div>
        </section>

        <section class="mt-12">
            <x-default::section-head title="Video" />
            <div class="border border-dashed border-line p-10 text-center">
                <i class="ri-video-line text-4xl text-muted"></i>
                <p class="mt-2 font-serif text-lg font-semibold">Video liputan segera hadir</p>
                <p class="mt-1 text-sm text-muted">Sementara itu, simak infografis terbaru di atas.</p>
            </div>
        </section>
    </div>
@endsection
