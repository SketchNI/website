<?php

namespace App\Http\Requests\Backend\Image;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'image' => ['required', 'image'],
            'caption' => ['nullable', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return request()->user()->hasPermissionTo('write blog entry');
    }
}
