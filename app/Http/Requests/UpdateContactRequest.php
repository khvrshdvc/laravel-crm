<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateContactRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email'],
            'phone' => ['required', 'string'],
            'position' => ['nullable', 'string', 'max:255'],
            'company_id' => ['required', 'exists:companies,id'],
        ];
    }
}
