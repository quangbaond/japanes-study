<style>
    li :hover {
        color: blue !important;
    }
</style>
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white p-0"
     style="background-color: rgba(50, 115, 220, 0.3);">
    <!--background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);  -->
    <div class="container  mw-100 px-0 mx-0">
        <div class="row w-100 d-flex justify-content-between">
            <div class="col-4 d-flex col-sm-6 d-sm-flex justify-content-sm-center p-sm-3  pr-sm-5">
                <a href="{{  route('student-dashboard') }}"
                   class="navbar-brand d-flex mt-2 align-items-center ml-3 d-sm-flex align-items-sm-center pr-sm-5">
                    <img src="{{ asset('images/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                         class="brand-image img-circle elevation-3 " style="opacity: .8">
                    <span class="brand-text font-weight-light d-none d-sm-block" style="font-size: 25px;">Student Japanese</span>
                </a>

                <button class="navbar-toggler order-1" type="button" data-toggle="collapse"
                        data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false"
                        aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
            <div class="col-5 d-flex col-sm-4 d-sm-flex justify-content-sm-end p-sm-3 ml-sm-5">
                {{-- For phone --}}
                <div class="d-block d-flex justify-content-end align-items-center d-sm-none">
                    @if( ($user->membership_status == null || $user->membership_status == config('constants.membership.id.free')) && ($user->user_payment_info_id == null))
                        @if(Auth::user()->role == config('constants.role.student') && Request::path() != 'student/payment/7-days-free-trial' && Request::path() != 'student/payment/premium')
                            @if(Auth::user()->role == config('constants.role.student') && Request::path() != 'student/payment/7-days-free-trial' && Request::path() != 'student/payment/premium')
                                <a href="{{ route('student.payment.7-days-free-trial') }}"><span class="badge badge-secondary mr-3">Get 7 days free trial</span></a>
                            @endif
                        @endif
                    @elseif($user->membership_status == config('constants.membership.id.free') && $user->user_payment_info_id != null)
                        @if(Auth::user()->role == config('constants.role.student') && Request::path() != 'student/payment/7-days-free-trial' && Request::path() != 'student/payment/premium')
                            <a href="{{ route('student.payment.premium') }}"><span class="badge badge-warning">{{ __('header.student.get_premium') }}</span></a>
                        @endif
                    @endif
                </div>
                {{-- For desktop --}}
                <div class="d-none d-sm-block d-sm-flex align-items-sm-center">
                    @if( ($user->membership_status == null || $user->membership_status == config('constants.membership.id.free')) && ($user->user_payment_info_id == null))
                        @if(Auth::user()->role == config('constants.role.student') && Request::path() != 'student/payment/7-days-free-trial' && Request::path() != 'student/payment/premium')
                            <a href="{{ route('student.payment.7-days-free-trial') }}">
                                <button type="button" class="btn btn-warning mr-sm-3">{{ __('header.student.get_trial') }}</button>
                            </a>
                        @endif
                    @elseif($user->membership_status == config('constants.membership.id.free') && $user->user_payment_info_id != null)
                        @if(Auth::user()->role == config('constants.role.student') && Request::path() != 'student/payment/7-days-free-trial' && Request::path() != 'student/payment/premium')
                            <a href="{{ route('student.payment.premium') }}"><button type="button" class="btn btn-success">{{ __('header.student.get_premium') }}</button></a>
                        @endif
                    @endif
                </div>
            </div>
            <div class="col-3 d-flex align-items-center justify-content-center col-sm-1 d-sm-flex p-sm-3">
                <div class="div">
                    <a class="nav-link ml-0" data-toggle="dropdown" href="#" style="padding-right: 0px !important;">
                        <img src="{{ asset('images/multi-language.png') }}" alt="multi-language" style="width: 30px; height: 30px">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ url('change-language/en') }}"  class="dropdown-item dropdown-footer">{{ __('header.english') }}</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('change-language/vi') }}"  class="dropdown-item dropdown-footer">{{ __('header.viet_nam') }}</a>
                    </div>
                </div>
                <div class="div">
                    <a class="nav-link px-1 pl-sm-3" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge" id="count_unread_notification">

                            {{$getNotificationStudent['num_read_at']}}

                        </span>
                    </a>
{{--                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">--}}
{{--                    <span class="dropdown-item dropdown-header">All: <span id="count_all_notification">0</span> Notifications</span>--}}
{{--                    <div id="element_notification">--}}
{{--                        @foreach (Auth::user()->unreadNotifications as $unreadNotification)--}}
{{--                            <div class="dropdown-divider"></div>--}}
{{--                            <a href="javascript:;" class="dropdown-item choice_notification" data-id="0">--}}
{{--                                <i class="fas fa-envelope mr-2"></i>laravel Notifications--}}
{{--                                <span class="float-right text-muted text-sm">04/03/2021</span>--}}
{{--                            </a>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                    <div class="dropdown-divider"></div>--}}
{{--                </div>--}}
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width : 348px; max-width : 400px;">
                        <span class="dropdown-item font-weight-bold dropdown-header" style="text-align: left; color: black">
                            {{__('student.notification')}}
                        </span>
                        <div id="element_notification">
                            <div class="dropdown-divider"></div>
                            @foreach($getNotificationStudent['notifications'] as $item)
                                <a href="{{route('student-notification-detail' , $item->id)}}" class="dropdown-item choice_notification" data-id="" >
                                    @if($item->read_at == NULL)
                                    <i class="fas fa-envelope mr-2"></i><b class="font-weight-bold " style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 170px"><span >{{$item->title}}</span></b>
                                    @else
                                    <i class="fas fa-envelope mr-2"></i><span style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 170px">{{$item->title}}</span>
                                    @endif
                                    <span class="float-right text-muted text-sm">{{$item->created_at}}</span>
                                </a>
                            @endforeach
                        </div>
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('student-notification')}}" class="dropdown-item dropdown-footer" style="text-align: left">{{__('student.see_all_notification')}}</a>
                    </div>
                </div>
                <div class="">
                    <a class="nav-link text-white pl-1 pl-sm-2 mt-1 mr-0 pr-0 mr-1" data-toggle="dropdown" href="#">
                        <i class="far fa-user"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right"
                        style="z-index: 3 !important;position: absolute;">
                        <a href="#" class="dropdown-item">
                            <div class="media">
                                <img src="@if(is_null($user->image_photo)){{ asset('images/avatar_2.png') }} @else {{$user->image_photo}} @endif" alt="User Avatar"
                                    class="img-size-50 img-circle mr-3" style="height: 50px; border: 1px solid #ccc;">
                                <div class="media-body">
                                    <h3 class="dropdown-item-title">
                                        <p style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 170px" data-toggle="tooltip" data-placement="top" title="{{ Auth::user()->email }}">{{ Auth::user()->email }}</p>
                                        <span class="float-right text-sm text-warning"></span>
                                    </h3>
                                    <p class="text-sm nickname" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 170px" data-toggle="tooltip" data-placement="top" title="{{ Auth::user()->nickname }}">
                                        {{ Auth::user()->nickname }}
                                    </p>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        @guest
                        @else
                            @if(Auth::user()->role == config('constants.role.admin'))
                                <form id="logout-form" action="{{ route('admin-logout') }}" method="POST"
                                    class="d-none">@csrf</form>
                            @elseif(Auth::user()->role == config('constants.role.teacher'))
                                <form id="logout-form" action="{{ route('teacher-logout') }}" method="POST"
                                    class="d-none">@csrf</form>
                            @elseif(Auth::user()->role == config('constants.role.student'))
                                <form id="logout-form" action="{{ route('student-logout') }}" method="POST"
                                    class="d-none">@csrf</form>
                            @endif

                            @if(Auth::user()->role == config('constants.role.teacher') || Auth::user()->role == config('constants.role.admin'))
                                <a href="{{ route('profile') }}"
                                class="dropdown-item dropdown-footer border-bottom">マイページ</a>
                                <a href="{{ route('student-logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item dropdown-footer">ログアウト</a>
                            @else
                                <a href="{{ route('student.profile') }}"
                                class="dropdown-item dropdown-footer border-bottom">{{ __('header.my_page') }}</a>
                                <a href="{{ route('student-logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                class="dropdown-item dropdown-footer">{{ __('header.logout') }}</a>
                            @endif
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>

