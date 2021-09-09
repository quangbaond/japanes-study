@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('notification') }}
@endsection

@section('stylesheets')
    <meta name="route-notification-validation" content="{{ route('admin.notification.search.validation') }}">
    <meta name="route-notification-delete" content="{{ route('admin.notification.delete') }}">
    <meta name="delete-confirm" content="{{ __('validation_custom.M015') }}">
    <meta name="delete-success" content="{{ __('validation_custom.M014') }}">
    <meta name="route-get-email" content="{{ route('admin.notification.get-email') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/manager/notifications/index.css') }}"/>

@endsection

@section('title_screen', '通知一覧')

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('admin.notification.search.validation')}}" method="post"
                          id="formSearchNotification">
                        @csrf
                        <div class="card form-search-clear" id="searchNotification">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <select class="col-sm-3 form-control mb-2" id="select_box">
                                                <option value="title">内容</option>
                                                <option value="email">作成者</option>
                                                <option value="date">作成日</option>
                                            </select>
                                            <div class="col-sm-9" id="input">
                                                <div class="input-group" id="inputTitle">
                                                    <input type="text" class="form-control" name="title" id="title">
                                                    <span class="invalid-feedback" role="alert"></span>
                                                </div>
                                                <div class="input-group d-none" id="inputEmail">
                                                    <select class="itemEmail form-control" id="email" name="email"
                                                            style="width: 100%"></select>
                                                    <span class="invalid-feedback" role="alert"></span>
                                                </div>
                                                <div class="row ml-5 d-none" id="inputDate">
                                                    <div class="input-group col-sm-5 ">
                                                        <input type="text" class="form-control datepicker created_at_from format_created_at_from"
                                                               id="created_at_from" name="created_at_from"/>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text" style="max-height: 38px!important;"><i id="icon_created_at_from" class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <span class="invalid-feedback-custom" id="format_created_at_from"></span>
                                                    </div>
                                                    <div class="input-form col-sm-2 text-center"
                                                         style="max-height: 30px;">
                                                        <h2>~</h2>
                                                    </div>
                                                    <div class="input-group col-sm-5">
                                                        <input type="text" class="form-control datepicker created_at_to format_created_at_to"
                                                               id="created_at_to" name="created_at_to"/>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text" style="max-height: 38px!important;"><i id="icon_created_at_to" class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <span class="invalid-feedback-custom" id="format_created_at_to"></span>
                                                    </div>
                                                </div>
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
                        <div class="card-header">
                            <a href="{{ route('admin-notification.create') }}"
                               class="btn btn-success float-right btn-flat ml-2">追加</a>
                            <form action="" method="post" id="formDeleteNotification">
                                @csrf
                                <button type="button" class="btn btn-danger float-right btn-flat" id="btnDelete"
                                        disabled>削除
                                </button>
                            </form>
                        </div>
                        <div class="card-body table-responsive">
                            <table id="notifications" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th style="width: 10px">
                                        <input type="checkbox" name="" id="check_all">
                                    </th>
                                    <th>タイトル</th>
                                    <th>作成者</th>
                                    <th>作成日</th>
                                    <th style="width:50px"></th>
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
    <script src="{{ asset('js/admin/managers/notification/index.js') }}"></script>
    <script src="{{ asset('template/admin/plugins/select2/js/i18n/ja.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        $(document).ready(function () {
            $('#notifications').DataTable({
                drawCallback: function () {
                    let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                    let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                    pagination.toggle(this.api().page.info().pages > 0);
                    info.toggle(this.api().page.info().pages > 0);
                },
                'lengthChange': false,
                'searching': false,
                "order": [[3, "desc"]],
                'autoWidth': false,
                "pagingType": "full_numbers",
                language: {
                    "url": "/Japanese.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.notification.data-table') }}",
                    type: 'GET',
                    data: function (d) {
                        d.title = $('#title').val();
                        d.email = $('#email').val();
                        d.created_at_from = $('#created_at_from').val();
                        d.created_at_to = $('#created_at_to').val();
                    },
                },
                columns: [
                    {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                    {
                        data: 'title', name: 'title', class: 'title', render: function (data) {
                            var array = data.split('&amp;#13;&amp;#10;');
                            let data1 = '';
                            if(array.length > 2) {
                                $.each(array, function (index, value) {
                                    data1 += value;
                                });
                                return data1
                            }
                            else return array[0];
                        }
                    },
                    {data: 'email', name: 'email', class: 'email'},
                    {data: 'user_created_at', name: 'user_created_at'},
                    {data: 'btn_notification_detail', name: 'btn_notification_detail', orderable: false},
                ],
                "createdRow":
                    function (row, data, rowIndex) {
                        $.each($('td[class=" title"]', row), function (colIndex, data) {
                            $(this).attr('data-toggle', "tooltip");
                            $(this).attr('data-placement', "top");
                            $(this).attr('data-original-title', $(data).text());
                        });
                    }
            });
        });
    </script>
@endpush
