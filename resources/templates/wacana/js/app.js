/*
 * Template Wacana — perilaku halaman (jQuery).
 * Fokus pada kenyamanan membaca: ukuran huruf, mode fokus, pencarian ringkas, muat lebih banyak.
 */
import $ from 'jquery';

const BASE = '/wacana';

const SCALE_KEY = 'rt-wacana-scale';
const store = {
    get(key) {
        try { return localStorage.getItem(key); } catch { return null; }
    },
    set(key, value) {
        try { localStorage.setItem(key, value); } catch { /* mode privat */ }
    },
};

/* Foto: tandai siap setelah termuat. */
function initPhotos(root = document) {
    $(root).find('.wc-photo > img').each(function () {
        const done = () => this.parentElement.classList.add('is-loaded');
        if (this.complete && this.naturalWidth) done();
        else $(this).one('load error', done);
    });
}

/* Bilah judul ringkas muncul saat menggulir melewati masthead. */
function initMasthead() {
    const bar = $('[data-compact]');
    if (!bar.length) return;
    const trigger = document.querySelector('[data-masthead]');
    new IntersectionObserver(([entry]) => bar.attr('data-show', String(!entry.isIntersecting))).observe(trigger);
}

/* Panel (menu kanal, pencarian, pemilih template). */
function initPanels() {
    $(document).on('click', '[data-open-panel]', function () {
        const id = this.dataset.openPanel;
        const panel = $(`#${id}`);
        const open = panel.attr('data-open') !== 'true';
        $('[data-panel][data-open="true"]').not(panel).attr('data-open', 'false');
        panel.attr('data-open', String(open));
        $(`[data-open-panel="${id}"]`).attr('aria-expanded', String(open));
        if (open) panel.find('input').first().trigger('focus');
    });
    $(document).on('keydown', (e) => {
        if (e.key === 'Escape') $('[data-panel]').attr('data-open', 'false');
    });
    $(document).on('click', (e) => {
        if (!$(e.target).closest('[data-panel], [data-open-panel]').length) {
            $('[data-panel][data-dismiss-outside][data-open="true"]').attr('data-open', 'false');
        }
    });
}

function fill(template, item) {
    const node = $(template.content.cloneNode(true));
    node.find('[data-f]').each(function () { $(this).text(item[this.dataset.f] ?? ''); });
    node.find('[data-href]').each(function () { this.href = item[this.dataset.href] ?? '#'; });
    node.find('[data-src]').each(function () { this.src = item[this.dataset.src] ?? ''; this.alt = item.title; });
    // Warna kanal diambil dari slug di URL kanal (/@slug).
    node.find('[data-kanal-from]').each(function () {
        this.dataset.kanal = (item[this.dataset.kanalFrom] || '').split('/@').pop();
    });
    return node;
}

function skeletons(id, count) {
    const template = document.getElementById(id);
    return $('<div class="contents" aria-hidden="true"></div>').append(Array.from({ length: count }, () => template.content.cloneNode(true)));
}

/* Muat lebih banyak dengan skeleton. */
function initLoadMore() {
    $(document).on('click', '[data-more]', async function () {
        const o = this.dataset;
        const list = $(o.target);
        const per = Number(o.per || 8);
        const button = $(this).prop('disabled', true);
        const holder = skeletons(`${o.template}-skeleton`, Math.min(per, 4));
        list.append(holder);
        try {
            const params = new URLSearchParams({ scope: o.scope, offset: o.offset, per, exclude: o.exclude || '' });
            const [res] = await Promise.all([
                fetch(`${BASE}/_mock/articles?${params}`).then((r) => r.json()),
                new Promise((resolve) => setTimeout(resolve, 450)),
            ]);
            holder.remove();
            res.data.forEach((item) => {
                const node = fill(document.getElementById(o.template), item);
                node.children().addClass('wc-enter');
                list.append(node);
            });
            initPhotos(list);
            o.offset = res.next_offset;
            if (!res.has_more) button.parent().remove();
        } catch {
            holder.remove();
        } finally {
            button.prop('disabled', false);
        }
    });
}

