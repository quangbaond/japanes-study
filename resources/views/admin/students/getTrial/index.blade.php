@extends('layouts.student.app')
@section('admin_title')

@endsection
@section('stylesheets')
    <meta name="input-error-radio-required" content="{{ __('validation_custom.check_radio_required') }}">
    <meta name="route-student-payment-trial" content="{{ route('student.payment.7-days-free-trial.save') }}">
    <meta name="route-student-payment-validation" content="{{ route('student.payment.validation') }}">
    <meta name="m046" content="{{ __('validation_custom.M046') }}">
    <meta name="m047" content="{{ __('validation_custom.M047') }}">
    <meta name="route-show-date-deadline" content="{{ route('student.register.show-date-deadline') }}">
@endsection

@section('content')
    {{-- Step 1--}}
    @include('admin.students.getTrial.step1')

    {{-- Step 2--}}
    @include('admin.students.getTrial.step2')

    {{-- Step 3 --}}
    @include('admin.students.getTrial.step3')

@endsection
@push('scripts')
    <script src="{{ asset('js/admin/students/getTrial/index.js') }}"></script>
@endpush
