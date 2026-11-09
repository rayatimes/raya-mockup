@props(['title', 'href' => null, 'items', 'rt'])
<section data-carousel {{ $attributes }}>
    <div class="mb-5 flex items-end justify-between gap-4 border-b border-line pb-3">
        <h2 class="rt-section-title">
            @if ($href)<a href="{{ $href }}" class="transition hover:text-accent">{{ $title }}</a>@else{{ $title }}@endif
        </h2>
        <div class="flex items-center gap-3">
            @if ($href)<a href="{{ $href }}" class="text-xs font-semibold text-accent hover:underline">Lihat semua {{ $title }}</a>@endif
            <div class="hidden gap-1 sm:flex">
                <button type="button" data-prev class="grid size-8 place-items-center border border-line transition hover:bg-fg hover:text-paper disabled:pointer-events-none disabled:opacity-30" aria-label="Sebelumnya"><i class="ri-arrow-left-s-line"></i></button>
                <button type="button" data-next class="grid size-8 place-items-center border border-line transition hover:bg-fg hover:text-paper disabled:pointer-events-none disabled:opacity-30" aria-label="Berikutnya"><i class="ri-arrow-right-s-line"></i></button>
            </div>
        </div>
    </div>
    <div data-track class="no-scrollbar -mx-4 flex snap-x snap-mandatory gap-5 overflow-x-auto scroll-px-4 px-4 lg:mx-0 lg:scroll-px-0 lg:px-0">
        @foreach ($items as $item)
            <x-default::card.tile :article="$item" :rt="$rt" :excerpt="false" :boxed="false" class="w-[72%] shrink-0 snap-start sm:w-[calc((100%-2.5rem)/3)]" />
        @endforeach
    </div>
</section>
