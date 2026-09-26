<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $remaining = $this->route('event')->remainingSeats();

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'guests' => ['required', 'integer', 'min:1', 'max:' . $remaining],
            'reserved_at' => ['required', 'date', 'after:now'],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => '名前',
            'email' => 'メールアドレス',
            'guests' => '人数',
            'reserved_at' => '予約日時',
        ];
    }
}
