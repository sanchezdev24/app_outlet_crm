<?php

namespace App\Presentation\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('customers', 'email')->ignore($this->route('customer'))
            ],
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'type' => 'required|in:regular,vip,wholesale',
            'birth_date' => 'required|date|before:today'
        ];
    }
}