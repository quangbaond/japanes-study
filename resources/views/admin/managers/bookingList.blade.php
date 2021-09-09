@extends('layouts.admin.app')

@section('breadcrumb')
{{ Breadcrumbs::render('list_booking') }}
@endsection

@section('stylesheets')
    <meta name="delete-confirm" content="{{ config('constants.delete_confirm') }}">
    <meta name="delete-success" content="{{ config('constants.delete_success') }}">
    <meta name="route-search-live-email" content="{{ route('admin.booking-list.search-live-email') }}">
    <meta name="route-search-live-nickname" content="{{ route('admin.booking-list.search-live-nickname') }}">
    <meta name="route-data-tables" content="{{ route('admin.booking-list.data-tables') }}">
    <meta name="route-search-form" content="{{ route('admin.booking-list.validate-search-form') }}">
    <meta name="route-get-booking-detail" content="{{ route('admin.booking-list.detail') }}">
    <meta name="route-delete-booking" content="{{ route('admin.booking-list.delete') }}">
@endsection

@section('title_screen', '予約一覧')

@section('content')
    <style>
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
        .tooltip {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 100px;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
{{--                <p>@php echo ""; @endphp</p>--}}
                <div class="col-12">
                    <form method="post" id="searchForm">
                        @csrf
                        <div class="card form-search-clear" id="searchTeacher">
                            @include('includes.admin.message_error')
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-sm-9">
                                        <!-- teacher_id -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">ID</label>
                                            <div class="col-sm-9">
                                                <div class="input-group">
                                                    <select class="form-control"  multiple="multiple" id="user_id" style="width: 100%;" name="user_id[]">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- nationality -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">メールアドレス</label>
                                            <div class="col-sm-9">
                                                <select class="form-control"  multiple="multiple" id="email" style="width: 100%;" name="email[]">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-9">
                                        <!-- created_at -->
                                        <div class="form-group row">
                                            <label class="col-sm-3 col-form-label">日付	</label>
                                            <div class="col-sm-9 row px-0">
                                                <div class="input-group col-sm-5 pl-3 pr-0">
                                                    <input type="text" class="form-control datepicker" id="from_date" name="from_date"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5 pl-3 pr-0">
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
                        <div class="card-body table-responsive">
                            <table id="bookingHistory" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>日付</th>
                                        <th>時間</th>
                                        <th>講師ID</th>
                                        <th>講師メールアドレス</th>
                                        <th>生徒ID</th>
                                        <th>生徒メールアドレス</th>
                                        <th>コイン</th>
                                        <th style="width : 90px"></th>
                                        <th class="d-none"></th>
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
    <!-- modal-->
    <div class="modal fade" id="modalRemoveBookingByAdmin" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">予約のキャンセル</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mx-2">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-column flex-sm-column">
                            <label>講師</label>
                        </div>
                        <div class="col-2 col-sm-2 d-flex d-sm-flex flex-column flex-sm-column">
                            <span class="mb-2">:</span>
                        </div>
                        <div class="col-7 col-sm-7 d-flex d-sm-flex flex-column flex-sm-column">
                            <input type="hidden" id="booking_id" value="">
                            <span id="teacher_nickname" class="mb-2" style="word-break: break-all;"></span>
                        </div>
                    </div>
                    <div class="row mx-2">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-column flex-sm-column">
                            <label>生徒</label>

                        </div>
                        <div class="col-2 col-sm-2 d-flex d-sm-flex flex-column flex-sm-column">
                            <span class="mb-2">:</span>
                        </div>
                        <div class="col-7 col-sm-7 d-flex d-sm-flex flex-column flex-sm-column">
                            <span id="student_nickname" class="mb-2" style="word-break: break-all;"></span>
                        </div>
                    </div>
                    <div class="row mx-2">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-column flex-sm-column">
                            <label>日付</label>
                        </div>
                        <div class="col-2 col-sm-2 d-flex d-sm-flex flex-column flex-sm-column">
                            <span class="mb-2">:</span>
                        </div>
                        <div class="col-7 col-sm-7 d-flex d-sm-flex flex-column flex-sm-column">
                            <span id="start_date" class="mb-2"></span>
                        </div>
                    </div>
                    <div class="row mx-2">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-column flex-sm-column">
                            <label>時間</label>
                        </div>
                        <div class="col-2 col-sm-2 d-flex d-sm-flex flex-column flex-sm-column">
                            <span class="mb-2">:</span>
                        </div>
                        <div class="col-7 col-sm-7 d-flex d-sm-flex flex-column flex-sm-column">
                            <span id="start_hour" class="mb-2"></span>
                        </div>
                    </div>
                    <div class="row mx-2">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-column flex-sm-column">
                            <label>返却コイン</label>
                        </div>
                        <div class="col-2 col-sm-2 d-flex d-sm-flex flex-column flex-sm-column">
                            <span class="mb-2">:</span>
                        </div>
                        <div class="col-7 col-sm-7 d-flex d-sm-flex flex-column flex-sm-column">
                            <span id="coin" class="mb-2"></span>
                        </div>
                    </div>
                    <p class="text-center">この予約をキャンセルしますか？</p>
                </div>
                <div class="modal-footer text-center justify-content-center">
                    <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">いいえ</button>
                    <button type="button" data-idbooking="" data-start_date="" data-start_hour="" data-coin=""
                            class="btn btn-primary" id="btnConfirmRemove" data-date="">はい
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
    <script src="{{ asset('js/admin/booking-list/index.js') }}"></script>
@endpush
