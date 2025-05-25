<?php

namespace App\Http\Requests\Backend\Blog\Category;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return request()->user()->can('create categories');
    }
}
