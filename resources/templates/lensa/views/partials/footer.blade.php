@php $site = $rt->site(); @endphp
<footer class="mt-24 bg-panel">
    <div class="mx-auto max-w-[90rem] px-4 py-14 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-[1.4fr_2fr]">
            <div>
                <img src="{{ $site['brand']['logo_main_dark'] }}" alt="RayaTimes" class="ls-logo-on-dark h-12 w-auto" loading="lazy">
                <img src="{{ $site['brand']['logo_main'] }}" alt="RayaTimes" class="ls-logo-on-light h-12 w-auto" loading="lazy">
                <p class="ls-headline mt-6 max-w-md text-4xl text-ink lg:text-5xl">Fakta, makna, daya bangsa.</p>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-dim">{{ $site['description'] }}</p>
            </div>
            <div class="grid gap-10 sm:grid-cols-3">
                @foreach ($site['footer_columns'] as $column)
                    <div>
                        <h2 class="text-sm font-semibold text-sun">{{ $column['title'] }}</h2>
                        <ul class="mt-4 space-y-3 text-sm">
                            @foreach ($column['links'] as $link)
                                <li><a href="{{ $link['url'] }}" class="text-ink/75 transition hover:text-ink">{{ $link['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
                <div>
                    <h2 class="text-sm font-semibold text-sun">{{ $site['app_download']['title'] }}</h2>
                    <p class="mt-4 text-sm text-dim">{{ $site['app_download']['text'] }}</p>
                    <div class="mt-4 flex flex-col items-start gap-2">
                        @foreach ($site['app_download']['links'] as $app)
                            <a href="{{ $app['url'] }}" class="transition hover:opacity-80"><img src="{{ $app['badge'] }}" alt="Unduh di {{ $app['name'] }}" class="h-10 w-auto" loading="lazy"></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-14 flex flex-col justify-between gap-4 border-t border-edge pt-6 text-xs text-dim sm:flex-row">
            <p>© {{ date('Y') }} RayaTimes. Seluruh hak cipta dilindungi.</p>
            <div class="flex gap-3">
                @foreach ($site['social_links'] as $social)
                    <a href="{{ $social['url'] }}" class="text-base text-ink/70 hover:text-sun" aria-label="{{ ucfirst($social['platform']) }}"><i class="ri-{{ $social['platform'] }}-line"></i></a>
                @endforeach
            </div>
        </div>
    </div>
</footer>
