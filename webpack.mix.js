const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/admin/app.js', 'public/js/admin')
    .js('resources/js/admin/home.js', 'public/js/admin')
    .js('resources/js/admin/notification/index.js', 'public/js/admin/notification')
    .js('resources/js/admin/users/index.js', 'public/js/admin/users')
    .js('resources/js/admin/users/edit.js', 'public/js/admin/users')
    .js('resources/js/admin/users/detail.js', 'public/js/admin/users')
    .js('resources/js/admin/managers/students/index.js', 'public/js/admin/managers/students')
    .js('resources/js/admin/managers/teachers/index.js', 'public/js/admin/managers/teachers')
    .js('resources/js/admin/profiles/profile.js', 'public/js/admin/profiles')
    .js('resources/js/admin/managers/students/create.js', 'public/js/admin/managers/students')
    .js('resources/js/admin/managers/teachers/create.js', 'public/js/admin/managers/teachers')
    .js('resources/js/admin/managers/teachers/bookingSubstitute.js', 'public/js/admin/managers/teachers')
    .js('resources/js/admin/teachers/mypage.js', 'public/js/admin/teachers')
    .js('resources/js/admin/teachers/edit.js', 'public/js/admin/teachers')
    .js('resources/js/admin/managers/teachers/detail.js', 'public/js/admin/managers/teachers')
    .js('resources/js/admin/managers/students/detail.js', 'public/js/admin/managers/students')
    .js('resources/js/admin/managers/notification/index.js', 'public/js/admin/managers/notification')
    .js('resources/js/admin/managers/notification/detail.js', 'public/js/admin/managers/notification')
    .js('resources/js/admin/managers/lessons/lessonHistory.js', 'public/js/admin/managers/lessons')
    .js('resources/js/admin/teachers/notification.js', 'public/js/admin/teachers')
    .js('resources/js/admin/students/create.js', 'public/js/admin/students')
    .js('resources/js/admin/students/register.js', 'public/js/admin/students')
    .js('resources/js/admin/students/register/index.js', 'public/js/admin/students/register')
    .js('resources/js/admin/students/register/step1.js', 'public/js/admin/students/register')
    .js('resources/js/admin/students/register/step2.js', 'public/js/admin/students/register')
    .js('resources/js/admin/students/getPremium/step1.js', 'public/js/admin/students/getPremium')
    .js('resources/js/admin/students/getTrial/index.js', 'public/js/admin/students/getTrial')
    .js('resources/js/admin/students/getPremium/index.js', 'public/js/admin/students/getPremium')
    .js('resources/js/admin/students/lessons/student_book_lesson.js', 'public/js/admin/students/lessons')
    .js('resources/js/admin/students/lessons/student_lesson_history.js', 'public/js/admin/students/lessons')
    .js('resources/js/admin/teachers/addSchedule.js', 'public/js/admin/teachers')
    .js('resources/js/admin/teachers/listSchedule.js', 'public/js/admin/teachers')
    .js('resources/js/admin/students/lessonBooking.js', 'public/js/admin/students')
    .js('resources/js/admin/students/addCoin/index.js', 'public/js/admin/students/addCoin')
    .js('resources/js/admin/students/notification.js', 'public/js/admin/students')
    .js('resources/js/admin/students/panel.js', 'public/js/admin/students')
    .js('resources/js/admin/students/myPage.js', 'public/js/admin/students')
    .js('resources/js/admin/managers/admin/login.js', 'public/js/admin/managers/admin')
    .js('resources/js/admin/students/review_student.js', 'public/js/admin/students')
    .js('resources/js/student/app.js', 'public/js/student')
    .js('resources/js/student/home.js', 'public/js/student')
    .js('resources/js/student/profile.js', 'public/js/student')
    .js('resources/js/student/payment_histories.js', 'public/js/student')
    .js('resources/js/admin/managers/admin/paymentIntents.js', 'public/js/admin/managers/admin')
    .js('resources/js/admin/booking-list/index.js', 'public/js/admin/booking-list')
    .js('resources/js/admin/teachers/lesson_histories/index.js', 'public/js/admin/teachers/lesson_histories')
    .js('resources/js/admin/managers/AdminList/index.js', 'public/js/admin/managers/AdminList')
    .js('resources/js/admin/managers/AdminList/create.js', 'public/js/admin/managers/AdminList')
    .js('resources/js/admin/managers/AdminList/edit.js', 'public/js/admin/managers/AdminList')
    .js('resources/js/admin/managers/courses/detail.js', 'public/js/admin/managers/courses')
    .js('resources/js/admin/teachers/courses/detail.js', 'public/js/admin/teachers/courses')
    .js('resources/js/admin/students/courses/detail.js', 'public/js/admin/students/courses')
    .js('resources/js/admin/teachers/notification/detail.js', 'public/js/admin/teachers/notification')
    .js('resources/js/admin/managers/CurriculumManagement/index.js', 'public/js/admin/managers/CurriculumManagement')
    .version();

mix.sass('resources/sass/app.scss', 'public/css')
    .sass('resources/sass/admin/manager/login.scss', 'public/css/admin/manager')
    .sass('resources/sass/admin/app.scss', 'public/css/admin')
    .sass('resources/sass/admin/students/step.scss', 'public/css/admin/students')
    .sass('resources/sass/admin/teachers/mypage.scss', 'public/css/admin/teachers')
    .sass('resources/sass/admin/manager/students/index.scss', 'public/css/admin/manager/students')
    .sass('resources/sass/admin/manager/teachers/index.scss', 'public/css/admin/manager/teachers')
    .sass('resources/sass/admin/manager/students/create.scss', 'public/css/admin/manager/students')
    .sass('resources/sass/admin/manager/teachers/create.scss', 'public/css/admin/manager/teachers')
    .sass('resources/sass/admin/manager/StudentPaymentInformation/infor.scss', 'public/css/admin/manager/StudentPaymentInformation')
    .sass('resources/sass/student/home.scss', 'public/css/student')
    .sass('resources/sass/admin/teachers/edit.scss', 'public/css/admin/teachers')
    .version();
