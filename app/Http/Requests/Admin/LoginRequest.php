<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
//            'email' => 'required|email|between:3,70|regex: /^(?=.*[a-z0-9])[a-z0-9!@#$%&*.]{7,}$/i',
//            'password' => 'required|between:8,16|regex: /^(?=.*[a-z0-9])[a-z0-9!@#$%&*.]{7,}$/i',
            'email' => 'required',
            'password' => 'required',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
            'password' => 'パスワード'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'email.required'    => ':attribute'.config('validation.required'),
            'email.email'       => ':attribute'.config('validation.email'),
            'email.between'     => ':attribute'.config('validation.between'),
            'email.regex'       => ':attribute'.config('validation.regex_alphanumeric'),
            'password.required' => ':attribute'.config('validation.required'),
            'password.between'  => ':attribute'.config('validation.between'),
            'password.regex'    => ':attribute'.config('validation.regex_alphanumeric'),
        ];
    }
}
