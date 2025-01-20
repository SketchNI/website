<?php

namespace App\Http\Requests\Theme;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'theme' => ['required', 'in:latte,frappe,macchiato,mocha'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
