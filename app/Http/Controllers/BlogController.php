<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Resources\Blog\CategoryResource;
use App\Http\Resources\Blog\PostResource;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Traits\ThrowsException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class BlogController
{
    use ThrowsException;

    public function index(): Response
    {
        return inertia('Blog/Index', [
            'posts' => Inertia::defer(fn () => PostResource::collection(
                Post::published()->isNotDeleted()->paginate(6)
            )),
            'categories' => CategoryResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve(),
        ]);
    }

    /**
     * @throws ForbiddenException
     */
    public function preview(Request $request): Response
    {
        $this->forbidden('write blog entry');

        $post = Post::unpublished()
            ->isNotDeleted()
            ->findOrFail($request->get('id'));

        return inertia('Blog/Preview', [
            'post' => new PostResource($post)->resolve(),
            'categories' => CategoryResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve(),
        ]);
    }

    public function show(Post $post): Response
    {
        $post = Post::published()
            ->with(['comments' => fn ($post) => $post->orderByDesc('id')])
            ->isNotDeleted()
            ->findOrFail($post->id);

        return inertia('Blog/Show', [
            'post' => new PostResource($post)->resolve(),
            'categories' => CategoryResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve(),
        ]);
    }
}
