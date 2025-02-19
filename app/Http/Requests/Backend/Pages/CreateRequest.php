<?php

namespace App\Http\Requests\Backend\Pages;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('pages')],
            'content' => ['required', 'string'],
            'published_at' => ['nullable', 'bool'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super-admin']);
    }
}
