/*
 * Uji interaksi mockup: skeleton & muat lebih banyak, pencarian instan, tema, switcher template,
 * hero story Lensa, ukuran huruf & mode fokus Wacana, drawer mobile.
 * Jalankan: npm run audit:interactions   (BASE_URL bawaan https://raya.test)
 */
import { chromium } from 'playwright';
const base = process.env.BASE_URL || 'https://raya.test';
const b = await chromium.launch();
const ctx = await b.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1280, height: 860 } });
const p = await ctx.newPage();
const errs = []; p.on('pageerror', e => errs.push(e.message));
let failed = 0;
const ok = (name, cond) => { if (!cond) failed++; console.log(cond ? 'PASS' : 'FAIL', name); };
const count = (sel) => p.locator(sel).count();

// ---------- default
await p.goto(`${base}/default/index`, { waitUntil: 'networkidle' });
let before = await count('#index-list > article');
await p.click('[data-loadmore]');
await p.waitForTimeout(150);
ok('default: skeleton tampil saat memuat', (await count('#index-list [data-skeleton]')) > 0);
await p.waitForTimeout(1200);
ok(`default: muat lebih banyak (${before} -> ${await count('#index-list > article')})`, (await count('#index-list > article')) > before);
await p.fill('#rt-q', 'prabowo');
await p.waitForTimeout(1200);
ok('default: pencarian instan menampilkan hasil', (await count('#rt-suggest [data-results] a')) > 0);
await p.click('[data-theme-toggle]');
await p.waitForTimeout(500);
ok('default: toggle tema gelap', (await p.getAttribute('html', 'data-theme')) === 'dark');
await p.click('[data-theme-toggle]');
await p.click('[data-toggle="rt-switcher"]');
await p.waitForTimeout(300);
ok('default: panel switcher terbuka', (await p.getAttribute('#rt-switcher', 'data-open')) === 'true');
await p.click('#rt-switcher a[href^="/lensa"]');
await p.waitForLoadState('networkidle');
ok('switcher: pindah ke Lensa & tetap di halaman sama', p.url().endsWith('/lensa/index') && (await count('#ls-progress')) === 1);

// ---------- lensa
await p.goto(`${base}/lensa`, { waitUntil: 'networkidle' });
const active0 = await p.locator('[data-slide][data-active="true"]').getAttribute('aria-label');
await p.click('[data-go="1"]');
await p.waitForTimeout(400);
const active1 = await p.locator('[data-slide][data-active="true"]').getAttribute('aria-label');
ok(`lensa: hero story berpindah slide (${active0} -> ${active1})`, active0 !== active1);
await p.keyboard.press('/');
await p.waitForTimeout(300);
ok('lensa: shortcut "/" membuka pencarian', (await p.getAttribute('#ls-search', 'data-open')) === 'true');
await p.keyboard.type('karhutla');
await p.waitForTimeout(1200);
ok('lensa: hasil pencarian instan', (await count('#ls-search [data-results] a[data-card]')) > 0);
await p.keyboard.press('Escape');
await p.click('[data-panel="ls-channels"]');
await p.waitForTimeout(300);
ok('lensa: panel kanal terbuka', (await p.getAttribute('#ls-channels', 'data-open')) === 'true');
await p.goto(`${base}/lensa/index`, { waitUntil: 'networkidle' });
before = await count('#index-grid > article');
await p.click('[data-more]');
await p.waitForTimeout(1300);
ok(`lensa: muat lebih banyak (${before} -> ${await count('#index-grid > article')})`, (await count('#index-grid > article')) > before);

// ---------- wacana
await p.goto(`${base}/wacana/sepakbola/el-clasico-mourinho-panaskan-isu-wasit-jelang-berjumpa-barcelona`, { waitUntil: 'networkidle' });
await p.click('[data-scale="0.05"]');
await p.click('[data-scale="0.05"]');
ok('wacana: perbesar huruf (110%)', (await p.textContent('[data-scale-value]')).trim() === '110%');
await p.click('[data-scale="-0.05"]'); await p.click('[data-scale="-0.05"]');
await p.click('[data-focus-mode]');
ok('wacana: mode fokus', await p.evaluate(() => document.body.classList.contains('is-focus')));
await p.click('[data-focus-mode]');
await p.click('button[data-open-panel="wc-search"] >> nth=0');
await p.fill('#wc-q', 'motogp');
await p.waitForTimeout(1200);
ok('wacana: pencarian langsung', (await count('#wc-hits a')) > 0);
await p.goto(`${base}/wacana/index`, { waitUntil: 'networkidle' });
before = await count('#index-list > article');
await p.click('[data-more]');
await p.waitForTimeout(1300);
const kanalSet = await p.locator('#index-list > article').last().getAttribute('data-kanal');
ok(`wacana: muat lebih banyak (${before} -> ${await count('#index-list > article')}), warna kanal=${kanalSet}`, (await count('#index-list > article')) > before && kanalSet);

