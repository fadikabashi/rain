<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name_en' => 'required|max:255|string',
            'description_en' => 'required|max:4000|string',
            'name_ar' => 'required|max:255|string',
            'description_ar' => 'required|max:4000|string',
            'quantity' => 'required|numeric',
            'manfacturer_id' => 'required|numeric',
            'type_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'is_available' => 'required|numeric',
            'photo' => 'mimes:jpg,bmp,png,jpeg',
            'price' => 'required|numeric',
            'discount' => 'required|numeric',
        ];
    }
}
