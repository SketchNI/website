<?php

namespace App\Http\Requests\Backend\Pages;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer|exists:users,id',
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'published_at' => 'nullable|bool',
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()->hasAnyRole(['admin', 'super-admin']);
    }
}
