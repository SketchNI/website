<?php

namespace App\Http\Controllers;

use Spatie\Tags\Tag;
use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\TagResource;
use Inertia\Response;

class TagController extends Controller
{
    public function __invoke(Tag $tag): Response
    {
        $posts = $tag->posts()
            ->with(['categories', 'user'])
            ->published()
            ->withoutTrashed()
            ->paginate(5);

        $posts = PostResource::collection($posts);

        $categories = TagResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve();

        $current = $tag->name;

        return inertia('Category/Show', compact('posts', 'categories', 'current'));
    }
}
