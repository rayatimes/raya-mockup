{{--
    Nama penulis bertaut. 1–2 penulis: "A" / "A dan B".
    3 penulis atau lebih: "A dan N lainnya", daftar lengkapnya bisa dibuka (seperti situs lama).
--}}
@props(['article'])
@php
    $authors = $article['authors'];
    $others = array_slice($authors, 1);
@endphp
<span {{ $attributes }}>
    @if (count($authors) <= 2)
        @foreach ($authors as $author)@if (! $loop->first) dan @endif<a href="{{ $author['url'] }}" class="font-semibold text-ink hover:text-java">{{ $author['name'] }}</a>@endforeach
    @else
        <a href="{{ $authors[0]['url'] }}" class="font-semibold text-ink hover:text-java">{{ $authors[0]['name'] }}</a> dan
        <button type="button" data-authors-toggle aria-expanded="false" class="font-semibold hover:text-java underline decoration-dotted underline-offset-4">{{ count($others) }} lainnya</button><span data-authors-list hidden> (@foreach ($others as $author)@if (! $loop->first){{ $loop->last ? ', dan ' : ', ' }}@endif<a href="{{ $author['url'] }}" class="font-semibold text-ink hover:text-java">{{ $author['name'] }}</a>@endforeach)</span>
    @endif
</span>
