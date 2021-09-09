@extends('layouts.student.app')
@section('admin_title')
    {{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
    <meta name="route-student-search" content="{{ route('student.validation') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('css/admin/teachers/timepicker.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/student/home.css') }}">
    <style>
        .symbol {
            display: inline-block;
            border-radius: 50%;
            border: 5px double white;
            height: 30px;
        }
        @media only screen and (max-width: 600px) {
           .btn-outline-warning {
               color: black!important;
               border-color: black!important;
               background-color: white!important;
           }
        }
        .rating-symbol{
            width : auto;
        }
        .form-control:disabled, .form-control[readonly] {
            background-color: white!important;
        }
        .carousel-control-prev{
            left: -15px!important;
        }
        .carousel-control-next {
            right: -15px!important;
        }
    </style>
    @if(!empty($teacher))
        <meta name="teacher_id" content="{{ $teacher->teacher_id }}">
        <meta name="route-push-notification" content="{{ route('student.push-notification-to-teacher',['id' =>  $teacher->teacher_id]) }}">
        <meta name="route-book-schedule" content="{{ route('student.book-schedule',['id' =>  $teacher->teacher_id]) }}">
        <meta name="route-cancel-schedule" content="{{ route('student.push-notification-to-teacher-when-canceled',['id' =>  $teacher->teacher_id]) }}">
    @endif
@endsection
@section('panel')
    @include('includes.student.panel')
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="card w-100">
                <form action="{{route('student.search.home')}}" method="post" id="formSearchHome" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        <h3 class="card-title">{{ __('student_home.search_teacher.title') }}</h3>
                    </div>
                    <div class="card-body w-100">
                        <input type="hidden" name="date_time" id="date_time" value="">
                        <input type="hidden" name="check_modal_teacher" id="check_modal_teacher" value="1">
                        <div class="row">
                            <div class="w-100 d-flex mb-3 align-items-start">
                                <div class="col-3 col-sm-3">
                                    <label for="exampleFormControlInput1">{{ __('student_home.search_teacher.status') }}</label>
                                </div>
                                <div class="col-9 col-sm-9 d-flex flex-column d-sm-flex flex-sm-row" id="checkShowDate">
                                    <div class="col-sm-3 ml-3 mr-sm-5 ml-sm-3 pl-sm-1">
                                        <input @if(isset($input['btnRadioStatus']) && $input['btnRadioStatus'] == 1) checked @else checked @endif class="form-check-input" type="radio" name="btnRadioStatus" id="status-1" value="1">
                                        <label class="form-check-label" for="status-1">{{ __('student_home.search_teacher.Unspecified') }}</label>
                                    </div>
                                    <div class=" col-sm-3 ml-3 mr-sm-5">
                                        <input @if(isset($input['btnRadioStatus']) && $input['btnRadioStatus'] == 2) checked @endif class="form-check-input" type="radio" name="btnRadioStatus" id="status-2" value="2">
                                        <label class="form-check-label" for="status-2">{{ __('student_home.search_teacher.available_lessons') }}</label>
                                    </div>
                                    <div class="col-sm-3 ml-3">
                                        <input @if(isset($input['btnRadioStatus']) && $input['btnRadioStatus'] == 3) checked @endif class="form-check-input" type="radio" name="btnRadioStatus" id="status-3" value="3">
                                        <label class="form-check-label" for="status-3">{{ __('student_home.search_teacher.date') }}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="w-100 d-none mb-3 align-items-center d-sm-none showDate">
                                <div class="col-3 col-sm-3">
                                    <label for="exampleFormControlInput1">{{ __('student_home.search_teacher.date') }}</label>
                                </div>
                                <div class="col-9 col-sm-9 d-sm-flex justify-content-sm-between">
                                    @if(isset($date))
                                        @for($i =0; $i<7; $i++)
                                            @if($date[$i]!=[])
                                                <button
                                                    type="button"
                                                    name="date[]"
                                                    id="date{{$i+1}}"
                                                    class="date btn w-25 mt-1 mt-sm-0 w-sm-0 mx-sm-1
                                                        @if( isset($input['date_time']) && strpos($input['date_time'], $date[$i]['year'] . '/' . $date[$i]['month'] . '/' . $date[$i]['day']))
                                                            btn-warning
                                                        @else
                                                            btn-outline-warning
                                                        @endif
                                                         border-dark
                                                        @if($date[$i]['name'] == 'Sun')
                                                            {{in_array($date[$i]['year'].'/'.$date[$i]['month'].'/'.$date[$i]['day'], $date_time) ? 'text-secondary': 'text-danger'}}
                                                        @elseif($date[$i]['name'] == 'Sat')
                                                            {{in_array($date[$i]['year'].'/'.$date[$i]['month'].'/'.$date[$i]['day'], $date_time) ? 'text-secondary': 'text-primary'}}
                                                        @else
                                                            {{in_array($date[$i]['year'].'/'.$date[$i]['month'].'/'.$date[$i]['day'], $date_time) ? 'text-dark': 'text-dark'}}
                                                        @endif
                                                    "
                                                    checkClass="
                                                        @if($date[$i]['name'] == 'Sun')
                                                             btn-danger
                                                        @elseif($date[$i]['name'] == 'Sat')
                                                            btn-primary
                                                        @else
                                                            btn-info
                                                        @endif"
                                                    year="{{ isset($date[$i]['year']) ? $date[$i]['year']:"" }}"
                                                >
                                                    {{$date[$i]['month'].'/'.$date[$i]['day']}}({{$date[$i]['name']}})
                                                </button>
                                            @endif
                                        @endfor
                                    @endif
                                </div>
                            </div>
                            <div class="w-100  d-none mb-3 align-items-center d-sm-none showDate">
                                <div class="col-3 col-sm-3">
                                    <label for="exampleFormControlInput1">{{ __('student_home.search_teacher.time') }}</label>
                                </div>
                                <div
                                    class="col-9 col-sm-9 d-flex  d-sm-flex flex-sm-column justify-content-sm-center form-group mb-0">
                                    <div class="mobile-width-100 w-50  d-flex align-items-center justify-content-between">
                                        <input value="{{!empty($input['time_from']) ? $input['time_from'] : ''}}"  type="text" name="time_from" id="time_from" class="bs-timepicker form-control text-center timepicker1 mr-sm-2 mr-2 highlight-error" readonly>
                                        <h2>~</h2>
                                        <input value="{{!empty($input['time_to']) ? $input['time_to'] : ''}}" type="text" name="time_to" id="time_to" class="bs-timepicker form-control text-center timepicker1 ml-sm-2 ml-2 highlight-error" readonly>
                                    </div>
                                    <span class="text-danger invalid-feedback-custom"></span>
                                </div>
                            </div>
                            <div class=" w-100 d-flex mb-3 align-items-center">
                                <div class="col-3 col-sm-3">
                                    <label for="nationality">{{ __('student_home.search_teacher.nationality') }}</label>
                                </div>
                                <div class="col-9 d-flex col-sm-9 form-group mb-0">
                                    <select class="form-control select2 w-100 w-sm-100" id="nationality" name="nationality">
                                        <option></option>
                                        @foreach($nationality as $key => $value)
                                            <option value={{$key}} @if(!empty($input['nationality']) && $input['nationality'] == $key) selected @endif>{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class=" w-100 d-flex mb-3 align-items-center">
                                <div class="col-3 col-sm-3">
                                    <label for="teacherNickname">{{ __('student_home.search_teacher.nickname') }}</label>
                                </div>
                                <div class="col-9 col-sm-9 d-sm-flex justify-content-sm-around form-group mb-0">
                                    <input type="text" class="form-control" name="nickname" id="nickname" value="{{!empty($input['nickname']) ? $input['nickname'] : ''}}">
                                </div>
                            </div>
                            <div class=" w-100 d-flex mb-3 align-items-center">
                                <div class="col-3 col-sm-3">
                                    <label for="free_word">{{ __('student_home.search_teacher.free_word') }}</label>
                                </div>
                                <div class="col-9 col-sm-9 d-sm-flex justify-content-sm-around form-group mb-0">
                                    <input type="text" class="form-control" name="free_word" id="free_word" value="{{!empty($input['free_word']) ? $input['free_word'] : ''}}">
                                </div>
                            </div>
                            <div class="w-100 mb-3 d-flex align-items-center">
                                <div class="col-3 col-sm-3">
                                    <label for="exampleFormControlInput1">{{ __('student_home.search_teacher.coin') }}</label>
                                </div>
                                <div class="col-9 col-sm-9 d-flex flex-column justify-content-start  d-sm-flex flex-sm-column justify-content-sm-center form-group mb-0">
                                    <div class="w-50 mobile-width-100 d-flex align-items-center justify-content-between">
                                        <input type="text" class="form-control mr-sm-2 mr-2 w-40 highlight-error" id="coin_from" name="coin_from" value="{{!empty($input['coin_from']) ? $input['coin_from'] : ''}}">
                                        <h2>~</h2>
                                        <input type="text" class="form-control ml-sm-2 ml-2 w-40 highlight-error" id="coin_to" name="coin_to" value="{{!empty($input['coin_to']) ? $input['coin_to'] : ''}}">
                                    </div>
                                    <span class="text-danger invalid-feedback-custom"></span>
                                </div>
                            </div>

                            <div class=" w-100 d-flex mb-3 align-items-center">
                                <div class="col-3 col-sm-3">
                                    <label for="">{{ __('student_home.search_teacher.specify_course') }}</label>
                                </div>
                                <div class="col-9 d-flex col-sm-9 form-group mb-0">
                                    <select class="form-control select2 w-100" name="courses" id="courses" style="width: 100% !important;">
                                        <option selected="selected"></option>
                                        @foreach ($courses as $course)
                                            <option value="{{ $course->id }}" @if(!empty($input['courses']) && $input['courses'] == $course->id) selected @endif>{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer w-100">
                        <div class="col-12 d-flex justify-content-end col-sm-12 d-sm-flex justify-content-sm-end">
                            <button type="button" class="btn btn-default" id="clearFormHomeSearch">{{ __('button.clear') }}</button>
                            <button type="button" class="btn btn-primary ml-2" id="btnHomeSearch">{{ __('button.search') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="card w-100">
                <div class="card-header">
                    <h3 class="card-title">{{ __('student_home.teacher_available.title') }}</h3>
                </div>
                <div class="card-body" >
                    <div class="row mx-auto my-auto">
                        <div id="recipeCarousel" class="carousel slide w-100" data-ride="carousel">
                            <div class="carousel-inner w-100" role="listbox">
{{--                                @if(!empty($teacher_lessons[0]) && Cache::has('user-is-online-' . $teacher_lessons[0]->teacher_id))--}}
{{--                                    <div class="carousel-item active">--}}
{{--                                        <div class="col-md-2">--}}
{{--                                            <a href="{{route('student.book-lesson', ['id' => $teacher_lessons[0]->teacher_id])}}">--}}
{{--                                                <div class="card card-body d-flex d-sm-flex flex-column flex-sm-column align-items-center align-items-sm-center card-online">--}}
{{--                                                    <img class=" img-fluid img-circle " width="100px" style="border: 3px solid #adb5bd; padding: 3px" src="{{ !empty($teacher_lessons[0]->teacher_image) ? $teacher_lessons[0]->teacher_image : asset('images/avatar_2.png') }}" alt="User profile picture">--}}
{{--                                                    <p class="my-1 text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" data-toggle="tooltip" data-placement="top" title="{{$teacher_lessons[0]->nickname}}">{{$teacher_lessons[0]->nickname}}</p>--}}
{{--                                                    @if (array_key_exists($teacher_lessons[0]->nationality, config('nation')))--}}
{{--                                                        <p class="mb-1 text-center">--}}
{{--                                                            <img src="https://www.countryflags.io/{{$teacher_lessons[0]->nationality}}/flat/64.png" width="25px" height="25px"/>--}}
{{--                                                        </p>--}}
{{--                                                        <p  class="text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" data-toggle="tooltip" data-placement="top" title="{{ config('nation')[$teacher_lessons[0]->nationality] }}">{{ config('nation')[$teacher_lessons[0]->nationality] }}</p>--}}
{{--                                                    @endif--}}
{{--                                                </div>--}}
{{--                                            </a>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                                @php
                                   $i = 0;
                                @endphp
                                @foreach($teacher_lessons as $teacher_lesson1)
                                    @if($i == 0)
                                        @php
                                            $i++;
                                        @endphp
                                        <div class="carousel-item active">
                                            @foreach($teacher_lesson1 as $teacher_lesson)
                                                <div class="col-md-2">
                                                    <a href="{{route('student.book-lesson', ['id' => $teacher_lesson->teacher_id])}}">
                                                        <div class="card card-body d-flex d-sm-flex flex-column flex-sm-column align-items-center align-items-sm-center card-online">
                                                            <img class=" img-fluid img-circle " style="width:100px; height:100px; border: 3px solid #adb5bd; padding: 3px;object-fit: cover" src="{{ !empty($teacher_lesson->teacher_image) ? $teacher_lesson->teacher_image : asset('images/avatar_2.png') }}" alt="User profile picture">
                                                            <p class="my-1 text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" data-toggle="tooltip" data-placement="top" title="{{$teacher_lesson->nickname}}">{{$teacher_lesson->nickname}}</p>
                                                            @if (array_key_exists($teacher_lesson->nationality, config('nation')))
                                                                <p class="mb-1 text-center">
                                                                    <img src="https://www.countryflags.io/{{$teacher_lesson->nationality}}/flat/64.png" width="25px" height="25px"/>
                                                                </p>
                                                                <p  class="text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" data-toggle="tooltip" data-placement="top" title="{{ config('nation')[$teacher_lesson->nationality] }}">{{ config('nation')[$teacher_lesson->nationality] }}</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                        @continue
                                    @endif
                                        <div class="carousel-item">
                                            @foreach($teacher_lesson1 as $teacher_lesson)
                                                <div class="col-md-2">
                                                    <a href="{{route('student.book-lesson', ['id' => $teacher_lesson->teacher_id])}}">
                                                        <div class="card card-body d-flex d-sm-flex flex-column flex-sm-column align-items-center align-items-sm-center card-online">
                                                            <img class=" img-fluid img-circle " style="width:100px; height:100px; border: 3px solid #adb5bd; padding: 3px;object-fit: cover" src="{{ !empty($teacher_lesson->teacher_image) ? $teacher_lesson->teacher_image : asset('images/avatar_2.png') }}" alt="User profile picture">
                                                            <p class="my-1 text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" data-toggle="tooltip" data-placement="top" title="{{$teacher_lesson->nickname}}">{{$teacher_lesson->nickname}}</p>
                                                            @if (array_key_exists($teacher_lesson->nationality, config('nation')))
                                                                <p class="mb-1 text-center">
                                                                    <img src="https://www.countryflags.io/{{$teacher_lesson->nationality}}/flat/64.png" width="25px" height="25px"/>
                                                                </p>
                                                                <p  class="text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 150px" data-toggle="tooltip" data-placement="top" title="{{ config('nation')[$teacher_lesson->nationality] }}">{{ config('nation')[$teacher_lesson->nationality] }}</p>
                                                            @endif
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                @endforeach
                            </div>
                            <a class="carousel-control-prev w-auto" href="#recipeCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon bg-dark border border-dark rounded-circle" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next w-auto" href="#recipeCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon bg-dark border border-dark rounded-circle" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="card w-100">
                <div class="card-header">
                    <h3 class="card-title">
                        @if($count > 0)
                        {{ $count }} {{ __('student_home.teacher_were_found.title') }}
                        @else
                            {{ __('student_home.teacher_were_found.not_found') }}
                        @endif
                    </h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($teachers as $item)
                            <div class="col-12 col-sm-6">
                                <div class="info-box custom-info-box btnRedirect" style="height: 250px; background-color: #D8E6E7;">
                                    <input type="text" hidden value="{{ route('student.book-lesson', ['id' => $item->teacher_id]) }}" name="linkRedirect">
                                    <div class="custom-border-right pr-2 w-25 border-2 d-flex flex-column align-items-center">
                                        <img style="" class="profile-user-img img-fluid img-circle mx-3 avatar-card-home-page-of-student" src="{{ !empty($item->teacher_image) ? $item->teacher_image : asset('images/avatar_2.png') }}" alt="User profile picture">
                                        <p class="mt-2 text-center" style="text-overflow: ellipsis;overflow: hidden;white-space: nowrap;width: 71px" data-toggle="tooltip" data-placement="top" title="{{$item->teacher_name}}">{{$item->teacher_name}}</p>

                                    </div>
                                    <div class="info-box-content w-75">
                                        <div class="introduce custom-border-bottom border-2 h-75 w-100" >
                                            <div class="d-flex flex-column justify-content-between align-items-start h-100 w-100"  style="width: 400px;">
                                                <div class="block-ellipsis" data-toggle="tooltip" data-placement="top" title="{{$item->teacher_self_introduction}}">{{$item->teacher_self_introduction}}</div>
                                            </div>
                                        </div>
                                        <div class="lecture">
                                            <div class="info-box-number">
                                                @if(\App\Helpers\Helper::starTeacher($item->teacher_id) > 0)
                                                <input type="hidden" class="rating"  disabled data-filled="symbol-filled fa fa-star" data-empty="symbol-empty fa fa-star" data-fractions="2"  value="{{\App\Helpers\Helper::starTeacher($item->teacher_id)}}">
                                                    <span class="">{{\App\Helpers\Helper::starTeacher($item->teacher_id)}}</span>
                                                @else
                                                    <span class="font-weight-bold">{{__('student.no_reviews')}}</span>
                                                @endif
                                                 <span class="font-weight-bold">({{$item->number_count_lesson}} @if($item->number_count_lesson > 1){{__('student.number_lessons')}}) @else {{__('student.number_lesson')}}) @endif</span>
                                                @if (array_key_exists($item->teacher_nationality, config('nation')))
                                                    <div class="flex">
                                                            <span class="mt-2 text-center" >
                                                            <img src="https://www.countryflags.io/{{$item->teacher_nationality}}/flat/64.png" width="25px" height="25px"/>
                                                            </span>
                                                        <span style="text-overflow: ellipsis;white-space: nowrap;width: 71px" data-toggle="tooltip" data-placement="top" title="{{config('nation')[$item->teacher_nationality]}}">{{config('nation')[$item->teacher_nationality]}}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row w-100 d-flex justify-content-between align-items-start">
                        <div class="col-sm-12 d-sm-flex justify-content-sm-end pr-0">
                            @if(count($teachers) != 0)
                                {{ $teachers->links() }}
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if(!isset($check_modal_teacher))
        <!-- modal -->
        @if( !empty($teacher) && $countSuddenLesson < 2 && (!empty($membership_status) && ($membership_status->membership_status == config('constants.membership.id.premium_trial') || $membership_status->membership_status == config('constants.membership.id.premium') || $membership_status->membership_status == config('constants.membership.id.cancelling_premium') )) )
            @if(empty($lesson_learn_last))
                <div class="modal fade" id="modalSuddenTeacher" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true" style="top:100px">
                    <div class="modal-dialog" style="max-width: 500px!important;">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="col-12 col-sm-12">
                                    <div class="d-flex flex-column align-items-center">
                                        <h3>Notification </h3>
                                    </div>
                                    <div class="d-flex flex-column">
                                        <p class="mb-1">※ Không có bài học tương ứng</p>
                                    </div>
                                </div>
                                <div class="modal-footer py-1">
                                    <div>
                                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="modal fade" id="modalSuddenTeacher" tabindex="-1" aria-labelledby="exampleModalLabel"
                     aria-hidden="true" style="top:100px">
                    <div class="modal-dialog" style="max-width: 500px!important;">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="col-12 col-sm-12">
                                    <div class="d-flex flex-column align-items-center">
                                        <input type="hidden" value="{{ $teacher->start_hour}}" id="start_hour">
                                        <input type="hidden" value="{{ $teacher->start_date}}" id="start_date">
                                        <input type="hidden" value="{{ $lesson_learn_last->number }}" id="lesson_number">
                                        <input type="hidden" value="{{ $lesson_learn_last->course_id }}" id="course_id">
                                        <input type="hidden" value="{{ Auth::id() }}" id="student_id">
                                        <h3>{{ __('sudden_lesson.LessonNumber') }}
                                            : {{ $lesson_learn_last->lesson_name }} {{ __('sudden_lesson.of') }} {{ $lesson_learn_last->course_name }} </h3>
                                        <p class="mb-1">{{ __('sudden_lesson.lesson_ready') }}</p>
                                        <p class="mb-1">{{ __('sudden_lesson.join_with_teacher') }}</p>
                                        <img class="profile-user-img img-fluid img-circle"
                                             style="height: 100px !important;" width="100px" height="100px"
                                             src="{{ empty($teacher->image_photo) ? 'images/avatar_2.png' : $teacher->image_photo }}"
                                             alt="Avatar teacher">
                                        <p class="mb-1">{{ $teacher->nickname }}</p>
                                    </div>
                                    <div class="d-flex flex-column mt-5">
                                        <p class="mb-1">※ {{ __('sudden_lesson.note_1') }}</p>
                                        <p class="mb-1">※ {{ __('sudden_lesson.note_2') }}</p>
                                    </div>
                                </div>
                                <div class="modal-footer py-1">
                                    <div
                                        class="col-12 d-flex justify-content-center col-sm-8 offset-4 d-sm-flex justify-content-sm-start">
                                        <button type="button" class="btn btn-secondary mr-2 btn-flat"
                                                data-dismiss="modal">{{ __('sudden_lesson.cancel')  }}
                                        </button>
                                        <button type="button" class="btn btn-primary btn-flat" id="btnBookSchedule">
                                            {{ __('sudden_lesson.start')  }}
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @elseif( $countSuddenLesson < 2 && (!empty($membership_status) && ($membership_status->membership_status == config('constants.membership.id.premium_trial') || $membership_status->membership_status == config('constants.membership.id.premium') || $membership_status->membership_status == config('constants.membership.id.cancelling_premium') )) )
            <div class="modal fade" id="modalSuddenTeacher" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true" style="top:100px">
                <div class="modal-dialog" style="max-width: 500px!important;">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="col-12 col-sm-12">
                                <div class="d-flex flex-column align-items-center">
                                    <h3>{{ __('student_home.teacher_not_found_now.title') }}</h3>
                                </div>
                                <div class="d-flex flex-column">
                                    <p class="mb-1">※ {{ __('student_home.teacher_not_found_now.notification') }}</p>
                                </div>
                            </div>
                            <div class="modal-footer py-1">
                                <div>
                                    <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">{{ __('student_home.teacher_not_found_now.button_cancel') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
@endsection
@push('scripts')
    <script src="{{ asset('js/student/home.js') }}"></script>
    <script src="{{ asset('js/admin/students/timepicker_student_home.js') }}"></script>
    <script>
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        })
        $(document).ready(()=> {
            $('#recipeCarousel').carousel({
                interval: 10000
            })
            // let length = $('.carousel').find('.carousel-item').length;

            // $('.carousel .carousel-item').each(function(){
            //     var minPerSlide = length > 6 ? 6 : length;
            //     console.log(minPerSlide);
            //     var next = $(this).next();
            //     if (!next.length) {
            //         next = $(this).siblings(':first');
            //     }
            //     // next.children(':first-child').clone().appendTo($(this));
            //
            //     // if(length >= 6) {
            //     for (var i=0;i<minPerSlide;i++) {
            //         next=next.next();
            //         if (!next.length) {
            //             next = $(this).siblings(':first');
            //         }
            //         next.children(':first-child').clone().appendTo($(this));
            //     }
            //     // }
            // });
        })
    </script>
@endpush

