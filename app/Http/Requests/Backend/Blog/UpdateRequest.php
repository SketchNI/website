<?php

namespace App\Http\Requests\Backend\Blog;

use App\Models\Blog\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'content' => ['required', 'string'],
            'excerpt' => ['required', 'string'],
            'published' => ['required', 'boolean'],
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'string', Rule::exists(Category::class, 'slug')],
        ];
    }

    public function authorize(): bool
    {
        if (request()->user()->hasRole('admin')) {
            $role = Role::findByName('admin');
            return $role->hasPermissionTo('update blog entry', 'web');
        }

        return false;
    }
}
