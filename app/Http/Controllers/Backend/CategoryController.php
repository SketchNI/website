<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Blog\Category\CreateRequest;
use App\Models\Blog\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function store(CreateRequest $request): RedirectResponse
    {
        $category = new Category();
        $category->name = $request->get('name');
        $category->slug = Str::slug($request->get('name'));
        $category->parent_id = $request->get('parent_id');

        if ($category->save()) {
            session()->flash('flash', ['message' => 'The category has been created.', 'type' => 'success']);

            return redirect()->back();
        }

        session()->flash('flash', ['message' => 'Unable to create category.', 'type' => 'error']);

        return redirect()->back();
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->delete()) {
            return response()->json(['message' => 'The category has been deleted.', 'type' => 'success']);
        }

        return response()->json(['message' => 'Unable to delete category.', 'type' => 'error']);
    }
}
