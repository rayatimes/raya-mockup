{{-- Pola yang diisi JS --}}
<template id="tpl-card">
    <article data-card class="group">
        <a data-href="url" class="block">
            <span class="ls-frame aspect-[4/3] rounded-frame"><img data-src="image" alt="" loading="lazy"></span>
            <span data-f="category" class="ls-kanal mt-3"></span>
            <span data-f="title" class="ls-headline mt-1 block text-xl transition group-hover:text-sun"></span>
            <span class="mt-2 block text-xs text-dim"><span data-f="ago"></span> · <span data-f="author"></span></span>
        </a>
    </article>
</template>
<template id="tpl-card-skeleton">
    <div><div class="ls-skel aspect-[4/3]"></div><div class="ls-skel mt-3 h-3 w-1/4"></div><div class="ls-skel mt-2 h-5 w-11/12"></div><div class="ls-skel mt-1.5 h-5 w-2/3"></div></div>
</template>
<template id="tpl-search">
    <a data-href="url" data-card class="group flex gap-4 rounded-frame bg-panel p-3 transition hover:bg-raise">
        <span class="ls-frame aspect-square w-20 shrink-0 rounded-xl"><img data-src="image" alt=""></span>
        <span class="min-w-0">
            <span data-f="category" class="ls-kanal"></span>
            <span data-f="title" class="ls-headline mt-1 block text-lg line-clamp-3"></span>
            <span data-f="ago" class="mt-1 block text-xs text-dim"></span>
        </span>
    </a>
</template>
<template id="tpl-search-skeleton">
    <div class="flex gap-4 rounded-frame bg-panel p-3"><div class="ls-skel size-20 shrink-0 !rounded-xl"></div><div class="flex-1 space-y-2 pt-1"><div class="ls-skel h-3 w-1/4"></div><div class="ls-skel h-4"></div><div class="ls-skel h-4 w-2/3"></div></div></div>
</template>
