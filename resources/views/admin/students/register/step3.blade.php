<div class="card card-outline card-warning d-none" id="step3">
    <div class="card-header border-bottom-0">
        <div id="card-error"></div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 pt-0">
                <div class="row">
                    <div class="col-sm-6 invoice-col">
                        {{ __('student.step3.from') }}
                        <address>
                            <span>{{ __('student.step3.email') }}: <span id="step3_email_from"></span></span><br>
                            {{ __('student.step3.phone') }}: <span id="step3_area_code"></span> <span id="step3_phone"></span><br>
                        </address>
                    </div>
                    <div class="col-sm-6 invoice-col">
                        {{ __('student.step3.to') }}
                        <address>
                            <span>{{ __('student.step3.email') }}: {{ config('constants.contact_email') }}</span><br>
                            {{ __('student.step3.phone') }}: {{ config('constants.contact_phone') }}<br>
                        </address>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>{{ __('student.step3.name_plan') }}</th>
                                <th>{{ __('student.step3.price_plan') }}</th>
                                <th>{{ __('student.step3.time') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><span id="plan_name"></span></td>
                                <td id="plan_cost"></td>
                                <td id="plan_interval"></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <img src="{{ asset('images/credit/visa.png') }}" alt="Visa">
                <img src="{{ asset('images/credit/mastercard.png') }}" alt="Mastercard">
                <img src="{{ asset('images/credit/american-express.png') }}" alt="American Express">
                <span>{{ __('student.step3.payment_with_stripe') }}</span> <a href="https://stripe.com" target="_blank">{{ __('student.step3.what_stripe') }}</a>
                <hr>
                <p><img src="{{ asset('images/icon-tick.png') }}" alt="" width="20px" height="20px">{{ __('student.step3.fast_payment') }}</p>
                <p><img src="{{ asset('images/icon-tick.png') }}" alt="" width="20px" height="20px">{{ __('student.step3.absolute_safety') }}</p>
                <p><img src="{{ asset('images/icon-tick.png') }}" alt="" width="20px" height="20px">{{ __('student.step3.secure_user') }}</p>
            </div>
            <div class="col-sm-6 pt-0">
                <div class="card card-warning" id="formCredit">
                    <form action="" method="post" id="formSubmitCreditOrDebit" enctype="multipart/form-data">
                        @csrf
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
                        <div class="card-footer">
                            <buton type="button" class="btn btn-warning btn-sm float-right btn-flat" id="btnSubmitCredit">{{ __('button.submit') }}</buton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <buton type="button" class="btn btn-default btn-flat float-left" id="btnBackStep2">{{ __('button.back') }}</buton>
        <buton type="button" class="btn btn-default btn-flat float-right" id="btnRegisterStep3">{{ __('button.skip') }}</buton>
    </div>
</div>
