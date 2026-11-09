@props(['title', 'back' => true])
<header class="appbar">
    @if ($back)
        <button type="button" class="icon-btn" data-back aria-label="Kembali"><i class="ri-arrow-left-s-line"></i></button>
    @endif
    <h1 class="appbar-title">{{ $title }}</h1>
    <div class="appbar-action">{{ $slot }}</div>
</header>
