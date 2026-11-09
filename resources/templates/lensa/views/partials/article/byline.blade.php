{{-- Baris penulis ringkas (dipakai tata letak judul atas); semua penulis ditampilkan. --}}
<div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3 text-sm text-ink/80">
    <div class="flex items-center gap-3">
        <span class="flex -space-x-3">
            @foreach (array_slice($article['authors'], 0, 3) as $author)
                @if ($author['avatar'])
                    <img src="{{ $author['avatar'] }}" alt="" class="size-10 rounded-full object-cover ring-2 ring-ground">
                @else
                    <span class="grid size-10 place-items-center rounded-full bg-sun font-bold text-ground ring-2 ring-ground">{{ mb_substr($author['name'], 0, 1) }}</span>
                @endif
            @endforeach
            @if (count($article['authors']) > 3)
                <span class="grid size-10 place-items-center rounded-full bg-raise text-xs font-bold text-ink ring-2 ring-ground">+{{ count($article['authors']) - 3 }}</span>
            @endif
        </span>
        <span>
            <x-lensa::author-names :article="$article" class="block font-semibold text-ink" />
            <span class="text-xs">{{ implode(' · ', array_unique(array_column($article['authors'], 'label'))) }}</span>
        </span>
    </div>
    <span class="flex items-center gap-1.5"><i class="ri-calendar-line"></i>{{ $rt->date($article['published']) }}</span>
    <span class="flex items-center gap-1.5"><i class="ri-time-line"></i>{{ $article['reading_time'] }} menit baca</span>
</div>
