@extends('wacana::layouts.app', ['title' => $static['title']])

@section('content')
    <div class="mx-auto mt-12 grid max-w-[84rem] grid-cols-[minmax(0,1fr)] gap-12 px-4 lg:grid-cols-[16rem_minmax(0,44rem)] lg:px-8">
        <nav aria-label="Halaman perusahaan" class="lg:sticky lg:top-20 lg:self-start">
            <ul class="no-scrollbar flex gap-4 overflow-x-auto border-t-[3px] border-ink pt-3 lg:flex-col lg:gap-2">
                @foreach ($rt->pages() as $item)
                    <li><a href="{{ $rt->url('/p/'.$item['slug']) }}" @if ($item['slug'] === $static['slug']) aria-current="page" @endif class="block whitespace-nowrap font-serif text-lg text-graphite hover:text-ink aria-[current=page]:font-semibold aria-[current=page]:text-java">{{ $item['title'] }}</a></li>
                @endforeach
            </ul>
        </nav>
        <article>
            <h1 class="wc-display text-5xl lg:text-6xl">{{ $static['title'] }}</h1>
            <div class="wc-prose mt-10">{!! $static['content'] !!}</div>
        </article>
    </div>
@endsection
