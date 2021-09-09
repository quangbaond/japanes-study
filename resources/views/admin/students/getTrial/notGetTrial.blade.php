@extends('layouts.student.app')
@section('admin_title')
@endsection

@section('content')
    <div class="container px-0">
        <div class="card card-outline card-warning">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <!-- Main content -->
                        <div class="invoice p-3 mb-3">
                            <!-- info row -->
                            <div class="card-body box-profile">
                                <h4 class="text-center">{{ __('student.notGetTrial.header') }}</h4>
                                <br>
                                <div class="text-center">
                                    <img class="img-circle" src="{{ asset('images/remove.png') }}" alt="User profile picture" style="width: 20%">
                                </div>
                                <br>
                                <h3 class="profile-username text-center">{{ __('student.getTrial.step3.back') }} <a href="{{route('student-dashboard')}}">{{ __('student.getTrial.step3.home') }}</a></h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
