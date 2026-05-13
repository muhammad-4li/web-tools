<?php

namespace App\Http\Controllers;

use App\Models\PageSeo;
use Inertia\Inertia;
use Inertia\Response;

class ToolController extends Controller
{
    private function seo(string $page): array
    {
        $db     = PageSeo::where('page_key', $page)->first();
        $config = config("seo.pages.{$page}", []);

        return [
            'title'         => $db?->title         ?: ($config['title'] ?? ''),
            'description'   => $db?->description   ?: ($config['description'] ?? ''),
            'keywords'      => $db?->keywords       ?: ($config['keywords'] ?? ''),
            'og_image'      => $db?->og_image       ?: ($config['og_image'] ?? config('seo.default_og')),
            'canonical_url' => $db?->canonical_url  ?: null,
            'robots'        => $db?->robots         ?: 'index, follow',
        ];
    }

    public function home(): Response
    {
        return Inertia::render('Home', ['seo' => $this->seo('home')]);
    }

    public function imageCrop(): Response
    {
        return Inertia::render('Tools/ImageCrop', ['seo' => $this->seo('image-crop')]);
    }

    public function imageBgRemover(): Response
    {
        return Inertia::render('Tools/ImageBgRemover', ['seo' => $this->seo('image-bg-remover')]);
    }

    public function pdfMerge(): Response
    {
        return Inertia::render('Tools/PdfMerge', ['seo' => $this->seo('pdf-merge')]);
    }

    public function pdfSign(): Response
    {
        return Inertia::render('Tools/PdfSign', ['seo' => $this->seo('pdf-sign')]);
    }

    public function pdfText(): Response
    {
        return Inertia::render('Tools/PdfText', ['seo' => $this->seo('pdf-text')]);
    }

    public function textEditor(): Response
    {
        return Inertia::render('Tools/TextEditor', ['seo' => $this->seo('text-editor')]);
    }
}
