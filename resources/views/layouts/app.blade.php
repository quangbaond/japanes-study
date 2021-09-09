<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="bootbox-confirm-true" content="{{ __('button.bootbox_confirm_true') }}">
        <meta name="bootbox-confirm-false" content="{{ __('button.bootbox_confirm_false') }}">
        <title>Japanese Study</title>

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/fontawesome-free/css/all.min.css') }}">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
        <!-- iCheck -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/adminlte.min.css') }}">
        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/daterangepicker/daterangepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/bootstrap-datepicker3.css') }}"/>
        <!-- app.css -->
        <link rel="stylesheet" href="{{ asset('css/admin/app.css') }}">
        @yield('stylesheets')
    </head>
    <body class="hold-transition register-page container" id="color-body">
        <div id="loading" style="display:none">
            <img src="{{asset('images/loading.gif')}}" alt="Loading..."/>
        </div>
        @yield('content')

        <!-- jQuery -->
        <script src="{{ asset('template/admin/plugins/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('template/admin/plugins/jquery-validation/jquery.validate.js') }}"></script>
        <!-- jQuery UI 1.11.4 -->
        <script src="{{ asset('template/admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
        <!-- Bootstrap 4 -->
        <script src="{{ asset('template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
        <!-- daterangepicker -->
        <script src="{{ asset('template/admin/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('template/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
        <script type="text/javascript" src="{{ asset('template/admin/dist/js/bootstrap-datepicker.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('template/admin/plugins/select2/js/select2.full.min.js') }}"></script>
        <script src="{{ asset('template/admin/bootbox.js') }}"></script>
        <script src="{{ asset('js/admin/app.js') }}"></script>
        @stack('scripts')
    </body>
</html>

