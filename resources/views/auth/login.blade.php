@extends('layouts.app')

@section('content')
<body class="hold-transition login-page container">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ route('login') }}" class="h1">{{ __('login.login')}}</a>
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input id="email" type="email" placeholder="{{ __('login.email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input id="password" type="password" placeholder="{{ __('login.password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-7">
                            <div class="icheck-primary">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('login.remember_me') }}
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-5">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('login.login') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center mt-2 mb-3">
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i>{{ __('login.sign_in_facebook') }}
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i>{{ __('login.sign_in_google') }}
                    </a>
                </div>
                <!-- /.social-auth-links -->

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
                            <a href="{{ route('register') }}" class="text-center">{{ __('login.register')}}</a>
                        </p>
                    </div>
                    <div class="col-4">
                        <div class="nav-item dropdown">
                            <a class="nav-link" data-toggle="dropdown" href="#">
                                <i style="font-size: 2em" class="fas fa-language float-right"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                                <a href="{{ url('change-language/en') }}"  class="dropdown-item dropdown-footer">{{ __('header.english') }}</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ url('change-language/vi') }}"  class="dropdown-item dropdown-footer">{{ __('header.viet_nam') }}</a>
                                <div class="dropdown-divider"></div>
                                <a href="{{ url('change-language/ja') }}"  class="dropdown-item dropdown-footer">{{ __('header.janpan') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>
@endsection
