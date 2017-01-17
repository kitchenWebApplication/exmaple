<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserCreateRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'            => 'required|string|between:3,20',
            'last_name'             => 'required|string|between:3,20',
            'email'                 => 'required|email|max:60|unique:users',
            'password'              => 'required|min:6',
            'password_confirmation' => 'required_with:password|same:password'
        ];
    }
}
