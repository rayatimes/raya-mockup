@php $lastBand = $lastBand ?? ''; $bands = $bands ?? false; $offset = $offset ?? 0; $showCategory = $showCategory ?? true; @endphp
@foreach ($items as $i => $article)
    @if ($bands)
        @php $band = $rt->dateBand($article['published']); @endphp
        @if ($band !== $lastBand)
            <p class="band">{{ $band }}</p>
            @php $lastBand = $band; @endphp
        @endif
    @endif
    {{-- Arus Terbaru: setiap berita keempat tampil besar, foto sebagai pembawa cerita. --}}
    <x-lensa-mobile::row :rt="$rt" :article="$article" :category="$showCategory" :big="$bands && ($offset + $i) % 4 === 0" />
@endforeach
