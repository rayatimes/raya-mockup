{{-- Pemilih template (presentasi) --}}
<div class="fixed bottom-5 right-5 z-[60]">
    <div id="wc-switcher" data-panel data-dismiss-outside data-open="false"
        class="invisible absolute bottom-full right-0 mb-2 w-80 translate-y-2 bg-paper opacity-0 shadow-[0_30px_60px_-30px_rgb(0_0_0/0.5)] ring-1 ring-ink/10 transition duration-300 data-[open=true]:visible data-[open=true]:translate-y-0 data-[open=true]:opacity-100">
        <p class="bg-stone px-5 py-3 text-[15px] font-semibold">Pilih edisi tampilan</p>
        @foreach ($templates as $key => $info)
            <a href="{{ $rt->templateUrl($key) }}" @if ($key === $template) aria-current="true" @endif
                class="group flex items-center justify-between gap-4 px-5 py-4 transition hover:bg-stone aria-[current=true]:bg-beige/50">
                <span><span class="wc-ui-head block text-[15px]">{{ $info['name'] }}</span><span class="text-[13px] text-ash">{{ $info['note'] }}</span></span>
                <i class="{{ $key === $template ? 'ri-check-line text-java' : 'ri-arrow-right-line text-ash' }} text-lg"></i>
            </a>
        @endforeach
    </div>
    <button type="button" data-open-panel="wc-switcher" aria-expanded="false" class="flex items-center gap-2 bg-java px-4 py-3 text-sm font-semibold text-paper shadow-lg transition hover:bg-ink">
        <i class="ri-book-open-line text-base"></i><span class="hidden sm:inline">Tampilan:</span> {{ $templates[$template]['name'] }}
    </button>
</div>
