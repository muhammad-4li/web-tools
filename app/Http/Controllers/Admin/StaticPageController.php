<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StaticPageController extends Controller
{
    private array $pages = [
        'about'   => 'About Us',
        'contact' => 'Contact',
        'privacy' => 'Privacy Policy',
    ];

    public function index(): Response
    {
        $records = StaticPage::whereIn('key', array_keys($this->pages))
            ->get()
            ->keyBy('key');

        $pages = collect($this->pages)->map(fn (string $label, string $key) => [
            'key'   => $key,
            'label' => $label,
            'title' => $records->get($key)?->title ?? '—',
        ])->values();

        return Inertia::render('Admin/Pages/Index', ['pages' => $pages]);
    }

    public function edit(string $key): Response
    {
        abort_unless(array_key_exists($key, $this->pages), 404);

        $page = StaticPage::findByKey($key);

        return Inertia::render('Admin/Pages/Form', [
            'pageKey'   => $key,
            'pageLabel' => $this->pages[$key],
            'page'      => $page ? [
                'title'            => $page->title            ?? '',
                'content'          => $page->content          ?? '',
                'meta_title'       => $page->meta_title       ?? '',
                'meta_description' => $page->meta_description ?? '',
                'meta_keywords'    => $page->meta_keywords    ?? '',
                'og_image'         => $page->og_image         ?? '',
                'robots'           => $page->robots           ?? 'index, follow',
            ] : null,
        ]);
    }

    public function update(Request $request, string $key): RedirectResponse
    {
        abort_unless(array_key_exists($key, $this->pages), 404);

        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'content'          => 'required|string',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords'    => 'nullable|string|max:500',
            'og_image'         => 'nullable|string|max:255',
            'robots'           => 'nullable|string|max:100',
        ]);

        StaticPage::updateOrCreate(['key' => $key], $data);

        return redirect()->route('admin.pages.index')
            ->with('success', "\"{$this->pages[$key]}\" page saved.");
    }
}
