/*
 * Template Default — perilaku halaman (jQuery).
 * Setiap fitur dibungkus fungsi kecil dan diinisialisasi sekali di bawah.
 */
import $ from 'jquery';

const BASE = '/default';

const THEME_KEY = 'rt-default-theme';
const storage = {
    get(key) {
        try { return localStorage.getItem(key); } catch { return null; }
    },
    set(key, value) {
        try { localStorage.setItem(key, value); } catch { /* mode privat */ }
    },
};

/* Gambar: hilangkan skeleton setelah gambar siap (termasuk yang sudah ada di cache). */
function initMedia(root = document) {
    $(root).find('.rt-media > img').each(function () {
        const done = () => this.parentElement.classList.add('is-loaded');
        if (this.complete && this.naturalWidth) done();
        else $(this).one('load error', done);
    });
}

/* Tema terang/gelap. Nilai awal sudah dipasang skrip inline di <head> agar tidak berkedip. */
function initTheme() {
    $(document).on('click', '[data-theme-toggle]', () => {
        const next = document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark';
        const apply = () => {
            document.documentElement.dataset.theme = next;
            storage.set(THEME_KEY, next);
        };
        if (document.startViewTransition && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
            document.startViewTransition(apply);
        } else {
            apply();
        }
    });
}

/* Garis progres tipis di atas halaman saat berpindah halaman. */
function initNavProgress() {
    $(document).on('click', 'a[href^="/"]:not([target]):not([download])', function (event) {
        if (event.metaKey || event.ctrlKey || event.shiftKey || event.button !== 0) return;
        $('#rt-progress').removeClass('is-running');
        requestAnimationFrame(() => $('#rt-progress').addClass('is-running'));

        // Kartu → gambar hero artikel: beri nama transisi hanya pada kartu yang diklik.
        const img = $(this).closest('[data-card]').find('.rt-media > img').get(0);
        if (img) {
            $('[style*="rt-hero"]').css('view-transition-name', '');
            img.style.viewTransitionName = 'rt-hero';
        }
    });
    window.addEventListener('pageshow', () => $('#rt-progress').removeClass('is-running'));
}

/* Menu kanal mobile & panel pemilih template. */
function initDisclosure() {
    $(document).on('click', '[data-toggle]', function () {
        const target = $(`#${this.dataset.toggle}`);
        const open = target.attr('data-open') !== 'true';
        target.attr('data-open', String(open));
        $(`[data-toggle="${this.dataset.toggle}"]`).attr('aria-expanded', String(open));
        $('body').toggleClass('overflow-hidden', open && target.is('[data-lock]'));
    });
    $(document).on('keydown', (event) => {
        if (event.key !== 'Escape') return;
        $('[data-open="true"]').attr('data-open', 'false');
        $('[aria-expanded="true"]').attr('aria-expanded', 'false');
        $('body').removeClass('overflow-hidden');
    });
    $(document).on('click', (event) => {
        const switcher = $('#rt-switcher');
        if (switcher.attr('data-open') === 'true' && !$(event.target).closest('#rt-switcher, [data-toggle="rt-switcher"]').length) {
            switcher.attr('data-open', 'false');
        }
    });
}

/* Isi <template> dengan data artikel: [data-f] = teks, [data-href] = tautan, [data-src] = gambar. */
function renderItem(template, item) {
    const node = $(template.content.cloneNode(true));
    node.find('[data-f]').each(function () { $(this).text(item[this.dataset.f] ?? ''); });
    node.find('[data-href]').each(function () { this.href = item[this.dataset.href] ?? '#'; });
    node.find('[data-src]').each(function () {
        if (item[this.dataset.src]) this.src = item[this.dataset.src];
        else $(this).closest('[data-optional]').remove();
    });
    node.find('[data-alt]').each(function () { this.alt = item[this.dataset.alt] ?? ''; });
    return node;
}

function skeletonRows(template, count) {
    return $('<div data-skeleton class="contents"></div>').append(
        Array.from({ length: count }, () => $(template.content.cloneNode(true))),
    );
}

/* Tombol "Muat lebih banyak": skeleton dulu, lalu hasil dari endpoint JSON. */
function initLoadMore() {
    $(document).on('click', '[data-loadmore]', async function () {
        const button = $(this);
        const opts = this.dataset;
        const list = $(opts.target);
        const template = document.getElementById(opts.template);
        const skeleton = document.getElementById(opts.skeleton);
        const per = Number(opts.per || 10);
        button.prop('disabled', true).find('[data-label]').text('Memuat…');
        const placeholder = skeleton ? skeletonRows(skeleton, Math.min(per, 4)) : null;
        if (placeholder) list.append(placeholder);
        try {
            const params = new URLSearchParams({ scope: opts.scope, offset: opts.offset, per, exclude: opts.exclude || '' });
            const [response] = await Promise.all([
                fetch(`${BASE}/_mock/articles?${params}`).then((r) => r.json()),
                new Promise((resolve) => setTimeout(resolve, 450)), // biar skeleton sempat terlihat saat presentasi
            ]);
            placeholder?.remove();
            response.data.forEach((item) => {
                const node = renderItem(template, item);
                node.children().addClass('rt-reveal');
                list.append(node);
            });
            initMedia(list);
            this.dataset.offset = response.next_offset;
            if (!response.has_more) button.closest('[data-loadmore-wrap]').remove();
        } catch {
            placeholder?.remove();
        } finally {
            button.prop('disabled', false).find('[data-label]').text(opts.label || 'Muat lebih banyak');
        }
    });
}

