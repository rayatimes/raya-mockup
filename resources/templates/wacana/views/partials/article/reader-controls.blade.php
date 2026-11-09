{{-- Kontrol baca: ukuran huruf & mode fokus. --}}
<div class="flex items-center gap-1">
    <button type="button" data-scale="-0.05" class="grid size-9 place-items-center bg-stone text-sm font-semibold transition hover:bg-ink hover:text-paper" aria-label="Perkecil huruf">A−</button>
    <span data-scale-value class="w-12 text-center text-sm tabular-nums">100%</span>
    <button type="button" data-scale="0.05" class="grid size-9 place-items-center bg-stone text-base font-semibold transition hover:bg-ink hover:text-paper" aria-label="Perbesar huruf">A+</button>
</div>
<button type="button" data-focus-mode aria-pressed="false" class="flex items-center gap-2 text-sm font-semibold underline decoration-2 underline-offset-4 aria-[pressed=true]:text-java"><i class="ri-focus-2-line"></i>Mode fokus</button>
