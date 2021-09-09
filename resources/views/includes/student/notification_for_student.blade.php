<div class="modal fade" id="modalSuccessfulBooking" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center align-items-center my-3">
                    <h6>{{ __('sudden_lesson.success_notification') }}</h6>
                    <h6>{{ __('sudden_lesson.yes_no_question') }}</h6>
                </div>
                <div
                    class="col-12 d-flex justify-content-center col-sm-12 d-sm-flex justify-content-sm-center">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"
                            id="btnCancelSchedule">{{ __('sudden_lesson.no') }}</button>
                    <a href="javascript:;" class="btn btn-primary linkZoom"
                       id="btnBookingScheduleConfirmation">{{ __('sudden_lesson.yes') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalFailedBooking" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center align-items-center my-3">
                    <h6>{{ __('sudden_lesson.fail_booking') }}</h6>
                </div>
                <div
                    class="col-12 d-flex justify-content-center col-sm-12 d-sm-flex justify-content-sm-center">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"
                    >OK</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalTheExpiredPremium" tabindex="-1" role="dialog"
     aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center align-items-center my-3">
                    <h6 id="messageTheExpiredPremium"></h6>
                </div>
                <div
                    class="col-12 d-flex justify-content-center col-sm-12 d-sm-flex justify-content-sm-center">
                    <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal"
                    >OK</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalCancelRequest" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true" style="top:100px">
    <div class="modal-dialog" style="max-width: 650px!important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 col-sm-12">
                    <div class="d-flex flex-column align-items-center">
                        <h3>{{ __('student_home.teacher_not_found_now.title') }} </h3>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-1">{{ __('sudden_lesson.teacher_cancel') }}</p>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <div>
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modalLessonUnavailableNow" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true" style="top:100px">
    <div class="modal-dialog" style="max-width: 300px!important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 col-sm-12">
                    <div class="d-flex flex-column align-items-center">
                        <h3>{{ __('student_home.teacher_not_found_now.title') }} </h3>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-2">{{ __('validation_custom.M048') }}</p>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <div>
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal_teacher_start_lesson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="d-flex flex-column justify-content-center align-items-center my-3">
                    <input type="hidden" value="" id="schedule_id">
                    <h5>{{__('student_panel.teacher_start_lesson')}}</h5>
                    <button  class="text-center mt-1 btn btn-primary" id="btn_student_start_lesson">{{__('student_panel.btn_start_lesson')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>


{{--<div class="modal fade" id="modal_teacher_start_lesson" tabindex="-1" aria-labelledby="exampleModalLabel"--}}
{{--     aria-hidden="true" style="top:100px">--}}
{{--    <div class="modal-dialog" style="max-width: 400px!important;">--}}
{{--        <div class="modal-content">--}}
{{--            <div class="modal-body mt-3">--}}
{{--                <div class="col-12 col-sm-12">--}}
{{--                    <div class="d-flex flex-column align-items-center">--}}
{{--                        <h3>Notification </h3>--}}
{{--                    </div>--}}
{{--                    <div class="d-flex flex-column">--}}
{{--                        <p class="mb-1">{{__('student_panel.teacher_start_lesson')}}</p>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <div class="modal-footer mt-3">--}}
{{--                    <div>--}}
{{--                        <button type="button" class="btn btn-primary btn-flat" data-dismiss="modal" id="btn_student_start_lesson">{{__('student_panel.btn_start_lesson')}}--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
