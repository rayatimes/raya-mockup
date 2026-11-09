@extends('lensa::layouts.app', ['title' => 'Media'])

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <h1 class="ls-headline text-6xl lg:text-8xl">Media</h1>
        <p class="mt-3 text-dim">Infografis, foto, dan video dari meja redaksi.</p>
        <div class="mt-8 flex gap-2"><span class="ls-pill" aria-current="page">Foto &amp; infografis</span><span class="ls-pill opacity-50">Video · segera</span></div>
        <div class="mt-10 columns-1 gap-4 sm:columns-2 lg:columns-3">
            @foreach ($rt->media() as $item)
                <a href="{{ $item['url'] }}" data-card data-theme="dark" class="group relative mb-4 block break-inside-avoid overflow-hidden rounded-frame bg-raise">
                    <img src="{{ $item['images'][0]['sm'] }}" alt="{{ $item['title'] }}" width="{{ $item['images'][0]['w'] }}" height="{{ $item['images'][0]['h'] }}" loading="lazy" class="h-auto w-full transition duration-700 group-hover:scale-[1.03]">
                    <span class="ls-shade absolute inset-0"></span>
                    <span class="absolute inset-x-5 bottom-5"><span class="ls-kanal">{{ $rt->ago($item['published']) }}</span><span class="ls-headline mt-1 block text-3xl">{{ $item['title'] }}</span></span>
                </a>
            @endforeach
        </div>
    </div>
@endsection
