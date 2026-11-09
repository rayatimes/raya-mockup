{{-- Kolom samping standar Lensa --}}
<aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
    <x-lensa::trending :rt="$rt" :limit="5" />
    <x-lensa::rank :rt="$rt" />
    <section class="rounded-frame bg-panel p-6">
        <h2 class="ls-headline text-2xl">Terbaru</h2>
        <div class="mt-5 space-y-5">
            @foreach ($rt->latest(5, 0, $exclude ?? []) as $item)
                <x-lensa::card.line :article="$item" :rt="$rt" time />
            @endforeach
        </div>
    </section>
</aside>
