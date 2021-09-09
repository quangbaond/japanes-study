@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('create_admin') }}
@endsection

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/manager/students/create.css') }}"/>
@endsection

@section('title_screen', 'アドミン追加')

@section('content')
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
                    {!! Form::open(array('route' => 'admin.admin-list.store','method'=>'POST', 'id' => 'formCreateAdmin', 'enctype'=>'multipart/form-data')) !!}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label pt-5">プロフィール写真</label>
                        <div class="col-sm-9">
                            <img class="profile-user-img img-fluid img-circle mr-5 position-relative" id="image" src="{{ asset('images/avatar_2.png') }}" alt="User profile picture" style="object-fit: cover;">
                            <img class="position-absolute" src="{{ asset('images/icon_delete.png') }}" id="clearImage" >
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
                            <input id="text" type="text" class="form-control @error('email') is-invalid @enderror @if ($message = Session::get('error_isset_email')) is-invalid @endif" name="email" value="{{old('email')}}">
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
                            <input class="mr-1" type="radio" value="1" @if(old('sex') == 1) checked="checked" @endif   id="sex-1" name="sex">
                            <label class="form-check-label pr-4" for="sex-1">
                                男性
                            </label>
                            <input class="form-group mr-1" type="radio" @if(old('sex') == 2) checked="checked" @endif  id="sex-2"  value="2" name="sex">
                            <label class="form-check-label pr-4" for="sex-2">
                                女性
                            </label>
                            <input class="form-group mr-1" type="radio" @if(old('sex') == 3) checked="checked" @endif  id="sex-3"  value="3" name="sex">
                            <label class="form-check-label" for="sex-3">
                                指定なし
                            </label>
                        </div>
                    </div>

                    {{-- nationality --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">国籍</label>
                        <div class="col-sm-6">
                            <select class="form-control select2" name="nationality">
                                @foreach($nationalities as $key => $nationality)
                                    <option value="{{$key}}" @if($key == old('nationality') && old('nationality') != '') selected @elseif($key == "JP" && old('nationality') == '') selected @endif>{{$nationality}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- area_code --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">電話番号</label>
                        <div class="input-group col-sm-9">
                            <div class="input-group-prepend">
                                <select class="custom-select select2 w-50" name="area_code">
                                    @foreach($phoneNumber as $key => $value)
                                        <option value="{{$key.'-'.$value['code']}}" @if(($key.'-'.$value['code']) == old('area_code')) selected @elseif(($key.'-'.$value['code']) == "JP-81" && old('area_code') == '') selected @endif>{{$value['name'].' (+'.$value['code'].')'}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" value="{{old("phone_number")}}" name="phone_number">
                        </div>
                        <label class="col-sm-3 col-form-label"></label>
                        @error('phone_number')
                        <p><span class="invalid-feedback-custom" style="padding-left: 7.5px;">{{ $message }}</span></p>
                        @enderror
                    </div>

                    {{-- role --}}
                    <div class="form-group row">
                        <label class="col-sm-3 col-form-label">役割</label>
                        <div class="col-sm-6 d-flex">
                            <div class="form-check w-25">
                                <input class="form-check-input" type="radio" name="role" @if(old('role') == 1) checked="checked" @endif id="admin_role" value="{{ config('constants.role.admin') }}" >
                                <label class="form-check-label" for="admin_role">
                                    親
                                </label>
                            </div>
                            <div class="form-check w-25">
                                <input class="form-check-input" type="radio" name="role" @if(old('role') == 4) checked="checked" @endif id="child_admin_role" value="{{ config('constants.role.child-admin') }}" @if(is_null(old('role'))) checked @endif>
                                <label class="form-check-label" for="child_admin_role">
                                    子
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="float-right">
                            <a href="{{route('admin.admin-list')}}"><buton type="button" class="btn btn-secondary btn-flat">キャンセル</buton></a>
                            <buton type="button" class="btn btn-primary btn-flat ml-2" id="btnCreateAdmin">登録</buton>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/AdminList/create.js') }}"></script>
@endpush
