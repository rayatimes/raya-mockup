<?php

/*
 * Rute mockup redesign portal RayaTimes.
 *
 * Murni frontend: data diambil dari resources/data/rayatimes (dummy dari backup), tanpa DB.
 * Setiap template berdiri sendiri di resources/templates/{nama} (views, css, js), didaftarkan
 * sebagai namespace view + komponen anonim dengan nama yang sama, dan disajikan di path /{nama}.
 */

use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use RayaTimes\Mockup\Repository;

require_once resource_path('data/rayatimes/Repository.php');

$templates = [
    'default' => ['name' => 'RayaTimes', 'note' => 'Template resmi — The News Frame'],
    'lensa' => ['name' => 'Lensa', 'note' => 'Visual-first — The Story Frame'],
    'wacana' => ['name' => 'Wacana', 'note' => 'Konteks & opini — The Editorial'],
];

/*
 * Mockup aplikasi mobile: layar iPhone di dalam bingkai perangkat, satu template per path.
 * Setiap layar bisa dimuat utuh (shell + layar) atau sebagai potongan lewat ?_screen=1,
 * yang dipakai JS untuk menumpuk layar seperti navigasi aplikasi.
 */
$mobileTemplates = [
    'default-mobile' => ['name' => 'Default', 'note' => 'Mengikuti aplikasi yang berjalan'],
    'lensa-mobile' => ['name' => 'Lensa', 'note' => 'Foto sebagai pembawa cerita'],
    'wacana-mobile' => ['name' => 'Wacana', 'note' => 'Konteks dan warna kanal'],
];

foreach (array_keys($templates + $mobileTemplates) as $key) {
    View::addNamespace($key, resource_path("templates/{$key}/views"));
    Blade::anonymousComponentPath(resource_path("templates/{$key}/views/components"), $key);
}

