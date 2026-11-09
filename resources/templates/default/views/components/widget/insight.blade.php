@props(['rt'])
@php $site = $rt->site(); @endphp
<section>
    <h2 class="mb-3 font-serif text-2xl font-semibold">RayaTimes Insight</h2>
    <a href="{{ $site['insight_url'] }}" class="group block" target="_blank" rel="noopener">
        <x-default::img :src="$site['brand']['insight']" alt="RayaTimes Insight — menelusuri fakta, menemukan makna" class="aspect-[900/277] dark:hidden" />
        <x-default::img :src="$site['brand']['insight_dark']" alt="RayaTimes Insight — menelusuri fakta, menemukan makna" class="hidden aspect-[900/277] dark:block" />
        <span class="mt-2 flex items-center gap-1 text-xs font-semibold text-accent">Baca laporan mendalam di insight.rayatimes.id <i class="ri-external-link-line"></i></span>
    </a>
</section>
