<?php

namespace App\Http\Controllers;

use App\Exceptions\ForbiddenException;
use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\TagResource;
use App\Models\Post;
use App\Traits\ThrowsException;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;

class BlogController
{
    use ThrowsException;

    public function index(): Response
    {
        return inertia('Blog/Index', [
            'posts' => Inertia::defer(fn () => PostResource::collection(
                Post::published()->withoutTrashed()->orderByDesc('published_at')->paginate(12)
            )),
            'tags' => TagResource::collection(Tag::withType('post')->get())->resolve(),
        ]);
    }

    /**
     * @throws ForbiddenException
     */
    public function preview(Request $request): Response
    {
        $this->forbidden('write blog entry');

        $post = Post::unpublished()
            ->withoutTrashed()
            ->findOrFail($request->get('id'));

        return inertia('Blog/Preview', [
            'post' => new PostResource($post)->resolve(),
            'tags' => TagResource::collection(Tag::withType('post')->get())->resolve(),
        ]);
    }

    public function show(Post $post): Response
    {
        $post = Post::published()
            ->withoutTrashed()
            ->findOrFail($post->id);

        return inertia('Blog/Show', [
            'post' => new PostResource($post)->resolve(),
            'tags' => TagResource::collection(Tag::withType('post')->get())->resolve(),
        ]);
    }
}
