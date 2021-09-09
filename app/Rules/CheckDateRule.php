<?php

namespace App\Rules;

use App\Helpers\Helper;
use Illuminate\Contracts\Validation\Rule;

class CheckDateRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $data = explode("-", $value);
        if (sizeof($data) != 3) {
            return false;
        }

        $year = $data[0];
        $month = $data[1];
        $day = $data[2];

        if (empty($year) || empty($month) || empty($day)) {
            return true;
        } else {

            return Helper::checkDate($year, $month, $day);
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation_custom.M020');
    }
}
