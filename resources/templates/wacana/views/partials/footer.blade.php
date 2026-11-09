@php $site = $rt->site(); @endphp
<footer class="mt-24 bg-ink text-paper">
    <div class="mx-auto max-w-[84rem] px-4 py-16 lg:px-8">
        <p class="wc-display max-w-4xl text-4xl lg:text-6xl">Melihat lebih luas, memahami lebih utuh, bertindak lebih solutif.</p>
        <div class="mt-14 grid gap-10 sm:grid-cols-2 lg:grid-cols-4">
            <div>
                <img src="{{ $site['brand']['logo_main_dark'] }}" alt="RayaTimes" class="h-10 w-auto" loading="lazy">
                <p class="mt-4 text-sm leading-relaxed text-paper/65">{{ $site['description'] }}</p>
            </div>
            @foreach ($site['footer_columns'] as $column)
                <div>
                    <h2 class="text-[13px] font-semibold text-beige">{{ $column['title'] }}</h2>
                    <ul class="mt-4 space-y-2.5 font-serif text-lg">
                        @foreach ($column['links'] as $link)
                            <li><a href="{{ $link['url'] }}" class="text-paper/85 transition hover:text-sun">{{ $link['label'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
            <div>
                <h2 class="text-[13px] font-semibold text-beige">{{ $site['app_download']['title'] }}</h2>
                <p class="mt-4 text-sm text-paper/65">{{ $site['app_download']['text'] }}</p>
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($site['app_download']['links'] as $app)
                        <a href="{{ $app['url'] }}" class="transition hover:opacity-80"><img src="{{ $app['badge'] }}" alt="Unduh di {{ $app['name'] }}" class="h-10 w-auto" loading="lazy"></a>
                    @endforeach
                </div>
            </div>
        </div>
        <p class="mt-14 border-t border-paper/15 pt-6 text-[13px] text-paper/55">© {{ date('Y') }} RayaTimes. Seluruh hak cipta dilindungi.</p>
    </div>
</footer>
