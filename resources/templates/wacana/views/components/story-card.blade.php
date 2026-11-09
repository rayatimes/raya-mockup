{{-- Kartu editorial: bidang warna kanal + foto duotone + judul besar. --}}
@props(['article', 'rt', 'size' => 'text-2xl'])
<article data-card data-kanal="{{ $article['category']['slug'] }}" {{ $attributes->merge(['class' => 'group flex flex-col bg-(--hue) text-(--on-hue)']) }}>
    <a href="{{ $article['url'] }}" class="flex h-full flex-col">
        <x-wacana::photo :src="$article['image']['sm']" :alt="$article['title']" duo class="aspect-[16/10]" />
        <span class="flex flex-1 flex-col p-5">
            <span class="text-[13px] font-semibold opacity-80">{{ $article['category']['name'] }}</span>
            <span class="wc-head mt-2 {{ $size }}"><span class="bg-[linear-gradient(currentColor,currentColor)] bg-[length:0_2px] bg-left-bottom bg-no-repeat transition-[background-size] duration-500 group-hover:bg-[length:100%_2px]">{{ $article['title'] }}</span></span>
            <span class="mt-auto pt-4 text-[13px] opacity-75">{{ $rt->authorLine($article, compact: true) }}</span>
        </span>
    </a>
</article>
