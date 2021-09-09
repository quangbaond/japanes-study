@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('create_teachers') }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/manager/teachers/create.css') }}"/>
@endsection

@section('title_screen', '講師追加')

@section('content')
{{--    {{ dd(Session::all()) }}--}}
    <style>
        .has-error .select2-selection {
            border-color: rgb(185, 74, 72) !important;
        }
    </style>

    <div class="content">
        <div class="container-fluid">
            <div class="card card-info">
                @if($errors->any())
                    <section class="content-header" id="error_section" style="display: block">
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <i class="icon fa fa-ban"></i>
                            <span id="error_mes">入力内容に誤りがあります。もう一度入力内容を見直してください。</span>
                        </div>
                    </section>
                @endif
                <div class="card-body">
                    <div class="tab-pane col-sm-9">
                        {!! Form::open(array('route' => 'admin.teacher.add','method'=>'POST', 'id' => 'formCreateTeacher', 'enctype'=>'multipart/form-data')) !!}
                        {{-- image --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label pt-5">プロフィール写真</label>
                            <div class="col-sm-9">
                                <img class="profile-user-img img-fluid img-circle mr-5" id="image" src="{{ asset('images/avatar_2.png') }}" alt="User profile picture" style="object-fit: cover;">
                                <img class="position-absolute" src="{{ asset('images/icon_delete.png') }}" id="clearImage">
                                <input type="file" name="image_photo" id="image_url" value="">
                                <button type="button" id="choice_image" class="btn btn-primary btn-flat @error('image_photo') is-invalid @enderror">ファイル選択</button>
                                @error('image_photo')
                                <br><span class="help-block text-danger">{{ $message }}</span>
                                @enderror
                                <br><span class="text-danger" id="error-photo"></span>
                            </div>
                        </div>

                        {{-- email --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">メールアドレス<span class="float-right" style="color: red">*</span></label>
                            <div class="input-group col-sm-9">
                                <input id="text" type="text" class="form-control @error('email') is-invalid @enderror  @if ($message = Session::get('error_isset_email')) is-invalid @endif" name="email" value="{{old('email')}}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                                @if ($message = Session::get('error_isset_email'))
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- nickname --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">ニックネーム<span class="float-right" style="color: red">*</span></label>
                            <div class="input-group col-sm-9">
                                <input id="text" type="text" class="form-control @error('nickname') is-invalid @enderror" name="nickname" value="{{old('nickname')}}">
                                @error('nickname')
                                <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- birthday --}}
                        <div class="form-group row" id="birthday">
                            <label class="col-sm-3 col-form-label">生年月日</label>
                            <div class="col-sm-9">
                                <select class="w-9 p-1 rounded" id="year" name="year" onchange="change_year(this)"
                                @error('year')
                                style="border:1px solid #f10;"
                                @enderror
                                >
                                    <option value="0" selected></option>
                                    @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                        <option value="{{$i}}" @if($i == old('year')) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                                <span class="pr-1">年</span>
                                <select class="w-8 p-1 rounded" id="month" name="month" onchange="change_month(this)"
                                @error('month')
                                    style="border:1px solid #f10;"
                                @enderror
                                >
                                    <option value="0" selected></option>
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{$i}}" @if($i == old('month')) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                                <span class="pr-1">月</span>
                                <select class="w-8 p-1 rounded" id="day" name="day"
                                @error('day')
                                    style="border:1px solid #f10;"
                                @enderror
                                >
                                    <option value="0" selected></option>
                                    @for($i = 1; $i <= 31; $i++)
                                        <option value="{{$i}}" @if($i == old('day')) selected @endif>{{$i}}</option>
                                    @endfor
                                </select>
                                <span>日</span>
                                @error('year')
                                <p><span class="invalid-feedback-custom">{{ $message }}</span></p>
                                @enderror
                                @error('month')
                                <p><span class="invalid-feedback-custom">{{ $message }}</span></p>
                                @enderror
                                @error('day')
                                <p><span class="invalid-feedback-custom">{{ $message }}</span></p>
                                @enderror
                            </div>
                        </div>

                        {{-- sex --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">性別</label>
                            <div class="col-sm-9">
                                <input class="mr-1" type="radio" value="1" @if(old('sex') == 1) checked="checked" @endif name="sex">
                                <span class="pr-4">男性</span>
                                <input class="form-group mr-1" type="radio" @if(old('sex') == 2) checked="checked" @endif value="2" name="sex">
                                <span class="pr-4">女性</span>
                                <input class="form-group mr-1" type="radio" @if(old('sex') == 3) checked="checked" @endif value="3" name="sex">
                                <span>指定なし</span>
                            </div>
                        </div>

                        {{-- nationality --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">国籍</label>
                            <div class="col-sm-6">
                                <select class="form-control select2" name="nationality">
                                    @foreach($nationalities as $key => $nationality)
                                        <option value="{{$key}}" @if($key == old('nationality')) selected @elseif($key == "JP" && old('nationality') == '') selected @endif>{{$nationality}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- area_code --}}
                        <div class="form-group row mb-0">
                            <label class="col-sm-3 col-form-label">電話番号</label>
                            <div class="input-group col-sm-9">
                                <div class="input-group-prepend">
                                    <select class="custom-select select2 w-50" name="area_code">
                                        @foreach($phoneNumber as $key => $value)
                                            <option value="{{$key.'-'.$value['code']}}" @if(($key.'-'.$value['code']) == old('area_code')) selected @elseif(($key.'-'.$value['code']) == "JP-81" && old('area_code') == '') selected @endif>{{$value['name'].' (+'.$value['code'].')'}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="text" class="form-control  @error('phone_number') is-invalid @enderror" value="{{old("phone_number")}}" name="phone_number">
                            </div>
                            <label class="col-sm-3 col-form-label"></label>
                            @error('phone_number')
                                <p><span class="invalid-feedback-custom" style="padding-left: 7.5px;">{{ $message }}</span></p>
                            @enderror
                        </div>
                        <div class=" form-group row d-flex align-items-start">
                            <label class="col-sm-3 col-form-label">講師紹介</label>
                            <div class="col-9 col-sm-9 d-flex flex-column justify-content-center align-items-start ">
                                        <textarea class="form-control @error('introduction_from_admin') is-invalid @enderror" style="font-size: 14.4px!important;overflow: hidden" onkeyup="countChar(this,'IntroduceFromAdmin','500'); setHeight(this)" maxlength="500"  name="introduction_from_admin" id="fieldIntro"  rows="3">{{old("introduction_from_admin")}}</textarea>
                                @error('introduction_from_admin')
                                <p><span class="invalid-feedback-custom" style="padding-left: 7.5px;">{{ $message }}</span></p>
                                @enderror
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="float-right mr-4" style="margin-top: -2.5rem" id="charNumForIntroduceFromAdmin"></div>
                            </div>
                        </div>
                        <div class="form-group @error('course') has-error @enderror row d-flex align-items-start">
                            <label class="col-sm-3 col-form-label">対応可能コース<span class="float-right" style="color: red">*</span></label>
                            <div class=" col-9 col-sm-9 d-flex flex-column justify-content-center align-items-start ">
                                <select class="select2 @error('course') is-invalid @enderror"  id="course" name="course[]" multiple="multiple" data-placeholder="" style="width: 100%;">
                                    @foreach($courses as  $value)
                                        <option  value="{{$value->id}}"
                                        @if($errors->any() && old('course') != null)
                                            @if(in_array($value->id, old('course')))
                                                selected
                                            @endif
                                        @endif
                                        >{{$value->name}}</option>
                                    @endforeach
                                </select>

                                @error('course')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- lessonCoin --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">レッスンのコイン数</label>
                            <div class="col-sm-6">
                                <select class="custom-select select2 w-50" name="lessonCoin">
                                    <option value="0" @if(old('lessonCoin') == 0) selected @endif>0</option>
                                    <option value="100" @if(old('lessonCoin') == 100 || old('lessonCoin') == '') selected @endif>100</option>
                                    <option value="200" @if(old('lessonCoin') == 200) selected @endif>200</option>
                                    <option value="300" @if(old('lessonCoin') == 300) selected @endif>300</option>
                                    <option value="400" @if(old('lessonCoin') == 400) selected @endif>400</option>
                                    <option value="500" @if(old('lessonCoin') == 500) selected @endif>500</option>
                                </select>
                            </div>
                        </div>

                        {{-- button --}}
                        <div class="form-group">
                            <div class="float-right">
                                <a href="{{route('admin.teacher.index')}}"><buton type="button" class="btn btn-secondary btn-flat">キャンセル</buton></a>
                                <buton type="button" class="btn btn-primary btn-flat ml-2" id="btnCreateTeacher">登録</buton>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/teachers/create.js') }}"></script>
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
            let fieldIntro = document.getElementById("fieldIntro");
            fieldIntro.style.height = "";
            fieldIntro.style.height = Math.min(fieldIntro.scrollHeight, limit) + "px";
        });
    </script>
@endpush
