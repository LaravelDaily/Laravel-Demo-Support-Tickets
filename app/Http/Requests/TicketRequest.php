<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Coderflex\LaravelTicket\Enums\Status;
use Illuminate\Foundation\Http\FormRequest;
use Coderflex\LaravelTicket\Enums\Priority;

class TicketRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string'],
            'message'     => ['required', 'string'],
            'categories'  => ['array', 'exists:categories,id'],
            'labels'      => ['array', 'exists:labels,id'],
            'priority'    => ['required', new Enum(Priority::class)],
            'status'      => ['nullable', new Enum(Status::class)],
            'assigned_to' => ['nullable', 'integer', 'exists:users,id'],
            'attachments' => ['nullable', 'array'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}