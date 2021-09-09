<?php

namespace App\Http\Requests\Admin;

use App\Rules\DateFormatRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|max:254',
            'email' => ['email','required'],
            'role' =>  ['numeric','nullable'],
            'auth' =>  ['numeric','nullable'],
            'status' =>  ['numeric','nullable'],
            'user_create' =>  ['numeric','nullable'],
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
            'name' => __('user.name'),
            'email' => __('user.email'),
            'role' => __('user.role'),
            'auth' => __('user.auth'),
            'status' => __('user.status'),
            'user_create' => __('user.user_create'),
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

        ];
    }

}
