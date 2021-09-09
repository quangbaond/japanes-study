@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('notification_detail') }}
@endsection

@section('stylesheets')
    <meta name="notification-validate" content="{{route('admin.notification.detail.validation', $notification->id)}}">
    <meta name="notification-update" content="{{route('admin.notification.detail.update', $notification->id)}}">
@endsection

@section('title_screen', '通知詳細')

@section('content')
    <div class="content">
        <div class="container-fluid">
            <div class="card card-info">
                <form action="" method="post" id="formUpdateNotification">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">送信対象者</label>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input name="" type="radio" class="m-2 general" id="general"
                                                   @if($notification->receiver_class == 1) checked
                                                   @else disabled @endif value="{{$notification->receiver_class}}">
                                            <label for="general" class="m-1">共通</label>
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input name="" type="radio" class="m-2 general" id="teacher"
                                                   @if($notification->receiver_class == 2) checked
                                                   @else disabled @endif value="{{$notification->receiver_class}}">
                                            <label for="teacher" class="m-1">講師のみ</label>
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="input-group">
                                            <input name="" type="radio" class="m-2 people" id="student"
                                                   @if($notification->receiver_class == 3) checked
                                                   @else disabled @endif value="{{$notification->receiver_class}}">
                                            <label for="student" class="m-1">生徒のみ</label>
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                    <div class="col-sm-3">
                                        <div class="input-group">
                                            <input name="" type="radio" class="m-2 people" id="people"
                                                   @if($notification->receiver_class == 4) checked
                                                   @else disabled @endif value="{{$notification->receiver_class}}">
                                            <label for="people" class="m-1">個人のユーザに送信</label>
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row @if($notification->receiver_class == 4) d-none @endif">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">表示期間</label>
                                    <div class="col-sm-9 row">
                                        <div class="input-group col-sm-5">
                                            <input type="text" name="from_date" id="from_date"
                                                   class="form-control datepicker"
                                                   style="background-color: white"
                                                   @if($notification->receiver_class == 4) disabled @endif
                                                   value="@if(!is_null($notification->start_date)) {{\Carbon\Carbon::parse($notification->start_date)->format('Y/m/d')}} @endif">
                                            <div class="input-group-append">
                                                <div class="input-group-text" style="max-height: 38px!important;"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            <span class="invalid-feedback-custom" id="format_date_from"></span>
                                        </div>
                                        <div class="input-form col-lg-2 text-center" style="max-height: 30px;">
                                            <h2>~</h2>
                                        </div>
                                        <div class="input-group col-sm-5">
                                            <input type="text" name="to_date" id="to_date"
                                                   class="form-control datepicker"
                                                   style="background-color: white"
                                                   @if($notification->receiver_class == 4) disabled @endif
                                                   value="@if(!is_null($notification->end_date)) {{\Carbon\Carbon::parse($notification->end_date)->format('Y/m/d')}} @endif"/>
                                            <div class="input-group-append">
                                                <div class="input-group-text" style="max-height: 38px!important;"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            <span class="invalid-feedback-custom" id="format_date_to"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row @if($notification->receiver_class != 4) d-none @endif people-form">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="address" class="col-sm-2 col-form-label">宛名</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <select id="user_email" multiple="multiple"
                                                    class="bg-white form-control w-100 select2"
                                                    name=""
                                                    @if($notification->receiver_class == 4) disabled @endif >
                                                @foreach($users as  $user)
                                                    <option name="user_email[]" value="{{$user->email}}"
                                                            @if(!is_null($user->id))
                                                            selected="selected"
                                                        @endif
                                                    >{{$user->email}}</option>
                                                @endforeach
                                            </select>
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">タイトル</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <textarea type="text" id="title" style="background-color: white" rows="1"
                                                      class="form-control" name="title"
                                                      @if($notification->receiver_class == 4) readonly @endif>@php echo $notification->title @endphp</textarea>
                                            <span class="invalid-feedback" role="alert" id="title_err"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="content" class="col-sm-2 col-form-label">内容</label>
                                    <div class="col-sm-9">
                                        <div class="input-group">
                                            <textarea type="text" style="background-color: white" id="content" rows="10"
                                                      class="form-control" name="content"
                                                      @if($notification->receiver_class == 4) readonly @endif>@php echo $notification->content @endphp</textarea>
                                            <span class="invalid-feedback" role="alert" id="content_err"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right @if($notification->receiver_class == 4) d-none @endif">
                            <button type="button" id="btnClear" class="btn btn-secondary btn-flat">クリア</button>
                            <button type="button" id="btnSubmit" class="btn btn-primary btn-flat">更新</button>
                        </div>
                    </div>
                </form>
                <form action="{{route('admin.notification.to-list-notifications', $notification->id)}}" method="post" id="to_list_notification">
                    @csrf
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/notification/detail.js') }}"></script>
    <script>
        $('textarea').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        })
    </script>
@endpush
