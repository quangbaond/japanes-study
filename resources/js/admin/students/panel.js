let STUDENT_UPDATE_COURSE = {};
let RouteUpdateCourse = $("[name=routeUpdateCourseLesson]").attr('content');
$(function () {
    STUDENT_UPDATE_COURSE.init = function () {
        STUDENT_UPDATE_COURSE.selectChange();
        STUDENT_UPDATE_COURSE.clickbtnSubmit();
        STUDENT_UPDATE_COURSE.showPopupJoinLesson();
    };

    STUDENT_UPDATE_COURSE.showPopupJoinLesson = () => {
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
    };

    STUDENT_UPDATE_COURSE.selectChange = () => {
        $('#course_id').change(function (e) {
            course_id = $('#course_id').val();
            // Ajax validation
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: RouteUpdateCourse,
                data: course_id,
                cache: false,
                contentType: false,
                processData: false,
                success: function success(result) {
                    if (result.status) {
                        if (result.data == 'next_lesson') {
                            lesson_name = result.message.lesson_name;
                            $('#lesson_name').val(lesson_name);
                        }
                    }
                },
                error: function error(error) {
                    // alert("Error");
                }
            });
        });
    }

    STUDENT_UPDATE_COURSE.clickbtnSubmit = () => {
        $('#btnSubmit').click(function () {
            var name = $("#course_id  option:selected").text();
            $('#current_course').html(name);
            $('#next_lesson').html($('#lesson_name').val());
        })
    };

});
$(document).ready(function () {
    STUDENT_UPDATE_COURSE.init();
});

