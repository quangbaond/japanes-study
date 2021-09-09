<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class   PlanRequest extends FormRequest
{
    protected function failedValidation(Validator $validator) {
        throw new HttpResponseException(redirect(route('plans.create'))->withInput()->with('error', __('validation_custom.CM001'))->withErrors($validator));
    }

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
            'name'              => 'required|max:50',
            'cost'              => 'required|regex:/^([0-9]*)$/|max:8',
            'interval_count'    => 'required|regex:/^([0-9]*)$/|max:10',
            'description'       => 'required|max:100',
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
            'name'              => 'プラン名',
            'cost'              => '料金',
            'interval_count'    => '量',
            'description'       => '説明',
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
            'name.required'             => __('validation_custom.M001'),
            'name.max'                  => __('validation_custom.M018'),
            'cost.required'             => __('validation_custom.M001'),
            'cost.regex'                => __('validation_custom.M006'),
            'cost.max'                  => __('validation_custom.M018'),
            'interval_count.required'   => __('validation_custom.M001'),
            'interval_count.regex'      => __('validation_custom.M006'),
            'interval_count.max'        => __('validation_custom.M018', ['max' => '10']),
            'description.max'           => __('validation_custom.M018'),
            'description.required'      => __('validation_custom.M001'),
        ];
    }
}
