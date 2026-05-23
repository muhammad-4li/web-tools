<?php

use App\Http\Controllers\AdsTxtController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\Admin\AdsController as AdminAdsController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\PageSeoController as AdminPageSeoController;
use App\Http\Controllers\Admin\StaticPageController as AdminStaticPageController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Tool Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ToolController::class, 'home'])->name('home');
Route::prefix('tools')->group(function () {
    Route::get('/image-crop',        [ToolController::class, 'imageCrop'])->name('tools.image-crop');
    Route::get('/image-crop-circle', [ToolController::class, 'imageCropCircle'])->name('tools.image-crop-circle');
    Route::get('/image-bg-remover',  [ToolController::class, 'imageBgRemover'])->name('tools.image-bg-remover');
    Route::get('/pdf-merge',        [ToolController::class, 'pdfMerge'])->name('tools.pdf-merge');
    Route::get('/pdf-sign',         [ToolController::class, 'pdfSign'])->name('tools.pdf-sign');
    Route::get('/pdf-text',         [ToolController::class, 'pdfText'])->name('tools.pdf-text');
    Route::get('/text-editor',      [ToolController::class, 'textEditor'])->name('tools.text-editor');
});

/*
|--------------------------------------------------------------------------
| Blog Routes
|--------------------------------------------------------------------------
*/
Route::prefix('blog')->group(function () {
    Route::get('/',        [BlogController::class, 'index'])->name('blog.index');
    Route::get('/{slug}',  [BlogController::class, 'show'])->name('blog.show');
});

/*
|--------------------------------------------------------------------------
| Static Pages (About / Contact / Privacy)
|--------------------------------------------------------------------------
*/
Route::get('/about',   [StaticPageController::class, 'about'])->name('about');
Route::get('/contact', [StaticPageController::class, 'contact'])->name('contact');
Route::get('/privacy', [StaticPageController::class, 'privacy'])->name('privacy');

/*
|--------------------------------------------------------------------------
| Sitemap & Robots
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/ads.txt',    AdsTxtController::class)->name('ads-txt');
Route::get('/robots.txt', function () {
    $base = config('app.url');
    return response(
        "User-agent: *\nAllow: /\nDisallow: /admin/\nSitemap: {$base}/sitemap.xml\n",
        200,
        ['Content-Type' => 'text/plain']
    );
})->name('robots');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login',  [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');

    Route::middleware(AdminMiddleware::class)->group(function () {
        Route::post('/logout',   [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/',          [AdminAuthController::class, 'dashboard'])->name('dashboard');
        Route::resource('blog',  AdminBlogController::class)->except(['show']);
        Route::patch('blog/{blog}/toggle-status', [AdminBlogController::class, 'toggleStatus'])->name('blog.toggle-status');

        // Page SEO management
        Route::get('/seo',              [AdminPageSeoController::class, 'index'])->name('seo.index');
        Route::get('/seo/{key}/edit',   [AdminPageSeoController::class, 'edit'])->name('seo.edit');
        Route::put('/seo/{key}',        [AdminPageSeoController::class, 'update'])->name('seo.update');

        // Static Pages (About / Contact / Privacy)
        Route::get('/pages',              [AdminStaticPageController::class, 'index'])->name('pages.index');
        Route::get('/pages/{key}/edit',   [AdminStaticPageController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{key}',        [AdminStaticPageController::class, 'update'])->name('pages.update');

        // Ads Settings
        Route::get('/ads', [AdminAdsController::class, 'edit'])->name('ads.edit');
        Route::put('/ads', [AdminAdsController::class, 'update'])->name('ads.update');

        // Profile
        Route::get('/profile',           [AdminProfileController::class, 'show'])->name('profile');
        Route::put('/profile',           [AdminProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password',  [AdminProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

