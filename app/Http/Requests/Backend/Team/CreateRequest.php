<?php

namespace App\Http\Requests\Backend\Team;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('teams', 'name')],
            'description' => ['nullable', 'string'],
            'role' => ['nullable', 'string'],
            'logo' => ['nullable', 'image'],
            'github' => ['nullable', 'string'],
            'website' => ['nullable', 'url'],
        ];
    }

    public function authorize(): bool
    {
        return request()->user()->can('create', Team::class);
    }
}
