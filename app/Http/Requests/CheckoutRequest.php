<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return ['customer_name' => ['required', 'string', 'max:100'], 'phone' => ['required', 'string', 'regex:/^[+0-9 ()-]{8,25}$/'], 'address' => ['required', 'string', 'min:10', 'max:2000'], 'event_date' => ['required', 'date', 'after_or_equal:today'], 'notes' => ['nullable', 'string', 'max:2000'], 'checkout_token' => ['required', 'string']];
    }
}
