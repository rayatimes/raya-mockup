<?php

namespace Tests\Feature;

use PHPUnit\Framework\Attributes\TestWith;
use Tests\TestCase;

class PreventSearchIndexingTest extends TestCase
{
    private const DIRECTIVES = 'noindex, nofollow, noarchive, nosnippet, noimageindex';

    #[TestWith(['/default'])]
    #[TestWith(['/lensa'])]
    #[TestWith(['/wacana'])]
    #[TestWith(['/default-mobile'])]
    #[TestWith(['/lensa-mobile'])]
    #[TestWith(['/wacana-mobile'])]
    #[TestWith(['/default/_mock/articles?per=1'])]
    #[TestWith(['/up'])]
    public function test_successful_response_forbids_indexing(string $path): void
    {
        $response = $this->get($path);

        $response->assertOk();
        $response->assertHeader('X-Robots-Tag', self::DIRECTIVES);
    }

    public function test_not_found_page_forbids_indexing(): void
    {
        $response = $this->get('/default/tidak-ada');

        $response->assertNotFound();
        $response->assertHeader('X-Robots-Tag', self::DIRECTIVES);
    }

    public function test_redirect_forbids_indexing(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/default');
        $response->assertHeader('X-Robots-Tag', self::DIRECTIVES);
    }

    public function test_robots_file_disallows_all_crawlers_from_whole_site(): void
    {
        $rules = array_map('trim', file(public_path('robots.txt'), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES));

        $this->assertSame(['User-agent: *', 'Disallow: /'], $rules);
    }
}
