<?php

//Breadcrumbs::for('dashboard', function ($trail) {
//    $trail->push("ダッシュボード", route('admin-dashboard'));
//});

Breadcrumbs::for('list_admin', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push(" アドミン一覧", route('admin.admin-list'));
});
Breadcrumbs::for('curriculum_management', function ($trail) {
    $trail->push(" カリキュラム一覧", route('admin.curriculum'));
});

Breadcrumbs::for('create_curriculum', function ($trail) {
    $trail->parent('curriculum_management');
    $trail->push(" カリキュラム追加", route('admin.curriculum.create'));
});
Breadcrumbs::for('curriculum_detail', function ($trail) {
    $trail->parent('curriculum_management');
    $trail->push(" カリキュラム詳細", route('admin.curriculum.detail', ['id' =>'1']));
});

Breadcrumbs::for('create_admin', function ($trail) {
    $trail->parent('list_admin');
    $trail->push(" アドミン追加", route('admin.admin-list.create'));
});
Breadcrumbs::for('admin_detail', function ($trail) {
    $trail->parent('list_admin');
    $trail->push(" アドミン詳細", route('admin.admin-list.detail', ['user_id' => '1']));
});

Breadcrumbs::for('teacher_dashboard', function ($trail) {
    $trail->push("マイページ", route('teacher-dashboard'));
});

Breadcrumbs::for('list_teachers', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("講師一覧", route('admin.teacher.index'));
});

Breadcrumbs::for('create_teachers', function ($trail) {
    $trail->parent('list_teachers');
    $trail->push("講師追加", route('admin.teacher.create'));
});

Breadcrumbs::for('list_students', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("生徒一覧", route('admin.student.index'));
});
Breadcrumbs::for('list_history', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("レッスン履歴", route('admin.lesson-history'));
});
Breadcrumbs::for('list_booking', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("予約一覧", route('admin.booking-list'));
});
Breadcrumbs::for('create_student', function ($trail) {
    $trail->parent('list_students');
    $trail->push("生徒追加", route('admin.teacher.create'));
});
Breadcrumbs::for('my_page', function ($trail) {
    $trail->push("マイページ", route('teacher.my-page'));
});

Breadcrumbs::for('admin.courses', function ($trail) {
    $trail->push("コース一覧", route('admin.courses'));
});

Breadcrumbs::for('admin.course.detail', function ($trail) {
    $trail->parent('admin.courses');
    $trail->push("コース詳細");
});

Breadcrumbs::for('courses', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("コース一覧", route('teacher.courses'));
});

Breadcrumbs::for('course.detail', function ($trail) {
    $trail->parent('courses');
    $trail->push("コース詳細");
});

Breadcrumbs::for('add_schedule', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("スケジュール一覧", route('teacher.listSchedule'));
    $trail->push("スケジュール追加", route('teacher.addSchedule'));
});

Breadcrumbs::for('list_schedule', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("スケジュール一覧", route('teacher.addSchedule'));
});
Breadcrumbs::for('teacher_lessonHistory', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("レッスン履歴", route('teacher.lesson.history'));
});

Breadcrumbs::for('edit_profile', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("プロファイル設定", route('teacher.edit-profile'));
});
Breadcrumbs::for('teacher_notification', function ($trail ) {
    $trail->parent('teacher_dashboard');
    $trail->push("通知一覧", route('teacher-notification'));

});

Breadcrumbs::for('teacher_notification_detail', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("通知一覧", route('teacher-notification'));
    $trail->push("通知詳細");
});

Breadcrumbs::for('teacher_notification_icon_detail', function ($trail) {
    $trail->parent('teacher_dashboard');
    $trail->push("通知詳細");
});

Breadcrumbs::for('student_trial_step1', function ($trail) {
//    $trail->parent('teacher_dashboard');
    $trail->push("Home page", route('student-dashboard'));
    $trail->push("7 days free trial", route('student.payment.7-days-free-trial'));
});

Breadcrumbs::for('student_trial_step2', function ($trail) {
    $trail->parent('student_trial_step1');
    $trail->push("Payment method", route('student.payment.7-days-free-trial'));
});

Breadcrumbs::for('student_premium_step1', function ($trail) {
    $trail->push("Home page", route('student-dashboard'));
    $trail->push("Get Premium", route('student.payment.premium'));
});

Breadcrumbs::for('student_premium_step2', function ($trail) {
    $trail->parent('student_premium_step1');
    $trail->push("Payment method", route('student.payment.premium'));
});


Breadcrumbs::for('student_dashboard', function ($trail) {
    $trail->push("Dashboard", route('student-dashboard'));
});
Breadcrumbs::for('update_info_student', function ($trail) {
    $trail->parent('student_dashboard');
    $trail->push("Setting password", route('student-password'));
});

// Breadcrumb of list plan for admin
Breadcrumbs::for('index_plan', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("プラン一覧", route('plans.list'));
});

// Breadcrumb of create plan for admin
Breadcrumbs::for('create_plan', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("プラン一覧", route('plans.list'));
    $trail->push("追加", route('plans.create'));
});

// Breadcrumb of edit plan for admin
Breadcrumbs::for('edit_plan', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("プラン一覧", route('plans.list'));
    $trail->push("変更", route('plans.list'));
});
Breadcrumbs::for('teacher_detail', function ($trail , $teacher) {
//    $trail->parent('dashboard');
    $trail->push("講師一覧", route('admin.teacher.index'));
    $trail->push("講師詳細" ,  route('admin.teacher.detail' , $teacher->id));
});
Breadcrumbs::for('teacher_detail_bookingSubstitute', function ($trail , $teacher) {
//    $trail->parent('dashboard');
    $trail->push("講師一覧", route('admin.teacher.index'));
    $trail->push("講師詳細" ,  route('admin.teacher.detail' , $teacher->id));
    $trail->push("レッスン予約代行" , route('admin.teacher.bookingSubstitute' , $teacher->id));
});

Breadcrumbs::for('payment_information', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("決済一覧", route('admin.payment.index'));
});
Breadcrumbs::for('student_detail', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("生徒一覧", route('admin.student.index'));
    $trail->push("生徒詳細", route('user.detail',['id'=>'13']));
});

Breadcrumbs::for('notification', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("通知一覧" , route('admin-notification'));
});

Breadcrumbs::for('notification_create', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("通知一覧" , route('admin-notification'));
    $trail->push("追加追加" , route('admin-notification'));
});
Breadcrumbs::for('notification_detail', function ($trail) {
//    $trail->parent('dashboard');
    $trail->push("通知一覧" , route('admin-notification'));
    $trail->push("通知詳細" , route('admin-notification'));
});


