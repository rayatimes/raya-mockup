@php $site = $rt->site(); @endphp
<footer class="border-t border-line bg-sunk">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:grid-cols-2 lg:grid-cols-[1.3fr_1fr_1fr_1.2fr]">
        <div>
            <img src="{{ $site['brand']['logo'] }}" alt="RayaTimes portal" class="h-10 dark:hidden" loading="lazy">
            <img src="{{ $site['brand']['logo_dark'] }}" alt="RayaTimes portal" class="hidden h-10 dark:block" loading="lazy">
            <p class="mt-4 max-w-xs text-sm leading-relaxed text-muted">{{ $site['description'] }}</p>
            <div class="mt-5 flex gap-2">
                @foreach ($site['social_links'] as $social)
                    <a href="{{ $social['url'] }}" class="grid size-9 place-items-center border border-line text-lg text-fg transition hover:border-raya hover:bg-raya hover:text-white" aria-label="{{ ucfirst($social['platform']) }}"><i class="ri-{{ $social['platform'] }}-line"></i></a>
                @endforeach
            </div>
        </div>
        @foreach ($site['footer_columns'] as $column)
            <div>
                <h2 class="font-serif text-base font-bold">{{ $column['title'] }}</h2>
                <ul class="mt-4 space-y-2.5 text-sm">
                    @foreach ($column['links'] as $link)
                        <li><a href="{{ $link['url'] }}" class="text-muted hover:text-accent">{{ $link['label'] }}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
        <div>
            <h2 class="font-serif text-base font-bold">{{ $site['app_download']['title'] }}</h2>
            <p class="mt-4 text-sm text-muted">{{ $site['app_download']['text'] }}</p>
            <div class="mt-4 flex flex-wrap gap-2">
                @foreach ($site['app_download']['links'] as $app)
                    <a href="{{ $app['url'] }}" class="transition hover:opacity-80"><img src="{{ $app['badge'] }}" alt="Unduh di {{ $app['name'] }}" class="h-10 w-auto" loading="lazy"></a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="border-t border-line">
        <p class="mx-auto max-w-7xl px-4 py-5 text-center text-xs text-muted">© {{ date('Y') }} RayaTimes. Fakta, Makna, Daya Bangsa. Seluruh hak cipta dilindungi.</p>
    </div>
</footer>
