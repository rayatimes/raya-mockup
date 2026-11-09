/*
 * Mockup aplikasi RayaTimes — perilaku aplikasi di dalam bingkai iPhone (jQuery).
 * Tumpukan layar, tab geser, sheet, serta simpan, riwayat, dan minat di penyimpanan lokal.
 */
import $ from 'jquery';

const BASE = '/lensa-mobile';
const KEY = 'rt-lm';
const TABS = { home: BASE, saved: `${BASE}/tersimpan`, notifications: `${BASE}/notifikasi`, profile: `${BASE}/profil` };
const TEXT_SIZES = { kecil: ['Kecil', 0.9], normal: ['Normal', 1], besar: ['Besar', 1.15], 'sangat-besar': ['Sangat Besar', 1.3] };

const store = {
    get(key, fallback) {
        try {
            const value = localStorage.getItem(`${KEY}-${key}`);
            return value === null ? fallback : JSON.parse(value);
        } catch {
            return fallback;
        }
    },
    set(key, value) {
        try { localStorage.setItem(`${KEY}-${key}`, JSON.stringify(value)); } catch { /* mode privat */ }
    },
};

const tabForPath = (path) => {
    const clean = path.replace(/\/$/, '');
    return Object.keys(TABS).find((tab) => TABS[tab] === clean) ?? null;
};

/* ------------------------------------------------------------------ Tampilan */

let toastTimer;
function toast(message) {
    const box = $('[data-toast]');
    box.text(message).attr('data-open', 'true');
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => box.attr('data-open', 'false'), 2200);
}

/** Layar yang masih terbuka; layar yang sedang beranimasi menutup tidak dihitung lagi. */
const openScreens = () => $('[data-stack] > .screen').not('[data-gone]');

function topScreen() {
    const stacked = openScreens().last();
    return stacked.length ? stacked : $('[data-tab-screen][data-active="true"]');
}

/** Warna ikon status bar mengikuti layar teratas. */
function syncChrome() {
    const sb = topScreen().find('.scr').first().attr('data-sb') ?? 'auto';
    $('#vp').attr('data-sb', sb);
    const open = openScreens();
    open.each(function (index) { $(this).attr('data-under', String(index < open.length - 1)); });
    $('[data-tabroot]').attr('data-under', String(open.length > 0));
}

function setTheme(theme) {
    document.documentElement.dataset.theme = theme;
    store.set('theme', theme);
    $('[data-switch="theme"]').attr('aria-checked', String(theme === 'dark'));
}

function setTextSize(size) {
    const [label, scale] = TEXT_SIZES[size] ?? TEXT_SIZES.normal;
    $('#vp').css('--reader', scale);
    store.set('text', size);
    $('[data-text-value]').text(label);
    $('[data-text-option]').each(function () { $(this).attr('aria-checked', String(this.dataset.textOption === size)); });
}

/* ---------------------------------------------------------------- Tumpukan */

const screenHtml = '<div class="scr" data-sb="auto"><div class="m-loading" aria-label="Memuat"><span></span><span></span><span></span></div></div>';
const withScreen = (url) => `${url}${url.includes('?') ? '&' : '?'}_screen=1`;
let entryDepth = 0;

async function loadInto(element, url) {
    const response = await fetch(withScreen(url));
    const html = await response.text();
    // Pembaca bisa saja sudah kembali sebelum isi layar selesai dimuat.
    if (!element[0].isConnected || element.attr('data-gone')) {
        return;
    }
    element.html(html);
    hydrate(element);
    syncChrome();
}

function pushScreen(url, addHistory = true) {
    const screen = $(`<section class="screen" data-entering="true">${screenHtml}</section>`).attr('data-url', url);
    $('[data-stack]').append(screen);
    syncChrome();
    requestAnimationFrame(() => requestAnimationFrame(() => screen.attr('data-entering', 'false')));
    if (addHistory) {
        history.pushState({ depth: openScreens().length }, '', url);
    }
    return loadInto(screen, url);
}

function popScreens(count = 1) {
    stopSpeech();
    openScreens().toArray().slice(-count).forEach((node) => {
        const screen = $(node);
        screen.removeAttr('data-under').attr('data-leaving', 'true').attr('data-gone', 'true');
        setTimeout(() => screen.remove(), 340);
    });
    syncChrome();
    refreshTop();
}

function goBack() {
    const depth = openScreens().length;
    if (depth === 0) {
        return;
    }
    if (depth > entryDepth) {
        history.back();
        return;
    }
    // Layar yang dibuka langsung lewat alamatnya: tidak ada riwayat aplikasi untuk ditelusuri mundur.
    popScreens();
    entryDepth = depth - 1;
    const below = openScreens().last().attr('data-url') ?? TABS[activeTab()];
    history.replaceState({ depth: depth - 1 }, '', below);
}

const activeTab = () => $('[data-tab-screen][data-active="true"]').attr('data-tab-screen');

