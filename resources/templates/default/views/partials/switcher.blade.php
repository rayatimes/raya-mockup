{{-- Pemilih template untuk kebutuhan presentasi mockup. --}}
<div class="fixed bottom-4 left-4 z-[65] print:hidden">
    <div id="rt-switcher" data-open="false"
        class="invisible absolute bottom-full left-0 mb-2 w-72 translate-y-2 border border-line bg-surface opacity-0 shadow-[0_24px_50px_-24px_rgb(0_0_0/0.55)] transition duration-200 data-[open=true]:visible data-[open=true]:translate-y-0 data-[open=true]:opacity-100">
        <p class="border-b border-line px-4 py-3 text-xs font-semibold text-muted">Pilih template portal</p>
        <ul>
            @foreach ($templates as $key => $info)
                <li>
                    <a href="{{ $rt->templateUrl($key) }}" @if ($key === $template) aria-current="true" @endif
                        class="flex items-start gap-3 px-4 py-3 transition hover:bg-sunk aria-[current=true]:bg-sunk">
                        <span class="mt-1 size-3 shrink-0 border-2 {{ $key === $template ? 'border-raya bg-raya' : 'border-line' }}"></span>
                        <span>
                            <span class="block text-sm font-semibold">{{ $info['name'] }}</span>
                            <span class="block text-xs text-muted">{{ $info['note'] }}</span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <button type="button" data-toggle="rt-switcher" aria-controls="rt-switcher" aria-expanded="false"
        class="flex items-center gap-2 bg-ink p-3 sm:px-4 text-sm font-semibold text-white shadow-lg transition hover:bg-raya dark:bg-white dark:text-ink dark:hover:bg-sun">
        <i class="ri-layout-masonry-line text-base"></i><span class="sr-only sm:not-sr-only">Template: {{ $templates[$template]['name'] }}</span>
    </button>
</div>
