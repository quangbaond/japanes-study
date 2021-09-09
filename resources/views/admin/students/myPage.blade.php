@extends('layouts.student.app')
@section('admin_title')
{{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/teachers/mypage.css') }}" xmlns="http://www.w3.org/1999/html">
    <meta content="{{ route('student.change-nickname') }}" name="route-change-nickname">
    <meta content="{{ route('student.change-password') }}" name="route-change-password">
    <meta content="{{ route('student.update-profile') }}" name="route-update-profile">
    <meta content="{{ route('student.change-email') }}" name="route-change-email">
    <meta content="{{ __('validation_custom.M049')  }}" name="message-M049">
    <meta content="{{ __('validation_custom.M024')  }}" name="message-M024">
    <meta content="{{ __('validation_custom.M019',['sizeMB'=>'5MB'])  }}" name="message-M019">
    <meta name="url-avatar-image-default" content="{{ asset('images/avatar_2.png') }}">
    <meta name="url-avatar-image" content="{{ $profile->image_photo }}" />
    <meta name="check-cancel-trial-payment" content="{{ route('student.check-cancel-trial-plan') }}">
    <meta name="check-cancel-premium-plan" content="{{ route('student.check-cancel-premium-plan') }}">
    <meta name="M050" content="{{ __('validation_custom.M050') }}">
    <meta name="M051" content="{{ __('validation_custom.M051') }}">
    <meta name="M052" content="{{ __('validation_custom.M052') }}">
    <meta name="change-nickname-success" content="{{ __('students_page.update_nickname_success') }}">
    <meta name="change-password-success" content="{{ __('students_page.update_password_success') }}">
@endsection

@section('content')
    <style>
        .box{
            border-radius: 50%;
            height: 120px;
            width:120px;
            @if(!is_null($profile->image_photo))
                background-image: url("{{ $profile->image_photo }}");
            @else
                background-image: url("{{ asset('images/avatar_2.png') }}");
            @endif
            background-position: center;
            background-size: cover;
            position: relative;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .upload{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            color: #FFF;
            position: absolute;
            height: 60px;
            background: linear-gradient(0deg, rgba(0,212,255,1) 0%, rgba(14,13,13,1) 0%, rgba(0,0,0,0) 100%);
            width: inherit;
            top: 56px;
            left: -2px;
            border-radius: 0 0 60px 60px;
            opacity: 0;
        }
        .upload > label {
            font-size: 50%;
        }

        #upload-photo {
            opacity: 0;
            position: absolute;
            z-index: -1;
            width: 120px;
        }
        .remove-image {
            position: absolute;
            top: -6px;
            right: 5px;
            /* left: 3px; */
            font-size: 25px;
            opacity: 0;
            font-weight: bold;
        }

        .upload:hover{
            opacity: 0.5;
        }
        #box:hover #remove-image {
            opacity: 1;
        }
        #remove-image:hover{
            cursor: pointer;
        }
    </style>
