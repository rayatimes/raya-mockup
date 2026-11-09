{{-- Tata letak "cover dulu" (Analisis): setelah cover, judul lalu informasi penulis dan atribut tulisan. --}}
@php $parent = $article['category']['parent'] ? $rt->category($article['category']['parent']) : null; @endphp
<nav aria-label="Breadcrumb" class="rt-meta flex flex-wrap items-center gap-1.5">
    <a href="{{ $rt->url('/') }}" class="hover:text-accent">Beranda</a><i class="ri-arrow-right-s-line"></i>
    @if ($parent)<a href="{{ $parent['url'] }}" class="hover:text-accent">{{ $parent['name'] }}</a><i class="ri-arrow-right-s-line"></i>@endif
    <a href="{{ $article['category']['url'] }}" class="font-semibold text-accent">{{ $article['category']['name'] }}</a>
</nav>

<a href="{{ $article['category']['url'] }}" class="mt-5 inline-block bg-raya px-2.5 py-1 text-[11px] font-bold uppercase tracking-wide text-white">{{ $article['category']['name'] }}</a>
@if ($article['upper'])
    <p class="mt-3 font-serif text-lg font-bold leading-snug text-accent sm:text-xl">{{ $article['upper'] }}</p>
@endif
<h1 class="{{ $article['upper'] ? 'mt-1.5' : 'mt-3' }} font-serif text-3xl font-semibold leading-[1.12] text-balance sm:text-4xl lg:text-5xl">{{ $article['title'] }}</h1>
<p class="mt-4 border-l-4 border-sun pl-4 font-serif text-lg italic leading-relaxed text-muted sm:text-xl">{{ $article['excerpt'] }}</p>

{{-- Penulis --}}
@php $many = count($article['authors']) > 2; @endphp
<div @class(['mt-8 border-y border-line py-5', 'space-y-4' => ! $many, 'grid gap-4 sm:grid-cols-2' => $many])>
    @foreach ($article['authors'] as $author)
        <div class="flex items-start gap-4">
            <x-default::avatar :author="$author" size="size-14" class="text-xl" />
            <div class="min-w-0 flex-1">
                <p class="text-xs text-muted">Ditulis oleh</p>
                <a href="{{ $author['url'] }}" class="font-serif text-lg font-semibold hover:text-accent">{{ $author['name'] }}</a>
                <p class="text-xs font-semibold text-accent">{{ $author['label'] }}</p>
                @unless ($many)<p class="mt-1 text-sm text-muted line-clamp-2">{{ $author['bio'] ?: 'Menulis untuk RayaTimes: fakta yang utuh, konteks yang jernih.' }}</p>@endunless
            </div>
            @unless ($many)<a href="{{ $author['url'] }}" class="rt-btn hidden !px-3 !py-1.5 text-xs sm:inline-flex"><i class="ri-user-add-line"></i>Ikuti</a>@endunless
        </div>
    @endforeach
</div>

{{-- Atribut tulisan --}}
<dl class="grid grid-cols-2 gap-x-6 gap-y-4 border-b border-line py-5 text-sm sm:grid-cols-4">
    <div><dt class="rt-meta">Terbit</dt><dd class="mt-1 font-medium"><time datetime="{{ $article['published']->toIso8601String() }}">{{ $rt->date($article['published'], false) }}, {{ $article['published']->format('H.i') }} WIB</time></dd></div>
    <div><dt class="rt-meta">Waktu baca</dt><dd class="mt-1 font-medium">{{ $article['reading_time'] }} menit</dd></div>
    <div><dt class="rt-meta">Kanal</dt><dd class="mt-1"><a href="{{ $article['category']['url'] }}" class="font-medium hover:text-accent">{{ $article['category']['name'] }}</a></dd></div>
    <div class="min-w-0"><dt class="rt-meta">Topik</dt><dd class="mt-1 [overflow-wrap:anywhere]">@forelse (array_slice($article['tag_list'], 0, 2) as $tag)<a href="{{ $tag['url'] }}" class="mr-2 font-medium hover:text-accent">#{{ $tag['name'] }}</a>@empty — @endforelse</dd></div>
</dl>
<div class="flex items-center justify-between gap-4 py-4">
    <p class="rt-meta">Bagikan tulisan ini</p>
    <x-default::share />
</div>
