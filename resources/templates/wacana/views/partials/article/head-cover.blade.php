{{-- Tata letak "cover dulu" (Analisis): cover lebar, lalu judul, penulis, dan atribut tulisan. --}}
<header>
    <figure class="mx-auto max-w-[84rem] px-4 pt-10 lg:px-8">
        <x-wacana::photo :src="$article['image']['lg']" :alt="$article['title']" eager class="aspect-video lg:aspect-[21/9]" />
        @if ($article['caption'])<figcaption class="mt-2 text-[13px] text-ash">{{ $article['caption'] }}</figcaption>@endif
    </figure>

    <div class="mx-auto mt-10 grid max-w-[84rem] gap-10 px-4 lg:grid-cols-12 lg:px-8">
        <div class="lg:col-span-8">
            <p class="inline-flex bg-(--hue) px-3 py-1.5 text-[13px] font-semibold text-(--on-hue)">{{ $article['category']['name'] }}</p>
            @if ($article['upper'])
                <p class="mt-4 font-serif text-2xl italic text-java lg:text-3xl">{{ $article['upper'] }}</p>
            @endif
            <h1 class="wc-display {{ $article['upper'] ? 'mt-2' : 'mt-4' }} text-[2.5rem] sm:text-6xl lg:text-[4.2rem]">{{ $article['title'] }}</h1>
            <p class="mt-6 max-w-3xl font-serif text-xl italic leading-relaxed text-graphite lg:text-2xl">{{ $article['excerpt'] }}</p>
        </div>

        {{-- Penulis dan atribut tulisan --}}
        <aside class="space-y-6 border-t-[6px] border-(--hue) pt-5 lg:col-span-4 lg:self-end">
            @php $many = count($article['authors']) > 2; @endphp
            @foreach ($article['authors'] as $author)
                <div class="flex gap-4">
                    @if ($author['avatar'])
                        <img src="{{ $author['avatar'] }}" alt="" class="size-16 shrink-0 object-cover grayscale">
                    @else
                        <span class="wc-display grid size-16 shrink-0 place-items-center bg-ink text-3xl text-paper">{{ mb_substr($author['name'], 0, 1) }}</span>
                    @endif
                    <div>
                        <p class="text-[13px] text-ash">Ditulis oleh</p>
                        <a href="{{ $author['url'] }}" class="wc-head text-xl hover:text-java">{{ $author['name'] }}</a>
                        <p class="text-[13px] font-semibold text-java">{{ $author['label'] }}</p>
                    </div>
                </div>
                @unless ($many)
                    <p class="font-serif text-[17px] leading-relaxed text-graphite line-clamp-3">{{ $author['bio'] ?: 'Menulis untuk RayaTimes: fakta yang utuh, konteks yang jernih.' }}</p>
                @endunless
            @endforeach
            <dl class="grid grid-cols-2 gap-x-6 gap-y-3 text-[15px]">
                <div><dt class="text-[13px] text-ash">Terbit</dt><dd class="font-semibold">{{ $rt->date($article['published'], false) }}</dd></div>
                <div><dt class="text-[13px] text-ash">Waktu baca</dt><dd class="font-semibold">{{ $article['reading_time'] }} menit</dd></div>
                <div class="col-span-2"><dt class="text-[13px] text-ash">Topik</dt><dd>@forelse (array_slice($article['tag_list'], 0, 3) as $tag)<a href="{{ $tag['url'] }}" class="mr-3 font-semibold underline decoration-ink/20 decoration-2 underline-offset-4 hover:decoration-java">{{ $tag['name'] }}</a>@empty — @endforelse</dd></div>
            </dl>
        </aside>
    </div>
</header>
