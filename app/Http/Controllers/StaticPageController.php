<?php

namespace App\Http\Controllers;

use App\Models\StaticPage;
use Inertia\Inertia;
use Inertia\Response;

class StaticPageController extends Controller
{
    private function render(string $key, string $view): Response
    {
        $page = StaticPage::findByKey($key);

        abort_if(! $page, 404);

        return Inertia::render($view, [
            'content' => $page->content,
            'seo'     => [
                'title'       => $page->meta_title       ?: $page->title,
                'description' => $page->meta_description ?: '',
                'keywords'    => $page->meta_keywords    ?: '',
                'og_image'    => $page->og_image         ?: null,
                'robots'      => $page->robots           ?: 'index, follow',
            ],
        ]);
    }

    public function about(): Response
    {
        return $this->render('about', 'About');
    }

    public function contact(): Response
    {
        return $this->render('contact', 'Contact');
    }

    public function privacy(): Response
    {
        return $this->render('privacy', 'Privacy');
    }
}
