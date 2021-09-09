<div class="container
@if(is_null($zoom_link))
 d-none
 @endif
" style="overflow: hidden !important;" id="message_join_meeting">
    <div class="row">
        <div class="col-12 px-0">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div class="col-12 col-sm-12 d-flex justify-content-between align-items-center d-sm-flex justify-content-sm-between align-items-sm-center">
                            <p class="mb-0"> {{ __('student_panel.notification')  }}</p>
                            <a href="{{ $zoom_link }}" target="_blank" class="btn btn-success text-end" id="zoom_link">{{ __('student_panel.btn_join') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--<div class="container" style="overflow: hidden !important;">--}}
{{--    <div class="row">--}}
{{--        <div class="col-12 px-0">--}}
{{--            <div class="card">--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row d-flex align-items-center">--}}
{{--                        <div class="col-12 col-sm-3">--}}
{{--                            <p class="mb-0">{{ __('student_panel.teacher_course') }}</p>--}}
{{--                        </div>--}}
{{--                        <div class="col-6 col-sm-5">--}}
{{--                            <div class="d-flex d-sm-flex">--}}
{{--                                <p class="mb-0 mr-2">{{ __('student_panel.course_name') }}: </p>--}}
{{--                                <input type="hidden" value="@if(isset($student_next_lesson)){{$student_next_lesson->course_id}}@endif" id="course_id" />--}}
{{--                                <p class="mb-0" id="current_course">--}}
{{--                                    @if(isset($student_next_lesson))--}}
{{--                                        {{$student_next_lesson->course_name}}--}}
{{--                                    @endif--}}
{{--                                </p>--}}
{{--                            </div>--}}
{{--                            <div class="d-flex d-sm-flex">--}}
{{--                                <p class="mb-0 mr-2">{{ __('student_panel.lesson_name') }}:</p>--}}
{{--                                <input type="hidden" value="@if(isset($student_next_lesson)){{$student_next_lesson->number}}@endif" id="lesson_id" />--}}
{{--                                <p class="mb-0" id="next_lesson">--}}
{{--                                    @if(isset($student_next_lesson))--}}
{{--                                        {{$student_next_lesson->lesson_name}}--}}
{{--                                    @endif</p>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="col-6 d-flex justify-content-end col-sm-4 d-sm-flex justify-content-sm-end">--}}
{{--                            <button class=" btn btn-primary" data-toggle="modal" data-target="#modal-update">{{ __('button.update') }}--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
<div class="modal fade" id="modal-update">
    <div class="modal-dialog modal-lg" style="max-width: 500px;!important">
        <div class="modal-content">
            <div class="modal-header h-25">
                <h4>{{ __('student_panel.title_popup') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>{{ __('student_panel.course') }}</label>
                    <select class="form-control" id="course_id">
                        @foreach($courses as $course)
                            <option value="{{$course->id}}" name="{{$course->name}}" @if(isset($student_next_lesson) && $course->name == $student_next_lesson->course_name) selected @endif>
                                {{$course->name}}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>{{ __('student_panel.lesson') }}</label>
                    <input type="text" class="form-control" disabled id="lesson_name" value="">
                </div>

            </div>
            <div class="modal-footer">
                <div class="col-sm-12">
                    <button class="btn btn-default btn-sm float-left" data-dismiss="modal">{{ __('button.close') }}</button>
                    <button class="btn btn-primary btn-sm float-right" data-dismiss="modal" id="btnSubmit">{{ __('button.submit') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>


