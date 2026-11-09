@props(['title', 'href' => null, 'more' => 'Lihat semua', 'as' => 'h2'])
<div {{ $attributes->merge(['class' => 'mb-5 flex items-end justify-between gap-4 border-b border-line pb-3']) }}>
    <{{ $as }} class="rt-section-title">{{ $title }}</{{ $as }}>
    @if ($href)
        <a href="{{ $href }}" class="group/more flex items-center gap-1 text-xs font-semibold text-accent">{{ $more }}<i class="ri-arrow-right-line transition-transform duration-300 group-hover/more:translate-x-1"></i></a>
    @endif
</div>
