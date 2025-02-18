<?php

namespace App\Http\Requests\Backend\Blog;

use Illuminate\Foundation\Http\FormRequest;
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
            'tags' => ['required', 'array'],
        ];
    }

    public function authorize(): bool
    {
        if (request()->user()->hasAnyRole(['super-admin', 'admin'])) {
            $role = Role::findByName('admin');

            return $role->hasPermissionTo('update blog entry', 'web');
        }

        return false;
    }
}
