let HOME = {};
let teacher_wait_student_message = $("[name=wait-student-message]").attr('content');

$(function () {
    HOME.init = function () {
        HOME.showPopup();
    };
    HOME.showPopup = () => {
        let data1 = $.cookie('notification_to_student_start_lesson');
        if(JSON.parse(data1).teacher_id == $('#user_login').val()) {
            $('#loading').addClass('d-block')
            $('#loading').removeClass('d-none')
            $('#loading_message').html(teacher_wait_student_message);
            setTimeout(() => {
                $('#loading').removeClass('d-block')
                $('#loading').addClass('d-none')
                $('#loading_message').html("");
                $('#modal_after_5_minutes').modal('show');
                $('#modal_teacher_start_lesson').modal('hide');
            },JSON.parse(data1).expires - new Date().getTime());
        }
    }
});

$(document).ready(function () {
    HOME.init();
});
