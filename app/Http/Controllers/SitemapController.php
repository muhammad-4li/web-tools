<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $sitemap = Sitemap::create();

        $tools = [
            '/',
            '/tools/image-crop',
            '/tools/image-bg-remover',
            '/tools/pdf-merge',
            '/tools/pdf-sign',
            '/tools/pdf-text',
            '/tools/text-editor',
            '/blog',
            '/about',
            '/contact',
            '/privacy',
        ];

        foreach ($tools as $path) {
            $sitemap->add(
                Url::create(config('app.url') . $path)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                    ->setPriority($path === '/' ? 1.0 : 0.8)
            );
        }

        BlogPost::published()->latest('published_at')->each(function (BlogPost $post) use ($sitemap) {
            $sitemap->add(
                Url::create(config('app.url') . '/blog/' . $post->slug)
                    ->setLastModificationDate($post->updated_at)
                    ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                    ->setPriority(0.6)
            );
        });

        return response($sitemap->render(), 200, ['Content-Type' => 'application/xml']);
    }
}
