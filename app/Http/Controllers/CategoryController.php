<?php

namespace App\Http\Controllers;

use App\Http\Resources\Blog\PostResource;
use App\Http\Resources\CategoryResource;
use App\Models\Blog\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        $posts = $category->posts()->with(['categories', 'user'])->paginate(5);

        $posts = PostResource::collection($posts);

        $categories = CategoryResource::collection(Category::all())->resolve();

        $current = $category->name;

        return inertia('Category/Show', compact('posts', 'categories', 'current'));
    }
}
