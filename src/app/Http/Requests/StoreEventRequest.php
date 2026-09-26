<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'location' => ['required', 'string', 'max:255'],
            'starts_at' => ['required', 'date', 'after:now'],
            'capacity' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }

    public function attributes(): array
    {
        return [
            'title' => 'イベント名',
            'description' => '内容',
            'location' => '場所',
            'starts_at' => '開催日時',
            'capacity' => '定員',
        ];
    }
}
