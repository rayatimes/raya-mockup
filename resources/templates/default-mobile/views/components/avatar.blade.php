@props(['person', 'size' => 40])
@php $initial = mb_strtoupper(mb_substr($person['name'] ?? 'R', 0, 1)); @endphp
<span class="avatar" style="--size: {{ $size }}px">
    @if (! empty($person['avatar']))
        <img src="{{ $person['avatar'] }}" alt="" loading="lazy">
    @else
        {{ $initial }}
    @endif
</span>
