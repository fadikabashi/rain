<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSliderRequest extends FormRequest
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
            'ad_825' => 'nullable|file|mimes:jpg,jpeg,bmp,png,webp|max:2048',
            'heading' => 'required|max:255|string',
            'sub_heading' => 'required|max:255|string',
            'description' => 'required|max:255|string',
        ];
    }
}
