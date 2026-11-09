@props(['rt', 'limit' => 10])
<section class="rt-panel p-4">
    <x-default::section-head title="Terpopuler" as="h2" class="!mb-1" />
    <ol class="divide-y divide-line">
        @foreach ($rt->popular($limit) as $i => $item)
            <x-default::card.rank :article="$item" :rt="$rt" :rank="$i + 1" />
        @endforeach
    </ol>
</section>
