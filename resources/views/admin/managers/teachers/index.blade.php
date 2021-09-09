@extends('layouts.admin.app')

@section('breadcrumb')
{{ Breadcrumbs::render('list_teachers') }}
@endsection

@section('stylesheets')
    <meta name="delete-confirm" content="{{ config('constants.delete_confirm') }}">
    <meta name="delete-success" content="{{ config('constants.delete_success') }}">
    <meta name="route-teacher-validation" content="{{ route('admin.teacher.validation') }}">
    <meta name="route-teacher-delete" content="{{ route('admin.teacher.delete') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/manager/teachers/index.css') }}"/>
@endsection

@section('title_screen', '講師一覧')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.teacher.validation')}}" method="post" id="formSearchTeacher">
                        @csrf
                        <div class="card form-search-clear" id="searchTeacher">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <!-- teacher_id -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">講師ID</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="teacher_id" id="teacher_id">
                                                    <span class="invalid-feedback" role="alert"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- nationality -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">国籍</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" id="nationality" style="width: 100%;" name="nationality">
                                                    <option value=""></option>
                                                    @foreach($nationalities as $key => $nationality)
                                                        <option value="{{$key}}">{{$nationality}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- mail -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">メールアドレス</label>
                                            <div class="col-sm-8">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="email" id="email">
                                                    <span class="invalid-feedback" role="alert"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- sex -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">性別</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" id="sex" name="sex">
                                                    <option></option>
                                                    <option value="{{ config('constants.sex.id.male') }}">男性</option>
                                                    <option value="{{ config('constants.sex.id.female') }}">女性</option>
                                                    <option value="{{ config('constants.sex.id.unspecified') }}">指定なし</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <!-- phone_number -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">電話番号</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control mr-3" name="phone_number" id="phone_number">
                                                    <span class="invalid-feedback" role="alert"></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- age -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">年齢</label>
                                            <div class="col-sm-9 row">
                                                <div class="input-group col-sm-5">
                                                    <input type="text" class="form-control" id="age_from" name="age_from"/>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5">
                                                    <input type="text" class="form-control" id="age_to" name="age_to"/>
                                                </div>
                                                <span class="ml-2 invalid-feedback-custom"></span>
                                            </div>
                                        </div>

                                        <!-- created_at -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">登録日</label>
                                            <div class="col-sm-9 row">
                                                <div class="input-group col-sm-5">
                                                    <input type="text" class="form-control datepicker" id="created_at_from" name="created_at_from"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5">
                                                    <input type="text" class="form-control datepicker" id="created_at_to" name="created_at_to"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <span class="ml-2 invalid-feedback-custom"></span>
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
                        <div class="card-header">
                            <a href="{{ route('admin.teacher.create') }}"><button type="button" class="btn btn-success float-right btn-flat ml-2">追加</button></a>
                            <form action="" method="post" id="formDeleteTeacher">
                                @csrf
                                <button type="button" class="btn btn-danger float-right btn-flat" id="btnDelete" disabled>削除</button>
                            </form>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="teachers" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th hidden></th>
                                        <th style="width: 10px">
                                            <input type="checkbox" id="check_all">
                                        </th>
                                        <th>講師ID</th>
                                        <th>ニックネーム</th>
                                        <th>メールアドレス</th>
                                        <th>電話番号</th>
                                        <th>国籍</th>
                                        <th>年齢</th>
                                        <th>性別</th>
                                        <th>登録日</th>
                                        <th style="width: 50px"></th>
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
    <script src="{{ asset('js/admin/managers/teachers/index.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        $(document).ready(function () {
            $('#teachers').DataTable({
                drawCallback: function() {
                    let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                    let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                    pagination.toggle(this.api().page.info().pages > 0);
                    info.toggle(this.api().page.info().pages > 0);
                },
                'lengthChange': false,
                'searching'   : false,
                "order": [[ 2, "desc" ]],
                'autoWidth'   : false,
                "pagingType": "full_numbers",
                language: {
                    "url": "/Japanese.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.teacher.data') }}",
                    type: 'GET',
                    data: function (d) {
                        d.teacher_id        = $('#teacher_id').val();
                        d.phone_number      = $('#phone_number').val();
                        d.nationality       = $('#nationality').val();
                        d.age_from          = $('#age_from').val();
                        d.age_to            = $('#age_to').val();
                        d.email              = $('#email').val();
                        d.created_at_from   = $('#created_at_from').val();
                        d.created_at_to     = $('#created_at_to').val();
                        d.sex               = $('#sex option:selected').val();
                        d.check_search      = $('#check_search').val();
                    },
                },
                columns: [
                    { data: 'td_hidden', name: 'td_hidden', "visible": false},
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'id', name: 'id' },
                    { data: 'nickname', name: 'nickname', class: 'nickname'},
                    { data: 'email', name: 'email', class: 'email' },
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'nationality', name: 'nationality' },
                    { data: 'age', name: 'age' },
                    { data: 'sex', name: 'sex' },
                    { data: 'created_at_user', name: 'created_at_user' },
                    { data: 'action', name: 'action', orderable: false }
                ],
                "createdRow": function (row, data, rowIndex) {
                    $.each($('td[class=" nickname"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).text());
                    });
                    $.each($('td[class=" email"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).text());
                    });
                }
            });
        });
    </script>
@endpush
