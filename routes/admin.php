<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Auth::routes();

Route::stripeWebhooks('stripe_webhook');

Route::group(['middleware' => ['lang']], function () {
    // Login admin
    Route::get('admin/login', 'Admin\Managers\AuthController@index')->name('login');
    Route::post('admin/post-login', 'Admin\Managers\AuthController@postLogin')->name('admin-login');
    Route::any('admin/logout', 'Admin\Managers\AuthController@logout')->name('admin-logout');

    // Login teacher
    Route::get('teacher/login', 'Admin\Teachers\AuthController@index')->name('login.teacher');
    Route::post('teacher/post-login', 'Admin\Teachers\AuthController@postLogin')->name('teacher-login');
    Route::any('teacher/logout', 'Admin\Teachers\AuthController@logout')->name('teacher-logout');

    // Login student
    Route::get('student/login', 'Admin\Students\AuthController@index')->name('login.student');
    Route::post('student/post-login', 'Admin\Students\AuthController@postLogin')->name('student-login');
    Route::any('student/logout', 'Admin\Students\AuthController@logout')->name('student-logout');

    // Login zalo
    Route::get('student/login/zalo', 'Admin\Students\AuthController@loginZalo')->name('login.student.zalo');
    Route::get('student/login/zalo/callback', 'Admin\Students\AuthController@callbackZaloLogin')->name('login.student.zalo.callback');

    // Login facebook
    Route::get('student/login/facebook', 'Admin\Students\AuthController@loginFacebook')->name('login.student.facebook');
    Route::get('student/login/facebook/callback', 'Admin\Students\AuthController@callbackFacebookLogin')->name('login.student.facebook.callback');

    // Login google
    Route::get('student/login/google', 'Admin\Students\AuthController@loginGoogle')->name('login.student.google');
    Route::get('student/login/google/callback', 'Admin\Students\AuthController@callbackGoogleLogin')->name('login.student.google.callback');

    // Student register
    Route::get('student/register', 'Admin\Students\AuthController@registerStudent')->name('student.register');
    Route::post('student/register/step-1/validation', 'Admin\Students\AuthController@registerStep1Validation')->name('student.register.step1.validation');
    Route::post('student/register/step-1/send-mail-update-auth', 'Admin\Students\AuthController@sendMailUpdateAuth')->name('student.register.step1.send-mail');
    Route::post('student/register/step-2/save', 'Admin\Students\AuthController@registerStep2Save')->name('student.register.step2.save');
    Route::get('student/activation/{token}', 'Admin\Students\AuthController@activateUser')->name('student.register.activate');
    Route::post('student/register/step-3/validation-payment', 'Admin\Students\AuthController@validationPayment')->name('student.register.step2.validation-payment');
    Route::post('student/register/step-3/payment', 'Admin\Students\AuthController@handlePayment')->name('student.register.step2.handle-payment');
    Route::post('student/register/show-date-deadline', 'Admin\Students\AuthController@showDateDeadline')->name('student.register.show-date-deadline');

    Route::get('student/register/paypal', 'Admin\Students\AuthController@payPal')->name('student.register.paypal');

    Route::get('student/terms-of-service', 'Admin\Students\AuthController@termOfService')->name('student.terms-of-service');

    // Forgot password
    Route::get('forgot-password', 'Admin\ForgotPasswordController@forgot')->name('password.request');
    Route::post('forgot-password', 'Admin\ForgotPasswordController@sendLinkEmail')->name('password.email');
    Route::get('reset-password/{token}', 'Admin\ResetPasswordController@reset')->name('password.reset');
    Route::post('reset-password', 'Admin\ResetPasswordController@updateReset')->name('password.update');

    // Change language
    Route::get('change-language/{language}', 'HomeController@changeLanguage')->name('change-language');

    //Profile
    Route::group(['middleware' => ['auth'], 'prefix' => 'profile', 'namespace' => 'Admin'], function () {
        Route::get('/', 'ProfileController@profile')->name('profile');
        Route::post('change-password', 'ProfileController@changePassword')->name('profile.change-password');
        Route::post('profile', 'ProfileController@updateProfile')->name('profile.update');
    });

    // Notification
    Route::group(['middleware' => ['auth'], 'prefix' => 'notification', 'namespace' => 'Admin'], function () {
        Route::get('create', 'SendNotificationController@create')->name('notification.create');
        Route::post('store', 'SendNotificationController@store')->name('notification.store');
        Route::get('detail', 'SendNotificationController@detail')->name('notification.detail');
    });

    // MAIL
    Route::get('create-mail', 'Mail\MailController@createMail')->name('mail.create');
    Route::post('send-mail', 'Mail\MailController@sendMail')->name('mail.send');

    //TEACHER send mail
    Route::get('/activation-teacher/{new_email}/{token}', 'Admin\Teachers\AuthController@activateUser')->name('teacher.change.activate');

    //STUDENT send email when change
    Route::get('/activation-student/{new_email}/{token}', 'Admin\Students\AuthController@activateUserWhenChange')->name('student.change.activate');

    // File private : videos, image
    Route::group(['middleware' => ['auth'], 'prefix' => 'file'], function () {
        Route::get("images/{name}", 'FilePrivateController@images');
        Route::get("videos/{name}", 'FilePrivateController@videos');
    });

    // ADMIN
    Route::group(['middleware' => ['auth', 'AdminRole'], 'prefix' => 'admin', 'namespace' => 'Admin\Managers'], function () {
        // Home
//        Route::get('/', 'HomeController@index')->name('admin-dashboard');
        Route::get('/', function() {
            return Auth::user()->role == config('constants.role.admin') ? redirect()->route('admin.admin-list') : redirect()->route('admin.teacher.index');
        });

        //Admin list
        Route::get("/admin-list", 'AdminController@index')->name('admin.admin-list');
        Route::get('/admin-list/data-tables', 'AdminController@getListAdmins')->name('admin.admin-list.data-tables');
        Route::post('/admin-list/validate-search-form', 'AdminController@validateSearchForm')->name('admin.admin-list.validate');
        Route::post('/admin-list/delete', 'AdminController@deleteAdmins')->name('admin.admin-list.delete');
        Route::get('admin-list/create', 'AdminController@create')->name('admin.admin-list.create');
        Route::post('admin-list/create', 'AdminController@store')->name('admin.admin-list.store');
        Route::get('admin-list/detail/{user_id}', 'AdminController@detail')->name('admin.admin-list.detail');
        Route::post('admin-list/update-profile','AdminController@updateProfile')->name('admin.admin-list.update-profile');
        Route::post('admin-list/change-password','AdminController@changePassword')->name('admin.admin-list.change-password');
        Route::post('admin-list/reset-password/{id}','AdminController@resetPassword')->name('admin.admin-list.reset-password');

        // Course
        Route::get('/courses', 'CourseController@indexCourse')->name('admin.courses');
        Route::get('/detail/{id}', 'CourseController@detailCourse')->name('admin.course.detail');

        //curriculum management
        Route::get("/curriculum", 'CurriculumManagementController@index')->name('admin.curriculum');
        Route::get("/curriculum/data-tables", 'CurriculumManagementController@getListCurriculum')->name('admin.curriculum.data-tables');
        Route::get('/curriculum/create', 'CurriculumManagementController@create')->name('admin.curriculum.create');
        Route::get('/curriculum/{id}/detail', 'CurriculumManagementController@detail')->name('admin.curriculum.detail');

        //Booking list
        Route::get('/booking-list', 'BookingListController@bookingList')->name('admin.booking-list');
        Route::get('/booking-list/data-tables', 'BookingListController@getAllBooking')->name('admin.booking-list.data-tables');
        Route::get('/booking-list/search-live-nickname', 'BookingListController@searchLiveNickname')->name('admin.booking-list.search-live-nickname');
        Route::get('/booking-list/search-live-email', 'BookingListController@searchLiveEmail')->name('admin.booking-list.search-live-email');
        Route::post('/booking-list/validate-search-form', 'BookingListController@validateSearchForm')->name('admin.booking-list.validate-search-form');
        Route::get('/booking-list/detail', 'BookingListController@detail')->name('admin.booking-list.detail');
        Route::post('/booking-list/delete', 'BookingListController@deleteBooking')->name('admin.booking-list.delete');

        // Notification
        Route::get('/notifications', 'NotificationController@index')->name('admin-notification');
        Route::get('/notifications/data-table', 'NotificationController@notificationsDatatable')->name('admin.notification.data-table');
        Route::post('/notifications/search/validation', 'NotificationController@notificationListValidation')->name('admin.notification.search.validation');
        Route::post('/notifications/delete', 'NotificationController@deleteNotification')->name('admin.notification.delete');
        Route::get('/notifications/get-email', 'NotificationController@getEmail')->name('admin.notification.get-email');
        Route::get('/notification/create', 'NotificationController@create')->name('admin-notification.create');
        Route::get('/notification/detail/{id}', 'NotificationController@detail')->name('admin.notification.detail');
        Route::post('/notification/detail/{id}/validation', 'NotificationController@validateNotification')->name('admin.notification.detail.validation');
        Route::post('/notification/detail/{id}/update', 'NotificationController@updateNotification')->name('admin.notification.detail.update');
        Route::post('/notification/detail/{id}/to-list-notifications', 'NotificationController@toListNotification')->name('admin.notification.to-list-notifications');
        Route::post('/notification/insert', 'NotificationController@insertNotification')->name('admin-notification.insert');

        // Users
        Route::get('users', 'UserController@index')->name('user');
        Route::get('user-datatable', 'UserController@userDataTable')->name('user.data');
        Route::get('user/detail/{id}', 'UserController@detail')->name('user.detail');
        Route::get('user/edit/{id}', 'UserController@edit')->name('user.edit');
        Route::post('user/update', 'UserController@update')->name('user.update');
        Route::post('user/delete', 'UserController@delete')->name('user.delete');
        Route::post('user/delete-all', 'UserController@deleteAll')->name('user.delete-all');

        // List product in payment stripe
        Route::get('products-stripe', 'StripeController@index')->name('stripe.list-product');

        //Routes for create Planstudent
        Route::get('create/plan', 'SubscriptionController@createPlan')->name('create.plan');
        Route::post('store/plan', 'SubscriptionController@storePlan')->name('store.plan');

        // List plans in payment auto
        Route::get('plans', 'PlanController@index')->name('plans.list');
        Route::get('plan/create', 'PlanController@create')->name('plans.create');
        Route::post('plan/store', 'PlanController@store')->name('plans.store');
        Route::get('plan/{id}/edit', 'PlanController@edit')->name('plans.edit');
        Route::post('plan/update', 'PlanController@update')->name('plans.update');

        // lessons
        Route::get('lesson/history', 'LessonController@lessonHistory')->name('admin.lesson-history');
        Route::get('lesson/history/statistic-dataTable', 'LessonController@statisticDataTable')->name('admin.lesson-history.statistic-dataTable');
        Route::get('lesson/history/lesson-history-dataTable', 'LessonController@lessonHistoryDataTable')->name('admin.lesson-history.dataTable');
        Route::get('lesson/history/get-nick-name-by-id', 'LessonController@getTeacherNicknameById')->name('admin.lesson-history.getById');
        Route::get('lesson/history/get-nick-name-by-email', 'LessonController@getTeacherNicknameByEmail')->name('admin.lesson-history.getByEmail');
        Route::post('lesson/history', 'LessonController@validationSearch')->name('admin.validation-search.lesson-history');
        Route::get('lesson/history/lesson-histories-dataTable', 'LessonController@lessonHistoriesDataTable')->name('admin.lesson-history.lesson-histories-dataTable');

        // Export to Excel
        Route::get('lesson/history/export-excel', 'LessonController@exportToExcel')->name('admin.lesson-history.export-to-excel');

        // Zoom manager
        Route::get('zoom', 'ZoomController@index')->name('zoom.index');

        // List Students
        Route::get('students', 'StudentController@index')->name('admin.student.index');
        Route::get('students/datatable', 'StudentController@studentDataTable')->name('student.data');
        Route::get('student/create', 'StudentController@create')->name('admin.student.create');
        Route::post('student/create', 'StudentController@addStudent')->name('admin.student.add');
        Route::post('student/delete-all', 'StudentController@deleteAll')->name('admin.student.delete-all');
        Route::post('student/validation', 'StudentController@studentValidation')->name('admin.student.validation');
        Route::get('student/detail/{user_id}', 'StudentController@detail')->name('admin.student.detail');

        // List Teachers
        Route::get('teachers', 'TeacherController@index')->name('admin.teacher.index');
        Route::get('teachers-datatable', 'TeacherController@teachersDataTable')->name('admin.teacher.data');
        Route::post('teacher-list-validation', 'TeacherController@teacherListValidation')->name('admin.teacher.validation');
        Route::post('delete-teacher', 'TeacherController@deleteTeacher')->name('admin.teacher.delete');
        Route::get('teacher/create', 'TeacherController@create')->name('admin.teacher.create');
        Route::post('teacher/create', 'TeacherController@addTeacher')->name('admin.teacher.add');
        Route::get('teacher/detail/{user_id}', 'TeacherController@detail')->name('admin.teacher.detail');
        Route::get('teacher/detail/booking-substitute/{user_id}', 'TeacherBookingSubstituteController@bookingSubstitute')->name('admin.teacher.bookingSubstitute');
        Route::post('teacher/detail/booking-substitute/{id}/validate', 'TeacherBookingSubstituteController@validateBookLesson')->name('admin.teacher.booking-substitute.validate');
        Route::post('teacher/detail/booking-substitute/{id}/validate/coin', 'TeacherBookingSubstituteController@validateCoinEnough')->name('admin.teacher.booking-substitute.validate-student-coin');
        Route::post('teacher/detail/booking-substitute', 'TeacherBookingSubstituteController@toAdminBookingList')->name('student.toAdminBookingLessonList');
        Route::get('teacher/detail/booking-substitute/{id}/get-lessons', 'TeacherBookingSubstituteController@getLessons')->name('admin.teacher.booking-substitute.get-lesson-by-course');
        Route::post('teacher/detail/booking-substitute/{id}/get-student-lesson-info', 'TeacherBookingSubstituteController@getStudentLesson')->name('admin.teacher.booking-substitute.get-student-lesson-info');

        //teachers' detail
        Route::post('teacher/reset-password/{id}','TeacherController@resetPassword')->name('admin.teacher.reset-password');
        Route::post('teacher/update-profile/{id}','TeacherController@updateProfile')->name('admin.teacher.update-profile');

        //student' detail
        Route::post('student/reset-password/{id}','StudentController@resetPassword')->name('admin.student.reset-password');
        Route::post('student/update-profile/{id}','StudentController@updateProfile')->name('admin.student.update-profile');
        Route::post('student/refund-coin/{id}','StudentController@refundCoin')->name('admin.student.refund-coin');

        // Test insert image S3 : demo
        Route::get('s3', 'S3Controller@index')->name('admin.s3.index');
        Route::post('s3/create', 'S3Controller@create')->name('admin.s3.create');
        Route::get('s3/show', 'S3Controller@show')->name('admin.s3.show');

        Route::get('payment-information', 'StudentPaymentInformationController@index')->name('admin.payment.index');
        Route::post('payment-information/validate-search-form', 'StudentPaymentInformationController@validateSearchForm')->name('admin.payment.validate');
        Route::get('payment-information/search-email', 'StudentPaymentInformationController@searchEmail')->name('admin.payment.search-email');

        // Calendar google : demo
        Route::get('calendar', 'CalendarController@index')->name('admin.calendar.index');
        Route::post('calendar', 'CalendarController@createEvent')->name('admin.calendar.createEvent');

        //Teacher booking substitute
        Route::get('students/booking-substitute', 'TeacherBookingSubstituteController@studentDataTable')->name('student.booking-substitute.data');
        Route::post('student/booking-substitute/validation', 'TeacherBookingSubstituteController@studentValidation')->name('admin.student.booking-substitute.validation');

        // Select 2 ajax search : demo
        Route::get('select2', 'Select2AutocompleteController@index')->name('select2.index');
        Route::get('select2-autocomplete-ajax', 'Select2AutocompleteController@dataAjax')->name('select2.data-ajax');

        // Timezone convert from local : demo
        Route::get('timezone', 'Select2AutocompleteController@timezone')->name('select2.timezone');
        Route::post('convertTimezone', 'Select2AutocompleteController@convertTimezone')->name('select2.convertTimezone');
    });

    // TEACHER
    Route::group(['middleware' => ['auth', 'TeacherRole'], 'prefix' => 'teacher', 'namespace' => 'Admin\Teachers'], function () {
        App::setLocale('ja');
        Route::get('/', 'HomeController@index')->name('teacher-dashboard');
        Route::get('/edit-profile', 'HomeController@editProfile')->name('teacher.edit-profile');
        Route::get('/add-schedule', 'ScheduleController@addSchedule')->name('teacher.addSchedule');
        Route::post('/add-schedule/validation', 'ScheduleController@validateSchedule')->name('teacher.validateSchedule');
        Route::post('/add-schedule', 'ScheduleController@toListSchedule')->name('teacher.toListSchedule');
        Route::get('/list-schedule', 'ScheduleController@listSchedule')->name('teacher.listSchedule');
        Route::post('/list-schedule/validation', 'ScheduleController@validationTime')->name('teacher.listSchedule.validation');

        //my-page
        Route::get('/my-page', 'MyPageController@myPage')->name('teacher.my-page');
        Route::get('/my-page/today-schedule/datatable','MyPageController@getTodayScheduleDataTable')->name('today-schedule.datatable');
        Route::post('/my-page/push-notification-student','MyPageController@notifyToStudentWhenClickButtonStart')->name('notify-student-start-lesson');
        Route::post('/my-page/teacher-cancel-lesson','MyPageController@notifyToStudentTeacherCancelLesson')->name('teacher-cancel-lesson');
        Route::post('/my-page/teacher-start-lesson','MyPageController@notifyToStudentTeacherStartLesson')->name('teacher-start-lesson');
        // notification
        Route::get('/notification', 'NotificationController@index')->name('teacher-notification');
        Route::get('/notification/detail/{id}', 'NotificationController@detail')->name('teacher-notification-detail');
        Route::get('/notifications/data-table', 'NotificationController@notificationsDatatable')->name('teacher.notification.data-table');
        Route::get('/notifications/get-email', 'NotificationController@getEmail')->name('teacher.notification.get-email');
        Route::post('/notifications/search/validation', 'NotificationController@notificationListValidation')->name('teacher.notification.search.validation');
        // lesson
        Route::get('/lesson/history', 'LessonController@history')->name('teacher.lesson.history');
        Route::get('/lesson/history/data-tables', 'LessonController@getData')->name('teacher.lesson-history.data-tables');
        Route::get('/lesson/history/search-live-nickname', 'LessonController@searchLiveNickname')->name('teacher.lesson-histories.search-live-nickname');
        Route::get('/lesson/history/search-live-email', 'LessonController@searchLiveEmail')->name('teacher.lesson-histories.search-live-email');
        Route::post('/lesson/history/validate-search-form', 'LessonController@validateSearch')->name('teacher.lesson-history.validate-search-form');

        Route::post('/edit-profile/update','HomeController@updateProfile')->name("teacher.update-profile");
        Route::post('/change-password', 'HomeController@changePassword')->name('teacher.change-password');
        Route::post('/change-email', 'HomeController@changeEmail')->name('teacher.change-email');

        Route::post('validate-link-youtube','HomeController@validateLinkYoutube')->name('teacher.validate-link-youtube');
        Route::post('start-meeting-room','HomeController@startMeetingRoomWithStudent')->name('teacher.start-meeting-room');
        Route::post('send-cancel-to-student','HomeController@sendCancelToStudent')->name('teacher.send-cancel-to-student');

        // Course
        Route::get('/courses', 'HomeController@indexCourse')->name('teacher.courses');
        Route::get('/course/detail/{id}', 'HomeController@detailCourse')->name('teacher.course.detail');
    });

    // STUDENT
    Route::group(['middleware' => ['auth', 'StudentRole','limitRoute'], 'prefix' => 'student', 'namespace' => 'Admin\Students'], function () {
        Route::get('/', 'HomeController@index')->name('student-dashboard');
        Route::post('validation', 'HomeController@studentValidation')->name('student.validation');
        Route::post('/', 'HomeController@studentSearch')->name('student.search.home');
        Route::get('/notification', 'NotificationController@index')->name('student-notification');
        Route::get('/notification/detail/{id}', 'NotificationController@detail')->name('student-notification-detail');
        Route::get('/notifications/data-table', 'NotificationController@notificationsDatatable')->name('student.notification.data-table');
        Route::post('/notifications/validation', 'NotificationController@notificationListValidation')->name('student.notification.search.validation');
        //Booking - lesson
        Route::get('/book-lesson/{id}', 'HomeController@bookLesson')->name('student.book-lesson');
        Route::post('/book-lesson/{id}/validate', 'BookLessonController@validateBookLesson')->name('student.book-lesson.validate');
        Route::post('/book-lesson', 'BookLessonController@toBookingList')->name('student.toBookingLessonList');
        Route::post('/book-lesson/{id}/validate/coin', 'BookLessonController@validateCoinEnough')->name('student.book-lesson.validate-coin');
        Route::get('/book-lesson/{id}/get-teacher-schedule', 'BookLessonController@getTeacherSchedule')->name('student.get-teacher-schedule');
        Route::get('/book-lesson/{id}/get-lessons', 'BookLessonController@getLessons')->name('student.get-lesson-by-course');
        Route::post('/book-lesson/{id}/get-student-lesson-info', 'BookLessonController@getStudentLesson')->name('student.get-student-lesson-info');

//        Route::post('/book-lesson/{id}/validate-lesson', 'BookLessonController@validateLesson')->name('student.validate-lesson');

        Route::get('lesson/list', 'HomeController@bookingList')->name('student.lesson.list');
        Route::post('lesson/list/getCourse', 'HomeController@getCourseCanTeachByTeacherId')->name('student.lesson.list.getCourse');
        Route::post('lesson/list/update', 'HomeController@updateLessonBooked')->name('student.lesson.list.update');
        Route::post('remove-booking/list', 'BookLessonController@removeBookingList')->name('student.removeBooking.list');
        Route::post('remove-booking/check-time', 'BookLessonController@checkTimeRemove')->name('student.removeBooking.check-time');
        Route::get('lesson/history', 'BookLessonController@lessonHistory')->name('student.lesson.history');
        Route::get('lesson/history/data-table', 'BookLessonController@getListHistoryDataTable')->name('student.list-history-datatable');
        // review lesson
        Route::post('lesson/histories' , 'BookLessonController@checkHistories')->name('student.checkHistories');
        Route::post('lesson/histories/review' , 'BookLessonController@reviewLesson')->name('student.reviewLesson');

        Route::post('book-schedule/{id}', 'HomeController@pushNotificationToTeacherWhenFirmlyBooked')->name('student.book-schedule');
        Route::post('push-notification/{id}', 'HomeController@pushNotificationToTeacherWhenBooked')->name('student.push-notification-to-teacher');
        Route::post('push-notification-canceled/{id}', 'HomeController@pushNotificationToTeacherWhenCanceled')->name('student.push-notification-to-teacher-when-canceled');
        Route::post('push-notification-teacher/{id}', 'HomeController@pushNotificationToTeacherWhenStart')->name('student.push-notification-to-teacher-when-start');
        Route::post('push-notification-teacher-closed/{id}', 'HomeController@pushNotificationToTeacherWhenClosed')->name('student.push-notification-to-teacher-when-close');
        Route::post('push-request-cancel', 'HomeController@pushRequestCancel')->name('student.push-request-cancel');

        Route::post('payment/validation-payment', 'PaymentController@validationPaymentCredit')->name('student.payment.validation');
        // Add information payment for package: trial
        Route::get('payment/7-days-free-trial', 'PaymentController@get7DaysTrial')->name('student.payment.7-days-free-trial');
        Route::post('payment/7-days-free-trial', 'PaymentController@save7DaysTrial')->name('student.payment.7-days-free-trial.save');

        // Add information payment for package: premium
        Route::get('payment/premium', 'PaymentController@getPremium')->name('student.payment.premium');
        Route::post('payment/premium', 'PaymentController@saveGetPremium')->name('student.payment.premium.save');

        //Update Course - lesson
        Route::post('/update-course', 'HomeController@updateCourse')->name('student.update-course');

        //Start-lesson
        Route::post('/start-lesson', 'HomeController@notifyToTeacherStartLesson')->name('student.notify-start-lesson');
        Route::post('/after-5-minutes', 'HomeController@notifyToTeacherAfter5Minutes')->name('student.notify-after-5-minutes');

        // Setting password for student when login social: face, google, zalo
        Route::get('setting/password', 'SettingPasswordController@settingPassword')->name('student-password');
        Route::post('setting/password', 'SettingPasswordController@updatePassword')->name('student_update_password');

        // Payments
        Route::get('invoice', 'InvoiceController@index')->name('student-invoice');
        Route::get('payments', 'PaypalController@index')->name('student-payment');
        Route::get('payments/status', 'PaypalController@status');
        Route::get('payments/list', 'PaypalController@paymentList');
        Route::get('payments/history', 'HomeController@paymentHistory')->name('student.payments.history');
        Route::get('datatable-payment-histories', 'HomeController@getPaymentHistories')->name('student.datatable.payment-histories');
        Route::get('visa-payment', 'StripeController@index')->name('stripe.index');
        Route::post('visa-payment', 'StripeController@handlePost')->name('stripe.payment');

        // Payments auto stripe subscription
        Route::get('/plans', 'PlanController@index')->name('plans.index');
        Route::get('/plan/{plan}', 'PlanController@show')->name('plans.show');
        Route::post('/subscription', 'SubscriptionController@create')->name('subscription.create');

        // List subscription of user (student)
        Route::get('/subscription/list', 'SubscriptionController@listSub')->name('subscription.list');
        Route::post('/subscription/cancel', 'SubscriptionController@cancel')->name('subscription.cancel');

        // Coin
        Route::get('add-coin', 'AddCoinController@index')->name('student.add-coin');
        Route::post('add-coin/validation', 'AddCoinController@validationPaymentCoin')->name('student.payment-coin.validation');
        Route::post('add-coin/payment', 'AddCoinController@paymentCoin')->name('student.payment-coin');
        Route::get('add-coin/history/datatable', 'AddCoinController@historyDataTable')->name('student.add-coin.history');
        Route::post('add-coin/check-cancel-premium', 'AddCoinController@checkCancelPremium')->name('student.check-cancel-premium');

        //profile
        Route::get('/profile', 'ProfileController@index')->name('student.profile');
        Route::post('/change-nickname','ProfileController@changeNickname')->name('student.change-nickname');
        Route::post('/change-password','ProfileController@changePassword')->name('student.change-password');
        Route::post('/change-email','ProfileController@changeEmail')->name('student.change-email');
        Route::post('/update-profile','ProfileController@updateProfile')->name('student.update-profile');

        // Cancel subscriptions payments
        Route::post('check-cancel-trial-plan', 'ProfileController@checkCancelTrialPlan')->name('student.check-cancel-trial-plan');
        Route::post('cancel-trial-plan', 'ProfileController@cancelTrialPlan')->name('student.cancel-trial-plan');
        Route::post('check-cancel-premium-plan', 'ProfileController@checkCancelPremiumPlan')->name('student.check-cancel-premium-plan');
        Route::post('cancel-premium-plan', 'ProfileController@cancelPremiumPlan')->name('student.cancel-premium-plan');

        // Course
        Route::get('/courses', 'HomeController@indexCourse')->name('student.courses');
        Route::get('/course/detail/{id}', 'HomeController@detailCourse')->name('student.course.detail');

    });

});