async function switchTab(tab, { reselect = true } = {}) {
    const current = activeTab();
    const panel = $(`[data-tab-screen="${tab}"]`);
    if (current === tab && reselect) {
        panel.find('[data-scroll]').each(function () { this.scrollTo({ top: 0, behavior: 'smooth' }); });
    }
    $('[data-tab-screen]').attr('data-active', 'false');
    panel.attr('data-active', 'true');
    $('[data-tab-link]').removeAttr('aria-current');
    $(`[data-tab-link="${tab}"]`).attr('aria-current', 'page');
    if (!panel.children().length) {
        panel.html(screenHtml);
        syncChrome();
        await loadInto(panel, TABS[tab]);
    }
    syncChrome();
    refreshTop();
}

function navigate(href) {
    const url = new URL(href, location.origin);
    const tab = tabForPath(url.pathname);
    if (tab) {
        const open = openScreens().length;
        if (open) {
            popScreens(open);
        }
        entryDepth = 0;
        history.pushState({ depth: 0 }, '', url.pathname);
        switchTab(tab);
        return;
    }
    pushScreen(url.pathname + url.search);
}

function initRouter() {
    entryDepth = $('[data-stack] > .screen').length;
    history.replaceState({ depth: entryDepth }, '', location.pathname + location.search);

    $(document).on('click', `a[href^="${BASE}/"], a[href="${BASE}"]`, function (event) {
        if (this.closest('.stage-switch') || event.metaKey || event.ctrlKey || event.shiftKey) {
            return;
        }
        event.preventDefault();
        closeOverlays();
        navigate(this.getAttribute('href'));
        const page = this.dataset.jumpPage;
        if (page) {
            setTimeout(() => goToPage($('[data-tab-screen="home"]'), page), 60);
        }
    });

    $(document).on('click', '[data-back]', goBack);

    window.addEventListener('popstate', (event) => {
        closeOverlays();
        const target = event.state?.depth ?? 0;
        const depth = openScreens().length;
        if (target < depth) {
            popScreens(depth - target);
        } else if (target > depth) {
            pushScreen(location.pathname + location.search, false);
        }
        const tab = tabForPath(location.pathname);
        if (tab) {
            switchTab(tab, { reselect: false });
        }
    });
}

/* --------------------------------------------------------------- Tab beranda */

function goToPage(root, page) {
    const pager = root.find('[data-pager]')[0];
    const target = root.find(`[data-page="${page}"]`)[0];
    if (pager && target) {
        pager.scrollTo({ left: target.offsetLeft, behavior: 'smooth' });
    }
}

function loadForYou(root) {
    const box = root.find('[data-for-you]');
    if (!box.length) {
        return;
    }
    const counts = store.get('counts', {});
    const activity = Object.keys(counts).sort((a, b) => counts[b] - counts[a]);
    const params = new URLSearchParams({ activity: activity.join(','), interests: store.get('interests', []).join(',') });
    const signature = params.toString();
    if (box.attr('data-signature') === signature) {
        return;
    }
    box.attr('data-signature', signature);
    fetch(`${BASE}/_for-you?${params}`).then((r) => r.text()).then((html) => {
        box.html(html);
        hydrate(box);
    });
}

function initPager(root) {
    root.find('[data-pager]').each(function () {
        const pager = this;
        const scope = $(pager).closest('.scr');
        const start = scope.find(`[data-page="${pager.dataset.start}"]`)[0];
        if (start) {
            pager.scrollLeft = start.offsetLeft;
        }
        pager.dataset.ready = 'true';
        const sync = () => {
            const index = Math.round(pager.scrollLeft / pager.clientWidth);
            const page = $(pager).children().eq(index).attr('data-page');
            scope.find('[data-pg]').each(function () {
                $(this).attr('aria-selected', String(this.dataset.pg === page));
            });
            if (page === 'untuk-anda') {
                loadForYou(scope);
            }
        };
        pager.addEventListener('scroll', sync, { passive: true });
        scope.find('[data-pg]').on('click', function () {
            goToPage(scope, this.dataset.pg);
        });
    });
}

/* ------------------------------------------------------------- Carousel / rel */

