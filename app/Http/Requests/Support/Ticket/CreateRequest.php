<?php

namespace App\Http\Requests\Support\Ticket;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'subject' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'attachments' => ['nullable', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('user::create ticket');
    }
}
