<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LanguageMiddleware;

Route::redirect('/', '/id');

Route::get('/{page}', function ($page) {
    if (in_array($page, ['insight', 'news', 'about'])) {
        return redirect("/id/{$page}");
    }
    abort(404);
})->where('page', 'insight|news|about');

Route::pattern('locale', 'en|id');

Route::middleware([LanguageMiddleware::class])
    ->prefix('{locale}')
    ->group(function () {
        Route::get('/', fn () => view('index'))->name('home');
        Route::get('/insight', fn () => view('insight'))->name('insight');
        Route::get('/news', fn () => view('news'))->name('news');
        Route::get('/about', fn () => view('about'))->name('about');
    });

Route::get('/insight/{locale}', fn ($locale) => redirect("/{$locale}/insight"))->where('locale', 'en|id');
Route::get('/news/{locale}', fn ($locale) => redirect("/{$locale}/news"))->where('locale', 'en|id');
Route::get('/about/{locale}', fn ($locale) => redirect("/{$locale}/about"))->where('locale', 'en|id');
Route::get('/index/{locale}', fn ($locale) => redirect("/{$locale}"))->where('locale', 'en|id');
