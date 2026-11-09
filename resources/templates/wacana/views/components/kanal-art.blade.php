{{-- Ilustrasi latar halaman kanal: tinggi mengikuti header, menempel di kanan agar teks di kiri tetap terbaca. --}}
@props(['slug'])
<svg viewBox="0 0 1600 420" aria-hidden="true" focusable="false" {{ $attributes->merge(['class' => 'wc-art']) }}>
    @includeFirst(['wacana::partials.kanal-art.'.$slug, 'wacana::partials.kanal-art.berita'])
</svg>
