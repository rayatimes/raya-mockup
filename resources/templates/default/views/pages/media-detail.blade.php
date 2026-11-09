@extends('default::layouts.app', ['title' => $item['title']])

@section('content')
    <div class="mx-auto grid max-w-7xl gap-10 px-4 pt-6 lg:grid-cols-[minmax(0,1fr)_22rem]">
        <div class="space-y-4">
            @foreach ($item['images'] as $image)
                <figure>
                    <img src="{{ $image['lg'] }}" alt="{{ $item['title'] }}" width="{{ $image['w'] }}" height="{{ $image['h'] }}" class="h-auto w-full bg-sunk" @if (! $loop->first) loading="lazy" @endif>
                    @if ($image['caption'])<figcaption class="mt-2 text-xs text-muted">{{ $image['caption'] }}</figcaption>@endif
                </figure>
            @endforeach
        </div>
        <aside class="lg:sticky lg:top-16 lg:self-start">
            <a href="{{ $rt->url('/media') }}" class="rt-meta inline-flex items-center gap-1 hover:text-accent"><i class="ri-arrow-left-line"></i>Galeri Media</a>
            <h1 class="mt-3 font-serif text-3xl font-semibold">{{ $item['title'] }}</h1>
            <p class="rt-meta mt-2">{{ $rt->date($item['published']) }}</p>
            <div class="mt-5 space-y-3 whitespace-pre-line text-sm leading-relaxed">{{ trim($item['description']) }}</div>
            @if ($item['link'])
                @php $linked = $rt->article($item['link']['article']); @endphp
                @if ($linked)<a href="{{ $linked['url'] }}" class="rt-btn rt-btn-solid mt-6 w-full">{{ $item['link']['label'] }}</a>@endif
            @endif
        </aside>
    </div>
@endsection
