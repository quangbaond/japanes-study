@extends('layouts.student.app')
@section('admin_title')
    {{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
    <meta name="route-student-search" content="{{ route('student.validation') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('css/admin/teachers/timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/student/home.css') }}">
    <meta name="route-get-data-payment-histories" content="{{ route('student.datatable.payment-histories') }}">
@endsection

@section('content')
<div class="container px-0">
    <div class="col-12 px-0">
        <div class="card w-100">
            <div class="card-header ">
                <h3 class="text-bold card-title">{{__('student.payments_history')}}</h3>
            </div>
            <div class="card-body ">
                <div class="row mt-2">
                    <div class="w-100 mb-3 align-items-center">
                        <div class="col-12">
                            <span id="no-data" class="d-none">{{ __('student.no_data_payment_histories') }}</span>
                            <div class="">
                                <table id="table_payments_history" style="width:100%" class="table table-md table-bordered dt-responsive table-hover">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>{{__('student.detail')}}</th>
                                            <th>{{__('student.amount')}}</th>
                                            <th>{{__('student.billing_date')}}</th>
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
        </div>
    </div>
</div>
    @endsection
@push('scripts')
{{--    <script src="{{ asset('js/student/payment_histories.js') }}"></script>--}}
    <script>
            $(document).ready(function () {
                let date = new Date();
                var table = $('#table_payments_history').DataTable({
                    drawCallback: function () {
                        let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                        let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                        pagination.toggle(this.api().page.info().pages > 0);
                        info.toggle(this.api().page.info().pages > 0);
                    },
                    'lengthChange': false,
                    'searching': false,
                    "order": [[2, "desc"]],
                    'autoWidth': false,
                    "pagingType": "full_numbers",
                    "pageLength": "{{ config('constants.pagination') }}",
                    language: {
                        "url": "{{ __('datatables.language') }}"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('student.datatable.payment-histories') }}",
                        type: 'GET',
                        data: {}
                    },
                    columns: [
                        {data: 'description', name: 'description', orderable: false, searchable: false},
                        {
                            data: null,
                            name: 'amount',
                            render: function (data, type, row) {
                                return data.amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",") + " " + data.currency.toUpperCase();
                            }
                        },
                        {data: 'created', name: 'created',searchable: false}
                    ],
                });
                table.on('draw', function () {
                    if (table.data().any()) {
                        $(this).parent().show();
                    } else {
                        $(this).parent().hide();
                        $('#no-data').removeClass('d-none');
                        $('#no-data').addClass('d-block');
                    }
                });
            })

    </script>
@endpush
