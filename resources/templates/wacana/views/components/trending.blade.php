{{-- Tren topik bergaya trending X; tiap tren diberi warna kanal tempat topik itu paling sering muncul. --}}
@props(['rt', 'limit' => 6])
<section {{ $attributes->merge(['class' => 'border-t-[6px] border-ink']) }}>
    <h2 class="wc-display pt-4 text-2xl">Sedang tren</h2>
    <ol class="mt-2 divide-y divide-ink/10">
        @foreach ($rt->hitTopics($limit) as $i => $topic)
            <li data-kanal="{{ $topic['kanal_slug'] }}">
                <a href="{{ $topic['url'] }}" class="group block py-3">
                    <span class="wc-meta flex items-center gap-2"><span class="size-2 shrink-0 bg-(--hue)"></span>{{ $i + 1 }} · Tren di {{ $topic['kanal'] }}</span>
                    <span class="wc-head wc-link mt-1 inline text-xl">{{ $topic['label'] }}</span>
                    <span class="wc-meta block">{{ $topic['recent'] }} berita</span>
                </a>
            </li>
        @endforeach
    </ol>
    <a href="{{ $rt->url('/tag') }}" class="mt-2 inline-flex text-sm font-semibold underline decoration-2 underline-offset-4 transition hover:text-java">Tampilkan lebih banyak</a>
</section>
