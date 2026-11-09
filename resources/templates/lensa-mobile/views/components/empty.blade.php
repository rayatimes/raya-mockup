@props(['icon' => 'ri-inbox-line', 'title', 'message'])
<div {{ $attributes->merge(['class' => 'empty']) }}>
    <i class="{{ $icon }}"></i>
    <h2>{{ $title }}</h2>
    <p>{{ $message }}</p>
</div>
