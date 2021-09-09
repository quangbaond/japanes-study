@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('list_admin') }}
@endsection

@section('stylesheets')
    <meta name="confirm-delete" content="{{ __('validation_custom.M015') }}">
    <meta name="delete-success" content="{{ config('constants.delete_success') }}">
    <meta name="route-get-list-admins" content="{{ route('admin.admin-list.data-tables') }}">
    <meta name="route-validate-search-form" content="{{ route('admin.admin-list.validate') }}">
    <meta name="delete-success" content="{{ config('constants.delete_success') }}">
    <meta name="route-delete-admins" content="{{ route('admin.admin-list.delete') }}">
@endsection

@section('title_screen', 'アドミン一覧')

@section('content')
    <style>
        .select2-selection {
            min-width: 100%!important;
            /*border-right: 0px;*/
        }
        td {
            max-width: 70px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        thead > tr > th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 20px;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.student.validation')}}" method="post" id="formSearchStudent">
                        @csrf
                        <div class="card form-search-clear" id="searchAdmins">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">アドミンID</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="admin_id" name="admin_id" class="form-control">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">メールアドレス</label>
                                            <div class="col-sm-8">
                                                <input type="text" id="email" name="email" class="form-control">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">電話番号</label>
                                            <div class="input-group col-sm-8" >
                                                <input type="text" class="form-control rounded-right" name="phone_number" id="phone_number">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">役割</label>
                                            <div class="col-sm-9 input-group">
                                                <select class="form-control select2" id="role" name="role">
                                                    <option></option>
                                                    <option value="{{ config('constants.role.admin') }}">親</option>
                                                    <option value="{{ config('constants.role.child-admin') }}">子</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class=" col-sm-3 col-form-label">登録日</label>
                                            <div class="col-sm-9 row">
                                                <div class="input-group col-sm-5">
                                                    <input type="text" name="from_date" id="from_date" class="form-control datepicker"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5">
                                                    <input type="text" name="to_date" id="to_date" class="form-control datepicker"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <span class="ml-2 invalid-feedback-custom" role="alert"></span>
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
                            <a href="{{ route('admin.admin-list.create') }}"><button type="button" class="btn btn-success float-right btn-flat ml-2"
                                 @if(Auth::user()->role != config('constants.role.child-admin'))
                                    id="btnSearch"
                                 @else
                                     disabled
                                 @endif
                                >追加</button>
                            </a>
                            <form action="" method="post" id="formDeleteAllAdmin">
                                @csrf
                                <button type="button" class="btn btn-danger float-right btn-flat"
                                        @if(Auth::user()->role != config('constants.role.child-admin'))
                                            id="delete-all-admin"
                                        @else
                                            disabled
                                        @endif
                                        disabled>削除</button>
                            </form>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="admins" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">
                                            <input type="checkbox" id="check_all" @if(Auth::user()->role == config('constants.role.child-admin')) disabled="disabled" @endif>
                                        </th>
                                        <th>ID</th>
                                        <th>ニックネーム</th>
                                        <th>メールアドレス</th>
                                        <th>電話番号</th>
                                        <th>役割</th>
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
    <script src="{{ asset('js/admin/managers/AdminList/index.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    </script>
@endpush
