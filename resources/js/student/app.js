let APP = {};
const routeRequestCancel = $("[name=route-timeout]").attr('content');

$(function () {
    APP.init = function () {
        $('.select2').select2();
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
        APP.waitingTeacherStartLesson();
        $('.bs-timepicker').timepicker();
        APP.datepicker();
    };

    APP.datepicker = function() {
        $(".datepicker").datepicker({
            format: 'yyyy/mm/dd',
            todayHighlight: true,
            autoclose: true,
        })
    }
    APP.waitingTeacherStartLesson = function () {
        let data  = $.cookie('notification_student_sudden_lesson');
        if (typeof data !== 'undefined'){
            data = JSON.parse(data);
            let expDate = new Date();
            let time = data.expires - expDate.getTime();

            $('#modalSuddenTeacher').modal('hide');
            $('#loading_wait_teacher').show();
            timeoutOfTeacher = setTimeout(() => {
                // cancelRequest();
                let formData = new FormData();
                formData.append( 'start_hour', data.start_hour );
                formData.append( 'start_date', data.start_date );
                formData.append( 'course_id', data.course_id );
                formData.append( 'lesson_id', data.lesson_id );
                formData.append( 'student_id', data.student_id );
                formData.append( 'coin', data.coin );
                formData.append( 'type', data.type );
                formData.append( 'teacher_id', data.teacher_id );

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "POST",
                    url: routeRequestCancel,
                    data: formData,
                    cache:false,
                    contentType: false,
                    processData: false,
                    success: function(result){
                        $('#loading_wait_teacher').hide();
                        // if (typeof $.cookie('notification_student_sudden_lesson') !== 'undefined'){
                        //     $('#modalCancelRequest').modal('show');
                        // }
                        $('#modalCancelRequest').modal('show');
                    },
                    error: function(result){
                    }
                });
            },time )
        }

        //case: start lesson at booking list so it will not change from booked to free time in teacher_schedule table
        let data_booked  = $.cookie('notification_student_booked');
        if(typeof data_booked !== 'undefined') {
            data_booked = JSON.parse(data_booked);
            let expDate = new Date();
            let time = data_booked.expires - expDate.getTime();

            $('#modalSuddenTeacher').modal('hide');
            $('#loading_wait_teacher').show();
            timeoutOfTeacher = setTimeout(() => {
                $('#loading_wait_teacher').hide();
                $('#modalCancelRequest').modal('show');
            },time )
        }
    }
});

$(document).ready(function () {
    APP.init();
});
