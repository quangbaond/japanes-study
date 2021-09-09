@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('list_students') }}
@endsection

@section('stylesheets')
    <meta name="confirm-delete" content="{{ config('constants.delete_confirm') }}">
    <meta name="delete-success" content="{{ config('constants.delete_success') }}">
    <meta name="route-student-delete" content="{{ route('admin.student.delete-all') }}">
    <meta name="route-student-validation" content="{{ route('admin.student.validation') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/manager/students/index.css') }}"/>
@endsection

@section('title_screen', '生徒一覧')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.student.validation')}}" method="post" id="formSearchStudent">
                        @csrf
                        <div class="card form-search-clear" id="searchStudent">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">生徒ID</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="student_id" name="student_id" class="form-control">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">会社名</label>
                                            <div class="col-sm-8">
                                                <select class="form-control select2" style="width: 100%;" id="company_name">
                                                    <option></option>
                                                    @foreach($students as $student)
                                                        <option name="company_name" value="{{$student->id}}">{{$student->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">メールアドレス</label>
                                            <div class="col-sm-8">
                                                <input type="email" id="email" name="email" class="form-control">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">電話番号</label>
                                            <div class="col-sm-9 input-group">
                                                <input type="text" id="phone_number" name="phone_number" class="form-control mr-3">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">会員状態</label>
                                            <div class="col-sm-9 input-group">
                                                <select class="form-control select2 mr-3" id="membership_status" name="membership_status">
                                                    <option></option>
                                                    <option value="{{ config('constants.membership.id.free') }}">無料</option>
                                                    <option value="{{ config('constants.membership.id.premium_trial') }}">トライアル</option>
                                                    <option value="{{ config('constants.membership.id.premium') }}">プレミアム</option>
                                                    <option value="{{ config('constants.membership.id.Special') }}">会社員</option>
                                                    <option value="{{ config('constants.membership.id.other_company') }}">他の会社</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">登録日</label>
                                            <div class="col-sm-9 row">
                                                <div class="input-group col-sm-5">
                                                    <input type="text" name="from_date" id="from_date" class="form-control datepicker"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="input-form col-lg-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5">
                                                    <input type="text" name="to_date" id="to_date" class="form-control datepicker"/>
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
                        <div class="card-header ">
                            <a href="{{ route('admin.student.create') }}"><button type="button" class="btn btn-success float-right btn-flat ml-2" id="btnSearch">追加</button></a>
                            <form action="" method="post" id="formDeleteAllStudent">
                                @csrf
                                <button type="button" class="btn btn-danger float-right btn-flat" id="delete-all-student" disabled>削除</button>
                            </form>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="students" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">
                                            <input type="checkbox" id="check_all">
                                        </th>
                                        <th>生徒ID</th>
                                        <th>ニックネーム</th>
                                        <th>メールアドレス</th>
                                        <th>電話番号</th>
                                        <th>会員状態</th>
                                        <th>会社名</th>
                                        <th>コイン</th>
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
    <script src="{{ asset('js/admin/managers/students/index.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        $(document).ready(function () {
            $('#students').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "responsive": true,
                "pagingType": "full_numbers",
                "order": [[ 1, "desc" ]],
                'autoWidth'   : false,
                language: {
                    "url": "/Japanese.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('student.data') }}",
                    type: 'GET',
                    data: function (d) {
                        d.email             = $('#email').val();
                        d.student_id        = $('#student_id').val();
                        d.phone_number      = $('#phone_number').val();
                        d.company_name      = $('#company_name option:selected').val();
                        d.membership_status = $('#membership_status option:selected').val();
                        d.from_date         = $('#from_date').val();
                        d.to_date           = $('#to_date').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'id', name: 'id',},
                    { data: 'nickname', name: 'nickname', class : 'nickname' },
                    { data: 'email', name: 'email', class:'email'},
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'membership_status', name: 'membership_status' },
                    { data: 'name', name: 'name' },
                    { data: 'td_hiden2', name: 'td_hiden2 '},
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false },
                ],
                "createdRow": function (row, data, rowIndex) {
                    $.each($('td[class=" nickname"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).html());
                    });
                    $.each($('td[class=" email"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).html());
                    });
                }
            });
        });
    </script>
@endpush
