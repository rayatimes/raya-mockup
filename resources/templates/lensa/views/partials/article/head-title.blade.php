{{-- Tata letak "judul atas": judul, ringkasan, penulis, lalu cover lebar. --}}
<header class="mx-auto max-w-5xl px-4 pt-10 lg:px-8">
    <nav class="flex items-center gap-2 text-sm text-dim" aria-label="Breadcrumb">
        <a href="{{ $rt->url('/') }}" class="hover:text-sun">Beranda</a><i class="ri-arrow-right-s-line"></i>
        <a href="{{ $article['category']['url'] }}" class="font-semibold text-sun">{{ $article['category']['name'] }}</a>
    </nav>
    @if ($article['upper'])
        <p class="ls-headline mt-4 text-2xl text-sun lg:text-3xl">{{ $article['upper'] }}</p>
    @endif
    <h1 class="ls-headline {{ $article['upper'] ? 'mt-2' : 'mt-4' }} text-[2.5rem] sm:text-6xl lg:text-7xl">{{ $article['title'] }}</h1>
    <p class="mt-5 max-w-3xl font-read text-xl leading-relaxed text-ink/85 lg:text-2xl">{{ $article['excerpt'] }}</p>
    @include('lensa::partials.article.byline')
</header>
<figure class="mx-auto mt-10 max-w-[90rem] px-4 lg:px-8">
    <x-lensa::frame :src="$article['image']['lg']" :alt="$article['title']" eager hero class="aspect-video rounded-frame lg:aspect-[21/9]" />
    @if ($article['caption'])<figcaption class="mt-2 text-xs text-dim"><i class="ri-camera-line"></i> {{ $article['caption'] }}</figcaption>@endif
</figure>
