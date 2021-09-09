<?php
return [
    'role' => [
        'admin' => 1,
        'teacher' => 2,
        'student' => 3,
        'child-admin' => 4
    ],

    'sex' => [
        'id' => [
            'male' => 1,
            'female' => 2,
            'unspecified' => 3
        ],
        'name' => [
            'male' => '男性',
            'female' => '女性',
            'unspecified' => '指定なし'
        ]
    ],

    'membership' => [
        'id' => [
            'free' => 1,
            'premium_trial' => 2,
            'premium' => 3,
            'Special' => 4,
            'other_company' => 5,
            'cancelling_premium' => 6
        ],
        'name' => [
            'free' => '無料',
            'premium_trial' => 'トライアル',
            'premium' => 'プレミアム',
            'Special' => '特別',
            'other_company' => '他の会社',
            'cancelling_premium' => 'プレミアムの解約処理中'
        ]
    ],
    'lesson_histories' => [
        'free' => 1,  //student frees coin status
        'use_coin' => 2 // student uses coin status
    ],
    'teacher_schedule' => [
        'done' => 1,
        'booking' => 2,
        'free_time' => 3
    ],
    'history_student_use_coin' => [
        'add_coin' => 1,
        'booking' => 2,
        'start_lesson_now' => 3,
        'return' => 4
    ],
    'no_data' => '',
    'delete_success' => '削除が完了しました。',
    'delete_confirm' => '選択されたレコードを削除します。
                         '.'<br>'.'よろしいでしょうか？',
    'email_isset'       => 'メールアドレスが既に登録されています。',
    'register_success'  => '登録が完了しました。',
    'distance_time' => '00:30',
    'distance_time_minute' => 30,
    'year_from' => '1930',
    'year_to' => \Carbon\Carbon::now()->format('Y') -1,
    'currency' => 'vnd',
    'time_hour_auto_payment' => 2,
    'time_minute_trial_end' => 10,
    'pagination' => 10,
    'expiration_date_add_coin' => 2,
    'have_no_record_today_schedule'=>'該当するレッスンがありません。',
    'trial_day_premium_plan' => 7,
    'text_link' => 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/file/pdf_seed.pdf',
    'video_link' => 'https://japanese-study.s3-ap-southeast-1.amazonaws.com/video/video_seed.mov',
    'contact_email' => 'members@mcrew-tech.com',
    'contact_phone' => '(VI-84) 906.419.000',
];
