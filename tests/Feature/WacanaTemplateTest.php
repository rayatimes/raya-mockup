<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class WacanaTemplateTest extends TestCase
{
    /**
     * @return array<string, array{string}>
     */
    public static function categorySlugs(): array
    {
        $categories = require dirname(__DIR__, 2).'/resources/data/rayatimes/categories.php';

        return collect($categories)
            ->mapWithKeys(fn (array $category): array => [$category['slug'] => [$category['slug']]])
            ->all();
    }

    #[DataProvider('categorySlugs')]
    public function test_each_category_page_shows_its_own_illustration(string $slug): void
    {
        $this->assertTrue(view()->exists("wacana::partials.kanal-art.{$slug}"), "Ilustrasi kanal {$slug} belum ada.");

        $response = $this->get("/wacana/@{$slug}");

        $response->assertOk();
        $response->assertSee('class="wc-art absolute', false);
    }

    public function test_dropdown_lists_only_sub_categories_behind_a_separate_toggle(): void
    {
        $response = $this->get('/wacana');

        $response->assertOk();
        $response->assertDontSee('Semua Berita');
        $response->assertSee('href="/wacana/@berita"', false);
        $response->assertSeeInOrder(['data-dropdown-toggle', 'href="/wacana/@nasional"', 'href="/wacana/@internasional"'], false);
    }

    #[DataProvider('pagesWithSidebar')]
    public function test_sidebar_shows_popular_above_trending(string $path): void
    {
        $response = $this->get($path);

        $response->assertOk();
        $response->assertSeeInOrder(['Paling banyak dibaca', 'Sedang tren']);
    }

    /**
     * @return array<string, array{string}>
     */
    public static function pagesWithSidebar(): array
    {
        return [
            'beranda' => ['/wacana'],
            'kanal' => ['/wacana/@ekonomi'],
        ];
    }

    public function test_article_shows_read_also_in_body_and_home_sidebar_on_the_right(): void
    {
        $rt = $this->get('/wacana')->viewData('rt');
        $articlesWithRelated = array_values(array_filter($rt->articles(), fn (array $article): bool => $rt->related($article, 1) !== []));
        usort($articlesWithRelated, fn (array $a, array $b): int => count($rt->contentBlocks($a)) <=> count($rt->contentBlocks($b)));

        foreach ([$articlesWithRelated[0], end($articlesWithRelated)] as $article) {
            $response = $this->get($article['url']);

            $response->assertOk();
            $response->assertSeeInOrder(['data-article-body', 'Baca juga', 'Paling banyak dibaca', 'Sedang tren'], false);
        }
    }

    public function test_read_also_never_directly_follows_a_subheading(): void
    {
        $rt = $this->get('/wacana')->viewData('rt');

        foreach (array_slice($rt->articles(), 0, 40) as $article) {
            $this->assertDoesNotMatchRegularExpression('/<\/h[1-6]>\s*<aside data-card/i', $this->get($article['url'])->assertOk()->getContent());
        }
    }

    public function test_home_section_dividers_do_not_use_category_colors(): void
    {
        $response = $this->get('/wacana');

        $response->assertOk();
        $response->assertSee('border-t-[3px] border-ink', false);
        $response->assertDontSee('border-t-[6px]', false);
    }

    public function test_home_banners_show_insight_cover_and_tajuk_channel_with_latest_thumbnail(): void
    {
        $response = $this->get('/wacana');

        $response->assertOk();
        $latestTajuk = $response->viewData('rt')->inCategory('tajuk')[0];
        $response->assertSeeInOrder(['aria-label="Banner"', 'https://insight.rayatimes.id/', '/dummy/banner/insight-cover.webp', 'RayaTimes Insight', 'href="/wacana/@tajuk"', $latestTajuk['image']['lg'], '>Tajuk<', 'Opini dan Pandangan Editorial Redaksi.', 'ri-arrow-right-up-line'], false);
        $response->assertDontSee('aplikasi-rayatimes', false);
    }

    public function test_tajuk_uses_insight_background_with_one_thumbnail_and_a_list(): void
    {
        $this->assertFileExists(public_path('dummy/banner/insight-cover.webp'));

        $tajukSection = str($this->get('/wacana')->assertOk()->getContent())
            ->after('wc-tajuk')
            ->before('</section>');

        $this->assertStringContainsString('dummy/banner/insight-cover.webp', $tajukSection);
        $this->assertSame(1, substr_count($tajukSection, '<img'));
        $this->assertGreaterThan(0, substr_count($tajukSection, '<li '));
    }
}
