---
name: news-design-references
description: Brand RayaTimes dan referensi desain portal berita (Al Jazeera, The Verge, Wired). Gunakan saat mendesain, merombak, atau menambah halaman/komponen di template portal (resources/templates/*), memilih warna/tipografi untuk RayaTimes, atau membandingkan pola tata letak portal berita.
---

# Referensi desain portal berita RayaTimes

## Brand RayaTimes (wajib diikuti)

Sumber: `Assets/VISUAL IDENTITY_RAYA TIMES.pdf` dan `Assets/Brand Identity RayaTimes .pdf`.

- **Tipografi**: Newsreader = suara (headline, judul, pull quote, opini). Inter = informasi (subjudul, body, caption, metadata, antarmuka). Eksplorasi di luar dua font ini diizinkan untuk konten khusus: editorial display, *condensed display* (untuk dampak/urgensi), atau dekoratif.
- **Warna utama**: Raya Red `#C62828`, Yellow `#FFC107`, Black `#111111`, White.
- **Warna sekunder**: Java Red `#8F2635`, Terracotta `#8A5A44`, Muted Gold `#C49A45`, Warm Beige `#E8DCC8`, Deep Green `#254B3C`, Soft Blue `#54758A`.
- **Tiga layout**: 01 The News Frame (foto, warna, headline), 02 The Story Frame (visual membawa cerita, tipografi memberi konteks), 03 The Editorial (berwarna, tipografis, beropini: Tajuk, Insight).
- **Visi**: "Fakta, Makna, dan Daya Bangsa". **Misi**: "Melihat Lebih Luas, Memahami Lebih Utuh, Bertindak Lebih Solutif."
- Logo dan aset brand siap pakai ada di `public/dummy/brand/`.

## Aturan struktur template di proyek ini

- Tiap template berdiri sendiri di `resources/templates/{default,lensa,wacana}/` (css, js, views). Jangan berbagi CSS, JS, atau komponen antar-template.
- Data hanya dari `resources/data/rayatimes/` lewat `$rt` (`Repository.php`). Jangan menyentuh backend.
- Stack: Blade, Vite, jQuery, Tailwind CSS v4 (CSS-first `@theme`), Remixicon.
- Setelah perubahan, audit dengan skill `playwright-cli` di lebar 1280px dan 390px. Pastikan tidak ada error konsol dan tidak ada overflow horizontal.
- Catat setiap file di luar `resources/` yang diubah di `CATATAN-MOCKUP.md`.

## Referensi pembanding

Baca file berikut hanya bila relevan. Ini analisis desain pihak ketiga (awesome-design-md, lisensi MIT), bukan untuk ditiru.

- `references/aljazeera.md`: hard-news, satu warna tanda tangan, label LIVE/breaking, kepadatan headline.
- `references/theverge.md`: tile berwarna penuh, display tebal, timeline cerita.
- `references/wired.md`: majalah cetak di web, serif display tinggi, serif untuk teks panjang.
