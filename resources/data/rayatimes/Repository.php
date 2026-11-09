<?php

namespace RayaTimes\Mockup;

use Carbon\CarbonImmutable;

/**
 * Akses data dummy RayaTimes untuk kebutuhan mockup template.
 *
 * Semua data berasal dari file PHP di folder ini (hasil ekstraksi backup), dimuat sekali per request.
 * Tanggal terbit digeser agar artikel terbaru selalu tampak "baru saja" saat presentasi.
 */
final class Repository
{
    /** @var array<string, self> */
    private static array $instances = [];

    private readonly string $basePath;

    /** @var array<string, mixed> */
    private array $site;

    /** @var array<string, array<string, mixed>> */
    private array $categories = [];

    /** @var array<int, array<string, mixed>> */
    private array $articles = [];

    /** @var array<string, int> */
    private array $articleIndex = [];

    /** @var array<string, array<string, mixed>> */
    private array $authors = [];

    /** @var array<string, array<string, mixed>> */
    private array $tags = [];

    /** @var array<int, array<string, mixed>> */
    private array $pages;

    /** @var array<int, array<string, mixed>> */
    private array $media;

    private CarbonImmutable $now;

    private int $shiftSeconds;

    /**
     * Repositori untuk satu template: semua URL yang dihasilkan memakai prefix path template itu.
     */
    public static function forTemplate(string $template): self
    {
        return self::$instances[$template] ??= new self($template);
    }

