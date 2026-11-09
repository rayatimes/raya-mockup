# Catatan Mockup Redesign Portal RayaTimes

Tanggal: 28 Oktober 2026. Fokus: frontend (Blade + Vite: jQuery, Tailwind CSS v4, Remixicon). Tanpa database, tanpa perubahan pada `config/`, `database/`, atau `.env`. Satu-satunya perubahan di `app/` dan `bootstrap/` adalah middleware pencegah indeks mesin pencari.

## Cara menjalankan

```bash
npm install
npm run build        # atau: npm run dev
```

Setiap template punya path sendiri:

| Template | URL |
| --- | --- |
| Default | `https://rayatimes.cloud/default` |
| Lensa | `https://rayatimes.cloud/lensa` |
| Wacana | `https://rayatimes.cloud/wacana` |

`https://rayatimes.cloud` mengarah ke `/default`. URL lama tanpa prefix (mis. `/@ekonomi`) juga diarahkan ke `/default/...`. Semua tautan di dalam halaman tetap berada di path template yang sedang dibuka. Tombol **Template** di pojok bawah membuka halaman yang sama di template lain. Cookie `rt_template` dan `?template=` tidak dipakai lagi.

Path yang ditulis di bagian lain dokumen ini (mis. `/@slug`, `/tag`) relatif terhadap path template, misalnya `/lensa/@ekonomi`.

## Mockup aplikasi mobile

Tiga mockup aplikasi dalam bingkai iPhone 16 ukuran asli (393 × 852 pt, area atas 59, bilah tab 49 + 34), bersumber dari aplikasi React Native di `RayaTimes/mobile`:

| Template | URL | Arah |
| --- | --- | --- |
| Default | `https://rayatimes.cloud/default-mobile` | Mengikuti aplikasi yang berjalan (bilah atas gelap, tab bergaris bawah, baris berita, pita tanggal, carousel headline), dengan sentuhan situs Default: Newsreader + Inter, Raya Red, sudut tegas. |
| Lensa | `https://rayatimes.cloud/lensa-mobile` | Foto sebagai pembawa cerita: hero story, kartu judul di atas foto, rel Berita Terkait, panel kanal bergambar. Gelap (bawaan) dan terang. |
| Wacana | `https://rayatimes.cloud/wacana-mobile` | Majalah berita: serif besar, garis hitam, warna per kanal, drop cap, foto duotone Analisis. Terang, dan mode gelap baru khusus aplikasi karena tombolnya wajib ada di header. |

Perubahan dari aplikasi yang berjalan, sesuai brief:

| Bagian | Perubahan |
| --- | --- |
| Header | Kiri: toggle panel (Kanal, Halaman, Insight). Tengah: logo. Kanan: cari dan mode gelap. Tombol Tata Letak dihapus beserta mode Brief dan Social Feed. |
| Menu | Hanya Terbaru, Unggulan, Untuk Anda, dan beranda terbuka di Unggulan. Terpopuler, Diikuti, dan Jejak Isu tidak ada lagi di menu. |
| Unggulan (baru) | Politik, Ekonomi, Hankam, Hukum, Analisis: 1 kartu gambar penuh, 5 daftar, dan tombol Selengkapnya ke halaman kanal. |
| Untuk Anda | Kanal diurutkan dari riwayat baca di perangkat, lalu Minat Topik, lalu pilihan redaksi untuk pembaca baru. |
| Detail artikel | Header: kiri kembali dan logo, kanan simpan, ukuran teks, bagikan. Susunan: foto + caption, judul, atribut, konten + Baca Juga di sela isi, tags, Berita Terkait. Suka, komentar, dan bilah aksi bawah dihapus. Pil TTS mengikuti `ReaderPill` aplikasi: 184 × 46 (kuncup 92), bisa digeser dan terkurung di layar, tombol putar, persentase baca, pilih suara. Posisinya tidak disimpan. |
| Profil dan penulis | Pengikut, mengikuti, komentar, Yang Anda Ikuti, dan privasi jaringan dihapus. Profil berisi Bacaan Saya, Minat Topik, Tampilan, Pemberitahuan, Bantuan & Legal. Notifikasi hanya berita dan info akun. |

**Atribut** artikel berisi penulis, tanggal terbit, kanal, dan waktu baca. Tanggalnya berformat `27 September 2026 | 02:41`.

Tiap layar bisa dibuka langsung lewat alamatnya; `?_screen=1` mengembalikan potongan layar yang ditumpuk JS. Simpan, riwayat, minat, ukuran teks, tema, dan status notifikasi tersimpan di `localStorage`. Saat dibuka di ponsel (≤480px), bingkai hilang dan mockup mengisi layar. Uji dengan `npm run audit:mobile` dan `tests/Feature/MobileTemplateTest.php`.

## Acuan desain Lensa dan Wacana

Keduanya tidak meniru situs tertentu. Acuan utamanya pedoman brand sendiri.

| Template | Acuan | Sentuhan pengembangan |
| --- | --- | --- |
| Lensa | Layout 02 The Story Frame, eksplorasi Condensed Display, dan pola story yang umum di media sosial | Hero story otomatis dengan bilah progres, judul di atas foto dengan gradasi, mode terang beraksen merah. Aplikasi: header hitam, tanpa sudut membulat, panel kanal bergambar, rel Berita Terkait. |
| Wacana | Layout 03 The Editorial, palet sekunder brand, dan kebutuhan audiens di pedoman | Warna per kanal, drop cap, ukuran huruf, mode fokus, headline dari kanal berbeda. Aplikasi: mode gelap baru, bidang warna kanal. |

