<div id="ls-search" data-open="false" role="dialog" aria-modal="true" aria-label="Cari berita"
    class="fixed inset-0 z-[70] overflow-y-auto bg-ground opacity-0 transition duration-300 invisible data-[open=true]:visible data-[open=true]:opacity-100">
    <div class="mx-auto max-w-5xl px-4 py-6 lg:py-12">
        <div class="flex items-center gap-4">
            <form action="{{ $rt->url('/search') }}" class="flex flex-1 items-center gap-3 border-b-2 border-edge pb-3 focus-within:border-sun">
                <i class="ri-search-line text-2xl text-dim"></i>
                <label for="ls-q" class="sr-only">Kata kunci</label>
                <input id="ls-q" name="q" type="search" autocomplete="off" placeholder="Cari berita, topik, atau tokoh"
                    class="ls-headline w-full bg-transparent text-3xl placeholder:text-dim/60 focus:outline-none lg:text-5xl">
            </form>
            <button type="button" data-search-close class="grid size-11 shrink-0 place-items-center rounded-full bg-raise text-xl transition hover:bg-sun hover:text-ground" aria-label="Tutup pencarian"><i class="ri-close-line"></i></button>
        </div>
        <div data-hint class="mt-8">
            <p class="text-sm text-dim">Sedang ramai</p>
            <div class="mt-3 flex flex-wrap gap-2">
                @foreach ($rt->hitTopics(10) as $topic)
                    <a href="{{ $topic['url'] }}" class="ls-pill">#{{ $topic['name'] }}</a>
                @endforeach
            </div>
        </div>
        <div data-results class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-3"></div>
    </div>
</div>
