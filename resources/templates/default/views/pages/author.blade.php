@extends('default::layouts.app', ['title' => $author['name']])

@php $items = $rt->byAuthor($author['username']); @endphp

@section('content')
    <div class="mx-auto max-w-7xl px-4 pt-6">
        <header class="border border-line bg-surface">
            <div class="h-28 bg-raya bg-[linear-gradient(120deg,var(--color-raya)_60%,var(--color-raya-deep))] sm:h-36"></div>
            <div class="flex flex-col gap-4 px-6 pb-6 sm:flex-row sm:items-end">
                <x-default::avatar :author="$author" size="size-24" class="-mt-12 border-4 border-surface text-4xl" />
                <div class="flex-1">
                    <h1 class="font-serif text-3xl font-semibold">{{ $author['name'] }}</h1>
                    <p class="rt-meta">{{ $author['label'] }} · bergabung {{ $rt->date($author['joined_at'], false) }}</p>
                    <p class="mt-2 text-sm"><strong>{{ $author['followers'] }}</strong> <span class="text-muted">pengikut</span> · <strong>{{ count($items) }}</strong> <span class="text-muted">tulisan</span></p>
                </div>
                <button type="button" data-react data-on="ri-check-line" data-off="ri-user-add-line" aria-pressed="false" class="rt-btn rt-btn-solid aria-[pressed=true]:!border-fg aria-[pressed=true]:!bg-fg aria-[pressed=true]:!text-paper"><i class="ri-user-add-line"></i>Ikuti</button>
            </div>
        </header>

        <section class="mt-10">
            <x-default::section-head :title="'Tulisan ('.count($items).')'" />
            <div id="author-list" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                @foreach (array_slice($items, 0, 12) as $item)
                    <x-default::card.tile :article="$item" :rt="$rt" />
                @endforeach
            </div>
            @if (count($items) > 12)
                <x-default::loadmore target="#author-list" :scope="'author:'.$author['username']" :offset="12" :per="8" template="tpl-tile" />
            @endif
        </section>
    </div>
@endsection
