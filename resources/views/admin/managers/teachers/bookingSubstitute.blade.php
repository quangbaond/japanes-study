@extends('layouts.admin.app')
@section('breadcrumb')
    {{ Breadcrumbs::render('teacher_detail_bookingSubstitute' , $teacher)  }}
@endsection

@section('stylesheets')
    <meta name="route-student-validation" content="{{ route('admin.student.booking-substitute.validation') }}">
    <meta name="route-student-booking-validation"
          content="{{ route('admin.teacher.booking-substitute.validate', $teacher->id) }}">
    <meta name="route-validate-student-coin"
          content="{{ route('admin.teacher.booking-substitute.validate-student-coin', $teacher->id) }}">
    <meta name="route-booking-substitute-students" content="{{ route('student.booking-substitute.data') }}">
    <meta name="M012" content="{{ __('validation_custom.M012') }}">
    <meta name="M071" content="{{ __('validation_custom.M071') }}">
    <meta name="M074" content="{{ __('validation_custom.M074') }}">
    <meta name="route-get-lessons-by-course" content="{{route('admin.teacher.booking-substitute.get-lesson-by-course', $teacher->id)}}">
    <meta name="route-get-student-lesson-info" content="{{route('admin.teacher.booking-substitute.get-student-lesson-info', $teacher->id)}}">
