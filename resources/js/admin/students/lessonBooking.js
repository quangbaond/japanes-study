let STUDENT_LESSON_BOOKING = {};
let routeBookSchedule;
let routePushNotificationWhenCanceled;
let routePushNotification;
let csrf_token = $("[name=csrf-token]").attr('content');
let message_no_data = $("[name=message-no-data]").attr('content');
let the_number_of_record = $("[name=the-number-of-record]").attr('content');
let routeCheckTimeRemove = $("[name=route-check-time]").attr('content');
let routeGetCourseCanTeach = $('[name=route-get-course-can-teach]').attr('content');
let routeUpdateLessonBooked = $('[name=route-update-lesson-booked]').attr('content');
let textDocument = $('[name=text-document]').attr('content');
let teacher_id;
var checkCloseModalConfirm = false;
var idBooking;
var dataLesson;
var teacher_schedule_id;
$(function() {
    var Arr_BOOKING = [];
    STUDENT_LESSON_BOOKING.init = function() {

        STUDENT_LESSON_BOOKING.changeUserTimepicker();
        STUDENT_LESSON_BOOKING.limitContent();
        STUDENT_LESSON_BOOKING.removeBookingList();
        STUDENT_LESSON_BOOKING.bookingScheduleConfirmation();
        STUDENT_LESSON_BOOKING.cancelSchedule();
        STUDENT_LESSON_BOOKING.startLesson();
        STUDENT_LESSON_BOOKING.cancelScheduleWhenClickOutside();
        STUDENT_LESSON_BOOKING.showVideo();
        STUDENT_LESSON_BOOKING.changeLesson();
        STUDENT_LESSON_BOOKING.changeLessonBooked();
    };
    STUDENT_LESSON_BOOKING.showVideo = function() {
        $('body').on('click', '.btnShowVideo', function () {
            let videoLink = $(this).attr('data-video_link');
            let html = `<video id="clip" controls preload=auto playsinline muted autoplay class="intro-video" data-setup="{}">
                        <source src="${videoLink}" type='video/mp4'/>
                    </video>`;
            $('#modalShowVideo').find('.modal-body').html(html);
            $('#modalShowVideo').modal('show');
        })
    }
    STUDENT_LESSON_BOOKING.changeLessonBooked = function() {
        $('body').on('click', '#btnUpdateLesson', function() {
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            let lesson_id = $('#lesson_id_select').val();
            let lesson_name = $('#lesson_id_select').find(":selected").text();
            let course_name = $('#course_id_select').find(":selected").text();
            let formData = new FormData();
            formData.append('lesson_id', lesson_id);
            formData.append('teacher_schedule_id', teacher_schedule_id);

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routeUpdateLessonBooked,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    $('#loading').removeClass("d-block");
                    $("#loading").addClass('d-none');
                    if (!result.status) {
                        toastr.error(result.message);
                    } else {
                        let row = $('body').find(`[data-teacher_schedule_id=${teacher_schedule_id}]`);
                        row.attr('data-lesson_id', result.data.lesson_id)
                        row.attr('data-course_id', result.data.course_id)
                        row.closest('tr').find('#course_name').html(result.data.course_name);
                        row.closest('tr').find('#lesson_name').html(result.data.lesson_name);
                        if(result.data.text_link === '') {
                            row.closest('tr').find('.openTabPdfField').html(`<button class="btn py-1 mb-0" style="background-color: #F6B352; padding: 1px 10px" disabled> ${textDocument} </button>`);
                        }
                        else {
                            row.closest('tr').find('.openTabPdfField').html(`<a class=" btn btnNewTagPdf py-1 mb-0" href="${result.data.text_link}" target="_blank" style="background-color: #F6B352; padding: 1px 10px">${textDocument}</a>`);
                        }
                        if(result.data.text_link === '') {
                            row.closest('tr').find('.showVideoField').html(`<button class="btn py-1 mb-0 " style="background-color: #F68657; padding: 1px 10px" disabled> Video </button>`)
                        }
                        else {
                            row.closest('tr').find('.showVideoField').html(`<a class="btn btnShowVideo py-1 mb-0" href="javascript:;" data-video_link="${result.data.video_link}" style="background-color: #F68657; padding: 1px 10px">Video</a>`);
                        }
                        row.closest('tr').find('.startLesson').attr('data-lesson_id', result.data.lesson_id)
                        row.closest('tr').find('.startLesson').attr('data-course_id', result.data.course_id)
                        $('#modalChangeLesson').modal('hide');
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.message}
                                </div>
                            </section>
                        `);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(result) {
                    toastr.error(result.message);
                }
            })
        })

    }
    STUDENT_LESSON_BOOKING.changeLesson = function() {
        $('body').on('click', '.btnChangeLesson', function () {
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            let lesson_id = $(this).attr('data-lesson_id');
            let course_id = $(this).attr('data-course_id');
            let teacher_id = $(this).attr('data-teacher_id');
            teacher_schedule_id = $(this).attr('data-teacher_schedule_id');
            let formData = new FormData();
            formData.append('teacher_id', teacher_id);
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routeGetCourseCanTeach,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    $('#loading').removeClass("d-block");
                    $("#loading").addClass('d-none');
                    if (!result.status) {
                        toastr.error(result.message);
                    } else {
                        dataLesson = { ...result.data};
                        showModalChangeLesson(course_id, lesson_id)
                        $('#modalChangeLesson').modal('show');
                    }
                },
                error: function(result) {
                    toastr.error(result.message);
                }
            })

        })
        $('body').on('change', '#course_id_select', function () {
            showModalChangeLesson($(this).val(), 1)
        })
    }
    const showModalChangeLesson = (course_id, lesson_id) => {
        let course_html = '';
        let lesson_html = '';
        let data = Object.values(dataLesson).reduce((acc, val) => {
            if(acc[val.course_id] === undefined){
                acc[val.course_id] = [];
            };
            acc[val.course_id].push(val);
            return acc;
        },{});
        $.each(data, function (index, value) {
            if( value[0].course_id === parseInt(course_id) ) {
                course_html += `<option value="${value[0].course_id}" selected>
                                    ${value[0].course_name}
                                </option>`;
            }
            else {
                course_html += `<option value="${value[0].course_id}">
                                    ${value[0].course_name}
                                </option>`;
            }

            if(value[0].course_id == parseInt(course_id) ) {
                for(let i = 0; i < value.length; ++i ){
                    if(value[i].lesson_id === parseInt(lesson_id)) {
                        lesson_html += `<option value="${value[i].lesson_id}" selected>
                                    ${value[i].lesson_name}
                                </option>`;
                        continue;
                    }
                    lesson_html += `<option value="${value[i].lesson_id}">
                                    ${value[i].lesson_name}
                                </option>`;
                }
            }
        })
        $('#course_id_select').html(course_html);
        $('#lesson_id_select').html(lesson_html);
    }
    STUDENT_LESSON_BOOKING.changeUserTimepicker = function() {
        $('.bs-timepicker').click((e) => {
            var temp = e.target.id;
            $(`#${temp}`).toggleClass('btn-warning');
            var hasClass = $(`#${temp}`).hasClass('btn-warning')
            const time = $(`#${temp}`).val()
            var dateTime = $(`#${temp}`).parents("tr:first").children("td:first").children().text();

            var row = $(`#${temp}`).parent().parent().attr('id');

            if (hasClass === true) {

                var OBJ_BOOKING = {
                    timemer: [time],
                    row: row,
                    date: dateTime.trim(),
                }
                var index = Arr_BOOKING.findIndex(el => el.row === row);
                if (index === -1) {
                    return Arr_BOOKING.push(OBJ_BOOKING)
                } else {
                    Arr_BOOKING.filter(item => {
                        if (item.row === row) {
                            item.timemer.push(time)
                        }
                    })
                }

            } else {
                Arr_BOOKING.map(item => {
                    for (var i = 0; i < item.timemer.length; i++) {
                        if (item.row === row) {
                            if (item.timemer[i] === time) {
                                return item.timemer.splice(item.timemer[i], 1);
                            }
                        }
                    }

                })
            }
        })

    };

    $('#confirm-modal').click(() => {
        $('#modalConfirm-Booking').modal('toggle')
        $('#modal-lg').modal('toggle')
        $('#comfirm__booking').html(STUDENT_LESSON_BOOKING.displayPopup(Arr_BOOKING))

    })
    STUDENT_LESSON_BOOKING.limitContent = () => {
        $('.ellipsis').each(function() {
            $(this).attr('data-toggle', "tooltip");
            $(this).attr('data-placement', "right");
            $(this).attr('data-original-title', $(this).html());
        })
    }
    STUDENT_LESSON_BOOKING.displayPopup = (OBJ) => {
        if (OBJ.length < 0) return

        var html = ""
        for (var i = 0; i < OBJ.length; i++) {
            var text = ""
            var classText = ""
            // console.log(OBJ[i]);
            for (var k of OBJ[i].timemer) {
                OBJ[i].date = OBJ[i].date.trim()
                if (OBJ[i].date.includes('Sat')) {
                    classText = "text-primary"
                } else if (OBJ[i].date.includes('Sun')) {
                    classText = "text-danger"
                }
                text += ` <input type="text" id="timepicker1-0"
                class="  btn btn-warning mr-lg-5 mb-3 mt-1 text-center timepicker1"
                style="width: 65px" value="${k}" readonly="">`
            }
            html +=
                `<tr>
                    <th hidden=""></th>
                    <td style="width: 150px">
                        <div class="justify-content-center text-center">
                            <span class="text-center ${classText}">${OBJ[i].date}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-between mt-1 addButton" id="divRow1">
                            <div class="row ml-2" id="row1">
                                <div class="d-flex" id="divTimepicker1-0">
                                    <div class="mb-2 mr-2" id="removeTimepicker1-0">
                                    </div>
                                   ${text}
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>`
        }

        return html
    }


    STUDENT_LESSON_BOOKING.bookingScheduleConfirmation = () => {
        $('#btnBookingScheduleConfirmation').click(() => {
            $('#loading_wait_teacher').show();
            checkCloseModalConfirm = true;
            let fd = new FormData();
            fd.append('start_hour', $('#start_hour').val());
            fd.append('start_date', $('#start_date').val());
            fd.append('course_id', $('#course_number').val());
            fd.append('lesson_id', $('#lesson_number').val());
            fd.append('student_id', $('#student_id').val());
            fd.append('coin', $('#coin').val());
            fd.append('type', $('#type').val());
            fd.append('book_type', $('#book_type').val());

            let dataCookies = {
                "start_hour": $('#start_hour').val(),
                "start_date": $('#start_date').val(),
                "course_id": $('#course_id').val(),
                "lesson_id": $('#lesson_number').val(),
                "student_id": $('#student_id').val(),
                "coin": $('#coin').val(),
                "type": $('#type').val(),
            }
            let expDate = new Date();
            let data_cookies = { ...dataCookies,
                'expires' : expDate.getTime() + (3 * 60 * 1000)
            };
            expDate.setTime(expDate.getTime() + (3 * 60 * 1000)); // add 3 minutes

            $.cookie('notification_student_booked', JSON.stringify(data_cookies), { path: '/', expires: expDate });

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routeBookSchedule,
                data: fd,
                cache: false,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (!result.status) {
                        $('#modalSuccessfulBooking').modal('hide');
                        // $(`[data-idbooking="${idBooking}"]`).attr('disabled',"disabled");
                        toastr.error(result.message);
                    } else {
                        $('#modalSuccessfulBooking').modal('hide');
                        // $(`[data-idbooking="${idBooking}"]`).attr('disabled',"disabled");
                    }
                },
                error: function(result) {
                    toastr.error(result.message);
                }
            })
            timeoutOfStudent =  setTimeout(function() {
                $('#loading_wait_teacher').hide();
                $('#modalCancelRequest').modal('show');
            }, 60 * 1000 * 3);
        })
    };
    STUDENT_LESSON_BOOKING.cancelScheduleWhenClickOutside = () => {
        $('#modalSuccessfulBooking').on('hide.bs.modal', () => {
            if (checkCloseModalConfirm) {
                checkCloseModalConfirm = false;
                return true;
            }
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routePushNotificationWhenCanceled,
                data: {},
                contentType: false,
                processData: false,
                success: function(result) {

                },
                error: function(result) {
                    toastr.error(result.message);
                }
            })
        });
    }
    STUDENT_LESSON_BOOKING.startLesson = () => {
        $('.startLesson').click(function() {
            let start_hour = $(this).attr('data-start_hour');
            let start_date = $(this).attr('data-start_date');
            var coin = $(this).data('coin');
            routeBookSchedule = $(this).attr('data-route_booked_confirm');
            routePushNotification = $(this).attr('data-route_booked');
            routePushNotificationWhenCanceled = $(this).attr('data-route_canceled');
            idBooking = $(this).attr('data-idbooking');
            let lesson_id = $(this).attr('data-lesson_id');
            let course_id = $(this).attr('data-course_id');
            $('#start_hour').val(start_hour);
            $('#start_date').val(start_date);
            $('#lesson_number').val(lesson_id);
            $('#course_number').val(course_id);
            $('#coin').val(coin);
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routePushNotification,
                data: {bookingId: idBooking},
                success: function(result) {
                    if(result.data.expired) {
                        $(`[data-idbooking=${idBooking}]`).attr('disabled', 'disabled');
                        $('#modalLessonUnavailableNow').modal('show');
                    }
                    else {
                        if (!result.status) {
                            $('#modalSuddenTeacher').modal('hide');
                            $('#modalFailedBooking').modal('show');
                        } else {
                            $('#modalSuccessfulBooking').modal('show');
                        }
                    }
                },
                error: function(result) {
                    toastr.error(result.message);
                }
            });
        })
    }

    STUDENT_LESSON_BOOKING.cancelSchedule = () => {
        $('#btnCancelSchedule').click(() => {
            checkCloseModalConfirm = true;
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: routePushNotificationWhenCanceled,
                data: {},
                contentType: false,
                processData: false,
                success: function(result) {

                },
                error: function(result) {
                    toastr.error(result.message);
                }
            });
        })
    }
    STUDENT_LESSON_BOOKING.removeBookingList = () => {
        $('.cancelBooking').click(function() {
            var idBooking = $(this).attr('data-idbooking')
            var teacherName = $(this).attr('data-teacherName');
            var date = $(this).attr('data-start_date');
            var time = $(this).attr('data-start_hour')
            var coin = $(this).attr('data-coin')
            var startDate = $(this).attr('data-date');
            $('#btnConfirmRemove').attr('data-idbooking', idBooking)
            $('#btnConfirmRemove').attr('data-start_date', date)
            $('#btnConfirmRemove').attr('data-start_hour', time)
            $('#btnConfirmRemove').attr('data-coin', coin)
            $('#teacherName').text(teacherName)
            $('#date').text(date)
            $('#time').text(time)
            $('#coin_techer').text(coin)
            $('#btnConfirmRemove').attr('data-date', startDate)
            $('#btnConfirmRemoveStep2').attr('data-date', startDate)
        })
        $('#btnConfirmRemove').click(function() {

            var start_date = $(this).attr('data-start_date');
            var start_hour = $(this).attr('data-start_hour');
            var idBooking = $(this).attr('data-idbooking');
            var checkTimeExpired = true;
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            $.ajax({
                url: routeCheckTimeRemove,
                dataType: "json",
                type: 'post',
                async: false,
                data: { bookingId: idBooking },
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                },
                success: function(result) {
                    $("#loading").addClass('d-none');
                    $('#loading').removeClass("d-block");
                    if(result.data.expired &&  $('#btnConfirmRemove').attr('data-coin') > 0) {
                        checkTimeExpired = false;
                        return true;
                    }
                },
                error: function(result) {
                    toastr.error(result.message);
                }
            })

            if (!checkTimeExpired) {
                $('#booking_id').val(idBooking);
                $('#modal-removeBooking').modal('hide');
                $('#modalConfirmRemoveBooking').modal('show');
            } else {
                var startDate = $(this).attr('data-date');
                $.ajax({
                    url: "/student/remove-booking/list",
                    dataType: "json",
                    type: 'post',
                    data: { idBooking: idBooking },
                    headers: {
                        "X-CSRF-TOKEN": csrf_token
                    },
                    beforeSend: function() {
                        $(this).html(
                            `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`
                        );
                    },
                    success: function(result) {
                        if (result.status) {
                            $('#modal-removeBooking').modal('hide');
                            $('#area_message').html(`
                                <section class="content-header px-0">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <i class="icon fa fa-check"></i>
                                        ${result.data}
                                    </div>
                                </section>
                            `);
                            $('#row-' + idBooking).parent().remove();
                            let rowspan = $(`td[data-start_date="${startDate}"]`).attr('rowspan');
                            $(`td[data-start_date="${startDate}"]`).attr('rowspan', rowspan - 1);
                            if (rowspan == 2) {
                                $(`td[data-start_date="${startDate}"]`).remove();
                            }
                            the_number_of_record -= 1;
                            if (the_number_of_record >= 2) {
                                $('#the_number_of_records').html(the_number_of_record + " Records")
                            } else if (the_number_of_record === 1) {
                                $('#the_number_of_records').html(the_number_of_record + " Record")
                            } else {
                                $('#no-data').removeClass('d-none');
                                $('#no-data').addClass('d-block');
                                $('#no-data').html(message_no_data);
                                $('#the_number_of_records').addClass('d-none');
                                $('#booking_list').addClass('d-none');
                            }
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function(result) {
                        toastr.error(result.message);
                    }
                })
            }

        })
        $('#btnConfirmRemoveStep2').click(function() {
            var idBooking = $('#booking_id').val();
            var startDate = $(this).attr('data-date');
            $.ajax({
                url: "/student/remove-booking/list",
                dataType: "json",
                type: 'post',
                data: { idBooking: idBooking },
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                },
                beforeSend: function() {
                    $(this).html(
                        `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>Loading...`
                    );
                },
                success: function(result) {
                    if (result.status) {
                        $('#modal-removeBooking').modal('hide');
                        $('#area_message').html(`
                            <section class="content-header px-0">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.data}
                                </div>
                            </section>
                        `);
                        $('#row-' + idBooking).parent().remove();
                        let rowspan = $(`td[data-start_date="${startDate}"]`).attr('rowspan');
                        $(`td[data-start_date="${startDate}"]`).attr('rowspan', rowspan - 1);
                        if (rowspan == 2) {
                            $(`td[data-start_date="${startDate}"]`).remove();
                        }
                        the_number_of_record -= 1;
                        if (the_number_of_record >= 2) {
                            $('#the_number_of_records').html(the_number_of_record + " Records")
                        } else if (the_number_of_record === 1) {
                            $('#the_number_of_records').html(the_number_of_record + " Record")
                        } else {
                            $('#no-data').removeClass('d-none');
                            $('#no-data').addClass('d-block');
                            $('#no-data').html(message_no_data);
                            $('#the_number_of_records').addClass('d-none');
                            $('#booking_list').addClass('d-none');
                        }
                        $('#modalConfirmRemoveBooking').modal('hide');
                    } else {
                        toastr.error(result.message);
                        $('#modalConfirmRemoveBooking').modal('hide');
                    }
                },
                error: function(result) {
                    toastr.error(result.message);
                }
            })
        })
    }
})
$(document).ready(function() {
    STUDENT_LESSON_BOOKING.init();
});
