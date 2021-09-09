@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('student_detail') }}
@endsection
@section('stylesheets')
    <meta name="route-reset-password" content="{{ route('admin.student.reset-password',['id' => $studentInformation->id]) }}">
    <meta name="route-update-profile" content="{{ route('admin.student.update-profile',['id' => $studentInformation->id]) }}">
    <meta name="route-refund-coin" content="{{ route('admin.student.refund-coin',['id' => $studentInformation->id]) }}">
@endsection
@section('title_screen', '生徒詳細')

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
    background-image: url("{{ $studentInformation->image_photo ?? asset('images/avatar_2.png') }}");
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
</style>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <form id="update-profile" enctype="multipart/form-data">
                        @csrf
                        <div class="card">
                            <!-- <div class="card-header">
                                <h3 class="card-title">Student payment information</h3>
                            </div> -->
                            <!-- /.card-header -->
                            <div class="card-body table-responsive">
                                <div class="row">
                                    <div class="col-12">
                                        <form>
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-2 mb-0">プロフィール写真</p>
                                                </div>
                                                <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                                    <div class="div-avatar">
                                                        <div class="box" id="box">
                                                            @if(Request::path() == 'teacher/edit-profile')
                                                            <div class="upload">
                                                                <input type='file' name="photo" id="upload-photo" />
                                                                <label for="upload-photo"><i class="fas fa-camera"></i></label>
                                                            </div>
                                                            @endif
                                                            <div class="remove-image" id="remove-image">
                                                                <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-center">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mb-0">メールアドレス</p>
                                                    <span class="float-right" style="color: red"></span>
                                                </div>
                                                <div class="col-9 d-flex justify-content-between align-items-center col-sm-6 d-sm-flex justify-content-sm-between align-items-sm-center">
                                                    <div class="col-6 col-sm-8 d-sm-flex flex-sm-column align-items-sm-start">
                                                        <p class="mb-0" id="email" name="email" style="word-break: break-all;">{{ $studentInformation->email }}</p>
                                                        <span class="text-danger" id="warning-email"></span>
                                                    </div>
                                                    <div
                                                        class="col-6 d-flex flex-column justify-content-center col-sm-4 d-sm-flex flex-sm-column align-items-sm-end ">
                                                        <a href="http://" data-toggle="modal" data-target="#modalResetPassword" style="text-decoration: underline!important">パスワードリセットする</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-2 mb-0">ニックネーム</p>
                                                    <span class="float-right" style="color: red">*</span>
                                                </div>
                                                <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                                    <input type="text" name="nickname" id="nickname" class="w-100 form-control"
                                                        value="{{ $studentInformation->nickname }}" >
                                                    <span class="text-danger" id="error_nickname"></span>
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-start mt-1">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-1 mb-0">生年月日</p>
                                                </div>
                                                <div class="col-9 col-sm-6 ">
                                                    <div class="d-flex justify-content-start align-items-center ">
                                                        <select class="w-8 p-1 rounded" id="year" name="year" onchange="change_year(this)">
                                                            <option value=""></option>
                                                            @for($i = config('constants.year_from'); $i <= config('constants.year_to'); $i++)
                                                                <option value="{{$i}}"
                                                                    @if($studentInformation->birthday != null && $i == date('Y',strtotime($studentInformation->birthday)))
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
                                                                    @if($studentInformation->birthday != null && $i == date('m',strtotime($studentInformation->birthday)))
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
                                                                    @if($studentInformation->birthday != null && $i == date('d',strtotime($studentInformation->birthday)))
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
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mb-0">性別</p>
                                                </div>
                                                <div class="col-9 col-sm-6 ">
                                                    <div class="d-flex justify-content-start align-items-center">
                                                        <div class="form-check mr-3">
                                                            <input class="form-check-input" type="radio" name="sex" id="exampleRadios1"
                                                                value="{{ config('constants.sex.id.male') }}"
                                                               @if($studentInformation->sex == config('constants.sex.id.male'))
                                                                   checked
                                                                @endif>
                                                            <label class="form-check-label" for="exampleRadios1">
                                                                男性
                                                            </label>
                                                        </div>
                                                        <div class="form-check mr-3">
                                                            <input class="form-check-input" type="radio" name="sex" id="exampleRadios2"
                                                                value="{{ config('constants.sex.id.female') }}"
                                                               @if($studentInformation->sex == config('constants.sex.id.female'))
                                                                   checked
                                                                @endif
                                                            >
                                                            <label class="form-check-label" for="exampleRadios2">
                                                                女性
                                                            </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" name="sex" id="exampleRadios3"
                                                                value="{{ config('constants.sex.id.unspecified') }}"
                                                               @if($studentInformation->sex == config('constants.sex.id.unspecified'))
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
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-1 mb-0">国籍</p>
                                                </div>
                                                <div class=" col-9 col-sm-4 d-flex flex-column justify-content-center align-items-start ">
                                                    <select class="form-control select2" name="nationality" style="width: 70%;">
                                                        <option value=""></option>
                                                        @foreach( $nationality as $key => $value)
                                                            <option value="{{$key}}"
                                                                @if($studentInformation->nationality == $key)
                                                                    selected
                                                                @endif
                                                            >{{$value}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="error_nationality"></span>
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-1 mb-0">会員状態</p>
                                                </div>
                                                <div class=" col-9 col-sm-4 d-flex flex-column justify-content-center align-items-start ">
                                                        @foreach( config('constants.membership.name') as $key => $val)
                                                            @if($studentInformation->membership_status == config("constants.membership.id.$key"))
                                                                <p class=" mt-1 mb-0">{{ $val }}</p>
                                                            @endif
                                                        @endforeach
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-2 mb-0">会社名</p>
                                                </div>
                                                <div class="col-9 col-sm-6 d-flex flex-column justify-content-center align-items-start ">
                                                    <select class="form-control select2" style="width: 100%;" name="company_id" id="company_id"
                                                        @if($studentInformation->membership_status != config('constants.membership.id.other_company'))
                                                            disabled
                                                        @endif
                                                    >
                                                        <option></option>
                                                        @foreach($company as $item)
                                                            <option value="{{$item->id}}"
                                                            @if($studentInformation->company_id == $item->id)
                                                                selected
                                                            @endif
                                                            >{{$item->name}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="text-danger" id="error_company_id"></span>
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-start">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mt-1 mb-0">電話番号</p>
                                                </div>
                                                <div class="col-9 col-sm-6 d-flex flex-column justify-content-start align-items-start ">
                                                    <div class="input-group-prepend w-100" >
                                                        <select class="custom-select select2" name="area_code" style="width: 30% !important;">
                                                            <option value=""></option>
                                                            @foreach($phoneNumber as $key => $value)
                                                                <option value="{{ $key . '-' . $value['code'] }}"
                                                                    @if($studentInformation->area_code != null && $studentInformation->area_code == ($key . '-' . $value['code']))
                                                                        selected
                                                                    @endif
                                                                >{{$value['name']}} (+{{ $value['code'] }}) </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" class="form-control" value="{{ $studentInformation->phone_number }}" name="phone_number" id="phone_number" style="width: 70% !important">
                                                    </div>
                                                    <span class="text-danger" id="error_phone_number"></span>
                                                </div>
                                            </div>
                                            <div class="row my-4 d-flex align-items-center">
                                                <div class="col-3 col-sm-3 d-flex justify-content-between">
                                                    <p class="ml-3 mb-0">コイン</p>
                                                </div>
                                                <div class="col-9 d-flex justify-content-between align-items-center col-sm-6 d-sm-flex justify-content-sm-between align-items-sm-center">
                                                    <div class="col-6 col-sm-8 d-sm-flex flex-sm-column align-items-sm-start">
                                                        <p class="mb-0 total_coin">{{ $studentInformation->total_coin ?? '0' }}</p>
                                                        <span class="text-danger" id="warning-"></span>
                                                    </div>
                                                    <div
                                                        class="col-6 d-flex flex-column justify-content-center col-sm-4 d-sm-flex flex-sm-column align-items-sm-end ">
                                                        @if(in_array($studentInformation->membership_status, [ config('constants.membership.id.premium_trial'), config('constants.membership.id.premium') ]))
                                                            <a href="javascript:;" data-toggle="modal" data-target="#modalRefundCoin" style="text-decoration: underline!important">コインを返却する</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- insert the number of student's id into button below -->
                                            <div class="row my-4 d-flex align-items-center">
                                                <div class="col-sm-12 d-flex justify-content-center">
                                                    <a type="button" class="btn btn-secondary mr-2" href="{{ route('admin.student.detail',['user_id'=>$studentInformation->id]) }}">クリア</a>
                                                    <a href="javascript:;" type="button" class="btn btn-primary" id="btnUpdateProfile">更新</a>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                    </form>
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
                                    <p class="ml-4" style="word-break: break-all;">メールアドレス : <span class="ml-3"> {{$studentInformation->email}}</span></p>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-12 col-sm-12">
                                    <p class="text-center my-2">この生徒のパスワードをリセットします。よろしいでしょうか？</p>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">キャンセル</button>
                            <button type="button" class="btn btn-primary" id="btnResetPasswordStudent">リセット</button>
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
                                <h6>新しいパスワードが生徒のメールに送信されました。</h6>
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
        <div class="modal fade" id="modalRefundCoin" tabindex="-1" aria-labelledby="exampleModalLabel"
             aria-hidden="true">
            <div class="modal-dialog" style="max-width: 600px !important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">コイン返却</h5>
                        <button type="button" class="close btnCancel" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="formRefundPassword">
                            @csrf
                            <div class="row mb-2 d-flex align-items-center">
                                <div class="col-6 col-sm-4 d-sm-flex justify-content-sm-between">
                                    <p class="ml-3 mt-1 mb-0">現在のコイン数：</p>
                                </div>
                                <div class="col-6 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <p class="mb-0 total_coin" name="" id="">{{ $studentInformation->total_coin }}</p>
                                    <span class="text-danger" id=""></span>
                                </div>
                            </div>
                            <div class="row mb-2 d-flex align-items-start">
                                <div class="col-6 col-sm-4 d-flex justify-content-between">
                                    <p class="ml-3 mt-1 mb-0">返却のコイン数：</p>
                                </div>
                                <div class="col-6 col-sm-8 d-flex flex-column justify-content-center align-items-start ">
                                    <input type="text" name="theNumberOfCoin" id="theNumberOfCoin" class="w-100 form-control" value="" style="font-size: 14.4px">
                                    <span class="text-danger" id="error_theNumberOfCoin"></span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <div class="col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                            <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">キャンセル</button>
                            <button type="button" class="btn btn-primary" id="btnRefundCoin">返却</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/students/detail.js') }}"></script>
@endpush
