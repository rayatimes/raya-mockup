{{-- Sidebar standar halaman daftar: Terbaru + Terpopuler (sesuai widget layout CMS). --}}
<aside class="space-y-8 lg:sticky lg:top-16 lg:self-start">
    <x-default::widget.latest :rt="$rt" :exclude="$exclude ?? []" />
    <x-default::widget.trending :rt="$rt" :limit="5" />
    <x-default::widget.popular :rt="$rt" />
</aside>
