{{-- Banner 2 kolom beranda (menggantikan Topik Hits, hasil meeting 28/10/2026). --}}
@props(['banners'])
<section aria-label="Banner" {{ $attributes->merge(['class' => 'grid gap-3 sm:grid-cols-2']) }}>
    @foreach ($banners as $banner)
        <a href="{{ $banner['url'] }}" target="_blank" rel="noopener" class="group block overflow-hidden border border-line">
            <x-default::img :src="$banner['image']" :alt="$banner['alt']" class="aspect-[900/277]" />
        </a>
    @endforeach
</section>
