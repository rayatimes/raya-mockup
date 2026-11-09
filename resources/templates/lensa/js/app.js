/*
 * Template Lensa — perilaku halaman (jQuery).
 * Hero bergaya story, rel geser, pencarian layar penuh, muat lebih banyak dengan skeleton.
 */
import $ from 'jquery';

const BASE = '/lensa';

const reduceMotion = matchMedia('(prefers-reduced-motion: reduce)').matches;

/* Bingkai foto: tandai siap saat gambar termuat (termasuk dari cache). */
function initFrames(root = document) {
    $(root).find('.ls-frame > img').each(function () {
        const done = () => this.parentElement.classList.add('is-loaded');
        if (this.complete && this.naturalWidth) done();
        else $(this).one('load error', done);
    });
}

/* Header transparan (selalu gelap) di atas hero; setelah menggulir menjadi padat dan ikut tema halaman. */
function initHeader() {
    const header = $('[data-header]');
    const overlay = header.is('[data-overlay]');
    const update = () => {
        const solid = scrollY > 40;
        header.attr('data-solid', String(solid));
        if (overlay) {
            if (solid) header.removeAttr('data-theme');
            else header.attr('data-theme', 'dark');
        }
    };
    $(window).on('scroll', () => requestAnimationFrame(update));
    update();
}

/* Tema gelap (bawaan) / terang, disimpan per pembaca. */
function initTheme() {
    $(document).on('click', '[data-theme-toggle]', () => {
        const next = document.documentElement.dataset.theme === 'light' ? 'dark' : 'light';
        const apply = () => {
            document.documentElement.dataset.theme = next;
            try { localStorage.setItem('rt-lensa-theme', next); } catch { /* mode privat */ }
        };
        if (document.startViewTransition && !reduceMotion) document.startViewTransition(apply);
        else apply();
    });
}

/* Hero story: autoplay dengan bilah progres; jeda saat hover atau tab tersembunyi. */
function initStories() {
    $('[data-stories]').each(function () {
        const root = $(this);
        const slides = root.find('[data-slide]');
        const bars = root.find('[data-bar]');
        const duration = Number(this.dataset.duration || 6500);
        this.style.setProperty('--slide-ms', `${duration}ms`);
        let index = 0;
        let timer;
        let started = 0;
        let remaining = duration;

        const show = (next) => {
            index = (next + slides.length) % slides.length;
            slides.attr('data-active', 'false').eq(index).attr('data-active', 'true');
            bars.each((i, bar) => {
                bar.dataset.state = i < index ? 'done' : 'idle';
                $(bar).find('span').css('animation', 'none').get(0).offsetWidth; // ulang animasi
                $(bar).find('span').css('animation', '');
            });
            bars.eq(index).attr('data-state', 'run');
            remaining = duration;
            schedule();
        };
        const schedule = () => {
            clearTimeout(timer);
            if (reduceMotion) return;
            started = Date.now();
            timer = setTimeout(() => show(index + 1), remaining);
        };
        const pause = () => {
            if (root.attr('data-paused') === 'true') return;
            root.attr('data-paused', 'true');
            clearTimeout(timer);
            remaining -= Date.now() - started;
        };
        const resume = () => {
            if (root.attr('data-paused') !== 'true') return;
            root.attr('data-paused', 'false');
            schedule();
        };

        root.on('mouseenter focusin', pause).on('mouseleave focusout', resume);
        root.on('click', '[data-go]', function (event) {
            event.preventDefault();
            show(index + Number(this.dataset.go));
        });
        bars.on('click', function () { show(bars.index(this)); });
        document.addEventListener('visibilitychange', () => (document.hidden ? pause() : resume()));

        // Geser di layar sentuh.
        let startX = null;
        root.on('touchstart', (e) => { startX = e.originalEvent.touches[0].clientX; });
        root.on('touchend', (e) => {
            if (startX === null) return;
            const delta = e.originalEvent.changedTouches[0].clientX - startX;
            if (Math.abs(delta) > 50) show(index + (delta < 0 ? 1 : -1));
            startX = null;
        });

        show(0);
    });
}

/* Rel horizontal: tombol geser + seret dengan mouse. */
function initRails() {
    $('[data-rail]').each(function () {
        const root = $(this);
        const track = root.find('[data-track]').get(0);
        const sync = () => {
            root.find('[data-prev]').prop('disabled', track.scrollLeft < 8);
            root.find('[data-next]').prop('disabled', track.scrollLeft + track.clientWidth > track.scrollWidth - 8);
        };
        root.on('click', '[data-prev], [data-next]', function () {
            track.scrollBy({ left: (this.hasAttribute('data-next') ? 1 : -1) * track.clientWidth * 0.8, behavior: 'smooth' });
        });
        let drag = null;
        $(track).on('pointerdown', (e) => {
            if (e.pointerType !== 'mouse') return;
            drag = { x: e.clientX, left: track.scrollLeft, moved: false };
        });
        $(window).on('pointermove', (e) => {
            if (!drag) return;
            const dx = e.clientX - drag.x;
            if (Math.abs(dx) > 4) {
                drag.moved = true;
                track.style.scrollSnapType = 'none';
                track.scrollLeft = drag.left - dx;
            }
        });
        $(window).on('pointerup', () => {
            if (drag?.moved) {
                $(track).one('click', (e) => e.preventDefault());
                track.style.scrollSnapType = '';
            }
            drag = null;
        });
        track.addEventListener('scroll', () => requestAnimationFrame(sync), { passive: true });
        sync();
    });
}

