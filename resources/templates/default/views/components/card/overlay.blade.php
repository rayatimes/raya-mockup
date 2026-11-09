@props(['article', 'rt', 'size' => 'md', 'eager' => false])
@php
    $title = ['lg' => 'text-2xl sm:text-3xl lg:text-[2.4rem] lg:leading-[1.12]', 'md' => 'text-lg lg:text-xl', 'sm' => 'text-base'][$size];
@endphp
<article data-card {{ $attributes->merge(['class' => 'group relative isolate overflow-hidden bg-ink']) }}>
    <x-default::img :src="$size === 'lg' ? $article['image']['lg'] : $article['image']['sm']" :alt="$article['title']" :eager="$eager" class="absolute inset-0 -z-10" />
    <div class="absolute inset-0 -z-10 bg-gradient-to-t from-black/90 via-black/35 to-black/0"></div>
    <a href="{{ $article['url'] }}" class="flex h-full flex-col justify-end p-4 text-white {{ $size === 'lg' ? 'sm:p-7' : '' }}">
        <span class="w-fit bg-raya px-2 py-0.5 text-[10.5px] font-bold uppercase tracking-wide">{{ $article['category']['name'] }}</span>
        <h3 class="mt-2.5 font-serif font-semibold {{ $title }} text-balance [text-shadow:0_1px_12px_rgb(0_0_0/0.35)]">
            <span class="bg-[linear-gradient(var(--color-sun),var(--color-sun))] bg-[length:0_2px] bg-left-bottom bg-no-repeat transition-[background-size] duration-500 group-hover:bg-[length:100%_2px]">{{ $article['title'] }}</span>
        </h3>
        @if ($size === 'lg')<p class="mt-3 hidden max-w-2xl text-sm leading-relaxed text-white/85 sm:block">{{ $article['excerpt'] }}</p>@endif
        <p class="mt-2 text-xs text-white/75">{{ $rt->ago($article['published']) }}</p>
    </a>
</article>
