@if(Auth::user()->role == config('constants.role.student') || Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin'))
<aside class="main-sidebar sidebar-dark-primary elevation-4" >
    <!-- Brand Logo -->
    <a href="/" class="brand-link  @if(Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin')) navbar-primary @elseif(Auth::user()->role == config('constants.role.teacher')) navbar-danger @else navbar-warning @endif">
        <img src="{{ asset('images/AdminLTELogo.png') }}" alt="japanese-study" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Japanese-Study</span>
    </a>

    <!-- Sidebar -->
        <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                @if(Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin'))
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin-dashboard') }}" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-tachometer-alt"></i>--}}
{{--                            <p>--}}
{{--                                ダッシュボード--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
{{--                    <li class="nav-item">--}}
{{--                        <a href="#" class="nav-link">--}}
{{--                            <i class="nav-icon fas fa-user"></i>--}}
{{--                            <p>--}}
{{--                                アドミン一覧--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    @if(Auth::user()->role == config('constants.role.admin'))
                    <li class="nav-item">
                        <a href="{{ route('admin.admin-list') }}" class="nav-link {{ request()->is('admin/admin-list') || request()->is('admin/admin-list/create') || request()->is('admin/admin-list/detail/*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>
                                アドミン一覧
                            </p>
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{ route('admin.teacher.index') }}" class="nav-link {{ request()->is('admin/teachers') || request()->is('admin/teacher/create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                講師一覧
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.student.index') }}" class="nav-link {{ request()->is('admin/students') || request()->is('admin/student/create') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>
                                生徒一覧
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href=" {{ route('admin.payment.index') }}" class="nav-link {{ request()->is('admin/payment-information')  ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>
                                決済一覧
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin-notification') }}" class="nav-link {{ request()->is('admin/notifications') || request()->is('admin/notification/detail/*') || request()->is('admin/notification/create') ? 'active' : '' }}">
                            <i class="nav-icon far fa-bell"></i>
                            <p>
                                通知一覧
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('plans.list') }}" class="nav-link {{ request()->is('admin/plans') || request()->is('admin/plan/create') || request()->is('admin/plan/*/edit') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-box-open"></i>
                            <p>
                                プラン一覧
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.lesson-history') }}" class="nav-link {{ request()->is('admin/lesson/history')  ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>
                                レッスン履歴
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.booking-list') }}" class="nav-link {{ request()->is('admin/booking-list')  ? 'active' : '' }}">
                            <i class="nav-icon far fa-calendar-alt"></i>
                            <p>
                                予約一覧
                            </p>
                        </a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a href="{{ route('admin.curriculum') }}" class="nav-link {{ request()->is('admin/curriculum')  ? 'active' : '' }}">--}}
{{--                            <i class="nav-icon fas fa-book"></i>--}}
{{--                            <p>--}}
{{--                                カリキュラム一覧--}}
{{--                            </p>--}}
{{--                        </a>--}}
{{--                    </li>--}}
                    <li class="nav-item">
                        <a href="{{ route('admin.courses') }}" class="nav-link @if( in_array((explode('/', Request::path())[0] . '/' . explode('/', Request::path())[1]), ['admin/courses', 'admin/detail']) ) active @endif">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                コース一覧
                            </p>
                        </a>
                    </li>
                    @endif

                    @if(Auth::user()->role == config('constants.role.teacher'))

                    @endif

                    @if(Auth::user()->role == config('constants.role.student'))
                        <li class="nav-item">
                            <a href="{{ route('student-invoice') }}" class="nav-link">
                                <i class="nav-icon fas fa-columns"></i>
                                <p>
                                    Invoice - test paypal (Student)
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('plans.index') }}" class="nav-link">
                                <i class="nav-icon fas fa-columns"></i>
                                <p>
                                    Stripe subscription auto (Student)
                                </p>
                            </a>
                        </li>

                    <li class="nav-item">
                        <a href="{{ route('subscription.list') }}" class="nav-link">
                            <i class="nav-icon fas fa-columns"></i>
                            <p>
                                List subscription (Student)
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="" class="nav-link {{ request()->is('admin/admin.lesson.history') || request()->is('admin/admin.lesson.history') || request()->is('admin/admin.lesson.history') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clock"></i>
                            <p>
                                レッスン履歴
                            </p>
                        </a>
                    </li>
                @endif
{{--                <li class="nav-item">--}}
{{--                    <a href="#" class="nav-link">--}}
{{--                        <i class="nav-icon fas fa-circle"></i>--}}
{{--                        <p>--}}
{{--                            Searching--}}
{{--                            <i class="right fas fa-angle-left"></i>--}}
{{--                        </p>--}}
{{--                    </a>--}}
{{--                    <ul class="nav nav-treeview">--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('stripe.list-product') }}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>--}}
{{--                                    List package stripe (Admin)--}}
{{--                                </p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('user') }}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>--}}
{{--                                    User (Admin)--}}
{{--                                </p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                        <li class="nav-item">--}}
{{--                            <a href="{{ route('notification.create') }}" class="nav-link">--}}
{{--                                <i class="far fa-circle nav-icon"></i>--}}
{{--                                <p>Notification Realtime</p>--}}
{{--                            </a>--}}
{{--                        </li>--}}
{{--                    </ul>--}}
{{--                </li>--}}
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
@endif
@if(Auth::user()->role == config('constants.role.teacher'))
<style>
.div-avatar {

}
.box{
    border-radius: 50%;
    height: 100px;
    width:100px;
    @if(!is_null($avatar_image->image_photo))
    background-image: url("{{ $avatar_image->image_photo }}");
    @else
    background-image: url("{{ asset('images/avatar_2.png') }}");
    @endif
    background-position: center;
    background-size: cover;
    position: relative;
    display: block;
    margin-left: auto;
    margin-right: auto;
}
.upload{
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    font-size: 50px;
    color: #FFF;
    position: absolute;
    height: 50px;
    background: linear-gradient(0deg, rgba(0,212,255,1) 0%, rgba(14,13,13,1) 0%, rgba(0,0,0,0) 100%);
    width: inherit;
    top: 46px;
    left: -2px;
    border-radius: 0 0 50px 50px;
    opacity: 0;
}
.upload > label {
    font-size: 50%;
}

