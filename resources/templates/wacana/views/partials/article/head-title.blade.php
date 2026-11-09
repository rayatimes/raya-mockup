{{-- Tata letak "judul atas": judul di tengah, ringkasan, penulis, lalu foto lebar. --}}
<header class="mx-auto max-w-[84rem] px-4 pt-12 lg:px-8">
    <div class="mx-auto max-w-4xl text-center">
        <x-wacana::kanal :category="$article['category']" class="justify-center" />
        @if ($article['upper'])
            <p class="mt-5 font-serif text-2xl italic text-java lg:text-3xl">{{ $article['upper'] }}</p>
        @endif
        <h1 class="wc-display {{ $article['upper'] ? 'mt-2' : 'mt-5' }} text-[2.5rem] sm:text-6xl lg:text-[4.2rem]">{{ $article['title'] }}</h1>
        <p class="mx-auto mt-6 max-w-3xl text-lg leading-relaxed text-graphite lg:text-xl">{{ $article['excerpt'] }}</p>
        <p class="wc-meta mt-6">
            Oleh <x-wacana::author-names :article="$article" />
            · {{ $rt->date($article['published']) }}
        </p>
    </div>
    <figure class="mt-10">
        <x-wacana::photo :src="$article['image']['lg']" :alt="$article['title']" eager class="aspect-[21/10]" />
        @if ($article['caption'])<figcaption class="mt-2 text-[13px] text-ash">{{ $article['caption'] }}</figcaption>@endif
    </figure>
</header>
