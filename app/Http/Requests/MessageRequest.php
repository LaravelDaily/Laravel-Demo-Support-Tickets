<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MessageRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'message' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}