#upload-photo {
    opacity: 0;
    position: absolute;
    z-index: -1;
}
.remove-image {
    position: absolute;
    top: -5px;
    right: -3px;
    /* left: 3px; */
    font-size: 25px;
    opacity: 0;
    font-weight: bold;
}
@if(Request::path() == 'teacher/edit-profile')

.upload:hover{
    opacity: 0.5;
}
#box:hover #remove-image {
    opacity: 1;
}
@endif
</style>

<aside class="main-sidebar sidebar-light-primary elevation-4" style="overflow: hidden !important;">
    <!-- Brand Logo -->
    <a href="/" class="brand-link  @if(Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin')) navbar-primary @elseif(Auth::user()->role == config('constants.role.teacher')) navbar-danger @else navbar-warning @endif">
        <img src="{{ asset('images/AdminLTELogo.png') }}" alt="japanese-study" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Japanese-Study</span>
    </a>

    <!-- Sidebar -->
    <div class="pt-3 h-100 border-right">
        <div class="row mx-0">
            <div class="col-12 col-sm-12 d-flex flex-column align-items-center d-sm-flex flex-sm-column align-items-sm-center">
                <div class="div-avatar">
                    <div class="box profile-user-img" id="box">
                        @if(Request::path() == 'teacher/edit-profile')
                        <div class="upload">
                            <input type='file' name="photo" id="upload-photo" />
                            <label for="upload-photo"><i class="fas fa-camera"></i></label>
                        </div>
                        @endif
                        <div class="remove-image" id="remove-image">
                            <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                        </div>
                    </div>
                </div>
                <span class="text-danger text-center" id="error-upload_photo"></span>
                <p class="text-center mb-1" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" id="sidebar_nickname" data-toggle="tooltip" data-placement="top" title="{{ Auth::user()->nickname }}">{{ Auth::user()->nickname }}</p>
                <p class="text-center mb-1">講師ID：{{ Auth::user()->id }}</p>
                <p class="text-center mb-1">レッスン数： {{$numberOfLesson}}回</p>
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                        <li class="nav-item">
                            <a href="{{ route('teacher.my-page') }}" class="nav-link @if(Request::path() == 'teacher/my-page') active @endif">
                                <i class="nav-icon fa fa-address-book"></i>
                                <p>
                                    マイページ
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.edit-profile') }}" class="nav-link @if(Request::path() == 'teacher/edit-profile') active @endif">
                                <i class="nav-icon fas fa-edit"></i>
                                <p>
                                    プロフィール設定
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.listSchedule') }}" class="nav-link @if(Request::path() == 'teacher/add-schedule' || Request::path() == 'teacher/list-schedule') active @endif">
                                <i class="nav-icon fas fa-table"></i>
                                <p>
                                    スケジュール一覧
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.lesson.history') }}" class="nav-link @if(Request::path() == 'teacher/lesson/history') active @endif">
                                <i class="nav-icon fas fa-calendar"></i>
                                <p>
                                    レッスン履歴
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('teacher.courses') }}" class="nav-link @if( in_array((explode('/', Request::path())[0] . '/' . explode('/', Request::path())[1]), ['teacher/courses', 'teacher/course']) ) active @endif">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    コース一覧
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
    <!-- /.sidebar -->
</aside>
@endif
