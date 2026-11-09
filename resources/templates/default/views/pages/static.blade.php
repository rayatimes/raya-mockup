@extends('default::layouts.app', ['title' => $static['title']])

@section('content')
    <div class="mx-auto grid max-w-7xl grid-cols-[minmax(0,1fr)] gap-10 px-4 pt-6 lg:grid-cols-[14rem_minmax(0,1fr)]">
        <nav aria-label="Halaman perusahaan" class="lg:sticky lg:top-16 lg:self-start">
            <p class="text-xs font-bold text-muted">Tentang RayaTimes</p>
            <ul class="no-scrollbar mt-3 flex gap-1 overflow-x-auto lg:flex-col">
                @foreach ($rt->pages() as $item)
                    <li><a href="{{ $rt->url('/p/'.$item['slug']) }}" @if ($item['slug'] === $static['slug']) aria-current="page" @endif class="block whitespace-nowrap border-l-2 border-transparent px-3 py-2 text-sm text-muted transition hover:text-fg aria-[current=page]:border-raya aria-[current=page]:bg-sunk aria-[current=page]:font-semibold aria-[current=page]:text-fg">{{ $item['title'] }}</a></li>
                @endforeach
            </ul>
        </nav>
        <article class="max-w-3xl">
            <h1 class="font-serif text-4xl font-semibold">{{ $static['title'] }}</h1>
            <div class="rt-prose mt-8">{!! $static['content'] !!}</div>
        </article>
    </div>
@endsection
