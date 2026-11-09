<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventSearchIndexing
{
    /**
     * Melarang mesin pencari mengindeks semua respons aplikasi, termasuk 404 dan pengalihan.
     *
     * File statis dilayani web server tanpa melewati Laravel, jadi dicakup oleh public/robots.txt.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet, noimageindex');

        return $response;
    }
}
