{{-- Tata letak "judul atas": judul, ringkasan, penulis, lalu cover. --}}
<nav aria-label="Breadcrumb" class="rt-meta flex flex-wrap items-center gap-1.5">
    <a href="{{ $rt->url('/') }}" class="hover:text-accent">Beranda</a><i class="ri-arrow-right-s-line"></i>
    @if ($parent)<a href="{{ $parent['url'] }}" class="hover:text-accent">{{ $parent['name'] }}</a><i class="ri-arrow-right-s-line"></i>@endif
    <a href="{{ $article['category']['url'] }}" class="font-semibold text-accent">{{ $article['category']['name'] }}</a>
</nav>

@if ($article['upper'])
    {{-- Judul atas (kicker): sengaja bukan heading, satu halaman hanya punya satu h1. --}}
    <p class="mt-4 font-serif text-lg font-bold leading-snug text-accent sm:text-xl">{{ $article['upper'] }}</p>
@endif
<h1 class="{{ $article['upper'] ? 'mt-1.5' : 'mt-4' }} font-serif text-3xl font-semibold leading-[1.15] text-balance sm:text-4xl lg:text-[2.85rem]">{{ $article['title'] }}</h1>
<p class="mt-4 font-serif text-lg leading-relaxed text-muted sm:text-xl">{{ $article['excerpt'] }}</p>

<div class="mt-6 flex flex-wrap items-center justify-between gap-4 border-y border-line py-4">
    <div class="flex items-center gap-3">
        <div class="flex -space-x-2">
            @foreach (array_slice($article['authors'], 0, 3) as $author)
                <x-default::avatar :author="$author" size="size-10" class="text-base ring-2 ring-paper" />
            @endforeach
            @if (count($article['authors']) > 3)
                <span class="grid size-10 place-items-center bg-ink text-xs font-bold text-white ring-2 ring-paper dark:bg-white dark:text-ink">+{{ count($article['authors']) - 3 }}</span>
            @endif
        </div>
        <div class="text-sm">
            <p class="font-semibold"><x-default::author-names :article="$article" /></p>
            <p class="rt-meta"><time datetime="{{ $article['published']->toIso8601String() }}">{{ $rt->date($article['published']) }}</time> · {{ $article['reading_time'] }} menit baca</p>
        </div>
    </div>
    <x-default::share />
</div>

<figure class="mt-6">
    <x-default::img :src="$article['image']['lg']" :alt="$article['title']" eager hero class="aspect-video" />
    @if ($article['caption'])<figcaption class="mt-2 text-xs text-muted">{{ $article['caption'] }}</figcaption>@endif
</figure>
