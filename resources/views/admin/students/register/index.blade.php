@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/students/step.css') }}"/>
    <meta name="route-register-student-validation" content="{{ route('student.register.step1.validation') }}">
    <meta name="input-error-common" content="{{ __('validation_custom.input_error_common') }}">
    <meta name="input-error-check-required" content="{{ __('validation_custom.check_required') }}">
    <meta name="route-send-mail-to-update-auth" content="{{ route('student.register.step1.send-mail') }}">
    <meta name="route-register-student-save" content="{{ route('student.register.step2.save') }}">
    <meta name="image-size" content="{{ __('student.image_size') }}">
    <meta name="image-format" content="{{ __('student.image_format') }}">
    <meta name="input-error-radio-required" content="{{ __('validation_custom.check_radio_required') }}">
    <meta name="route-register-student-payment" content="{{ route('student.register.step2.handle-payment') }}">
    <meta name="route-validation-student-payment" content="{{ route('student.register.step2.validation-payment') }}">
    <meta name="route-show-date-deadline" content="{{ route('student.register.show-date-deadline') }}">
    <meta name="m046" content="{{ __('validation_custom.M046') }}">
    <meta name="m047" content="{{ __('validation_custom.M047') }}">
@endsection

@section('content')
    <br>
    <!-- process menu -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form id="msform">
                        <ul id="progressbar">
                            <li id="account" class="active"><strong>{{__('student.1_account')}}</strong></li>
                            <li id="personal" ><strong>{{__('student.2_7_day_free')}}</strong></li>
                            <li id="payment"><strong>{{__('student.3_payment')}}</strong></li>
                            <li id="confirm"><strong>{{__('student.4_confirm')}}</strong></li>
                            <li id="finish"><strong>{{__('student.5_finish')}}</strong></li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /.process menu -->

    <!-- body -->
    <div class="container-fluid">
        <!-- STEP 1 -->
        @include('admin.students.register.step1')

        <!-- STEP 2 -->
        @include('admin.students.register.step2')

        <!-- STEP 3 -->
        @include('admin.students.register.step3')

        <!-- STEP 4 -->
        @include('admin.students.register.step4')
    </div>
    <!-- /.body -->
@endsection

@push('scripts')
    <script src="{{ asset('js/admin/students/register/index.js') }}"></script>
@endpush
