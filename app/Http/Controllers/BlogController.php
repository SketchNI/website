<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\CategoryResource;
use App\Models\Blog\Post;
use App\Models\Blog\Category;

class BlogController
{
    public function index()
    {
        $posts = PostResource::collection(
            Post::with(['user', 'categories'])->orderByDesc('created_at')->paginate(6)
        );

        $categories = CategoryResource::collection(Category::all())->resolve();

        return inertia('Blog/Index', compact('posts', 'categories'));
    }

    public function show(Post $post)
    {
        $categories = CategoryResource::collection(Category::all())->resolve();

        $post = new PostResource($post)->resolve();
        $theme = session('theme');

        return inertia('Blog/Show', compact('post', 'categories', 'theme'));
    }
}