function initCarousel(root) {
    root.find('[data-carousel]').each(function () {
        const track = this;
        const box = $(track).closest('[data-carousel-root]');
        const dots = box.find('[data-dot]');
        const duration = Number(track.dataset.duration || 5000);
        let index = 0;
        let playing = true;

        const mark = () => {
            dots.each(function (i) { $(this).attr('data-active', String(i === index)); });
        };
        const go = (next) => {
            index = (next + dots.length) % dots.length;
            track.scrollTo({ left: track.clientWidth * index, behavior: 'smooth' });
            mark();
        };

        mark();
        dots.on('click', function (event) {
            event.preventDefault();
            playing = false;
            go(dots.index(this));
        });
        box.find('[data-carousel-next]').on('click', () => { playing = false; go(index + 1); });
        box.find('[data-carousel-prev]').on('click', () => { playing = false; go(index - 1); });
        track.addEventListener('pointerdown', () => { playing = false; });
        track.addEventListener('scroll', () => {
            const next = Math.round(track.scrollLeft / track.clientWidth);
            if (next !== index) {
                index = next;
                mark();
            }
        }, { passive: true });

        const timer = setInterval(() => {
            if (!track.isConnected) {
                clearInterval(timer);
                return;
            }
            const visible = !openScreens().length && activeTab() === 'home' && $('[data-tab-screen="home"] [data-pg="terbaru"]').attr('aria-selected') === 'true';
            if (playing && visible && !document.hidden && !matchMedia('(prefers-reduced-motion: reduce)').matches) {
                go(index + 1);
            }
        }, duration);
    });
}

/** Geser dengan mouse, supaya rel dan tab bisa dicoba di komputer seperti di layar sentuh. */
function initDragScroll() {
    let active = null;
    // Seret bawaan browser pada tautan dan foto akan memutus geseran.
    $(document).on('dragstart', '[data-drag] a, [data-drag] img', (event) => event.preventDefault());
    $(document).on('mousedown', '[data-drag]', function (event) {
        active = { el: this, x: event.pageX, left: this.scrollLeft, moved: false };
    });
    $(document).on('mousemove', (event) => {
        if (!active) {
            return;
        }
        const delta = event.pageX - active.x;
        if (Math.abs(delta) > 5) {
            active.moved = true;
            active.el.style.scrollSnapType = 'none';
            active.el.style.scrollBehavior = 'auto';
            active.el.scrollLeft = active.left - delta;
        }
    });
    $(document).on('mouseup', () => {
        if (!active) {
            return;
        }
        const { el, moved } = active;
        active = null;
        if (moved) {
            const width = el.dataset.drag === 'page' ? el.clientWidth : 0;
            if (width) {
                el.scrollTo({ left: Math.round(el.scrollLeft / width) * width, behavior: 'smooth' });
            }
            setTimeout(() => { el.style.scrollSnapType = ''; el.style.scrollBehavior = ''; }, 320);
            const stop = (e) => { e.preventDefault(); e.stopPropagation(); };
            el.addEventListener('click', stop, { capture: true, once: true });
            setTimeout(() => el.removeEventListener('click', stop, { capture: true }), 60);
        }
    });
}

/* ---------------------------------------------------- Gulir tanpa batas */

function initFeeds(root) {
    root.find('[data-feed]').each(function () {
        const feed = $(this);
        const sentinel = feed.nextAll('[data-sentinel]').first()[0];
        if (!sentinel) {
            return;
        }
        let busy = false;
        const observer = new IntersectionObserver((entries) => {
            if (!entries[0].isIntersecting || busy) {
                return;
            }
            busy = true;
            const params = new URLSearchParams({
                scope: feed.attr('data-scope'),
                offset: feed.attr('data-offset'),
                per: feed.attr('data-per') || 10,
                exclude: feed.attr('data-exclude') || '',
                bands: feed.attr('data-bands') || '0',
                last_band: feed.attr('data-last-band') || '',
            });
            fetch(`${BASE}/_rows?${params}`).then((r) => r.json()).then((res) => {
                feed.append(res.html);
                feed.attr('data-offset', res.next_offset).attr('data-last-band', res.last_band);
                busy = false;
                if (!res.has_more) {
                    observer.disconnect();
                    sentinel.remove();
                }
            });
        }, { root: feed.closest('[data-scroll]')[0], rootMargin: '400px' });
        observer.observe(sentinel);
    });
}

/* --------------------------------------------- Simpan, riwayat, dan minat */

const savedSlugs = () => store.get('saved', []);

function refreshStats() {
    $('[data-stat="read"]').text(store.get('history', []).length);
    $('[data-stat="saved"]').text(savedSlugs().length);
    $('[data-stat="interests"]').text(store.get('interests', []).length);
    $('[data-count="saved"]').text(savedSlugs().length ? `${savedSlugs().length} berita` : '');
}

function markSaved(root = $(document)) {
    const saved = savedSlugs();
    root.find('[data-save]').each(function () {
        const slug = this.dataset.save || $(this).closest('[data-article]').attr('data-slug');
        $(this).attr('aria-pressed', String(saved.includes(slug)));
    });
}

function toggleSaved(slug) {
    const saved = savedSlugs();
    const on = !saved.includes(slug);
    store.set('saved', on ? [slug, ...saved] : saved.filter((s) => s !== slug));
    toast(on ? 'Berita disimpan.' : 'Dihapus dari Tersimpan.');
    markSaved();
    refreshStats();
    renderSlugList($('[data-saved-list]'), savedSlugs());
}