// mobile drawer default
await p.setViewportSize({ width: 390, height: 800 });
await p.goto(`${base}/default`, { waitUntil: 'networkidle' });
await p.click('[data-toggle="rt-drawer"] >> nth=0');
await p.waitForTimeout(400);
ok('default mobile: drawer kanal terbuka', (await p.getAttribute('#rt-drawer', 'data-open')) === 'true');

// ---------- hasil meeting 28/10/2026: banner 2 kolom, urutan kanal beranda, muat lebih banyak di Terkini
await p.setViewportSize({ width: 1280, height: 860 });
for (const [tpl, list] of [['default', '#home-latest'], ['lensa', '#ls-more'], ['wacana', '#wc-latest']]) {
    await p.goto(`${base}/${tpl}`, { waitUntil: 'networkidle' });
    ok(`${tpl}: banner 2 kolom`, (await count('section[aria-label="Banner"] a')) === 2);
    const trends = await p.locator('section:has(> h2:text-is("Sedang tren")) li a').allTextContents();
    ok(`${tpl}: kolom samping Sedang tren (${trends.length} topik, mis. ${(trends[0] || '').replace(/\s+/g, ' ').trim()})`, trends.length >= 5 && /Tren di .+/.test(trends[0] || ''));
    const order = await p.evaluate(() => [...document.querySelectorAll('h2 a[href*="/@"]')].map((a) => a.getAttribute('href').replace(/^\/[a-z]+/, '')).filter((h) => ['/@ekonomi', '/@politik', '/@hukum', '/@hankam'].includes(h)));
    ok(`${tpl}: urutan kanal ${order.join(' > ')}`, order.join(',') === '/@ekonomi,/@politik,/@hukum,/@hankam');
    const before = await count(`${list} > article`);
    await p.click(`[data-target="${list}"]`);
    await p.waitForTimeout(1300);
    ok(`${tpl}: Terkini beranda bisa muat lebih banyak (${before} -> ${await count(`${list} > article`)})`, (await count(`${list} > article`)) > before);
}

// ---------- Wacana: headline utama di kiri + 4 headline horizontal di kanan
await p.goto(`${base}/wacana`, { waitUntil: 'networkidle' });
const head = await p.evaluate(() => {
    const section = document.querySelector('section[aria-label="Headline"]');
    const lead = section.querySelector('article').getBoundingClientRect();
    const items = [...section.querySelectorAll('ol > li')];
    const kanal = new Set(items.map((li) => li.dataset.kanal));
    const first = items[0].getBoundingClientRect();
    const photo = items[0].querySelector('.wc-photo').getBoundingClientRect();
    return { side: items.length, kanal: kanal.size, leftOfSide: lead.right <= first.left, horizontal: photo.right <= items[0].querySelector('h3').getBoundingClientRect().left };
});
ok(`wacana: headline utama kiri + ${head.side} headline kanan (${head.kanal} kanal berbeda), kartu horizontal`, head.side === 4 && head.leftOfSide && head.horizontal && head.kanal >= 3);

// ---------- Dua tata letak artikel: judul atas (biasa) & cover dulu (Analisis)
for (const tpl of ['default', 'lensa', 'wacana']) {
    for (const [path, expect] of [
        ['/sepakbola/el-clasico-mourinho-panaskan-isu-wasit-jelang-berjumpa-barcelona', 'judul'],
        ['/analisis/atasi-kemiskinan-dengan-kesejahteraan-bukan-dengan-statistik', 'cover'],
        ['/analisis/atasi-kemiskinan-dengan-kesejahteraan-bukan-dengan-statistik?tata=judul', 'judul'],
    ]) {
        await p.goto(`${base}/${tpl}${path}`, { waitUntil: 'networkidle' });
        const got = await p.evaluate(() => {
            const h1 = document.querySelector('main h1').getBoundingClientRect().top + scrollY;
            const cover = document.querySelector('main img[fetchpriority="high"]').getBoundingClientRect().top + scrollY;
            return { layout: cover < h1 ? 'cover' : 'judul', author: document.body.innerText.includes('Ditulis oleh') };
        });
        ok(`${tpl}: ${path.split('/')[1]}${path.includes('tata') ? ' (?tata=judul)' : ''} -> ${got.layout}${got.author ? ' + kartu penulis' : ''}`, got.layout === expect && got.author === (expect === 'cover'));
    }
}

