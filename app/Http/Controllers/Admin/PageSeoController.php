<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageSeo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageSeoController extends Controller
{
    /** All manageable pages: key => human label */
    private array $pages = [
        'home'              => 'Home Page',
        'image-crop'        => 'Image Crop Tool',
        'image-bg-remover'  => 'Background Remover',
        'pdf-merge'         => 'PDF Merge',
        'pdf-sign'          => 'PDF Sign',
        'pdf-text'          => 'PDF Text',
        'text-editor'       => 'Text Editor',
        'blog'              => 'Blog Listing',
    ];

    public function index(): Response
    {
        $dbRecords = PageSeo::whereIn('page_key', array_keys($this->pages))
            ->get()
            ->keyBy('page_key');

        $pages = collect($this->pages)->map(function (string $label, string $key) use ($dbRecords) {
            $config = config("seo.pages.{$key}", []);
            $db     = $dbRecords->get($key);

            return [
                'key'          => $key,
                'label'        => $label,
                'title'        => $db?->title ?: ($config['title'] ?? '—'),
                'robots'       => $db?->robots ?? 'index, follow',
                'has_override' => (bool) $db,
            ];
        })->values();

        return Inertia::render('Admin/Seo/Index', ['pages' => $pages]);
    }

    public function edit(string $key): Response
    {
        abort_unless(array_key_exists($key, $this->pages), 404);

        $db     = PageSeo::where('page_key', $key)->first();
        $config = config("seo.pages.{$key}", []);

        return Inertia::render('Admin/Seo/Form', [
            'pageKey'   => $key,
            'pageLabel' => $this->pages[$key],
            'seo'       => [
                'title'         => $db?->title         ?? ($config['title'] ?? ''),
                'description'   => $db?->description   ?? ($config['description'] ?? ''),
                'keywords'      => $db?->keywords       ?? ($config['keywords'] ?? ''),
                'og_image'      => $db?->og_image       ?? ($config['og_image'] ?? ''),
                'canonical_url' => $db?->canonical_url  ?? '',
                'robots'        => $db?->robots         ?? 'index, follow',
            ],
            'defaults'  => $config,
        ]);
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        abort_unless(array_key_exists($key, $this->pages), 404);

        $data = $request->validate([
            'title'         => 'nullable|string|max:255',
            'description'   => 'nullable|string|max:320',
            'keywords'      => 'nullable|string|max:500',
            'og_image'      => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url|max:255',
            'robots'        => 'nullable|string|max:100',
        ]);

        PageSeo::updateOrCreate(['page_key' => $key], $data);

        return redirect()->route('admin.seo.index')
            ->with('success', "SEO for \"{$this->pages[$key]}\" saved.");
    }
}
