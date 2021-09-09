<div class="card card-outline card-warning" id="step1">
    <form action="{{route('student.register.step2.save')}}" method="post" id="formRegisterStudentStep1" enctype="multipart/form-data">
        @csrf
        <div class="card-body row">
            <!-- show error -->
            <div class="col-sm-12">
                <section class="col-sm-12" id="error_section"></section>
            </div>
            <!-- ./show error -->

            <!-- Account information -->
            <div class="col-sm-5 card-body pt-0">
                <h4 class="pb-3">{{__('student.account_information')}}</h4>
                <div class="tab-pane">

                    {{-- email --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.email')}}<span class="float-right text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="email" id="email" value="{{old('email')}}">
                            <strong class="invalid-feedback" role="alert"></strong>
                        </div>
                    </div>
                    {{-- /.email --}}

                    {{-- email confirm --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.email_confirm')}}<span class="float-right text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="email_confirm" name="email_confirm" value="{{old('email_confirm')}}">
                            <strong class="invalid-feedback" role="alert"></strong>
                        </div>
                    </div>
                    {{-- /.email confirm --}}

                    {{-- password --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.password')}}<span class="float-right text-danger">*</span></label>
                        <div class="col-sm-8 float-right">
                            <input type="password" class="form-control" id="password" name="password" value="{{old('password')}}">
                            <strong class="invalid-feedback" role="alert"></strong>
                        </div>
                    </div>
                    {{-- /.password --}}

                    {{-- password confirm --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.password_confirm')}}<span class="float-right text-danger">*</span></label>
                        <div class="col-sm-8 float-right">
                            <input type="password" class="form-control" id="password_confirm" name="password_confirm" value="{{old('password_confirm')}}">
                            <strong class="invalid-feedback" role="alert"></strong>
                        </div>
                    </div>
                    {{-- /.password confirm --}}

                    <!-- nick name -->
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.nickname')}}<span class="float-right text-danger">*</span></label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="nickname" name="nickname" value="{{old('nickname')}}">
                            <strong class="invalid-feedback" role="alert"></strong>
                        </div>
                    </div>
                    <!-- /.nick name -->

                </div>
            </div>
            <!-- /.End account information -->

            <!-- Profile -->
            <div class="col-sm-5 card-body pt-0">
                <h4 class="">{{__('student.profile')}}</h4>
                <div class="tab-pane">
                    {{-- image --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label pt-4">{{__('student.image_photo')}}</label>
                        <div class="col-sm-8">
                            <img id="image" class="profile-user-img img-fluid img-circle mr-4 w-25 position-relative" src="{{ asset('images/avatar_2.png') }}" alt="User profile picture" style="object-fit: cover;">
                            <input type="file" name="image_photo" id="image_photo">
                            <div class="position-absolute d-none" style="top: 0px;" id="clearImage">
                                <span class="text-center bg-white rounded-circle font-weight-bold" id="btnCancel" style="font-size: 20px;">
                                    <i class="far fa-times-circle text-danger" ></i>
                                </span>
                            </div>
                            <button type="button" class="btn btn-primary btn-flat btn-sm" id="choice_image">{{ __('student.choose_file') }}</button>
                        </div>
                        <label class="col-sm-4"></label>
                        <div class="col-sm-8">
                            <strong class="invalid-feedback-custom" role="alert" id="error_image"></strong>
                        </div>
                    </div>
                    {{-- /.image --}}

                    <!-- birthday -->
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.birthday')}}</label>
                        <div class="col-sm-8" >
                            <input type="text" hidden id="birthday">
                            <select class="w-9 p-1 rounded" name="year" id="year">
                                <option value="" selected></option>
                                @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                    <option value="{{$i}}" @if($i == old('year')) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <span class="pr-1">{{__('student.year')}}</span>
                            <select class="w-8 b-r" name="month" id="month">
                                <option value="" selected></option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                            <span class="pr-1">{{__('student.month')}}</span>
                            <select class="w-8 b-r" name="day" id="day">
                                <option value="" selected></option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{$i}}" @if($i == old('day')) selected @endif>{{$i}}</option>
                                @endfor
                            </select>
                            <span>{{__('student.day')}}</span>
                            <p><strong class="invalid-feedback-custom"></strong></p>
                        </div>
                    </div>
                    <!-- /.birthday -->

                    <!-- sex -->
                    <div class="form-group row clearfix">
                        <label class="col-sm-4 col-form-label">{{__('student.sex')}}</label>
                        <div class="col-sm-8">
                            <input class="mr-1" type="radio" name="sex" value="1">
                            <span class="pr-4">{{__('student.male')}}</span>
                            <input class="mr-1" type="radio" name="sex" value="2">
                            <span class="pr-4">{{__('student.female')}}</span>
                            <input class="mr-1" type="radio" name="sex" value="3">
                            <span>{{__('student.other')}}</span>
                        </div>
                    </div>
                    <!-- /.sex -->

                    <!-- phone_number -->
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{__('student.phone_number')}}</label>
                        <div class="input-group col-sm-8">
                            <div class="input-group-prepend">
                                <select class="custom-select select2 w-50" name="area_code">
                                    @foreach($area_code as $key => $value)
                                        <option value="{{$key.'-'.$value['code']}}" @if(($key.'-'.$value['code']) == "VN-84") selected @endif>{{$value['name'].' (+'.$value['code'].')'}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <input type="text" class="form-control ml-2" id="phone_number" name="phone_number" value="{{old('phone_number')}}">
                            <strong class="invalid-feedback" role="alert"></strong>
                        </div>
                    </div>
                    <!-- /.phone_number -->

                    {{-- nationality --}}
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">{{ __('student.nationality') }}</label>
                        <div class="col-sm-8">
                            <select class="form-control select2" name="nationality">
                                @foreach($nationalities as $key => $nationality)
                                    <option value="{{$key}}" @if($nationality == "Viet Nam") selected @endif>{{$nationality}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    {{-- /.nationality --}}

                </div>
            </div>
            <!-- /.End profile -->

            <!-- Profile -->
            <div class="col-sm-2 card-body pt-0">
                <h4 class="">{{ __('student.title_sign_in') }}</h4>
                <div class="tab-pane">
                    <a href="{{ route('login.student.facebook') }}" class="btn btn-block btn-primary btn-flat btn-sm">
                        <i class="fab fa-facebook float-left py-1"></i>Facebook
                    </a>
                    <a href="{{ route('login.student.google') }}" class="btn btn-block btn-danger btn-flat btn-sm">
                        <i class="fab fa-google-plus float-left py-1"></i>Google
                    </a>
                    <a href="{{ route('login.student.zalo') }}" class="btn btn-block btn-default btn-flat btn-sm">
                        <img class="float-left d-block img-fluid py-1" src="/images/zalo_icon.jpg">Zalo
                    </a>
                </div>
                <div class="nav-item dropdown">
                    <a class="nav-link text-right" data-toggle="dropdown" href="#">
                        <span>{{ __('student.choice_language') }}</span>
                        <img src="{{ asset('images/multi-language.png') }}" alt="" style="width: 30px;height: 30px">
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <a href="{{ url('change-language/en') }}"  class="dropdown-item dropdown-footer">{{ __('header.english') }}</a>
                        <div class="dropdown-divider"></div>
                        <a href="{{ url('change-language/vi') }}"  class="dropdown-item dropdown-footer">{{ __('header.viet_nam') }}</a>
                    </div>
                </div>
            </div>
            <!-- /.End profile -->
        </div>
        <div class="card-footer">
            <div class="input-group">
                <!-- i_agree_to_the -->
                <div class="form-group col-sm-6">
                    <div class="icheck-primary">
                        <input type="checkbox" id="agreeTerms" name="terms">
                        <label for="agreeTerms">
                            {{ __('login.i_agree_to_the') }} <a href="{{route('student.terms-of-service')}}">{{ __('login.terms_of_service') }}</a>
                        </label>
                        <label><strong class="invalid-feedback-custom" id="show-error-agree"></strong></label>
                    </div>
                </div>
                <!-- /.i_agree_to_the -->

                <!-- button -->
                <div class="form-group col-sm-6">
                    <div class="text-center">
                        <button type="button" class="btn btn-primary btn-flat float-right" id="btnGoToStep2">{{ __('button.next') }}</button>
                        <a href="{{route('login.student')}}">
                            <button type="button" class="btn btn-default mr-3 float-right btn-flat">{{__('button.back')}}</button>
                        </a>
                    </div>
                </div>
                <!-- /.button -->
            </div>
        </div>
    </form>
</div>
