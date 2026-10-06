<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
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
            'ad_1_350' => 'mimes:jpg,bmp,png',
            'ad_2_350' => 'mimes:jpg,bmp,png',
            'ad_3_350' => 'mimes:jpg,bmp,png',
            'ad_1_555' => 'mimes:jpg,bmp,png',
            'ad_2_555' => 'mimes:jpg,bmp,png',
            'ad_1_1110' => 'mimes:jpg,bmp,png',
            'ad_1_shop' => 'mimes:jpg,bmp,png',
        ];
    }
}
