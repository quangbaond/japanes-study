@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('curriculum_management') }}
@endsection

@section('stylesheets')
{{--    <meta name="delete-confirm" content="{{ config('constants.delete_confirm') }}">--}}
{{--    <meta name="delete-success" content="{{ config('constants.delete_success') }}">--}}
{{--    <meta name="route-search-live-email" content="{{ route('admin.booking-list.search-live-email') }}">--}}
{{--    <meta name="route-search-live-nickname" content="{{ route('admin.booking-list.search-live-nickname') }}">--}}
    <meta name="route-data-tables" content="{{ route('admin.curriculum.data-tables') }}">
{{--    <meta name="route-search-form" content="{{ route('admin.booking-list.validate-search-form') }}">--}}
{{--    <meta name="route-get-booking-detail" content="{{ route('admin.booking-list.detail') }}">--}}
{{--    <meta name="route-delete-booking" content="{{ route('admin.booking-list.delete') }}">--}}
@endsection

@section('title_screen', 'カリキュラム一覧')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form method="post" id="searchForm">
                        @csrf
                        <div class="card form-search-clear" id="">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">キーワード</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <input type="text" class="form-control" name="" id="" >
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-sm-9">
                                        <!-- created_at -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">作成日</label>
                                            <div class="col-sm-9 row px-0">
                                                <div class="input-group col-sm-5 pl-3">
                                                    <input type="text" class="form-control datepicker" id="from_date" name="from_date"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5 pr-0">
                                                    <input type="text" class="form-control datepicker" id="to_date" name="to_date"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <span class="pl-3 text-danger" id="error_date"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary float-right btn-flat ml-2" id="btnSearch">検索</button>
                                <a type="button" href="{{ route('admin.booking-list') }}" class="btn btn-default float-right btn-flat" id="btnClearForm">クリア</a>
                            </div>
                        </div>
                    </form>

                    <div class="card">
                        <div class="card-header ">
                            <a href="{{ route('admin.curriculum.create') }}"><button type="button" class="btn btn-success float-right btn-flat ml-2">追加</button>
                            </a>
                            <form action="" method="post" id="">
                                @csrf
                                <button type="button" class="btn btn-danger float-right btn-flat">削除</button>
                            </form>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="table-curriculum" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" id="check_all">
                                    </th>
                                    <th>カリキュラム名</th>
                                    <th>レベール</th>
                                    <th>説明</th>
                                    <th>作成日</th>
                                    <th style="width : 90px"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @for($i =0; $i < 10; ++$i)
                                        <tr>
                                            <th>
                                                <input type="checkbox">
                                            </th>
                                            <th>123</th>
                                            <th>123</th>
                                            <th>123</th>
                                            <th>123</th>
                                            <th style="width : 90px">123</th>
                                        </tr>
                                    @endfor
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
        })
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    </script>
    <script src="{{ asset('js/admin/managements/CurriculumManagement/index.js') }}"></script>
@endpush
