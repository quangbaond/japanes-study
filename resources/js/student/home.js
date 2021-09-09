const STUDENT_HOME_PAGE = {};
const routeStudentSearch = $("[name=route-student-search]").attr('content')
const routeBookSchedule = $("[name=route-book-schedule]").attr('content');
const routePushNotificationWhenCanceled = $("[name=route-cancel-schedule]").attr('content');
const routePushNotification = $("[name=route-push-notification]").attr('content');
const teacher_id = $("[name=teacher_id]").attr('content');
const routeRequestCancel = $("[name=route-timeout]").attr('content');
let csrf_token = $("[name=csrf-token]").attr('content');
var checkCloseModalConfirm = false;
$(function () {
    STUDENT_HOME_PAGE.init = function () {
        STUDENT_HOME_PAGE.showPopup();
        STUDENT_HOME_PAGE.showOption();
        STUDENT_HOME_PAGE.redirectCard();
        STUDENT_HOME_PAGE.studentSearch();
        STUDENT_HOME_PAGE.bookSchedule();
        STUDENT_HOME_PAGE.bookingScheduleConfirmation();
        STUDENT_HOME_PAGE.cancelSchedule();
        STUDENT_HOME_PAGE.changeDate();
        STUDENT_HOME_PAGE.clearFormHomeSearch();
        STUDENT_HOME_PAGE.checkRadioHomeSearch();
        STUDENT_HOME_PAGE.cancelScheduleWhenClickOutside();
    };

    STUDENT_HOME_PAGE.checkRadioHomeSearch = function() {
        let btnRadio = $('input[name="btnRadio"]:checked').val();
        let btnRadioStatus = $('input[name="btnRadioStatus"]:checked').val();
        if (btnRadio == 2) {
            $('.showMaterial').removeClass('d-flex');
            $('.showMaterial').removeClass('d-sm-flex');
            $('.showMaterial').addClass('d-none');
            $('.showMaterial').addClass('d-sm-none');
            $('.showSpecify').removeClass('d-none');
            $('.showSpecify').removeClass('d-sm-none');
            $('.showSpecify').addClass('d-flex');
            $('.showSpecify').addClass('d-sm-flex');
        }

        if (btnRadioStatus == 3) {
            $('.showDate').removeClass('d-none');
            $('.showDate').removeClass('d-sm-none');
            $('.showDate').addClass('d-flex');
            $('.showDate').addClass('d-sm-flex');
        }
    };

    STUDENT_HOME_PAGE.showPopup = () => {
        let data  = $.cookie('notification_student_sudden_lesson');
        let data1 = $.cookie('notification_require_student_join_lesson');
        if ((typeof data === 'undefined') && (typeof data1 === 'undefined')){
            $('#modalSuddenTeacher').modal('show');
        }
        else if(JSON.parse(data1).student_id == $('#user_login').val()) {
            $('#modal_teacher_start_lesson').modal({
                backdrop: 'static'
            });
            $('#modal_teacher_start_lesson').modal('show');
            // $('#modalSuddenTeacher').modal('hide');
            setTimeout(()=>{
                $('#modal_teacher_start_lesson').modal('hide');
            },JSON.parse(data1).expires - new Date().getTime());
        }
    }
    STUDENT_HOME_PAGE.showOption = () => {
        $('input:radio[name="btnRadioStatus"]').click(function(){
            if ($('input:radio[id="status-3"]').is(':checked')) {
                    $('.showDate').removeClass('d-none');
                    $('.showDate').removeClass('d-sm-none');
                    $('.showDate').addClass('d-flex');
                    $('.showDate').addClass('d-sm-flex');
            } else {
                $('input[name=time_from]').val('');
                $('input[name=time_to]').val('');
                    $('.showDate').removeClass('d-flex');
                    $('.showDate').removeClass('d-sm-flex');
                    $('.showDate').addClass('d-none');
                    $('.showDate').addClass('d-sm-none');
            }
        });
    };
    STUDENT_HOME_PAGE.redirectCard = () => {
        $('body').on('click', '.btnRedirect', function() {
            let linkRedirect = $(this).find('input[name=linkRedirect]').val();
            window.location.href = linkRedirect;
        });
    };

    STUDENT_HOME_PAGE.bookingScheduleConfirmation = () => {
        $('#btnBookingScheduleConfirmation').click(() => {
            checkCloseModalConfirm = true;
            $('#modalSuccessfulBooking').modal('hide');
            $('#loading_wait_teacher').show();
            let formData = new FormData();
            formData.append( 'start_hour', $('#start_hour').val() );
            formData.append( 'start_date', $('#start_date').val() );
            formData.append( 'course_id', $('#course_id').val() );
            formData.append( 'lesson_id', $('#lesson_number').val() );
            formData.append( 'student_id', $('#student_id').val() );
            formData.append( 'coin', '0' );
            formData.append( 'type', '1' );

            let dataCookies = {
                "start_hour": $('#start_hour').val(),
                "start_date": $('#start_date').val(),
                "course_id": $('#course_id').val(),
                "lesson_id": $('#lesson_number').val(),
                "student_id": $('#student_id').val(),
                "coin": "0",
                "type": "1",
                "teacher_id": teacher_id
            }
            let expDate = new Date();
            let data_cookies = { ...dataCookies,
                'expires' : expDate.getTime() + (3 * 60 * 1000)
            };
            expDate.setTime(expDate.getTime() + (3 * 60 * 1000)); // add 3 minutes

            $.cookie('notification_student_sudden_lesson', JSON.stringify(data_cookies), { path: '/', expires: expDate });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routeBookSchedule,
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(result){
                    $('#loading_wait_teacher').show();
                },
                error: function(result){
                    toastr.error(result.message);
                }
            })

            timeoutOfStudent =  setTimeout(function() {
                //after 3 minutes don't have response from teacher, student will send the request to change teacher schedule status back 3(free time)
                formData.append( 'teacher_id', teacher_id );
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": csrf_token
                    }
                });
                $.ajax({
                    type: "POST",
                    url: routeRequestCancel,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function(result){
                        if (!result.status) {
                            toastr.error(result.message);
                        }
                        else {
                            $('#loading_wait_teacher').hide();
                            $('#modalCancelRequest').modal('show');
                        }
                    },
                    error: function(result){
                        toastr.error(result.message);
                    }
                })
            }, 60 * 1000 * 3);
        })
    };

    STUDENT_HOME_PAGE.bookSchedule = () => {
        $('#btnBookSchedule').on( "click", () => {
            let formData = new FormData();
            formData.append( 'start_hour', $('#start_hour').val() );
            formData.append( 'start_date', $('#start_date').val() );
            formData.append( 'course_id', $('#course_id').val() );
            formData.append( 'lesson_id', $('#lesson_number').val() );

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routePushNotification,
                data: formData,
                cache:false,
                contentType: false,
                processData: false,
                success: function(result){
                    if (!result.status) {
                        if(result.data === 'expired') {
                            $('#messageTheExpiredPremium').html(result.message);
                            $('#modalSuddenTeacher').modal('hide');
                            $('#message_expired_premium').show();
                            $('#modalTheExpiredPremium').modal('show');

                        }
                        else {
                            $('#modalSuddenTeacher').modal('hide');
                            $('#modalFailedBooking').modal('show');
                        }
                    }
                    else {
                        $('#modalSuddenTeacher').modal('hide');
                        $('#modalSuccessfulBooking').modal('show');
                    }
                },
                error: function(result){
                    toastr.error(result.message);
                }
            } );
        })
    }

    STUDENT_HOME_PAGE.cancelSchedule = () => {
        $('#btnCancelSchedule').click(() => {
            let formData = new FormData();
            checkCloseModalConfirm = true;
            formData.append( 'start_hour', $('#start_hour').val() );
            formData.append( 'start_date', $('#start_date').val() );
            formData.append( 'course_id', $('#course_id').val() );
            formData.append( 'lesson_id', $('#lesson_number').val() );
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routePushNotificationWhenCanceled,
                data: formData,
                contentType: false,
                processData: false,
                success: function(result){

                },
                error: function(result){
                    toastr.error(result.message);
                }
            })
        });

    }
    STUDENT_HOME_PAGE.cancelScheduleWhenClickOutside = () => {
        $('#modalSuccessfulBooking').on('hide.bs.modal', () => {
            if(checkCloseModalConfirm) {
                checkCloseModalConfirm = false;
                return true;
            }
            let formData = new FormData();
            formData.append('start_hour', $('#start_hour').val());
            formData.append('start_date', $('#start_date').val());
            formData.append('course_id', $('#course_id').val());
            formData.append('lesson_id', $('#lesson_number').val());
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routePushNotificationWhenCanceled,
                data: formData,
                contentType: false,
                processData: false,
                success: function (result) {

                },
                error: function (result) {
                    toastr.error(result.message);
                }
            })
        });
    }
    STUDENT_HOME_PAGE.clearFormHomeSearch = function() {
        $('#clearFormHomeSearch').click(function() {
            $('#formSearchHome').find('input:not([name=_token])').val('');
            $('#formSearchHome').find('.select2').val(null).trigger('change');
            $('#formSearchHome').find('input[type=radio]').prop('checked', false);
            $('#formSearchHome').submit();
        });
    };

    STUDENT_HOME_PAGE.studentSearch = () => {
        $('#btnHomeSearch').click( () => {
            // Clear error
            $(".invalid-feedback-custom").html('');
            $('.highlight-error').removeClass('is-invalid');
            // Get data form search teacher
            let data = $("#formSearchHome").serialize();

            //get date
            let date_time = "";
            $('#formSearchHome .btn-warning').each( function (key, val) {
                let year =$(this).attr('year');
                let day = $(this).text().trim();
                $date_year = (year+"/"+day).substring(0, 10);
                date_time = date_time+"|"+$date_year;
            })
            $('#date_time').val(date_time);
            // Ajax
            $.ajax({
                type: "POST",
                url: routeStudentSearch,
                data: data,
                success: function (result) {
                    if (result.status) {
                        $('#formSearchHome').submit();
                    } else {
                        $.each(result.message, function (key, value) {
                            if (typeof value !== "undefined") {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key).closest('.form-group').find('span').html(value[0]);
                            }
                        });
                    }
                },
                error: function(error){
                    alert("Error server");
                }
            })
        });
    }

    STUDENT_HOME_PAGE.changeDate = () => {
        for (let i = 1; i <= 7; i++) {
            $(`#date${i}`).click(() => {
                let date = $(`#date${i}`).text();
                if ($(`#date${i}`).hasClass('btn-outline-warning border-dark')) {
                    $(`#date${i}`).removeClass('btn-outline-warning border-dark').addClass("btn-warning");
                }
                else {
                    $(`#date${i}`).removeClass('btn-warning').addClass("btn-outline-warning border-dark");
                }

                // if ($(`#date${i}`).hasClass('btn-info')) {
                //     $(`#date${i}`).removeClass('btn-info').addClass('btn-secondary');
                // }else if ($(`#date${i}`).hasClass('btn-danger')) {
                //     $(`#date${i}`).removeClass('btn-danger').addClass('btn-secondary');
                // }else if ($(`#date${i}`).hasClass('btn-primary')) {
                //     $(`#date${i}`).removeClass('btn-primary').addClass('btn-secondary');
                // }else if ($(`#date${i}`).hasClass('btn-secondary')) {
                //     var class_check = $(`#date${i}`).attr('checkclass')
                //     $(`#date${i}`).removeClass('btn-secondary').addClass("btn-warning");
                // }
            });
        }
    }
    STUDENT_HOME_PAGE.readURL = (input) => {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function(e) {
                $('#image_profile').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]); // convert to base64 string
        }
    }

    $("#upload-photo").change(function() {
        STUDENT_HOME_PAGE.readURL(this);
    });
});
$(document).ready(function(){
    STUDENT_HOME_PAGE.init();
});
