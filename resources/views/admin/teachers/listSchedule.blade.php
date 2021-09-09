@extends('layouts.admin.app')
@section('stylesheets')
    <link href="{{ asset('css/admin/teachers/timepicker.min.css') }}" rel="stylesheet">
    <meta name="removeImage" content="{{ asset('images/remove.png') }}">
    <meta name="route-validate-time" content="{{ route('teacher.listSchedule.validation') }}">
    <meta name="distance" content="{{$distance}}">
@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('list_schedule') }}
@endsection
@section('title_screen', 'スケジュール一覧')
@section('content')
    <section class="content">
        <div class="container-fluid d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        {!! Form::open(array('route' => 'teacher.listSchedule','method'=>'GET', 'id' => 'formSchedule')) !!}
                            <section id="success_section">
                                @if(Session::has('success_schedule'))
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <i class="icon fa fa-check"></i>
                                        <span id="error_mes">{{Session::get('success_schedule')}}</span>

                                    </div>
                                @endif
                            </section>
                            <div class="card">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12 d-flex">
                                            <div class="col-8">
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">日付</label>
                                                    <div class="col-sm-8 row">
                                                        <div class="input-group col-sm-5">
                                                            <input type="text" name="from_date" value="@if(old('from_date') != ''){{old('from_date')}} @elseif(request()->input('from_date') !== null) {{request()->input('from_date')}} @elseif(isset($_GET['from_date'])) {{request()->input('from_date')}} @else {{ $time }} @endif" id="from_date" class="form-control datepicker" autocomplete="off"/>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><i id="icon_from_date" class="fa fa-calendar"></i></div>

                                                            </div>
                                                            <!--<div class="input-group-text"><i class="fas fa-times"></i></div>-->
                                                        </div>
                                                        <div class="input-form col-sm-2" style="text-align: center; max-height: 30px;">
                                                            <h2>~</h2>
                                                        </div>
                                                        <div class="input-group col-sm-5">
                                                            <input type="text" name="to_date" value="@if(old('to_date') != ''){{old('to_date')}} @elseif(request()->input('to_date') !== null) {{request()->input('to_date')}} @elseif(isset($_GET['to_date'])) {{request()->input('to_date')}} @else {{ $time_next }} @endif" id="to_date" class="form-control datepicker @error('to_date') is-invalid @enderror" autocomplete="off"/>
                                                            <div class="input-group-append">
                                                                <div class="input-group-text"><i id="icon_to_date" class="fa fa-calendar"></i></div>
                                                            </div>
                                                        </div>
                                                        @error('to_date')<span class="ml-2 invalid-feedback-custom text-danger">{{$message}}</span>@enderror
                                                        @error('from_date')<span class="ml-2 invalid-feedback-custom text-danger">{{$message}}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label">時間帯</label>
                                                    <div class="col-sm-8 row">
                                                        <div class="input-group col-sm-5">
                                                            <input type="text" name="from_time" id="from_time" class="input-timepicker form-control " value="@if(old('from_time') != ''){{old('from_time')}} @elseif(request()->input('from_time') != '') {{request()->input('from_time')}} @endif" autocomplete="off"/>
                                                        </div>
                                                        <div class="input-form col-sm-2" style="text-align: center; max-height: 30px;">
                                                            <h2>~</h2>
                                                        </div>
                                                        <div class="input-group col-sm-5">
                                                            <input type="text" name="to_time" id="to_time" class="input-timepicker form-control @error('to_time') is-invalid @enderror" value="@if(old('to_time') != ''){{old('to_time')}} @elseif(request()->input('to_time') != '') {{request()->input('to_time')}} @endif" autocomplete="off"/>
                                                        </div>
                                                        @error('to_time')<span class="ml-2 invalid-feedback-custom text-danger">{{$message}}</span>@enderror
                                                        @error('from_time')<span class="ml-2 invalid-feedback-custom text-danger">{{$message}}</span>@enderror
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <label class="col-sm-3 col-form-label ">予約状態</label>
                                                    <div class="col-sm-7 mt-2 d-sm-flex">
                                                        <div class="col-sm-4">
                                                            <input class="form-group mr-1" type="radio" value="4" id="allradio" name="status" @if(old('status') == 4 || request()->input('status') == 4 || request()->input('status') == '') checked @endif  >
                                                            <span class="pr-4">全て</span>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input class="form-group mr-1" type="radio" value="2" name="status"  @if(old('status') == 2 || request()->input('status') == 2) checked @endif>
                                                            <span class="pr-4">予約中</span>
                                                        </div>
                                                        <div class="col-sm-4">
                                                            <input class="form-group mr-1" type="radio" value="3" name="status" @if(old('status') == 3 || request()->input('status') == 3) checked @endif>
                                                            <span>予約なし</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="float-right mt-2" id="">
                                        <button type="button" class="btn btn-default mr-4"
                                                id="btnClear" data-url="{{ route('teacher.listSchedule') }}">
                                            クリア
                                        </button>
                                        <button type="button" class="btn btn-primary"
                                                id="btnSearch">
                                            検索
                                        </button>
                                    </div>
                                </div>
                            </div>
                        {!! Form::close() !!}
                        <section class="col-sm-12" id="error_section">

                        </section>
                        <div class="card">
                            <div class="card-header d-flex flex-column d-sm-flex flex-sm-row align-items-sm-center w-100 w-sm-100">
                                <div class="row w-100 ml-0 ml-sm-0">
                                        <div class="col-12 col-sm-6 d-flex d-sm-flex justify-content-sm-start">
                                            <div class="d-flex justify-content-center">
                                                <span class="badge badge-secondary mr-1 mt-1" style="padding: 15px">     </span>
                                                <span class="text-center pb-0 mr-lg-5 mt-2">実施済み</span>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <span class="badge badge-warning mr-1 mt-1" style="padding: 15px">     </span>
                                                <span class="text-center pb-0 mr-lg-5 mt-2">予約中</span>
                                            </div>
                                            <div class="d-flex justify-content-center">
                                                <span class="badge badge-success mr-1 mt-1" style="padding: 15px">     </span>
                                                <span class="text-center pb-0 mt-2">予約なし</span>
                                            </div>
                                        </div>
                                        <div class="col-12 col-sm-6 d-flex d-sm-flex justify-content-sm-end pr-0 mt-2 mt-sm-0" id="button_change">
                                            <div class="d-flex d-sm-flex justify-content-sm-end">
                                                <a href="{{route('teacher.addSchedule')}}">        <button  class="btn btn-success" id="btnAdd" @if((is_null($teacher_zoom->link_zoom) || $teacher_zoom->link_zoom ==='') && $teacher_zoom->role == 2) disabled @endif>スケジュール追加</button></a>
                                                <button class="btn btn-primary float-right ml-2 mr-0" id="changeButton" @if((is_null($teacher_zoom->link_zoom) || $teacher_zoom->link_zoom ==='') && $teacher_zoom->role == 2) disabled @endif @if(!$numOfSchedule) disabled @endif>入力可能モード</button>
                                            </div>
                                        </div>
                                </div>
                            </div>
                                <div class="card-body table-responsive " id="tabledata">
                                    @if($numOfSchedule)
                                    <table id="table_list_schedule" class="table table-bordered table-hover">
                                        <tbody>
                                        @if(isset($date))
                                            @for($i =0; $i < $diff; $i++)
                                                <tr class="@if(!$date[$i]['check_isset']) d-none @endif">
                                                    <th hidden></th>
                                                    <td style="width: 100px;">
                                                        <div class="mt-2 @if($date[$i]['name'] == '土') text-primary @elseif($date[$i]['name'] == '日') text-danger @endif ">
                                                            <span>{{$date[$i]['month'].'/'.$date[$i]['day']}}</span>
                                                            <span>({{$date[$i]['name']}})</span>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="d-flex justify-content-between mt-1 addButton" @if(\Carbon\Carbon::parse($time)->lte(\Carbon\Carbon::parse($date[$i]['year'].'/'.$date[$i]['month'].'/'.$date[$i]['day'])) && \Carbon\Carbon::parse($time_next)->gte(\Carbon\Carbon::parse($date[$i]['year'].'/'.$date[$i]['month'].'/'.$date[$i]['day']))) id="divRow{{$i+1}}" @endif data-time="{{$date[$i]['time']}}" data-total="{{sizeof($schedules[$i])}}" >
                                                            @php
                                                                $s = 0;
                                                                if(strtotime(Timezone::convertToLocal(\Carbon\Carbon::now(),'Y/m/d')) > strtotime($date[$i]['time'])){
                                                                    $s = 1;
                                                                }
                                                            @endphp
                                                            <div class="row" id="row{{$i+1}}" style="width: 95%" data-disable="{{$s}}">
                                                                @for($j =0 ; $j<sizeof($schedules[$i]); $j++)
                                                                    @php
                                                                        $string = Carbon\Carbon::parse($schedules[$i][$j]->start_hour)->format('H:i');
                                                                        $tmp = explode(':', $string);
                                                                        $time =  (int)($tmp[0]*3600 + $tmp[1]*60);
                                                                        $time_s = Timezone::convertToLocal(\Carbon\Carbon::now(),'H:i');
                                                                        $tmp = explode(':', $time_s);
                                                                        $time_s =  (int)($tmp[0]*3600 + $tmp[1]*60);
                                                                        $check_time = $time - $time_s;
                                                                        $t2 = Timezone::convertToLocal(\Carbon\Carbon::now(),'Y-m-d');
                                                                        $t1 = $schedules[$i][$j]->start_date;
                                                                        $diff_day = date_diff(date_create($t1), date_create($t2));
                                                                        $day = $diff_day->days;

                                                                        $disabled = false;
                                                                        if(strtotime(Timezone::convertToLocal(\Carbon\Carbon::now(),'Y-m-d')) > strtotime($schedules[$i][$j]->start_date)){
                                                                            $disabled = true;
                                                                        }
                                                                        if($day == 0){
                                                                            if($check_time < $constant_time){
                                                                                $disabled = true;
                                                                            }
                                                                        }

                                                                    @endphp
                                                                    @if($schedules[$i][$j]->status == 3)
                                                                        <div class="d-flex" id="divTimepicker{{$i+1}}-{{$j}}">
                                                                            <div class="mb-2 mr-2" @if($disabled == false) id="removeTimepicker{{$i+1}}-{{$j}}" @endif></div>
                                                                            <input type="text" id="timepicker{{$i+1}}-{{$j}}" class="@if($disabled == false) bs-timepicker @endif btn-success mr-lg-5 mb-2 mt-1 text-center timepicker{{$i+1}} timpicker-exits" data-time="{{ Carbon\Carbon::parse($schedules[$i][$j]->start_date)->format('Y-m-d') }}" data-id="{{$schedules[$i][$j]->id}}" style="width: 65px;height: 28px;" data-value="{{  Carbon\Carbon::parse($schedules[$i][$j]->start_hour)->format('H:i') }}" value="{{  Carbon\Carbon::parse($schedules[$i][$j]->start_hour)->format('H:i') }}" disabled>
                                                                        </div>
                                                                    @elseif($schedules[$i][$j]->status == 1)
                                                                        <div class="d-flex" id="divTimepicker{{$i+1}}-{{$j}}">
                                                                            <div class="mb-2 mr-2"></div>
                                                                            <input type="text" id="timepicker{{$i+1}}-{{$j}}" class="btn-secondary mr-lg-5 mb-2 mt-1  text-center  timepicker{{$i+1}} timpicker-exits" data-time="{{ Carbon\Carbon::parse($schedules[$i][$j]->start_date)->format('Y-m-d') }}" data-id="{{$schedules[$i][$j]->id}}" style="width: 65px;height: 28px;" value="{{ Carbon\Carbon::parse($schedules[$i][$j]->start_hour)->format('H:i') }}" disabled>
                                                                        </div>
                                                                    @else
                                                                        <div class="d-flex" id="divTimepicker{{$i+1}}-{{$j}}">
                                                                            <div class="mb-2 mr-2"></div>
                                                                            <input type="text" id="timepicker{{$i+1}}-{{$j}}" class=" btn-warning mr-lg-5 mb-2 mt-1  text-center  timepicker{{$i+1}} timpicker-exits" data-time="{{ Carbon\Carbon::parse($schedules[$i][$j]->start_date)->format('Y-m-d') }}" data-id="{{$schedules[$i][$j]->id}}" style="width: 65px;height: 28px;" value="{{ Carbon\Carbon::parse($schedules[$i][$j]->start_hour)->format('H:i') }}" disabled>
                                                                        </div>
                                                                    @endif
                                                                @endfor
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endfor
                                        @endif
                                        </tbody>
                                    </table>

                                    <div class="float-right mt-2" id="divSave"></div>
                                    <div class="float-right mt-2" id="divCancel"></div>
                                    @else
                                        <div class="form-group row mb-0">
                                            <label class="col-sm-6 col-form-label" style="font-weight: normal">{{__('validation_custom.M011')}}</label>
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
    <script src="{{ asset('js/admin/teachers/listSchedule.js') }}"></script>
    <script src="{{ asset('js/admin/teachers/timepicker_teacher_listSchedule.js') }}"></script>
@endpush
