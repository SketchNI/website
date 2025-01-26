<?php

namespace App\Http\Requests\Backend\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'alpha:ascii'],
            'email' => ['required', 'email', 'max:254', Rule::unique('users')->ignore($this->id)],
            'email_verified' => ['bool'],
            'role.name' => ['required', 'exists:roles,name'],
        ];
    }

    public function authorize(): bool
    {
        return request()->user()->hasPermissionTo('update user');
    }
}
