@props(['rt', 'exclude' => [], 'limit' => 5])
<section class="rt-panel p-4">
    <x-default::section-head title="Terbaru" as="h2" class="!mb-1" />
    <div class="divide-y divide-line">
        @foreach ($rt->latest($limit, 0, $exclude) as $item)
            <x-default::card.mini :article="$item" :rt="$rt" />
        @endforeach
    </div>
</section>
