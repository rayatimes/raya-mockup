<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class MobileTemplateTest extends TestCase
{
    private const ARTICLE = '/nusantara/114-korban-virgo-transport-8-ditemukan-129-masih-dicari';

    /**
     * @return array<string, array{string}>
     */
    public static function templates(): array
    {
        return [
            'default' => ['default-mobile'],
            'lensa' => ['lensa-mobile'],
            'wacana' => ['wacana-mobile'],
        ];
    }

    #[DataProvider('templates')]
    public function test_path_renders_device_shell_with_home_screen(string $template): void
    {
        $response = $this->get("/{$template}");

        $response->assertOk();
        $response->assertViewIs("{$template}::shell");
        $response->assertSee('data-device', false);
        $response->assertSeeInOrder(['data-drawer-open', 'bar-logo', 'Cari berita', 'data-theme-toggle'], false);
        $response->assertDontSee('Tata letak');
    }

    #[DataProvider('templates')]
    public function test_screen_fragment_renders_without_shell(string $template): void
    {
        $response = $this->get("/{$template}/profil?_screen=1");

        $response->assertOk();
        $response->assertViewIs("{$template}::screens.profile");
        $response->assertDontSee('<html', false);
    }

    #[DataProvider('templates')]
    public function test_home_has_only_three_menu_items(string $template): void
    {
        $html = $this->get("/{$template}?_screen=1")->assertOk()->getContent();

        preg_match_all('/data-pg="([a-z-]+)"/', $html, $matches);

        $this->assertSame(['terbaru', 'unggulan', 'untuk-anda'], $matches[1]);
    }

    #[DataProvider('templates')]
    public function test_home_opens_on_featured_tab(string $template): void
    {
        $response = $this->get("/{$template}?_screen=1");

        $response->assertOk();
        $response->assertSee('data-pg="unggulan" aria-selected="true"', false);
        $response->assertSee('data-start="unggulan"', false);
        $response->assertDontSee('data-pg="terbaru" aria-selected="true"', false);
    }

    #[DataProvider('templates')]
    public function test_featured_tab_has_five_channels_with_one_card_and_five_rows_linking_to_channel_page(string $template): void
    {
        $html = $this->get("/{$template}?_screen=1")->assertOk()->getContent();

        $featured = substr($html, strpos($html, 'data-page="unggulan"'), strpos($html, 'data-page="untuk-anda"') - strpos($html, 'data-page="unggulan"'));
        $sections = array_slice(preg_split('/<section class="featured"/', $featured), 1);

        $this->assertCount(5, $sections);
        foreach (['politik', 'ekonomi', 'hankam', 'hukum', 'analisis'] as $i => $slug) {
            $this->assertSame(1, substr_count($sections[$i], 'class="lead"'), $slug);
            $this->assertSame(5, substr_count($sections[$i], '<article class="row'), $slug);
            $this->assertStringContainsString("href=\"/{$template}/@{$slug}\" class=\"featured-more\"", $sections[$i]);
        }
    }

    #[DataProvider('templates')]
    public function test_article_follows_requested_order_without_social_features(string $template): void
    {
        $response = $this->get("/{$template}".self::ARTICLE.'?_screen=1');

        $response->assertOk();
        $response->assertSeeInOrder(['bar-article', 'bar-start', 'data-back', 'bar-logo', 'bar-end', 'Simpan berita', 'Ukuran teks', 'Bagikan'], false);
        $response->assertSeeInOrder(['data-pill', 'data-pill-play', 'data-pill-percent', 'data-pill-voice', 'data-pill-toggle'], false);
        $response->assertSeeInOrder(['article-cover', '<figcaption', 'article-title', 'class="attr"', 'data-body', 'data-speech-skip', 'class="tags"', 'class="related"'], false);
        foreach (['Suka', 'Komentar', 'Ikuti', 'Pengikut', 'pengikut'] as $removed) {
            $response->assertDontSee(">{$removed}<", false);
        }
    }

    #[DataProvider('templates')]
    public function test_article_attribute_shows_date_as_day_month_year_and_time(string $template): void
    {
        $response = $this->get("/{$template}".self::ARTICLE.'?_screen=1');

        $this->assertMatchesRegularExpression('/\d{1,2} [A-Z][a-z]+ \d{4} \| \d{2}:\d{2}(?! WIB)/', $response->getContent());
        $this->assertDoesNotMatchRegularExpression('/(Senin|Selasa|Rabu|Kamis|Jumat|Sabtu|Minggu), \d{2} [A-Z][a-z]+ \d{4} \|/', $response->getContent());
    }

    #[DataProvider('templates')]
    public function test_article_links_stay_inside_mobile_template(string $template): void
    {
        $response = $this->get("/{$template}".self::ARTICLE.'?_screen=1');

        $response->assertSee("href=\"/{$template}/@nusantara\"", false);
        $response->assertDontSee('href="/@nusantara"', false);
    }

    #[DataProvider('templates')]
    public function test_profile_and_author_have_no_follow_system(string $template): void
    {
        $profile = $this->get("/{$template}/profil?_screen=1");
        $author = $this->get("/{$template}/u/tejo-t?_screen=1");

        $profile->assertOk();
        $profile->assertSee('Riwayat Membaca');
        $profile->assertSee('Minat Topik');
        $author->assertOk();
        foreach (['Pengikut', 'pengikut', 'Mengikuti', 'Yang Anda Ikuti', 'Tata Letak'] as $removed) {
            $profile->assertDontSee($removed);
            $author->assertDontSee($removed);
        }
    }

    #[DataProvider('templates')]
    public function test_for_you_puts_most_read_channel_first(string $template): void
    {
        $response = $this->get("/{$template}/_for-you?activity=hukum,ekonomi&interests=politik");

        $response->assertOk();
        $response->assertSeeInOrder(['Hukum', 'Ekonomi', 'Politik']);
        $this->assertSame(2, substr_count($response->getContent(), 'Sering Anda baca'));
        $this->assertSame(1, substr_count($response->getContent(), 'Minat Anda'));
    }

    #[DataProvider('templates')]
    public function test_for_you_falls_back_for_new_reader_and_ignores_unknown_channels(string $template): void
    {
        $response = $this->get("/{$template}/_for-you?activity=tidak-ada");

        $response->assertOk();
        $response->assertSee('Berita');
        $response->assertDontSee('Sering Anda baca');
    }

    #[DataProvider('templates')]
    public function test_rows_endpoint_returns_saved_articles_and_pagination(string $template): void
    {
        $saved = $this->getJson("/{$template}/_rows?slugs=114-korban-virgo-transport-8-ditemukan-129-masih-dicari,tidak-ada");
        $page = $this->getJson("/{$template}/_rows?per=3&offset=3");

        $saved->assertOk()->assertJsonPath('total', 1);
        $this->assertStringContainsString("/{$template}".self::ARTICLE, $saved->json('html'));
        $page->assertOk()->assertJsonPath('next_offset', 6)->assertJsonPath('has_more', true);
    }

    #[DataProvider('templates')]
    public function test_unknown_page_renders_not_found_inside_device(string $template): void
    {
        $response = $this->get("/{$template}/tidak-ada");

        $response->assertNotFound();
        $response->assertViewIs("{$template}::shell");
        $response->assertSee('Halaman Tidak Ditemukan');
    }

    public function test_notifications_exclude_social_activity(): void
    {
        $response = $this->get('/default-mobile/notifikasi?_screen=1');

        $response->assertOk();
        $response->assertSee('Berita Penting');
        foreach (['mengikuti', 'mengomentari', 'menyukai'] as $social) {
            $response->assertDontSee($social);
        }
    }

    #[DataProvider('templates')]
    public function test_every_template_uses_the_main_logo_instead_of_the_portal_logo(string $template): void
    {
        foreach (["/{$template}", "/{$template}".self::ARTICLE] as $path) {
            $response = $this->get($path);

            $response->assertOk();
            $response->assertSee('rayatimes_main_', false);
            $response->assertDontSee('rayatimes_portal_', false);
        }
    }

    public function test_lensa_home_shows_banners_between_headline_and_latest_feed(): void
    {
        $response = $this->get('/lensa-mobile');

        $response->assertOk();
        $latestTajuk = $response->viewData('rt')->inCategory('tajuk')[0];
        $response->assertSeeInOrder(['class="hero"', 'class="home-banners"', '/dummy/banner/insight-cover.webp', 'RayaTimes Insight', 'href="/lensa-mobile/@tajuk"', $latestTajuk['image']['lg'], '>Tajuk<', 'Opini dan Pandangan Editorial Redaksi.', 'ri-arrow-right-up-line home-banner-visit', 'data-scope="latest"'], false);
        $response->assertDontSee('aplikasi-rayatimes', false);
    }

    public function test_lensa_article_header_no_longer_floats_over_the_cover(): void
    {
        $response = $this->get('/lensa-mobile'.self::ARTICLE.'?_screen=1');

        $response->assertOk();
        $response->assertSeeInOrder(['class="bar bar-article"', 'data-scroll', 'article-cover'], false);
        $response->assertDontSee('data-solid-at', false);
    }
}
