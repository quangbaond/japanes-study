@extends('layouts.student.app')
@section('admin_title')
{{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
<meta name="error-input" content="{{ __('validation_custom.M009') }}">
<meta name="route-notification-validation" content="{{ route('student.notification.search.validation') }}">
<meta name="route-notification-list" content="{{ route('student.notification.data-table') }}">
<meta name="lang_table_no_result" content="{{__('student.no_result')}}">
<meta name="lang_table_empty_table" content="{{__('student.empty_table')}}">
<link rel="stylesheet" href="{{ asset('css/admin/manager/students/index.css') }}"/>
<style>
    @media (max-width:575px) {
        .px-sm-0 {
            padding: 7.5px 0px;
        }
    }
    .input-group-text {
        max-height: 38px;
    }
</style>
@endsection

@section('content')

<div class="container">
    <section class="content">
        <div class="row">
            <div class="col-12 px-0">
                <form action="{{route('student.notification.search.validation')}}" method="post" id="formSearchNotification">
                    @csrf
                    <div class="card form-search-clear" id="searchNotification">
                        @include('includes.admin.message_error')
                        <div class="card-header">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row">
                                        <select class="col-md-3 col-sm-12 form-control" id="search" style="margin: 0px 0 10px 0;">
                                            <option value="title">{{__('student.content')}}</option>
                                            <option value="created_at">{{__('student_notification.created_at')}}</option>
                                        </select>
                                        <div class="col-md-9 col-sm-12 px-sm-0 px-md-2" id="inputTitle">
                                            <div class="input-group">
                                                <input type="text"  id="title" class="form-control" name="title">
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="row d-none col-md-9 col-sm-12 px-md-2 px-sm-0" id="inputCreated">
                                            <div class="input-group col-sm-5 ">
                                                <input type="text" class="form-control created_at_from datepicker"
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
                                                <input type="text" class="form-control datepicker"
                                                        id="created_at_to" name="created_at_to"/>
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i class="fa fa-calendar"></i>
                                                    </div>
                                                </div>
                                                <span class="invalid-feedback-custom" id="format_created_at_to"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary float-right btn-flat ml-2" id="btnSearch">{{__('student.search')}}</button>
                            <button type="button" class="btn btn-default float-right btn-flat" id="btnClearForm">{{__('student.clear')}}</button>
                        </div>
                    </div>
                </form>
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-bold card-title">{{__('student.notification')}}</h3>
                    </div>
                    <div class="card-body table-responsive">
                        <table id="notifications" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 50%">{{__('student_notification.title')}}</th>
                                    <th style="width: 20%;">{{__('student_notification.created_by')}}</th>
                                    <th>{{__('student_notification.created_at')}}</th>
                                    <th style="" class="no-sort"></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>

@endsection
@push('scripts')
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
$('body').tooltip({selector: '[data-toggle="tooltip"]'});
</script>

<script src="{{ asset('js/admin/students/notification.js') }}"></script>
@endpush
