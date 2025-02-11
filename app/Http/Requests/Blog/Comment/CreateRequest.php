<?php

namespace App\Http\Requests\Blog\Comment;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'blog.id' => ['required', 'exists:blog_posts,id'],
            'type' => ['required', 'in:post'],
            'comment' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->check();
    }
}
