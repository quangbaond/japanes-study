@extends('layouts.student.app')

@section('breadcrumb')
{{ Breadcrumbs::render('update_info_student') }}
@endsection

@section('stylesheets')
    <style type="text/css">
        .center-content{
            display: flex;
            justify-content: center;
        }
    </style>
@endsection

@section('title_screen', __('login.setting_password'))

@section('content')
    <div class="container">
        <div class="row">
            <div class="card w-100">
                {!! Form::open(array('route' => 'student_update_password','method'=>'POST', 'id' => 'updatePassword', 'enctype'=>'multipart/form-data')) !!}
                <div class="card-body">
                    <p>{{__('login.excerpt_setting_password')}} @if(empty(Auth::user()->zalo_id)) Google/Facebook @else Zalo @endif</p>
                    <p>{{__('login.excerpt_setting_password_2')}}</p>
                    <div class="tab-pane col-sm-12">

                        {{-- email --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ __('login.email') }}<span class="float-right" style="color: red">*</span></label>
                            <div class="input-group col-sm-9">
                                <input id="text" type="text" class="form-control @error('email') is-invalid @enderror" @if(Auth::user()->email != '') disabled="disabled" @endif name="email" value="@if(old('email') != '') {{old('email')}} @else {{Auth::user()->email}} @endif">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- password --}}
                        <div class="form-group row">
                            <label class="col-sm-3 col-form-label">{{ __('login.password') }}<span class="float-right" style="color: red">*</span></label>
                            <div class="input-group col-sm-9">
                                <input id="text" type="password" class="form-control @error('password') is-invalid @enderror" name="password" value="{{old('password')}}">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>

                        {{-- password confirm --}}
                        <div class="form-group row">
                            <label for="password-confirm" class="col-sm-3 col-form-label">{{ __('login.password_confirm') }}<span class="float-right" style="color: red">*</span></label>

                            <div class="col-md-9">
                                <input id="password-confirm" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" value="{{old('password_confirmation')}}" name="password_confirmation" autocomplete="new-password">
                                @error('password_confirmation')
                                <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="center-content">
                        <a href="{{route('student-dashboard')}}"><buton type="button" class="btn btn-secondary btn-flat">{{ __('button.cancel') }}</buton></a>
                        <buton type="button" class="btn btn-primary btn-flat ml-2"  onclick="document.getElementById('updatePassword').submit();" id="btnCreateTeacher">{{ __('button.update') }}</buton>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/teachers/create.js') }}"></script>
@endpush
