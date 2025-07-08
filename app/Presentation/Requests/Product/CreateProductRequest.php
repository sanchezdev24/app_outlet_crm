<?php

namespace App\Presentation\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'sku' => 'required|string|unique:products,sku|max:100',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'images' => 'array',
            'images.*' => 'string|url',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_valid_until' => 'nullable|date|after:today'
        ];
    }
}