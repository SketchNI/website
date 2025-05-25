<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Blog\Category\CreateRequest;
use App\Traits\Flashable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Spatie\Tags\Tag;

class CategoryController extends Controller
{
    use Flashable;

    public function store(CreateRequest $request): RedirectResponse
    {
        $category = new Tag;
        $category->name = $request->get('name');
        $category->slug = Str::slug($request->get('name'));
        $category->type = 'post';

        if ($category->save()) {
            $this->flash('The category has been created.');

            return redirect()->back();
        }

        $this->flash('Unable to create category.', 'error');

        return redirect()->back();
    }

    public function destroy(Tag $category): RedirectResponse
    {
        if ($category->delete()) {
            $this->flash('The category has been deleted.');

            return redirect()->back();
        }

        $this->flash('Unable to delete category.', 'error');

        return redirect()->back();
    }
}
