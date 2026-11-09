{{-- Pembatas seksi: selalu hitam dan setipis garis bilah menu. --}}
@props(['title', 'href' => null, 'lede' => null])
<div {{ $attributes->merge(['class' => 'mb-8 flex items-end justify-between gap-6 border-t-[3px] border-ink pt-4']) }}>
    <div>
        <h2 class="wc-ui-display text-2xl lg:text-[2rem]">
            @if ($href)<a href="{{ $href }}" class="transition hover:text-java">{{ $title }}</a>@else{{ $title }}@endif
        </h2>
        @if ($lede)<p class="mt-2 max-w-2xl text-base text-graphite">{{ $lede }}</p>@endif
    </div>
    @if ($href)<a href="{{ $href }}" class="shrink-0 text-sm font-semibold underline decoration-2 underline-offset-4 transition hover:text-java">Selengkapnya</a>@endif
</div>
