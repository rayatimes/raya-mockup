@extends('lensa::layouts.app', ['title' => $author['name']])

@php $items = $rt->byAuthor($author['username']); @endphp

@section('content')
    <div class="mx-auto max-w-[90rem] px-4 pt-10 lg:px-8">
        <header class="relative isolate overflow-hidden rounded-frame bg-panel p-6 lg:p-10">
            @if ($items)<x-lensa::frame :src="$items[0]['image']['lg']" alt="" class="absolute inset-0 -z-10 opacity-25" />@endif
            <div class="absolute inset-0 -z-10 bg-gradient-to-r from-panel via-panel/90 to-panel/40"></div>
            <div class="flex flex-col gap-6 sm:flex-row sm:items-center">
                @if ($author['avatar'])
                    <img src="{{ $author['avatar'] }}" alt="" class="size-28 rounded-full object-cover ring-4 ring-sun">
                @else
                    <span class="ls-headline grid size-28 place-items-center rounded-full bg-sun text-5xl text-ground">{{ mb_substr($author['name'], 0, 1) }}</span>
                @endif
                <div class="flex-1">
                    <p class="ls-kanal">{{ $author['label'] }}</p>
                    <h1 class="ls-headline mt-1 text-5xl lg:text-6xl">{{ $author['name'] }}</h1>
                    <p class="mt-2 text-sm text-dim">{{ count($items) }} cerita · {{ $author['followers'] }} pengikut · bergabung {{ $rt->date($author['joined_at'], false) }}</p>
                </div>
                <button type="button" data-toggle-press aria-pressed="false" class="ls-btn aria-[pressed=true]:bg-ink"><i class="ri-user-add-line"></i>Ikuti</button>
            </div>
        </header>
        <div id="author-grid" class="mt-12 grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (array_slice($items, 0, 12) as $item)
                <x-lensa::card.photo :article="$item" :rt="$rt" />
            @endforeach
        </div>
        @if (count($items) > 12)
            <x-lensa::more target="#author-grid" :scope="'author:'.$author['username']" :offset="12" />
        @endif
    </div>
@endsection
