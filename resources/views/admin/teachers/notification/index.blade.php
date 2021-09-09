@extends('layouts.admin.app')
@section('stylesheets')
<meta name="route-get-email" content="{{ route('teacher.notification.get-email') }}">
<meta name="delete-confirm" content="{{ __('validation_custom.M015') }}">
<meta name="delete-success" content="{{ __('validation_custom.M014') }}">
<meta name="route-notification-validation" content="{{ route('teacher.notification.search.validation') }}">
<link rel="stylesheet" href="{{ asset('css/admin/manager/students/index.css') }}"/>
<style>
    .input-group.col-sm-5 {
        height: 30px !important;
    }
</style>
@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('teacher_notification') }}
@endsection
@section('title_screen', '通知一覧')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form action="{{route('teacher.notification.search.validation')}}" method="post" id="formSearchNotification">
                        @csrf
                        <div class="card form-search-clear" id="searchNotification">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group row">
                                            <select class="col-sm-3 form-control mb-2" id="select_box">
                                                <option value="title">内容</option>
                                                <option value="date">作成日</option>
                                            </select>
                                            <div class="col-sm-9 col-12" id="input">
                                                <div class="input-group" id="inputTitle">
                                                    <input type="text" class="form-control" name="title" id="title">
                                                    <span class="invalid-feedback" role="alert"></span>
                                                </div>
                                                <div class="row d-none" id="inputDate">
                                                    <div class="input-group col-sm-5 ">
                                                        <input type="text" class="form-control datepicker created_at_from"
                                                               id="created_at_from" name="created_at_from"/>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <span class="invalid-feedback-custom" id="format_created_at_from"></span>
                                                    </div>
                                                    <div class="input-form col-sm-2 text-center"
                                                         style="max-height: 30px;">
                                                        <h2>~</h2>
                                                    </div>
                                                    <div class="input-group col-sm-5">
                                                        <input type="text" class="form-control datepicker created_at_to"
                                                               id="created_at_to" name="created_at_to"/>
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <span class="invalid-feedback-custom" id="format_created_at_to"></span>
                                                    </div>
                                                    <span class="ml-2 invalid-feedback-custom" id="error_date"></span>
                                                </div>
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
                            <table id="notifications" class="table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th style="width:50%">タイトル</th>
                                    <th style="width: 20%">作成者</th>
                                    <th>作成日</th>
                                    <th style="width:50px"  class="no-sort"></th>
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
                    url: "{{ route('teacher.notification.data-table') }}",
                    type: 'GET',
                    data: function (d) {
                        d.title = $('#title').val();
                        d.email = $('#email').val();
                        d.created_at_from = $('#created_at_from').val();
                        d.created_at_to = $('#created_at_to').val();
                    },
                },
                columnDefs: [
                    { targets: 'no-sort', orderable: false }
                ],
                columns: [
                    {
                        data: 'title', name: 'title', class: 'title', render: function (data) {
                            var array = data.split('&amp;#13;&amp;#10;');
                            let data1 = '';
                            $.each(array, function (index, value) {
                                data1 += value;
                            });
                            return data1
                        }
                    },
                    {data: 'email', name: 'email', class: 'email'},
                    {data: 'user_created_at', name: 'user_created_at'},
                    {data: 'btn_notification_detail', name: 'btn_notification_detail' , class:"text-center"}
                ],
                "createdRow":
                    function (row, data, rowIndex) {
                        $.each($('td[class=" title"]', row), function (colIndex, data) {
                            $(this).attr('data-toggle', "tooltip");
                            $(this).attr('data-placement', "top");
                            $(this).attr('data-original-title', $(data).html());
                        });
                        $.each($('td[class=" email"]', row), function (colIndex, data) {
                            $(this).attr('data-toggle', "tooltip");
                            $(this).attr('data-placement', "top");
                            $(this).attr('data-original-title', $(data).html());
                        });
                    }
            });
            $('#btnClearForm').click(function () {
                $('form#formSearchNotification').trigger('reset')
                $('.invalid-feedback-custom').html("")
                $('input').val("")
                $('input').removeClass('is-invalid');
                $('#area_message').html('');
                $('#inputDate').addClass('d-none')
                $('#inputTitle').removeClass('d-none')
                $('.itemName').val('').trigger('change')
            })
        });
    </script>
@endpush
