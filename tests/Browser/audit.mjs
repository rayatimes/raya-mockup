/*
 * Audit mockup portal: semua halaman x 3 template x 2 lebar layar.
 * Mengecek status HTTP, error JS/konsol, gambar rusak, <img> tanpa alt, dan overflow horizontal.
 * Jalankan: npm run audit   (BASE_URL bawaan https://raya.test)
 */
import { chromium } from 'playwright';
const base = process.env.BASE_URL || 'https://raya.test';
const paths = ['/', '/index', '/search?q=karhutla', '/search', '/tag', '/tag/karhutla', '/@berita', '/@olahraga', '/@tajuk', '/u/tejo-t',
  '/p/about-us', '/media', '/media/photo/infografis', '/podcast',
  '/sepakbola/el-clasico-mourinho-panaskan-isu-wasit-jelang-berjumpa-barcelona', '/tajuk/ketika-mimbar-kehilangan-adab',
  '/analisis/atasi-kemiskinan-dengan-kesejahteraan-bukan-dengan-statistik', '/analisis/peta-terbaru-2026-jakarta-tak-aman-dari-gempa?tata=judul',
  '/nusantara/114-korban-virgo-transport-8-ditemukan-129-masih-dicari', '/analisis/narco-dollar-yang-menyilaukan',
  '/nasional/dna-terjawab-jenazah-di-enggano-ternyata-surakman-abk-km-arupadhatu-1',
  '/hiburan/5-film-tentang-dosa-manusia-terhadap-alam-gen-z-wajib-nonton', '/hiburan/5-film-tentang-dosa-manusia-terhadap-alam-gen-z-wajib-nonton?tata=cover'];
const b = await chromium.launch();
const issues = [];
for (const tpl of ['default', 'lensa', 'wacana']) {
  for (const width of [1280, 390]) {
    const ctx = await b.newContext({ ignoreHTTPSErrors: true, viewport: { width, height: 860 } });
    const p = await ctx.newPage();
    let errs = [];
    p.on('pageerror', e => errs.push('JS: ' + e.message));
    p.on('console', m => { if (m.type() === 'error') errs.push('console: ' + m.text()); });
    p.on('requestfailed', r => errs.push('failed: ' + r.url()));
    p.on('response', r => { if (r.status() >= 400 && !r.url().endsWith('/tidak')) errs.push(`${r.status()}: ${r.url()}`); });
    for (const path of paths) {
      errs = [];
      const r = await p.goto(`${base}/${tpl}${path === '/' ? '' : path}`, { waitUntil: 'networkidle' });
      await p.evaluate(async () => { for (let y = 0; y < document.body.scrollHeight; y += 800) { scrollTo(0, y); await new Promise(r => setTimeout(r, 30)); } });
      await p.waitForTimeout(300);
      const info = await p.evaluate(() => {
        const over = document.documentElement.scrollWidth - innerWidth;
        const broken = [...document.images].filter(i => i.complete && i.naturalWidth === 0 && i.getAttribute('src')).map(i => i.src);
        const offenders = over > 0 ? [...document.querySelectorAll('body *')].filter(el => el.getBoundingClientRect().right > innerWidth + 1 && getComputedStyle(el).position !== 'fixed' && !el.closest('[data-track],[data-hero-track],.overflow-x-auto,.overflow-hidden')).slice(0, 3).map(el => el.tagName + '.' + el.className.toString().slice(0, 60)) : [];
        const noAlt = [...document.images].filter(i => !i.hasAttribute('alt')).length;
        return { over, broken, offenders, noAlt, title: document.title };
      });
      const tag = `[${tpl} ${width}] ${path}`;
      if (r.status() !== 200) issues.push(`${tag} status ${r.status()}`);
      if (info.over > 0) issues.push(`${tag} overflow ${info.over}px ${info.offenders.join(' | ')}`);
      if (info.broken.length) issues.push(`${tag} broken img ${info.broken.slice(0, 3).join(', ')}`);
      if (info.noAlt) issues.push(`${tag} ${info.noAlt} img without alt`);
      if (errs.length) issues.push(`${tag} ${[...new Set(errs)].slice(0, 4).join(' ; ')}`);
    }
    await ctx.close();
  }
}
console.log(issues.length ? issues.join('\n') : 'Tidak ada masalah.');
await b.close();
process.exitCode = issues.length ? 1 : 0;
