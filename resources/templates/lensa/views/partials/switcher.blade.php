{{-- Pemilih template (presentasi). --}}
<div class="fixed bottom-24 right-4 z-[60] lg:bottom-6 lg:right-6">
    <div id="ls-switcher" data-panel-body data-open="false"
        class="invisible absolute bottom-full right-0 mb-3 w-72 origin-bottom-right scale-95 rounded-2xl bg-panel p-2 opacity-0 shadow-2xl ring-1 ring-edge transition duration-300 data-[open=true]:visible data-[open=true]:scale-100 data-[open=true]:opacity-100">
        <p class="px-3 pb-2 pt-1 text-xs text-dim">Ganti tampilan portal</p>
        @foreach ($templates as $key => $info)
            <a href="{{ $rt->templateUrl($key) }}" @if ($key === $template) aria-current="true" @endif
                class="flex items-center gap-3 rounded-xl px-3 py-2.5 transition hover:bg-raise aria-[current=true]:bg-raise">
                <span class="grid size-9 shrink-0 place-items-center rounded-full {{ $key === $template ? 'bg-sun text-ground' : 'bg-raise text-dim' }}"><i class="{{ $key === $template ? 'ri-check-line' : 'ri-layout-line' }}"></i></span>
                <span><span class="block text-sm font-semibold">{{ $info['name'] }}</span><span class="block text-xs text-dim">{{ $info['note'] }}</span></span>
            </a>
        @endforeach
    </div>
    <button type="button" data-panel="ls-switcher" aria-controls="ls-switcher" aria-expanded="false"
        class="flex items-center gap-2 rounded-full bg-sun py-3 pl-3 pr-4 text-sm font-semibold text-ground shadow-xl transition hover:bg-ink">
        <i class="ri-contrast-drop-2-line text-lg"></i>{{ $templates[$template]['name'] }}
    </button>
</div>