// ---------- Wacana artikel responsif: 3 kolom hanya >= 1280px, di bawahnya 1 kolom + bilah baca
for (const width of [390, 1100, 1366]) {
    await p.setViewportSize({ width, height: 860 });
    await p.goto(`${base}/wacana/analisis/peta-terbaru-2026-jakarta-tak-aman-dari-gempa`, { waitUntil: 'networkidle' });
    const r = await p.evaluate(() => {
        const visible = (el) => !!el && getComputedStyle(el).display !== 'none' && el.getBoundingClientRect().width > 0;
        const asides = [...document.querySelectorAll('article > div.grid > aside')];
        const body = document.querySelector('[data-article-body]').getBoundingClientRect();
        return {
            columns: asides.filter(visible).length + 1,
            bar: visible(document.querySelectorAll('[data-scale-value]')[1]?.closest('.xl\\:hidden')),
            bodyWidth: Math.round(body.width),
            latar: document.body.innerText.includes('Sebelum membaca'),
        };
    });
    const want = width >= 1280 ? 3 : 1;
    ok(`wacana artikel @${width}px: ${r.columns} kolom, isi ${r.bodyWidth}px, bilah baca ${r.bar ? 'tampil' : 'tersembunyi'}, tanpa "Sebelum membaca"`, r.columns === want && r.bar === (width < 1280) && !r.latar && r.bodyWidth >= Math.min(320, width - 40));
}
await p.setViewportSize({ width: 1280, height: 860 });

// ---------- Judul atas (upper) & multi-penulis
for (const tpl of ['default', 'lensa', 'wacana']) {
    await p.goto(`${base}/${tpl}/analisis/narco-dollar-yang-menyilaukan`, { waitUntil: 'networkidle' });
    const up = await p.evaluate(() => {
        const h1 = document.querySelector('main h1');
        const prev = h1.previousElementSibling;
        return { text: prev?.textContent.trim(), tag: prev?.tagName, h1s: document.querySelectorAll('h1').length };
    });
    ok(`${tpl}: upper "${up.text}" tepat di atas judul (bukan heading, ${up.h1s} h1)`, up.text === 'Narkoba, Bisnis Raksasa dari Zaman Purba (Bagian 4)' && up.tag === 'P' && up.h1s === 1);
    await p.goto(`${base}/${tpl}/nasional/dna-terjawab-jenazah-di-enggano-ternyata-surakman-abk-km-arupadhatu-1`, { waitUntil: 'networkidle' });
    const names = await p.evaluate(() => document.querySelector('main').innerText.includes('Fahmi dan Tondy Manangka'));
    ok(`${tpl}: multi-penulis "Fahmi dan Tondy Manangka"`, names);
    // Empat penulis (contoh demo): ringkas "A dan 3 lainnya", daftar lengkap bisa dibuka.
    await p.goto(`${base}/${tpl}/hiburan/5-film-tentang-dosa-manusia-terhadap-alam-gen-z-wajib-nonton`, { waitUntil: 'networkidle' });
    const toggle = p.locator('main [data-authors-toggle]').first();
    const label = (await toggle.textContent()).trim();
    const hiddenBefore = await p.locator('main [data-authors-list]').first().isHidden();
    await toggle.click();
    const list = (await p.locator('main [data-authors-list]').first().innerText()).trim();
    ok(`${tpl}: 4 penulis -> "Inge Mangkoe dan ${label}", dibuka: ${list}`, label === '3 lainnya' && hiddenBefore && list.includes('Shaina Neysa Hanifa, Fahmi, dan Tondy Manangka'));
}

