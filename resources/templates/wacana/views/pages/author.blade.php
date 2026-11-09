@extends('wacana::layouts.app', ['title' => $author['name']])

@php
    $items = $rt->byAuthor($author['username']);
    $kanal = collect($items)->countBy(fn ($a) => $a['category']['name'])->sortDesc()->take(4);
@endphp

@section('content')
    <div class="mx-auto mt-12 max-w-[84rem] px-4 lg:px-8">
        <header class="grid gap-8 border-b-[3px] border-ink pb-10 lg:grid-cols-12">
            <div class="flex gap-6 lg:col-span-8">
                @if ($author['avatar'])
                    <img src="{{ $author['avatar'] }}" alt="" class="size-28 shrink-0 object-cover grayscale">
                @else
                    <span class="wc-display grid size-28 shrink-0 place-items-center bg-ink text-5xl text-paper">{{ mb_substr($author['name'], 0, 1) }}</span>
                @endif
                <div>
                    <p class="text-sm font-semibold text-java">{{ $author['label'] }}</p>
                    <h1 class="wc-display mt-1 text-5xl lg:text-6xl">{{ $author['name'] }}</h1>
                    <p class="mt-3 font-serif text-lg text-graphite">{{ $author['bio'] ?: 'Menulis untuk RayaTimes sejak '.$rt->date($author['joined_at'], false).'.' }}</p>
                    <button type="button" data-press aria-pressed="false" class="wc-btn mt-5 aria-[pressed=true]:bg-java">Ikuti tulisan {{ explode(' ', $author['name'])[0] }}</button>
                </div>
            </div>
            <dl class="grid grid-cols-2 gap-6 lg:col-span-4">
                <div><dt class="text-[13px] text-ash">Tulisan</dt><dd class="wc-display text-4xl">{{ count($items) }}</dd></div>
                <div><dt class="text-[13px] text-ash">Pengikut</dt><dd class="wc-display text-4xl">{{ $author['followers'] }}</dd></div>
                <div class="col-span-2"><dt class="text-[13px] text-ash">Paling sering menulis di</dt><dd class="mt-1 font-serif text-lg">{{ $kanal->keys()->implode(', ') ?: '—' }}</dd></div>
            </dl>
        </header>
        <div id="author-list" class="mt-4 grid gap-x-12 divide-y divide-ink/10 lg:grid-cols-2 lg:divide-y-0">
            @foreach (array_slice($items, 0, 10) as $item)
                <x-wacana::entry :article="$item" :rt="$rt" class="lg:border-b lg:border-ink/10" />
            @endforeach
        </div>
        @if (count($items) > 10)
            <x-wacana::more target="#author-list" :scope="'author:'.$author['username']" :offset="10" />
        @endif
    </div>
@endsection
