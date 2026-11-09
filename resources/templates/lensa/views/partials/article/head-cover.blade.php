{{-- Tata letak "cover dulu" (Analisis): cover penuh tanpa teks, lalu judul, penulis, dan atribut tulisan. --}}
<figure data-theme="dark" class="relative bg-ground">
    <x-lensa::frame :src="$article['image']['lg']" :alt="$article['title']" eager hero class="h-[78svh] min-h-[28rem] w-full" />
    <span class="pointer-events-none absolute inset-x-0 top-0 h-40 bg-gradient-to-b from-black/70 to-transparent"></span>
    @if ($article['caption'])
        <figcaption class="absolute bottom-4 right-4 max-w-md rounded-full bg-black/60 px-4 py-1.5 text-xs text-white/85"><i class="ri-camera-line"></i> {{ $article['caption'] }}</figcaption>
    @endif
</figure>

<header class="mx-auto max-w-5xl px-4 pt-10 lg:px-8">
    <nav class="flex items-center gap-2 text-sm text-dim" aria-label="Breadcrumb">
        <a href="{{ $rt->url('/') }}" class="hover:text-sun">Beranda</a><i class="ri-arrow-right-s-line"></i>
        <a href="{{ $article['category']['url'] }}" class="hover:text-sun">{{ $article['category']['name'] }}</a>
    </nav>
    <a href="{{ $article['category']['url'] }}" class="mt-5 inline-flex rounded-full bg-sun px-4 py-1.5 text-sm font-semibold text-ground">{{ $article['category']['name'] }}</a>
    @if ($article['upper'])
        <p class="ls-headline mt-4 text-2xl text-sun lg:text-3xl">{{ $article['upper'] }}</p>
    @endif
    <h1 class="ls-headline {{ $article['upper'] ? 'mt-2' : 'mt-4' }} text-[2.5rem] sm:text-6xl lg:text-7xl">{{ $article['title'] }}</h1>
    <p class="mt-5 max-w-3xl font-read text-xl leading-relaxed text-ink/85 lg:text-2xl">{{ $article['excerpt'] }}</p>

    {{-- Penulis --}}
    <div class="mt-8 grid gap-3 sm:grid-cols-2">
        @foreach ($article['authors'] as $author)
            <a href="{{ $author['url'] }}" class="group flex items-center gap-4 rounded-frame bg-panel p-4 transition hover:bg-raise">
                @if ($author['avatar'])
                    <img src="{{ $author['avatar'] }}" alt="" class="size-16 shrink-0 rounded-full object-cover">
                @else
                    <span class="ls-headline grid size-16 shrink-0 place-items-center rounded-full bg-sun text-2xl text-ground">{{ mb_substr($author['name'], 0, 1) }}</span>
                @endif
                <span class="min-w-0">
                    <span class="block text-xs text-dim">Ditulis oleh</span>
                    <span class="ls-headline block text-2xl transition-colors group-hover:text-sun">{{ $author['name'] }}</span>
                    <span class="block text-xs font-semibold text-sun">{{ $author['label'] }} · {{ $author['followers'] }} pengikut</span>
                </span>
            </a>
        @endforeach
    </div>

    {{-- Atribut tulisan --}}
    <ul class="mt-5 flex flex-wrap gap-2 text-sm">
        <li class="ls-pill pointer-events-none"><i class="ri-calendar-line"></i>{{ $rt->date($article['published']) }}</li>
        <li class="ls-pill pointer-events-none"><i class="ri-time-line"></i>{{ $article['reading_time'] }} menit baca</li>
        @foreach (array_slice($article['tag_list'], 0, 3) as $tag)
            <li><a href="{{ $tag['url'] }}" class="ls-pill">#{{ $tag['name'] }}</a></li>
        @endforeach
    </ul>
</header>
