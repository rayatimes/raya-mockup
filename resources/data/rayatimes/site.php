<?php

/**
 * Data dummy RayaTimes (site) — diekstrak dari backup 24/10/2026.
 * Dibuat otomatis; jangan diedit manual.
 */

return [
    'name' => 'RayaTimes',
    'title' => 'Fakta, Makna, Daya Bangsa',
    'description' => 'RayaTimes.id menyajikan berita terbaru Indonesia dan dunia seputar politik, ekonomi, hukum, hingga gaya hidup. Fakta, Makna, Daya Bangsa.',
    'locale' => 'id',
    'timezone' => 'Asia/Jakarta',
    'brand' => [
        'logo' => '/dummy/brand/rayatimes_portal_light.webp',
        'logo_dark' => '/dummy/brand/rayatimes_portal_dark.webp',
        'logo_main' => '/dummy/brand/rayatimes_main_light.webp',
        'logo_main_dark' => '/dummy/brand/rayatimes_main_dark.webp',
        'mark' => '/dummy/brand/rayatimes_logo.webp',
        'favicon' => '/dummy/brand/favicon.png',
        'insight' => '/dummy/brand/rayatimes_insight.webp',
        'insight_dark' => '/dummy/brand/rayatimes_insight_dark.webp',
    ],
    'insight_url' => 'https://insight.rayatimes.id/',
    'footer_columns' => [
        [
            'title' => 'Perusahaan',
            'links' => [
                [
                    'label' => 'Tentang Kami',
                    'url' => '/p/about-us',
                ],
                [
                    'label' => 'Hubungi Kami',
                    'url' => '/p/contact-us',
                ],
                [
                    'label' => 'Kebijakan Privasi',
                    'url' => '/p/privacy-policy',
                ],
                [
                    'label' => 'Pedoman Editorial',
                    'url' => '/p/editorial-guidelines',
                ],
                [
                    'label' => 'Pedoman Media Siber',
                    'url' => '/p/online-media-guidelines',
                ],
                [
                    'label' => 'Rate Card',
                    'url' => '/p/rate-card',
                ],
            ],
        ],
        [
            'title' => 'Kanal Unggulan',
            'links' => [
                [
                    'label' => 'Politik',
                    'url' => '/@politik',
                ],
                [
                    'label' => 'Ekonomi',
                    'url' => '/@ekonomi',
                ],
                [
                    'label' => 'Hankam',
                    'url' => '/@hankam',
                ],
                [
                    'label' => 'Hukum',
                    'url' => '/@hukum',
                ],
                [
                    'label' => 'Analisis',
                    'url' => '/@analisis',
                ],
            ],
        ],
    ],
    'social_links' => [
        [
            'platform' => 'instagram',
            'url' => '#',
        ],
    ],
    'app_download' => [
        'title' => 'Unduh Aplikasi',
        'text' => 'RayaTimes kini tersedia untuk Android dan iOS',
        'links' => [
            [
                'name' => 'Android',
                'platform' => 'android',
                'url' => 'https://rayatimes.id/android',
                'badge' => '/dummy/brand/android-download-badge.webp',
            ],
            [
                'name' => 'App Store',
                'platform' => 'ios',
                'url' => 'https://apps.apple.com/id/app/rayatimes/id6807511076',
                'badge' => '/dummy/brand/app-store-badge-168.webp',
            ],
        ],
    ],
    'home_widgets' => [
        [
            'area' => 'main',
            'type' => 'category',
            'config' => [
                'limit' => 6,
                'style' => 'carousel',
                'columns' => 3,
                'show_image' => true,
                'show_excerpt' => false,
                'category' => 'politik',
            ],
            'sort' => 0,
        ],
        [
            'area' => 'main',
            'type' => 'category',
            'config' => [
                'limit' => 6,
                'style' => 'carousel',
                'columns' => 3,
                'show_image' => true,
                'show_excerpt' => false,
                'category' => 'hankam',
            ],
            'sort' => 1,
        ],
        [
            'area' => 'main',
            'type' => 'category',
            'config' => [
                'limit' => 6,
                'style' => 'carousel',
                'columns' => 3,
                'show_image' => true,
                'show_excerpt' => false,
                'category' => 'hukum',
            ],
            'sort' => 2,
        ],
        [
            'area' => 'main_middle',
            'type' => 'category',
            'config' => [
                'limit' => 5,
                'style' => 'carousel',
                'columns' => 3,
                'show_image' => true,
                'show_excerpt' => false,
                'category' => 'ekonomi',
            ],
            'sort' => 0,
        ],
        // [
        //     'area' => 'sidebar',
        //     'type' => 'single',
        //     'config' => [
        //         'article' => 'mengapa-rayatimes-ada',
        //     ],
        //     'sort' => 0,
        // ],
        [
            'area' => 'sidebar',
            'type' => 'newsletter',
            'config' => [
                'description' => '',
            ],
            'sort' => 2,
        ],
        [
            'area' => 'sidebar',
            'type' => 'popular',
            'config' => [
                'limit' => 10,
                'style' => 'list',
                'show_image' => true,
            ],
            'sort' => 3,
        ],
    ],
    'share_wording' => 'Download aplikasi RayaTimes untuk update berita terbaru.',
    'home_banners' => [
        [
            'title' => 'RayaTimes Insight',
            'image' => '/dummy/banner/rayatimes-insight.webp',
            'alt' => 'RayaTimes Insight: menelusuri fakta, menemukan makna',
            'url' => 'https://insight.rayatimes.id/',
            'width' => 900,
            'height' => 277,
        ],
        [
            'title' => 'Unduh aplikasi RayaTimes',
            'image' => '/dummy/banner/aplikasi-rayatimes.webp',
            'alt' => 'Berita terkini, langsung di genggaman. RayaTimes kini tersedia untuk Android dan iOS.',
            'url' => 'https://rayatimes.id/android',
            'width' => 1800,
            'height' => 554,
        ],
    ],
    // Banner foto dengan teks di atasnya (Wacana dan Lensa Mobile). Foto banner Tajuk diambil dari artikel Tajuk terbaru.
    'insight_banner' => [
        'title' => 'RayaTimes Insight',
        'text' => 'Menelusuri Fakta. Menemukan Makna.',
        'image' => '/dummy/banner/insight-cover.webp',
    ],
    'tajuk_banner' => [
        'title' => 'Tajuk',
        'text' => 'Opini dan Pandangan Editorial Redaksi.',
    ],
    // Urutan kanal di beranda (hasil meeting 28/10/2026): setelah Terkini.
    'home_sections' => [
        'ekonomi',
        'politik',
        'hukum',
        'hankam',
    ],
];
