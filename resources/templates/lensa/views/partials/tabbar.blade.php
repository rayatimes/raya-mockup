@php $path = $rt->localPath(); @endphp
<nav class="fixed inset-x-0 bottom-0 z-50 border-t border-edge bg-ground pb-[env(safe-area-inset-bottom)] lg:hidden" aria-label="Navigasi utama">
    <ul class="grid grid-cols-5 text-[11px] font-medium">
        @foreach ([['/', 'ri-home-5', 'Beranda'], ['/index', 'ri-time', 'Terkini'], ['#cari', 'ri-search', 'Cari'], ['/media', 'ri-image-2', 'Media'], ['/tag', 'ri-price-tag-3', 'Topik']] as [$url, $icon, $label])
            @php $active = $url === $path; @endphp
            <li>
                <a href="{{ $rt->url($url === '#cari' ? '/search' : $url) }}" @if ($url === '#cari') data-search-open @endif @if ($active) aria-current="page" @endif
                    class="flex flex-col items-center gap-0.5 py-2.5 text-dim aria-[current=page]:text-sun">
                    <i class="{{ $icon }}-{{ $active ? 'fill' : 'line' }} text-xl"></i>{{ $label }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
