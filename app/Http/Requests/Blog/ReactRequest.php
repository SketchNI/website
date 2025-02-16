<?php

namespace App\Http\Requests\Blog;

use Illuminate\Foundation\Http\FormRequest;

class ReactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'reaction' => ['required', 'in:upvote,downvote,poop,heart'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('user::create vote');
    }
}
