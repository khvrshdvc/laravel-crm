<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDealRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'lead_id' => ['nullable', 'exists:leads,id'],
            'company_id' => ['nullable', 'exists:companies,id'],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required','string','in:new,qualified,proposal,negotiation,won,lost'],
            'assigned_to' => ['nullable','exists:users,id'],
        ];
    }
}