/* Pencarian instan di header: hasil teratas sambil mengetik. */
function initInstantSearch() {
    let timer;
    let controller;
    $(document).on('input', '[data-instant-search]', function () {
        const input = this;
        const box = $(`#${input.dataset.instantSearch}`);
        const template = document.getElementById('tpl-suggest');
        const skeleton = document.getElementById('tpl-suggest-skeleton');
        const query = input.value.trim();
        clearTimeout(timer);
        controller?.abort();
        if (query.length < 3) {
            box.attr('data-open', 'false');
            return;
        }
        box.attr('data-open', 'true').find('[data-results]').empty().append(skeletonRows(skeleton, 3));
        box.find('[data-all]').attr('href', `${BASE}/search?q=${encodeURIComponent(query)}`);
        timer = setTimeout(async () => {
            controller = new AbortController();
            try {
                const params = new URLSearchParams({ scope: `search:${query}`, per: 5 });
                const response = await fetch(`${BASE}/_mock/articles?${params}`, { signal: controller.signal }).then((r) => r.json());
                const results = box.find('[data-results]').empty();
                if (!response.data.length) {
                    results.append(`<p class="px-4 py-6 text-sm text-muted">Tidak ada berita yang cocok dengan “${$('<i>').text(query).html()}”. Coba kata kunci lain.</p>`);
                }
                response.data.forEach((item) => results.append(renderItem(template, item)));
                box.find('[data-count]').text(response.total);
                initMedia(results);
            } catch { /* dibatalkan */ }
        }, 280);
    });
    $(document).on('click', (event) => {
        if (!$(event.target).closest('[data-search-root]').length) $('[data-suggest]').attr('data-open', 'false');
    });
}

/* Carousel berbasis scroll-snap dengan tombol geser. */
function initCarousels() {
    $('[data-carousel]').each(function () {
        const root = $(this);
        const track = root.find('[data-track]');
        const update = () => {
            const el = track.get(0);
            root.find('[data-prev]').prop('disabled', el.scrollLeft < 8);
            root.find('[data-next]').prop('disabled', el.scrollLeft + el.clientWidth > el.scrollWidth - 8);
        };
        root.on('click', '[data-prev], [data-next]', function () {
            const el = track.get(0);
            const dir = this.hasAttribute('data-next') ? 1 : -1;
            el.scrollBy({ left: dir * el.clientWidth * 0.85, behavior: 'smooth' });
        });
        track.on('scroll', () => requestAnimationFrame(update));
        update();
    });
}

/* Hero beranda bergeser otomatis di mobile (berhenti saat disentuh). */
function initHeroDots() {
    $('[data-hero-track]').each(function () {
        const track = this;
        const dots = $(track).parent().find('[data-dot]');
        track.addEventListener('scroll', () => {
            const index = Math.round(track.scrollLeft / track.clientWidth);
            dots.attr('aria-current', 'false').eq(index).attr('aria-current', 'true');
        }, { passive: true });
        dots.on('click', function () {
            track.scrollTo({ left: track.clientWidth * Number(this.dataset.dot), behavior: 'smooth' });
        });
    });
}

/* Artikel: progres baca, salin tautan, suka & simpan (lokal). */
function initArticle() {
    const bar = $('#rt-read-progress');
    const body = $('[data-article-body]');
    if (bar.length && body.length) {
        const update = () => {
            const rect = body.get(0).getBoundingClientRect();
            const total = rect.height - innerHeight * 0.6;
            const value = Math.min(1, Math.max(0, -rect.top / total));
            bar.css('transform', `scaleX(${value})`);
        };
        $(window).on('scroll resize', () => requestAnimationFrame(update));
        update();
    }
    $(document).on('click', '[data-copy-link]', async function () {
        try {
            await navigator.clipboard.writeText(location.href);
            $(this).find('i').attr('class', 'ri-check-line');
            setTimeout(() => $(this).find('i').attr('class', 'ri-link'), 1600);
        } catch { /* clipboard ditolak */ }
    });
    $(document).on('click', '[data-react]', function () {
        const pressed = this.getAttribute('aria-pressed') !== 'true';
        this.setAttribute('aria-pressed', String(pressed));
        const count = $(this).find('[data-count]');
        if (count.length) count.text(Number(count.text()) + (pressed ? 1 : -1));
        $(this).find('i').toggleClass(this.dataset.on, pressed).toggleClass(this.dataset.off, !pressed);
    });
}

/* Header ringkas saat menggulir. */
function initStickyHeader() {
    const header = $('[data-header]');
    const update = () => header.attr('data-scrolled', String(scrollY > 80));
    $(window).on('scroll', () => requestAnimationFrame(update));
    update();
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

/* Akordeon sub-kanal di sidenav. */
function initAccordions() {
    $(document).on('click', '[data-accordion-toggle]', function () {
        const item = $(this).closest('[data-accordion]');
        const open = item.attr('data-open') !== 'true';
        item.attr('data-open', String(open));
        this.setAttribute('aria-expanded', String(open));
    });
}

$(() => {
    initDropdowns();
    initAccordions();
    initAuthorsToggle();
    initMedia();
    initTheme();
    initNavProgress();
    initDisclosure();
    initLoadMore();
    initInstantSearch();
    initCarousels();
    initHeroDots();
    initArticle();
    initStickyHeader();
});