/* Klon <template> lalu isi dengan data artikel. */
function fill(template, item) {
    const node = $(template.content.cloneNode(true));
    node.find('[data-f]').each(function () { $(this).text(item[this.dataset.f] ?? ''); });
    node.find('[data-href]').each(function () { this.href = item[this.dataset.href] ?? '#'; });
    node.find('[data-src]').each(function () { this.src = item[this.dataset.src] ?? ''; this.alt = item.title; });
    return node;
}

function skeletons(id, count) {
    const template = document.getElementById(id);
    return $('<div class="contents" aria-hidden="true"></div>').append(Array.from({ length: count }, () => template.content.cloneNode(true)));
}

/* Muat lebih banyak. */
function initLoadMore() {
    $(document).on('click', '[data-more]', async function () {
        const opts = this.dataset;
        const list = $(opts.target);
        const per = Number(opts.per || 8);
        const button = $(this).prop('disabled', true);
        const holder = skeletons(`${opts.template}-skeleton`, Math.min(per, 4));
        list.append(holder);
        try {
            const params = new URLSearchParams({ scope: opts.scope, offset: opts.offset, per, exclude: opts.exclude || '' });
            const [res] = await Promise.all([
                fetch(`${BASE}/_mock/articles?${params}`).then((r) => r.json()),
                new Promise((resolve) => setTimeout(resolve, 500)),
            ]);
            holder.remove();
            res.data.forEach((item, i) => {
                const node = fill(document.getElementById(opts.template), item);
                node.children().addClass('ls-rise').css('animation-delay', `${i * 50}ms`);
                list.append(node);
            });
            initFrames(list);
            opts.offset = res.next_offset;
            if (!res.has_more) button.parent().remove();
        } catch {
            holder.remove();
        } finally {
            button.prop('disabled', false);
        }
    });
}

/* Pencarian layar penuh dengan hasil instan. */
function initSearch() {
    const overlay = $('#ls-search');
    const input = overlay.find('input');
    const results = overlay.find('[data-results]');
    let timer;
    let controller;

    const open = () => {
        overlay.attr('data-open', 'true');
        $('body').addClass('overflow-hidden');
        setTimeout(() => input.trigger('focus'), 60);
    };
    const close = () => {
        overlay.attr('data-open', 'false');
        $('body').removeClass('overflow-hidden');
    };

    $(document).on('click', '[data-search-open]', (e) => { e.preventDefault(); open(); });
    $(document).on('click', '[data-search-close]', close);
    $(document).on('keydown', (e) => {
        if (e.key === 'Escape' && overlay.attr('data-open') === 'true') close();
        if (e.key === '/' && !$(e.target).is('input, textarea')) { e.preventDefault(); open(); }
    });

    input.on('input', () => {
        const q = input.val().trim();
        clearTimeout(timer);
        controller?.abort();
        overlay.find('[data-hint]').toggle(q.length < 3);
        if (q.length < 3) { results.empty(); return; }
        results.empty().append(skeletons('tpl-search-skeleton', 4));
        timer = setTimeout(async () => {
            controller = new AbortController();
            try {
                const res = await fetch(`${BASE}/_mock/articles?${new URLSearchParams({ scope: `search:${q}`, per: 6 })}`, { signal: controller.signal }).then((r) => r.json());
                results.empty();
                if (!res.data.length) results.append($('<p class="col-span-full py-10 text-center text-dim"></p>').text(`Tidak ada berita tentang “${q}”.`));
                res.data.forEach((item) => results.append(fill(document.getElementById('tpl-search'), item)));
                if (res.total > res.data.length) {
                    results.append(`<a href="${BASE}/search?q=${encodeURIComponent(q)}" class="ls-btn col-span-full justify-self-center">Lihat ${res.total} hasil</a>`);
                }
                initFrames(results);
            } catch { /* dibatalkan */ }
        }, 260);
    });
}

/* Panel umum: menu kanal & pemilih template. */
function initPanels() {
    $(document).on('click', '[data-panel]', function () {
        const target = $(`#${this.dataset.panel}`);
        const next = target.attr('data-open') !== 'true';
        $('[data-open="true"]').not(target).not('#ls-search').attr('data-open', 'false');
        target.attr('data-open', String(next));
        $(this).attr('aria-expanded', String(next));
    });
    $(document).on('click', (e) => {
        if (!$(e.target).closest('[data-panel], [data-panel-body]').length) {
            $('[data-panel-body][data-open="true"]').attr('data-open', 'false');
        }
    });
}

