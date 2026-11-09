@php $lastBand = $lastBand ?? ''; $bands = $bands ?? false; $offset = $offset ?? 0; $showCategory = $showCategory ?? true; @endphp
@foreach ($items as $i => $article)
    @if ($bands)
        @php $band = $rt->dateBand($article['published']); @endphp
        @if ($band !== $lastBand)
            <p class="band">{{ $band }}</p>
            @php $lastBand = $band; @endphp
        @endif
    @endif
    <x-default-mobile::row :rt="$rt" :article="$article" :category="$showCategory" :index="$offset + $i" />
@endforeach
