<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Inertia\Inertia;
use Inertia\Response;

class BlogController extends Controller
{
    public function index(): Response
    {
        $posts = BlogPost::published()
            ->select('id', 'title', 'slug', 'excerpt', 'og_image', 'published_at')
            ->latest('published_at')
            ->paginate(12);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
            'seo'   => [
                'title'       => 'Blog — MA Tools Tips & Tutorials',
                'description' => 'Tips, tutorials and guides for using free online image and PDF tools. Learn how to crop images, remove backgrounds, merge PDFs and more.',
                'keywords'    => 'image tools blog, pdf tools tutorials, online tools tips, web tools guide',
            ],
        ]);
    }

    public function show(string $slug): Response
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return Inertia::render('Blog/Show', [
            'post' => $post,
            'seo'  => [
                'title'       => $post->meta_title ?: $post->title,
                'description' => $post->meta_description ?: $post->excerpt,
                'keywords'    => $post->meta_keywords,
                'og_image'    => $post->og_image,
            ],
        ]);
    }
}
