@extends('layouts.app')
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/students/step.css') }}"/>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <form id="msform">
                        <ul id="progressbar">
                            <li id="account"><strong>{{__('student.1_account')}}</strong></li>
                            <li id="personal" ><strong>{{__('student.2_7_day_free')}}</strong></li>
                            <li id="payment"><strong>{{__('student.3_payment')}}</strong></li>
                            <li id="confirm"><strong>{{__('student.4_confirm')}}</strong></li>
                            <li id="finish" class="active"><strong>{{__('student.5_finish')}}</strong></li>
                        </ul>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- info row -->
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="img-circle" src="{{ asset('images/success-04.png') }}" alt="User profile picture" style="width: 50%">
                        </div>
                        <h3 class="profile-username text-center">{{__('student.step5.registration_complete')}}</h3>
                        <p class="text-center">{{__('student.step5.click')}} <a href={{ route('login.student') }}>{{__('student.step5.here')}}</a> {{__('student.step5.to_login')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>

</script>
