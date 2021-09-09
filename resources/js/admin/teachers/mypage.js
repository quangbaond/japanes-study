let TEACHER_MY_PAGE = {};
let RouteGetTodaySchedule = $("[name=routeGetTodaySchedule]").attr('content');
let RouteNotifyToStudent = $("[name=route-notify-student-start-lesson]").attr('content');
let RouteNotifyTeacherCancelLesson = $("[name=route-notify-teacher-cancel-lesson]").attr('content');
let RouteNotifyTeacherStartLesson = $("[name=route-notify-teacher-start-lesson]").attr('content');
let teacher_wait_student_message = $("[name=wait-student-message]").attr('content');
let csrf_token = $("[name=csrf-token]").attr('content');
let student_id = 0;
let schedule_time = "00:00:00";
let schedule_id = null;
let countTime = null;
$(function () {
    TEACHER_MY_PAGE.init = function () {
        TEACHER_MY_PAGE.todayScheduleDatatable();
        TEACHER_MY_PAGE.notifyToInviteStudent();
        TEACHER_MY_PAGE.notifyTeacherCancel();
        TEACHER_MY_PAGE.clickButtonNotify();
        TEACHER_MY_PAGE.notifyTeacherStartLesson();
        TEACHER_MY_PAGE.showVideo();
    };
    TEACHER_MY_PAGE.showVideo = function() {
        $('body').on('click', 'tbody td .btnShowVideo', function () {
            let videoLink = $(this).attr('data-video_link');
            let html = `<video id="clip" controls preload=auto playsinline muted autoplay class="intro-video" data-setup="{}">
                        <source src="${videoLink}" type='video/mp4'/>
                    </video>`;
            $('#modalShowVideo').find('.modal-body').html(html);
            $('#modalShowVideo').modal('show');
        })
    }
    TEACHER_MY_PAGE.clickButtonNotify = () => {
        //channel notify to teacher if student dont click start-lesson button after 5 minutes or click button start-lesson
        let channel_notification_teacher_when_student_start_lesson = pusher.subscribe('notification-student-notify-teacher-' + $ ("#user_login").val());
        channel_notification_teacher_when_student_start_lesson.bind('my-event', function(data) {
            $(document).ready(() => {
                if(data.type === 1) {
                    $.removeCookie('notification_to_student_start_lesson', { path: '/' });
                    clearTimeout(countTime);
                    $('#loading').addClass('d-none')
                    $('#loading').removeClass('d-block')
                    $('#message_join_meeting').removeClass('d-none');
                    $('#modal_teacher_start_lesson').modal('hide');
                    $('#zoom_link').attr('href',data.zoom_url);
                    window.open(data.zoom_url, '_blank');
                    location.reload();
                }
            })
        });
    }
    TEACHER_MY_PAGE.todayScheduleDatatable = () => {
        $('#today-schedule').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": false,
            "info": false,
            "autoWidth": false,
            "responsive": true,
            "language": {
                "url": "/Japanese.json"
            },
            ajax: {
                url: RouteGetTodaySchedule,
                type: 'GET',
            },
            columns: [
                {
                    data: 'start_hour', render: function (data) {
                        time = data.split(':');
                        return time[0] + ":" + time[1];
                    }
                },
                {data: 'student_id',},
                {data: 'user_nickname'},
                {data: 'user_email'},
                {data: 'course_name',},
                {data: 'lesson_name'},
                {data: 'actions'},
                // {data: 'video_link',},
                // {data: 'btn_start_lesson_now',},
            ],
            "columnDefs": [
                {"className": "dt-center", "targets": "_all"}
            ]
        });

    }

    TEACHER_MY_PAGE.notifyToInviteStudent = () => {
        $('#today-schedule tbody').on('click', 'td button', function (e){
            start_time = e.target.value;

            let before_time = new Date();
            before_time.setTime(before_time.getTime() + 5 * 60000)
            before_time = before_time.getTime();

            let after_time = new Date();
            after_time.setTime(after_time.getTime() - 10 * 60000)
            after_time = after_time.getTime();

            let today = new Date();
            let today_dd = today.getDate();
            let today_mm = today.getMonth()+1;
            let today_yyyy = today.getFullYear();
            if(today_dd<10)
            {
                today_dd='0'+today_dd;
            }

            if(today_mm<10)
            {
                today_mm='0'+today_mm;
            }
            start_time = today_yyyy+'-'+today_mm+'-'+today_dd + " " + start_time;
            start_time = new Date(start_time.replace(' ', 'T')).getTime();
            if(start_time > after_time && start_time < before_time) {
                let formData = new FormData();
                formData.append('student_id', e.target.name);
                formData.append('teacher_id', $('#user_login').val());
                $.ajaxSetup({
                    headers: {
                        "X-CSRF-TOKEN": csrf_token
                    }
                });
                $.ajax({
                    type: "POST",
                    url: RouteNotifyToStudent,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        student_id = e.target.name;
                        schedule_time = e.target.value;
                        schedule_id = e.target.getAttribute('content');
                        $('#modal_teacher_start_lesson').modal('show');
                    },
                    error: function (result) {
                    }
                });
            }
            else {
                $(this).attr('disabled','disabled');
                $('#modalLessonUnavailableNow').modal('show');
            }
        })
    }

    TEACHER_MY_PAGE.notifyTeacherCancel = () => {
        $('#btn_teacher_cancel').click(function () {
            let formdata = new FormData();
            formdata.append('student_id', student_id);
            formdata.append('teacher_id', $('#user_login').val());
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: RouteNotifyTeacherCancelLesson,
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    student_id = 0;
                },
                error: function (result) {
                }
            });
        })
    };

    TEACHER_MY_PAGE.notifyTeacherStartLesson = () => {
        $('#btn_teacher_start').click(function () {
            let formdata = new FormData();
            formdata.append('student_id', student_id);
            formdata.append('teacher_id', $('#user_login').val());
            formdata.append('schedule_time', schedule_time);
            formdata.append('schedule_id', schedule_id);
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: "POST",
                url: RouteNotifyTeacherStartLesson,
                data: formdata,
                cache: false,
                contentType: false,
                processData: false,
                success: function (result) {
                    let dataCookies = {
                        "student_id": student_id,
                        "teacher_id": $('#user_login').val(),
                        "schedule_id": schedule_id,
                    }
                    let expDate = new Date();
                    let data_cookies = { ...dataCookies,
                        'expires' : expDate.getTime() + (300000)
                    };
                    expDate.setTime(expDate.getTime() + (300000)); // add 5 minutes

                    $.cookie('notification_to_student_start_lesson', JSON.stringify(data_cookies), { path: '/', expires: expDate });

                    $('#modal_teacher_start_lesson').modal('hide');
                    student_id = 0;
                    $('#loading').addClass('d-block')
                    $('#loading').removeClass('d-none')
                    $('#loading_message').html(teacher_wait_student_message);
                    countTime = setTimeout(()=>{
                        $('#loading').addClass('d-none')
                        $('#loading').removeClass('d-block')
                        $('#loading_message').html("");
                        $('#modal_after_5_minutes').modal('show');
                        $('#modal_teacher_start_lesson').modal('hide');
                    },300000)
                },
                error: function (result) {
                }
            });
        })
    };

});

$(document).ready(function () {
    TEACHER_MY_PAGE.init();
});

