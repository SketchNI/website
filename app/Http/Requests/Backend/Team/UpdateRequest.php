<?php

namespace App\Http\Requests\Backend\Team;

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', Rule::unique('teams')->ignore($this->id)],
            'description' => ['nullable', 'string'],
            'role' => ['nullable', 'string'],
            'logo' => Str::contains($this->logo, 'images') ? ['nullable'] : ['nullable', 'image'],
            'github' => ['nullable', 'string'],
            'website' => ['nullable', 'url'],
        ];
    }

    public function authorize(): bool
    {
        return request()->user()->can('update', Team::class);
    }
}
