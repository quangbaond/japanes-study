<?php
namespace App\Http\Requests;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class EditAdminRequest extends  FormRequest {

    protected function failedValidation(Validator $validator) {
        return response()->json([
            'status' => false,
            'message' => $validator->errors(),
            'status_code' => Response::HTTP_OK,
            'data' => null,
        ]);
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
            'year' => __('student.birthday'),
            'month' => __('student.birthday'),
            'day' => __('student.birthday'),
            'sex' => __('student.gender'),
            'phone_number' => __('student.phone_number'),
            'nationality' => __('student.nationality'),
            'image_photo' => __('student.photo'),
            'birthday' => __('student.birthday'),
            'nickname' => 'ニックネーム'
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
            'birthday.date_format'              => __('validation_custom.M020'),
            'sex.numeric'                       => __('validation_custom.M006',['attribute'=>':attribute']),
            'phone_number.numeric'              => __('validation_custom.M006',['attribute'=>':attribute']),
            'area_code.in'                      => ':attribute '.config('validation.digits_between'),       //chua co
            'image_photo.max'                   => __('validation_custom.M018',['attribute'=>':attribute','max' => '5120kb']),
            'phone_number.between'              => __('validation_custom.M018',['attribute'=>':attribute']),
            'phone_number.digits_between'       => __('validation_custom.M018',['attribute'=>':attribute','max' => '11']),
            'birthday.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
            'year.required'                     => __('validation_custom.M001',['attribute'=>':attribute']),
            'month.required'                    => __('validation_custom.M001',['attribute'=>':attribute']),
            'day.required'                      => __('validation_custom.M001',['attribute'=>':attribute']),
            'nickname.required'                 => __('validation_custom.M001',['attribute'=>':attribute']),
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
