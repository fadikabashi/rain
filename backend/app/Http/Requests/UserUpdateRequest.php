<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\User;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    // public function authorize()
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|max:255|string',
            'email' => 'required|max:255|email|unique:users,email,'.request()->id,
            'password' => 'nullable|min:8',
            'user_type_id' => 'required|numeric',
            'is_active' => 'required|numeric',
            'phone' => 'required|numeric|digits_between:9,12|unique:users,phone,'.request()->id,
            'profile_pic' => 'nullable|mimes:jpg,bmp,png|max:2048',
        ];
    }
}