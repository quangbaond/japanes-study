let NOTIFICATION = {};
let routeStartMeetingRoom = $("[name=route-start-meeting-room]").attr('content');
let routeCancelMeetingRoom = $("[name=route-cancel-meeting-room]").attr('content');
let routeNotifyStartLesson = $("[name=routeNotifyStartLesson]").attr('content');
let routeTeacherMyPage = $("[name=route-teacher-mypage]").attr('content');
let routStudentBookingList = $("[name=route-student-booking-list]").attr('content');
let routeNotifyAfter5Minutes = $("[name=routeNotifyAfter5Minutes]").attr('content');
let routeNotifyToTeacher = $("[name=routeNotifyToTeacher]").attr('content');
let messageTeacherStatus = $("[name=message-teacher-status]").attr('content');
let messageTeacherInviteJoinLesson = $("[name=msg-teacher-invite-join-lesson]").attr('content');
let messageTeacherCancelLesson = $("[name=msg-teacher-cancel-lesson]").attr('content');
let routeNotification = $("[name=routeNotification]").attr('content');
let csrf_token = $("[name=csrf-token]").attr('content');
let timeoutOfTeacher;
let M040_content = $("[name=M040_content]").attr('content');
let M040_title = $("[name=M040_title]").attr('content');
const BOOKING_STATUS = 1;
const BOOKED_STATUS = 2;
const CANCEL_STATUS = 3;
const TEACHER_START_MEETING_ROOM_STATUS = 1;
const TEACHER_CANCEL_MEETING_ROOM_STATUS = 2;
const TEACHER_START_LESSON_STATUS = 3;
let countTime = null;
let toggleModal=null;
var checkCloseModalConfirm = false;
let teacher_id_notify = 0;
let schedule_time = "00:00:00";
let schedule_id = null;