<div class="container">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="text-bold card-title">{{ __('students_page.user_information') }}</h3>
            </div>
            <form action="">
                <div class="card-body p-3">
                    <!-- avatar and Update info -->

                    <div class="row mr-md-5 ml-md-3">
                        <div class="col-12 col-md-2 col-sm-12 d-sm-flex flex-sm-column justify-content-sm-center align-items-sm-center">
                            <div class="div-avatar">
                                <div class="box profile-user-img" id="box">
                                    <div class="upload">
                                        <input type='file' name="photo" id="upload-photo" />
                                        <label for="upload-photo"><i class="fas fa-camera"></i></label>
                                    </div>
                                    <div class="remove-image" id="remove-image">
                                        <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-danger w-100 text-center" id="error_upload_photo"></span>
                        </div>
                        <div class="col ml-2">
                            <div class="row d-flex align-items-start d-sm-flex align-items-sm-start">
                                <div class="col-6 col-sm-6 d-flex flex-column align-items-start d-sm-flex flex-sm-column align-items-sm-start justify-content-sm-center ">
                                    <p class="mb-0">{{__('student.nickname')}}: </p>
                                    <span class="text-bold nickname" style="word-break: break-all">{{ $profile->nickname }}</span>
                                </div>
                                <div class="col-6 col-sm-6 d-flex d-sm-flex flex-sm-column justify-content-end align-items-end justify-content-sm-center">
                                    <a href="javascript:;" data-toggle="modal" data-target="#modalUpdateNickname"
                                        class="custom-a-tag">{{__('students_page.change_nickname')}}</a>
                                </div>
                            </div>
                            <hr>

                            <div class="row d-flex align-items-start d-sm-flex align-items-sm-start">
                                <div class="col-6 col-sm-6 d-flex flex-column align-items-start d-sm-flex flex-sm-column align-items-sm-start justify-content-sm-center ">
                                    <p class="mb-0">{{__('student.email')}}:</p>
                                    <span class="" id="email" style="word-break: break-all"><b>{{ $profile->email }}</b></span>
                                </div>
                                <div class="col-6 col-sm-6 d-flex flex-column align-items-end d-sm-flex flex-sm-column align-items-sm-end justify-content-sm-center ">
                                    <a href="javascript:;" data-toggle="modal" data-target="#modalUpdateEmail"
                                        class="custom-a-tag">{{__('student.update_email')}}</a>
                                    <a href="javascript:;" data-toggle="modal" data-target="#modalChangePassword"
                                        class="custom-a-tag">{{__('student.update_password')}}</a>
                                </div>
                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="row mx-md-5 my-3">

                        <div class="col-12 my-3">
                            <div class="row">
                                <div class="col-5 col-sm-3">
                                    <p>{{__('students_page.user_id')}}</p>
                                </div>
                                <div class="col-1 col-sm-2">
                                    <p>:</p>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <p>{{ $profile->id }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <div class="row">
                                <div class="col-5 col-sm-3">
                                    <p>{{__('student.membership_status')}}</p>
                                </div>
                                <div class="col-1 col-sm-2">
                                    <p>:</p>
                                </div>
                                <div class="col-3 col-sm-4">
                                    <p>
                                        @if($profile->membership_status == config('constants.membership.id.free'))
                                            {{ __('validation_custom.role.free') }}
                                        @elseif($profile->membership_status == config('constants.membership.id.premium_trial'))
                                            {{ __('validation_custom.role.trial') }}
                                        @elseif($profile->membership_status == config('constants.membership.id.premium'))
                                            {{ __('validation_custom.role.premium') }}
                                        @elseif($profile->membership_status == config('constants.membership.id.Special'))
                                            {{ __('validation_custom.role.special') }}
                                        @elseif($profile->membership_status == config('constants.membership.id.other_company'))
                                            {{ __('validation_custom.role.other_company') }}
                                        @elseif($profile->membership_status == config('constants.membership.id.cancelling_premium'))
                                            {{ __('validation_custom.role.canceling_premium') }}
                                        @endif
                                    </p>
                                </div>

                                <div class="col-3 col-sm-3 d-sm-flex justify-content-sm-end">
                                    <p>
                                        @if($profile->membership_status == config('constants.membership.id.free') && $profile->user_payment_info_id == NULL)
                                            <a href="{{route('student.payment.7-days-free-trial')}}" class="custom-a-tag">{{__('students_page.membership_role.get_7_days_free_trial')}}</a>
                                        @elseif( $profile->membership_status == config('constants.membership.id.free') && $profile->user_payment_info_id != NULL)
                                            <a href="{{ route('student.payment.premium')}}" class="custom-a-tag">{{__('students_page.membership_role.get_premium')}}</a>
                                        @elseif($profile->membership_status == config('constants.membership.id.premium_trial'))
                                            <a href="javascript:;" id="buttonOpenModalTrial" class="custom-a-tag">{{__('students_page.membership_role.cancel_7_days_free_trial')}}</a>
                                        @elseif($profile->membership_status == config('constants.membership.id.premium'))
                                            <a href="javascript:;" id="buttonOpenModalPremium" class="custom-a-tag">{{__('students_page.membership_role.cancel_premium')}}</a>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <div class="row">
                                <div class="col-5 col-sm-3">
                                    <p>{{__('students_page.coins')}}</p>
                                </div>
                                <div class="col-1 col-sm-2">
                                    <p>:</p>
                                </div>
                                <div class="col-3 col-sm-4">
                                    <p>
                                        {{ $profile->total_coin ?? '0' }}
                                    </p>
                                </div>
                                <div class="col-3 col-sm-3 d-sm-flex justify-content-sm-end">
                                    @if(  !in_array($profile->membership_status,[config('constants.membership.id.free'), config('constants.membership.id.Special'), config('constants.membership.id.other_company')]) )
                                        <p>
                                            <a href="{{route('student.add-coin')}}" class="custom-a-tag">
                                                {{__('student.add_coin')}}
                                            </a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-3">
                            <div class="row">
                                <div class="col-5 col-sm-3">
                                    <p>{{__('student.renewal_date')}}</p>
                                </div>
                                <div class="col-1 col-sm-2">
                                    <p>:</p>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <p>
                                        @if($profile->membership_status == config('constants.membership.id.premium_trial') && $profile->trial_end_date != null )
                                            {{ $profile->trial_end_date }}
                                        @elseif(($profile->membership_status == config('constants.membership.id.premium') || $profile->membership_status == config('constants.membership.id.cancelling_premium')) && $profile->premium_end_date != null)
                                            {{ $profile->premium_end_date }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end avatar and Update info -->
                    <div class="card-header">
                        <h3 class="card-title text-bold">{{ __('students_page.profile_details') }}</h3>
                    </div>

                    <!-- profile details -->
                <form id="update-profile" enctype="multipart/form-data">
                    @csrf
                    <div class="row mx-md-5 my-3">
                        <div class="col-12 my-2">
                            <div class="row">
                                <div class="col-4 col-sm-3">
                                    {{__('student.birthday')}}
                                </div>
                                <div class="col-2 col-sm-2 d-none d-sm-block">
                                    <p>:</p>
                                </div>
                                <div class="col-12 col-sm-5">
                                    <div class="form-group row mb-0">
                                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-row flex-sm-row mr-3 mr-sm-0">
                                            <select class="w-10 b-r mr-3 mr-sm-1" name="year" id="year">
                                                <option value=""></option>
                                                @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                                    <option value="{{$i}}"
                                                            @if($profile->birthday != null && $i == date('Y',strtotime($profile->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor

                                            </select>
                                            <span class="pr-1">{{__('student.year')}}</span>
                                        </div>
                                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-row flex-sm-row">
                                            <select class="w-8 b-r mr-1 mr-sm-1" name="month" id="month">
                                                <option value=""></option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{$i}}"
                                                            @if($profile->birthday != null && $i == date('m',strtotime($profile->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span class="pr-1">{{__('student.month')}}</span>
                                        </div>
                                        <div class="col-3 col-sm-3 d-flex d-sm-flex flex-row flex-sm-row">
                                            <select class="w-8 b-r mr-1 mr-sm-1" name="day" id="day">
                                                <option value=""></option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{$i}}"
                                                            @if($profile->birthday != null && $i == date('d',strtotime($profile->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span>{{__('student.day')}}</span>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="error_birthday"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-2">
                            <div class="row">
                                <div class="col-4 col-md-3 col-sm-3">
                                    <p>{{__('student.gender')}}</p>
                                </div>
                                <div class="col-2 col-sm-2 d-none d-sm-block">
                                    <p>:</p>
                                </div>
                                <div class="col-12 col-sm-5">
{{--                                    <p>--}}
{{--                                        <input type="radio" id="male" name="gender"--}}
{{--                                           @if($profile->sex == 1)--}}
{{--                                                   checked--}}
{{--                                            @endif--}}
{{--                                        >--}}
{{--                                        <label for="male" class="px-1 px-md-2">{{__('student.gender_option.male')}}</label>--}}
{{--                                        <input type="radio" id="female" name="gender"--}}
{{--                                           @if($profile->sex == 2)--}}
{{--                                            checked--}}
{{--                                            @endif>--}}
{{--                                        <label for="female" class="px-1 px-md-2">{{__('student.gender_option.female')}}</label>--}}
{{--                                        <input type="radio" id="unspecified" name="gender"--}}
{{--                                           @if($profile->sex == 3)--}}
{{--                                                checked--}}
{{--                                            @endif>--}}
{{--                                        <label for="unspecified" class="px-1 px-md-2">Unspecified</label>--}}
{{--                                    </p>--}}
{{--                                    <span class="text-danger" id="error_birthday">zxczxc</span>--}}
                                    <div class="d-flex justify-content-start align-items-center">
                                        <div class="form-check mr-3">
                                            <input class="form-check-input" type="radio" name="sex" id="exampleRadios1"
                                                   value="1"
                                                   @if($profile->sex == 1)
                                                   checked
                                                @endif
                                            >
                                            <label class="form-check-label" for="exampleRadios1">
                                                {{__('student.gender_option.male')}}
                                            </label>
                                        </div>
                                        <div class="form-check mr-3">
                                            <input class="form-check-input" type="radio" name="sex" id="exampleRadios2"
                                                   value="2"
                                                   @if($profile->sex == 2)
                                                   checked
                                                @endif
                                            >
                                            <label class="form-check-label" for="exampleRadios2">
                                                {{__('student.gender_option.female')}}
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="sex" id="exampleRadios3"
                                                   value="3"
                                                   @if($profile->sex == 3)
                                                   checked
                                                @endif
                                            >
                                            <label class="form-check-label" for="exampleRadios3">
                                                {{__('student.gender_option.unspecified')}}
                                            </label>
                                        </div>
                                    </div>
                                    <span class="text-danger" id="error_sex"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-2">
                            <div class="row">
                                <div class="col-12 col-md-3 col-sm-3">
                                    <p>{{__('student.phone_number')}}</p>
                                </div>
                                <div class="col-2 col-sm-2 d-none d-sm-block">
                                    <p>:</p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <div class="input-group-prepend w-100" >
                                        <select class="custom-select select2" name="area_code" style="width: 30% !important;">
                                            <option value=""></option>
                                            @foreach($phoneNumber as $key => $value)
                                                <option value="{{ $key . '-' . $value['code'] }}"
                                                    @if($profile->area_code != null && $profile->area_code == ($key . '-' . $value['code']))
                                                        selected
                                                    @endif
                                                >{{$value['name']}} (+{{ $value['code'] }}) </option>
                                            @endforeach
                                        </select>
                                        <input type="text"
                                               class="form-control" value="{{ $profile->phone_number }}" name="phone_number" id="phone_number" style="width: 70% !important">
                                    </div>
                                    <span class="text-danger" id="error_phone_number"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 my-2">
                            <div class="row">
                                <div class="col-12 col-sm-3">
                                    <p>{{ __('student.nationality') }}</p>
                                </div>
                                <div class="col-2 col-sm-2 d-none d-sm-block">
                                    <p>:</p>
                                </div>
                                <div class="col-md-7 col-sm-12">
                                    <select class="form-control select2" name="nationality" style="width: 70%;">
                                        <option value=""></option>
                                        @foreach( $nationality as $key => $value)
                                            <option value="{{$key}}"
                                                @if($profile->nationality == $key)
                                                    selected
                                                @endif
                                            >{{$value}}</option>
                                        @endforeach
                                    </select>
                                    <span class="text-danger" id="error_nationality"></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                    <!-- end profile Detail  -->
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <a href="{{ route('student.profile') }}" class="btn btn-flat btn-default mx-2 " type="reset">{{__('button.clear_form')}}</a>
                    <a type="button" href="javascript:;" class="btn btn-flat btn-primary" id="btnUpdateProfile">{{__('button.save_your_change')}}</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="modalChangePassword" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('student.update_password')}}</h5>
                <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formChangePassword">
                    @csrf
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('student.update.current_password')}}</p>
                            <span class="d-none d-sm-block float-right" style="color: red">*</span>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                            <span class="ml-2" style="color: red">*</span>
                        </div>
                        <div class="col-10 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                            <input type="password" name="old_password" id="old_password"
                                    class="w-100 form-control">
                            <span class="text-danger" id="error_old_password"></span>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('student.update.new_password')}}</p>
                            <span class="d-none d-sm-block float-right" style="color: red">*</span>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                            <span class="ml-2" style="color: red">*</span>
                        </div>
                        <div class="col-10 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                            <input type="password" name="new_password" id="new_password"
                                    class="w-100 form-control">
                            <span class="text-danger" id="error_new_password"></span>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('student.update.confirm_new_password')}}</p>
                            <span class="d-none d-sm-block float-right" style="color: #ff0000">*</span>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                            <span class="ml-2" style="color: red">*</span>
                        </div>
                        <div class="col-10 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                    class="w-100 form-control">
                            <span class="text-danger" id="error_new_password_confirmation"></span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-12 d-flex justify-content-center col-sm-8 offset-sm-4 d-sm-flex justify-content-sm-start">
                    <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('button.cancel')}}</button>
                    <button type="button" class="btn btn-primary" id="btnChangePassword">{{__('students_page.popup_change_password.update')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalUpdateEmail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-width: 850px!important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('student.update_email')}}</h5>
                <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formChangeEmail">
                    @csrf
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('student.update.current_email')}}</p>
                        </div>
                        <div class="col-10 offset-1  col-sm-8 offset-sm-0 d-flex flex-column justify-content-center align-items-start ">
                            <input type="text" name="old_email" id="old_email" class="w-100 form-control"
                                    value="{{ $profile->email }}" disabled>
                            <span class="text-danger" id="error_old_email"></span>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('student.update.new_email')}}</p>
                            <span class="d-none d-sm-block float-right" style="color: red">*</span>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                            <span class="ml-2" style="color: red">*</span>
                        </div>
                        <div class="col-10 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                            <input type="text" name="new_email" id="new_email" class="w-100 form-control">
                            <span class="text-danger" id="error_new_email"></span>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('student.update.confirm_new_email')}}</p>
                            <span class="d-none d-sm-block float-right" style="color: red">*</span>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                            <span class="ml-2" style="color: red">*</span>
                        </div>
                        <div class="col-10 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                            <input type="text" name="new_email_confirmation" id="new_email_confirmation"
                                    class="w-100 form-control">
                            <span class="text-danger" id="error_new_email_confirmation"></span>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-center col-sm-8 offset-sm-4 d-sm-flex justify-content-sm-start">
                        <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('button.cancel')}}
                        </button>
                        <button type="button" class="btn btn-primary" id="btnSendMailConfirm">{{__('button.send_a_confirm_email')}}</button>
                    </div>
                </div>
            </div>
            <div class="modal-footer d-flex justify-content-start">
                <span style="font-family: Arial, sans-serif !important; color:black;">※ {{__('student.update.label_mail')}}</span>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalUpdateNickname" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
    <div class="modal-dialog" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{__('students_page.popup_change_nickname.change_nickname')}}</h5>
                <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUpdateNickname">
                    @csrf
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('students_page.popup_change_nickname.current_nickname')}}</p>
                        </div>
                        <div class="offset-1 col-10 col-sm-8 offset-sm-0 d-flex flex-column justify-content-center align-items-start ">
                            <input type="text" name="old_nickname" id="old_nickname" class="w-100 form-control"
                                    value="{{ $profile->nickname }}" disabled>
                            <span class="text-danger" id="error_old_nickname"></span>
                        </div>
                    </div>
                    <div class="row mb-2 d-flex align-items-start">
                        <div class="col-11 offset-1 col-sm-4 offset-sm-0 d-flex justify-content-between">
                            <p class="ml-0 ml-sm-3 mt-1 mt-sm-1 mb-0">{{__('students_page.popup_change_nickname.new_nickname')}}</p>
                            <span class=" d-none d-sm-block float-right" style="color: red">*</span>
                        </div>
                        <div class="col-1 d-block d-sm-none">
                            <span class="ml-2" style="color: red">*</span>
                        </div>
                        <div class="col-10 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                            <input type="text" name="new_nickname" id="new_nickname" class="w-100 form-control">
                            <span class="text-danger" id="error_new_nickname"></span>
                        </div>
                    </div>
                </form>
                <div class="row mt-4">
                    <div class="col-12 d-flex justify-content-center col-sm-8 offset-md-4 d-sm-flex justify-content-sm-start">
                        <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('students_page.popup_change_nickname.cancel')}}
                        </button>
                        <button type="button" class="btn btn-primary" id="btnChangeNickname">{{__('students_page.popup_change_nickname.change')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(!empty($plan))
    {{-- Cancel plan 7day trial --}}
    <div class="modal fade bd-example-modal-sm" id="modalTrial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('student.cancel-trial-plan') }}" method="post" id="formCancelTrialPayment">
                    @csrf
                    <input type="text" hidden value="{{ $profile->stripe_subscription_id }}" name="stripe_subscription_id">
                    <input type="text" hidden value="{{ $profile->stripe_customer_id }}" name="stripe_customer_id">
                    <input type="text" hidden value="{{ $profile->trial_end_date }}" name="trial_end_date">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('student.cancel_7day.title')}}</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="error-message-cancel"></div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_7day.member_ship')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">{{__('student.cancel_7day.member_ship_name')}}</div>
                        </div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_7day.end_date')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">{{ \App\Helpers\Helper::formatDateHIS($profile->trial_end_date) }}</div>
                        </div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_7day.option_plan')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">{{ $plan->name }}</div>
                        </div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_7day.premium_member_state_date')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">{{ \App\Helpers\Helper::formatDateHIS($profile->trial_end_date) }}</div>
                        </div>
                        <p class="text-center font-weight-bold">{{__('student.cancel_7day.question_confirm')}}?</p>
                    </div>
                    <div class="modal-footer d-flex d-sm-flex flex-column flex-sm-column align-items-start">
                        <div class="col-12 col-sm-12 d-flex d-sm-flex justify-content-center justify-content-sm-center">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('student.cancel_7day.confirm_no')}}</button>
                            <button type="button" class="btn btn-primary" id="cancelTrialPayment">{{__('student.cancel_7day.confirm_yes')}}</button>
                        </div>
                        <p class="mx-2">※ {{__('student.cancel_7day.label')}}</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm" id="modalCancelBookingTrial" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('student.cancelBooking.trial.title')}}</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 70vh; overflow-y: auto;">
                        <p>{{__('student.cancelBooking.trial.content1')}}</p>
                        <p>{{__('student.cancelBooking.trial.content2')}}</p>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="list_booking" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>{{ __('student.cancelBooking.table.date') }}</th>
                                        <th>{{ __('student.cancelBooking.table.time') }}</th>
                                        <th>{{ __('student.cancelBooking.table.teacher_id') }}</th>
                                        <th>{{ __('student.cancelBooking.table.nickname') }}</th>
                                        <th>{{ __('student.cancelBooking.table.email') }}</th>
                                        <th>{{ __('student.cancelBooking.table.coin') }}</th>
                                        <th>{{ __('student.cancelBooking.refund_coin') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <p class="">{{__('student.cancelBooking.refund_coin')}}: <span class="ml-4 text-bold text-info" id="total_coin_refund"></span></p>
                        <p class="text-bold">{{__('student.cancelBooking.trial.confirm')}}</p>
                    </div>
                    <div class="modal-footer text-center justify-content-center">
                        <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('student.cancel_7day.confirm_no')}}</button>
                        <button type="button" class="btn btn-primary" id="btnSubmitTrial">{{__('student.cancel_7day.confirm_yes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- ./Cancel plan 7day trial --}}

    {{--    Cancel Preminum--}}
    <div class="modal fade  bd-example-modal-sm" id="modalPremium" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('student.cancel-premium-plan') }}" method="post" id="formCancelPremiumPayment">
                    @csrf
                    <input type="text" hidden value="{{ $profile->stripe_subscription_id }}" name="stripe_subscription_id">
                    <input type="text" hidden value="{{ $profile->stripe_customer_id }}" name="stripe_customer_id">
                    <input type="text" hidden value="{{ $profile->premium_end_date }}" name="premium_end_date">
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('student.cancel_premium.title')}}</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div id="error-message-cancel-premium"></div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_premium.member_ship')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">Premium</div>
                        </div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_premium.option_plan')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">{{ $plan->name }}</div>
                        </div>
                        <div class="row text-overflow-no-space">
                            <div class="col-5">{{__('student.cancel_premium.next_renewal_date')}}</div>
                            <div class="col-1">:</div>
                            <div class="col-6">{{ \App\Helpers\Helper::formatDate($profile->premium_end_date) }}</div>
                        </div>
                        <p class="text-center font-weight-bold">{{__('student.cancel_premium.question_confirm')}}</p>
                    </div>
                    <div class="modal-footer d-flex d-sm-flex flex-column flex-sm-column align-items-start">
                        <div class="col-12 col-sm-12 d-flex d-sm-flex justify-content-center justify-content-sm-center">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('student.cancel_7day.confirm_no')}}</button>
                            <button type="button" class="btn btn-primary" id="cancelPremiumPayment">{{__('student.cancel_7day.confirm_yes')}}</button>
                        </div>
                        <p class="">
                            ※{{__('student.cancel_premium.label')}}   {{ \Carbon\Carbon::parse($profile->premium_end_date)->format('Y/m/d H:i:s') }}.
                            <br>
                            <span > {{__('student.cancel_premium.label_2')}}</span>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-sm" id="modalCancelBookingPremium" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="" method="post">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{__('student.cancelBooking.premium.title')}}</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style="height: 70vh; overflow-y: auto;">
                        <p>{{__('student.cancelBooking.premium.content1')}}</p>
                        <p>{{__('student.cancelBooking.premium.content2')}}</p>
                        <div class="row">
                            <div class="table-responsive">
                                <table id="list_booking_premium" class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th>{{ __('student.cancelBooking.table.date') }}</th>
                                        <th>{{ __('student.cancelBooking.table.time') }}</th>
                                        <th>{{ __('student.cancelBooking.table.teacher_id') }}</th>
                                        <th>{{ __('student.cancelBooking.table.nickname') }}</th>
                                        <th>{{ __('student.cancelBooking.table.email') }}</th>
                                        <th>{{ __('student.cancelBooking.table.coin') }}</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                        <p class="">{{__('student.cancelBooking.refund_coin')}}: <span class="ml-4 text-bold text-info" id="total_coin_refund_premium"></span></p>
                        <p class="text-bold">{{__('student.cancelBooking.premium.confirm')}}</p>
                    </div>
                    <div class="modal-footer text-center justify-content-center">
                        <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{__('student.cancel_7day.confirm_no')}}</button>
                        <button type="button" class="btn btn-primary" id="btnSubmitPremium">{{__('student.cancel_7day.confirm_yes')}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{--    ./Cancel Preminum--}}
@endif

<div class="modal fade" id="modalUpdateNicknameSuccessfully" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" >
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="text-center">{{ __('validation_custom.M027') }}</h5>
                <div class="row mt-4">
                    <div class="col-sm-12 d-sm-flex justify-content-sm-center ">
                        <button type="button" class="btn btn-primary my-sm-2 my-md-0" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalUpdatePasswordSuccessfully" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-sm" >
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="text-center">{{ __('validation_custom.M027') }}</h5>
                <div class="row mt-4">
                    <div class="col-sm-12 d-sm-flex justify-content-sm-center ">
                        <button type="button" class="btn btn-primary my-sm-2 my-md-0" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalSendEmailSuccessfully" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="text-center">{{ __('student.update.content_send_mail_successfully') }}</h5>
                <p class="text-center">{{ __('student.update.note_send_mail_successfully') }}</p>
                <div class="row mt-4">
                    <div class="col-sm-12 d-sm-flex justify-content-sm-center ">
                        <button type="button" class="btn btn-primary my-sm-2 my-md-0" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@push('scripts')
    <script src="{{ asset('js/admin/students/myPage.js') }}"></script>
    <script src="{{ asset('js/student/profile.js') }}"></script>
@endpush
