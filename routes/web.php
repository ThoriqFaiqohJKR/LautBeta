<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\LanguageMiddleware;
use App\Http\Middleware\cmslogin;
use App\Http\Controllers\ControllerCms;
use App\Http\Controllers\PageController;
use App\Http\Middleware\Auth as MustLogin;
use UniSharp\LaravelFilemanager\Lfm;




Route::get('/login', [ControllerCms::class, 'login'])->name('login');
Route::post('/logout', [ControllerCms::class, 'logout'])->name('logout');

Route::get('/', function () {
    return view('welcome');
})->middleware(MustLogin::class);


Route::group(['prefix' => 'laravel-filemanager', 'middleware' => [MustLogin::class]], function () {
    Lfm::routes();
});


Route::get('/', function () {
    $locale = app()->getLocale();
    return redirect("/{$locale}");
});




Route::pattern('locale', 'en|id');

Route::middleware([LanguageMiddleware::class])
    ->prefix('{locale}')
    ->where(['locale' => 'en|id'])
    ->group(function () {
        Route::get('/', [PageController::class, 'home'])->name('home');

        Route::get('/bukuview/{id}', [PageController::class, 'previewjurnal'])->name('previewjournal');

        //ini about
        Route::get('/Pageabout', [PageController::class, 'about'])->name('about');
        //ini insight
        //ini Analysis
        Route::get('/PageAnalysis', [PageController::class, 'analysis'])->name('analysis');
        Route::get('/PageDetailAnalysis/{id}/{slug?}', [PageController::class, 'detailanalysis'])->name('analysis.detail');

        //ini ngopini
        Route::get('/PageDetailNgopini/{id}/{slug?}', [PageController::class, 'detailngopini'])->name('ngopini.detail');

        //ini Feature
        Route::get('/PageFeature', [PageController::class, 'feature'])->name('feature');
        Route::get('/PageDetailFeature/{id}/{slug?}', [PageController::class, 'detailfeature'])->name('feature.detail');

        //ini Detail Insight
        Route::get('/PageDetailInsight/{id}/{slug?}', [PageController::class, 'detailinsight'])->name('insight.detail');

        //ini literacy
        Route::get('/Pagegrafik', [PageController::class, 'grafik'])->name('grafik');
        Route::get('/Pagejournal', [PageController::class, 'journal'])->name('journal');

        //ini Report
        Route::get('/Pagereport', [PageController::class, 'report'])->name('report');
        Route::get('/Pagedetailreport/{id}/{slug?}', [PageController::class, 'detailreport'])->name('report.detail');


        //ini agenda
        //ini Event
        Route::get('/Pageevent', [PageController::class, 'event'])->name('event');
        Route::get('/Pagedetailevent/{id}/{slug?}', [PageController::class, 'detailevent'])->name('event.detail');

        //ini Activity
        Route::get('/Pageactivity', [PageController::class, 'activity'])->name('activity');
        Route::get('/Pagedetailactivity/{id}/{slug?}', [PageController::class, 'detailactivity'])->name('activity.detail');

        //ini resource
        Route::get('/Pagereportresource', [PageController::class, 'reportresource'])->name('reportresource');
        Route::get('/Pagedetailreportresource', [PageController::class, 'detailreportresource'])->name('reportresource.detail   ');

        Route::get('/Pagedatabase', [PageController::class, 'database'])->name('database');
        Route::get('/Pagedetaildatabase', [PageController::class, 'detaildatabase'])->name('database.detail');

        Route::get('/Pagegallery', [PageController::class, 'gallery'])->name('gallery');

        Route::get('/Pagenews', [PageController::class, 'news'])->name('news');
    });







Route::prefix('cms/{locale}')
    ->name('cms.')
    ->where(['locale' => 'en|id'])
    ->middleware([cmslogin::class . ':admin', LanguageMiddleware::class])
    ->group(function () {

        Route::get('/pageabout', [ControllerCms::class, 'pageabout'])->name('page.about');

        // insight
        Route::get('/pageinsight', [ControllerCms::class, 'pageinsight'])->name('page.insight');
        Route::get('/pageindexinsight', [ControllerCms::class, 'indexinsight'])->name('page.index.insight');
        Route::get('/pageaddinsight', [ControllerCms::class, 'addinsight'])->name('page.add.insight');
        Route::get('/pageeditinsight/{id}', [ControllerCms::class, 'editinsight'])->name('page.edit.insight');
        Route::get('/pagepreviewinsight/{id}', [ControllerCms::class, 'previewinsight'])->name('page.preview.insight');

        // literacy
        Route::get('/pageliteracy', [ControllerCms::class, 'pageliteracy'])->name('page.literacy');
        Route::get('/pageindexliteracy', [ControllerCms::class, 'indexliteracy'])->name('page.index.literacy');
        Route::get('/pageaddliteracy', [ControllerCms::class, 'addliteracy'])->name('page.add.literacy');
        Route::get('/pageeditliteracy/{id}', [ControllerCms::class, 'editliteracy'])->name('page.edit.literacy');
        Route::get('/pagepreviewliteracy/{id}', [ControllerCms::class, 'previewliteracy'])->name('page.preview.literacy');

        // agenda
        Route::get('/pageagenda', [ControllerCms::class, 'pageagenda'])->name('page.agenda');
        Route::get('/pageindexagenda', [ControllerCms::class, 'indexagenda'])->name('page.index.agenda');
        Route::get('/pageaddagenda', [ControllerCms::class, 'addagenda'])->name('page.add.agenda');
        Route::get('/pageeditagenda/{id}', [ControllerCms::class, 'editagenda'])->name('page.edit.agenda');
        Route::get('/pagepreviewagenda/{id}', [ControllerCms::class, 'previewagenda'])->name('page.preview.agenda');

        // resource
        Route::get('/pageresource', [ControllerCms::class, 'pageresource'])->name('page.resource');
        Route::get('/pageindexresource', [ControllerCms::class, 'indexresource'])->name('page.index.resource');
        Route::get('/pageaddresource', [ControllerCms::class, 'addresource'])->name('page.add.resource');
        Route::get('/pageeditresource/{id}', [ControllerCms::class, 'editresource'])->name('page.edit.resource');
        Route::get('/pagepreviewresource/{id}', [ControllerCms::class, 'previewresource'])->name('page.preview.resource');
    });
