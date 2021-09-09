@extends('layouts.admin.app')
@section('breadcrumb')
{{ Breadcrumbs::render('teacher_detail', $teacher) }}
@endsection
@section('stylesheets')
    <meta name="route-reset-password" content="{{ route('admin.teacher.reset-password',['id' => $teacher->id]) }}">
    <meta name="route-update-profile" content="{{ route('admin.teacher.update-profile',['id' => $teacher->id]) }}">
@endsection

@section('title_screen', '講師詳細')
@section('content')
    <style>
        .has-error .select2-selection {
            border-color: rgb(185, 74, 72) !important;
        }
        #btnCancel {
            /* display: none; */
            opacity: 0;
        }
        .introduce {
            position:relative;
            padding: 10px;
        }
        .introduce > .cancel {
            position: absolute;
            top: -10px;
            right: -4px;
        }
        #showCancel:hover #btnCancel {
            /* display: none; */
            opacity: 1;
        }
        #btnCancel:hover {
            opacity: 1;
            cursor: pointer;
        }
        #remove-video {
            /*background: rgb(192,225,229);*/
            height:320px;
            border: 2px solid #dee2e6;
        }
        .div-avatar {
            padding: 3px;
            border: 3px solid #adb5bd;
            border-radius: 50%;
        }
        .box{
            border-radius: 50%;
            height: 100px;
            width:100px;
            background-image: url("{{ $teacher->image_photo ?? asset('images/avatar_2.png') }}");
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
            z-index: -1;
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
        li.select2-results__option:first-child {
            width: 100% !important;
            height: auto !important;
        }
    </style>
    <section class="content">
        <div class="container-fluid d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        <form id="update-profile" enctype="multipart/form-data">
                            @csrf
                            <div class="card p-3">
                                <div class="row my-2">
                                    <div class="col-3">
                                        <p class="ml-3 mt-1">写真</p>
                                    </div>
                                    <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <div class="div-avatar">
                                            <div class="box" id="box">
                                                <div class="remove-image" id="remove-image">
                                                    <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 text-right">
                                        <a href="{{ route('admin.teacher.bookingSubstitute' , $teacher->id)}}" class="btn-flat btn btn-primary">
                                           レッスン予約代行
                                        </a>
                                    </div>
                                </div>

                                <div class="row mb-1">

                                    <div class="col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1">紹介ビデオ</p>
                                    </div>
                                    <div class="col-sm-6 py-1 px-0">
                                        <div class="introduce" id="showCancel">
                                            <div class="" id="remove-video">
                                                <iframe class="w-100 px-0 introduce-video" width="560" height="315" id="link_youtube"
                                                    src="{{ $teacher->link_youtube }}" frameborder="0"
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                    allowfullscreen></iframe>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="row my-2">
                                    <div
                                        class="col-9 offset-3 d-flex justify-content-end col-sm-3 offset-sm-6 d-sm-flex justify-content-sm-end">
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-center">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mb-0">メールアドレス</p>
                                        <span class="float-right" style="color: red"></span>
                                    </div>
                                    <div class="col-8 d-flex justify-content-between align-items-center col-sm-6 d-sm-flex justify-content-sm-between align-items-sm-center">
                                        <div class="col-6 col-md-5 col-sm-8 d-sm-flex flex-sm-column align-items-sm-start" style="word-break: break-all;">
                                            <p class="mb-0" id="email">{{ $teacher->email }}</p>
                                            <span class="text-danger" id="warning-email"></span>
                                        </div>
                                        <div
                                            class="col-6 col-md-7 d-flex flex-column justify-content-center col-sm-4 d-sm-flex flex-sm-column align-items-sm-end ">
                                            <a href="http://" style="text-decoration: underline!important;" data-toggle="modal" data-target="#modalResetPassword">パスワードをリセットする</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-2 mb-0">ニックネーム</p>
                                        <span class="float-right" style="color: red">*</span>
                                    </div>
                                    <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <input type="text" name="nickname" id="nickname" class="w-100 form-control"
                                               value="{{ $teacher->nickname }}" >
                                        <span class="text-danger" id="error_nickname"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start mt-1">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">生年月日</p>
                                    </div>
                                    <div class="col-9 col-sm-6 ">
                                        <div class="d-flex justify-content-start align-items-center ">
                                            <select class="w-8 p-1 rounded" id="year" name="year" >
                                                <option value=""></option>
                                                @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                                    <option value="{{$i}}"
                                                            @if($teacher->birthday != null && $i == date('Y',strtotime($teacher->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span class="pr-1">年</span>
                                            <select class="w-8 p-1 rounded" id="month" name="month" >
                                                <option value=""></option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{$i}}"
                                                            @if($teacher->birthday != null && $i == date('m',strtotime($teacher->birthday)))
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
                                                            @if($teacher->birthday != null && $i == date('d',strtotime($teacher->birthday)))
                                                            selected
                                                        @endif
                                                    >{{$i}}</option>
                                                @endfor
                                            </select>
                                            <span>日</span>
                                        </div>
                                        <span class="text-danger" id="error_birthday"></span>
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
                                                    @if($teacher->sex == config('constants.sex.id.male'))
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
                                                    @if($teacher->sex == config('constants.sex.id.female'))
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
                                                    @if($teacher->sex == config('constants.sex.id.unspecified'))
                                                    checked
                                                    @endif
                                                >
                                                <label class="form-check-label" for="exampleRadios3">
                                                    指定なし
                                                </label>
                                            </div>
                                        </div>
                                        <span class="text-danger" id="error_sex"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">国籍</p>
                                    </div>
                                    <div class=" col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                        <select class="form-control select2" name="nationality" id="nationality" style="width: 70%;">
                                            <option value=""></option>
                                            @foreach( $nationality as $key => $value)
                                                <option value="{{$key}}"
                                                    @if($teacher->nationality == $key)
                                                        selected
                                                    @endif
                                                >{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger" id="error_nationality"></span>
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
                                                            @if($teacher->area_code != null && $teacher->area_code == ($key . '-' . $value['code']))
                                                            selected
                                                        @endif
                                                    >{{$value['name']}} (+{{ $value['code'] }}) </option>
                                                @endforeach
                                            </select>
                                            <input type="text"
                                               class="form-control @error('phone_number') is-invalid @enderror"
                                               value="{{$teacher->phone_number}}" name="phone_number" id="phone_number" style="width: 70% !important">
                                        </div>
                                        <span class="text-danger" id="error_phone_number"></span>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-3 col-sm-3">
                                        <p class="ml-3 mt-1 mb-0">スタッフからの紹介</p>
                                    </div>
                                    <div class="col-9 col-sm-6 ">
                                        <div class="form-group w-100  mb-0">
                                            <p class="mb-0"></p>
                                            <textarea class="form-control" id="introduction_from_admin" name="introduction_from_admin" onkeyup="countChar(this,'IntroductionFromAdmin','500'); setHeight(this)" maxlength="500" id="" name="" rows="3" style="overflow: hidden;font-size: 14.4px!important;">{{$teacher->introduction_from_admin}}</textarea>
                                        </div>
                                        <span class="text-danger" id="error_introduction_from_admin"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="float-right m-l-3 mr-4" id="charNumForIntroductionFromAdmin"></div>
                                    </div>
                                </div>
                                <div class="row my-2">
                                    <div class="col-3 col-sm-3">
                                        <p class="ml-3 mt-1 mb-0">自己紹介</p>
                                    </div>
                                    <div class="col-9 col-sm-6">
                                        <div class="form-group w-100  mb-0">
                                            <textarea class="form-control" name="self-introduction" disabled id="self-introduction" onkeyup="countChar(this,'Introduction','500')" maxlength="500"
                                                      id="exampleFormControlTextarea1"
                                                      rows="3" style="overflow: hidden;font-size: 14.4px!important;">{{ $teacher->introduction }}</textarea>
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
                                        <textarea class="form-control" onkeyup="countChar(this,'Experience','100')" maxlength="100" name="experience" id="experience"
                                                      rows="2" style="font-size: 14.4px!important;">{{ $teacher->experience }}</textarea>
                                        <span class="text-danger" id="error_experience"></span>
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
                                        <textarea class="form-control" onkeyup="countChar(this,'Certification','100')" maxlength="100" name="certification" id="certification"
                                                      rows="2" style="font-size: 14.4px!important;">{{ $teacher->certification }}</textarea>
                                        <span class="text-danger" id="error_certification"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-9">
                                        <div class="float-right m-l-3 mr-4" id="charNumForCertification"></div>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-start">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-3 mb-0">対応可能コース</p>
                                        <span class="float-right" style="color: red">*</span>
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
                                        <span class="text-danger" id="error_course"></span>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-center">
                                    <div class="col-3 col-sm-3 d-flex justify-content-between">
                                        <p class="ml-3 mt-1 mb-0">レッスンのコイン数</p>
                                    </div>
                                    <div class="col-6 col-sm-6">
                                        <select class="custom-select select2 w-50" name="coin">
                                            <option value="0" @if($teacher->coin == 0 || $teacher->coin == '') selected @endif>0</option>
                                            <option value="100" @if($teacher->coin == 100 ) selected @endif>100</option>
                                            <option value="200" @if($teacher->coin == 200) selected @endif>200</option>
                                            <option value="300" @if($teacher->coin == 300) selected @endif>300</option>
                                            <option value="400" @if($teacher->coin == 400) selected @endif>400</option>
                                            <option value="500" @if($teacher->coin == 500) selected @endif>500</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row my-2 d-flex align-items-center">
                                    <div class="col-sm-12 d-flex justify-content-center">
                                        <a type="button" class="btn btn-secondary mr-2" href="{{ route('admin.teacher.detail',['user_id' => $teacher->id]) }}">クリア</a>
                                        <a href="javascript:;" type="button" class="btn btn-primary" id="btnUpdateProfile" >更新</a>
                                    </div>
                                </div>
                            </div>

                        </form>
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
        <div class="modal fade" id="modalResetPasswordSuccessfully" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 800px!important;">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row mb-2 d-flex align-items-center">
                            <div class="col-sm-12 d-sm-flex flex-sm-column justify-content-center align-items-center ">
                                <h6><i class="fas fa-check text-success"></i>パスワードのリセットが完了しました。</h6><h6>新しいパスワードが講師のメールに送信されました。</h6>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-12 d-sm-flex justify-content-sm-center">
                        <button type="button" class="btn btn-primary mr-3" data-dismiss="modal" >OK</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog modal-md" >
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">パスワードリセット</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row d-flex d-sm-flex">
                            <div class="col-4 col-sm-4 " >
                                <p class="pr-0 ml-4">メールアドレス :</p>
                            </div>
                            <div class="col-8 col-sm-8 " >
                                <p class="" style="word-break: break-all;"> {{$teacher->email}}</p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-12">
                                <p class="text-center my-2">この講師のパスワードをリセットします。よろしいでしょうか？</p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">クリア</button>
                            <button type="button" class="btn btn-primary" id="btnResetPasswordTeacher">リセット</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
<script src="{{ asset('js/admin/managers/teachers/detail.js') }}"></script>

    <script>
        function countChar(val,title,maxlength) {
            var len = val.value.length;
            if (len >= 501) {
                val.value = val.value.substring(0, 500);
            } else {
                $('#charNumFor'+title).text(`${len}/${maxlength}`);
            }
        };
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
