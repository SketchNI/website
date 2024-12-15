<?php

namespace App\Http\Controllers\Backend;

use App\Models\Blog\Post;
use Illuminate\Contracts\View\View;

class BlogController
{
    public function index(): View
    {
        $posts = Post::with(['user'])->orderByDesc('id')->paginate(15);

        return view('backend.blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = Post::with(['user'])->where('slug', $slug)->first();

        return view('backend.blog.show', compact('post'));
    }
}