</nav>
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white p-0" style="z-index: 2 !important;">
    <div class="container">
        <div class="row w-100 d-flex justify-content-around h-100">
            <div class="col-12 d-flex d-sm-flex col-sm-8 justify-content-sm-center">
                <div class="collapse navbar-collapse order-3" id="navbarCollapse">
                    <!-- Left navbar links -->
                    <ul class="w-100 d-flex justify-content-center pb-4 navbar-nav h-100 d-sm-flex pb-sm-0" style="flex-direction: row !important;">
                        <div class="h-100  d-flex justify-content-center  pb-sm-4 pt-sm-2 w-25">
                            <li class="nav-item d-flex h-100">
                                <a href="{{ route('student-dashboard') }}" class="nav-link text-center font-weight-bold
                                @if (Request::path() == 'student')
                                    text-primary
                                @endif
                                "><i class="fas fa-home"></i><br> {{ __('header.student.home') }}</a>
                            </li>
                        </div>

                        <!-- Lessons -->
                        <div class="h-100  pb-sm-4 pt-sm-2 w-25">
                            <li class="nav-item d-flex justify-content-center dropdown h-100">
                                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false" class="nav-link  text-center font-weight-bold"
                                   style="content:none !important"><i class="fas fa-graduation-cap "></i><br>{{ __('header.student.lesson') }}</a>
                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow" style="z-index: 3; position: absolute">
                                    <li><a href="{{ route('student.lesson.list') }}" class="dropdown-item">{{ __('header.student.booking_list') }}</a></li>
                                    <li><a href="{{ route('student.lesson.history') }}" class="dropdown-item">{{ __('header.student.lesson_history') }}</a></li>
                                    <li><a href="{{ route('student.payments.history') }}" class="dropdown-item">{{ __('header.student.payment_history') }}</a></li>
                                    <li><a href="{{ route('student.add-coin') }}" class="dropdown-item">{{ __('header.student.add_coin') }}</a></li>
                                    <li><a href="{{ route('student.courses') }}" class="dropdown-item">{{ __('header.student.course') }}</a></li>
                                </ul>
                            </li>
                        </div>
                        <!-- ./Lessons -->

                        <!-- border-right -->
                        <div class="h-100 d-flex justify-content-center pb-sm-4 pt-sm-2 w-25">
                            <li class="nav-item d-flex h-100">
                                <a href="#" class="nav-link text-center font-weight-bold"><i class="fas fa-address-book"></i><br>{{ __('header.student.guide') }}</a>
                            </li>
                        </div>
                        <div class="h-100  pb-sm-4 pt-sm-2 w-25 d-none d-sm-block">
                        <!-- d-flex justify-content-center -->
                            <li class="nav-item d-flex dropdown h-100">
                                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true"
                                   aria-expanded="false" class="nav-link  text-center font-weight-bold"
                                   style="content:none !important"><i class="fas fa-ellipsis-h"></i><br>{{ __('header.student.other') }}</a>
{{--                                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">--}}
{{--                                    <li><a href="#" class="dropdown-item">Some action </a></li>--}}
{{--                                    <li><a href="#" class="dropdown-item">Some other action</a></li>--}}

{{--                                    <li class="dropdown-divider"></li>--}}

{{--                                    <!-- Level two dropdown-->--}}
{{--                                    <li class="dropdown-submenu dropdown-hover">--}}
{{--                                        <a id="dropdownSubMenu2" href="#" role="button" data-toggle="dropdown"--}}
{{--                                           aria-haspopup="true" aria-expanded="false"--}}
{{--                                           class="dropdown-item dropdown-toggle ">Hover for action</a>--}}
{{--                                        <ul aria-labelledby="dropdownSubMenu2" class="dropdown-menu border-0 shadow">--}}
{{--                                            <li>--}}
{{--                                                <a tabindex="-1" href="#" class="dropdown-item">level 2</a>--}}
{{--                                            </li>--}}

{{--                                            <!-- Level three dropdown-->--}}
{{--                                            <li class="dropdown-submenu">--}}
{{--                                                <a id="dropdownSubMenu3" href="#" role="button" data-toggle="dropdown"--}}
{{--                                                   aria-haspopup="true" aria-expanded="false"--}}
{{--                                                   class="dropdown-item dropdown-toggle">level 2</a>--}}
{{--                                                <ul aria-labelledby="dropdownSubMenu3"--}}
{{--                                                    class="dropdown-menu border-0 shadow">--}}
{{--                                                    <li><a href="#" class="dropdown-item">3rd level</a></li>--}}
{{--                                                    <li><a href="#" class="dropdown-item">3rd level</a></li>--}}
{{--                                                </ul>--}}
{{--                                            </li>--}}
{{--                                            <!-- End Level three -->--}}

{{--                                            <li><a href="#" class="dropdown-item">level 2</a></li>--}}
{{--                                            <li><a href="#" class="dropdown-item">level 2</a></li>--}}
{{--                                        </ul>--}}
{{--                                    </li>--}}
{{--                                </ul>--}}
                            </li>
                        </div>
                        <!-- End Level two -->
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<div class="modal fade" id="modal_teacher_invite_join_lesson" tabindex="-1" aria-labelledby="exampleModalLabel"
           aria-hidden="true" style="top:100px">
    <div class="modal-dialog" style="max-width: 400px!important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 col-sm-12">
                    <div class="d-flex flex-column align-items-center">
                        <h3>Notification </h3>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-1">{{__('student_panel.teacher_invite_lesson')}}</p>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <div>
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_teacher_cancel_lesson" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true" style="top:100px">
    <div class="modal-dialog" style="max-width: 400px!important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 col-sm-12">
                    <div class="d-flex flex-column align-items-center">
                        <h3>Notification </h3>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-1">{{__('student_panel.teacher_cancel_lesson')}}</p>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <div>
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">OK
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

