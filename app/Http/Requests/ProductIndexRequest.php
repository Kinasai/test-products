<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => 'nullable|string|max:255',
            'price_from' => 'nullable|numeric|min:0',
            'price_to' => 'nullable|numeric|min:0|gt:price_from',
            'category_id' => 'nullable|integer|exists:categories,id',
            'in_stock' => 'nullable|boolean',
            'rating_from' => 'nullable|numeric|min:0|max:5',
            'sort' => 'nullable|in:price_asc,price_desc,rating_desc,newest',
            'per_page' => 'nullable|integer|min:1|max:100',
        ];
    }

    public function messages(): array
    {
        return [
            'price_to.gt' => 'Price to must be greater than price from',
            'category_id.exists' => 'Selected category does not exist',
            'sort.in' => 'Invalid sort option',
        ];
    }

    public function filters(): array
    {
        return $this->validated();
    }
}
