@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/manager/login.css') }}">
@endsection
@section('content')
    <body class="hold-transition login-page container" id="color-body">
        <div class="login-box">
            <div class="card card-outline card-primary">
                <div class="card-header text-center">
                    <h1>ログイン</h1>
                </div>
                <!-- message -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ config('validation.input_error') }}
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        {{ $message }}
                    </div>
                @endif
                <div class="card-body">
                    <form method="POST" action="{{ route('admin-login') }}">
                        @csrf
                        <div class="input-group mb-3">
                            <input id="email" type="text" placeholder="メールアドレス" class="form-control @error('email') is-invalid @enderror" name="email" value="@if(Request::cookie('email')){{Cookie::get('email')}}@else{{ old('email') }}@endif" autofocus>
                            <div class="input-group-append">
                                <div class="input-group-text m-l-2">
                                    <span class="fas fa-envelope"></span>
                                </div>
                            </div>
                            @error('email')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="input-group mb-3 ">
                            <input id="password" type="password" value="@if(Request::cookie('password')){{Cookie::get('password')}}@else{{ old('password') }}@endif" placeholder="パスワード" class="form-control @error('password') is-invalid @enderror" name="password" autocomplete="current-password">
                            <div class="input-group-append">
                                <div class="input-group-text">
                                    <span class="fas fa-lock"></span>
                                </div>
                            </div>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-7">
                                <div class="icheck-primary">
                                    <input class="form-check-input" type="checkbox" name="remember_me" id="remember" @if(Request::cookie('remember_me')){{Cookie::get('remember_me')}}@else{{ old('remember_me') ? 'checked' : '' }}@endif>

                                    <label class="form-check-label" for="remember">
                                        ログインを記録する
                                    </label>
                                </div>
                            </div>
                            <div class="col-5">
                                <button type="submit" class="btn btn-primary btn-block btn-flat">ログイン</button>
                            </div>
                        </div>
                    </form>
                    <p class="mb-1">
                        @if (Route::has('password.request'))
                            <a class="text-center" href="{{ route('password.request') }}">
                                パスワードを忘れた方
                            </a>
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </body>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/admin/login.js')  }}"></script
@endpush