@endsection
@section('content')
    <form action="{{route('student.toAdminBookingLessonList')}}" method="post" id="booking_lesson_list">
        @csrf
    </form>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-7 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">講師情報</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-4 col-sm-4">
                                    <p>ID</p>
                                    <p>メールアドレス</p>
                                    <p>ニックネーム</p>
                                </div>
                                <div class="col-1">
                                    <p>:</p>
                                    <p>:</p>
                                    <p>:</p>
                                </div>
                                <div class="col">
                                    <p>{{$teacher->id}}</p>
                                    <p>{{$teacher->email}}</p>
                                    <p>{{$teacher->nickname}}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{route('admin.student.validation')}}" method="post" id="formSearchStudent">
                        @csrf
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">生徒情報</h3>
                            </div>
                            @include('includes.admin.message_error')
                            <div class="card-body form-search-clear" id="searchStudent">
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-normal">ID</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="student_id" id="student_id">
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-normal">メールアドレス</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="student_email"
                                                   id="student_email">
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-normal">ニックネーム</label>
                                    <div class="col-sm-6">
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="student_name"
                                                   id="student_name">
                                            <span class="invalid-feedback" role="alert"></span>
                                        </div>
                                    </div>
                                </div>
                                <!-- nationality -->
                                <div class="form-group row">
                                    <label class="col-sm-4 col-form-label font-weight-normal">会社名</label>
                                    <div class="col-sm-6">
                                        <select class="form-control select2" style="width: 100%;" id="company_name">
                                            <option></option>
                                            @foreach($companies as $company)
                                                <option name="company_name"
                                                        value="{{$company->id}}">{{$company->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer w-100" style="background-color: white">
                                <div
                                    class="col-12 d-flex justify-content-end col-sm-12 d-sm-flex justify-content-sm-end">
                                    <button type="button" class="btn btn-default btn-flat" id="clearFormSearch">クリア
                                    </button>
                                    <button type="button" class="btn btn-primary ml-2 btn-flat" id="btnFormSearch">検索
                                    </button>
                                </div>
                            </div>
                            <hr>
                            <div class="card-body">
                                <div class="d-flex float-left">
                                    <section class="col-sm-12" id="error_require_choose_student">
                                    </section>
                                </div>
                                <div class="col table-responsive">
                                    <table id="students" class="table table-bordered table-hover">
                                        <thead>
                                        <tr>
                                            <th style="width: 15px">
                                            </th>
                                            <th>ID</th>
                                            <th>メールアドレス</th>
                                            <th>ニックネーム</th>
                                            <th>会社名</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-5 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">スケジュール</h3>
                        </div>
                        <div class="card-body">
                            @if($schedules === [])
                                <p>{{__('validation_custom.M011')}}</p>
                            @else
                                <div class="row">
                                    <div class="d-flex float-left">
                                        <section class="col-sm-12" id="error_section_require_schedule">
                                        </section>
                                    </div>
                                    <div class="col-12 col-sm-12">
                                        <div class="card-header d-flex justify-content-end">
                                            <div class="d-sm-flex justify-content-sm-center">
                                                <span class="badge badge-secondary mr-1" style="padding: 15px"> </span>
                                                <span class="text-center pb-0 mr-3 pt-2 "> : 不可</span>
                                            </div>
                                            <div class="d-sm-flex justify-content-sm-center">
                                                <span class="badge badge-success mr-1" style="padding: 15px"> </span>
                                                <span class="text-center pb-0 mr-3 pt-2 ">: 可能</span>
                                            </div>
                                            <div class="d-sm-flex justify-content-sm-center">
                                                <span class="badge badge-warning mr-1" style="padding: 15px"> </span>
                                                <span class="text-center pb-0 pt-2 ">: 選択中</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="table-responsive " id="tabledata">
                                    <table id="tabel-booking" class="table table-bordered table-hover">
                                        <tbody>
                                        @for($i=0; $i<7; $i++)
                                            <tr @if($date[$i]['check_exist_schedule'] == false) hidden @endif>
                                                <th hidden></th>
                                                <td style="width: 90px">
                                                    <div class="mt-2">
                                                        <span class="
                                                            @if($date[$i]['name'] == "日")
                                                            text-danger
                                                            @elseif($date[$i]['name'] == "土")
                                                            text-primary
                                                            @endif">
                                                            {{$date[$i]['month'].'/'.$date[$i]['day'].' ('.$date[$i]['name'].')'}}
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex justify-content-between">
                                                        <div class="row ml-2" id="row{{$i}}">
                                                            @foreach($schedules as $schedule)
                                                                @if($schedule->start_date==$date[$i]['full'])
                                                                    <div class="d-flex">
{{--                                                                        $schedule->start_date == Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d') &&--}}
                                                                        @if(!\Carbon\Carbon::parse($schedule->start_date.' '.$schedule->start_hour)->gte(\Carbon\Carbon::parse(Timezone::convertToLocal(\Carbon\Carbon::now()->addMinute('30'), 'Y-m-d H:i:00'))) || $schedule->deleted_at != null)
                                                                            <button name="{{$schedule->start_date}}"
                                                                                    class="bs-timepicker btn btn-secondary mr-lg-2 mb-2 mt-1 text-center"
                                                                                    style="width: 65px; height: 40px"
                                                                                    value=""
                                                                                    id="{{$schedule->id}}"
                                                                                    disabled>{{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}</button>
                                                                        @else
                                                                            @if($schedule->status == 3)
                                                                                <button name="{{$schedule->start_date}}"
                                                                                        class="bs-timepicker btn btn-success mr-lg-2 mb-2 mt-1 text-center"
                                                                                        style="width: 65px; height: 40px"
                                                                                        value="{{$schedule->id}}"
                                                                                        id="{{$schedule->id}}"
                                                                                        readonly>{{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}</button>
                                                                            @else
                                                                                <button name="{{$schedule->start_date}}"
                                                                                        class="bs-timepicker btn btn-secondary mr-lg-2 mb-2 mt-1 text-center"
                                                                                        style="width: 65px; height: 40px"
                                                                                        value="{{$schedule->id}}"
                                                                                        id="{{$schedule->id}}"
                                                                                        disabled>{{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}</button>
                                                                            @endif
                                                                        @endif
                                                                    </div>
                                                                @endif
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endfor
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                        <div class="card-footer text-right">
                            <form action="" method="post" id="validateBookLesson">
                                @csrf
                                <button type="button" class="btn btn-secondary mr-2 btn-flat btnCancel"
                                        id="btnCancelBooking">クリア
                                </button>
                                <button type="button" id="validate" class="btn btn-flat btn-primary">予約代行</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade " id="modalConfirm-Booking" style="overflow: auto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="border-0 px-4 py-2">
                    <h4 class="modal-title font-weight-bold ">予約確認</h4>
                    <hr class="" style="border-color :  #ccc; margin :10px 0"/>
                    <div class="float-left">
                        <section class="col-sm-12 pl-0" id="error_section_confirm">
                        </section>
                        <div class="font-weight-bold">
                            <p>選択したスケジュール情報が正しいかどうか、ご確認ください。</p>
                        </div>
                    </div>
                    <br>
                </div>
                <div class="modal-body">
                    <div class="table-responsive " id="tabledata">
                        <table id="" class="table table-bordered table-hover">
                            <tbody id="confirm">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-secondary btn-flat btnCancel" data-dismiss="modal"
                            data-target="#modalConfirm-Booking"
                            id="btnCancelConfirm">キャンセル
                    </button>
                    <button type="button" class="btn btn-primary btn-flat" id="btnConfirm">
                        確認
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalNotificationWhenLackOfCoin" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center my-3">
                        <h6>{{ __('validation_custom.M055')}}</h6>
                    </div>
                    <div class="col-12 d-flex justify-content-center col-sm-12 d-sm-flex justify-content-sm-center">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">確認
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lesson" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;!important" role="document">
            <div class="modal-content">
                {{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
                {{--                        <span aria-hidden="true">&times;</span>--}}
                {{--                    </button>--}}
                <div class="border-0 px-4 py-2">
                    <h4 class="modal-title font-weight-bold">コース選択</h4>
                    <hr class="" style="border-color : #ccc; margin :10px 0"/>
                    <div class="d-flex float-left" id="modal_lesson_title">
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>コース名</label>
                        <select class="form-control" id="course_id_select" name="course_id_select">
{{--                            @if(!$checkTeacherCanTeach)<option selected id="course_empty"></option>@endif--}}
                            @foreach($teacher_courses as $course)
                                <option value="{{$course->course_id}}">
                                    {{$course->course_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>レッスン名</label>
                        <select class="form-control" name="lesson_id_select" id="lesson_id_select">
{{--                            @foreach($lessons as $lesson)--}}
{{--                                <option value="{{$lesson->id}}" @if($student_last_lesson->lesson_id === $lesson->id) selected @endif>--}}
{{--                                    {{$lesson->name}}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12">
                        <form id="form-get-lesson">
                            <button type="button" class="btn btn-primary btn-sm float-right" id="btnChooseLesson">確認</button>
                            <button type="button" class="btn btn-default btn-sm float-left" data-dismiss="modal" id="btnCancelChooseLesson">キャンセル</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('title_screen', 'レッスン予約代行')
@push('scripts')
    <script src="{{ asset('js/admin/managers/teachers/bookingSubstitute.js') }}"></script>
@endpush