// ---------- Beranda: semua berita dulu (Terkini → kanal), isi kolom samping menyusul; banner tetap di atas
for (const tpl of ['default', 'lensa', 'wacana']) {
    for (const width of [390, 1280]) {
        await p.setViewportSize({ width, height: 860 });
        await p.goto(`${base}/${tpl}`, { waitUntil: 'networkidle' });
        const r = await p.evaluate(() => {
            const top = (el) => el.getBoundingClientRect().top + scrollY;
            const heading = (text) => [...document.querySelectorAll('main h2')].find((h) => h.textContent.trim() === text);
            const names = ['Terkini', 'Ekonomi', 'Politik', 'Hukum', 'Hankam'];
            const tops = names.map((n) => top(heading(n)));
            const side = heading('Sedang tren');
            const banner = document.querySelector('section[aria-label="Banner"]');
            return {
                ordered: tops.every((t, i) => i === 0 || t > tops[i - 1]),
                bannerFirst: top(banner) < tops[0],
                sideTop: top(side), lastKanal: tops.at(-1), firstKanal: tops[1],
                sideRight: side.getBoundingClientRect().left > heading('Terkini').getBoundingClientRect().right,
            };
        });
        const layoutOk = width < 1024 ? r.sideTop > r.lastKanal : r.sideRight && r.sideTop < r.firstKanal;
        ok(`${tpl} @${width}px: banner → Terkini → Ekonomi → Politik → Hukum → Hankam, kolom samping ${width < 1024 ? 'setelah semua berita' : 'di kanan'}`, r.ordered && r.bannerFirst && layoutOk);
    }
}
await p.setViewportSize({ width: 1280, height: 860 });

// ---------- Navigasi: dropdown kanal (desktop) & sidenav dengan akordeon (ponsel)
const opener = { default: '[data-toggle="rt-drawer"]', lensa: '[data-sidenav-open]', wacana: '[data-sidenav-open]' };
const drawer = { default: '#rt-drawer', lensa: '#ls-sidenav', wacana: '#wc-sidenav' };
for (const tpl of ['default', 'lensa', 'wacana']) {
    await p.setViewportSize({ width: 1280, height: 860 });
    await p.goto(`${base}/${tpl}`, { waitUntil: 'networkidle' });
    const dd = p.locator('[data-dropdown]').first();
    await dd.locator('[data-dropdown-toggle]').click();
    const shown = await dd.locator('div[id]').first().evaluate((el) => getComputedStyle(el).visibility === 'visible');
    const subs = await dd.locator('div[id] a').count();
    await p.keyboard.press('Escape');
    ok(`${tpl}: dropdown kanal desktop (${subs} tautan) dibuka dengan ketuk, ditutup Esc`, shown && subs >= 4 && (await dd.getAttribute('data-open')) === 'false');

    await p.setViewportSize({ width: 390, height: 800 });
    await p.goto(`${base}/${tpl}`, { waitUntil: 'networkidle' });
    await p.evaluate(() => scrollTo(0, 1500));
    await p.locator(opener[tpl]).first().click();
    await p.waitForTimeout(400);
    const acc = p.locator(`${drawer[tpl]} [data-accordion]`).first();
    await acc.locator('[data-accordion-toggle]').click();
    await p.waitForTimeout(400);
    const childVisible = await acc.locator('a').nth(1).isVisible();
    await p.keyboard.press('Escape');
    await p.waitForTimeout(300);
    ok(`${tpl}: sidenav ponsel terbuka dari posisi gulir, sub-kanal akordeon tampil, Esc menutup`, childVisible && (await p.getAttribute(drawer[tpl], 'data-open')) === 'false');
}
await p.setViewportSize({ width: 1280, height: 860 });

// ---------- Lensa mode terang
await p.goto(`${base}/lensa`, { waitUntil: 'networkidle' });
await p.click('[data-theme-toggle]');
await p.waitForTimeout(500);
const light = await p.evaluate(() => ({
    root: document.documentElement.dataset.theme,
    body: getComputedStyle(document.body).backgroundColor,
    hero: getComputedStyle(document.querySelector('[data-stories]')).backgroundColor,
}));
ok(`lensa: mode terang (latar ${light.body}, hero tetap ${light.hero})`, light.root === 'light' && light.body === 'rgb(255, 255, 255)' && light.hero === 'rgb(17, 17, 17)');
await p.reload({ waitUntil: 'networkidle' });
ok('lensa: pilihan tema tersimpan setelah muat ulang', (await p.getAttribute('html', 'data-theme')) === 'light');
await p.click('[data-theme-toggle]');
console.log(errs.length ? 'JS errors: ' + errs.join(' | ') : 'JS errors: none');
await b.close();
process.exitCode = failed || errs.length ? 1 : 0;
