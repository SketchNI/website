<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\CategoryResource;
use App\Http\Resources\Blog\PostResource;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use Inertia\Inertia;
use Inertia\Response;

class BlogController
{
    public function index(): Response
    {
        return inertia('Blog/Index', [
            'posts' => Inertia::defer(fn () => PostResource::collection(
                Post::published()->isNotDeleted()->paginate(6)
            )),
            'categories' => CategoryResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve(),
        ]);
    }

    public function show(Post $post): Response
    {
        $post = Post::published()->isNotDeleted()->findOrFail($post->id);

        return inertia('Blog/Show', [
            'post' => new PostResource($post)->resolve(),
            'categories' => CategoryResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve(),
        ]);
    }
}
