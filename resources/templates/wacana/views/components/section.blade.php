@props(['title', 'href' => null, 'kanal' => null, 'lede' => null])
<div @if ($kanal) data-kanal="{{ $kanal }}" @endif {{ $attributes->merge(['class' => 'mb-8 flex items-end justify-between gap-6 border-t-[6px] border-(--hue,var(--color-ink)) pt-4']) }}>
    <div>
        <h2 class="wc-display text-3xl lg:text-[2.6rem]">
            @if ($href)<a href="{{ $href }}" class="transition hover:text-java">{{ $title }}</a>@else{{ $title }}@endif
        </h2>
        @if ($lede)<p class="mt-2 max-w-2xl font-serif text-lg text-graphite">{{ $lede }}</p>@endif
    </div>
    @if ($href)<a href="{{ $href }}" class="shrink-0 text-sm font-semibold underline decoration-2 underline-offset-4 transition hover:text-java">Selengkapnya</a>@endif
</div>
