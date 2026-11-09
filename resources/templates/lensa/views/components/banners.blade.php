{{-- Banner 2 kolom beranda (menggantikan Topik hits, hasil meeting 28/10/2026). --}}
@props(['banners'])
<section aria-label="Banner" {{ $attributes->merge(['class' => 'mx-auto grid max-w-[90rem] gap-4 px-4 sm:grid-cols-2 lg:px-8']) }}>
    @foreach ($banners as $banner)
        <a href="{{ $banner['url'] }}" target="_blank" rel="noopener" class="group block overflow-hidden rounded-frame">
            <x-lensa::frame :src="$banner['image']" :alt="$banner['alt']" class="aspect-[900/277]" />
        </a>
    @endforeach
</section>