function renderSlugList(list, slugs) {
    list.each(function () {
        const box = $(this);
        const empty = box.siblings('[data-list-empty]');
        const signature = slugs.join(',');
        empty.attr('hidden', slugs.length > 0 ? 'hidden' : null);
        if (box.attr('data-signature') === signature) {
            return;
        }
        box.attr('data-signature', signature);
        if (!slugs.length) {
            box.empty();
            return;
        }
        fetch(`${BASE}/_rows?${new URLSearchParams({ slugs: signature, per: 50 })}`).then((r) => r.json()).then((res) => {
            box.html(res.html);
            markSaved(box);
        });
    });
}

function recordRead(article) {
    const slug = article.attr('data-slug');
    const history_ = store.get('history', []).filter((s) => s !== slug);
    store.set('history', [slug, ...history_].slice(0, 50));
    const counts = store.get('counts', {});
    const channel = article.attr('data-cat');
    counts[channel] = (counts[channel] ?? 0) + 1;
    store.set('counts', counts);
    refreshStats();
}

/** Isi layar yang bergantung pada data di perangkat, dibuat ulang saat layar kembali terlihat. */
function refreshTop() {
    refreshStats();
    renderSlugList($('[data-saved-list]'), savedSlugs());
    renderSlugList($('[data-history-list]'), store.get('history', []));
    syncNotifications();
    const home = $('[data-tab-screen="home"]');
    const pager = home.find('[data-pager]')[0];
    if (pager && Math.round(pager.scrollLeft / pager.clientWidth) === 2) {
        loadForYou(home);
    }
}

/* ---------------------------------------------------- Pembaca suara (TTS) */

let speaking = null;
let speechRun = 0;

const canSpeak = () => 'speechSynthesis' in window;
const indonesianVoices = () => (canSpeak() ? speechSynthesis.getVoices().filter((voice) => /^(id|in)\b/i.test(voice.lang)) : []);

function setPlaying(pill, on) {
    pill.attr('data-playing', String(on));
    pill.find('[data-pill-play]').attr('aria-label', on ? 'Hentikan pembacaan' : 'Bacakan berita ini');
}

function stopSpeech() {
    speechRun += 1;
    if (speaking) {
        speechSynthesis.cancel();
        setPlaying(speaking, false);
        speaking = null;
    }
}

/** Dibacakan per potongan, karena browser memutus satu ucapan yang terlalu panjang. */
function speechChunks(text) {
    const chunks = [];
    (text.match(/[^.!?]+[.!?]*\s*/g) ?? [text]).forEach((sentence) => {
        const last = chunks.length - 1;
        if (last >= 0 && chunks[last].length + sentence.length <= 220) {
            chunks[last] += sentence;
        } else {
            chunks.push(sentence);
        }
    });
    return chunks;
}

function startSpeech(article, pill) {
    stopSpeech();
    const body = article.find('[data-body]').clone();
    body.find('[data-speech-skip]').remove();
    const chunks = speechChunks(`${article.attr('data-title')}. ${body.text()}`.replace(/\s+/g, ' '));
    const voices = indonesianVoices();
    const voice = voices.find((item) => item.name === store.get('voice', null)) ?? voices[0] ?? null;
    const run = speechRun;

    const speakFrom = (index) => {
        if (run !== speechRun) {
            return;
        }
        if (index >= chunks.length) {
            stopSpeech();
            return;
        }
        const utterance = new SpeechSynthesisUtterance(chunks[index]);
        utterance.lang = voice?.lang ?? 'id-ID';
        if (voice) {
            utterance.voice = voice;
        }
        utterance.onend = () => speakFrom(index + 1);
        utterance.onerror = () => {
            if (run === speechRun) {
                stopSpeech();
                toast('Suara pembaca belum tersedia di perangkat ini.');
            }
        };
        speechSynthesis.speak(utterance);
    };

    speaking = pill;
    setPlaying(pill, true);
    speakFrom(0);
}

function renderVoices() {
    const voices = indonesianVoices();
    const selected = store.get('voice', null) ?? '';
    const option = (value, label, note) => $('<button type="button" class="sheet-option" role="radio"></button>')
        .attr('data-voice-option', value)
        .attr('aria-checked', String(selected === value))
        .append($('<span></span>').append($('<b></b>').text(label), $('<small></small>').text(note)), '<i class="ri-check-line"></i>');
    const list = $('[data-voice-list]').empty();
    list.append(option('', 'Otomatis', voices[0] ? `Suara terbaik di perangkat ini, ${voices[0].name}` : 'Mengikuti setelan Text-to-Speech perangkat'));
    voices.forEach((voice) => list.append(option(voice.name, voice.name, voice.lang)));
}

function initVoice() {
    if (canSpeak()) {
        speechSynthesis.addEventListener('voiceschanged', renderVoices);
    }
    $(document).on('click', '[data-pill-voice]', () => {
        renderVoices();
        openSheet('voice');
    });
    $(document).on('click', '[data-voice-option]', function () {
        store.set('voice', this.dataset.voiceOption || null);
        closeOverlays();
        if (speaking) {
            startSpeech(speaking.closest('[data-article]'), speaking);
        }
    });
    window.addEventListener('resize', () => {
        document.querySelectorAll('[data-pill]').forEach((node) => node.reclamp?.());
    });
}

