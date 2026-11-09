@extends('wacana::layouts.app', ['title' => $item['title']])

@section('content')
    <div class="mx-auto mt-12 grid max-w-[84rem] gap-12 px-4 lg:grid-cols-12 lg:px-8">
        <div class="space-y-6 lg:col-span-7">
            @foreach ($item['images'] as $image)
                <img src="{{ $image['lg'] }}" alt="{{ $item['title'] }}" width="{{ $image['w'] }}" height="{{ $image['h'] }}" class="h-auto w-full bg-stone">
            @endforeach
        </div>
        <aside class="lg:sticky lg:top-20 lg:col-span-5 lg:self-start">
            <a href="{{ $rt->url('/media') }}" class="text-sm font-semibold text-java underline underline-offset-4">Media</a>
            <h1 class="wc-ui-display mt-3 text-4xl">{{ $item['title'] }}</h1>
            <p class="wc-meta mt-2">{{ $rt->date($item['published']) }}</p>
            <p class="mt-6 whitespace-pre-line text-lg leading-relaxed">{{ trim($item['description']) }}</p>
            @if ($item['link'] && ($linked = $rt->article($item['link']['article'])))
                <a href="{{ $linked['url'] }}" class="wc-btn mt-8">{{ $item['link']['label'] }}<i class="ri-arrow-right-line"></i></a>
            @endif
        </aside>
    </div>
@endsection
