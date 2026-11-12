<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return ['category_id' => ['required', 'exists:categories,id'], 'name' => ['required', 'string', 'max:150'], 'description' => ['required', 'string', 'max:5000'], 'price' => ['required', 'integer', 'min:1000', 'max:100000000'], 'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048', 'dimensions:max_width=4096,max_height=4096'], 'is_available' => ['required', 'boolean']];
    }
}
