@extends('lensa::layouts.app', ['title' => $item['title']])

@section('content')
    <div class="mx-auto grid max-w-[90rem] gap-10 px-4 pt-10 lg:grid-cols-[minmax(0,1fr)_24rem] lg:px-8">
        <div class="space-y-4">
            @foreach ($item['images'] as $image)
                <a href="{{ $image['lg'] }}" data-zoom class="block cursor-zoom-in overflow-hidden rounded-frame bg-raise">
                    <img src="{{ $image['lg'] }}" alt="{{ $item['title'] }}" width="{{ $image['w'] }}" height="{{ $image['h'] }}" class="h-auto w-full">
                </a>
            @endforeach
        </div>
        <aside class="lg:sticky lg:top-24 lg:self-start">
            <a href="{{ $rt->url('/media') }}" class="ls-pill"><i class="ri-arrow-left-line"></i>Media</a>
            <h1 class="ls-headline mt-6 text-5xl">{{ $item['title'] }}</h1>
            <p class="mt-2 text-sm text-dim">{{ $rt->date($item['published']) }}</p>
            <p class="mt-6 whitespace-pre-line font-read text-lg leading-relaxed text-ink/85">{{ trim($item['description']) }}</p>
            @if ($item['link'] && ($linked = $rt->article($item['link']['article'])))
                <a href="{{ $linked['url'] }}" class="ls-btn mt-8 w-full">{{ $item['link']['label'] }}</a>
            @endif
        </aside>
    </div>
    <div id="ls-lightbox" data-open="false" class="fixed inset-0 z-[75] grid cursor-zoom-out place-items-center bg-black/95 p-4 opacity-0 transition invisible data-[open=true]:visible data-[open=true]:opacity-100">
        <img src="" alt="{{ $item['title'] }}" class="max-h-full max-w-full rounded-frame">
    </div>
@endsection