/* ------------------------------------------------------------ Pil pembaca */

const PILL = { edge: 16, height: 46, wide: 184, narrow: 92, slop: 6 };

/** Ruang aman atas dan bawah, dibaca setelah `env()` dihitung browser. */
function safeArea() {
    const probe = $('<span style="position:absolute;visibility:hidden;padding-top:var(--sat);padding-bottom:var(--sab)"></span>').appendTo('#vp');
    const style = getComputedStyle(probe[0]);
    const area = { top: parseFloat(style.paddingTop) || 0, bottom: parseFloat(style.paddingBottom) || 0 };
    probe.remove();
    return area;
}

/**
 * Pil mengikuti ReaderPill di aplikasi: bisa digeser ke mana pun, terkurung di dalam layar,
 * bisa dikuncupkan, dan kembali ke kanan bawah setiap kali artikel dibuka.
 * Menggeser dan menekan dibedakan oleh jarak, supaya tombol di dalamnya tetap bisa ditekan.
 */
function initPill(article) {
    const pill = article.find('[data-pill]');
    const node = pill[0];
    const host = article[0];
    let collapsed = false;
    let position = { x: 0, y: 0 };

    const between = (value, min, max) => Math.max(min, Math.min(value, Math.max(min, max)));
    const width = () => (collapsed ? PILL.narrow : PILL.wide);
    const limit = ({ x, y }, pillWidth = width()) => {
        const { top, bottom } = safeArea();
        return {
            x: between(x, PILL.edge, host.clientWidth - pillWidth - PILL.edge),
            y: between(y, top + PILL.edge, host.clientHeight - bottom - PILL.height - PILL.edge),
        };
    };
    const place = (next) => {
        position = next;
        node.style.transform = `translate(${next.x}px, ${next.y}px)`;
    };

    place(limit({ x: Infinity, y: Infinity }));
    pill.attr('data-placed', 'true');
    requestAnimationFrame(() => pill.attr('data-animate', 'true'));
    node.reclamp = () => place(limit(position));

    // Tanpa ini browser memulai seret bawaan pada tautan atau foto di bawah titik tekan, dan geserannya putus.
    node.addEventListener('mousedown', (event) => event.preventDefault());

    node.addEventListener('pointerdown', (event) => {
        if (event.button) {
            return;
        }
        const scale = host.getBoundingClientRect().width / host.clientWidth || 1;
        const start = { x: event.clientX, y: event.clientY, from: position };
        let moved = false;

        const move = (moveEvent) => {
            const dx = (moveEvent.clientX - start.x) / scale;
            const dy = (moveEvent.clientY - start.y) / scale;
            if (!moved && Math.abs(dx) <= PILL.slop && Math.abs(dy) <= PILL.slop) {
                return;
            }
            if (!moved) {
                // Kursor boleh keluar jendela browser tanpa kehilangan saat dilepas.
                node.setPointerCapture(moveEvent.pointerId);
            }
            moved = true;
            pill.attr('data-dragging', 'true');
            place({ x: start.from.x + dx, y: start.from.y + dy });
        };
        const end = () => {
            window.removeEventListener('pointermove', move);
            window.removeEventListener('pointerup', end);
            window.removeEventListener('pointercancel', end);
            if (!moved) {
                return;
            }
            pill.attr('data-dragging', 'false');
            place(limit(position));
            // Geseran yang berakhir di atas tombol tidak boleh terbaca sebagai ketukan.
            const stop = (clickEvent) => { clickEvent.stopPropagation(); clickEvent.preventDefault(); };
            node.addEventListener('click', stop, { capture: true, once: true });
            setTimeout(() => node.removeEventListener('click', stop, { capture: true }), 60);
        };

        window.addEventListener('pointermove', move);
        window.addEventListener('pointerup', end);
        window.addEventListener('pointercancel', end);
    });

    pill.find('[data-pill-toggle]').on('click', function () {
        const current = width();
        const next = collapsed ? PILL.wide : PILL.narrow;
        // Menahan tepi terdekat: pil di separuh kanan layar menyusut ke arah tepi kanannya.
        const shift = position.x + current / 2 > host.clientWidth / 2 ? current - next : 0;
        collapsed = !collapsed;
        pill.attr('data-collapsed', String(collapsed));
        this.setAttribute('aria-label', collapsed ? 'Perbesar pil' : 'Perkecil pil');
        this.setAttribute('aria-expanded', String(!collapsed));
        place(limit({ x: position.x + shift, y: position.y }));
    });

    pill.find('[data-pill-play]').on('click', () => {
        if (!canSpeak()) {
            toast('Perangkat ini belum mendukung pembacaan suara.');
            return;
        }
        if (speaking) {
            stopSpeech();
        } else {
            startSpeech(article, pill);
        }
    });
}

