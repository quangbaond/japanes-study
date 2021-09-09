<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="route-teacher-mypage" content="{{ route('teacher.my-page') }}">
    <meta name="M040_title" content="{{ __('validation_custom.M040.title') }}">
    <meta name="M040_content" content="{{ __('validation_custom.M040.content') }}">
    <meta name="wait-student-message" content="{{ __('teacher.wait_student') }}">
@if(Auth::user()->role == config('constants.role.teacher'))
        <meta name="routeNotification" content="{{route('teacher-notification-detail' , ':id')}}">
    @endif
    @if(Auth::user()->role == config('constants.role.admin'))
        <meta name="routeNotification" content="{{route('admin.notification.detail' , ':id')}}">
    @endif
    <title>@yield('title', 'Japanese Study')</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <!-- iCheck -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <!-- JQVMap -->
{{--    <link rel="stylesheet" href="{{ asset('template/admin/plugins/jqvmap/jqvmap.min.css') }}">--}}
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('template/admin/dist/css/adminlte.min.css') }}">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/daterangepicker/daterangepicker.css') }}">
    <!-- summernote -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/summernote/summernote-bs4.min.css') }}">
    <!-- app.css -->

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('toastr/build/toastr.css') }}"/>
    <link rel="stylesheet" href="{{ asset('template/admin/dist/css/bootstrap-datepicker3.css') }}"/>
    <meta name="route-start-meeting-room" content="{{ route('teacher.start-meeting-room') }}">
    <meta name="route-cancel-meeting-room" content="{{ route('teacher.send-cancel-to-student') }}">
    @yield('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/app.css') }}">
</head>
<body class="hold-transition sidebar-mini layout-fixed" style="font-size: 90%">
    <div id="loading" class="d-none">
        <img src="{{asset('images/loading.gif')}}" style="left: calc(50% - 35px) !important;" alt="Loading..."/>
        <h4 style="top:calc(55%); position: absolute;left: calc(15%);right: calc(15%);width: 70%;text-align: center" id="loading_message"></h4>
    </div>
    <input type="hidden" value="{{Auth::user()->id}}" id="user_login">
    <div class="wrapper">
        <!-- Main Headers Container -->
        @include('includes.admin.headers')

        <!-- Main Sidebar Container -->
        @include('includes.admin.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">@yield('title_screen', 'Japanese Study')</h1>
                        </div>
                        <div class="col-sm-6">
                            @yield('breadcrumb')
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div id="area_message">
                    @include('includes.admin.message')
                </div>
                @include('includes.admin.notifications')
                @include('includes.admin.panel')
                @yield('content')
                @include('includes.admin.notification_for_teacher')
            </section>
        </div>
        <!-- /.content-wrapper -->

        @include('includes.admin.footers')

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
        <!-- /.control-sidebar -->
    </div>

    <!-- jQuery -->
    <script src="{{ asset('template/admin/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/admin/plugins/jquery-validation/jquery.validate.js') }}"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="{{ asset('template/admin/plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
        $.widget.bridge('uibutton', $.ui.button)
    </script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('template/admin/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('template/admin/plugins/select2/js/select2.full.min.js') }}"></script>
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('template/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

    <!-- ChartJS -->
    <script src="{{ asset('template/admin/plugins/chart.js/Chart.min.js') }}"></script>
    <!-- Sparkline -->
    <script src="{{ asset('template/admin/plugins/sparklines/sparkline.js') }}"></script>
    <!-- JQVMap -->
    {{--<script src="{{ asset('template/admin/plugins/jqvmap/jquery.vmap.min.js') }}"></script>--}}
    {{--<script src="{{ asset('template/admin/plugins/jqvmap/maps/jquery.vmap.usa.js') }}"></script>--}}
    <!-- jQuery Knob Chart -->
    <script src="{{ asset('template/admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>
    <!-- daterangepicker -->
    <script src="{{ asset('template/admin/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('template/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <!-- Tempusdominus Bootstrap 4 -->
    <script src="{{ asset('template/admin/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
    <!-- Summernote -->
    <script src="{{ asset('template/admin/plugins/summernote/summernote-bs4.min.js') }}"></script>
    <!-- overlayScrollbars -->
    <script src="{{ asset('template/admin/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('template/admin/dist/js/adminlte.js') }}"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="{{ asset('template/admin/dist/js/demo.js') }}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ asset('template/admin/dist/js/pages/dashboard.js') }}"></script>
    <script src="{{ asset('template/admin/bootbox.js') }}"></script>
    <script src="{{ asset('toastr/toastr.js') }}"></script>
    <script src="{{ asset('js/admin/app.js') }}"></script>
    <script src="{{ asset('js/admin/home.js') }}"></script>
    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>

    <script type="text/javascript">
        let pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            encrypted: true,
            cluster: "ap1"
        });
        var chanel = pusher.subscribe('notification-user-{{Auth::id()}}');
        chanel.bind('my-event' , function(data){
            console.log(data)
        })
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script src="{{ asset('js/admin/notification/index.js') }}"></script>
    <script type="text/javascript" src="{{ asset('template/admin/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
    @stack('scripts')
</body>
</html>
