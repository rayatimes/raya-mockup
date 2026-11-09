@props(['src', 'alt' => '', 'eager' => false])
<span {{ $attributes->merge(['class' => 'wc-photo']) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" @if ($eager) fetchpriority="high" @else loading="lazy" @endif decoding="async">
</span>
