<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */
    'role' => [
        'free' => 'Free',
        'trial' => 'Trial',
        'premium' => 'Premium',
        'special' => 'Special',
        'other_company' => 'Other company',
        'canceling_premium' => 'Canceling Premium',
    ],
    'register_success' => 'Register success.',
    'register_error' => 'Register failed.',

    'input_error_common' => 'The input value went wrong. Please try again.',
    'email_not_auth' => 'Email already exists in the system but is not authenticated. Send mail to verify.',

    //M021
    'email_isset' => 'The email address is already registered. Please login or use a different email address.',

    //M016
    'check_required' => 'Please check this box if you want to proceed.',
    'check_radio_required' => 'Please choose a option.',
    //M004
    'email_alpha' => 'Please enter email in alphanumeric or symbols.',

    // CM0001
    'CM001' => 'The input value went wrong. Please try again.',
    'CM002' => 'Login failed.',
    'M001' => 'The :attribute field is required.',
    'M002' => 'The Email must be a valid email address.',
    'M003' => 'The :attribute must be between :min and :max characters.',
    'M004' => 'The :attribute may only contain letters, numbers, and symbols.',
    'M005' => 'Your login information was incorrect. Please try again.',
    'M006' => 'The :attribute may only contain numbers.',
    'M007' => 'Register failed.',
    'M008' => 'Register success.',
    'M009' => 'Please enter a search item.',
    'M010' => 'Start date must be a date before or equal to end date.',
    'M011' => 'No match was found for your search.',
    'M012' => 'Please select a record.',
    'M013' => 'The :attribute must be less than or equal :field.',
    'M014' => 'Delete success.',
    'M015' => 'Are you sure you want to delete selected records?',
    'M016' => 'Please check this box if you want to proceed.',
    'M017' => 'The :attribute must be at least :min characters.',
    'M018' => 'The :attribute may not be greater than :max characters.',
    'M019' => 'The image size should not exceed :sizeMB.',
    'M020' => 'The date format is incorrect.',
    'M021' => 'The email address is already registered. Please login or use a different email address.',
    'M022' => 'The URL has expired. Please do it again.',
    'M023' => 'The email address is already registered.',
    'M024' => 'The image file is invalid or the image type is not allowed. Allowed types: JPEG・JPG・PNG・GIF.',
    'M025' => 'The confirm password and password must match.',
    'M026' => 'The confirm email and email must match.',
    'M027' => 'Update success.',
    'M028' => 'Update failed.',
    'M029' => 'The schedule is duplicated.',
    'M030' => 'Please enter at least one schedule.',
    'M031' => 'Please enter the time after 30 minutes from now.',
    'M032' => 'Your card number is incorrect.',
    'M033' => 'Your card\'s security code is invalid.',
    'M034' => 'This is not a YouTube link.',
    'M035' => 'The current password is incorrect.',
    'M036' => 'Your email has been deleted by admin, contact admin for support.',
    'M037' =>'You has been changed to Free membership.Click  <a href="'.route('student.payment.premium').'"  style="text-decoration: underline">here</a> to Re-Get premium plan.',
    'M038_1' => 'Your remaining coins are not enough',
    'M038_2' => ' click here to buy more coins.',
    'M038' => 'Your remaining coins are not enough, <a href="'.route("student.add-coin").'">click here to buy more coins.</a> ',
    'M039' => 'The lesson booking is complete.',
    'M040' => ['content'=>'You have been booked for new lesson. <a href=":attribute">Click to see the schedule.</a>',
        'title'=>'Notice of reservation'],
    'M041' => 'Schedule has been booking, please choose another schedule.',
    'M042' => 'Bookings can only be made 30 minutes prior to the class start time.',
    'M043' => 'The transaction was successful.',
    'M044' => 'Cancel lesson successful.',
    'M045' => 'The schedule has been changed by the teacher, please choose a different schedule.',
    'M046' => 'Are you sure you want to pay?',
    'M047' => '<h5><i class="icon fas fa-exclamation-triangle"></i> This payment has been failed by your card issue!</h5>Please contact your card issuer for more details, or use a different card.',
    'M048' => 'The lesson unavailable now.',
    'M049' => 'Email address is unauthenticated',
    'M050' => 'Payment is in progress and cannot be canceled.',
    'M051' => 'Are you sure you want to cancel your trial membership?',
    'M052' => 'Are you sure you want to cancel your Premium membership?',
    'M053' => 'Membership has been successfully canceled.',
    'M054' => 'You cannot do this because your Premium membership has expired.',
    'M056' => 'Please enter a number greater than 0.',
    'M057' => 'Update nickname successful.',
    'M058' => 'Update email successful.',
    'M059' => 'Update password successful.',
    'M060' => 'Refund coins successful.',
    'M062' => 'The schedule you have selected is past the premium membership period.',
    'M067' => 'You do not have access to this function.',
    'M069' => 'Thank you for your rating.',
    'M070' => 'There are no data corresponding to the conditions.',
    'M071' => 'This teacher does not teach the course you are taking. Please select your desired course and lesson.',
    'M072' => 'The current time zone is ',
    'M073' => 'You are a Free member, you cannot watch text and video content. Click <a href="'.route('student.payment.premium').'"  style="text-decoration: underline">here</a> to get premium plan.',
    'M074' => 'You have completed our final lesson. You can choose old lesson for review.',
    'M075' => 'You are a Free member, coins cannot be used. Click  <a href="'.route('student.payment.premium').'"  style="text-decoration: underline">here</a> to get premium plan.',
    'M076' => 'You are a Free member, coins cannot be used. Click <a href="'.route('student.payment.7-days-free-trial').'"  style="text-decoration: underline">here</a> to get 7 days free.',
    'M077' => 'You are a Free member, you cannot watch text and video content. Click <a href="'.route('student.payment.7-days-free-trial').'"  style="text-decoration: underline">here</a> to get 7 days free trial.',
    'M078' => 'Thẻ của bạn đang dùng là thẻ để test',
];
