<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="routeUpdateCourseLesson" content="{{ route('student.update-course') }}">
        <meta name="route-student-booking-list" content="{{ route('student.lesson.list') }}">
        <meta name="routeNotifyStartLesson" content="{{ route('student.notify-start-lesson') }}">
        <meta name="routeNotifyAfter5Minutes" content="{{ route('student.notify-after-5-minutes') }}">
        <meta name="M040_title" content="{{ __('validation_custom.M040.title') }}">
        <meta name="M040_content" content="{{ __('validation_custom.M040.content') }}">
        <meta name="routeNotification" content="{{route('student-notification-detail' , ':id')}}">
        <meta name="msg-teacher-invite-join-lesson" content="{{ __('student.teacher_invite_join_lesson') }}">
        <meta name="msg-teacher-cancel-lesson" content="{{ __('student.teacher_cancel_lesson') }}">
        <meta name="route-timeout" content="{{ route("student.push-request-cancel") }}">
        <title>@yield('title', 'Japanese Study')</title>
        <meta name="route-review-student" content="{{route('student.checkHistories')}}">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Google Font: Source Sans Pro -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

        <!-- Font Awesome Icons -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/fontawesome-free/css/all.min.css') }}">

        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/adminlte.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/fontawesome-free/css/all.min.css')}}">

        <!-- Daterange picker -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/daterangepicker/daterangepicker.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/bootstrap-datepicker3.css') }}"/>

        <!-- iCheck for checkboxes and radio inputs -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
        <link rel="stylesheet" href="{{ asset('template\admin\plugins\bootstrap-colorpicker\css\bootstrap-colorpicker.min.css') }}">

        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

        <!-- Bootstrap4 Duallistbox -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css') }}">

        <!-- BS Stepper -->
        {{--    <link rel="stylesheet" href="{{ asset('template/admin/plugins/bs-stepper/css/bs-stepper.min.css') }}">--}}

        <!-- dropzonejs -->
        {{--    <link rel="stylesheet" href="{{ asset('template/admin/plugins/dropzone/min/dropzone.min.css') }}">--}}

        <!-- Theme style -->
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/adminlte.min.css') }}">

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

        <!-- app.css -->
        <link rel="stylesheet" href="{{ asset('css/admin/app.css') }}">
        <link rel="stylesheet" href="{{ asset('template/admin/dist/css/bootstrap-rating.css') }}">
        @yield('stylesheets')
    </head>
    <body class="hold-transition layout-top-nav">
        <div id="loading" style="display: none">
            <img src="{{asset('images/loading.gif')}}" alt="Loading..."/>
        </div>
        <div id="loading_wait_teacher" style="display: none">
            <img src="{{asset('images/loading.gif')}}" alt="Loading..."/>
            <h4 style="top:calc(55%); position: absolute;left: calc(35%);right: calc(35%);width: 30%;text-align: center">{{ __('sudden_lesson.wait_teacher') }}..</h4>
        </div>
        <input type="hidden" value="{{Auth::user()->id}}" id="user_login">
        <div class="wrapper" >
            <style>
                .custom-bg {
                    background: rgba(192, 225, 229, 0.3) !important;
                }
                .content-wrapper {
                    min-height: 760px;
                }
            </style>
            @include('includes.student.headers')

            <!-- Content Wrapper. Contains page content -->
            <div class="content-wrapper">
                <div class="content-header">
                    <!-- Main content -->

                    <!-- /.content -->
                </div>
                <!-- Main content -->
                <section class="content">
                    <div class="content w-100 px-0 d-flex flex-column align-items-center">
                        <div class="container" style="overflow: hidden !important;">
                            <div class="row">
                                <div class="col-12 px-0">
                                    <div id="area_message" class="">
                                        @include('includes.admin.message')
                                    </div>
                                </div>
                            </div>
                        </div>
                        @include('includes.student.notifications')
                        <br>

                        @include('includes.student.panel_expired_premium')
                        @yield('panel')
                        @include('includes.student.review_lesson')
                        @yield('content')
                    </div>
                </section>
                <!-- /.content -->
                <!-- /.content-wrapper -->

                <!-- Control Sidebar -->
                <aside class="control-sidebar control-sidebar-dark">
                    <div class="p-3">
                        <h5>Title</h5>
                        <p>Sidebar content</p>
                    </div>
                </aside>
                <!-- /.control-sidebar -->

                @include('includes.student.notification_for_student')
                <!-- Main Footer -->
                <!-- ./Main Footer -->
            </div>
        @include('includes.student.footers')


        <!-- ./wrapper -->

            <!-- REQUIRED SCRIPTS -->
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

            <!-- AdminLTE App -->
            <script src="{{ asset('template/admin/dist/js/adminlte.min.js') }}"></script>

            <!-- AdminLTE for demo purposes -->
            <script src="{{ asset('template/admin/dist/js/demo.js') }}"></script>

            <!-- Select2 -->
            <script src="{{ asset('template/admin/plugins/select2/js/select2.full.min.js') }}"></script>

            <!-- DataTables  & Plugins -->
            <script src="{{ asset('template/admin/plugins/datatables/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('template/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

            <!-- ChartJS -->
            {{--        <script src="{{ asset('template/admin/plugins/chart.js/Chart.min.js') }}"></script>--}}
            <!-- Sparkline -->
            {{--        <script src="{{ asset('template/admin/plugins/sparklines/sparkline.js') }}"></script>--}}
            <!-- jQuery Knob Chart -->
            {{--        <script src="{{ asset('template/admin/plugins/jquery-knob/jquery.knob.min.js') }}"></script>--}}

            <!-- daterangepicker -->
            <script src="{{ asset('template/admin/plugins/moment/moment.min.js') }}"></script>
            <script src="{{ asset('template/admin/plugins/daterangepicker/daterangepicker.js') }}"></script>
            <script type="text/javascript" src="{{ asset('template/admin/dist/js/bootstrap-datepicker.min.js') }}"></script>

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
            {{--    <script src="{{ asset('template/admin/dist/js/pages/dashboard.js') }}"></script>--}}

            <script src="{{ asset('template/admin/bootbox.js') }}"></script>
            <script src="{{ asset('js/student/app.js') }}"></script>
            <script src="{{ asset('js/admin/app.js') }}"></script>
            <script src="{{ asset('js/admin/students/panel.js') }}"></script>
            <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
            <script src="{{ asset('template/admin/dist/js/bootstrap-rating.min.js') }}"></script>

            <script src="{{ asset('js/admin/students/review_student.js') }}"></script>

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
            <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js"></script>
            @stack('scripts')
        </div>
    </body>
</html>
