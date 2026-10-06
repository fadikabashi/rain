<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Checkout POST should always return to the checkout page on validation failure.
     * Admin order forms keep the default (redirect to previous URL).
     */
    protected function getRedirectUrl(): string
    {
        $url = $this->redirector->getUrlGenerator();

        if ($this->is('admin/*')) {
            return $url->previous();
        }

        return $url->route('checkout');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'costumer_name' => ['required', 'string', 'max:255'],
            'costumer_number' => ['required', 'numeric', 'digits_between:8,15'],
            'address' => ['required', 'string', 'max:500'],
            'total' => ['required', 'numeric', 'min:0'],
            'note' => ['nullable', 'string', 'max:1000'],
            'coupon_code' => ['nullable', 'string', 'max:8'],
            'coupon_id' => ['nullable', 'string', 'exists:coupons,id'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'costumer_name.required' => 'Customer name is required.',
            'costumer_name.string' => 'Customer name must be a valid text.',
            'costumer_name.max' => 'Customer name cannot exceed 255 characters.',
            'costumer_number.required' => 'Customer phone number is required.',
            'costumer_number.numeric' => 'Customer phone number must be a number.',
            'costumer_number.digits_between' => 'Customer phone number must be between 8 and 15 digits.',
            'address.required' => 'Address is required.',
            'address.string' => 'Address must be a valid text.',
            'address.max' => 'Address cannot exceed 500 characters.',
            'total.required' => 'Total amount is required.',
            'total.numeric' => 'Total amount must be a number.',
            'total.min' => 'Total amount cannot be negative.',
            'note.string' => 'Note must be a valid text.',
            'note.max' => 'Note cannot exceed 1000 characters.',
        ];
    }
}
