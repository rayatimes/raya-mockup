{{-- Tren topik bergaya trending X: konteks kanal, tagar/frasa, jumlah berita. --}}
@props(['rt', 'limit' => 6])
<section {{ $attributes->merge(['class' => 'overflow-hidden rounded-frame bg-panel']) }}>
    <h2 class="ls-headline px-6 pb-2 pt-6 text-2xl">Sedang tren</h2>
    <ol>
        @foreach ($rt->hitTopics($limit) as $i => $topic)
            <li>
                <a href="{{ $topic['url'] }}" class="group block px-6 py-3 transition hover:bg-raise">
                    <span class="block text-xs text-dim">{{ $i + 1 }} · Tren di {{ $topic['kanal'] }}</span>
                    <span class="mt-0.5 block text-lg font-bold leading-snug transition-colors group-hover:text-sun">{{ $topic['label'] }}</span>
                    <span class="block text-xs text-dim">{{ $topic['recent'] }} berita</span>
                </a>
            </li>
        @endforeach
    </ol>
    <a href="{{ $rt->url('/tag') }}" class="block px-6 pb-6 pt-3 text-sm font-semibold text-sun hover:underline">Tampilkan lebih banyak</a>
</section>