/* ------------------------------------------------------------------- Artikel */

function initArticle(root) {
    const article = root.find('[data-article]');
    if (!article.length) {
        return;
    }
    recordRead(article);
    markSaved(article);
    initPill(article);

    const scroller = article.find('[data-scroll]')[0];
    const update = () => {
        const progress = Math.min(1, (scroller.scrollTop + scroller.clientHeight) / Math.max(1, scroller.scrollHeight));
        const percent = Math.round(progress * 100);
        article.find('[data-pill-percent]').text(`${percent}%`);
        article.find('[data-pill-fill]').css('width', `${percent}%`);
        article.find('[data-progress-line]').css('--progress', progress);
    };
    scroller.addEventListener('scroll', update, { passive: true });
    update();
}

/** Judul di header halaman kanal baru muncul setelah judul besar di foto tergulir lewat. */
function initSolidHeader(root) {
    root.find('[data-solid-at]').each(function () {
        const screen = $(this);
        const scroller = screen.find('[data-scroll]')[0];
        const limit = () => Number(screen.attr('data-solid-at'));
        const update = () => {
            const solid = String(scroller.scrollTop > limit());
            if (screen.attr('data-solid') !== solid) {
                screen.attr('data-solid', solid).attr('data-sb', solid === 'true' ? 'auto' : 'light');
                syncChrome();
            }
        };
        scroller.addEventListener('scroll', update, { passive: true });
    });
}

/* ----------------------------------------------------------- Sheet & drawer */

function closeOverlays() {
    $('[data-sheet]').attr('data-open', 'false');
    $('[data-drawer]').attr('data-open', 'false');
    $('[data-scrim]').attr('data-open', 'false');
    syncChrome();
}

function openSheet(name) {
    $('[data-sheet]').attr('data-open', 'false');
    $(`[data-sheet="${name}"]`).attr('data-open', 'true');
    $('[data-scrim]').attr('data-open', 'true');
}

let shareTarget = { title: '', url: '' };
function setShareTarget(title, url) {
    shareTarget = { title, url };
    const sheet = $('[data-sheet="share"]');
    sheet.find('[data-share-title]').text(title);
    const text = encodeURIComponent(`${title} ${url}`);
    sheet.find('[data-share="whatsapp"]').attr('href', `https://wa.me/?text=${text}`);
    sheet.find('[data-share="telegram"]').attr('href', `https://t.me/share/url?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`);
    sheet.find('[data-share="x"]').attr('href', `https://x.com/intent/post?text=${text}`);
    sheet.find('[data-share="facebook"]').attr('href', `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`);
}

let confirmAction = null;
function confirmSheet({ title, message, ok, action }) {
    const sheet = $('[data-sheet="confirm"]');
    sheet.find('[data-confirm-title]').text(title);
    sheet.find('[data-confirm-message]').text(message);
    sheet.find('[data-confirm-ok]').text(ok);
    confirmAction = action;
    openSheet('confirm');
}

function initOverlays() {
    $(document).on('click', '[data-drawer-open]', () => {
        $('[data-drawer]').attr('data-open', 'true');
        $('[data-scrim]').attr('data-open', 'true');
        // Panel menutupi bilah atas, jadi ikon status bar mengikuti warna panel.
        $('#vp').attr('data-sb', 'auto');
    });
    $(document).on('click', '[data-scrim], [data-overlay-close]', closeOverlays);
    $(document).on('keydown', (event) => {
        if (event.key === 'Escape') {
            closeOverlays();
        }
    });
    $(document).on('click', '[data-accordion-toggle]', function () {
        const row = $(this).closest('[data-accordion]');
        const open = row.attr('data-open') !== 'true';
        $('[data-accordion]').attr('data-open', 'false');
        row.attr('data-open', String(open));
        $(this).attr('aria-expanded', String(open));
    });

    $(document).on('click', '[data-sheet-open]', function () {
        const name = this.dataset.sheetOpen;
        if (name === 'share') {
            const article = $(this).closest('[data-article]');
            setShareTarget(article.attr('data-title'), article.attr('data-url'));
        }
        openSheet(name);
    });

    $(document).on('click', '[data-more]', function (event) {
        event.preventDefault();
        const { slug, title, url, cat, catUrl, author, authorUrl } = this.dataset;
        const sheet = $('[data-sheet="more"]');
        sheet.find('[data-more-title]').text(title);
        sheet.find('[data-save]').attr('data-save', slug);
        sheet.find('[data-more-save-label]').text(savedSlugs().includes(slug) ? 'Hapus dari Tersimpan' : 'Simpan Berita');
        sheet.find('[data-more-cat]').attr('href', catUrl).find('span').text(`Buka kanal ${cat}`);
        sheet.find('[data-more-author]').attr('href', authorUrl).attr('hidden', authorUrl && authorUrl !== '#' ? null : 'hidden').find('span').text(`Lihat profil ${author}`);
        setShareTarget(title, url);
        markSaved(sheet);
        openSheet('more');
    });

    $(document).on('click', '[data-more-share]', () => openSheet('share'));

    $(document).on('click', '[data-save]', function () {
        const slug = this.dataset.save || $(this).closest('[data-article]').attr('data-slug');
        toggleSaved(slug);
        if ($(this).closest('[data-sheet="more"]').length) {
            closeOverlays();
        }
    });

    $(document).on('click', '[data-share-copy]', async () => {
        try {
            await navigator.clipboard.writeText(shareTarget.url);
            toast('Tautan disalin.');
        } catch {
            toast(shareTarget.url);
        }
        closeOverlays();
    });
    $(document).on('click', '[data-share-native]', async () => {
        if (navigator.share) {
            try { await navigator.share(shareTarget); } catch { /* dibatalkan pembaca */ }
        } else {
            toast('Lembar bagikan sistem tersedia di perangkat.');
        }
        closeOverlays();
    });
    $(document).on('click', '[data-share]', closeOverlays);

    $(document).on('click', '[data-text-option]', function () {
        setTextSize(this.dataset.textOption);
    });

    $(document).on('click', '[data-confirm-ok]', () => {
        closeOverlays();
        confirmAction?.();
        confirmAction = null;
    });
}