$(function () {
    NOTIFICATION.init = function () {
        NOTIFICATION.pushNotification();
        NOTIFICATION.startMeeting();
        NOTIFICATION.cancelMeeting();
        NOTIFICATION.clickStartLesson();
        NOTIFICATION.notify();
        $('.notification-active').first().addClass('active');

    };
    NOTIFICATION.notify = () => {
        let data  = $.cookie('notification');
        if (typeof data !== 'undefined'){
            data = JSON.parse(data);
            let expDate = new Date();
            let time = data.expires - expDate.getTime();
            $('#start_hour').val(data.start_hour);
            $('#start_date').val(data.start_date);
            $('#lesson_id').val(data.lesson_id);
            $('#course_id').val(data.course_id);
            $('#student_id').val(data.student_id);
            $('#coin').val(data.coin);
            $('#type').val(data.type);
            $('#book_type').val(data.book_type);
            $('#modalNotificationWhenBooked').modal({
                backdrop: 'static'
            });
            $('#modalNotificationWhenBooked').modal('show');
            window.onbeforeunload = function(e) {
                // cancelRequest();
                $('#modalNotificationWhenBooked').modal('hide');
                let fd = new FormData();
                fd.append( 'student_id', $('#student_id').val() );
                fd.append( 'start_hour', $('#start_hour').val() );
                fd.append( 'start_date', $('#start_date').val() );
                fd.append( 'lesson_id', $('#lesson_id').val() );
                fd.append( 'course_id', $('#course_id').val() );

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: routeCancelMeetingRoom,
                    data: fd,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function(result){
                    },
                    error: function(result){
                        // console.log(result);
                    }
                });
                return null;
            };
            timeoutOfTeacher = setTimeout(() => {
                // cancelRequest();
                $('#modalNotificationWhenBooked').modal('hide');
                let fd = new FormData();
                fd.append( 'student_id', $('#student_id').val() );
                fd.append( 'start_hour', $('#start_hour').val() );
                fd.append( 'start_date', $('#start_date').val() );
                fd.append( 'lesson_id', $('#lesson_id').val() );
                fd.append( 'course_id', $('#course_id').val() );

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: routeCancelMeetingRoom,
                    data: fd,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function(result){
                    },
                    error: function(result){
                        // console.log(result);
                    }
                });
                },time )
            data_to_cancel = data;
        }
    }
    NOTIFICATION.pushNotification = function() {
        let channel_user = pusher.subscribe('notification-user-'+$("#user_login").val());
        channel_user.bind('my-event', function(data) {
            //Add 1 for count_unread_notification
            let count_unread_notification = $("#count_unread_notification").text();
            $("#count_unread_notification").text(parseInt(count_unread_notification) + 1);
            let count_record = $('#element_notification .dropdown-item').length
            if(count_record >= 10){
                $('#element_notification .dropdown-item:last-child').remove()
            }
            // url notication detail
            routeNotification = routeNotification.replace(':id', data.id);
            // Add detail notification
            let html = `
                    <div class="dropdown-divider"></div>
                    <a href="${routeNotification}"  class="dropdown-item choice_notification" data-id="${data.id}">
                        <i class="fas fa-envelope mr-2"></i><strong>${data.title}</strong>
                        <span class="float-right text-muted text-sm">${data.created_at}</span>
                    </a>
                `;
            $("#element_notification").prepend(html);

            $(document).Toasts('create', {
                class: 'bg-warning',
                title: data.title,
                subtitle: data.created_at,
                // body: "Nội dung: " + data.content,
                icon: 'fas fa-envelope fa-lg',
                autohide: true,
                delay: 5000,
            });
            $('#toastsContainerTopRight').on('click', function () {
                window.location.href = routeNotification;
            });
        });
        // let channel_all_user = pusher.subscribe('notification-all-user');
        // channel_all_user.bind('my-event', function(data) {
        //     $(document).Toasts('create', {
        //         class: 'bg-info',
        //         icon: 'fas fa-envelope fa-lg',
        //         title: data.title,
        //         subtitle: data.created_at,
        //         autohide: true,
        //         delay: 5000,
        //     })
        // });
        let channel_teacher_is_booked = pusher.subscribe('notification-open-lesson-teacher-'+$("#user_login").val());
        channel_teacher_is_booked.bind('my-event', function(data) {
            $(document).ready(() => {

                if(data.type === BOOKING_STATUS) {
                    $(document).Toasts('create', {
                        class: 'bg-info',
                        title: 'お知らせ',
                        body: "今すぐレッスンするようにリクエスト生徒がいます。",
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 5000,
                    });
                }
                else if(data.type === BOOKED_STATUS){
                    $.removeCookie('notification_to_student_start_lesson', { path: '/' });
                    $('#loading').addClass('d-none')
                    $('#loading').removeClass('d-block')
                    $('#message_join_meeting').removeClass('d-none');
                    let expDate = new Date();
                    // console.log(data.data);
                    let data_cookies = { ...data.data,
                        'expires' : expDate.getTime() + (3 * 60 * 1000)
                    };
                    expDate.setTime(expDate.getTime() + (3 * 60 * 1000)); // add 3 minutes

                    $.cookie('notification', JSON.stringify(data_cookies), { path: '/', expires: expDate });
                    $('#start_hour').val(data.data.start_hour);
                    $('#start_date').val(data.data.start_date);
                    $('#lesson_id').val(data.data.lesson_id);
                    $('#course_id').val(data.data.course_id);
                    $('#student_id').val(data.data.student_id);
                    $('#coin').val(data.data.coin);
                    $('#type').val(data.data.type);
                    $('#type').val(data.data.type);
                    $('#book_type').val(data.data.book_type);
                    $('#teacher_id').val(data.data.teacher_id);
                    $('#modalNotificationWhenBooked').modal({
                        backdrop: 'static'
                    });
                    $('#modalNotificationWhenBooked').modal('show');
                    window.onbeforeunload = function(e) {
                        // cancelRequest();
                        $('#modalNotificationWhenBooked').modal('hide');
                        let fd = new FormData();
                        fd.append( 'student_id', $('#student_id').val() );
                        fd.append( 'start_hour', $('#start_hour').val() );
                        fd.append( 'start_date', $('#start_date').val() );
                        fd.append( 'lesson_id', $('#lesson_id').val() );
                        fd.append( 'course_id', $('#course_id').val() );

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: routeCancelMeetingRoom,
                            data: fd,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: function(result){
                            },
                            error: function(result){
                                // console.log(result);
                            }
                        });
                        return null;
                    };
                    timeoutOfTeacher = setTimeout(() => {
                        // cancelRequest();
                        $('#modalNotificationWhenBooked').modal('hide');
                        let fd = new FormData();
                        fd.append( 'student_id', $('#student_id').val() );
                        fd.append( 'start_hour', $('#start_hour').val() );
                        fd.append( 'start_date', $('#start_date').val() );
                        fd.append( 'lesson_id', $('#lesson_id').val() );
                        fd.append( 'course_id', $('#course_id').val() );

                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: "POST",
                            url: routeCancelMeetingRoom,
                            data: fd,
                            cache:false,
                            contentType: false,
                            processData: false,
                            success: function(result){
                            },
                            error: function(result){
                                // console.log(result);
                            }
                        });
                    },60000 * 3 ) //60s * 3p
                    data_to_cancel = data.data;
                }
                else if (data.type === CANCEL_STATUS) {
                    $(document).Toasts('create', {
                        class: 'bg-warning',
                        title: 'お知らせ',
                        body: "生徒がリクエストをキャンセルしました。",
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 5000,
                    });
                }
            })
        });

        let channel_student_when_started_room_by_teacher = pusher.subscribe('notification-open-lesson-student-' +$ ("#user_login").val());
        channel_student_when_started_room_by_teacher.bind('my-event', function(data) {
            $(document).ready(() => {
                if(data.type === TEACHER_START_MEETING_ROOM_STATUS) {

                    $('#loading_wait_teacher').hide();
                    $('#zoom_link').attr('href',data.data);
                    $('#message_join_meeting').removeClass('d-none');
                    $('#message_join_meeting').addClass('d-block');
                    $('#btnBookSchedule').html(messageTeacherStatus);
                    $('#btnBookSchedule').attr('disabled', 'disabled');
                    $('#btnCreateTeacher').attr('disabled', 'disabled');
                    window.open(data.data, '_blank');
                    $.removeCookie('notification_student_sudden_lesson', {
                        path: '/'
                    });
                    $.removeCookie('notification_student_booked', {
                        path: '/'
                    });
                    if(window.location.pathname === '/student/lesson/list') {
                        window.location.reload();
                    }
                }
                if(data.type === TEACHER_CANCEL_MEETING_ROOM_STATUS) {
                    let data_sudden  = $.cookie('notification_student_sudden_lesson');
                    let data_booked  = $.cookie('notification_student_booked');
                    if (typeof data_sudden !== 'undefined' || typeof data_booked !== 'undefined'){
                        $.removeCookie('notification_student_sudden_lesson', {
                            path: '/'
                        });
                        $.removeCookie('notification_student_booked', {
                            path: '/'
                        });
                        $('#loading_wait_teacher').hide();
                        $('#modalCancelRequest').modal('show');
                    }
                }
            })
        });

        //channel notify to student when teacher start lesson on teacher's my-page
        let channel_notification_teacher_start_lesson_to_student = pusher.subscribe('notification-student-teacher-my-page-' +$ ("#user_login").val());
        channel_notification_teacher_start_lesson_to_student.bind('my-event', function(data) {
            $(document).ready(() => {
                teacher_id_notify = data.teacher_id;
                if(data.type === TEACHER_START_MEETING_ROOM_STATUS) {
                    $(document).Toasts('create', {
                        class: 'bg-info',
                        title: 'Notification',
                        body: `${messageTeacherInviteJoinLesson}`,
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 5000,
                    });
                    $('#modalSuddenTeacher').modal('hide');
                    // $('#modal_teacher_start_lesson').modal('hide');
                    // $('#modal_teacher_invite_join_lesson').modal('show');
                    // $('#modal_teacher_cancel_lesson').modal('hide');
                }
                else if(data.type === TEACHER_CANCEL_MEETING_ROOM_STATUS) {
                    $(document).Toasts('create', {
                        class: 'bg-warning',
                        title: 'Notification',
                        body: `${messageTeacherCancelLesson}`,
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 5000,
                    });
                    $('#modalSuddenTeacher').modal('hide');
                    // $('#modal_teacher_start_lesson').modal('hide');
                    // $('#modal_teacher_invite_join_lesson').modal('hide');
                    // $('#modal_teacher_cancel_lesson').modal('show');
                }
                else if(data.type === TEACHER_START_LESSON_STATUS) {
                    console.log(data);
                    $('#modal_review_lesson').modal('hide');
                    $('#modalSuddenTeacher').modal('hide');
                    $.removeCookie('notification_student_sudden_lesson', { path: '/' });
                    $.removeCookie('notification_student_booked', { path: '/' });
                    $('#loading_wait_teacher').hide();
                    let dataCookies = {
                        "student_id": $('#user_login').val(),
                        "schedule_id": data.schedule_id,
                        'teacher_id' : data.teacher_id,
                        'schedule_time': data.schedule_time,
                    }
                    let expDate = new Date();
                    let data_cookies = { ...dataCookies,
                        'expires' : expDate.getTime() + (300000)
                    };
                    expDate.setTime(expDate.getTime() + (300000)); // add 5 minutes
                    $.cookie('notification_require_student_join_lesson', JSON.stringify(data_cookies), { path: '/', expires: expDate });
                    $('#loading').addClass('d-none')
                    $('#loading').removeClass('d-block')
                    $('#modal_teacher_start_lesson').modal({
                        backdrop: 'static'
                    });
                    $('#modal_teacher_start_lesson').modal('show');
                    // $('#modal_teacher_invite_join_lesson').modal('hide');
                    // $('#modal_teacher_cancel_lesson').modal('hide');
                    schedule_time = data.schedule_time;
                    schedule_id = data.schedule_id;
                    toggleModal = setTimeout(()=>{
                        // NOTIFICATION.notifyTeacherDontStartLessonAfter5minutes();
                        $('#modal_teacher_start_lesson').modal('hide');
                    },300000);
                }
            })
        });

        let channel_notification_teacher_when_student_start_lesson = pusher.subscribe('notification-student-notify-teacher-' + $ ("#user_login").val());
        channel_notification_teacher_when_student_start_lesson.bind('my-event', function(data) {
            $(document).ready(() => {
                if(data.type === 4) {
                    $(document).Toasts('create', {
                        class: 'bg-info',
                        title: `${data.data.title}`,
                        body: `<a href="${routeTeacherMyPage}" style="color: white">${data.data.content}</a>`,
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 10000,
                    });
                }
            })
        });

        let channel_admin_notify_to_student_and_teacher = pusher.subscribe('notification-admin-notify-' + $ ("#user_login").val());
        channel_admin_notify_to_student_and_teacher.bind('my-event', function(data) {
            $(document).ready(() => {
                // console.log(data);
                if(data.type === 1) {
                    $(document).Toasts('create', {
                        class: 'bg-info',
                        title: `${M040_title}`,
                        body: `<a href="${routStudentBookingList}" style="color: white">${M040_content}</a>`,
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 10000,
                    });
                }
                if(data.type === 2) {
                    $(document).Toasts('create', {
                        class: 'bg-info',
                        title: `${M040_title}`,
                        body: `<a href="${routeTeacherMyPage}" style="color: white">${M040_content}</a>`,
                        icon: 'fas fa-envelope fa-lg',
                        autohide: true,
                        delay: 10000,
                    });
                }
            })
        });
    }


    NOTIFICATION.notifyTeacherStudentStartLesson = () => {
        $("#loading").removeClass('d-none');
        $('#loading').addClass("d-block");
        let data = $.cookie('notification_require_student_join_lesson');
        data = JSON.parse(data);
        let fd = new FormData();

        fd.append('teacher_id', data.teacher_id);
        fd.append('schedule_time', data.schedule_time);
        fd.append('schedule_id', data.schedule_id);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrf_token
            }
        });
        $.ajax({
            type: "POST",
            url: routeNotifyStartLesson,
            data: fd,
            cache: false,
            contentType: false,
            processData: false,
            success: function (result) {
                $('#modal_teacher_start_lesson').modal('hide')
                $("#loading").removeClass('d-block');
                $('#loading').addClass("d-none");
                $('#zoom_link').attr('href',result.data.zoom_url);
                $('#message_join_meeting').removeClass('d-none');
                window.open(result.data.zoom_url, '_blank');
                $.removeCookie('notification_require_student_join_lesson', { path: '/' });
            },
            error: function (result) {
            }
        });
    }


    NOTIFICATION.clickStartLesson = () => {
        $('#btn_student_start_lesson').click(function () {
            clearTimeout(toggleModal);
            clearTimeout(countTime);
            NOTIFICATION.notifyTeacherStudentStartLesson();
        })
    }

    //start meeting room of teacher
    //write here because i can not import js file
    NOTIFICATION.startMeeting = () => {
        $('#startMeetingWithStudent').click(() => {
            $.removeCookie('notification', { path: '/' });
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            clearTimeout(timeoutOfTeacher);
            checkCloseModalConfirm = true;
            let fd = new FormData();
            fd.append( 'start_hour', $('#start_hour').val() );
            fd.append( 'start_date', $('#start_date').val() );
            fd.append( 'course_id', $('#course_id').val() );
            fd.append( 'lesson_id', $('#lesson_id').val() );
            fd.append( 'student_id', $('#student_id').val() );
            fd.append( 'coin', $('#coin').val() );
            fd.append( 'type', $('#type').val() );
            fd.append( 'book_type', $('#book_type').val() );
            fd.append( 'teacher_id', $('#teacher_id').val() );
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: routeStartMeetingRoom,
                data: fd,
                cache:false,
                contentType: false,
                processData: false,
                success: function(result){
                    $('#modalNotificationWhenBooked').modal('hide');
                    $("#loading").removeClass('d-block');
                    $('#loading').addClass("d-none");
                    if (!result.status) {
                        // console.log(result);
                    }
                    else {
                        $('#zoom_link').attr('href',result.data.data);
                        $('#message_join_meeting').removeClass('d-none');
                        $('#message_join_meeting').addClass('d-block');
                        window.open(result.data.data, '_blank');
                    }
                },
                error: function(result){
                    // console.log(result);
                }
            });
        })
    }
    NOTIFICATION.cancelMeeting = () => {
        $('#modalNotificationWhenBooked').on('hide.bs.modal', () => {
            if(checkCloseModalConfirm === true) {
                checkCloseModalConfirm = false;
                return true;
            }
            $.removeCookie('notification', { path: '/' });
            let fd = new FormData();
            fd.append( 'student_id', $('#student_id').val() );
            fd.append( 'start_hour', $('#start_hour').val() );
            fd.append( 'start_date', $('#start_date').val() );
            fd.append( 'lesson_id', $('#lesson_id').val() );
            fd.append( 'course_id', $('#course_id').val() );

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: routeCancelMeetingRoom,
                data: fd,
                cache:false,
                contentType: false,
                processData: false,
                success: function(result){
                },
                error: function(result){
                    // console.log(result);
                }
            });
        });
    }

});

$(document).ready(function () {
    NOTIFICATION.init();
});

