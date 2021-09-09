@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('payment_information') }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/manager/StudentPaymentInformation/infor.css') }}"/>
    <meta content="{{ route('admin.payment.validate') }} " name="route-validate-search-form">
@endsection

@section('title_screen', '決済一覧')

@section('content')
    <style>
        a.disabled {
            pointer-events: none;
            color: #ccc;
        }
    </style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form method="GET" id="formSearchPaymentIntents" enctype='multipart/form-data'>
{{--                        @csrf--}}
                        <div class="card form-search-clear" id="searchStudent">
                            @include('includes.admin.message_error')
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12 col-sm-9">
                                        <div class="form-group d-flex d-sm-flex">
                                            <label class="w-25 col-form-label">決済日</label>
                                            <div class="w-75 row">
                                                <div class="input-group col-sm-5">
                                                    <input type="text" name="from_date" id="from_date" class="form-control datepicker" value="{{ $_GET['from_date'] ?? '' }}"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <div class="input-form col-sm-2 text-center" style="max-height: 30px;">
                                                    <h2>~</h2>
                                                </div>
                                                <div class="input-group col-sm-5 pr-0">
                                                    <input type="text" name="to_date" id="to_date" class="form-control datepicker" value="{{ $_GET['to_date'] ?? '' }}"/>
                                                    <div class="input-group-append" >
                                                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                                    </div>
                                                </div>
                                                <span class="text-danger ml-2" id="error_date"></span>
                                            </div>
                                        </div>
                                        <div class="form-group d-flex d-sm-flex">
                                            <label class="w-25 col-form-label">顧客のメールアドレス</label>
                                            <div class="w-75 input-group">
                                                <select class="form-control" id="user_id" name="user_id[]" multiple="multiple" style="width: 100%">
                                                    @if(isset($_GET['user_id']))
                                                        @foreach($_GET['user_id'] as $val)
                                                            <option value="{{ $val }}" selected>{{ $emailMatchCustomerIdSelected[$val] ?? '' }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                                <span class="text-danger" id="error_email"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <a href="javascript:;" type="button" class="btn btn-primary float-right btn-flat ml-2" id="btnSearch">検索</a>
                            <a type="button" class="btn btn-default float-right btn-flat" href="{{ route('admin.payment.index') }}">クリア</a>
                        </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <!-- /.card-header -->
                        <div class="card-body table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th style="width: 150px" >金額</th>
                                            <th>決済内容</th>
                                            <th class="email_column" style="min-width: 300px; width: 300px;max-width: 300px">顧客のメールアドレス</th>
                                            <th class="status" style="min-width: 100px; max-width: 100px;width: 100px">ステータス</th>
                                            <th width="170px">決済日</th>
                                            <th style="min-width: 120px; width: 120px">ストライプの顧客ID</th>
                                        </tr>
                                    </thead>
                                    @if(!is_null($paymentHistories) && !empty($paymentHistories))
                                    <tbody>
                                        @foreach($paymentHistories as $paymentHistory)
                                            <tr>
                                                <td>{{ number_format($paymentHistory->amount_received, '0') ." ". strtoupper($paymentHistory->currency)  }}</td>
                                                <td style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;max-width: 200px" data-toggle="tooltip" data-placement="top" title="{{ $paymentHistory->description }}">{{ $paymentHistory->description }}</td>
                                                <td style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;max-width: 230px" data-toggle="tooltip" data-placement="top" title="{{ $emailMatchCustomerId[$paymentHistory->customer] ?? '' }}">{{ $emailMatchCustomerId[$paymentHistory->customer] ?? '' }}</td>
                                                <td class="text-center" style="max-width: 50px">
                                                @if($paymentHistory->status == 'succeeded')
                                                    <span class="badge badge-success">Success <i class="fas fa-check"></i></span>
                                                @else
                                                    <span class="badge badge-danger">Failed <i class="fas fa-times"></i></span>
                                                @endif
                                                </td>
                                                <td>{{ $paymentHistory->created }}</td>
                                                <td>{{ $paymentHistory->customer }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    @else
                                        <tbody>
                                            <tr>
                                                <td colspan="6" class="text-center">{{ __('validation_custom.M011') }}</td>
                                            </tr>
                                        </tbody>
                                    @endif
                                </table>
                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer clearfix d-flex justify-content-end">
                            <a class="page-link {{ $buttonPrevious ? "" : "disabled"}}" href="{{ $previous_url ?? 'javascript:;' }}" >&laquo;</a>
                            <a class="page-link {{ $buttonNext ? "" : "disabled"}}" href="{{ $next_url ?? 'javascript:;' }}">&raquo;</a>
{{--                            <ul class="pagination pagination-sm m-0 float-right">--}}

{{--                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>--}}
{{--                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>--}}
{{--                            </ul>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
<script src="{{ asset('js/admin/managers/admin/paymentIntents.js') }}"></script>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
    $('body').tooltip({selector: '[data-toggle="tooltip"]'});
    $(document).ready(function () {
        $.fn.select2.defaults.set('language', {
            // errorLoading: function () {
            //     return "ERROR_LOADING";
            // },
            // inputTooLong: function (args) {
            //     return "INPUT_TOO_LONG";
            // },
            // inputTooShort: function (args) {
            //     return "INPUT_TOO_SHORT";
            // },
            // loadingMore: function () {
            //     return "LOADING_MORE";
            // },
            // maximumSelected: function (args) {
            //     return "MAX_SELECTED";
            // },
            noResults: function () {
                return "該当がありません。";
            },
            searching: function () {
                return "検索中";
            }
        });
        $('#user_id').select2({
            placeholder: '',
            ajax: {
                url: '{{ route('admin.payment.search-email') }}',
                dataType: 'json',
                delay: 100,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.email,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    });
</script>
@endpush