/* ------------------------------------------------------ Tema, saklar, profil */

function initControls() {
    $(document).on('click', '[data-theme-toggle]', () => {
        setTheme(document.documentElement.dataset.theme === 'dark' ? 'light' : 'dark');
    });
    $(document).on('click', '[data-switch]', function () {
        const name = this.dataset.switch;
        const next = this.getAttribute('aria-checked') !== 'true';
        if (name === 'theme') {
            setTheme(next ? 'dark' : 'light');
            return;
        }
        this.setAttribute('aria-checked', String(next));
        store.set(`switch-${name}`, next);
    });
    $(document).on('click', '[data-logout]', () => {
        confirmSheet({
            title: 'Keluar dari akun?',
            message: 'Tersimpan dan riwayat tetap ada di perangkat ini.',
            ok: 'Keluar',
            action: () => toast('Ini mockup, jadi akun tetap masuk.'),
        });
    });
    $(document).on('click', '[data-clear-history]', () => {
        confirmSheet({
            title: 'Hapus riwayat membaca?',
            message: 'Tab Untuk Anda akan kembali memakai minat Anda.',
            ok: 'Hapus',
            action: () => {
                store.set('history', []);
                store.set('counts', {});
                refreshTop();
                toast('Riwayat dihapus.');
            },
        });
    });
    $(document).on('submit', '[data-profile-form]', (event) => {
        event.preventDefault();
        toast('Profil diperbarui.');
        goBack();
    });
    $(document).on('click', '[data-interest]', function () {
        const slug = this.dataset.interest;
        const list = store.get('interests', []);
        const on = !list.includes(slug);
        store.set('interests', on ? [...list, slug] : list.filter((s) => s !== slug));
        this.setAttribute('aria-pressed', String(on));
        refreshStats();
    });
}

function restoreControls(root) {
    root.find('[data-switch]').each(function () {
        if (this.dataset.switch === 'theme') {
            this.setAttribute('aria-checked', String(document.documentElement.dataset.theme === 'dark'));
        } else {
            this.setAttribute('aria-checked', String(store.get(`switch-${this.dataset.switch}`, this.getAttribute('aria-checked') === 'true')));
        }
    });
    const interests = store.get('interests', []);
    root.find('[data-interest]').each(function () {
        this.setAttribute('aria-pressed', String(interests.includes(this.dataset.interest)));
    });
    $('[data-text-value]').text((TEXT_SIZES[store.get('text', 'normal')] ?? TEXT_SIZES.normal)[0]);
}

/* ---------------------------------------------------------------- Notifikasi */

function syncNotifications() {
    const read = store.get('notif-read', []);
    const cleared = store.get('notif-cleared', false);
    const ids = ($('[data-tabbar]').attr('data-notif-ids') || '').split(',').filter(Boolean);
    const unread = cleared ? 0 : ids.filter((id) => !read.includes(id)).length;
    $('[data-badge]').text(unread).attr('hidden', unread ? null : 'hidden');
    $('[data-notif]').each(function () {
        $(this).attr('data-read', String(read.includes(this.dataset.notif)));
    });
    $('[data-notif-list]').attr('hidden', cleared ? 'hidden' : null);
    $('[data-notif-empty]').attr('hidden', cleared ? null : 'hidden');
    $('[data-notif-filters], [data-notif-clear]').attr('hidden', cleared ? 'hidden' : null);
}

