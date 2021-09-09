@extends('layouts.app')

@section('content')
<body class="hold-transition register-page">
    <div class="register-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ route('register') }}" class="h1">{{ __('login.register')}}</a>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input id="name" type="text" placeholder="{{ __('login.name') }}" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="input-group mb-3">
                        <input id="email" type="email" placeholder="{{ __('login.email') }}" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
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
                        <input id="password" type="password" placeholder="{{ __('login.password') }}" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

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

                    <div class="input-group mb-3">
                        <input id="password-confirm" type="password" placeholder="{{ __('login.password_confirm') }}" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-lock"></span>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-8">
                            <div class="icheck-primary">
                                <input type="checkbox" id="agreeTerms" name="terms" value="agree">
                                <label for="agreeTerms">
                                    {{ __('login.i_agree_to_the') }} <a href="#">{{ __('login.terms') }}</a>
                                </label>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('login.register')}}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>

                <div class="social-auth-links text-center">
                    <a href="#" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i>
                        {{ __('login.sign_in_facebook') }}
                    </a>
                    <a href="#" class="btn btn-block btn-danger">
                        <i class="fab fa-google-plus mr-2"></i>
                        {{ __('login.sign_in_google') }}
                    </a>
                </div>

                <div class="row">
                    <div class="col-8">
                        <a href="{{ route('login') }}" class="text-center">{{ __('login.membership') }}</a>
                    </div>
                    <div class="col-4">
                        <div class="nav-item dropdown">
                            <a class="nav-link float-right" data-toggle="dropdown" href="#">
                                </i><i style="font-size: 2em" class="fas fa-language"></i>
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
            <!-- /.form-box -->
        </div><!-- /.card -->
    </div>
</body>
@endsection
