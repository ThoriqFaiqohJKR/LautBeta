<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ambil locale dari segment pertama, bukan kedua
        $locale = $request->segment(1);

        if (!in_array($locale, ['en', 'id'])) {
            $locale = session('locale', 'en'); // default English
        }

        app()->setLocale($locale);
        session(['locale' => $locale]);

        return $next($request);
    }
}
