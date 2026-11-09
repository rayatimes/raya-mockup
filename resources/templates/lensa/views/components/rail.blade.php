{{-- Rel kartu foto yang digeser. inset=false: dipakai di dalam kolom (tanpa kontainer & bleed sendiri). --}}
@props(['title', 'href' => null, 'items', 'rt', 'note' => null, 'inset' => true])
<section data-rail {{ $attributes->merge(['class' => $inset ? 'mx-auto max-w-[90rem] px-4 lg:px-8' : '']) }}>
    <x-lensa::head :title="$title" :href="$href" :note="$note" rail />
    <div data-track @class([
        'no-scrollbar -mx-4 flex cursor-grab snap-x snap-mandatory scroll-px-4 gap-4 overflow-x-auto px-4 active:cursor-grabbing',
        'lg:-mx-8 lg:scroll-px-8 lg:px-8' => $inset,
        'lg:mx-0 lg:scroll-px-0 lg:px-0' => ! $inset,
    ])>
        @foreach ($items as $item)
            <x-lensa::card.photo :article="$item" :rt="$rt" ratio="aspect-[3/4]" @class([
                'w-[70%] shrink-0 snap-start sm:w-[40%]',
                'lg:w-[calc((100%-4rem)/5)]' => $inset,
                'lg:w-[calc((100%-2rem)/3)] xl:w-[calc((100%-3rem)/4)]' => ! $inset,
            ]) />
        @endforeach
    </div>
</section>
