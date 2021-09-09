@extends('layouts.student.app')
@section('admin_title')

@endsection
@section('stylesheets')
    <meta name="input-error-radio-required" content="{{ __('validation_custom.check_radio_required') }}">
    <meta name="route-student-payment-premium" content="{{ route('student.payment.premium.save') }}">
    <meta name="route-student-payment-validation" content="{{ route('student.payment.validation') }}">
    <meta name="m046" content="{{ __('validation_custom.M046') }}">
    <meta name="m047" content="{{ __('validation_custom.M047') }}">
    <meta name="check_radio_required" content="{{ __('validation_custom.check_radio_required') }}">
@endsection

@section('content')
    {{-- Step 1--}}
    @include('admin.students.getPremium.step1')

    {{-- Step 2--}}
    @include('admin.students.getPremium.step2')

    {{-- Step 3 --}}
    @include('admin.students.getPremium.step3')

@endsection
@push('scripts')
    <script src="{{ asset('js/admin/students/getPremium/index.js') }}"></script>
@endpush
