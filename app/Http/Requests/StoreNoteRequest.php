<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreNoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'content'       => ['required', 'string', 'max:1000'],
            'noteable_type' => ['required', 'string'],
            'noteable_id'   => ['required', 'integer'],
        ];
    }
}