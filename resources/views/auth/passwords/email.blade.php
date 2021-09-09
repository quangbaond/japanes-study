@extends('layouts.app')

@section('content')
<body class="hold-transition register-page">
    <div class="login-box">
        <div class="card card-outline card-primary">
            <div class="card-header text-center">
                <a href="{{ route('password.email')}}" class="h1">{{ __('login.reset_password')}}</a>
            </div>
            <div class="card-body">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
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
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-block">{{ __('login.send_link') }}</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
                    <div class="row">
                        <div class="col-8">
                            <p class="mt-3 mb-1">
                                <a href="{{ route('login')}}">{{ __('login.login') }}</a>
                            </p>
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
            <!-- /.login-card-body -->
        </div>
    </div>
</body>
@endsection
