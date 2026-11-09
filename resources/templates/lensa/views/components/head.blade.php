@props(['title', 'href' => null, 'note' => null, 'rail' => false])
<div {{ $attributes->merge(['class' => 'mb-6 flex items-end justify-between gap-4']) }}>
    <div>
        <h2 class="ls-headline text-3xl lg:text-4xl">
            @if ($href)<a href="{{ $href }}" class="transition hover:text-sun">{{ $title }}</a>@else{{ $title }}@endif
        </h2>
        @if ($note)<p class="mt-1 text-sm text-dim">{{ $note }}</p>@endif
    </div>
    <div class="flex shrink-0 items-center gap-2">
        @if ($href)<a href="{{ $href }}" class="group/m inline-flex items-center gap-1 text-sm font-medium text-dim transition hover:text-sun">Semua<i class="ri-arrow-right-up-line transition group-hover/m:-translate-y-0.5 group-hover/m:translate-x-0.5"></i></a>@endif
        @if ($rail)
            <button type="button" data-prev class="hidden size-10 place-items-center rounded-full bg-raise text-lg transition hover:bg-sun hover:text-ground disabled:opacity-30 disabled:hover:bg-raise disabled:hover:text-ink sm:grid" aria-label="Sebelumnya"><i class="ri-arrow-left-line"></i></button>
            <button type="button" data-next class="hidden size-10 place-items-center rounded-full bg-raise text-lg transition hover:bg-sun hover:text-ground disabled:opacity-30 disabled:hover:bg-raise disabled:hover:text-ink sm:grid" aria-label="Berikutnya"><i class="ri-arrow-right-line"></i></button>
        @endif
    </div>
</div>
