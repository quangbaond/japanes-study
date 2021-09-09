@extends('layouts.student.app')
@section('admin_title')

@endsection
@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('toastr/build/toastr.css') }}"/>
    <meta name="message-no-data" content="{{__('student.no_data')}}">
    <meta name="the-number-of-record" content="{{count($data['booking'])}}">
    <meta name="route-check-time" content="{{ route('student.removeBooking.check-time') }}">
    <meta name="route-get-course-can-teach" content="{{ route('student.lesson.list.getCourse') }}">
    <meta name="route-update-lesson-booked" content="{{ route('student.lesson.list.update') }}">
    <meta name="text-document" content="{{ __('courses.document') }}">
    <style>
    .ellipsis {
        max-width: 100px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    table{
        border-collapse: separate !important;
        border-spacing: 0;
    }
    td {
        max-width: 400px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    thead > tr > th {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
        width: 50px;
    }
    @media only screen and (min-width: 786px) {
        .intro-video {
            width: 1037px;
            height: 500px;
        }
    }
    @media only screen and (min-width: 601px) and (max-width: 785px) {
        .intro-video {
            width: 650px;
            height: 400px;
        }
    }
    @media only screen and (max-width: 600px) {
        .intro-video {
            width: 320px;
            height: 200px;
        }
    }
</style>
@endsection
@section('panel')
    @include('includes.student.panel')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="card w-100">
            <div class="card-header">
                <h3 class="text-bold card-title">{{__('student.booking_list')}}</h3>
            </div>
                <div class="card-body ">
                    <div class="row">
                        <div class="col-12">
                            <p class="d-none "id="no-data"></p>
                        @if(count($data['booking']) <= 0)
                                <p class="mb-0">{{__('student.no_data')}}</p>
                        @else
                            <p class="text-right" id="the_number_of_records">{{count($data['booking'])}} {{__('student.record')}}@if(count($data['booking']) > 1 && Config::get('app.locale') =='en')s @endif</p>
                            <div class="table-responsive">

                                <table style="border:none;"  class="w-100 booking-list table table-bordered table-hover border-right" id="booking_list">
                                    <thead class="w-100">
                                    <tr>
                                        <th hidden></th>
                                        <th>{{__('student.date')}}</th>
                                        <th>{{__('student.time')}}</th>
                                        <th>{{__('student.teacher_id')}}</th>
                                        <th>{{__('student.nickname')}}</th>
                                        <th>{{__('student.email')}}</th>
                                        <th>{{__('student.coin')}}</th>
                                        <th> {{ __('courses.course') }} </th>
                                        <th colspan="2"> {{ __('courses.lesson') }} </th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody style="height: 10px !important; overflow: scroll; ">
                                    @php sort($data['start_date']) @endphp
                                    @foreach($data['start_date'] as $key => $startDate)
                                        @php $i = 0; @endphp
                                        @php $first = true; @endphp
                                        @php \App\Helpers\Helper::osort($data['booking'] , array('start_hour' => SORT_ASC)) @endphp
                                        @foreach($data['booking'] as $key => $booking)
                                            @if($booking->start_date == $startDate)
                                                 @php $i++ @endphp
                                            @endif
                                        @endforeach
                                        <tr>
                                            @foreach($data['booking'] as $key => $booking)
                                                @php $date = date_create($booking->start_hour); @endphp

                                                @if($first == true )
                                                    <td class="{{ \App\Helpers\Helper::getDate($startDate)['class'] }} date" data-start_date="{{$startDate}}"
                                                        rowspan="{{ $i + 1 }} "
                                                        data-datetime="{{ \App\Helpers\Helper::getDate($startDate)['day'] }}/{{ \App\Helpers\Helper::getDate($startDate)['month'] }}/{{ \App\Helpers\Helper::getDate($startDate)['year'] }}({{ \App\Helpers\Helper::getDate($startDate)['week'] }})">
                                                        {{ \App\Helpers\Helper::getDate($startDate)['day'] }}/
                                                        {{ \App\Helpers\Helper::getDate($startDate)['month'] }}
                                                        ({{ \App\Helpers\Helper::getDate($startDate)['week'] }})
                                                    </td>
                                                    @php ($first = false) @endphp
                                                @endif
                                                @if($booking->start_date == $startDate)
                                                    <tr>
                                                        <td hidden id="row-{{ $booking->id_booking }}"></td>
                                                        <td class="time"
                                                        data-start_hour="{{date_format($date , 'H:i')}}">{{date_format($date , 'H:i')}}</td>
                                                        <td class=''>{{$booking->teacher_id}}</td>
                                                        <td class="teacherName ellipsis"> {{$booking->nickname}}</td>
                                                        <td class="ellipsis"> {{$booking->email}}</td>
                                                        <td class="coin">{{$booking->coin_teacher}}</td>
                                                        <td id="course_name">{{$booking->course_name}}</td>
                                                        <td id="lesson_name">{{$booking->lesson_name}}</td>
                                                        <td class="">
                                                            <button  class="btn btnChangeLesson py-1 mb-0 pt-2 btn-primary" @if(((date('Y-m-d', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d H:i:s'))) == $booking->start_date) && date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now()->addHour(1), 'Y-m-d H:i:s'))) > $booking->start_hour) ) disabled @endif data-teacher_schedule_id="{{$booking->teacher_schedule_id}}" data-teacher_id="{{$booking->teacher_id}}" data-lesson_id="{{ $booking->lesson_id }}" data-course_id="{{ $booking->course_id }}" style="padding: 1px 10px"> {{ __('courses.edit') }} </button>
                                                        </td>
                                                        <td class="d-flex d-sm-flex justify-content-sm-center">
                                                            <div class="openTabPdfField mx-1">
                                                                @if(empty($booking->text_link))
                                                                    <a href="javascript:;   " class="btn py-1 mb-0 pt-2" style="background-color: #F6B352; padding: 1px 10px" disabled> {{ __('courses.document') }} </a>
                                                                @else
                                                                    <a class=" btn btnNewTagPdf py-1 mb-0 pt-2" href="{{$booking->text_link}}" target="_blank" style="background-color: #F6B352; padding: 1px 10px">{{ __('courses.document') }}</a>
                                                                @endif
                                                            </div>
                                                            <div class="showVideoField mx-1">
                                                                @if(empty($booking->text_link))
                                                                    <a href="javascript:;   " class="btn py-1 mb-0  pt-2" style="background-color: #F68657; padding: 1px 10px" disabled> Video </a>
                                                                @else
                                                                    <a class="btn btnShowVideo py-1 mb-0 pt-2" href="javascript:;" data-video_link="{{$booking->video_link}}" style="background-color: #F68657; padding: 1px 10px">Video</a>
                                                                @endif
                                                            </div>
                                                            <button type="button" class="btn btn-success  mx-1 startLesson"
                                                                    @if(!(date('Y-m-d', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d H:i:s'))) == $booking->start_date && (( date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now()->subMinute(1), 'Y-m-d H:i:s'))) <= $booking->start_hour && date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now()->addMinute(5), 'Y-m-d H:i:s'))) >= $booking->start_hour) ||  ( date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now()->addMinute(1), 'Y-m-d H:i:s'))) >= $booking->start_hour && date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now()->subMinute(11), 'Y-m-d H:i:s'))) <= $booking->start_hour)) ) )
                                                                        disabled
                                                                    @endif
                                                                    id="btnCreateTeacher"
                                                                    data-route_booked_confirm="{{ route('student.book-schedule',['id' =>  $booking->teacher_id]) }}"
                                                                    data-route_booked="{{ route('student.push-notification-to-teacher-when-start',['id' =>  $booking->teacher_id]) }}"
                                                                    data-route_canceled="{{ route('student.push-notification-to-teacher-when-close',['id' =>  $booking->teacher_id]) }}"
                                                                    data-start_date="{{$booking->start_date}}"
                                                                    data-start_hour={{$booking->start_hour}} data-idbooking="{{$booking->id_booking}}"
                                                                    data-lesson_id="{{ $booking->lesson_id }}"
                                                                    data-course_id="{{ $booking->course_id }}"
                                                                    data-coin="{{ $booking->coin_teacher }}"
                                                                    >{{__('student.start_lesson')}}</button>
                                                            <button type="button"
                                                                    class="btn btn-default cancelBooking mx-1"
                                                                    data-toggle="modal"
                                                                    data-target="#modal-removeBooking"
                                                                    data-coin="@if(((date('Y-m-d', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now(), 'Y-m-d H:i:s'))) == $booking->start_date) && date('H:i:s', strtotime(Timezone::convertToLocal(\Carbon\Carbon::now()->addHour(1), 'Y-m-d H:i:s'))) > $booking->start_hour) ) {{'0'}} @else {{$booking->coin_teacher}} @endif"
                                                                    data-teacherName="{{$booking->nickname}}"
                                                                    data-date="{{ $booking->start_date }}"
                                                                    data-start_date="{{date('d-m-Y', strtotime($booking->start_date))}}"
                                                                    data-start_hour="{{date_format($date , 'H:i')}}" data-idbooking="{{$booking->id_booking}}">
                                                                    {{__('student.cancel')}}
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{--    set value to send to teacher --}}
    <div class="d-none">
        <input type="hidden" value="" id="start_hour">
        <input type="hidden" value="" id="start_date">
        <input type="hidden" value="" id="coin">
        <input type="hidden" value="" id="lesson_number">
        <input type="hidden" value="" id="course_number">
        <input type="hidden" value="{{ Auth::id() }}" id="student_id">
        <input type="hidden" value="2" id="type">
        <input type="hidden" value="2" id="book_type">
    </div>
    <!-- modal-->
    <div class="modal fade" id="modal-removeBooking" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('student.cancel_booking')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mx-2">
                        <div class="col-5 my-1">
                            <p>{{__('student.teacher_name')}}</p>
                            <p>{{__('student.date')}}</p>
                            <p>{{__('student.time')}}</p>
                            <p>{{__('student.refund_coin')}}</p>
                        </div>
                        <div class="col-1 my-1">
                            <p>:</p>
                            <p class="pt-md-0">:</p>
                            <p>:</p>
                            <p class="pt-md-0">:</p>
                        </div>
                        <div class="col-6 my-2">
                            <p class="pt-md-0 ellipsis"id="teacherName"></p>
                            <p id="date"></p>
                            <p id="time"></p>
                            <p id="coin_techer"></p>
                        </div>
                    </div>
                    <p class="text-center">{{__('student.confirm_cancel_booking')}}</p>
                </div>
                <div class="modal-footer text-center justify-content-center">
                    <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{ __('student.no') }}</button>
                    <button type="button" data-idbooking="" data-start_date="" data-start_hour="" data-coin=""
                            class="btn btn-primary" id="btnConfirmRemove" data-date="">{{__('student.yes')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="modalConfirmRemoveBooking" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{__('student.cancel_booking')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row mx-2">
                        <div class="col-12">
                            <input type="hidden" value="" id="booking_id"/>
                            <p class="text-center">{{__('student.refund_coin')}}: 0</p>
                            <p class="text-center">{{__('student.confirm_cancel_booking')}}</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer text-center justify-content-center">
                    <button type="button" class="btn btn-secondary mr-2 btnCancel" data-dismiss="modal">{{ __('student.no') }}</button>
                    <button type="button" data-idbooking="" data-start_date="" data-start_hour="" data-coin=""
                            class="btn btn-primary" id="btnConfirmRemoveStep2" data-date="">{{__('student.yes')}}
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="modalShowVideo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 1100px!important;">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex justify-content-center">
                </div>
            </div>
        </div>
    </div>
    {{--    add modalChangeLesson --}}
    <div class="modal fade" id="modalChangeLesson" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;!important" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('courses.title_selection') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-0">
                        <label>{{ __('courses.note_selection') }}</label>
                    </div>
                    <div class="form-group">
                        <label>{{ __('courses.course') }}</label>
                        <select class="form-control" id="course_id_select" name="course_id_select">
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('courses.lesson') }}</label>
                        <select class="form-control" name="lesson_id_select" id="lesson_id_select">
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12">
                        <form id="form-get-lesson">
                            <button type="button" class="btn btn-primary btn-sm float-right" id="btnUpdateLesson">{{ __('courses.btn_update') }}</button>
                            <button type="button" class="btn btn-default btn-sm float-left" data-dismiss="modal" id="btnCancelChooseLesson">{{ __('courses.btn_cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('toastr/toastr.js') }}"></script>
    <script src="{{ asset('js/admin/students/lessonBooking.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
    </script>
@endpush
