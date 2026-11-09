@extends('wacana::layouts.app', ['title' => 'Media'])

@section('content')
    <div class="mx-auto mt-12 max-w-[84rem] px-4 lg:px-8">
        <h1 class="wc-display text-6xl">Media</h1>
        <p class="mt-3 font-serif text-xl text-graphite">Infografis dan foto yang merangkum data dalam satu tampilan.</p>
        <div class="mt-12 grid gap-12 lg:grid-cols-2">
            @foreach ($rt->media() as $item)
                <a href="{{ $item['url'] }}" data-card class="group grid gap-6 sm:grid-cols-[1fr_1.1fr]">
                    <x-wacana::photo :src="$item['images'][0]['sm']" :alt="$item['title']" class="aspect-[4/5] [&>img]:object-top" />
                    <span>
                        <span class="text-sm font-semibold text-java">Infografis</span>
                        <span class="wc-display mt-2 block text-3xl group-hover:text-java">{{ $item['title'] }}</span>
                        <span class="mt-3 block font-serif text-lg leading-relaxed text-graphite line-clamp-5">{{ $item['description'] }}</span>
                        <span class="wc-meta mt-3 block">{{ $rt->date($item['published'], false) }}</span>
                    </span>
                </a>
            @endforeach
        </div>
        <p class="mt-16 bg-stone p-6 font-serif text-lg">Video liputan akan hadir di halaman ini.</p>
    </div>
@endsection
