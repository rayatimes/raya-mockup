<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class TemplatePathTest extends TestCase
{
    public function test_root_redirects_to_default_template(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/default');
    }

    #[TestWith(['default'])]
    #[TestWith(['lensa'])]
    #[TestWith(['wacana'])]
    public function test_each_template_renders_its_own_home_with_links_inside_its_path(string $template): void
    {
        $response = $this->get("/{$template}");

        $response->assertOk();
        $response->assertViewIs("{$template}::pages.home");
        $response->assertSee("href=\"/{$template}/@ekonomi\"", false);
        $response->assertSee("href=\"/{$template}/p/about-us\"", false);
        $response->assertDontSee('href="/@ekonomi"', false);
    }

    #[TestWith(['default'])]
    #[TestWith(['lensa'])]
    #[TestWith(['wacana'])]
    public function test_issue_trail_feature_is_removed(string $template): void
    {
        $page = $this->get("/{$template}/isu/di-balik-kematian-sutrimo");
        $home = $this->get("/{$template}");
        $article = $this->get("/{$template}/nasional/polisi-selidiki-kematian-sutrimo-orang-kepercayaan-febrie");

        $page->assertNotFound();
        $home->assertOk();
        $home->assertDontSee('Jejak isu');
        $home->assertDontSee('Jejak Isu');
        $article->assertOk();
        $article->assertDontSee('jejak isu');
        $article->assertDontSee('Jejak Isu');
    }

    public function test_cookie_does_not_change_template_served_by_path(): void
    {
        $response = $this->withUnencryptedCookie('rt_template', 'wacana')->get('/lensa/index');

        $response->assertViewIs('lensa::pages.index');
    }

    public function test_unprefixed_url_redirects_to_default_template_and_keeps_query(): void
    {
        $response = $this->get('/search?q=karhutla');

        $response->assertRedirect('/default/search?q=karhutla');
    }

    public function test_unknown_page_renders_not_found_of_active_template(): void
    {
        $response = $this->get('/wacana/tidak-ada');

        $response->assertNotFound();
        $response->assertViewIs('wacana::pages.not-found');
    }

    public function test_unknown_article_renders_not_found_of_active_template(): void
    {
        $response = $this->get('/lensa/sepakbola/artikel-tidak-ada');

        $response->assertNotFound();
        $response->assertViewIs('lensa::pages.not-found');
    }

    public function test_article_content_links_stay_inside_active_template(): void
    {
        $response = $this->get('/lensa/sepakbola/julian-nagelsmann-diperingatkan-tolak-tawaran-manchester-united');

        $response->assertOk();
        $response->assertSee('href="/lensa/sepakbola/3-kandidat-pengganti-michael-carrick-jika-dipecat-manchester-united"', false);
    }

    public function test_switcher_links_to_same_page_and_query_in_other_templates(): void
    {
        $response = $this->get('/lensa/search?q=karhutla');

        $response->assertOk();
        $response->assertSee('href="/default/search?q=karhutla"', false);
        $response->assertSee('href="/wacana/search?q=karhutla"', false);
    }

    public function test_articles_endpoint_returns_urls_inside_active_template(): void
    {
        $response = $this->getJson('/wacana/_mock/articles?per=1');

        $response->assertOk();
        $this->assertStringStartsWith('/wacana/', $response->json('data.0.url'));
        $this->assertStringStartsWith('/wacana/@', $response->json('data.0.category_url'));
    }
}
