@extends('layouts.student.app')
@section('admin_title')
    {{--    {{ $data->title }}--}}
@endsection

@section('stylesheets')
    <link href="{{ asset('css/student/home.css') }}" rel="stylesheet">
    <meta name="route-payment-coin" content="{{ route('student.payment-coin') }}">
    <meta name="route-validation-payment-coin" content="{{ route('student.payment-coin.validation') }}">
    <meta name="route-check-cancel-premium" content="{{ route('student.check-cancel-premium') }}">
    <meta name="m043" content="{{ __('validation_custom.M043') }}">
    <meta name="check_radio_required" content="{{ __('validation_custom.check_radio_required') }}">
    <meta name="m046" content="{{ __('validation_custom.M046') }}">
    <meta name="m047" content="{{ __('validation_custom.M047') }}">
    <meta name="M054" content="{{ __('validation_custom.M054') }}">
    <meta name="card-number" content="{{ __('student.step3.number_card') }}">
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="card w-100">
                <div class="card-header">
                    <h3 class="card-title text-bold">{{ __('student_add_coin.add_coin.title') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-sm-3">
                            <label class="text-bold" style="font-size: 20px">{{ __('student_add_coin.add_coin.current_coin') }}</label>
                        </div>
                        <div class="col-sm-3">
                            <label class="text-bold text-info total-coin" style="font-size: 40px">{{!empty($student->total_coin) ? $student->total_coin : 0}}</label>
                        </div>
                        <div class="col-sm-6">
                            <button type="button" class="btn btn-info" {{!empty($student->total_coin) ? '' : 'disabled'}} id="confirm_deadline"  data-toggle="modal" data-target="#modal-lg">{{ __('student_add_coin.add_coin.confirm_deadline') }}</button>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <p>・ {{ __('student_add_coin.add_coin.content1') }}</p>
                        <p>・ {{ __('student_add_coin.add_coin.content2') }}</p>
                        <p>・ {{ __('student_add_coin.add_coin.content3') }}</p>
                    </div>
                    <div class="row">
                        <div class="table-responsive">
                            <table id="schedule" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('student_add_coin.add_coin.additional_coin') }}</th>
                                        <th>{{ __('student_add_coin.add_coin.bonus_coin') }}</th>
                                        <th>{{ __('student_add_coin.add_coin.amount') }}</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($master_coin as $key => $item)
                                        <tr>
                                            <td class="text-bold text-info " style="font-size: 20px">
                                                <div class="d-flex ">
                                                    <img src="{{asset('images/coin.png')}}" width="30px" height="30px" class="mr-3">
                                                    <div>{{ $item->coin }}</div>
                                                </div>
                                            </td>
                                            <td>{{ $item->bonus_coin }}</td>
                                            <td>{{ number_format($item->amount) }} VND</td>
                                            <td>
                                                <button
                                                    data-id="{{ $item->id }}"
                                                    data-coin="{{ $item->coin }}"
                                                    data-bonus-coin="{{ $item->bonus_coin }}"
                                                    data-amount="{{ number_format($item->amount) }}"
                                                    class="btn btn-outline-warning text-bold border-2 openModalAddCoin"
                                                    @if(
                                                    $student->membership_status == config('constants.membership.id.free')
                                                    || $student->membership_status == config('constants.membership.id.Special')
                                                    || $student->membership_status == config('constants.membership.id.other_company')
                                                    ) disabled="true" @endif
                                                >
                                                    {{ __('student_add_coin.add_coin.add_coin') }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="card w-100">
                <div class="card-header">
                    <h3 class="card-title text-bold">{{ __('student_add_coin.history.title') }}</h3>
                </div>
                <div class="card-body table-responsive">

                    <div class="alert alert-success alert-dismissible d-none" id="area_message_success">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-check"></i>
                        <span id="message_success"></span>
                    </div>
                    <div class="alert alert-danger alert-dismissible d-none" id="area_message_error">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <i class="icon fa fa-ban"></i>
                        <span></span>
                    </div>


                    <table id="history_use_coin" class="table table-bordered table-hover @if($history == 0) d-none @endif">
                        <thead>
                            <tr>
                                <th hidden>ID</th>
                                <th>{{ __('student_add_coin.history.date') }}</th>
                                <th>{{ __('student_add_coin.history.content') }}</th>
                                <th>{{ __('student_add_coin.history.coin') }}</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                    @if($history == 0)
                        <p id="M070">{{ __('validation_custom.M070') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- modal show current coin -->
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <h4 class="">{{ __('student_add_coin.add_coin.deadline_use') }}</h4>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-between">
                            <div class="modal-title">
                                <p class="font-weight-bold">{{ __('student_add_coin.add_coin.current_coin') }}</p>
                            </div>
                            <div class="float-right">
                                <h4 class="text-info total-coin">{{!empty($student->total_coin) ? $student->total_coin : 0}}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12 d-flex justify-content-between">
                            <div class="modal-title">
                                <p class="font-weight-bold">{{ __('student_add_coin.add_coin.expiration_date') }}</p>
                            </div>
                            <div class="float-right">
                                <h4 class="text-info" id="expiration_date">{{!empty($student->expiration_date) ?  Timezone::convertToLocal(\Carbon\Carbon::parse($student->expiration_date), 'Y-m-d H:i:s') : ''}}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- ./modal show current coin -->

    <!-- modal add coin -->
    <div class="modal fade" id="modal-add-coin">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title"><h4 class="font-weight-bold">{{ __('student_add_coin.modal_payment_method.title') }}</h4></div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body" style="height: 450px;overflow-y: auto;">
                    <div id="card-error"></div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-6">
                                    {{ __('student.step3.from') }}
                                    <address>
                                        <span>{{ __('student.step3.email') }}: <span>{{$student->email}}</span></span><br>
                                        {{ __('student.step3.phone') }}: <span>@if(!empty($student->phone_number)) ({{$student->area_code}}) @endif</span> <span id="step2_phone">{{$student->phone_number}}</span><br>
                                    </address>
                                </div>
                                <div class="col-sm-6">
                                    {{ __('student.step3.to') }}
                                    <address>
                                        <span>{{ __('student.step3.email') }}: {{ config('constants.contact_email') }}</span><br>
                                        {{ __('student.step3.phone') }}: {{ config('constants.contact_phone') }}<br>
                                    </address>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12 table-responsive">
                                    <table class="table table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>{{ __('student_add_coin.add_coin.additional_coin') }}</th>
                                                <th>{{ __('student_add_coin.add_coin.bonus_coin') }}</th>
                                                <th>{{ __('student_add_coin.add_coin.amount') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td id="coin-show"></td>
                                                <td id="bonus-coin-show" class="text-info text-bold"></td>
                                                <td id="amount-coin" class="text-warning text-bold"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                    <img src="{{ asset('images/credit/visa.png') }}" alt="Visa">
                                    <img src="{{ asset('images/credit/mastercard.png') }}" alt="Mastercard">
                                    <img src="{{ asset('images/credit/american-express.png') }}" alt="American Express">
                                    <span>{{ __('student.step3.payment_with_stripe') }}</span> <a href="https://stripe.com" target="_blank">{{ __('student.step3.what_stripe') }}</a>
                                    <hr>
                                    <p><img src="{{ asset('images/icon-tick.png') }}" alt="" width="20px" height="20px">{{ __('student.step3.fast_payment') }}</p>
                                    <p><img src="{{ asset('images/icon-tick.png') }}" alt="" width="20px" height="20px">{{ __('student.step3.absolute_safety') }}</p>
                                    <p><img src="{{ asset('images/icon-tick.png') }}" alt="" width="20px" height="20px">{{ __('student.step3.secure_user') }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <form action="{{route('student.payment-coin')}}" method="post" id="formPaymentAddCoinForStudent" enctype="multipart/form-data">
                                @csrf
                                <div class="alert alert-danger alert-dismissible d-none" id="area_message_choice_payment">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                    <span id="message_choice_payment"></span>
                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="icheck-primary">
                                            <input type="radio" id="radioPrimary2" name="choicePayment" value="1" checked>
                                            <label for="radioPrimary2">{{ __('student_add_coin.modal_payment_method.radio1') }}</label>
                                        </div>
                                        <div class="icheck-primary">
                                            <input type="radio" id="radioPrimary1" name="choicePayment" value="2">
                                            <label for="radioPrimary1">{{ __('student_add_coin.modal_payment_method.radio2') }}</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card d-none" id="formAddCoin">
                                    <div class="card-header">
                                        <h3 class="card-title"><i class="far fa-credit-card"></i> {{ __('student.step3.debit_or_credit') }}</h3>
                                    </div>
                                    <div class="card-body">
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <label>{{ __('student.step3.name_card') }}</label>
                                                <input type="text" class="form-control" name="name_card" id="name_card" value="">
                                                <strong class="invalid-feedback" role="alert"></strong>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>{{ __('student.step3.number_card') }}</label>
                                            <input type="text" class="form-control format_visa" name="number_card" id="number_card" value="" maxlength="19">
                                            <strong class="invalid-feedback" role="alert"></strong>
                                        </div>
                                        <div class="form-group row">
                                            <div class="form-group col-sm-6">
                                                <label>{{ __('student.step3.cvc') }}</label>
                                                <input type="number" class="form-control" name="cvc" id="cvc" value="">
                                                <strong class="invalid-feedback" role="alert"></strong>
                                            </div>
                                            <div class="form-group col-sm-6">
                                                <label>{{ __('student.step3.expiration_date') }}</label>
                                                <input type="text" class="form-control datepicker" name="date_expiration" id="date_expiration" value="">
                                                <strong class="invalid-feedback" role="alert"></strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ __('button.close') }}</button>
                    <button type="button" class="btn btn-primary btn-flat" id="btnSubmitPayment">{{ __('button.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    <!-- ./modal add coin -->
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/students/addCoin/index.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('#history_use_coin').DataTable({
                drawCallback: function() {
                    let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                    let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                    pagination.toggle(this.api().page.info().pages > 0);
                    info.toggle(this.api().page.info().pages > 0);
                },
                'lengthChange': false,
                'searching'   : false,
                "order": [[ 1, "desc" ]],
                'autoWidth'   : false,
                "pagingType": "full_numbers",
                "pageLength": "{{ config('constants.pagination') }}",
                language: {
                    "url": "{{ __('datatables.language') }}"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('student.add-coin.history') }}",
                    type: 'GET',
                    data: function (d) {
                        d.teacher_id = $('#teacher_id').val();
                    },
                },
                columns: [
                    { data: 'id', name: 'id', "visible": false},
                    { data: 'created_at', name: 'created_at'},
                    { data: 'status', name: 'status' },
                    { data: 'coin', name: 'coin' },
                ],
            });
        });
    </script>
@endpush

