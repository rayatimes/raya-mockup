{{-- Banner beranda: foto dengan lapisan gelap dan teks di atasnya. --}}
@props(['banners'])
<section aria-label="Banner" {{ $attributes->merge(['class' => 'mx-auto grid max-w-[84rem] gap-5 px-4 lg:grid-cols-2 lg:px-8']) }}>
    @foreach ($banners as $banner)
        <a href="{{ $banner['url'] }}" @if ($banner['is_external']) target="_blank" rel="noopener" @endif class="group relative isolate flex aspect-[2/1] min-h-44 w-full flex-col justify-end overflow-hidden bg-ink p-5 text-paper [--hue:var(--color-sun)] sm:aspect-[3/1] sm:p-6 lg:aspect-[21/9]">
            <x-wacana::photo :src="$banner['image']" class="!absolute inset-0 -z-20 size-full" />
            <span class="absolute inset-0 -z-10 bg-linear-to-t from-black/90 via-black/45 to-black/10"></span>
            <span class="flex items-end justify-between gap-4">
                <span class="min-w-0">
                    <span class="wc-ui-display block text-3xl sm:text-4xl"><span class="wc-link">{{ $banner['title'] }}</span></span>
                    <span class="mt-1 block text-sm text-paper/90 sm:text-[15px]">{{ $banner['text'] }}</span>
                </span>
                <i class="ri-arrow-right-up-line grid size-10 shrink-0 place-items-center bg-paper text-xl text-ink transition group-hover:bg-sun" aria-hidden="true"></i>
            </span>
        </a>
    @endforeach
</section>