/* Pencarian ringkas di panel atas. */
function initSearch() {
    let timer;
    let controller;
    $(document).on('input', '[data-live-search]', function () {
        const q = this.value.trim();
        const out = $(this.dataset.liveSearch);
        clearTimeout(timer);
        controller?.abort();
        if (q.length < 3) { out.empty(); return; }
        out.empty().append(skeletons('tpl-hit-skeleton', 3));
        timer = setTimeout(async () => {
            controller = new AbortController();
            try {
                const res = await fetch(`${BASE}/_mock/articles?${new URLSearchParams({ scope: `search:${q}`, per: 5 })}`, { signal: controller.signal }).then((r) => r.json());
                out.empty();
                if (!res.data.length) out.append($('<p class="py-6 text-sm text-ash"></p>').text(`Belum ada tulisan tentang “${q}”.`));
                res.data.forEach((item) => out.append(fill(document.getElementById('tpl-hit'), item)));
                if (res.total > res.data.length) out.append(`<a href="${BASE}/search?q=${encodeURIComponent(q)}" class="mt-4 inline-flex text-sm font-semibold text-java underline underline-offset-4">Lihat ${res.total} hasil</a>`);
            } catch { /* dibatalkan */ }
        }, 260);
    });
}

/* Artikel: ukuran huruf, mode fokus, progres, salin tautan. */
function initReader() {
    const root = document.documentElement;
    const apply = (scale) => {
        root.style.setProperty('--reader-scale', scale);
        $('[data-scale-value]').text(`${Math.round(scale * 100)}%`);
    };
    apply(Number(store.get(SCALE_KEY)) || 1);
    $(document).on('click', '[data-scale]', function () {
        const current = Number(getComputedStyle(root).getPropertyValue('--reader-scale')) || 1;
        const next = Math.min(1.3, Math.max(0.85, current + Number(this.dataset.scale)));
        apply(next);
        store.set(SCALE_KEY, next);
    });
    $(document).on('click', '[data-focus-mode]', function () {
        const on = $('body').toggleClass('is-focus').hasClass('is-focus');
        $('[data-focus-mode]').attr('aria-pressed', String(on));
    });

    const body = document.querySelector('[data-article-body]');
    if (body) {
        const bar = $('[data-read-bar]');
        const update = () => {
            const r = body.getBoundingClientRect();
            bar.css('transform', `scaleX(${Math.min(1, Math.max(0, -r.top / (r.height - innerHeight * 0.5)))})`);
        };
        $(window).on('scroll resize', () => requestAnimationFrame(update));
        update();
    }

    $(document).on('click', '[data-copy]', async function () {
        try {
            await navigator.clipboard.writeText(location.href);
            $(this).find('span').text('Tautan tersalin');
        } catch { /* ditolak */ }
    });
    $(document).on('click', '[data-press]', function () {
        this.setAttribute('aria-pressed', String(this.getAttribute('aria-pressed') !== 'true'));
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

/* Dropdown kanal (desktop): hanya dibuka lewat tombol panah; Esc, klik di luar, atau fokus keluar menutup. */
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
    $(document).on('focusin', (event) => {
        closeAll($(event.target).closest('[data-dropdown]'));
    });
    $(document).on('keydown', (event) => {
        if (event.key !== 'Escape') return;
        const focusedItem = $(document.activeElement).closest('[data-dropdown][data-open="true"]');
        closeAll();
        focusedItem.find('[data-dropdown-toggle]').trigger('focus');
    });
}

/* Sidenav ponsel + akordeon sub-kanal. */
function initSidenav() {
    const side = $('#wc-sidenav');
    const set = (open) => {
        side.attr('data-open', String(open));
        $('[data-sidenav-open]').attr('aria-expanded', String(open));
        $('body').toggleClass('overflow-hidden', open);
    };
    $(document).on('click', '[data-sidenav-open]', () => set(true));
    $(document).on('click', '[data-sidenav-close]', () => set(false));
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
    initPhotos();
    initMasthead();
    initPanels();
    initLoadMore();
    initSearch();
    initReader();
});
