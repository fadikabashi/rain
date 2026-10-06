<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Product;
use App\Services\CartStockService;

class AddToCartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'exists:products,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1', 'max:100'],
            'photo' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->has('id')) {
                $product = Product::find($this->input('id'));
                
                if ($product && ! $product->is_available) {
                    $validator->errors()->add('id', 'This product is currently unavailable.');
                }

                $addQty = (int) $this->input('quantity', 1);
                if ($product && $product->is_available && ! CartStockService::canMergeAdd($product, $addQty)) {
                    $totalAfter = CartStockService::totalAfterAdd($product->id, $addQty);
                    $validator->errors()->add('quantity', __('frontend.insufficient_stock', [
                        'requested' => $totalAfter,
                        'available' => $product->quantity,
                    ]));
                }
            }
        });
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id.required' => 'Product ID is required.',
            'id.exists' => 'The selected product does not exist.',
            'name.required' => 'Product name is required.',
            'price.numeric' => 'Product price must be a number.',
            'price.min' => 'Product price cannot be negative.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 1.',
            'quantity.max' => 'Quantity cannot exceed 100.',
        ];
    }
}
