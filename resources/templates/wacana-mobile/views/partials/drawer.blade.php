@php
    $site = $rt->site();
    $channels = array_values(array_filter($rt->categories(), fn ($category) => $category['parent'] === null));
    $staticPages = array_values(array_filter($rt->pages(), fn ($page) => $page['slug'] !== 'account-deletion'));
@endphp
{{-- Panel dari tombol kiri header: Kanal berwarna, Halaman, dan Insight. --}}
<aside class="drawer" data-drawer data-open="false" aria-label="Kanal, halaman, dan Insight">
    <div class="drawer-head">
        <h2 class="drawer-title">Kanal</h2>
        <button type="button" class="icon-btn" data-overlay-close aria-label="Tutup panel"><i class="ri-close-line"></i></button>
    </div>
    <div class="drawer-body">
        @foreach ($channels as $channel)
            @php $children = $rt->childCategories($channel['slug']); @endphp
            <div class="drawer-row" data-accordion data-open="false" data-kanal="{{ $channel['slug'] }}">
                <div class="drawer-line">
                    <a href="{{ $channel['url'] }}" class="drawer-link"><span class="kanal-mark"></span>{{ $channel['name'] }}</a>
                    @if ($children)
                        <button type="button" class="drawer-toggle" data-accordion-toggle aria-expanded="false" aria-label="Sub-kanal {{ $channel['name'] }}"><i class="ri-arrow-right-s-line"></i></button>
                    @endif
                </div>
                @if ($children)
                    <div class="drawer-children">
                        @foreach ($children as $child)
                            <a href="{{ $child['url'] }}">{{ $child['name'] }}</a>
                        @endforeach
                    </div>
                @endif
            </div>
        @endforeach

        <h3 class="drawer-group">Halaman</h3>
        @foreach ($staticPages as $staticPage)
            <a href="{{ $rt->url('/p/'.$staticPage['slug']) }}" class="drawer-page">{{ $staticPage['title'] }}</a>
        @endforeach

        <h3 class="drawer-group">Insight</h3>
        <a href="{{ $site['insight_url'] }}" target="_blank" rel="noopener" class="drawer-insight">
            <img src="{{ $site['brand']['insight'] }}" alt="" class="drawer-insight-light">
            <img src="{{ $site['brand']['insight_dark'] }}" alt="" class="drawer-insight-dark">
            <span>Menelusuri fakta, menemukan makna <i class="ri-external-link-line"></i></span>
        </a>
    </div>
</aside>