/* Artikel: progres baca, salin tautan, reaksi. */
function initArticle() {
    const body = document.querySelector('[data-article-body]');
    const bar = $('#ls-progress');
    if (body && bar.length) {
        const update = () => {
            const rect = body.getBoundingClientRect();
            const p = Math.min(1, Math.max(0, -rect.top / (rect.height - innerHeight * 0.5)));
            bar.css('transform', `scaleX(${p})`);
        };
        $(window).on('scroll resize', () => requestAnimationFrame(update));
        update();
    }
    $(document).on('click', '[data-copy]', async function () {
        try {
            await navigator.clipboard.writeText(location.href);
            const label = $(this).find('[data-copy-label]');
            label.text('Tersalin');
            setTimeout(() => label.text('Salin tautan'), 1500);
        } catch { /* ditolak */ }
    });
    $(document).on('click', '[data-toggle-press]', function () {
        const on = this.getAttribute('aria-pressed') !== 'true';
        this.setAttribute('aria-pressed', String(on));
        const count = $(this).find('[data-count]');
        if (count.length) count.text(Number(count.text()) + (on ? 1 : -1));
    });
}

/* Galeri: lightbox sederhana untuk foto media. */
function initLightbox() {
    const box = $('#ls-lightbox');
    if (!box.length) return;
    $(document).on('click', '[data-zoom]', function (e) {
        e.preventDefault();
        box.find('img').attr('src', this.href);
        box.attr('data-open', 'true');
    });
    box.on('click', () => box.attr('data-open', 'false'));
}

/* Saat berpindah ke artikel, gambar kartu yang diklik "terbang" menjadi hero artikel. */
function initHeroTransition() {
    $(document).on('click', '[data-card] a[href^="/"]', function () {
        const img = $(this).closest('[data-card]').find('.ls-frame > img').get(0);
        if (!img) return;
        document.querySelectorAll('[style*="ls-hero"]').forEach((el) => { el.style.viewTransitionName = ''; });
        img.style.viewTransitionName = 'ls-hero';
    });
}

/* Multi-penulis: buka/tutup daftar lengkap "N lainnya". */
function initAuthorsToggle() {
    $(document).on('click', '[data-authors-toggle]', function () {
        const open = this.getAttribute('aria-expanded') !== 'true';
        this.setAttribute('aria-expanded', String(open));
        $(this).next('[data-authors-list]').prop('hidden', !open);
    });
}

/* Dropdown kanal (desktop): hover/fokus lewat CSS, ketuk panah untuk layar sentuh; Esc/klik luar menutup. */
function initDropdowns() {
    const closeAll = (except) => {
        $('[data-dropdown][data-open="true"]').not(except).attr('data-open', 'false')
            .find('[data-dropdown-toggle]').attr('aria-expanded', 'false');
    };
    $(document).on('click', '[data-dropdown-toggle]', function (event) {
        event.stopPropagation();
        const item = $(this).closest('[data-dropdown]');
        const open = item.attr('data-open') !== 'true';
        closeAll(item);
        item.attr('data-open', String(open));
        this.setAttribute('aria-expanded', String(open));
    });
    $(document).on('click', (event) => {
        if (!$(event.target).closest('[data-dropdown]').length) closeAll();
    });
    $(document).on('keydown', (event) => {
        if (event.key === 'Escape') closeAll();
    });
}

/* Sidenav ponsel + akordeon sub-kanal. */
function initSidenav() {
    const side = $('#ls-sidenav');
    const set = (open) => {
        side.attr('data-open', String(open));
        $('[data-sidenav-open]').attr('aria-expanded', String(open));
        $('body').toggleClass('overflow-hidden', open);
    };
    $(document).on('click', '[data-sidenav-open]', () => set(true));
    $(document).on('click', '[data-sidenav-close]', () => set(false));
    side.on('click', '[data-search-open]', () => set(false));
    $(document).on('keydown', (event) => {
        if (event.key === 'Escape' && side.attr('data-open') === 'true') set(false);
    });
    $(document).on('click', '[data-accordion-toggle]', function () {
        const item = $(this).closest('[data-accordion]');
        const open = item.attr('data-open') !== 'true';
        item.attr('data-open', String(open));
        this.setAttribute('aria-expanded', String(open));
    });
}

$(() => {
    initDropdowns();
    initSidenav();
    initAuthorsToggle();
    initFrames();
    initHeader();
    initTheme();
    initStories();
    initRails();
    initLoadMore();
    initSearch();
    initPanels();
    initArticle();
    initLightbox();
    initHeroTransition();
});
