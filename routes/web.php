<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\ToolController;
use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BlogController as AdminBlogController;
use App\Http\Controllers\Admin\PageSeoController as AdminPageSeoController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Tool Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [ToolController::class, 'home'])->name('home');
Route::prefix('tools')->group(function () {
    Route::get('/image-crop',       [ToolController::class, 'imageCrop'])->name('tools.image-crop');
    Route::get('/image-bg-remover', [ToolController::class, 'imageBgRemover'])->name('tools.image-bg-remover');
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
| Sitemap & Robots
|--------------------------------------------------------------------------
*/
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
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

        // Page SEO management
        Route::get('/seo',              [AdminPageSeoController::class, 'index'])->name('seo.index');
        Route::get('/seo/{key}/edit',   [AdminPageSeoController::class, 'edit'])->name('seo.edit');
        Route::put('/seo/{key}',        [AdminPageSeoController::class, 'update'])->name('seo.update');
    });
});

