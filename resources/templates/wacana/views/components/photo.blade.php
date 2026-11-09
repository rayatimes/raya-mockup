@props(['src', 'alt' => '', 'eager' => false, 'duo' => false])
<span {{ $attributes->merge(['class' => $duo ? 'wc-duo' : 'wc-photo']) }}>
    <img src="{{ $src }}" alt="{{ $alt }}" @if ($eager) fetchpriority="high" @else loading="lazy" @endif decoding="async">
</span>
