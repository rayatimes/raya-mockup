@php
    $channels = array_values(array_filter($rt->categories(), fn ($category) => count($rt->inCategory($category['slug'])) >= 5));
@endphp
<div class="scr" data-sb="auto">
    <x-lensa-mobile::appbar title="Minat Topik" />
    <div class="scroll" data-scroll>
        <p class="lede-pad">Pilih kanal yang ingin sering Anda baca. Tab Untuk Anda memakai riwayat baca lebih dulu, lalu pilihan ini.</p>
        <div class="chip-wrap chip-wrap-pad">
            @foreach ($channels as $channel)
                <button type="button" class="m-chip" data-interest="{{ $channel['slug'] }}" aria-pressed="false">{{ $channel['name'] }}</button>
            @endforeach
        </div>
        <div class="article-end"></div>
    </div>
</div>