function initNotifications() {
    $(document).on('click', '[data-notif]', function () {
        store.set('notif-read', [...new Set([...store.get('notif-read', []), this.dataset.notif])]);
        syncNotifications();
    });
    $(document).on('click', '[data-nfilter]', function () {
        const type = this.dataset.nfilter;
        $('[data-nfilter]').attr('aria-pressed', 'false');
        $(this).attr('aria-pressed', 'true');
        $('[data-notif]').each(function () {
            $(this).attr('hidden', type && this.dataset.type !== type ? 'hidden' : null);
        });
    });
    $(document).on('click', '[data-notif-clear]', () => {
        confirmSheet({
            title: 'Hapus semua notifikasi?',
            message: 'Seluruh notifikasi akan dihapus dari daftar ini.',
            ok: 'Hapus',
            action: () => {
                store.set('notif-cleared', true);
                syncNotifications();
            },
        });
    });
}

/* ----------------------------------------------------------------- Pencarian */

function renderRecents(root) {
    const recents = store.get('recents', []);
    const box = root.find('[data-recents]');
    box.empty();
    recents.forEach((term) => {
        box.append($('<button type="button" class="m-chip" data-search-term></button>').text(term).attr('data-search-term', term));
    });
    root.find('[data-recents-wrap]').attr('hidden', recents.length ? null : 'hidden');
}

function initSearch() {
    let timer;
    let controller;
    const run = (root, query, remember = false) => {
        const results = root.find('[data-search-results]');
        const empty = root.find('[data-search-empty]');
        if (!query) {
            results.empty().attr('hidden', 'hidden');
            empty.attr('hidden', null);
            return;
        }
        controller?.abort();
        controller = new AbortController();
        fetch(`${BASE}/_search?q=${encodeURIComponent(query)}`, { signal: controller.signal }).then((r) => r.text()).then((html) => {
            results.html(html).attr('hidden', null);
            empty.attr('hidden', 'hidden');
            markSaved(results);
        }).catch(() => { /* permintaan dibatalkan */ });
        if (remember) {
            store.set('recents', [query, ...store.get('recents', []).filter((t) => t !== query)].slice(0, 6));
        }
    };

    $(document).on('input', '[data-search-input]', function () {
        const root = $(this).closest('.scr');
        const query = this.value.trim();
        root.find('[data-search-clear]').attr('hidden', query ? null : 'hidden');
        clearTimeout(timer);
        timer = setTimeout(() => run(root, query), 260);
    });
    $(document).on('submit', '[data-search-form]', function (event) {
        event.preventDefault();
        const root = $(this).closest('.scr');
        run(root, root.find('[data-search-input]').val().trim(), true);
    });
    $(document).on('click', '[data-search-term]', function () {
        const root = $(this).closest('.scr');
        const term = this.dataset.searchTerm;
        root.find('[data-search-input]').val(term);
        root.find('[data-search-clear]').attr('hidden', null);
        run(root, term, true);
    });
    $(document).on('click', '[data-search-clear]', function () {
        const root = $(this).closest('.scr');
        root.find('[data-search-input]').val('').trigger('focus');
        $(this).attr('hidden', 'hidden');
        run(root, '');
        renderRecents(root);
    });
    $(document).on('click', '[data-recents-clear]', function () {
        store.set('recents', []);
        renderRecents($(this).closest('.scr'));
    });
    $(document).on('click', '[data-rt]', function () {
        const box = $(this).closest('[data-rtabs-root]');
        box.find('[data-rt]').attr('aria-selected', 'false');
        $(this).attr('aria-selected', 'true');
        box.find('[data-rpanel]').attr('hidden', 'hidden');
        box.find(`[data-rpanel="${this.dataset.rt}"]`).attr('hidden', null);
    });
}

/* ---------------------------------------------------------------- Inisialisasi */

function hydrate(root) {
    initPager(root);
    initCarousel(root);
    initFeeds(root);
    initArticle(root);
    initSolidHeader(root);
    restoreControls(root);
    markSaved(root);
    if (root.find('[data-search-input]').length) {
        renderRecents(root);
        setTimeout(() => root.find('[data-search-input]').trigger('focus'), 360);
    }
    refreshTop();
}

function initStage() {
    $(document).on('click', '[data-fit]', function () {
        const device = document.querySelector('[data-device]');
        const on = this.getAttribute('aria-pressed') !== 'true';
        this.setAttribute('aria-pressed', String(on));
        device.style.zoom = on ? Math.min(1, (window.innerHeight - 32) / device.offsetHeight) : '';
        $(this).find('span').text(on ? 'Ukuran asli' : 'Muat di layar');
    });
}

$(() => {
    setTextSize(store.get('text', 'normal'));
    initRouter();
    initDragScroll();
    initOverlays();
    initControls();
    initNotifications();
    initSearch();
    initVoice();
    initStage();
    hydrate($('[data-tabroot]'));
    $('[data-stack] > .screen').each(function () { hydrate($(this)); });
    syncChrome();
});

