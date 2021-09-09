<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        @if(Auth::user()->role == config('constants.role.student') && !Request::path() == 'students/payment/7-days-free-trial')
            <li class="nav-item">
                <a class="nav-link" href="{{route("student.payment.7-days-free-trial")}}">
                    <i style="font-size: 1.6em" class="fas fa-check-circle"></i>
                </a>
            </li>
        @endif

        @if(Auth::user()->role == config('constants.role.student')&& !Request::path() == 'students/payment/premium')
            <li class="nav-item">
                <a class="nav-link" href="{{route("student.payment.premium")}}">
                    <i style="font-size: 1.6em" class="fas fa-heart"></i>
                </a>
            </li>
        @endif
    <!-- Language multi Dropdown Menu -->
        @if(Auth::user()->role == config('constants.role.student'))
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i style="font-size: 1.6em" class="fas fa-language"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <a href="{{ url('change-language/en') }}"
                       class="dropdown-item dropdown-footer">{{ __('header.english') }}</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('change-language/vi') }}"
                       class="dropdown-item dropdown-footer">{{ __('header.viet_nam') }}</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ url('change-language/ja') }}"
                       class="dropdown-item dropdown-footer">{{ __('header.janpan') }}</a>
                </div>
            </li>
    @endif
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
{{--            <li class="nav-item">--}}
{{--                <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">--}}
{{--                    <i class="fas fa-th-large"></i>--}}
{{--                </a>--}}
{{--            </li>--}}
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge" id="count_unread_notification">
                    @if(Auth::user()->role == config('constants.role.teacher'))
                        {{$getNotificationTeacher['num_read_at']}}
                    @endif
                    @if(Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin'))
                            {{$getNotificationAdmin['num_read_at']}}
                        @endif
                    </span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="min-width : 348px; max-width : 400px;">
                    <span class="dropdown-item font-weight-bold dropdown-header" style="text-align: left; color: black">
                        お知らせ
                    </span>
                    <div id="element_notification">
                        <div class="dropdown-divider"></div>
                        @if(Auth::user()->role == config('constants.role.teacher'))
                            @foreach($getNotificationTeacher['notifications'] as $item)
                                <a href="{{route('teacher-notification-detail' , $item->id)}}" class="dropdown-item choice_notification" data-id="" >
                                    @if($item->read_at == NULL)
                                    <i class="fas fa-envelope mr-2"></i><b class="font-weight-bold ">{{$item->title}}</b>
                                    @else
                                    <i class="fas fa-envelope mr-2"></i>{{$item->title}}
                                    @endif
                                    <span class="float-right text-muted text-sm">{{$item->created_at}}</span>
                                </a>
                            @endforeach
                        @endif
                        @if(Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin'))
                            @foreach($getNotificationAdmin['notifications'] as $item)
                                <a href="{{route('admin.notification.detail' , $item->id)}}" class="dropdown-item choice_notification" data-id="" >
                                    @if($item->read_at == NULL)
                                        <i class="fas fa-envelope mr-2"></i><b class="font-weight-bold ">{{$item->title}}</b>
                                    @else
                                        <i class="fas fa-envelope mr-2"></i>{{$item->title}}
                                    @endif
                                    <span class="float-right text-muted text-sm">{{$item->created_at}}</span>
                                </a>
                            @endforeach
                        @endif
                    </div>
                    <div class="dropdown-divider"></div>
                    <a href="@if(Auth::user()->role == 1) {{ route('admin-notification')}} @elseif (Auth::user()->role == 2){{ route('teacher-notification')}} @endif" class="dropdown-item dropdown-footer" style="text-align: left">一覧へ</a>
                </div>
            </li>

    <!-- Account Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <a href="#" class="dropdown-item">
                    <div class="media">
                        <img src="@if(is_null($user->image_photo)){{ asset('images/avatar_2.png') }} @else {{$user->image_photo}}@endif" alt="User Avatar"
                             class="img-size-50 img-circle mr-3" style="height: 50px; border: 1px solid #ccc;">
                        <div class="media-body">
                            <h3 class="dropdown-item-title">
                                <p>{{ Auth::user()->email }}</p>
                                <span class="float-right text-sm text-warning"></span>
                            </h3>
                            <p class="text-sm">
                                {{ Auth::user()->nickname }}
                            </p>
                        </div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                @guest
                @else
                    @if(Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin'))
                        <form id="logout-form" action="{{ route('admin-logout') }}" method="POST"
                              class="d-none">@csrf</form>
                    @elseif(Auth::user()->role == config('constants.role.teacher'))
                        <form id="logout-form" action="{{ route('teacher-logout') }}" method="POST"
                              class="d-none">@csrf</form>
                    @elseif(Auth::user()->role == config('constants.role.student'))
                        <form id="logout-form" action="{{ route('student-logout') }}" method="POST"
                              class="d-none">@csrf</form>
                    @endif

                    @if(Auth::user()->role == config('constants.role.teacher'))
                        <a href="{{ route('teacher.my-page') }}" class="dropdown-item dropdown-footer border-bottom">マイページ</a>
                        <a href="{{ route('admin-logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="dropdown-item dropdown-footer">ログアウト</a>
                    @elseif( Auth::user()->role == config('constants.role.admin') || Auth::user()->role == config('constants.role.child-admin'))
                            <a href="{{ route('admin.admin-list.detail', ['user_id' => Auth::user()->id ]) }}" class="dropdown-item dropdown-footer border-bottom">プロフィール</a>
                            <a href="{{ route('admin-logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                               class="dropdown-item dropdown-footer">ログアウト</a>
                    @else
                        <a href="{{ route('profile') }}"
                           class="dropdown-item dropdown-footer border-bottom">{{ __('header.profile') }}</a>
                        <a href="{{ route('admin-logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="dropdown-item dropdown-footer">{{ __('header.logout') }}</a>
                    @endif
                @endguest
            </div>
        </li>
    </ul>
<!-- panel notification all -->

</nav>

<div class="modal fade" id="modal_notification">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

        </div>
    </div>
</div>
