<?php

namespace App\Http\Requests\Admin;

use App\Helpers\Helper;
use App\Rules\CheckDateRule;
use App\Rules\CheckEmailRule;
use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'email' => ['required', 'email', 'between:3,70' , new CheckEmailRule()],
            'nickname' =>  'required|between:1,50',
            'year' => 'numeric',
            'month' => 'numeric|between:0,12',
            'day' => 'numeric|between:0,31',
            'membership_status' => 'sometimes|required|between:1,4',
            'phone_number' => 'nullable|regex:/^([0-9]*)$/|max:11',
            'image_photo' => 'sometimes|mimes:jpeg,jpg,png,gif|max:5120',
            'introduction_from_admin' => 'sometimes|max:500'
        ];
    }
    // Thach creates Conflict
    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'email' => 'メールアドレス',
            'nickname' => 'ニックネーム',
            'year' => '生年月日',
            'month' => '生年月日',
            'day' => '生年月日',
            'membership_status' => '会員状態',
            'phone_number' => '電話番号',
            'image_photo' => 'プロフィール写真',
            'introduction_from_admin' => '講師紹介'
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
            'email.required'    => ':attribute'.config('validation.requiredField'),
            'email.email'       => ':attribute'.config('validation.email'),
            'email.between'     => ':attribute'.config('validation.between'),
            'email.regex'      => ':attribute'.config('validation.regex_alphanumeric'),
            'nickname.required' => ':attributeを入力してください。',
            'nickname.between'  => ':attribute'.config('validation.between'),
            'year.numeric'      => ':attribute'.config('validation.numeric'),
            'year.between'      => ':attribute'.config('validation.between'),
            'year.min'          => __('validation_custom.M001',['attribute'=>':attribute']),
            'month.numeric'     => ':attribute'.config('validation.numeric'),
            'month.max'         => '日付形式が正しくありません。',
            'month.between'     => ':attribute'.config('validation.between'),
            'month.min'         => __('validation_custom.M001',['attribute'=>':attribute']),
            'day.numeric'       => ':attribute'.config('validation.numeric'),
            'day.between'       => ':attribute'.config('validation.between'),
            'day.min'           => __('validation_custom.M001',['attribute'=>':attribute']),
            'day.max'           => '日付形式が正しくありません。',
            'membership_status.required' => ':attribute'.config('validation.requiredField'),
            'membership_status.between'  => ':attribute'.config('validation.between'),
            'phone_number.required'      => ':attribute'.config('validation.requiredField'),
            'phone_number.regex'         => ':attribute'.config('validation.regexNum'),
            'phone_number.max'           => ':attribute'.config('validation.max'),
            'image_photo.max'            => '',//config('validation.size')
            'image_photo.mimes'          => '', //__('validation_custom.M024')
            'introduction_from_admin.max'=> ':attribute'.config('validation.max_1'),
        ];
    }

    public function withValidator($validator)
    {

        $validator->sometimes('month', 'min:1', function($input) {
            return $input->year >= 1;
        });
        $validator->sometimes('day', 'min:1', function($input) {
            return $input->year >= 1 && $input->month >=1;
        });
        $validator->sometimes('month', 'min:1', function($input) {
            return $input->year >= 1 && $input->day >=1;
        });
        $validator->sometimes('year', 'min:1', function($input) {
            return $input->month >= 1 ;
        });
        $validator->sometimes('year', 'min:1', function($input) {
            return $input->day >= 1;
        });
        $validator->sometimes('day', 'max:28', function($input) {
            return (!(Helper::checkDate($input->year, $input->month, $input->day)) && $input->month == 2);
        });
        $validator->sometimes('day', 'max:30', function($input) {
            return $input->month == 4 || $input->month == 6 || $input->month == 9 || $input->month == 11;
        });
    }
}
