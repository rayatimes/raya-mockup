<template id="tpl-entry">
    <article data-card data-kanal-from="category_url" class="group grid grid-cols-[1fr_7rem] gap-5 py-6 sm:grid-cols-[1fr_11rem]">
        <div>
            <a data-href="category_url" data-f="category" class="wc-kanal"></a>
            <h3 class="wc-head mt-2 text-[1.375rem] sm:text-[1.625rem]"><a data-href="url" data-f="title" class="wc-link"></a></h3>
            <p data-f="excerpt" class="mt-2 hidden text-[15px] leading-relaxed text-graphite line-clamp-2 sm:block"></p>
            <p class="wc-meta mt-3"><span data-f="author"></span>, <span data-f="ago"></span></p>
        </div>
        <a data-href="url" class="wc-photo aspect-[4/3]"><img data-src="image" alt="" loading="lazy"></a>
    </article>
</template>
<template id="tpl-entry-skeleton">
    <div class="grid grid-cols-[1fr_7rem] gap-5 py-6 sm:grid-cols-[1fr_11rem]" aria-hidden="true">
        <div class="space-y-3"><div class="wc-skel h-3 w-20"></div><div class="wc-skel h-6 w-11/12"></div><div class="wc-skel h-6 w-2/3"></div><div class="wc-skel h-3 w-32"></div></div>
        <div class="wc-skel aspect-[4/3]"></div>
    </div>
</template>
<template id="tpl-hit">
    <a data-href="url" data-kanal-from="category_url" class="group flex items-baseline gap-4 py-3">
        <span data-f="category" class="wc-kanal w-28 shrink-0"></span>
        <span data-f="title" class="wc-head wc-link text-lg"></span>
    </a>
</template>
<template id="tpl-hit-skeleton">
    <div class="flex gap-4 py-3" aria-hidden="true"><div class="wc-skel h-4 w-28"></div><div class="wc-skel h-5 flex-1"></div></div>
</template>