Dari `voltagent/awesome-design-md`, hanya analisis Al Jazeera yang dibaca sebelum template dibuat, sebagai pembanding umum. Analisis The Verge dan Wired baru disalin ke skill `news-design-references` setelah ketiga template selesai, jadi bukan dasar desainnya. `taste-skill` dan `frontend-design` dipakai sebagai panduan kualitas, bukan acuan tampilan.

## Tiga template

Masing-masing mengambil satu dari tiga layout di *Visual Identity RayaTimes* (bagian 04 Layout).

| Template | Layout brand | Untuk siapa | Ciri |
| --- | --- | --- | --- |
| **Default** (RayaTimes resmi) | 01 The News Frame | Semua pembaca, mengikuti struktur situs resmi | Tanpa sudut membulat, transisi halaman halus (View Transitions), skeleton pada gambar, muat-lebih-banyak, dan pencarian instan. Mode terang/gelap. Newsreader + Inter, Raya Red + aksen kuning. |
| **Lensa** | 02 The Story Frame | Audiens tersier, pembaca mobile | Foto sebagai pembawa cerita: hero bergaya *story* (autoplay + bilah progres), kartu judul di atas foto, rel geser, pencarian layar penuh (tombol `/`), tab bar bawah di mobile. Mode gelap (bawaan, aksen kuning) dan mode terang (aksen Raya Red). Archivo condensed (eksplorasi "Condensed Display" dari brand guide) + Newsreader untuk teks baca. |
| **Wacana** | 03 The Editorial | Audiens primer: profesional, pembuat kebijakan, akademisi | Bidang warna per kanal dari palet sekunder brand, Newsreader khusus judul artikel (selain itu Inter), ilustrasi latar per kanal, Tajuk berlatar utama RayaTimes Insight, pengatur ukuran huruf dan mode fokus di artikel. |

Halaman yang tersedia di ketiga template: beranda, detail artikel, kanal (`/@slug`), indeks (`/index`), pencarian (`/search`), topik (`/tag`, `/tag/slug`), profil penulis (`/u/username`), halaman statis (`/p/slug`), media (`/media`, `/media/photo/slug`), siniar (`/podcast`), dan 404.

## Dokumen identitas template

Dokumen untuk pembaca non-teknis: karakter, tipografi, warna, struktur dan alasan layout, gerak, dan kapan dipakai.

