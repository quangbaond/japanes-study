@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('list_history') }}
@endsection

@section('stylesheets')
    <meta name="delete-confirm" content="{{ config('constants.delete_confirm') }}">
    <meta name="delete-success" content="{{ config('constants.delete_success') }}">
    <meta name="route-get-nickname-by-id" content="{{ route('admin.lesson-history.getById') }}">
    <meta name="route-get-nickname-by-email" content="{{ route('admin.lesson-history.getByEmail') }}">
    <meta name="route-validation-search" content="{{ route('admin.validation-search.lesson-history') }}">
    <meta name="route-export-to-excel" content="{{ route('admin.lesson-history.export-to-excel') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/manager/lessons/lesson_history.css') }}"/>
@endsection

@section('title_screen', 'レッスン履歴')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.teacher.validation')}}" method="post" id="formSearchLessonHistories">
                        @csrf
                        <div class="card form-search-clear" id="searchLessonHistories">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <!-- teacher_id -->
                                    <div class="col-sm-9">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">ID</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <select class="form-control select2 itemTeacherId"
                                                            multiple="multiple" name="teacher_id[]" id="teacher_id"
                                                            style="width: 100%"></select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- nationality -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">メールアドレス</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <select class="form-control select2 itemTeacherEmail"
                                                            multiple="multiple" name="teacher_email[]"
                                                            id="teacher_email" style="width: 100%;"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- created_at -->
                                    <div class="col-sm-9">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">日付 </label>
                                            <div class="col-sm-9 row px-0">
                                                <div class="input-group col-sm-5 pl-3">
                                                    <input type="text"
                                                           class="form-control datepicker format_date_from date_from"
                                                           id="date_from"
                                                           name="date_from"
                                                           value="{{$first_day_of_month}}"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text" style="max-height: 38px!important;">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <span class="invalid-feedback-custom" id="format_date_from"></span>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5 pl-3">
                                                    <input type="text"
                                                           class="form-control datepicker format_date_to date_to"
                                                           id="date_to"
                                                           name="date_to"
                                                           value="{{$today}}"/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text" style="max-height: 38px!important;">
                                                            <i class="fa fa-calendar"></i>
                                                        </div>
                                                    </div>
                                                    <span class="invalid-feedback-custom" id="format_date_to"></span>
                                                </div>
                                                <span class="invalid-feedback-custom ml-3" id="err_date"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary float-right btn-flat ml-2" id="btnSearch">
                                    検索
                                </button>
                                <button type="button" class="btn btn-default float-right btn-flat" id="btnClearForm">
                                    クリア
                                </button>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-header align-items-center">
                            <h5 class="card-title my-2">講師の統計表</h5>
                            <form action="{{route('admin.lesson-history.export-to-excel')}}" method="get"
                                  id="formExportToExcel">
                                <button type="button" class="btn btn-primary float-right btn-flat" id="exportExcel">
                                    エクセルに出力
                                </button>
                            </form>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="statistics" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>講師ID</th>
                                    <th>講師ニックネーム</th>
                                    <th>講師メールアドレス</th>
                                    <th>レッスン数</th>
                                    <th>コイン数</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header align-items-center">
                            <h5 class="card-title my-2 ">レッスン履歴</h5>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="lessonHistories" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>時間</th>
                                    <th>講師ID</th>
                                    <th>講師メールアドレス</th>
                                    <th>生徒ID</th>
                                    <th>生徒メールアドレス</th>
                                    <th>コース</th>
                                    <th>レッスン内容</th>
                                    <th>コイン</th>
                                    <th style="max-width: 50px;">ステータス</th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        $('#statistics').DataTable({
            drawCallback: function () {
                let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                pagination.toggle(this.api().page.info().pages > 0);
                info.toggle(this.api().page.info().pages > 0);
            },
            'lengthChange': false,
            'searching': false,
            "order": [[0, "desc"]],
            'autoWidth': false,
            "pagingType": "full_numbers",
            language: {
                "url": "/Japanese.json"
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.lesson-history.statistic-dataTable') }}",
                type: 'GET',
                data: function (d) {
                    d.teacher_id = $('#teacher_id').val();
                    d.teacher_email = $('#teacher_email').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                },
            },
            columns: [
                {data: 'teacher_id', name: 'teacher_id',},
                {data: 'teacher_nickname', name: 'teacher_nickname', class: 'teacher_nickname'},
                {data: 'teacher_email', name: 'teacher_email', class: 'teacher_email_statistics'},
                {data: 'total_lessons', name: 'total_lessons', class: 'total_lessons'},
                {data: 'total_coins', name: 'total_coins', class: 'total_coins'},
            ],
            createdRow: function (row, data, rowIndex) {
                $.each($('td[class=" teacher_nickname"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" teacher_email_statistics"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
            }
        })

        $('#lessonHistories').DataTable({
            drawCallback: function () {
                let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                pagination.toggle(this.api().page.info().pages > 0);
                info.toggle(this.api().page.info().pages > 0);
            },
            'lengthChange': false,
            'searching': false,
            "order": [[0, "desc"], [1, 'desc']],
            'autoWidth': false,
            "pagingType": "full_numbers",
            language: {
                "url": "/Japanese.json"
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('admin.lesson-history.lesson-histories-dataTable') }}",
                type: 'GET',
                data: function (d) {
                    d.teacher_id = $('#teacher_id').val();
                    d.teacher_email = $('#teacher_email').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                },
            },
            columns: [
                {data: 'lesson_histories_date', name: 'lesson_histories_date',},
                {data: 'lesson_histories_time', name: 'lesson_histories_time'},
                {data: 'teacher_id', name: 'teacher_id', class: 'teacher_id'},
                {data: 'teacher_email', name: 'teacher_email', class: 'teacher_email'},
                {data: 'student_id', name: 'student_id', class: 'student_id'},
                {data: 'student_email', name: 'student_email', class: 'student_email'},
                {data: 'course_name', name: 'course_name', class: 'course_name', defaultContent: ''},
                {data: 'lesson_content', name: 'lesson_content', class: 'lesson_content', defaultContent: ''},
                {data: 'lesson_histories_coin', name: 'lesson_histories_coin',},
                {data: 'history_status', name: 'history_status',}
            ],
            createdRow: function (row, data, rowIndex) {
                $.each($('td[class=" teacher_email"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" student_email"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" lesson_content"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
            },
            "initComplete": function (settings) {
                $('#lessonHistories thead th').each(function () {
                    var $td = $(this);
                    $td.attr('title', $td.text());
                });

                /* Apply the tooltips */
                $('#lessonHistories thead th[title]').tooltip(
                    {
                        "container": 'body'
                    });
            }
        });
    </script>
    <script src="{{ asset('template/admin/plugins/select2/js/i18n/ja.js') }}"></script>
    <script src="{{ asset('js/admin/managers/lessons/lessonHistory.js') }}"></script>
@endpush
