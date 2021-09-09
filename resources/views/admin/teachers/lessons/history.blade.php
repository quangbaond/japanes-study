@extends('layouts.admin.app')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/manager/teachers/index.css') }}"/>
    <meta name="route-search-live-email" content="{{ route('teacher.lesson-histories.search-live-email') }}">
    <meta name="route-search-live-nickname" content="{{ route('teacher.lesson-histories.search-live-nickname') }}">
    <meta name="route-get-list-lesson-histories" content="{{ route('teacher.lesson-history.data-tables') }}">
    <meta name="route-search-form" content="{{ route('teacher.lesson-history.validate-search-form') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/teachers/lessons/lesson_history.css') }}"/>
@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('teacher_lessonHistory') }}
@endsection
@section('title_screen', 'レッスン履歴')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="" method="post" id="formSearchLessonHistories">
                        @csrf
                        <div class="card form-search-clear" id="searchLessonHistories">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <!-- teacher_id -->
                                    <div class="col-sm-9">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">生徒ID</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <select class="form-control select2 studentID" multiple="multiple" name="studentID[]" id="studentID" style="width: 100%"></select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- nationality -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">生徒メールアドレス</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <select class="form-control select2 studentEmail" multiple="multiple" name="studentEmail[]" id="studentEmail" style="width: 100%;"></select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- created_at -->
                                    <div class="col-sm-9">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">日付</label>
                                            <div class="col-sm-9 row px-0">
                                                <div class="input-group item-input-date col-sm-5 pl-3 ">
                                                    <input type="text" class="form-control datepicker format_date_from date_from" id="date_from"
                                                           name="date_from" value=""/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text" style="max-height: 38px!important;"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    <span class="invalid-feedback-custom" id="format_date_from"></span>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group item-input-date col-sm-5 pl-3">
                                                    <input type="text" class="form-control datepicker format_date_to date_to" id="date_to"
                                                           name="date_to" value=""/>
                                                    <div class="input-group-append">
                                                        <div class="input-group-text" style="max-height: 38px!important;"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                    <span class="invalid-feedback-custom" id="format_date_to"></span>
                                                </div>
                                                <span class="invalid-feedback-custom ml-3" id="err_date"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary float-right btn-flat ml-2" id="btnSearch">検索</button>
                                <button type="button" class="btn btn-default float-right btn-flat" id="btnClearForm">クリア</button>
                            </div>
                        </div>
                    </form>
                    <div class="card">
                        <div class="card-body table-responsive">
                            <table id="lessonHistories" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>日付</th>
                                    <th>時間</th>
                                    <th>生徒ID</th>
                                    <th>生徒ニックネーム</th>
                                    <th>生徒メールアドレス</th>
                                    <th>コース</th>
                                    <th>レッスン内容</th>
                                    <th>コイン</th>
                                    <th>ステータス</th>
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
            $('[data-toggle="tooltip"]').tooltip();
        })
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    </script>
    <script src="{{ asset('js/admin/teachers/lesson_histories/index.js') }}"></script>

@endpush
