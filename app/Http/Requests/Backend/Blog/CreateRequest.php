<?php

namespace App\Http\Requests\Backend\Blog;

use App\Models\Blog\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'content' => ['string', 'nullable'],
            'excerpt' => ['required', 'string'],
            'published' => ['required', 'boolean'],
            'tags' => ['required', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return request()->user()->hasPermissionTo('write blog entry', 'web');
    }
}
