@props(['author', 'size' => 'size-5'])
@if ($author['avatar'] ?? null)
    <img src="{{ $author['avatar'] }}" alt="" loading="lazy" {{ $attributes->merge(['class' => $size.' shrink-0 object-cover bg-sunk']) }}>
@else
    <span {{ $attributes->merge(['class' => $size.' grid shrink-0 place-items-center bg-raya text-[0.6em] font-bold text-white']) }} aria-hidden="true">{{ mb_substr($author['name'] ?? 'R', 0, 1) }}</span>
@endif
