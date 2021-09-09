@extends('layouts.admin.app')
@section('stylesheets')
    <meta name="route-update-profile" content="{{ route('teacher.update-profile') }}">
    <meta name="route-change-password" content="{{ route('teacher.change-password') }}">
    <meta name="route-change-email" content="{{ route('teacher.change-email') }}">
    <meta name="url-avatar-image-default" content="{{ asset('images/avatar_2.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="url-avatar-image" content="{{ $profile->image_photo }}" />
    <meta name="route-teacher-login" content="{{ route('login.teacher') }}" />
    <meta name="url-link-youtube" content="{{ $profile->link_youtube }}" />
    <meta name="route-validate-link-youtube" content="{{ route('teacher.validate-link-youtube') }}" />
    <meta name="message-required" content="{{ __('validation_custom.M001',['attribute'=>'YouTubeリンク']) }}" />
    <meta name="message-youtube-link-invalid" content="{{ __('validation_custom.M034') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/teachers/edit.css') }}">
@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('edit_profile') }}
@endsection
@section('title_screen', 'プロフィール設定')
@section('content')
    <section class="content">
        <div class="container-fluid d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        <form id="update-profile" enctype="multipart/form-data">
                            @csrf
                            <div class="card p-3">
                                <div class="row mb-1">
                                    <div class="col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1">紹介ビデオ</p>
                                    </div>
                                    <div class="col-sm-6 py-1 px-0">
                                        <div class="introduce" id="showCancel">
                                            <div class="" id="remove-video">
                                                <iframe class="w-100 px-0 introduce-video" width="560" height="315" id="link_youtube"
                                                    src="{{ $profile->link_youtube }}" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                            <div class="cancel" id="btnCancel">
                                                <span class="text-center text-danger font-weight-bold" id="btnRemoveVideo"
                                                      style="font-size: 30px;"><i
                                                        class="far fa-times-circle rounded-circle" style="background-color: white"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 d-flex align-items-end w-100">

                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div
                                        class="col-9 offset-3 d-flex justify-content-end col-sm-3 offset-sm-6 d-sm-flex justify-content-sm-end">
                                        <button type="button" class="btn btn-primary w-50 mb-2 px-0" href="http://" data-toggle="modal" data-target="#modalChangeLinkYoutube">ビデオを変更する</button>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-center">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mb-0">メールアドレス</p>
                                        <span class="float-right" style="color: red"></span>
                                    </div>
                                    <div class="col-9 d-flex justify-content-between align-items-center col-sm-6 d-sm-flex justify-content-sm-between align-items-sm-center">
                                        <div class="col-6 col-sm-6 d-sm-flex flex-sm-column align-items-sm-start">
                                            <p class="mb-0" id="email">{{ $profile->email }}</p>
                                            <span class="text-danger" id="warning-email"></span>
                                        </div>
                                        <div
                                            class="col-6 d-flex flex-column justify-content-center col-sm-6 d-sm-flex flex-sm-column align-items-sm-end ">
                                            <a href="javascript:;" style="text-decoration: underline!important;" data-toggle="modal" data-target="#updateEmail">メールアドレスを変更する</a>
                                            <a href="javascript:;" style="text-decoration: underline!important;" data-toggle="modal" data-target="#modalChangePassword">パスワードを変更する</a>
                                        </div>
                                    </div>
                                    <div class="col-sm-5 d-sm-flex flex-sm-column align-items-sm-end">

                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-2 mb-0">ニックネーム</p>
                                        <span class="float-right" style="color: red">*</span>
                                    </div>
                                    <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <input type="text" name="nickname" id="nickname" class="w-100 form-control"
                                               value="{{ $profile->nickname }}" >
                                        <span class="text-danger" id="error-nickname"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start mt-1">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">生年月日</p>
                                    </div>
                                    <div class="col-9 col-sm-6 ">
                                        <div class="d-flex justify-content-start align-items-center ">
                                            <select class="w-8 p-1 rounded" id="year" name="year" onchange="change_year(this)">
                                                <option value=""></option>
                                                @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                                    <option value="{{$i}}"
                                                            @if($profile->birthday != null && $i == date('Y',strtotime($profile->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span class="pr-1">年</span>
                                            <select class="w-8 p-1 rounded" id="month" name="month" onchange="change_month(this)">
                                                <option value=""></option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{$i}}"
                                                            @if($profile->birthday != null && $i == date('m',strtotime($profile->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span class="pr-1">月</span>
                                            <select class="w-8 p-1 rounded" id="day" name="day">
                                                <option value=""></option>
                                                @for($i = 1; $i <= 31; $i++)
                                                    <option value="{{$i}}"
                                                            @if($profile->birthday != null && $i == date('d',strtotime($profile->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span>日</span>
                                        </div>
                                        <span class="text-danger" id="error-birthday"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-2 mb-0">性別</p>
                                    </div>
                                    <div class="col-9 col-sm-6 ">
                                        <div class="d-flex justify-content-start align-items-center">
                                            <div class="form-check mr-3">
                                                <input class="form-check-input" type="radio" name="sex" id="exampleRadios1"
                                                    value="1"
                                                    @if($profile->sex == 1)
                                                    checked
                                                    @endif
                                                >
                                                <label class="form-check-label" for="exampleRadios1">
                                                    男性
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
                                                    女性
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
                                                    指定なし
                                                </label>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="error-sex"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">国籍</p>
                                    </div>
                                    <div class=" col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
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
                                        <span class="text-danger" id="error-nationality"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">電話番号</p>
                                    </div>
                                    <div class="col-9 col-sm-6 d-flex flex-column justify-content-start align-items-start ">
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
                                               class="form-control @error('phone_number') is-invalid @enderror"
                                               value="{{$profile->phone_number}}" name="phone_number" id="phone_number" style="width: 70% !important">
                                        </div>
                                        <span class="text-danger" id="error-phone_number"></span>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-3 col-sm-3">
                                        <p class="ml-3 mt-1 mb-0">スタッフからの紹介</p>
                                    </div>
                                    <div class="col-9 col-sm-6 ">
                                        <div class="form-group w-100  mb-0">
                                            <p class="mb-0"></p>
                                            <textarea class="form-control " id="introduction_from_admin" name="introduction_from_admin" rows="3" disabled style="overflow: hidden" >{{$profile->introduction_from_admin}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-3 col-sm-3">
                                        <p class="ml-3 mt-1 mb-0">自己紹介</p>
                                    </div>
                                    <div class="col-9 col-sm-6">
                                        <div class="form-group w-100  mb-0">
                                            <textarea class="form-control" name="self-introduction" id="self-introduction" onkeyup="countChar(this,'Introduction','500'); setHeight(this)" maxlength="500"
                                                      id="exampleFormControlTextarea1"
                                                      rows="3"
                                            style="overflow: hidden;font-size: 14.4px!important;">{{ $profile->introduction }}</textarea>
                                        </div>
                                        <span class="text-danger" id="error_self-introduction"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="float-right m-l-3 mr-4" id="charNumForIntroduction"></div>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">講師歴</p>
                                    </div>
                                    <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <textarea class="form-control" style="font-size: 14.4px!important;" onkeyup="countChar(this,'Experience','100')" maxlength="100"  name="experience" id="experience"
                                                      rows="3">{{ $profile->experience }}</textarea>
                                        <span class="text-danger" id="error-experience"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="float-right m-l-3 mr-4" id="charNumForExperience"></div>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">資格</p>
                                    </div>
                                    <div class=" col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <textarea class="form-control" style="font-size: 14.4px!important;" onkeyup="countChar(this,'Certification','100')" maxlength="100" name="certification" id="certification"
                                                      rows="3">{{ $profile->certification }}</textarea>
                                        <span class="text-danger" id="error-certification"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="float-right m-l-3 mr-4" id="charNumForCertification"></div>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-center">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">対応可能コース</p>
                                    </div>
                                    <div class=" col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <select class="select2"  id="course" multiple="multiple" data-placeholder="" style="width: 100%;">
                                            @foreach($course as  $value)
                                            <option name="course[]" value="{{$value->id}}"
                                            @if(!is_null($value->user_id))
                                                selected="selected"
                                            @endif
                                            >{{$value->name}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="error-course"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-2 mb-0">ズームリンク</p>
                                        <span class="float-right" style="color: red">*</span>
                                    </div>
                                    <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <input type="text" name="link_zoom" id="link_zoom" class="w-100 form-control"
                                               value="{{ $profile->link_zoom }}" >
                                        <span class="text-danger" id="error-link_zoom"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-center">
                                    <div class="col-sm-12 d-flex justify-content-center">
                                        <a type="button" class="btn btn-secondary mr-2" href="{{ route('teacher.edit-profile') }}">クリア</a>
                                        <a href="javascript:;" type="button"  class="btn btn-primary" id="btnUpdateProfile" >更新</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="updateEmail" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">メールアドレス変更</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formChangeEmail">
                            @csrf
                            <div class="row mb-2 d-flex align-items-start">
                                <div class="col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">現在のメールアドレス</p>
                                </div>
                                <div class="col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="text" name="old_email" id="old_email" class="w-100 form-control"
                                           value="{{ $profile->email }}" disabled>
                                    <span class="text-danger" id="error_old_email"></span>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-start">
                                <div class="col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">新しいメールアドレス</p>
                                    <span class="float-right" style="color: #ff0000">*</span>
                                </div>
                                <div class="col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="text" name="new_email" id="new_email" class="w-100 form-control">
                                    <span class="text-danger" id="error_new_email"></span>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-start">
                                <div class="col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">新しいメールアドレス（確認）</p>
                                    <span class="float-right" style="color: red">*</span>
                                </div>
                                <div class="col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="text" name="new_email_confirmation" id="new_email_confirmation" class="w-100 form-control">
                                    <span class="text-danger" id="error_new_email_confirmation"></span>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-12 col-sm-8 offset-sm-4 d-flex justify-content-center d-sm-flex justify-content-sm-start">
                                <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">キャンセル</button>
                                <button type="button" class="btn btn-primary" id="btnSendMailConfirm">確認メールを送信する
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <span style="font-family: Arial, sans-serif !important; color:black;">メールに記載されている認証用URLにアクセスするとメールアドレスの変更手続きが完了します。</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="sentMailConfirm" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-sm-12 d-sm-flex flex-sm-column justify-content-center align-items-center ">
                                <h3>新しいメールアドレスに確認を送信しました。</h3>
                                <p>（認証URLの有効期限は24時間です。）</p>
                                <button type="button" class="btn btn-primary mr-3" data-dismiss="modal">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="showDoneMessage" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-sm-12 d-sm-flex flex-sm-column justify-content-center align-items-center ">
                                <h3>メールアドレス変更手続きが完了しました。</h3>
                                <p>ログインしてメールアドレスが変更されている事を確認してください。</p>
                                <a href="http://" data-toggle="modal">ログイン</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalChangePasswordSuccessfully" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-sm-12 d-sm-flex flex-sm-column justify-content-center align-items-center ">
                                <h4>パスワード変更が完了しました。</h4>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-12 d-sm-flex justify-content-sm-center">
                        <button type="button" class="btn btn-primary mr-3" data-dismiss="modal">OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalChangePassword" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">パスワード変更</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formChangePassword">
                            @csrf
                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">現在のパスワード</p>
                                    <span class="float-right" style="color: red">*</span>
                                </div>
                                <div class="col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="password" name="old_password" id="old_password"
                                           class="w-100 form-control">
                                    <span class="text-danger" id="error_old_password"></span>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">新しいパスワード</p>
                                    <span class="float-right" style="color: red">*</span>
                                </div>
                                <div class="col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="password" name="new_password" id="new_password"
                                           class="w-100 form-control">
                                    <span class="text-danger" id="error_new_password"></span>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">新しいパスワード（確認）</p>
                                    <span class="float-right" style="color: red">*</span>
                                </div>
                                <div class="col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                                           class="w-100 form-control">
                                    <span class="text-danger" id="error_new_password_confirmation"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">キャンセル</button>
                            <button type="button" class="btn btn-primary" id="btnChangePassword">更新</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalChangeLinkYoutube" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">ビデオ変更</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formChangeLinkYoutube">
                            @csrf
                            <div class="row mb-2 d-flex align-items-start">
                                <div class="col-sm-11 offset-sm-1 d-flex justify-content-start mb-3">
                                    <p>新しいYouTubeリンクを下記に貼り付けてください。</p>
                                </div>
                                <div class="col-sm-3 offset-sm-1 d-flex justify-content-between">
                                    <p class=" mt-2 mb-0">YouTubeリンク</p>
                                    <span class="float-top" style="color: red">*</span>
                                </div>
                                <div class="col-sm-7 d-flex flex-column justify-content-center align-items-start">
                                    <input type="text" name="input_link_youtube" id="input_link_youtube"
                                           class="w-100 form-control">
                                    <span class="text-danger" id="error-link_youtube"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">キャンセル</button>
                            <button type="button" class="btn btn-primary" id="btnChangeLinkYoutube">更新</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/teachers/edit.js') }}"></script>
    <script>
        function countChar(val,title,maxlength) {
            var len = val.value.length;
            if (len >= 501) {
                val.value = val.value.substring(0, 500);
            } else {
                $('#charNumFor'+title).text(`${len}/${maxlength}`);
            }
        };
        // var textarea = document.getElementById("self-introduction");
        var limit = 500; //height limit

        setHeight = function(textarea) {
            textarea.style.height = "";
            textarea.style.height = Math.min(textarea.scrollHeight, limit) + "px";
        };
        $(document).ready(function() {
            let introduction_from_admin = document.getElementById("introduction_from_admin");
            introduction_from_admin.style.height = "";
            introduction_from_admin.style.height = Math.min(introduction_from_admin.scrollHeight, limit) + "px";
            let self_introduction = document.getElementById("self-introduction");
            self_introduction.style.height = "";
            self_introduction.style.height = Math.min(self_introduction.scrollHeight, limit) + "px";
        });
    </script>
@endpush
