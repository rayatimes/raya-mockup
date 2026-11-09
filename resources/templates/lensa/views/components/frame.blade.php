@props(['src', 'alt' => '', 'eager' => false, 'hero' => false])
<span {{ $attributes->merge(['class' => 'ls-frame']) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" @if ($eager) fetchpriority="high" @else loading="lazy" @endif decoding="async" @if ($hero) style="view-transition-name: ls-hero" @endif>
</span>
