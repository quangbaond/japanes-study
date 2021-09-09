@extends('layouts.admin.app')
@section('stylesheets')
    <link href="{{ asset('css/admin/teachers/timepicker.min.css') }}" rel="stylesheet">
    <meta name="removeImage" content="{{ asset('images/remove.png') }}">
    <meta name="M007" content="{{ __('validation_custom.M007') }}">
    <meta name="routeValidateSchedule" content="{{ route('teacher.validateSchedule') }}">
@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('add_schedule') }}
@endsection
@section('title_screen', 'スケジュール追加')
@section('content')
    <form action="{{route('teacher.toListSchedule')}}" method="post" id="listSchedule">
        @csrf
    </form>
    <section class="content">
        <div class="container-fluid d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body table-responsive">
                                <section class="col-sm-12" id="error_section">
                                </section>
                                <table id="table_add_schedule" class="table table-bordered table-hover">
                                    <tbody>
                                    @if(isset($date))
                                        @for($i =0; $i<7; $i++)
                                            @if($date[$i]!=[])
                                                <tr>
                                                    <th hidden></th>
                                                    <td style="width: 100px; @if($date[$i]['name'] == '土')
                                                        color:blue;
                                                    @elseif($date[$i]['name'] == '日')
                                                        color:red;
                                                    @endif">
                                                        <div class="mt-2">
                                                            <span>{{$date[$i]['month'].'/'.$date[$i]['day']}}</span>
                                                            <span>({{$date[$i]['name']}})</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-between mt-1 addButton"
                                                             id="divRow{{$i+1}}">
                                                            <div class="row" id="row{{$i+1}}" style="width: 95%">
                                                                <div class="d-flex" id="divTimepicker{{$i+1}}-0">
                                                                    <div class="mb-2 mr-2"
                                                                         id="removeTimepicker{{$i+1}}-0">
                                                                        <img class="removeTimepicker position-absolute"
                                                                             style="width: 15px; height: 15px"
                                                                             src="{{asset('images/remove.png')}}"
                                                                             id="{{$i+1}}-0">
                                                                    </div>
                                                                    <input type="text"
                                                                           id="timepicker{{$i+1}}-0"
                                                                           class="bs-timepicker mr-lg-5 mb-3 mt-1 border border-dark text-center timepicker{{$i+1}}"
                                                                           style="width: 65px" value="" readonly>
                                                                </div>
                                                            </div>
                                                            <div class="float-right">
                                                                <button class="btn btn-primary btnAddTimePicker"
                                                                        id="button{{$i+1}}">
                                                                    <i class="fas fa-plus"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endfor
                                    @endif
                                    </tbody>
                                </table>
                                @if(!isset($notification))
                                    <div class="form-group row mb-0">
                                        <label class="col-sm-6 col-form-label" style="font-weight: normal">一週間分のスケジュールを登録することが出来ます。</label>
                                    </div>
                                    <div class="float-right">
                                        <button class="btn btn-default mr-4" id="btnClear">クリア</button>
                                        <button class="btn btn-primary" id="btnSubmit" @if((is_null($teacher_zoom->link_zoom) || $teacher_zoom->link_zoom ==='') && $teacher_zoom->role == 2) disabled @endif>登録</button>
                                    </div>
                                @else
                                    <div class="form-group row mb-0">
                                        <label class="col-form-label"
                                               style="font-weight: normal">{{$notification}}</label>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/teachers/addSchedule.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/timepicker_teacher_addSchedule.js') }}"></script>
@endpush
