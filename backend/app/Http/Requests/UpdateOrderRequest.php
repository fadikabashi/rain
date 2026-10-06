<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'total' => 'required|numeric',
            'note' => 'required|max:4000|string',
            'costumer_name' => 'required|max:255|string',
            'address' => 'required|max:255|string',
            'costumer_number' => 'required|numeric|digits_between:9,12',
            'order_status'=>'required|numeric',
            'coupon_id' => 'numeric',

        ];
    }
}