    private function __construct(string $template)
    {
        $dir = __DIR__;
        $this->basePath = '/'.$template;
        $this->now = CarbonImmutable::now('Asia/Jakarta');
        $this->site = require $dir.'/site.php';
        $this->media = require $dir.'/media.php';
        $this->pages = array_map(
            fn (array $page): array => ['content' => $this->localizeLinks($page['content'])] + $page,
            require $dir.'/pages.php',
        );

        foreach ($this->site['footer_columns'] as $column => $footerColumn) {
            foreach ($footerColumn['links'] as $link => $footerLink) {
                $this->site['footer_columns'][$column]['links'][$link]['url'] = $this->url($footerLink['url']);
            }
        }
        foreach (require $dir.'/categories.php' as $category) {
            $this->categories[$category['slug']] = $category + ['url' => $this->url('/@'.$category['slug'])];
        }
        foreach (require $dir.'/authors.php' as $author) {
            $this->authors[$author['username']] = $author + ['url' => $this->url('/u/'.$author['username'])];
        }
        foreach (require $dir.'/tags.php' as $tag) {
            $this->tags[$tag['slug']] = $tag + ['url' => $this->url('/tag/'.$tag['slug'])];
        }

        $rows = require $dir.'/articles.php';
        usort($rows, fn ($a, $b) => strcmp($b['published_at'], $a['published_at']));
        $newest = CarbonImmutable::parse($rows[0]['published_at'], 'Asia/Jakarta');
        $this->shiftSeconds = (int) $newest->diffInSeconds($this->now->subMinutes(24), false);

        foreach ($rows as $i => $row) {
            $this->articles[$i] = $this->hydrate($row);
            $this->articleIndex[$row['slug']] = $i;
        }
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    private function hydrate(array $row): array
    {
        $published = $this->shifted($row['published_at']);
        $row['published'] = $published;
        $row['category'] = $this->categories[$row['category']] ?? ['name' => $row['category'], 'slug' => $row['category'], 'url' => '#', 'parent' => null];
        $row['authors'] = array_values(array_filter(array_map(fn ($u) => $this->authors[$u] ?? null, $row['authors'])));
        $row['author'] = $row['authors'][0] ?? null;
        $row['tag_list'] = array_values(array_filter(array_map(fn ($t) => $this->tags[$t] ?? null, $row['tags'])));
        $row['url'] = $this->url('/'.$row['category']['slug'].'/'.$row['slug']);
        $row['content'] = $this->localizeLinks($row['content']);

        return $row;
    }

    /**
     * Tautan internal di isi konten mengarah ke template aktif; aset dan tautan luar tidak berubah.
     */
    private function localizeLinks(string $html): string
    {
        return preg_replace('#\bhref=(["\'])/(?!/|dummy/|build/)#', 'href=$1'.$this->basePath.'/', $html);
    }

    /* ------------------------------------------------------------------- URL */

    /**
     * URL halaman di dalam template aktif, mis. "/search" menjadi "/lensa/search".
     * Nilai yang bukan path internal (tautan luar, "#") dikembalikan apa adanya.
     */
    public function url(string $path = '/'): string
    {
        if (! str_starts_with($path, '/')) {
            return $path;
        }

        return $path === '/' ? $this->basePath : $this->basePath.$path;
    }

    /**
     * Path halaman saat ini tanpa prefix template, mis. "/lensa/index" menjadi "/index".
     */
    public function localPath(): string
    {
        return substr('/'.request()->path(), strlen($this->basePath)) ?: '/';
    }

    /**
     * URL halaman yang sama (termasuk query) di template lain, untuk pemilih template.
     */
    public function templateUrl(string $template): string
    {
        return '/'.$template.substr(request()->getRequestUri(), strlen($this->basePath));
    }

    private function shifted(?string $date): CarbonImmutable
    {
        return CarbonImmutable::parse($date ?? 'now', 'Asia/Jakarta')->addSeconds($this->shiftSeconds ?? 0);
    }

    /* ---------------------------------------------------------------- Format */

    public function ago(CarbonImmutable|string $date): string
    {
        $date = is_string($date) ? $this->shifted($date) : $date;
        $seconds = max(0, $this->now->getTimestamp() - $date->getTimestamp());

        return match (true) {
            $seconds < 60 => 'Baru saja',
            $seconds < 3600 => intdiv($seconds, 60).' menit lalu',
            $seconds < 86400 => intdiv($seconds, 3600).' jam lalu',
            $seconds < 86400 * 7 => intdiv($seconds, 86400).' hari lalu',
            default => $this->date($date, false),
        };
    }

    public function date(CarbonImmutable|string $date, bool $withTime = true): string
    {
        $date = is_string($date) ? $this->shifted($date) : $date;
        $date = $date->locale('id');

        return $withTime
            ? $date->translatedFormat('l, d F Y | H:i').' WIB'
            : $date->translatedFormat('d M Y');
    }

    /**
     * Tanggal terbit di atribut artikel aplikasi: "27 September 2026 | 02:41".
     */
    public function attributeDate(CarbonImmutable $date): string
    {
        return $date->locale('id')->translatedFormat('j F Y | H:i');
    }

    public function now(): CarbonImmutable
    {
        return $this->now;
    }

    /**
     * Pita tanggal daftar berita di aplikasi: "Hari Ini, Kamis 6 Agustus 2026".
     */
    public function dateBand(CarbonImmutable $date): string
    {
        $full = $date->locale('id')->translatedFormat('l j F Y');

        return match (true) {
            $date->isSameDay($this->now) => 'Hari Ini, '.$full,
            $date->isSameDay($this->now->subDay()) => 'Kemarin, '.$full,
            default => $full,
        };
    }

    /**
     * Nama penulis untuk tampilan teks: "A", "A dan B", "A, B, dan C".
     * Versi ringkas (kartu): "A dan 2 lainnya".
     */
    public function authorLine(array $article, bool $compact = false): string
    {
        $names = array_column($article['authors'], 'name') ?: ['Redaksi'];
        $count = count($names);

        return match (true) {
            $count === 1 => $names[0],
            $compact => $names[0].' dan '.($count - 1).' lainnya',
            $count === 2 => $names[0].' dan '.$names[1],
            default => implode(', ', array_slice($names, 0, -1)).', dan '.end($names),
        };
    }

    public function compact(int $number): string
    {
        return $number >= 1000 ? rtrim(rtrim(number_format($number / 1000, 1, ',', '.'), '0'), ',').' rb' : (string) $number;
    }

    /* ------------------------------------------------------------------ Site */

    /** @return array<string, mixed> */
    public function site(): array
    {
        return $this->site;
    }

    /**
     * Menu utama portal (kanal induk + sub-kanal), mengikuti urutan situs resmi.
     *
     * @return array<int, array<string, mixed>>
     */
    public function nav(): array
    {
        $nav = [['name' => 'Beranda', 'url' => $this->url(), 'slug' => '', 'children' => []]];
        foreach ($this->categories as $category) {
            if ($category['parent'] === null) {
                $nav[] = $category + ['children' => $this->childCategories($category['slug'])];
            }
        }
        $nav[] = ['name' => 'Indeks', 'url' => $this->url('/index'), 'slug' => 'index', 'children' => []];
        $nav[] = ['name' => 'Siniar', 'url' => $this->url('/podcast'), 'slug' => 'podcast', 'children' => []];
        $nav[] = ['name' => 'Media', 'url' => $this->url('/media'), 'slug' => 'media', 'children' => []];

        return $nav;
    }

    /* ------------------------------------------------------------ Categories */

    /** @return array<string, array<string, mixed>> */
    public function categories(): array
    {
        return $this->categories;
    }

    /** @return array<string, mixed>|null */
    public function category(string $slug): ?array
    {
        return $this->categories[$slug] ?? null;
    }

    /** @return array<int, array<string, mixed>> */
    public function childCategories(string $slug): array
    {
        return array_values(array_filter($this->categories, fn ($c) => $c['parent'] === $slug));
    }

    /* -------------------------------------------------------------- Articles */

    /** @return array<int, array<string, mixed>> */
    public function articles(): array
    {
        return $this->articles;
    }

    /** @return array<string, mixed>|null */
    public function article(string $slug): ?array
    {
        return isset($this->articleIndex[$slug]) ? $this->articles[$this->articleIndex[$slug]] : null;
    }

    /**
     * @param  array<int, string>  $exclude  slug artikel yang tidak ikut
     * @return array<int, array<string, mixed>>
     */
    public function latest(int $limit = 10, int $offset = 0, array $exclude = []): array
    {
        $list = $exclude ? array_values(array_filter($this->articles, fn ($a) => ! in_array($a['slug'], $exclude, true))) : $this->articles;

        return array_slice($list, $offset, $limit);
    }

    /**
     * Artikel utama: diutamakan yang ditandai headline dalam 3 hari terakhir.
     *
     * @return array<int, array<string, mixed>>
     */
    public function headlines(int $limit = 5, bool $diverse = false): array
    {
        $recent = array_slice($this->articles, 0, 80);
        $flagged = array_filter($recent, fn ($a) => $a['is_headline']);
        $rest = array_filter($recent, fn ($a) => ! $a['is_headline']);
        $ordered = array_values([...$flagged, ...$rest]);

        if (! $diverse) {
            return array_slice($ordered, 0, $limit);
        }

        // Satu berita per kanal induk dulu, agar headline tidak didominasi satu kanal.
        $picked = [];
        $seen = [];
        foreach ($ordered as $article) {
            $kanal = $article['category']['parent'] ?? $article['category']['slug'];
            if (! isset($seen[$kanal])) {
                $seen[$kanal] = true;
                $picked[] = $article;
            }
        }
        foreach ($ordered as $article) {
            if (! in_array($article, $picked, true)) {
                $picked[] = $article;
            }
        }

        return array_slice($picked, 0, $limit);
    }

    /**
     * Laporan terkait yang terbit sebelum artikel ini, diurutkan dari yang terlama.
     *
     * @param  array<int, string>  $exclude  slug yang tidak ikut
     * @return array<int, array<string, mixed>>
     */
    public function background(array $article, int $limit = 3, array $exclude = []): array
    {
        $earlier = array_filter(
            $this->related($article, 12),
            fn ($a) => $a['published_at'] < $article['published_at'] && ! in_array($a['slug'], $exclude, true),
        );
        $earlier = array_slice(array_values($earlier), 0, $limit);
        usort($earlier, fn ($a, $b) => strcmp($a['published_at'], $b['published_at']));

        return $earlier;
    }

    /** @return array<int, array<string, mixed>> */
    public function popular(int $limit = 10, int $window = 120): array
    {
        $list = array_slice($this->articles, 0, $window);
        usort($list, fn ($a, $b) => $b['views'] <=> $a['views']);

        return array_slice($list, 0, $limit);
    }

    /** @return array<int, array<string, mixed>> */
    public function inCategory(string $slug, bool $withChildren = true): array
    {
        $slugs = [$slug];
        if ($withChildren) {
            $slugs = [...$slugs, ...array_column($this->childCategories($slug), 'slug')];
        }

        return array_values(array_filter($this->articles, fn ($a) => in_array($a['category']['slug'], $slugs, true)));
    }

    /** @return array<int, array<string, mixed>> */
    public function withTag(string $slug): array
    {
        return array_values(array_filter($this->articles, fn ($a) => in_array($slug, $a['tags'], true)));
    }

    /** @return array<int, array<string, mixed>> */
    public function byAuthor(string $username): array
    {
        return array_values(array_filter($this->articles, fn ($a) => in_array($username, array_column($a['authors'], 'username'), true)));
    }

    /** @return array<int, array<string, mixed>> */
    public function search(string $query): array
    {
        $words = array_filter(preg_split('/\s+/', mb_strtolower(trim($query))));
        if (! $words) {
            return [];
        }
        $scored = [];
        foreach ($this->articles as $article) {
            $haystack = mb_strtolower($article['title'].' '.$article['excerpt'].' '.implode(' ', $article['tags']).' '.$article['keywords']);
            $title = mb_strtolower($article['title']);
            $score = 0;
            foreach ($words as $word) {
                if (! str_contains($haystack, $word)) {
                    continue 2;
                }
                $score += str_contains($title, $word) ? 3 : 1;
            }
            $scored[] = [$score, $article];
        }
        usort($scored, fn ($a, $b) => $b[0] <=> $a[0] ?: strcmp($b[1]['published_at'], $a[1]['published_at']));

        return array_column($scored, 1);
    }

    /** @return array<int, array<string, mixed>> */
    public function related(array $article, int $limit = 6): array
    {
        $scored = [];
        foreach (array_slice($this->articles, 0, 260) as $candidate) {
            if ($candidate['slug'] === $article['slug']) {
                continue;
            }
            $score = count(array_intersect($candidate['tags'], $article['tags'])) * 2
                + ($candidate['category']['slug'] === $article['category']['slug'] ? 1 : 0);
            if ($score > 0) {
                $scored[] = [$score, $candidate];
            }
        }
        usort($scored, fn ($a, $b) => $b[0] <=> $a[0] ?: strcmp($b[1]['published_at'], $a[1]['published_at']));

        return array_slice(array_column($scored, 1), 0, $limit);
    }

    /**
     * Tata letak kepala artikel: "cover" (cover dulu, lalu judul & penulis) untuk kanal Analisis,
     * "judul" (judul di atas cover) untuk artikel lain. Bisa dipaksa lewat ?tata=judul|cover.
     */
    public function articleLayout(array $article, mixed $override = null): string
    {
        if (in_array($override, ['judul', 'cover'], true)) {
            return $override;
        }

        return in_array($article['category']['slug'], ['analisis'], true) ? 'cover' : 'judul';
    }

    /**
     * Pecah konten artikel menjadi paragraf agar template bisa menyisipkan "Baca juga" dsb.
     *
     * @return array<int, string>
     */
    public function contentBlocks(array $article): array
    {
        preg_match_all('/<(p|h[1-6]|ul|ol|figure|blockquote|div|table|video)\b[^>]*>.*?<\/\1>/s', $article['content'], $matches);

        return $matches[0] ?: [$article['content']];
    }

    /**
     * Daftar isi dari subjudul (h2/h3) di konten.
     *
     * @return array<int, string>
     */
    public function outline(array $article): array
    {
        preg_match_all('/<h[23][^>]*>(.*?)<\/h[23]>/s', $article['content'], $matches);

        return array_values(array_filter(array_map(fn ($h) => trim(html_entity_decode(strip_tags($h))), $matches[1])));
    }

    /* ------------------------------------------------------------------ Tags */

    /** @return array<string, array<string, mixed>> */
    public function tags(): array
    {
        return $this->tags;
    }

    /** @return array<string, mixed>|null */
    public function tag(string $slug): ?array
    {
        return $this->tags[$slug] ?? null;
    }

    /**
     * Topik paling ramai pada artikel terbaru, lengkap dengan gambar dari artikel terakhirnya.
     *
     * @return array<int, array<string, mixed>>
     */
    public function hitTopics(int $limit = 8, int $window = 160): array
    {
        $counts = [];
        $covers = [];
        $kanal = [];
        foreach (array_slice($this->articles, 0, $window) as $article) {
            foreach ($article['tags'] as $slug) {
                $counts[$slug] = ($counts[$slug] ?? 0) + 1;
                $covers[$slug] ??= $article;
                $category = $article['category']['slug'];
                $kanal[$slug][$category] = ($kanal[$slug][$category] ?? 0) + 1;
            }
        }
        arsort($counts);
        $topics = [];
        foreach (array_slice($counts, 0, $limit, true) as $slug => $count) {
            arsort($kanal[$slug]);
            $name = $this->tags[$slug]['name'];
            $kanalSlug = (string) array_key_first($kanal[$slug]);
            $topics[] = $this->tags[$slug] + [
                'recent' => $count,
                'cover' => $covers[$slug],
                // Kanal tempat topik ini paling sering muncul ("Tren di Hukum").
                'kanal' => $this->categories[$kanalSlug]['name'] ?? $kanalSlug,
                'kanal_slug' => $kanalSlug,
                // Gaya trending X: satu kata jadi tagar, frasa beberapa kata ditulis apa adanya.
                'label' => str_contains(trim($name), ' ') ? $name : '#'.$name,
            ];
        }

        return $topics;
    }

    /* --------------------------------------------------------------- Authors */

    /** @return array<string, array<string, mixed>> */
    public function authors(): array
    {
        return $this->authors;
    }

    /** @return array<string, mixed>|null */
    public function author(string $username): ?array
    {
        return $this->authors[$username] ?? null;
    }

    /* ------------------------------------------------------- Pages dan media */

    /** @return array<int, array<string, mixed>> */
    public function pages(): array
    {
        return $this->pages;
    }

    /** @return array<string, mixed>|null */
    public function page(string $slug): ?array
    {
        return array_values(array_filter($this->pages, fn ($p) => $p['slug'] === $slug))[0] ?? null;
    }

    /** @return array<int, array<string, mixed>> */
    public function media(): array
    {
        return array_map(fn ($m) => $m + ['url' => $this->url('/media/photo/'.$m['slug']), 'published' => $this->shifted($m['published_at'])], $this->media);
    }

    /** @return array<string, mixed>|null */
    public function mediaItem(string $slug): ?array
    {
        return array_values(array_filter($this->media(), fn ($m) => $m['slug'] === $slug))[0] ?? null;
    }

    /**
     * Contoh notifikasi aplikasi. Hanya berita dan info akun; aktivitas sosial (ikuti, komentar) tidak ada lagi.
     *
     * @return array<int, array{id: string, type: string, label: string, title: string, body: string|null, url: string, ago: string}>
     */
    public function mobileNotifications(): array
    {
        $types = [['breaking', 'Berita Penting'], ['kanal', 'Dari Minat Anda'], ['kanal', 'Dari Minat Anda'], ['breaking', 'Berita Penting'], ['kanal', 'Dari Minat Anda'], ['kanal', 'Dari Minat Anda']];
        $notifications = [];

        foreach ($this->headlines(6, diverse: true) as $i => $article) {
            $notifications[] = [
                'id' => 'n-'.$article['slug'],
                'type' => $types[$i][0],
                'label' => $types[$i][1],
                'title' => $article['title'],
                'body' => $article['excerpt'] ?: null,
                'url' => $article['url'],
                'ago' => $this->ago($article['published']),
            ];
        }

        $notifications[] = [
            'id' => 'n-minat',
            'type' => 'sistem',
            'label' => 'Info Akun',
            'title' => 'Atur minat Anda',
            'body' => 'Pilih topik agar tab Untuk Anda sesuai dengan yang ingin Anda baca.',
            'url' => $this->url('/minat'),
            'ago' => '1 hari lalu',
        ];

        return $notifications;
    }

    /* ------------------------------------------------------------- Listing */

    /**
     * Sumber daftar untuk halaman & endpoint "muat lebih banyak".
     *
     * @return array<int, array<string, mixed>>
     */
    public function scope(string $scope): array
    {
        [$type, $value] = array_pad(explode(':', $scope, 2), 2, '');

        return match ($type) {
            'category' => $this->inCategory($value),
            'tag' => $this->withTag($value),
            'author' => $this->byAuthor($value),
            'search' => $this->search($value),
            default => $this->articles,
        };
    }

    /**
     * Ringkasan artikel untuk respons JSON (dirender ulang oleh JS tiap template).
     *
     * @return array<string, mixed>
     */
    public function summary(array $article): array
    {
        return [
            'title' => $article['title'],
            'url' => $article['url'],
            'excerpt' => $article['excerpt'],
            'image' => $article['image']['sm'],
            'image_lg' => $article['image']['lg'],
            'category' => $article['category']['name'],
            'category_url' => $article['category']['url'],
            'author' => $this->authorLine($article, compact: true),
            'author_url' => $article['author']['url'] ?? '#',
            'author_avatar' => $article['author']['avatar'] ?? null,
            'ago' => $this->ago($article['published']),
            'date' => $this->date($article['published'], false),
            'reading_time' => $article['reading_time'],
        ];
    }
}
