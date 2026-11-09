{{-- Pola HTML yang dikloning JS (muat lebih banyak & pencarian instan). --}}
<template id="tpl-row">
    <article data-card class="group flex gap-4 py-5">
        <a data-href="url" class="rt-media aspect-[4/3] w-28 shrink-0 sm:w-40"><img data-src="image" data-alt="title" alt="" loading="lazy"></a>
        <div class="min-w-0">
            <a data-href="category_url" data-f="category" class="rt-kanal"></a>
            <h3 class="mt-1 text-base leading-snug sm:text-lg"><a data-href="url" data-f="title" class="rt-title"></a></h3>
            <p data-f="excerpt" class="mt-1.5 hidden text-sm leading-relaxed text-muted line-clamp-2 sm:block"></p>
            <p class="rt-meta mt-2"><a data-href="author_url" data-f="author" class="font-medium text-fg hover:text-accent"></a> · <span data-f="ago"></span></p>
        </div>
    </article>
</template>
<template id="tpl-row-skeleton">
    <div class="flex gap-4 py-5" aria-hidden="true">
        <span class="sk aspect-[4/3] w-28 shrink-0 sm:w-40"></span>
        <div class="flex-1 space-y-2.5 pt-1"><span class="sk-line w-16"></span><span class="sk-line h-4 w-11/12"></span><span class="sk-line h-4 w-3/4"></span><span class="sk-line w-1/3"></span></div>
    </div>
</template>
<template id="tpl-tile">
    <article data-card class="group flex flex-col border border-line bg-surface">
        <a data-href="url" class="rt-media aspect-video"><img data-src="image" data-alt="title" alt="" loading="lazy"></a>
        <div class="flex flex-1 flex-col p-4">
            <p class="rt-meta"><a data-href="category_url" data-f="category" class="rt-kanal"></a> · <span data-f="ago"></span></p>
            <h3 class="mt-2 text-lg leading-snug line-clamp-3"><a data-href="url" data-f="title" class="rt-title"></a></h3>
            <p data-f="excerpt" class="mt-2 text-sm leading-relaxed text-muted line-clamp-2"></p>
        </div>
    </article>
</template>
<template id="tpl-tile-skeleton">
    <div class="border border-line bg-surface" aria-hidden="true">
        <span class="sk block aspect-video"></span>
        <div class="space-y-2.5 p-4"><span class="sk-line w-1/3"></span><span class="sk-line h-4"></span><span class="sk-line h-4 w-2/3"></span></div>
    </div>
</template>
<template id="tpl-suggest">
    <a data-href="url" data-card class="group flex items-center gap-3 px-4 py-3 hover:bg-sunk">
        <span class="rt-media aspect-square w-12 shrink-0"><img data-src="image" data-alt="title" alt=""></span>
        <span class="min-w-0">
            <span data-f="category" class="rt-kanal block"></span>
            <span data-f="title" class="block font-serif text-sm font-semibold leading-snug line-clamp-2"></span>
        </span>
    </a>
</template>
<template id="tpl-suggest-skeleton">
    <div class="flex items-center gap-3 px-4 py-3" aria-hidden="true">
        <span class="sk size-12 shrink-0"></span>
        <span class="flex-1 space-y-2"><span class="sk-line w-14"></span><span class="sk-line w-11/12"></span></span>
    </div>
</template>
