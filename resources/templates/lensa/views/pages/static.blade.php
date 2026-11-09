@extends('lensa::layouts.app', ['title' => $static['title']])

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <nav class="no-scrollbar flex gap-2 overflow-x-auto pb-2" aria-label="Halaman perusahaan">
            @foreach ($rt->pages() as $item)
                <a href="{{ $rt->url('/p/'.$item['slug']) }}" @if ($item['slug'] === $static['slug']) aria-current="page" @endif class="ls-pill shrink-0">{{ $item['title'] }}</a>
            @endforeach
        </nav>
        <article class="mx-auto mt-12 max-w-3xl">
            <h1 class="ls-headline text-6xl">{{ $static['title'] }}</h1>
            <div class="ls-read mt-10">{!! $static['content'] !!}</div>
        </article>
    </div>
@endsection
