{{-- Tren topik bergaya trending X: konteks kanal, tagar/frasa, jumlah berita. --}}
@props(['rt', 'limit' => 6])
<section class="rt-panel">
    <h2 class="rt-section-title px-4 pb-2 pt-4">Sedang tren</h2>
    <ol>
        @foreach ($rt->hitTopics($limit) as $i => $topic)
            <li>
                <a href="{{ $topic['url'] }}" class="group block px-4 py-3 transition hover:bg-sunk">
                    <span class="rt-meta block">{{ $i + 1 }} · Tren di {{ $topic['kanal'] }}</span>
                    <span class="mt-0.5 block font-semibold text-fg group-hover:text-accent">{{ $topic['label'] }}</span>
                    <span class="rt-meta block">{{ $topic['recent'] }} berita</span>
                </a>
            </li>
        @endforeach
    </ol>
    <a href="{{ $rt->url('/tag') }}" class="block border-t border-line px-4 py-3 text-sm font-semibold text-accent transition hover:bg-sunk">Tampilkan lebih banyak</a>
</section>
