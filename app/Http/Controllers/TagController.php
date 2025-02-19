<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\Blog\TagResource;
use App\Models\Blog\Category;
use Inertia\Response;

class TagController extends Controller
{
    public function __invoke(Category $category): Response
    {
        $posts = $category->posts()
            ->with(['categories', 'user'])
            ->published()
            ->withoutTrashed()
            ->paginate(5);

        $posts = PostResource::collection($posts);

        $categories = TagResource::collection(Category::with('children')->whereNull('parent_id')->get())->resolve();

        $current = $category->name;

        return inertia('Category/Show', compact('posts', 'categories', 'current'));
    }
}
