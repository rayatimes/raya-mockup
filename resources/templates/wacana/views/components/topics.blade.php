{{-- Peta topik: panjang bilah = jumlah berita (data jujur, bukan hiasan). --}}
@props(['rt', 'limit' => 8])
@php
    $topics = $rt->hitTopics($limit);
    $max = max(array_column($topics, 'recent') ?: [1]);
@endphp
<section {{ $attributes }}>
    <h2 class="wc-display text-2xl">Isu paling banyak diberitakan</h2>
    <p class="wc-meta mt-1">Jumlah berita dalam dua hari terakhir</p>
    <ul class="mt-5 space-y-3">
        @foreach ($topics as $topic)
            <li>
                <a href="{{ $topic['url'] }}" class="group block">
                    <span class="flex items-baseline justify-between gap-3 text-sm"><span class="font-semibold group-hover:text-java">{{ $topic['name'] }}</span><span class="tabular-nums text-ash">{{ $topic['recent'] }}</span></span>
                    <span class="mt-1.5 block h-2 bg-stone"><span class="block h-full bg-java transition-[width] duration-700 group-hover:bg-ink" style="width: {{ round($topic['recent'] / $max * 100) }}%"></span></span>
                </a>
            </li>
        @endforeach
    </ul>
</section>
