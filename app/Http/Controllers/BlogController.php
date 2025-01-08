<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\PostResource;
use App\Models\Blog\Post;
use Inertia\Inertia;
use Inertia\Response;

class BlogController
{
    public function index(): Response
    {
        return inertia('Blog/Index', [
            'posts' => Inertia::defer(fn () => PostResource::collection(
                Post::with('user')->orderByDesc('created_at')->paginate(6)
            )),
        ]);
    }

    public function show(Post $post): Response
    {
        return inertia('Blog/Show', [
            'post' => Inertia::defer(fn () => new PostResource($post)->resolve()),
        ]);
    }
}
