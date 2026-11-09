/*
 * Uji mockup aplikasi di /default-mobile, /lensa-mobile, /wacana-mobile:
 * ukuran layar, urutan header, tiga menu, Unggulan, susunan artikel, simpan, ukuran teks,
 * Untuk Anda dari riwayat baca, mode gelap, dan tidak adanya fitur sosial.
 * Jalankan: npm run audit:mobile   (BASE_URL bawaan https://raya.test)
 */
import { chromium } from 'playwright';

const base = process.env.BASE_URL || 'https://raya.test';
const article = '/nusantara/114-korban-virgo-transport-8-ditemukan-129-masih-dicari';
const hukumArticle = '/hukum/rutan-salemba-gagalkan-penyelundupan-65-cartridge-diduga-berisi-etomidate';
const browser = await chromium.launch();
let failed = 0;
const ok = (name, condition, detail = '') => {
    if (!condition) {
        failed += 1;
    }
    console.log(condition ? 'PASS' : 'FAIL', name, detail);
};

for (const template of ['default-mobile', 'lensa-mobile', 'wacana-mobile']) {
    const url = `${base}/${template}`;
    const context = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 1440, height: 920 }, reducedMotion: 'reduce' });
    const page = await context.newPage();
    const errors = [];
    page.on('pageerror', (e) => errors.push(e.message));
    page.on('console', (m) => { if (m.type() === 'error') { errors.push(m.text()); } });
    page.on('response', (r) => { if (r.status() >= 400) { errors.push(`${r.status()} ${r.url()}`); } });
    const top = '[data-stack] > .screen:last-child';

    await page.goto(url, { waitUntil: 'networkidle' });

    const size = await page.locator('#vp').boundingBox();
    ok(`${template}: layar iPhone 393 x 852`, size.width === 393 && size.height === 852, `${size.width}x${size.height}`);

    const header = await page.evaluate(() => {
        const bar = document.querySelector('[data-tab-screen="home"] .bar');
        const vp = document.querySelector('#vp').getBoundingClientRect();
        const logo = [...bar.querySelectorAll('.bar-logo')].find((el) => el.offsetParent).getBoundingClientRect();
        const left = bar.querySelector('[data-drawer-open]').getBoundingClientRect();
        const right = [...bar.querySelectorAll('.bar-end > *')];
        return {
            leftFirst: left.right <= logo.left,
            centered: Math.abs(logo.left + logo.width / 2 - (vp.left + vp.width / 2)) <= 1,
            right: right.map((el) => el.getAttribute('aria-label')),
            rightAfter: right.every((el) => el.getBoundingClientRect().left >= logo.right),
        };
    });
    ok(`${template}: header kiri kanal, tengah logo, kanan cari dan mode gelap`, header.leftFirst && header.centered && header.rightAfter && header.right.length === 2 && /Cari/.test(header.right[0]) && /gelap/.test(header.right[1]));

    const menus = await page.locator('[data-tab-screen="home"] [data-pg]').allTextContents();
    ok(`${template}: menu hanya Terbaru, Unggulan, Untuk Anda`, menus.join() === 'Terbaru,Unggulan,Untuk Anda', menus.join());

    const start = await page.evaluate(() => {
        const home = document.querySelector('[data-tab-screen="home"]');
        const pager = home.querySelector('[data-pager]');
        return {
            selected: home.querySelector('[data-pg][aria-selected="true"]').dataset.pg,
            onPage: Math.round(pager.scrollLeft) === home.querySelector('[data-page="unggulan"]').offsetLeft,
            visible: getComputedStyle(pager).visibility === 'visible',
        };
    });
    ok(`${template}: beranda terbuka di Unggulan`, start.selected === 'unggulan' && start.onPage && start.visible, JSON.stringify(start));

    const tabs = await page.locator('[data-tab-link]').allInnerTexts();
    ok(`${template}: menu bawah empat tujuan`, tabs.map((t) => t.replace(/\d/g, '').trim()).join() === 'Home,Tersimpan,Notifikasi,Profil');

    await page.click('[data-drawer-open]');
    await page.waitForTimeout(400);
    const drawer = await page.locator('[data-drawer]').innerText();
    ok(`${template}: panel berisi Kanal, Halaman, dan Insight`, /Kanal/.test(drawer) && /Halaman/i.test(drawer) && /Insight/i.test(drawer) && /Politik/.test(drawer) && /Tentang RayaTimes/.test(drawer));
    await page.click('[data-drawer] [data-overlay-close]');

    // Unggulan
    await page.click('[data-pg="unggulan"]');
    await page.waitForTimeout(600);
    const featured = await page.evaluate(() => [...document.querySelectorAll('[data-page="unggulan"] .featured')].map((section) => ({
        title: section.querySelector('.featured-title').textContent.trim(),
        lead: section.querySelectorAll('.lead').length,
        rows: section.querySelectorAll('.row').length,
        more: section.querySelector('.featured-more').getAttribute('href'),
    })));
    ok(`${template}: Unggulan lima rubrik, tiap rubrik 1 kartu + 5 daftar`, featured.map((f) => f.title).join() === 'Politik,Ekonomi,Hankam,Hukum,Analisis' && featured.every((f) => f.lead === 1 && f.rows === 5));
    // Geser tab dengan mouse dari atas kartu berita: pindah tab, bukan membuka berita atau seret bawaan.
    const leadBox = await page.locator('[data-page="unggulan"] a.lead').first().boundingBox();
    await page.mouse.move(leadBox.x + leadBox.width - 30, leadBox.y + 80);
    await page.mouse.down();
    await page.mouse.move(leadBox.x - 40, leadBox.y + 80, { steps: 8 });
    await page.mouse.up();
    await page.waitForTimeout(600);
    const swiped = await page.locator('[data-tab-screen="home"] [data-pg][aria-selected="true"]').getAttribute('data-pg');
    ok(`${template}: tab bisa digeser dari atas kartu berita`, swiped === 'untuk-anda' && (await page.locator('[data-stack] > .screen').count()) === 0, swiped);
    await page.click('[data-pg="unggulan"]');
    await page.waitForTimeout(600);

    await page.locator('.featured-more').first().scrollIntoViewIfNeeded();
    await page.locator('.featured-more').first().click();
    await page.waitForSelector(`${top} [data-feed]`);
    ok(`${template}: Selengkapnya membuka halaman kanal`, page.url().endsWith('/@politik'));
    await page.click(`${top} [data-back]`);
    await page.waitForTimeout(500);
    ok(`${template}: tombol kembali kembali ke beranda`, page.url() === url || page.url() === `${url}/`);

    // Setelah kembali, beranda harus utuh: tidak bergeser, tidak redup, dan tumpukan kosong.
    const chromeState = () => page.evaluate(() => ({
        under: document.querySelector('[data-tabroot]').dataset.under,
        moved: getComputedStyle(document.querySelector('[data-tabroot]')).transform !== 'none',
        stacked: document.querySelectorAll('[data-stack] > .screen').length,
    }));
    const isClean = (state) => state.under === 'false' && !state.moved && state.stacked === 0;
    let state = await chromeState();
    ok(`${template}: beranda utuh setelah kembali`, isClean(state), JSON.stringify(state));

    // Kembali dua kali dengan cepat dari dua layar bertumpuk.
    await page.locator('.featured-more').first().click();
    await page.waitForSelector(`${top} [data-feed] a.row-link`);
    await page.locator(`${top} [data-feed] a.row-link`).first().click();
    await page.waitForSelector(`${top} [data-article]`);
    await page.evaluate(() => {
        document.querySelector('[data-stack] > .screen:last-child [data-back]').click();
        document.querySelector('[data-stack] > .screen:nth-last-child(2) [data-back]').click();
    });
    await page.waitForTimeout(700);
    state = await chromeState();
    ok(`${template}: beranda utuh setelah kembali dua kali cepat`, isClean(state), JSON.stringify(state));

    // Kembali sebelum isi layar selesai dimuat.
    await page.route('**/*_screen=1*', async (route) => {
        await new Promise((resolve) => setTimeout(resolve, 500));
        await route.continue();
    });
    await page.locator('[data-page="unggulan"] a.lead').first().click();
    await page.waitForTimeout(80);
    await page.goBack();
    await page.waitForTimeout(900);
    await page.unroute('**/*_screen=1*');
    state = await chromeState();
    ok(`${template}: beranda utuh setelah kembali saat layar masih dimuat`, isClean(state), JSON.stringify(state));

    // Pindah tab saat ada layar terbuka.
    await page.locator('.stage-jumps a', { hasText: 'Halaman kanal' }).click();
    await page.waitForSelector(`${top} [data-feed]`);
    await page.locator('.stage-jumps a', { hasText: 'Tersimpan' }).click();
    await page.waitForTimeout(700);
    state = await chromeState();
    ok(`${template}: tab utuh setelah pindah dari layar bertumpuk`, isClean(state), JSON.stringify(state));
    await page.locator('.stage-jumps a', { hasText: 'Unggulan' }).click();
    await page.waitForTimeout(500);

    // Artikel
    await page.goto(url + article, { waitUntil: 'networkidle' });
    const order = await page.evaluate((selector) => {
        const screen = document.querySelector(selector);
        const y = (css) => screen.querySelector(css)?.getBoundingClientRect().top ?? -1;
        const bar = screen.querySelector('.bar');
        return {
            ys: [y('.article-cover img'), y('.article-title'), y('.attr'), y('[data-body]'), y('.tags'), y('.related')],
            caption: screen.querySelector('.article-cover figcaption')?.textContent.trim().length > 0,
            alsoInBody: screen.querySelector('[data-body] .also') !== null,
            right: [...bar.querySelectorAll('.bar-end > *')].map((el) => el.getAttribute('aria-label')),
            back: bar.querySelector('.bar-start').firstElementChild.getAttribute('aria-label'),
            logoLeft: (() => {
                const start = bar.querySelector('[data-back]').getBoundingClientRect();
                const logo = [...bar.querySelectorAll('.bar-logo')].find((el) => el.offsetParent).getBoundingClientRect();
                const end = bar.querySelector('.bar-end').getBoundingClientRect();
                const host = screen.getBoundingClientRect();
                return start.right <= logo.left + 1 && logo.left - start.right <= 8 && end.right >= host.right - 16 && logo.right < end.left;
            })(),
            text: screen.innerText,
            attr: screen.querySelector('.attr').innerText,
        };
    }, top);
    ok(`${template}: susunan foto, judul, atribut, konten, tags, terkait`, order.ys.every((value, i) => value >= 0 && (i === 0 || value > order.ys[i - 1])), order.ys.map(Math.round).join());
    ok(`${template}: caption foto dan Baca Juga di sela konten`, order.caption && order.alsoInBody);
    ok(`${template}: header artikel kiri kembali dan logo, kanan simpan, ukuran teks, bagikan`, order.back === 'Kembali' && order.logoLeft && order.right.join() === 'Simpan berita,Ukuran teks,Bagikan');
    ok(`${template}: tanggal atribut berformat tanggal bulan tahun | jam`, /\b\d{1,2} [A-Z][a-z]+ \d{4} \| \d{2}:\d{2}\b/.test(order.attr) && !/(Minggu|Senin|Selasa|Rabu|Kamis|Jumat|Sabtu),|WIB/.test(order.attr), order.attr.replace(/\s+/g, ' '));
    ok(`${template}: artikel tanpa suka, komentar, ikuti, pengikut`, !/\b(Suka|Komentar|Ikuti|Pengikut|pengikut)\b/.test(order.text));

    // Pil pembaca seperti di aplikasi: ukuran, posisi awal, geser, kurung, kuncup, suara.
    const pill = page.locator(`${top} [data-pill]`);
    const vpBox = await page.locator('#vp').boundingBox();
    const pillBox = async () => {
        await page.waitForTimeout(420);
        const box = await pill.boundingBox();
        return { left: box.x - vpBox.x, top: box.y - vpBox.y, right: vpBox.x + vpBox.width - box.x - box.width, bottom: vpBox.y + vpBox.height - box.y - box.height, width: box.width, height: box.height };
    };
    let box = await pillBox();
    ok(`${template}: pil 184 x 46 di kanan bawah`, box.width === 184 && box.height === 46 && box.right === 16 && box.bottom === 34 + 16, JSON.stringify(box));

    const grip = await page.locator(`${top} .pill-drag`).boundingBox();
    await page.mouse.move(grip.x + 8, grip.y + 8);
    await page.mouse.down();
    await page.mouse.move(grip.x - 90, grip.y - 200, { steps: 6 });
    await page.mouse.up();
    let moved = await pillBox();
    ok(`${template}: pil bisa digeser`, moved.left === box.left - 98 && moved.top === box.top - 208, JSON.stringify(moved));

    await page.mouse.move(vpBox.x + moved.left + 12, vpBox.y + moved.top + 23);
    await page.mouse.down();
    await page.mouse.move(vpBox.x - 300, vpBox.y - 300, { steps: 6 });
    await page.mouse.up();
    moved = await pillBox();
    ok(`${template}: pil terkurung di dalam layar`, moved.left === 16 && moved.top === 59 + 16, JSON.stringify(moved));

    await page.evaluate(() => { window.speechSynthesis.speak = () => {}; });
    const play = page.locator(`${top} [data-pill-play]`);
    const playBox = await play.boundingBox();
    await page.mouse.move(playBox.x + 15, playBox.y + 15);
    await page.mouse.down();
    await page.mouse.move(playBox.x + 80, playBox.y + 120, { steps: 6 });
    await page.mouse.up();
    ok(`${template}: menggeser dari tombol putar tidak memutar suara`, (await pill.getAttribute('data-playing')) === 'false');
    await page.waitForTimeout(420);
    await play.click();
    const label = await play.getAttribute('aria-label');
    ok(`${template}: tombol putar tetap bisa ditekan`, (await pill.getAttribute('data-playing')) === 'true' && label === 'Hentikan pembacaan');
    await play.click();

    await page.evaluate((selector) => { document.querySelector(selector).scrollTop = 900; }, `${top} [data-scroll]`);
    await page.waitForTimeout(100);
    ok(`${template}: pil menampilkan persentase baca`, /^[1-9]\d*%$/.test((await page.locator(`${top} [data-pill-percent]`).innerText()).trim()));
    await page.evaluate((selector) => { document.querySelector(selector).scrollTop = 0; }, `${top} [data-scroll]`);

    await page.locator(`${top} [data-pill-voice]`).click();
    await page.waitForTimeout(400);
    const voiceSheet = await page.locator('[data-sheet="voice"]').innerText();
    ok(`${template}: lembar Suara Pembaca terbuka`, /Suara Pembaca/.test(voiceSheet) && /Otomatis/.test(voiceSheet));
    await page.locator('[data-voice-option=""]').click();
    await page.waitForTimeout(400);

    // Kuncup menahan tepi terdekat: dipindah dulu ke separuh kanan.
    const parked = await pill.boundingBox();
    await page.mouse.move(parked.x + 12, parked.y + 23);
    await page.mouse.down();
    await page.mouse.move(vpBox.x + vpBox.width + 200, parked.y + 23, { steps: 6 });
    await page.mouse.up();
    await page.waitForTimeout(420);
    await page.locator(`${top} [data-pill-toggle]`).click();
    box = await pillBox();
    ok(`${template}: pil kuncup 92 dan tetap menempel tepi kanan`, box.width === 92 && box.right === 16 && !(await page.locator(`${top} [data-pill-voice]`).isVisible()), JSON.stringify(box));
    await page.locator(`${top} [data-pill-toggle]`).click();
    box = await pillBox();
    ok(`${template}: pil mekar lagi 184 dan tetap menempel tepi kanan`, box.width === 184 && box.right === 16, JSON.stringify(box));

    const beforeSize = await page.locator(`${top} [data-body]`).evaluate((el) => parseFloat(getComputedStyle(el).fontSize));
    await page.click(`${top} [data-sheet-open="text"]`);
    await page.waitForTimeout(400);
    await page.click('[data-text-option="besar"]');
    const afterSize = await page.locator(`${top} [data-body]`).evaluate((el) => parseFloat(getComputedStyle(el).fontSize));
    ok(`${template}: ukuran teks berubah`, afterSize > beforeSize, `${beforeSize} -> ${afterSize}`);
    await page.click('[data-text-option="normal"]');
    await page.click('[data-sheet="text"] [data-overlay-close]');
    await page.waitForTimeout(400);

    await page.click(`${top} .bar-end [data-sheet-open="share"]`);
    await page.waitForTimeout(400);
    ok(`${template}: lembar bagikan terbuka`, (await page.getAttribute('[data-sheet="share"]', 'data-open')) === 'true');
    await page.click('[data-scrim]', { position: { x: 200, y: 100 } });
    await page.waitForTimeout(400);

    await page.click(`${top} .bar-end [data-save]`);
    await page.goto(`${url}/tersimpan`, { waitUntil: 'networkidle' });
    await page.waitForSelector('[data-saved-list] article');
    ok(`${template}: berita tersimpan muncul di Tersimpan`, (await page.locator('[data-saved-list] article').count()) === 1);

    // Untuk Anda mengikuti kanal yang paling sering dibaca.
    await page.goto(url + hukumArticle, { waitUntil: 'networkidle' });
    await page.goto(url + hukumArticle, { waitUntil: 'networkidle' });
    await page.goto(url, { waitUntil: 'networkidle' });
    await page.click('[data-pg="untuk-anda"]');
    await page.waitForSelector('[data-for-you] section');
    const forYou = await page.evaluate(() => [...document.querySelectorAll('[data-for-you] .section-title')].map((el) => el.textContent.trim()));
    const badge = await page.locator('[data-for-you] section').first().locator('.section-badge').innerText();
    ok(`${template}: Untuk Anda dibuka kanal yang paling sering dibaca`, forYou[0] === 'Hukum' && /sering anda baca/i.test(badge) && forYou.length === 5, forYou.join());

    // Profil tanpa sistem sosial.
    await page.goto(`${url}/profil`, { waitUntil: 'networkidle' });
    const profile = await page.locator('[data-tab-screen="profile"]').innerText();
    ok(`${template}: profil tanpa pengikut, komentar, dan tata letak`, !/(Pengikut|Mengikuti|Yang Anda Ikuti|Komentar|Tata Letak)/i.test(profile) && /Riwayat Membaca/.test(profile) && /Minat Topik/.test(profile));
    await page.goto(`${url}/u/tejo-t`, { waitUntil: 'networkidle' });
    ok(`${template}: halaman penulis tanpa tombol ikuti`, !/(Ikuti|Pengikut|pengikut)/.test(await page.locator(top).innerText()));

    // Mode gelap
    await page.goto(url, { waitUntil: 'networkidle' });
    const themeBefore = await page.evaluate(() => getComputedStyle(document.querySelector('.scr')).backgroundColor);
    await page.click('[data-theme-toggle]');
    const themeAfter = await page.evaluate(() => getComputedStyle(document.querySelector('.scr')).backgroundColor);
    ok(`${template}: tombol mode gelap mengganti tema`, themeBefore !== themeAfter, `${themeBefore} -> ${themeAfter}`);
    await page.reload({ waitUntil: 'networkidle' });
    const themeSaved = await page.evaluate(() => getComputedStyle(document.querySelector('.scr')).backgroundColor);
    ok(`${template}: pilihan tema tersimpan`, themeSaved === themeAfter);
    await context.close();

    // Dibuka langsung dari ponsel: bingkai hilang, tanpa luapan mendatar.
    const phone = await browser.newContext({ ignoreHTTPSErrors: true, viewport: { width: 390, height: 780 }, hasTouch: true, isMobile: true });
    const phonePage = await phone.newPage();
    for (const path of ['', article, '/@politik', '/profil', '/notifikasi', '/search']) {
        await phonePage.goto(url + path, { waitUntil: 'networkidle' });
        const over = await phonePage.evaluate(() => document.documentElement.scrollWidth - innerWidth);
        ok(`${template} 390px ${path || '/'}: tanpa luapan`, over <= 0, `${over}px`);
    }
    await phone.close();

    ok(`${template}: tanpa error browser`, errors.length === 0, [...new Set(errors)].slice(0, 3).join(' | '));
}

await browser.close();
process.exitCode = failed ? 1 : 0;
