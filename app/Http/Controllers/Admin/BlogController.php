<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Blog/Index', [
            'posts' => BlogPost::latest()->paginate(20),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Blog/Form', ['post' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_posts,slug',
            'content'          => 'required|string',
            'excerpt'          => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords'    => 'nullable|string|max:500',
            'og_image'         => 'nullable|string|max:255',
            'is_published'     => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['published_at'] = $data['is_published'] ? now() : null;

        BlogPost::create($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post created.');
    }

    public function edit(BlogPost $blog): Response
    {
        return Inertia::render('Admin/Blog/Form', [
            'post' => [
                'id'               => $blog->id,
                'title'            => $blog->title ?? '',
                'slug'             => $blog->slug ?? '',
                'excerpt'          => $blog->excerpt ?? '',
                'content'          => $blog->content ?? '',
                'meta_title'       => $blog->meta_title ?? '',
                'meta_description' => $blog->meta_description ?? '',
                'meta_keywords'    => $blog->meta_keywords ?? '',
                'og_image'         => $blog->og_image ?? '',
                'is_published'     => (bool) $blog->is_published,
            ],
        ]);
    }

    public function update(Request $request, BlogPost $blog): RedirectResponse
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'slug'             => 'nullable|string|max:255|unique:blog_posts,slug,' . $blog->id,
            'content'          => 'required|string',
            'excerpt'          => 'nullable|string|max:500',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:320',
            'meta_keywords'    => 'nullable|string|max:500',
            'og_image'         => 'nullable|string|max:255',
            'is_published'     => 'boolean',
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        if ($data['is_published'] && ! $blog->published_at) {
            $data['published_at'] = now();
        } elseif (! $data['is_published']) {
            $data['published_at'] = null;
        }

        $blog->update($data);

        return redirect()->route('admin.blog.index')->with('success', 'Post updated.');
    }

    public function destroy(BlogPost $blog): RedirectResponse
    {
        $blog->delete();
        return redirect()->route('admin.blog.index')->with('success', 'Post deleted.');
    }

    public function toggleStatus(BlogPost $blog): RedirectResponse
    {
        $blog->is_published = ! $blog->is_published;
        $blog->published_at = $blog->is_published ? now() : null;
        $blog->save();

        return redirect()->route('admin.blog.index')
            ->with('success', $blog->is_published ? 'Post published.' : 'Post moved to draft.');
    }
}
