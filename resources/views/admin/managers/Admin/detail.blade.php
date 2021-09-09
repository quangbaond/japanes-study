@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('admin_detail') }}
@endsection

@section('stylesheets')
    <meta content="{{ __('validation_custom.M049')  }}" name="message-M049">
    <meta content="{{ __('validation_custom.M024')  }}" name="message-M024">
    <meta content="{{ __('validation_custom.M019',['sizeMB'=>'5MB'])  }}" name="message-M019">
    <meta content="{{ route('admin.admin-list.update-profile') }}" name="route-update-profile">
    <meta content="{{ route('admin.admin-list.change-password') }}" name="route-change-password">
    <meta name="route-reset-password" content="{{ route('admin.admin-list.reset-password',['id' => $adminInformation->id]) }}">
    <link rel="stylesheet" href="{{ asset('css/admin/manager/students/create.css') }}"/>
    <meta name="url-avatar-image-default" content="{{ asset('images/avatar_2.png') }}">
    <meta name="url-avatar-image" content="{{ $adminInformation->image_photo }}" />
@endsection

@section('title_screen', 'アドミン詳細')

@section('content')
    <style>
        .div-avatar {
            padding: 3px;
            border: 3px solid #adb5bd;
            border-radius: 50%;
        }
        .box{
            border-radius: 50%;
            height: 100px;
            width:100px;
            background-image: url("{{ $adminInformation->image_photo ?? asset('images/avatar_2.png') }}");
            background-position: center;
            background-size: cover;
            position: relative;
        }
        .upload{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            color: #FFF;
            position: absolute;
            height: 50px;
            background: linear-gradient(0deg, rgba(0,212,255,1) 0%, rgba(14,13,13,1) 0%, rgba(0,0,0,0) 100%);
            width: inherit;
            top:50px;
            border-radius: 0 0 50px 50px;
            opacity: 0;
        }
        .upload > label {
            font-size: 50%;
        }

        #upload-photo {
            opacity: 0;
            position: absolute;
            z-index: 5;
            width: 100px;
            overflow: hidden!important;
        }
        .remove-image {
            position: absolute;
            top: -5px;
            right: -3px;
            /* left: 3px; */
            font-size: 25px;
            opacity: 0;
            font-weight: bold;
        }

        .upload:hover{
            opacity: 0.5;
            cursor: pointer;
        }
        #box:hover #remove-image {
            opacity: 1;
        }
        #remove-image:hover{
            cursor: pointer;
        }
        .box:hover{
            cursor: pointer;
        }
    </style>
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-body">
                <div class="tab-pane col-sm-9">
                    <form>
                    <input type="hidden" id="user_id" value="{{ $adminInformation->id }}" />
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                            <p class="mb-0">プロフィール写真</p>
                        </div>
{{--                        <label class="col-sm-3 col-form-label pt-5"></label>--}}
                        <div class="col-9 col-sm-9 d-flex d-sm-flex justify-content-start align-items-center justify-content-sm-start align-items-sm-center">
                            <div class="div-avatar">
                                <div class="box" id="box">
                                    <div class="upload">
                                        <input type='file' name="photo" id="upload-photo" />
                                        <label for="upload-photo"><i class="fas fa-camera"></i></label>
                                    </div>
                                    <div class="remove-image" id="remove-image">
                                        <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                    </div>
                                </div>
                            </div>
                            <span class="text-danger ml-5 error-custom" id="error_upload_photo"></span>
                        </div>
                    </div>

                    {{-- email --}}
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                            <p class="mb-0">メールアドレス</p>
                        </div>
{{--                        <label class="col-sm-3 col-form-label">メールアドレス</label>--}}
                        <div class="input-group  col-sm-9 d-sm-flex justify-content-sm-between">
                            <p class="mb-0"  style="word-break: break-all;">{{ $adminInformation->email }}</p>
                            @if(Auth::user()->id == $adminInformation->id)
                                <a href="javascript:;" style="text-decoration: underline" id="resetPassword" data-toggle="modal" data-target="#modalChangePassword">パスワードを変更する</a>
                            @else
                                <a href="javascript:;" style="text-decoration: underline" data-toggle="modal" data-target="#modalResetPassword">パスワードをリセットする</a>
                            @endif
                        </div>
                    </div>

                    {{-- nickname --}}
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                            <p class="mb-0">ニックネーム</p>
                            <span class="mb-3" style="color: red">*</span>
                        </div>
                        <div class="input-group col-sm-9">
                            <div class="w-100">
                                <input type="text" class="form-control" name="nickname" id="nickname" value="{{ $adminInformation->nickname }}">
                            </div>
                            <span class="text-danger error-custom" id="error_nickname"></span>
                        </div>
                    </div>

                    {{-- birthday --}}
                    <div class="form-group row" id="birthday">
                        <div class="col-3 col-sm-3 d-flex justify-content-between">
                            <p class="mb-0">生年月日</p>
                        </div>
                        <div class="col-9 col-sm-6 ">
                            <div class="d-flex justify-content-start align-items-center ">
                                <select class="w-9 p-1 rounded" id="year" name="year" onchange="change_year(this)">
                                    <option value=""></option>
                                    @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                        <option value="{{$i}}"
                                                @if($adminInformation->birthday != null && $i == date('Y',strtotime($adminInformation->birthday)))
                                                selected
                                            @endif
                                        >{{$i}}</option>
                                    @endfor
                                </select>
                                <span class="pr-1 mr-2">年</span>
                                <select class="w-8 p-1 rounded" id="month" name="month">
                                    <option value=""></option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{$i}}"
                                                @if($adminInformation->birthday != null && $i == date('m',strtotime($adminInformation->birthday)))
                                                selected
                                            @endif
                                        >{{$i}}</option>
                                    @endfor
                                </select>
                                <span class="pr-1 mr-2">月</span>
                                <select class="w-8 p-1 rounded" id="day" name="day">
                                    <option value=""></option>
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{$i}}"
                                                @if($adminInformation->birthday != null && $i == date('d',strtotime($adminInformation->birthday)))
                                                selected
                                            @endif
                                        >{{$i}}</option>
                                    @endfor
                                </select>
                                <span>日</span>
                            </div>
                            <span class=" text-danger error-custom" id="error_birthday"></span>
                        </div>
                    </div>

                    {{-- sex --}}
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                            <p class="mb-0">性別</p>
                        </div>
                        <div class="col-sm-9">
                            <input class="mr-1" type="radio" value="1" @if($adminInformation->sex == 1) checked="checked" @endif   id="sex-1" name="sex">
                            <label class="form-check-label pr-4" for="sex-1">
                                男性
                            </label>
                            <input class="form-group mr-1" type="radio" @if($adminInformation->sex == 2) checked="checked" @endif  id="sex-2"  value="2" name="sex">
                            <label class="form-check-label pr-4" for="sex-2">
                                女性
                            </label>
                            <input class="form-group mr-1" type="radio" @if($adminInformation->sex == 3) checked="checked" @endif  id="sex-3"  value="3" name="sex">
                            <label class="form-check-label" for="sex-3">
                                指定なし
                            </label>
                        </div>
                    </div>

                    {{-- nationality --}}
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                            <p class="mb-0">国籍</p>
                        </div>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="nationality">
                                @foreach($nationalities as $key => $nationality)
                                    <option value="{{$key}}" @if($key == $adminInformation->nationality && $adminInformation->nationality != '') selected @elseif($key == "VN" && $adminInformation->nationality == '') selected @endif>{{$nationality}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger error-custom"  id="error_nationality"></span>
                        </div>
                    </div>

                    {{-- area_code --}}
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                            <p class="mb-0">電話番号</p>
                        </div>
                        <div class="col-9 col-sm-6 d-flex flex-column justify-content-start align-items-start ">
                            <div class="input-group-prepend w-100" >
                                <select class="custom-select select2" name="area_code" style="width: 30% !important;">
                                    <option value=""></option>
                                    @foreach($phoneNumber as $key => $value)
                                        <option value="{{ $key . '-' . $value['code'] }}"
                                                @if($adminInformation->area_code != null && $adminInformation->area_code == ($key . '-' . $value['code']))
                                                selected
                                            @endif
                                        >{{$value['name']}} (+{{ $value['code'] }}) </option>
                                    @endforeach
                                </select>
                                <input type="text" class="form-control" value="{{ $adminInformation->phone_number }}" name="phone_number" id="phone_number" style="width: 70% !important">
                            </div>
                            <span class="text-danger error-custom"  id="error_phone_number"></span>
                        </div>
                    </div>

                    {{-- role --}}
                    <div class="form-group row">
                        <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                            <p class="mb-0">役割</p>
                        </div>
                        <div class="col-sm-6 d-flex">
                            <div class="form-check w-25">
                                <input class="form-check-input" disabled type="radio" name="role" @if($adminInformation->role == config('constants.role.admin')) checked="checked" @endif id="admin_role" value="{{ config('constants.role.admin') }}" >
                                <label class="form-check-label" for="admin_role">
                                    親
                                </label>
                            </div>
                            <div class="form-check w-25">
                                <input class="form-check-input" disabled type="radio" name="role" @if($adminInformation->role == config('constants.role.child-admin')) checked="checked" @endif  id="child_admin_role" value="{{ config('constants.role.child-admin') }}" >
                                <label class="form-check-label" for="child_admin_role">
                                    子
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="float-right">
                            <a href="{{route('admin.admin-list.detail', ['user_id' => $adminInformation->id])}}"><buton type="button" class="btn btn-secondary btn-flat">クリア</buton></a>
                            <buton type="button" class="btn btn-primary btn-flat ml-2" id="btnUpdateProfile">更新</buton>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">パスワードリセット</h5>
                    <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formResetPassword">
                        @csrf
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-12 col-sm-12">
                                <p class="ml-4" style="word-break: break-all;">メールアドレス : <span class="ml-3"> {{$adminInformation->email}}</span></p>
                            </div>
                        </div>
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-12 col-sm-12">
                                <p class="text-center my-2">このアドミンのパスワードをリセットします。よろしいでしょうか？</p>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                        <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">キャンセル</button>
                        <button type="button" class="btn btn-primary" id="btnResetPasswordAdmin">リセット</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalResetPasswordSuccessfully" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" style="max-width: 800px!important;">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row mb-2 d-flex align-items-center">
                        <div class="col-sm-12 d-sm-flex flex-sm-column justify-content-center align-items-center ">
                            <h6><i class="fas fa-check text-success"></i>パスワードのリセットが完了しました。</h6>
                            <h6>新しいパスワードがアドミンのメールに送信されました。</h6>
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
                        <div class="row mb-2 d-flex align-items-start">
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
                        <div class="row mb-2 d-flex align-items-start">
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
                        <div class="row mb-2 d-flex align-items-start">
                            <div class="col-sm-4 d-flex justify-content-between">
                                <p class="ml-3 mt-1 mb-0">新しいパスワード（確認）</p>
                                <span class="float-right" style="color: #ff0000">*</span>
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
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/AdminList/edit.js') }}"></script>
@endpush