Route::withoutMiddleware([StartSession::class, ShareErrorsFromSession::class, PreventRequestForgery::class])
    ->group(function () use ($templates, $mobileTemplates) {
        Route::redirect('/', '/default')->name('mockup.home');

        foreach (array_keys($mobileTemplates) as $template) {
            $repository = fn (): Repository => Repository::forTemplate($template);

            /**
             * Render satu layar aplikasi: potongan untuk JS (?_screen=1) atau utuh dengan shell.
             *
             * @param  array<string, mixed>  $data
             */
            $render = function (string $screen, array $data = [], int $status = 200) use ($template, $mobileTemplates, $repository) {
                $shared = [
                    'rt' => $repository(),
                    'template' => $template,
                    'templates' => $mobileTemplates,
                    'screen' => $screen,
                    'member' => ['name' => 'Rani Prameswari', 'username' => 'raniprameswari', 'email' => 'rani@contoh.id', 'bio' => 'Pembaca setia berita ekonomi dan kebijakan publik.'],
                ];
                $view = request()->boolean('_screen') ? "{$template}::screens.{$screen}" : "{$template}::shell";

                return response()->view($view, $data + $shared, $status);
            };

            $notFound = fn () => $render('not-found', [], 404);

            Route::prefix($template)->name("mockup.{$template}.")->group(function () use ($render, $notFound, $repository, $template) {
                Route::get('/', fn () => $render('home'))->name('home');
                Route::get('/tersimpan', fn () => $render('saved'))->name('saved');
                Route::get('/notifikasi', fn () => $render('notifications'))->name('notifications');
                Route::get('/profil', fn () => $render('profile'))->name('profile');
                Route::get('/profil/edit', fn () => $render('profile-edit'))->name('profile.edit');
                Route::get('/riwayat', fn () => $render('history'))->name('history');
                Route::get('/minat', fn () => $render('interests'))->name('interests');
                Route::get('/search', fn () => $render('search'))->name('search');

                // Baris berita untuk gulir tanpa batas, Tersimpan, dan Riwayat.
                Route::get('/_rows', function (Request $request) use ($repository, $template) {
                    $rt = $repository();
                    $slugs = array_filter(explode(',', (string) $request->query('slugs', '')));
                    $list = $slugs
                        ? array_values(array_filter(array_map(fn (string $slug) => $rt->article($slug), $slugs)))
                        : $rt->scope((string) $request->query('scope', 'latest'));
                    $exclude = array_filter(explode(',', (string) $request->query('exclude', '')));
                    if ($exclude) {
                        $list = array_values(array_filter($list, fn (array $article) => ! in_array($article['slug'], $exclude, true)));
                    }
                    $per = min(50, max(1, (int) $request->query('per', 10)));
                    $offset = max(0, (int) $request->query('offset', 0));
                    $items = array_slice($list, $offset, $per);

                    return response()->json([
                        'html' => view("{$template}::partials.rows", [
                            'rt' => $rt,
                            'items' => $items,
                            'offset' => $offset,
                            'bands' => $request->boolean('bands'),
                            'lastBand' => (string) $request->query('last_band', ''),
                        ])->render(),
                        'last_band' => $items ? $rt->dateBand(end($items)['published']) : '',
                        'next_offset' => $offset + count($items),
                        'has_more' => $offset + count($items) < count($list),
                        'total' => count($list),
                    ]);
                })->name('rows');

                // Untuk Anda: kanal disusun JS dari riwayat baca dan minat di perangkat.
                Route::get('/_for-you', function (Request $request) use ($repository, $template) {
                    return view("{$template}::partials.for-you", [
                        'rt' => $repository(),
                        'activity' => array_filter(explode(',', (string) $request->query('activity', ''))),
                        'interests' => array_filter(explode(',', (string) $request->query('interests', ''))),
                    ]);
                })->name('for-you');

                Route::get('/_search', function (Request $request) use ($repository, $template) {
                    return view("{$template}::partials.search-results", [
                        'rt' => $repository(),
                        'query' => trim((string) $request->query('q', '')),
                    ]);
                })->name('search.results');

                Route::get('/tag/{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $tag = $repository()->tag($slug);

                    return $tag ? $render('topic', ['kind' => 'tag', 'topic' => $tag]) : $notFound();
                })->name('tag');
                Route::get('/u/{username}', function (string $username) use ($render, $notFound, $repository) {
                    $author = $repository()->author($username);

                    return $author ? $render('author', ['author' => $author]) : $notFound();
                })->name('author');
                Route::get('/p/{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $static = $repository()->page($slug);

                    return $static ? $render('page', ['static' => $static]) : $notFound();
                })->name('page');
                Route::get('/@{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $category = $repository()->category($slug);

                    return $category ? $render('topic', ['kind' => 'category', 'topic' => $category]) : $notFound();
                })->name('category');
                Route::get('/{category}/{slug}', function (string $category, string $slug) use ($render, $notFound, $repository) {
                    $article = $repository()->article($slug);

                    return $article ? $render('article', ['article' => $article]) : $notFound();
                })->where('category', '[a-z0-9-]+')->name('article');

                Route::get('/{path}', $notFound)->where('path', '.*')->name('not-found');
            });
        }

        foreach (array_keys($templates) as $template) {
            $repository = fn (): Repository => Repository::forTemplate($template);

            /**
             * Render halaman milik template ini.
             *
             * @param  array<string, mixed>  $data
             */
            $render = function (string $page, array $data = [], int $status = 200) use ($template, $templates, $repository) {
                $shared = [
                    'rt' => $repository(),
                    'template' => $template,
                    'templates' => $templates,
                    'page' => $page,
                ];

                return response()->view("{$template}::pages.{$page}", $data + $shared, $status);
            };

            $notFound = fn () => $render('not-found', [], 404);

            Route::prefix($template)->name("mockup.{$template}.")->group(function () use ($render, $notFound, $repository) {
                Route::get('/', fn () => $render('home'))->name('home');

                // Endpoint JSON untuk "muat lebih banyak" & pencarian instan (dirender ulang oleh JS tiap template).
                Route::get('/_mock/articles', function (Request $request) use ($repository) {
                    $rt = $repository();
                    $list = $rt->scope((string) $request->query('scope', 'latest'));
                    $exclude = array_filter(explode(',', (string) $request->query('exclude', '')));
                    if ($exclude) {
                        $list = array_values(array_filter($list, fn ($a) => ! in_array($a['slug'], $exclude, true)));
                    }
                    $per = min(30, max(1, (int) $request->query('per', 10)));
                    $offset = max(0, (int) $request->query('offset', 0));
                    $items = array_slice($list, $offset, $per);

                    return response()->json([
                        'data' => array_map(fn ($a) => $rt->summary($a), $items),
                        'next_offset' => $offset + count($items),
                        'has_more' => $offset + count($items) < count($list),
                        'total' => count($list),
                    ]);
                })->name('articles');

                Route::get('/index', fn () => $render('index'))->name('index');
                Route::get('/search', fn (Request $request) => $render('search', ['query' => trim((string) $request->query('q', '')), 'tab' => (string) $request->query('tab', 'berita')]))->name('search');
                Route::get('/tag', fn () => $render('tags'))->name('tags');
                Route::get('/tag/{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $tag = $repository()->tag($slug);

                    return $tag ? $render('tag', ['tag' => $tag]) : $notFound();
                })->name('tag');
                Route::get('/u/{username}', function (string $username) use ($render, $notFound, $repository) {
                    $author = $repository()->author($username);

                    return $author ? $render('author', ['author' => $author]) : $notFound();
                })->name('author');
                Route::get('/p/{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $static = $repository()->page($slug);

                    return $static ? $render('static', ['static' => $static]) : $notFound();
                })->name('page');
                Route::get('/podcast', fn () => $render('podcast'))->name('podcast');
                Route::get('/media', fn () => $render('media'))->name('media');
                Route::get('/media/photo/{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $item = $repository()->mediaItem($slug);

                    return $item ? $render('media-detail', ['item' => $item]) : $notFound();
                })->name('media.photo');
                Route::get('/@{slug}', function (string $slug) use ($render, $notFound, $repository) {
                    $category = $repository()->category($slug);

                    return $category ? $render('category', ['category' => $category]) : $notFound();
                })->name('category');
                Route::get('/{category}/{slug}', function (string $category, string $slug) use ($render, $notFound, $repository) {
                    $article = $repository()->article($slug);

                    return $article ? $render('article', ['article' => $article]) : $notFound();
                })->where('category', '[a-z0-9-]+')->name('article');

                Route::get('/{path}', $notFound)->where('path', '.*')->name('not-found');
            });
        }

        // URL lama tanpa prefix template diarahkan ke template Default.
        Route::fallback(fn (Request $request) => redirect('/default'.$request->getRequestUri()));
    });
