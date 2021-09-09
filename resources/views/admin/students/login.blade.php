@extends('layouts.app')
<style>
    #color-body {
        height: 100%;
        background-color: #FDD692; /* Dành cho các trình duyệt không hỗ trợ gradient*/
        background-image: linear-gradient(#FDD692, #D4DFE6);
        font-size: 90%;
    }
    .w-95 {
        width: 95%;
    }
</style>
@section('content')
    <body class="hold-transition login-page container" id="color-body">
    <div class="col-sm-8 mt-5 pt-5">
        <div class="card card-outline card-warning">
            <!-- header -->
            <div class="card-header text-left">
                <h1>{{ __('login.login')}}</h1>
            </div>
            <!-- message -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ config('validation.input_error_student') }}
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ $message }}
                </div>
        @endif
            <!-- center -->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 col-sm-12 mt-3 border-right ">
                        <form method="POST" action="{{ route('student-login') }}" class="w-95">
                            @csrf
                            <div class="input-group mb-4  @error('email') is-invalid @enderror">
                                <input id="email" type="text" placeholder="email" class="form-control @error('email') is-invalid @enderror" name="email" value="@if(Request::cookie('email_student')){{Cookie::get('email_student')}}@else{{ old('email') }}@endif" autofocus>
                                <div class="input-group-append">
                                    <div class="input-group-text m-l-2">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="input-group mb-4 @error('password') is-invalid @enderror">
                                <input id="password" type="password" value="@if(Request::cookie('password_student')){{Cookie::get('password_student')}}@else{{ old('password') }}@endif" placeholder="password" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-7">
                                    <div class="icheck-primary">
                                        <input class="form-check-input" type="checkbox" name="remember_me" id="remember" @if(Request::cookie('remember_me_student')){{Cookie::get('remember_me_student')}}@else{{ old('remember_me') ? 'checked' : '' }}@endif>

                                        <label class="form-check-label" for="remember">
                                            {{ __('login.remember_me') }}
                                        </label>
                                    </div>
                                </div>
                                <!-- /.col -->
                                <div class="col-5">
                                    <button type="submit" class="btn btn-primary btn-block btn-flat">{{ __('login.login') }}</button>
                                </div>
                                <!-- /.col -->
                            </div>
                        </form>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="w-95 ml-3">
                            <strong>{{ __('login.login_with') }}</strong>
                            <div class="social-auth-links text-center">
                                <a href="{{ route('login.student.facebook') }}" class="btn btn-block btn-primary btn-flat">
                                    <i class="fab fa-facebook float-left py-1"></i>{{ __('login.sign_in_facebook') }}
                                </a>
                                <a href="{{ route('login.student.google') }}" class="btn btn-block btn-danger mt-3  btn-flat">
                                    <i class="fab fa-google-plus float-left py-1"></i>{{ __('login.sign_in_google') }}
                                </a>
                                
                                <a href="{{ route('login.student.zalo') }}" class="btn btn-block btn-default mt-3 btn-flat">
                                    <img class="float-left img-fluid  d-block py-1" src="/images/zalo_icon.jpg">{{ __('login.sign_in_zalo') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- footer -->
                <div class="row">
                    <div class="col-8">
                        <p class="mb-1">
                            @if (Route::has('password.request'))
                                <a class="text-center" href="{{ route('password.request') }}">
                                    {{ __('login.forgot_password') }}
                                </a>
                            @endif
                        </p>
                        <p class="mb-0">
                            <a href="{{ route('student.register') }}" class="text-center">{{ __('login.register')}}</a>
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="nav-item dropdown">
                            <a class="nav-link text-right" data-toggle="dropdown" href="#">
                                <span>{{ __('student.choice_language') }}</span>
                                <img src="{{ asset('images/multi-language.png') }}" alt="" style="width: 30px;height: 30px">
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <a href="{{ url('change-language/en') }}"  class="dropdown-item dropdown-footer">{{ __('header.english') }}</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ url('change-language/vi') }}"  class="dropdown-item dropdown-footer">{{ __('header.viet_nam') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