- [Identitas Template RayaTimes — Default](https://claude.ai/artifact/EnEviCgTXwQvESgBKsJyNN)
- [Identitas Template RayaTimes — Lensa](https://claude.ai/artifact/5BmvuVVnam42PDhCgi12Cn)
- [Identitas Template RayaTimes — Wacana](https://claude.ai/artifact/DVbentHFgHBveurjNn9ZVn)

## Hasil meeting 28 Oktober 2026

| Keputusan | Yang dikerjakan |
| --- | --- |
| Tren Topik dihilangkan, diganti banner 2 kolom | Default: seksi Topik Hits di beranda dan strip "Topik hits" di bawah bilah kanal dihapus. Lensa: rel Topik hits di beranda dihapus. Wacana: grafik "Isu paling banyak diberitakan" dihapus dari beranda. Di posisi Topik Hits kini ada banner 2 kolom (RayaTimes Insight dan promo aplikasi), yang menumpuk menjadi 1 kolom di ponsel. |
| Tren Topik dijadikan sidebar, seperti trending X (revisi lanjutan) | Widget **Sedang tren** di kolom samping ketiga template: baris "1 · Tren di {kanal}", nama topik (satu kata jadi tagar, mis. `#Karhutla`; beberapa kata ditulis apa adanya, mis. `Manchester United`), lalu "{n} berita"; tautan "Tampilkan lebih banyak" ke `/tag`. Kanal = kanal tempat topik itu paling sering muncul di 160 berita terbaru. Tampil di beranda (samping Terkini) dan kolom samping halaman lain: Default (artikel, kanal, topik, pencarian, indeks), Lensa (kanal, topik, indeks), Wacana (kanal, topik, pencarian, indeks; menggantikan grafik batang tren). Wacana memberi penanda warna kanal; Lensa mengikuti tema gelap/terang. |
| Wacana: headline utama besar di kiri, 4 lainnya di kanan dengan kartu horizontal (revisi 29 Okt) | Blok headline beranda Wacana: berita utama (foto 16:10, judul besar, ringkasan, penulis) di 7 kolom kiri; 4 headline lain di 5 kolom kanan sebagai kartu mendatar (foto 4:3 di kiri, kanal + judul + penulis di kanan). Empat headline samping diambil dari kanal induk yang berbeda (`headlines(5, diverse: true)`) agar tidak didominasi satu kanal. (`background()` di `Repository.php`, yang dulu menyuplai kotak konteks, masih tersedia tetapi tidak dipakai lagi.) |
| Wacana: label "Headline lainnya" tidak perlu | Label dihapus; kartu headline pertama di kanan kini sejajar dengan foto utama. |
| Wacana: "Konteks berita ini" dihapus | Pita beige "Konteks berita ini" di beranda dihapus; setelah headline langsung banner 2 kolom. Kotak "Sebelum membaca: latar peristiwa" di halaman artikel kemudian juga dihapus (lihat baris berikut). |
| Wacana artikel: "Sebelum membaca" dihapus; kolom kiri-kanan kurang responsif di mobile | Kotak latar peristiwa dihapus. Tiga kolom (kiri: waktu baca, kata kunci, kontrol baca; kanan: Baca juga) kini hanya di layar ≥1280px. Di bawah itu satu kolom (lebar baca maks. 44rem): kontrol baca jadi bilah ringkas di atas isi, dua "Baca juga" tampil di sela isi; kata kunci tidak diulang di layar kecil karena sudah terwakili Topik. Partial baru: `partials/article/reader-controls.blade.php`. |
| Wacana artikel: Kata kunci duplikat dengan Topik | "Kata kunci" dihapus dari artikel Wacana (kolom kiri & akhir artikel); topik cukup ditampilkan sekali di akhir artikel. |
| Navigasi: sidenav di ponsel, dropdown untuk menu bersub-menu (semua template) | **Desktop**: kanal yang punya sub-kanal (Berita, Olahraga, Gaya Hidup) mendapat dropdown, terbuka saat hover, fokus keyboard, atau ketuk tombol panah (untuk tablet sentuh); Esc / klik di luar menutup. **Ponsel & tablet (<1024px)**: bilah kanal geser diganti **sidenav** dari kiri; sub-kanal tampil sebagai dropdown akordeon (terbuka otomatis bila kanal itu aktif). Default: header kini menempel di atas di ponsel, sidenav berisi cari + Masuk/Daftar + ganti tema. Lensa: tombol menu baru di kiri logo; tombol panel kanal bergambar hanya di desktop. Wacana: bilah kanal di ponsel berisi tombol "Kanal" + cari; jarak menu menyesuaikan lebar (logo kecil & cari di bilah hanya ≥1280px) agar tidak meluber. |
| Beranda: semua berita dulu, baru isi kolom samping (semua template) | Terkini dan Ekonomi → Politik → Hukum → Hankam kini berada dalam **satu kolom utama**; isi kolom samping (bukan iklan) menjadi kolom kanan yang menempel (sticky) sepanjang kolom utama di desktop, dan tampil **setelah Hankam** di ponsel. Banner 2 kolom (iklan) tetap di atas Terkini. Default: kolom samping = tulisan pilihan, Sedang tren, Terpopuler, ringkasan pagi; rel kanal menjadi 3 kartu per layar. Lensa: kolom samping = Detik ini, Sedang tren, Paling banyak dibaca (sebelumnya di bawah beranda); komponen `rail` mendapat opsi `inset=false` untuk dipakai di dalam kolom. Wacana: kolom samping = Sedang tren, Paling banyak dibaca; blok kanal menjadi berita utama (foto kiri, judul kanan) + 4 judul dalam 2 kolom. Tajuk dan Infografis tetap setelah blok ini. |
| Contoh artikel ber-upper title & multi-penulis, di semua template | Data dummy ditambah 32 artikel ber-`upper` dan 4 artikel multi-penulis (tabel `article_author` di backup); total kini 451 artikel. **Upper title** (kicker) tampil tepat di atas judul sebagai `<p>`, bukan heading (satu h1 per halaman, sama dengan situs lama), di kedua tata letak artikel: Default merah tebal Newsreader, Lensa Archivo condensed warna aksen, Wacana serif miring Java Red. **Multi-penulis**: komponen `author-names` per template ("A dan B"; tiga atau lebih "A dan N lainnya" dengan tombol untuk membuka daftar lengkap, mengikuti `AuthorNames` situs lama; tiap nama bertaut ke profil) + avatar bertumpuk maks. 3 + penanda "+N"; tata letak cover dulu meringkas kartu penulis (tanpa profil panjang) bila penulisnya 3 atau lebih; tata letak cover dulu memberi kartu profil per penulis; kartu/daftar memakai versi ringkas "A dan 1 lainnya" (`authorLine()` di `Repository.php`, juga dipakai endpoint muat-lebih-banyak). Contoh: upper `/nusantara/114-korban-virgo-transport-8-ditemukan-129-masih-dicari`, upper + Analisis `/analisis/narco-dollar-yang-menyilaukan`, multi-penulis `/nasional/dna-terjawab-jenazah-di-enggano-ternyata-surakman-abk-km-arupadhatu-1`, empat penulis `/hiburan/5-film-tentang-dosa-manusia-terhadap-alam-gen-z-wajib-nonton`. **Catatan:** backup hanya berisi artikel dua penulis; artikel "5 Film tentang Dosa Manusia…" sengaja diberi empat penulis (Inge Mangkoe, Shaina Neysa Hanifa, Fahmi, Tondy Manangka) sebagai **contoh demo**, ditandai `DEMO_AUTHORS` di skrip pembangun data. |
| Contoh artikel "judul atas" dan artikel Analisis "cover dulu" di semua template | Tata letak dipilih otomatis oleh `articleLayout()` di `Repository.php`: kanal **Analisis** → cover dulu, kanal lain → judul atas. Paksa untuk presentasi dengan `?tata=judul` atau `?tata=cover`. Kepala artikel dipecah menjadi partial per template: `partials/article/head-title.blade.php` dan `partials/article/head-cover.blade.php` (Default juga mendapat komponen `share`, Lensa partial `byline`). Cover dulu = cover lebar/penuh → label kanal → judul → ringkasan → kartu "Ditulis oleh" (foto, jabatan, profil/pengikut) → atribut (terbit, waktu baca, kanal, topik). Contoh: berita `/sepakbola/el-clasico-mourinho-panaskan-isu-wasit-jelang-berjumpa-barcelona`, Analisis `/analisis/atasi-kemiskinan-dengan-kesejahteraan-bukan-dengan-statistik`. |
| Urutan beranda: Terkini (muat lebih banyak) → Ekonomi → Politik → Hukum → Hankam, tiap kanal menuju halaman kanal | Ketiga template mengikuti urutan ini tepat setelah berita utama dan banner. Judul tiap kanal dan tautan "Lihat semua / Semua / Selengkapnya" mengarah ke `/@ekonomi`, `/@politik`, `/@hukum`, `/@hankam`. Bagian lain (Tajuk, Analisis, Infografis) dipindah ke bawah urutan tersebut. |
| Lensa selain gelap, apakah bisa? | **Bisa, sudah dibuat.** Tombol matahari/bulan di header Lensa beralih ke mode terang (latar putih, aksen Raya Red karena kuning di atas putih kurang terbaca). Hero, kartu judul-di-atas-foto, dan pita Tajuk sengaja tetap gelap agar suasana sinematiknya terjaga. Pilihan disimpan per pembaca; bawaan tetap gelap. |

Data yang mendukung keputusan ini ada di `resources/data/rayatimes/site.php`: `home_banners` (dua banner) dan `home_sections` (urutan kanal). Urutan kanal cukup diubah di sana.

## Revisi Wacana 9 November 2026

| Permintaan | Yang dikerjakan |
| --- | --- |
| Newsreader hanya untuk judul artikel | Isi artikel, ringkasan, judul seksi, judul kanal, nama penulis, footer, dan bagian template lain memakai Inter (kelas `wc-ui-display`, `wc-ui-head`). Judul artikel tetap `wc-display` dan `wc-head`. Upper title diperlakukan sebagai bagian judul sehingga tetap Newsreader. |
| Tagline header | Inter tebal, 18px di ponsel dan 20px di desktop. |
| Ukuran teks bilah atas, menu, dan sub-menu | Satu kelas `wc-nav-text`: 13px, 13,5px di `xl`, 14px di `2xl`; semuanya Inter. |
| Menu dan dropdown terpisah | "Semua {kanal}" dihapus. Tombol menu menuju halaman kanal; dropdown hanya dibuka tombol panah, ditutup Esc, klik di luar, atau fokus keluar. |
| Ilustrasi latar halaman kanal | 23 ilustrasi SVG dalam `views/partials/kanal-art/`, satu per kanal dan sub-kanal, dipanggil lewat komponen `kanal-art`. Warna mengikuti warna teks kanal. Di bawah `lg` ilustrasi berada di atas teks agar tidak saling menimpa. |
| Urutan kolom samping | Paling banyak dibaca di atas, Sedang tren di bawahnya. |
| Pembatas seksi | Pembatas Terkini dan daftar kanal setipis garis menu (3px) dan selalu hitam. |
| Tajuk bawah: isi | Satu tajuk utama dengan foto, sisanya daftar. |
| Tajuk bawah: latar | Mengikuti `#cover` di insight.rayatimes.id: gambar yang sama, lapisan hitam 50%, `cover`, `fixed` mulai `lg`. |
| Foto kartu Analisis & perspektif dan Untuk memahami lebih utuh | Efek duotone dihapus dari template; foto tampil asli tanpa perlu hover. |
| Judul daftar artikel diperbesar | Daftar artikel 22px di ponsel dan 26px mulai `sm`, termasuk hasil muat-lebih-banyak; daftar headline samping 20px dan 22px. |

## Banner foto Wacana dan Lensa Mobile (9 November 2026)

Banner gambar di beranda Wacana (web) dan Lensa Mobile diganti kartu foto dengan lapisan gelap dan teks asli di atasnya, lewat `Repository::featureBanners()`. Insight memakai sampul `insight.rayatimes.id`; Tajuk memakai foto artikel Tajuk terbaru dengan deskripsi singkat (`tajuk_banner` di `site.php`), dan menuju halaman kanal Tajuk. Kedua banner punya ikon kunjungi di kanan bawah. Banner unduh aplikasi tidak lagi tampil di kedua template itu. `home_banners` tetap dipakai beranda Default dan Lensa web.

## Revisi Lensa Mobile 9 November 2026

| Permintaan | Yang dikerjakan |
| --- | --- |
| Header hitam | Header semua layar (`.bar`, `.appbar`, `.searchbar`, `.drawer-head`) berlatar `#111111` di kedua tema; status bar selalu putih. |
| Tanpa sudut membulat | Semua `border-radius` aplikasi dihapus, termasuk avatar, sakelar, dan pil pembaca. Yang tetap melengkung: rangka iPhone, layar perangkat, Dynamic Island, dan home indicator. |
| Menu | Tiga kolom sama lebar rapat ke tepi; latar bawaan mengikuti latar aplikasi, warna aktif tetap. |
| Thumbnail terhalang header | Header artikel dan kanal tidak lagi mengambang. Sampul artikel tampil utuh mengikuti rasio aslinya. |
| Logo mobile | Default dan Lensa kini memakai logo Main (`logo_main`, `logo_main_dark`); Wacana sudah memakainya. |
| Banner setelah headline | Dua banner bertumpuk setelah hero di tab Terbaru, lebar mengikuti layar, dengan jarak dan garis pembatas yang memudar di kedua ujungnya sebelum daftar Terbaru. |

## File di LUAR `resources/`

### Diubah

| File | Perubahan |
| --- | --- |
| `.gitignore` | Menambahkan `.claude/settings.local.json` karena berisi path khusus mesin lokal. |
| `routes/web.php` | Rute `welcome` diganti dengan `require __DIR__.'/mockup.php'`. |
| `bootstrap/app.php` | Mendaftarkan `PreventSearchIndexing` sebagai middleware global. |
| `public/robots.txt` | Melarang semua crawler dari seluruh situs (`Disallow: /`). **Wajib dikembalikan saat situs resmi dibuka untuk mesin pencari**, bersama pencabutan middleware di atas. |
| `vite.config.js` | Menambah entry CSS/JS untuk 3 template web dan 3 template aplikasi, serta `refresh` untuk `resources/templates`, `resources/data`, `routes`. |
| `package.json`, `package-lock.json` | Menambah `@fontsource-variable/newsreader`, `@fontsource-variable/inter`, `@fontsource-variable/archivo` (font brand di-host sendiri lewat Vite). Dev: `@playwright/cli`, `playwright`. Skrip baru: `npm run audit`, `npm run audit:interactions`, `npm run audit:mobile`. |
| `.gitignore` | Mengabaikan folder output alat: `/.playwright-cli`, `/.taste-skill`. |
| `composer.json`, `composer.lock` | Menambah `laravel/boost` (dev), sesuai instruksi `CLAUDE.md` proyek. |
| `CLAUDE.md` | Ditulis ulang otomatis oleh `php artisan boost:install`. |
| `lang/en/*`, `lang/id/*`, `lang/*.json` | Diterbitkan ulang otomatis oleh skrip Composer `laravel-lang` saat `composer require`. Tidak diedit manual. |
| `tests/Feature/ExampleTest.php` | Uji halaman berhasil dimuat kini memakai `/default`, karena `/` sekarang berupa pengalihan. |

### Ditambahkan

| File / folder | Isi |
| --- | --- |
| `routes/mockup.php` | Semua rute mockup, dikelompokkan per template di bawah `/default`, `/lensa`, `/wacana` (nama rute `mockup.{template}.*`), termasuk endpoint JSON `/{template}/_mock/articles` untuk muat-lebih-banyak dan pencarian instan. Middleware sesi & CSRF dilepas di grup ini karena tabel `sessions` belum dimigrasi. |
| `public/dummy/` | Gambar dummy (35 MB): foto artikel yang sudah dioptimasi ke WebP (versi `-lg` 1280px dan `-sm` 560px), avatar penulis, infografis, logo & lencana unduhan aplikasi. |
| `public/dummy/banner/insight-cover.webp` | Latar Tajuk Wacana dan banner Insight (Wacana, Lensa Mobile), konversi WebP dari `rayatimes-lapsus-hero.png` milik insight.rayatimes.id. |
| `public/dummy/banner/` | Dua gambar banner beranda: `rayatimes-insight.webp` (salinan aset Insight) dan `aplikasi-rayatimes.webp` (promo aplikasi, dibuat dengan font & warna brand). |
| `public/build/` | Hasil `npm run build`. |
| `boost.json`, `.mcp.json`, `.claude/skills/{deploying-to-cloud,infer-conventions,laravel-best-practices,tailwindcss-development,testing-best-practices}` | Dibuat oleh Laravel Boost. |
| `.claude/skills/frontend-design/` | Skill dari `anthropics/claude-plugins-official` (beserta LICENSE). |
| `.claude/skills/taste-skill/` | Skill dari `leonxlnx/taste-skill`, lengkap dengan referensi, library, dan skrip `draw`/`inspect` (lisensi MIT). |
| `.claude/skills/news-design-references/` | Skill khusus proyek ini: token brand RayaTimes, aturan struktur template, dan referensi Al Jazeera/The Verge/Wired dari `voltagent/awesome-design-md` (lisensi MIT). |
| `.claude/skills/playwright-cli/` | Dipasang oleh `npx playwright-cli install --skills claude`. |
| `.playwright/cli.config.json` | Konfigurasi bawaan playwright-cli (Chromium). |
| `.claude/settings.local.json` | `CHROME_PATH` ke Chromium Playwright (agar skrip `taste-skill` bisa jalan), dan riwayat `taste-skill` disimpan di proyek. Lokal, tidak untuk di-commit. |
| `tests/Browser/audit.mjs`, `tests/Browser/interactions.mjs` | Skrip audit Playwright yang dipakai selama pengerjaan. URL bisa diganti lewat `BASE_URL`. |
| `app/Http/Middleware/PreventSearchIndexing.php`, `tests/Feature/PreventSearchIndexingTest.php` | Header `X-Robots-Tag` di semua respons Laravel (termasuk 404 dan pengalihan) beserta ujinya. File statis tidak melewati Laravel, jadi dicakup `robots.txt`. |
| `tests/Feature/TemplatePathTest.php` | Uji pemisahan path template: pengalihan dari `/` dan URL lama, tautan tetap di dalam template, 404 per template, pemilih template, endpoint JSON. |
| `tests/Feature/MobileTemplateTest.php`, `tests/Browser/mobile.mjs` | Uji aturan mockup aplikasi: ukuran layar, urutan header, tiga menu, Unggulan, susunan artikel, Untuk Anda, dan hilangnya fitur sosial. |
| `tests/Feature/WacanaTemplateTest.php` | Uji revisi Wacana: ilustrasi tiap kanal, dropdown, urutan kolom samping, pembatas seksi, dan Tajuk. |
| `CATATAN-MOCKUP.md` | Dokumen ini. |
| `Profil-Desain-RayaTimes.pdf` | Profil desain 15 halaman (A4 mendatar) untuk pembaca non-teknis, mencakup portal web dan aplikasi: fondasi brand, kerangka aplikasi dan perubahannya, tiga halaman per template (profil, identitas visual, web dan aplikasi), acuan dan pengembangan Lensa serta Wacana, dan perbandingan. Tangkapan layarnya perlu dibuat ulang bila tampilan berubah. |

## Struktur di dalam `resources/`

```
resources/
├── data/rayatimes/          ← data dummy (satu-satunya yang dipakai bersama)
│   ├── Repository.php       ← kelas akses data yang dipanggil dari Blade ($rt)
│   ├── articles.php         ← 451 artikel (isi lengkap, upper title, tag, penulis & rekan penulis, gambar)
│   ├── categories.php  tags.php  authors.php  pages.php  media.php  stories.php  site.php
└── templates/
    ├── default/  { css/app.css, js/app.js, views/{layouts,partials,components,pages} }
    ├── lensa/    { … sama … }
    └── wacana/   { … sama … }
```

- Tiap template punya CSS (Tailwind dengan `@source` hanya ke folder miliknya), JS, layout, partial, dan komponen sendiri. Tidak ada CSS/JS/komponen yang dipakai bersama.
- View didaftarkan sebagai namespace (`default::`, `lensa::`, `wacana::`), komponen anonim sebagai `<x-default::…>`, `<x-lensa::…>`, `<x-wacana::…>`.
- `Repository::forTemplate()` menghasilkan semua URL dengan prefix template aktif, termasuk tautan internal di isi artikel dan halaman statis. Di Blade, tautan internal ditulis `{{ $rt->url('/search') }}`; di JS, tiap template memakai konstanta `BASE`.
- Data dummy berupa file PHP `return [...]` hasil ekstraksi `rayatimes.sql` (backup 24/10/2026). Data rahasia (password, surel, SMTP, API key) tidak ikut diekstrak.
- Tanggal terbit digeser saat dimuat, sehingga artikel terbaru selalu tampil "24 menit lalu" ketika dipresentasikan.
- Angka pembaca (`views`) di backup bernilai 0. Untuk "Terpopuler", angkanya dibuat deterministik dari token artikel.

## Audit (Playwright)

- 3 template × 23 halaman contoh × 2 lebar layar (1280px dan 390px): semua berstatus 200 (404 sesuai harapan), tanpa error JavaScript, tanpa gambar rusak, tanpa overflow horizontal, dan semua `<img>` punya `alt`.
- Interaksi yang lolos uji: skeleton dan muat-lebih-banyak, pencarian instan, toggle tema, switcher template (tetap di halaman yang sama), hero story Lensa, panel kanal, shortcut `/`, ukuran huruf dan mode fokus Wacana, drawer mobile.
- Uji tata letak headline Wacana: berita utama di kiri, 4 kartu horizontal di kanan dari kanal berbeda.
- Uji navigasi (ketiga template): dropdown kanal desktop terbuka dengan ketuk & tertutup Esc; sidenav ponsel terbuka dari posisi gulir, akordeon sub-kanal tampil, Esc menutup. Audit lebar 1024–1536px: bilah kanal Wacana tidak meluber.
- Uji urutan beranda (ketiga template, 390px & 1280px): banner → Terkini → Ekonomi → Politik → Hukum → Hankam; kolom samping setelah Hankam di ponsel dan di kanan pada desktop.
- Uji upper title (tepat di atas judul, bukan heading, satu h1), multi-penulis ("Fahmi dan Tondy Manangka"), dan empat penulis ("Inge Mangkoe dan 3 lainnya", daftar bisa dibuka) di ketiga template; audit mencakup halaman-halaman contohnya.
- Uji artikel Wacana responsif: 1 kolom + bilah baca di 390px dan 1100px, 3 kolom di 1366px, tanpa kotak "Sebelum membaca".
- Uji dua tata letak artikel (ketiga template): berita → judul di atas cover; Analisis → cover di atas judul + kartu penulis; `?tata=judul` memaksa judul atas. Audit kini juga mencakup halaman Analisis.
- Uji hasil meeting (ketiga template): banner 2 kolom tampil, widget Sedang tren di kolom samping beranda, urutan kanal Ekonomi → Politik → Hukum → Hankam, tombol muat lebih banyak di Terkini beranda. Lensa: mode terang aktif, hero tetap gelap, dan pilihan tema tersimpan setelah muat ulang.
- Diperbaiki selama audit: overflow halaman statis di mobile (Default & Wacana), mode fokus Wacana yang ikut menyembunyikan tombol keluarnya, area foto Lensa yang ikut memutih di mode terang, dan tagar topik panjang yang meluber di kepala artikel Analisis Default (mobile).
- Jalankan ulang kapan saja dengan `npm run audit` dan `npm run audit:interactions`.

## Fitur frontend existing vs mockup

Sumber: source portal lama (`resources/js/pages/portal`, `components/portal`, `layouts/PortalLayout.jsx`) dan `.claude/fitur-baru.md`, `.claude/CMS.md` di backup. Status: **Ada** = tampil & berfungsi di mockup (dengan data dummy), **Sebagian** = tampilan ada tetapi tidak tersambung/tidak lengkap, **Belum** = belum ada. Kolom D/L/W = Default/Lensa/Wacana.

### Kerangka & navigasi

| Fitur existing | D | L | W | Catatan |
| --- | --- | --- | --- | --- |
| Header: logo, cari, tema, Masuk/Daftar | Ada | Sebagian | Ada | Lensa belum punya tombol Masuk/Daftar. Di semua template, Masuk/Daftar hanya tautan `#`. |
| Menu kanal + dropdown sub-kanal | Ada | Ada | Ada | Menu masih dari kanal di data, belum dari modul Menu CMS. |
| Navigasi ponsel | Ada | Ada | Ada | Sidenav + akordeon. Lensa juga punya tab bar bawah. |
| Saran pencarian saat mengetik | Ada | Ada | Ada | Existing menyarankan beragam tipe (artikel, penulis, kanal); mockup hanya artikel. |
| Ganti tema terang/gelap | Ada | Ada | Belum | Wacana sengaja terang saja. |
| Dialog Masuk / Daftar / Lupa password (`AuthDialog`) | Belum | Belum | Belum | Termasuk halaman reset password & verifikasi email. |
| Ticker berita penting (`BreakingTicker`) | Belum | Belum | Belum | Data ada (126 artikel pernah bertanda breaking); tinggal dibuat per template. |
| Pemutar siniar mini global (`MiniPlayer`: putar/jeda, kecepatan, volume, tetap jalan antar-halaman) | Belum | Belum | Belum | Backup tidak punya episode siniar aktif. |
| Footer: kolom tautan, sosial, unduh aplikasi | Ada | Ada | Ada | |
| Halaman 404 | Ada | Ada | Ada | Existing bisa memakai judul/pesan 404 dari Pengaturan; mockup memakai teks tetap. |
| Mode pemeliharaan | Belum | Belum | Belum | Urusan backend (503). |
| Skeleton saat memuat | Ada | Ada | Ada | |

### Beranda

| Fitur existing | D | L | W | Catatan |
| --- | --- | --- | --- | --- |
| Headline (`HeadlineBento`) | Ada | Ada | Ada | Existing hanya memakai artikel bertanda Headline yang masih berlaku (kosong = disembunyikan); mockup mengisi dengan berita terbaru bila kurang. Wacana memilih 4 headline samping dari kanal berbeda. |
| Topik Hits (`TopicCard`) | — | — | — | Diganti banner 2 kolom + widget Sedang tren di kolom samping (keputusan meeting). |
| Terkini + muat lebih banyak | Ada | Ada | Ada | |
| Blok kanal (Ekonomi, Politik, Hukum, Hankam) | Ada | Ada | Ada | Urutan dari `home_sections` di data dummy, belum dari pengaturan Layout CMS. |
| Widget Layout CMS (15 tipe, per halaman × area) | Sebagian | Sebagian | Sebagian | Sudah terwakili: kanal, artikel tunggal (D), terpopuler, terbaru, banner, newsletter (D), media post (L), tag/tren. Belum: podcast, podcast player, jajak pendapat, teks, HTML/iklan dinamis, breaking. Susunan widget masih ditulis tetap di template. |
| Jajak pendapat (`PollCard`) | Belum | Belum | Belum | Tidak ada data jajak pendapat aktif di backup. |
| Form newsletter | Sebagian | Belum | Belum | Default menampilkan form, tetapi belum mengirim. |

### Artikel

| Fitur existing | D | L | W | Catatan |
| --- | --- | --- | --- | --- |
| Breadcrumb Kanal › Sub-kanal | Ada | Sebagian | Sebagian | Lensa hanya kanal; Wacana memakai label kanal tanpa breadcrumb. |
| Judul atas (*upper*) | Ada | Ada | Ada | |
| Susunan khusus kanal Analisis | Ada | Ada | Ada | **Berbeda dari existing**: existing = judul → gambar → "oleh: penulis (Redaktur RayaTimes) \| tanggal"; mockup = cover dulu → judul → kartu penulis (sesuai permintaan). |
| Multi-penulis (`AuthorNames`) | Ada | Ada | Ada | |
| Label konten bersponsor | Belum | Belum | Belum | Data ada (1 artikel). |
| Galeri foto tambahan | Belum | Belum | Belum | Data ada (10 artikel punya >1 foto); mockup hanya memakai foto utama. |
| Perbesar gambar (`ImageLightbox`) di artikel | Belum | Belum | Belum | Lensa punya lightbox di halaman media saja. |
| Isi artikel dilipat "Muat lebih banyak" | Belum | Belum | Belum | Sakelar per artikel; tidak ada artikel yang menyalakannya di backup. |
| Video artikel | Belum | Belum | Belum | Tidak ada data video. |
| Baca juga di tengah isi | Ada | Ada | Ada | Existing disisipkan acak (dengan seed slug); mockup di posisi tetap. |
| Tautan internal otomatis (autolink tag) | Belum | Belum | Belum | Diproses server saat render; mockup memakai isi mentah. |
| Suka & Simpan (tersimpan, butuh login) | Sebagian | Sebagian | Sebagian | Tombol berubah status, tetapi tidak tersimpan. |
| Bagikan (`ShareButtons`) | Sebagian | Sebagian | Sebagian | Salin tautan berfungsi; tombol WhatsApp/X/dll masih `#`. |
| Tag artikel | Ada | Ada | Ada | |
| Profil penulis + Ikuti (`FollowButton`) | Sebagian | Sebagian | Sebagian | Ikuti hanya mengubah tampilan. |
| Komentar (`CommentSection`) | Belum | Belum | Belum | Di existing komentar sedang dimatikan lewat Pengaturan. |
| Berita terkait / terkait topik | Ada | Ada | Ada | |
| Widget kanal di samping artikel (`ChannelBox`) | Ada | Sebagian | Belum | Lensa memakai rel "Lainnya di kanal". |

### Kanal, topik, indeks, pencarian

| Fitur existing | D | L | W | Catatan |
| --- | --- | --- | --- | --- |
| Halaman kanal `/@slug` + sub-kanal + muat lebih banyak | Ada | Ada | Ada | |
| Berlangganan kanal + jumlah subscriber (`SubscribeButton`) | Sebagian | Sebagian | Sebagian | Tombol Ikuti hanya tampilan; jumlah subscriber belum tampil. |
| `/@handle` untuk penulis/host | Belum | Belum | Belum | Mockup memakai `/u/username`; `/@slug` hanya untuk kanal. |
| Indeks topik `/tag` & detail topik | Ada | Ada | Ada | Wacana menambah indeks A–Z. |
| Indeks berita `/index` | Ada | Ada | Ada | |
| Pencarian dengan tab Berita / Penulis/Host / Kanal / Podcast / Media | Sebagian | Sebagian | Sebagian | Default: tab Berita, Penulis, Kanal, Topik (tanpa Podcast & Media). Lensa & Wacana: hasil berita + saran penulis/topik, tanpa tab. |

### Penulis & member

| Fitur existing | D | L | W | Catatan |
| --- | --- | --- | --- | --- |
| Profil publik `/u/username`: tulisan | Ada | Ada | Ada | |
| Profil publik: tab Langganan & Mengikuti/Pengikut | Belum | Belum | Belum | |
| Area member: dashboard + personalisasi, notifikasi, komentar saya, tersimpan, langganan, jaringan, pengaturan akun (avatar, username, privasi, hapus akun) | Belum | Belum | Belum | Butuh login. |

### Siniar, media, halaman lain

| Fitur existing | D | L | W | Catatan |
| --- | --- | --- | --- | --- |
| Indeks & detail siniar (audio/YouTube/Spotify) | Sebagian | Sebagian | Sebagian | Hanya halaman indeks dengan *empty state*; detail belum ada (tidak ada data). |
| Galeri media: foto & video terbaru | Sebagian | Sebagian | Sebagian | Foto/infografis ada; video *empty state*. |
| Indeks khusus `/media/video` & `/media/photo` | Belum | Belum | Belum | |
| Detail media: zoom foto, CTA post, tautan per foto, posisi fokus gambar | Sebagian | Sebagian | Sebagian | Detail & CTA post ada. Zoom hanya Lensa. Tautan per foto & posisi fokus belum. |
| Halaman statis `/p/slug` | Ada | Ada | Ada | |
| Newsletter: konfirmasi, berhenti, kelola preferensi tanpa login | Belum | Belum | Belum | |

### SEO & teknis

| Fitur existing | Status | Catatan |
| --- | --- | --- |
| `<title>` & meta description per halaman | Ada | |
| Open Graph / Twitter card, canonical, JSON-LD Schema.org | Belum | Existing menyuntikkan dari server per rute. |
| Sitemap, Google News, RSS, robots | — | Backend; tidak disentuh mockup. |
| Pelacakan kunjungan (`/api/v1/track`), GA/GTM | Belum | Sengaja tidak dipasang di mockup. |
| Data dari API `/api/v1` | Belum | Mockup membaca data dummy PHP; endpoint `/_mock/articles` hanya untuk muat lebih banyak & saran cari. |

### Keputusan yang sudah ditetapkan

- **Jejak Isu dihapus** dari portal web dan aplikasi: halaman `/isu/{slug}`, tautan di artikel, teaser beranda, data `stories.php`, dan method-nya di `Repository.php`.
- **Susunan artikel Analisis** di web tetap memakai cover dulu, walau berbeda dari situs saat ini (judul → gambar → penulis).
- **Atribut artikel aplikasi** berisi penulis, tanggal terbit, kanal, dan waktu baca.

### Ada di mockup, tidak ada di existing

Banner 2 kolom; widget Sedang tren bergaya X; kolom "Detik ini" (Lensa); hero story (Lensa); mode terang/gelap Lensa; tab bar bawah (Lensa); headline beragam kanal, indeks topik A–Z, warna per kanal (Wacana); kontrol ukuran huruf & mode fokus (Wacana); garis progres baca; transisi halaman & foto "terbang" (View Transitions); paksa tata letak artikel `?tata=judul|cover`; pemilih template.

## Catatan lanjutan

- Siniar dan video di backup kosong, jadi halaman tersebut menampilkan *empty state* yang dirancang.
- Konten artikel yang memuat gambar `.avif` atau video `.mp4` masih merujuk ke `rayatimes.id` karena formatnya tidak dikonversi.
- Untuk mengembalikan halaman bawaan Laravel, kembalikan `routes/web.php` ke rute `welcome`.
- Dokumen identitas template di claude.ai belum diperiksa untuk rujukan cara lama (`?template=`).
