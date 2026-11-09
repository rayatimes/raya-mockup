@props(['category'])
<a href="{{ $category['url'] }}" data-kanal="{{ $category['slug'] }}" {{ $attributes->merge(['class' => 'wc-kanal']) }}>{{ $category['name'] }}</a>
