let MANAGER_TEACHER_BOOKING_SUBSTITUTE = {};
let routeStudentValidation = $("[name=route-student-validation]").attr('content');
let routeStudentBookingValidation = $("[name=route-student-booking-validation]").attr('content');
let routeValidateStudentCoin = $("[name=route-validate-student-coin]").attr('content');
let routeGetStudentLessonInfo = $("[name=route-get-student-lesson-info]").attr('content');
let routeStudentData = $("[name=route-booking-substitute-students]").attr('content');
let routeGetLessonsByCourse = $("[name=route-get-lessons-by-course]").attr('content');
let M012 = $("[name=M012]").attr('content');
let M071 = $("[name=M071]").attr('content');
let M074 = $("[name=M074]").attr('content');
let formData = $('#validateBookLesson');
let count = 0;
let Arr_BOOKING = [];
let csrf_token = $("[name=csrf-token]").attr('content');
let student_id = null;
let student_membership = null;
let firstTime = 1;
let choosing_schedule = null;
let choosing_lesson = null;
let previous_lesson = $('#lesson_id_select').val();
let previous_lesson_temp = null;
let previous_course = $('#course_id_select').val();
let checkTeacherCanTeach = null;
let arrayChooseLesson = [];

$(function () {
    MANAGER_TEACHER_BOOKING_SUBSTITUTE.init = function () {
        // $('#modal-lesson').modal('show');
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickSearch();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clearForm();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.toggleChangeTimepicker();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnValidate();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnClear();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnConfirm();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.chooseStudent();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.selectCourse();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnCloseChooseLesson();
        MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnChooseLesson();
            // MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnCancelConfirm();
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnCloseChooseLesson = () => {
        $('#btnCancelChooseLesson').click(function () {
            // console.log(checkTeacherCanTeach);
            if (!checkTeacherCanTeach) {
                $('#modal_lesson_title').html(
                    `<span class="text-warning mr-2" style="font-size: 20px">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                     <span style="color: #E53A40"> ${M071}</span>`
                );
                $('#lesson_id_select').html('');
                $('#lesson_id_select').attr('disabled', true);
                $('#course_id_select option').attr('selected', false);
                $('#course_id_select').find('option[id=course_empty]').remove();
                $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>')
                $('#btnChooseLesson').attr('disabled', true);
            } else {
                var now_course = $('#course_id_select').val();
                // console.log('now: ' + now_course, 'previous: ' + previous_course);
                if (now_course != previous_course) {
                    $('#course_id_select').val(previous_course);
                    $('#course_id_select').change();
                }
                $('#lesson_id_select option').attr('selected', false);
                $('#lesson_id_select').find(`option[value=${previous_lesson_temp}]`).attr('selected', true);
            }
            var row = $(`#${choosing_schedule}`).parent().parent().attr('id');
            // console.log(choosing_schedule);
            MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed('' + choosing_schedule, row);
            formData.find(`input[id='schedule-${choosing_schedule}']`).remove();
            formData.find(`input[id='schedule_${choosing_schedule}_lesson']`).remove();
            $(`#${choosing_schedule}`).removeClass('btn-warning')
            $(`#${choosing_schedule}`).addClass('btn-success');
            choosing_schedule = null;
        });
    }
    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnChooseLesson = () => {
        $('#btnChooseLesson').click(function () {
            checkTeacherCanTeach = 1;
            choosing_lesson = $('#lesson_id_select').val();
            previous_lesson = parseInt(choosing_lesson) + 1;
            // previous_lesson_temp = previous_lesson;
            formData.find(`input[id='schedule_${choosing_schedule}_lesson']`).remove();
            var temp = `<input type="text" value="${choosing_schedule}:${choosing_lesson}" name="schedule_lesson[]" hidden id="schedule_${choosing_schedule}_lesson">`
            formData.append(temp);
            $('#modal-lesson').modal('hide');
            var course_id = parseInt($('#course_id_select option:selected').val());
            var lesson_id =  parseInt($('#lesson_id_select option:selected').val());
            arrayChooseLesson.push(course_id + ':' + lesson_id);
            console.log(arrayChooseLesson);
            MANAGER_TEACHER_BOOKING_SUBSTITUTE.selectNextLesson();
        });
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.selectNextLesson = () => {
        let check_course = $('#course_id_select option:selected').next().is('option');
        if (check_course) {
            let check_lesson = $('#lesson_id_select option:selected').next().is('option');
            // console.log(1 +' : '+ check_lesson);
            if (check_lesson) {
                // console.log(2)
                setTimeout(() => {
                    let temp = $('#lesson_id_select option:selected');
                    temp.attr('selected', false);
                    temp.next().attr('selected', true);
                    previous_lesson_temp = $('#lesson_id_select').val();
                }, 100)
            } else {
                // console.log(3);
                // setTimeout(() => {
                $('#loading').addClass('d-block');
                let temp = $('#course_id_select option:selected');
                let val = temp.next().val();
                $('#course_id_select').val(val);
                temp.attr('selected', false);
                temp.next().attr('selected', true);
                previous_course = $('#course_id_select').val();
                // }, 100)
                temp = `<input type="text" value="${val}" name="get_lesson_by_course" id="get_lesson_by_course" hidden>`;
                $('#form-get-lesson').append(temp);
                var data = $('#form-get-lesson').serialize();
                $.ajax({
                    type: "GET",
                    url: routeGetLessonsByCourse,
                    data: data,
                    success: function success(result) {
                        $('#form-get-lesson').find(`input[id='get_lesson_by_course']`).remove();
                        if (result.data === 'lessons') {
                            let options = '';
                            $.each(result.message, function (index, value) {
                                options += `<option value="${value.id}">${value.name}</option>`;
                            });
                            $('#lesson_id_select').html(options);
                            $('#lesson_id_select option:first').attr('selected', true);
                        }
                        previous_lesson_temp = $('#lesson_id_select').val();
                        $('#loading').removeClass('d-block');
                    },
                    error: function error(_error) {
                        $('#loading').removeClass('d-block');
                        alert("Error server");
                    }
                });
            }
        } else {
            let check = $('#lesson_id_select option:selected').next().is('option')
            // console.log(4 + ': ' +check);
            if (check) {
                setTimeout(() => {
                    let temp = $('#lesson_id_select option:selected');
                    temp.attr('selected', false);
                    temp.next().attr('selected', true);
                    previous_lesson_temp = $('#lesson_id_select').val();
                }, 100)
            } else {
                // console.log(5);
                previous_lesson_temp = $('#lesson_id_select').val();
                previous_course = $('#course_id_select').val();
                return 1;
            }
        }
    };


    // MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnCancelConfirm = () => {
    //     $('#btnCancelConfirm').click(function () {
    //         $('#btnCancelBooking').click();
    //     });
    // };


    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickSearch = function () {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        $('#btnFormSearch').click(function () {

            $("#error_section").css('display', 'none');
            let invalid = true;
            $('#searchStudent').find('select,input').each(function () {
                if ($(this).val() != '') {
                    invalid = false;
                }
            });

            if (invalid) {
                $("#error_mes").html('検索項目を入力してください。');
                $("#error_section").css('display', 'block');
                $("html, body").animate({scrollTop: 300}, "slow");
                return false;
            }
            $('#error_require_choose_student').html('');

            // Clear error
            $('body').find('input').removeClass('is-invalid');
            $(".invalid-feedback-custom").html(''); // Get data form search teacher
            if (firstTime === 1) {
                $('#students').DataTable({
                    "paging": true,
                    "lengthChange": false,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "responsive": true,
                    "pagingType": "full_numbers",
                    "order": [[1, "desc"]],
                    'autoWidth': false,
                    language: {
                        "url": "/Japanese.json"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: routeStudentData,
                        type: 'GET',
                        data: function (d) {
                            d.student_email = $('#student_email').val();
                            d.student_id = $('#student_id').val();
                            d.student_name = $('#student_name').val();
                            d.company_name = $('#company_name option:selected').val();
                        }
                    },
                    columns: [
                        {data: 'checkbox', name: 'checkbox', orderable: false, searchable: false},
                        {data: 'id', name: 'id',},
                        {data: 'email', name: 'email', class: 'email'},
                        {data: 'nickname', name: 'nickname', class: 'nickname'},
                        {data: 'name', name: 'name', class: 'name'},
                    ],
                    "createdRow": function (row, data, rowIndex) {
                        $.each($('td[class=" nickname"]', row), function (colIndex, data) {
                            $(this).attr('data-toggle', "tooltip");
                            $(this).attr('data-placement', "top");
                            $(this).attr('data-original-title', $(data).html());
                        });
                        $.each($('td[class=" email"]', row), function (colIndex, data) {
                            $(this).attr('data-toggle', "tooltip");
                            $(this).attr('data-placement', "top");
                            $(this).attr('data-original-title', $(data).html());
                        });
                        $.each($('td[class=" name"]', row), function (colIndex, data) {
                            $(this).attr('data-toggle', "tooltip");
                            $(this).attr('data-placement', "top");
                            $(this).attr('data-original-title', $(data).html());
                        });
                    }
                });
            }
            firstTime = 2;
            var data = $("#formSearchStudent").serialize(); // Ajax

            $.ajax({
                type: "POST",
                url: routeStudentValidation,
                data: data,
                success: function success(result) {
                    if (result.status) {
                        $('#students').DataTable().draw(true);
                        student_id = null;
                    } else {
                        $.each(result.message, function (key, value) {
                            if (typeof value !== "undefined") {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key).closest('.form-group').find('span').html(value[0]);
                            }
                        });
                    }
                },
                error: function error(_error) {
                    alert("Error server");
                }
            });
        });
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clearForm = function () {
        $('#clearFormSearch').click(function () {
            $('#student_email').val('');
            $('#student_id').val('');
            $('#student_name').val('');
            $('#company_name').prop('selectedIndex', 0);
            $('.select2-selection__rendered').html('');
            $("#error_section").css('display', 'none');
        });
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.toggleChangeTimepicker = function () {
        $('.bs-timepicker').click(function (e) {
            if(student_id === null) {
                MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_require_choose_student', M012);
                var top = $('#error_require_choose_student').offset().top;
                $("html, body").animate({scrollTop: top - 10}, "slow");
                return 1;
            }
            var schedule_id = e.target.id;
            var date = $(`#${schedule_id}`).parents("tr:first").children("td:first").children().text();
            var text = $(`#${schedule_id}`).text();
            var row = $(`#${schedule_id}`).parent().parent().attr('id');
            var name = $(`#${schedule_id}`).attr('name');
            if ($(this).hasClass('btn-success')) {
                $(this).toggleClass('btn-warning')
                if ($(this).hasClass('btn-warning')) {
                    choosing_schedule = schedule_id;
                    $('#modal-lesson').modal('show');
                    count++;
                    // console.log('count: ' + count)
                    name = e.target.name;
                    value = e.target.value;
                    id = e.target.id;
                    temp = `<input type="text" value="${value}" name="${name}[]" hidden id="schedule-${id}">`
                    formData.append(temp);

                    var OBJ_BOOKING = {
                        schedule_id: [schedule_id],
                        value: [text],
                        name: [name],
                        row: row,
                        date: date.trim()
                    }
                    var index = Arr_BOOKING.findIndex(el => el.row === row);
                    if (index === -1) {
                        Arr_BOOKING.push(OBJ_BOOKING)
                    } else {
                        Arr_BOOKING.filter(item => {
                            if (item.row === row) {
                                item.schedule_id.push(schedule_id)
                                item.value.push(text)
                                item.name.push(name)
                            }
                        })
                    }
                } else {
                    $('#loading').addClass('d-block');
                    arrayChooseLesson.pop()
                    let lesson = arrayChooseLesson[arrayChooseLesson.length - 1];
                    console.log(lesson, arrayChooseLesson);
                    lesson = lesson.split(':');
                    let now_course = $('#course_id_select').val();
                    if (now_course != lesson[0]) {
                        $('#course_id_select').val(lesson[0]);
                        if (count === 1) {
                            $('#course_id_select').find('option[id=course_empty]').remove();
                            $('#lesson_id_select').attr('disabled', false);
                            $('#btnChooseLesson').attr('disabled', false);
                        }
                        let val = $('#course_id_select').val();
                        previous_course = val;
                        console.log('value: ' + lesson[0]);
                        temp = `<input type="text" value="${lesson[0]}" name="get_lesson_by_course" id="get_lesson_by_course" hidden>`;
                        $('#form-get-lesson').append(temp);
                        var data = $('#form-get-lesson').serialize();
                        $.ajax({
                            type: "GET",
                            url: routeGetLessonsByCourse,
                            data: data,
                            success: function success(result) {
                                $('#form-get-lesson').find(`input[id='get_lesson_by_course']`).remove();
                                if (result.data === 'lessons') {
                                    if(result.message.length > 0) {
                                        $('#modal_lesson_title').html(
                                            `<p class="modal-title font-weight-bold">以下は勉強する予定のコースです。生徒がそれを変更できます。</p>`
                                        );
                                    } else {
                                        $('#modal_lesson_title').html(
                                            `<span class="text-warning mr-2" style="font-size: 20px">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                            <span style="color: #E53A40"> ${M071}</span>`);
                                    }
                                    var options = '';
                                    $.each(result.message, function (index, value) {
                                        options += `<option value="${value.id}">${value.name}</option>`;
                                    });
                                    $('#lesson_id_select').html(options);
                                }
                                if (lesson[1] == 0 && lesson[0] == 0) {
                                    checkTeacherCanTeach = false;
                                }
                                $('#form-get-lesson').find(`input[id='get_lesson_by_course']`).remove();
                                previous_lesson = previous_lesson_temp = lesson[1];
                                // previous_course = lesson[0];
                                choosing_lesson = lesson[1];
                                $('#lesson_id_select option').attr('selected', false);
                                $('#lesson_id_select').find(`option[value=${lesson[1]}]`).attr('selected', true);
                                console.log('previous_lesson_temp: ' + previous_lesson_temp);
                                if (arrayChooseLesson.length > 1) {
                                    console.log('select next');
                                    // previous_lesson = previous_lesson_temp = parseInt(previous_lesson_temp) + 1;
                                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.selectNextLesson();
                                } else if (!checkTeacherCanTeach) {
                                    $('#lesson_id_select').html('');
                                    $('#lesson_id_select').attr('disabled', true);
                                    $('#course_id_select option').attr('selected', false);
                                    $('#course_id_select').find('option[id=course_empty]').remove();
                                    $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>')
                                    $('#btnChooseLesson').attr('disabled', true);
                                }
                                $('#loading').removeClass('d-block');
                            },
                            error: function error(_error) {
                                $('#loading').removeClass('d-block');
                                alert("Error server");
                            }
                        });
                    } else {
                        if (lesson[1] == 0 && lesson[0] == 0) {
                            checkTeacherCanTeach = false;
                        }
                        previous_lesson = previous_lesson_temp = lesson[1];
                        // previous_course = lesson[0];
                        choosing_lesson = lesson[1];
                        $('#lesson_id_select option').attr('selected', false);
                        $('#lesson_id_select').find(`option[value=${lesson[1]}]`).attr('selected', true);
                        if (arrayChooseLesson.length > 1) {
                            console.log('select next');
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.selectNextLesson();
                        } else if (!checkTeacherCanTeach) {
                            $('#lesson_id_select').html('');
                            $('#lesson_id_select').attr('disabled', true);
                            $('#course_id_select option').attr('selected', false);
                            $('#course_id_select').find('option[id=course_empty]').remove();
                            $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>')
                            $('#btnChooseLesson').attr('disabled', true);
                        }
                        $('#loading').removeClass('d-block');
                    }
                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed(schedule_id, row);
                    let id = e.target.id;
                    formData.find(`input[id='schedule-${id}']`).remove();
                    formData.find(`input[id='schedule_${id}_lesson']`).remove();
                }
            }
            MANAGER_TEACHER_BOOKING_SUBSTITUTE.sortArray();
            // if (count === 0) {
            //     $('#validate').prop('disabled', true);
            //     $('#btnConfirm').prop('disabled', true);
            // } else {
            //     $('#validate').prop('disabled', false);
            //     $('#btnConfirm').prop('disabled', false);
            // }
        });
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.sortArray = () => {
        for (i = 0; i < Arr_BOOKING.length; i++) {
            for (j = i + 1; j < Arr_BOOKING.length; j++) {
                if (Arr_BOOKING[i].row.split('row')[1] > Arr_BOOKING[j].row.split('row')[1]) {
                    obj = {};
                    obj = Arr_BOOKING[i]
                    Arr_BOOKING[i] = Arr_BOOKING[j]
                    Arr_BOOKING[j] = obj;
                }
            }
        }
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed = (schedule_id, row) => {
        Arr_BOOKING.map(item => {
            for (var i = 0; i < item.schedule_id.length; i++) {
                if (item.row === row) {
                    if (item.schedule_id[i] === schedule_id) {
                        item.schedule_id.splice(i, 1)
                        item.value.splice(i, 1)
                        item.name.splice(i, 1)
                    }
                }
            }
        })
        count--;
        // console.log('count -- : '+ count);
        // if (count === 0) {
        //     $('#validate').prop('disabled', true);
        //     $('#btnConfirm').prop('disabled', true);
        // } else {
        //     $('#validate').prop('disabled', false);
        //     $('#btnConfirm').prop('disabled', false);
        // }
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.displayPopup = (OBJ) => {
        if (OBJ.length < 0) return
        var html = ""
        for (var i = 0; i < OBJ.length; i++) {
            var text = ""
            var classText = ""
            // console.log(OBJ[i]);
            if (OBJ[i].schedule_id.length === 0) continue;
            for (var k = 0; k < OBJ[i].schedule_id.length; k++) {
                OBJ[i].date = OBJ[i].date.trim();
                if (OBJ[i].date.includes('土')) {
                    classText = "text-primary"
                } else if (OBJ[i].date.includes('日')) {
                    classText = "text-danger"
                }
                text += ` <button name="${OBJ[i].name[k]}"
                                class="bs-timepicker btn
                                btn-warning
                                mr-lg-5 mb-2 mt-1 text-center "
                                style="width: 65px; height: 40px"
                                value=""
                                id="temp-${OBJ[i].schedule_id[k]}">
                                ${OBJ[i].value[k]}
                          </button>`
            }
            html += `<tr>
                    <th hidden=""></th>
                    <td style="width: 130px">
                        <div class="mt-2">
                            <span class="text-center ${classText}">${OBJ[i].date}</span>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-between">
                            <div class="row ml-2" id="row{{$i}}">
                                <div class="d-flex">
                                    <div class="mb-2 mr-2">
                                    </div>
                                    ${text}
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>`
        }
        return html;
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnClear = () => {
        $('#btnCancelBooking').click(function () {
            choosing_schedule = null;
            choosing_lesson = null;
            previous_lesson_temp = null;
            arrayChooseLesson = [];
            if ($('.bs-timepicker').hasClass('btn-warning')) {
                $('.bs-timepicker').removeClass('btn-warning');
                $('#error_section_confirm').html('');
                $('#error_section').html('');
                $.each(Arr_BOOKING, function (index, value) {
                    $.each(value.schedule_id, function (i, v) {
                        $(`#${v}`).removeClass('btn-warning');
                        formData.find(`input[id='schedule-${v}']`).remove();
                        formData.find(`input[id='schedule_${v}_lesson']`).remove();
                        var row = $(`#${v}`).parent().parent().attr('id');
                    });
                });
            }
            $('input[name ="user_id"]').prop('checked', false);
            Arr_BOOKING = [];
            count = 0;
            student_id = null;
            student_membership = null;
            MANAGER_TEACHER_BOOKING_SUBSTITUTE.clearErrorSection();
        });
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.selectCourse = () => {
        $('#course_id_select').on('change', () => {
            $('#loading').addClass('d-block');
            if(count === 1) {
                $('#course_id_select').find('option[id=course_empty]').remove();
                $('#lesson_id_select').attr('disabled',false);
                $('#btnChooseLesson').attr('disabled',false);
            }
            let val = $('#course_id_select').val();
            previous_course = val;
            // console.log('value: ' + val);
            temp = `<input type="text" value="${val}" name="get_lesson_by_course" id="get_lesson_by_course" hidden>`;
            $('#form-get-lesson').append(temp);
            var data = $('#form-get-lesson').serialize();
            $.ajax({
                type: "GET",
                url: routeGetLessonsByCourse,
                data: data,
                success: function success(result) {
                    $('#form-get-lesson').find(`input[id='get_lesson_by_course']`).remove();
                    if (result.data === 'lessons') {
                        if(result.message.length > 0) {
                            $('#modal_lesson_title').html(
                                `<p class="modal-title font-weight-bold">以下は勉強する予定のコースです。生徒がそれを変更できます。</p>`
                            );
                        } else {
                            $('#modal_lesson_title').html(
                                `<span class="text-warning mr-2" style="font-size: 20px">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                <span style="color: #E53A40"> ${M071}</span>`
                            );
                        }
                        // console.log('nhay vao day', result.message)

                        var options = '';
                        if(result.message.length < 1) {
                            var options = `<option selected></option>`;
                            $('#lesson_id_select').html(options);
                            return 1;
                        }
                        $.each(result.message, function (index, value) {
                            options += `<option value="${value.id}" ${value.id == previous_lesson_temp? 'selected': ''}>${value.name}</option>`;
                        });
                        $('#lesson_id_select').html(options);
                        // previous_lesson_temp = previous_lesson;
                        previous_lesson = $('#lesson_id_select option:first').val();
                    }
                    $('#loading').removeClass('d-block');
                },
                error: function error(_error) {
                    $('#loading').removeClass('d-block');
                    alert("Error server");
                }
            });
        })
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.chooseStudent = () => {
        $("body").on("change", ".chk_item", function (e) {
            $('#loading').addClass('d-block');
            $('#btnCancelBooking').click();
            $('#lesson_id_select').html('');
            $('#course_id_select option').attr('selected', false)
            $('#course_id_select').find('option[id=course_empty]').remove();
            student_id = e.target.id.split('-')[1];
            $(`#${e.target.id}`).prop('checked', true);
            student_membership = e.target.value;
            let form_data = new FormData();
            form_data.append('student_id', student_id);
            form_data.append('_token', csrf_token);
            //get student lesson info
            $.ajax({
                type: "post",
                url: routeGetStudentLessonInfo,
                data: form_data,
                contentType:false,
                processData: false,
                success: function success(result) {
                    if (result.data === 'last_lesson') {
                        if(!result.message.check_teacher_can_teach) {
                            if(result.message.check_latest_lesson) {
                                $('#modal_lesson_title').html(
                                    `<span class="text-warning mr-2" style="font-size: 20px">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                <span style="color: #E53A40"> ${M074}</span>`
                                );
                            }
                            else {
                                $('#modal_lesson_title').html(
                                    `<span class="text-warning mr-2" style="font-size: 20px">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    </span>
                                <span style="color: #E53A40"> ${M071}</span>`
                                );
                            }
                            checkTeacherCanTeach = 0 ;
                            $('#course_id_select').find('option[id=course_empty]').remove();
                            $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>');
                            $('#btnChooseLesson').attr('disabled', true);
                            $('#lesson_id_select').attr('disabled', true);
                            arrayChooseLesson.push('0:0');
                        } else {
                            checkTeacherCanTeach = 1;
                            $('#lesson_id_select').attr('disabled', false);
                            $('#btnChooseLesson').attr('disabled', false);
                            $('#course_id_select').find('option[id=course_empty]').remove();
                            let options = '';
                            $('#course_id_select').find(`option[value=${result.message.last_lesson.course_id}]`).attr('selected',true)
                            $.each(result.message.lessons, function (index, value) {
                                options += `<option value="${value.id}"
                               ${value.id === result.message.last_lesson.lesson_id ? 'selected': ''}> ${value.name}
                                </option>`;
                            })
                            $('#lesson_id_select').html(options);
                            $('#btnChooseLesson').attr('disabled', false);
                            $('#modal_lesson_title').html(
                                `<p class="modal-title font-weight-bold">以下は勉強する予定のコースです。生徒がそれを変更できます。</p>`
                            );
                            arrayChooseLesson.push(result.message.last_lesson.course_id + ':' + result.message.last_lesson.lesson_id);
                            previous_course = result.message.last_lesson.course_id;
                            previous_lesson = result.message.last_lesson.lesson_id;
                            previous_lesson_temp = previous_lesson;
                            $('#lesson_id_select').html(options);
                        }
                     }
                    $('#loading').removeClass('d-block');
                },
                error: function error(_error) {
                    $('#loading').removeClass('d-block');
                    alert('Error server');
                }
            });
        });
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon = (error_id, message) => {
        $(`#${error_id}`).html(`
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-ban"></i>
                <span id="error_mes">${message}</span>
            </div>
        `);
    };

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clearErrorSection = () => {
        $('#error_section_confirm').html('');
        $('#error_require_choose_student').html('');
        $('#error_section_require_schedule').html('');
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnValidate = () => {
        $('#validate').click(function () {
            if(!(count > 0)) {
                $('#btnConfirm').prop('disabled', true);
            }
            else {
                $('#btnConfirm').prop('disabled', false);
            }
            formData.find(`input[name='student_id']`).remove();
            formData.find(`input[name='student_membership']`).remove();
            let top = 0;
            MANAGER_TEACHER_BOOKING_SUBSTITUTE.clearErrorSection();
            if (student_id === null && count !==0) {
                MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_require_choose_student', M012);
                top = $('#error_require_choose_student').offset().top;
                $("html, body").animate({scrollTop: top - 10}, "slow");
            }

            if (student_id === null && count === 0) {
                MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_require_choose_student', M012);
                MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_require_schedule', M012);
                top = $('#error_require_choose_student').offset().top <= $('#error_section_require_schedule').offset().top ? $('#error_require_choose_student').offset().top : $('#error_section_require_schedule').offset().top;
                $("html, body").animate({scrollTop: top - 10}, "slow");
            }

            if(student_id !== null && count ===0) {
                MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_require_schedule', M012);
                top = $('#error_section_require_schedule').offset().top;
                $("html, body").animate({scrollTop: top - 10}, "slow");
            }

            if (student_id !== null && count !== 0) {
                var temp = `<input type="text" value="${student_id}" name="student_id" hidden>
                        <input type="text" value="${student_membership}" name="student_membership" hidden>`
                formData.append(temp);
                var data = formData.serialize();
                $.ajax({
                    type: "POST",
                    url: routeStudentBookingValidation,
                    data: data,
                    success: function success(result) {
                        if (result.data === 'error') {
                            $.each(result.message, (index, value) => {
                                if (index != 'message') {
                                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeSchedule(value);
                                    var row = $(`#${value}`).parent().parent().attr('id');
                                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed('' + value, row);
                                } else {
                                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_require_schedule', value)
                                    $("html, body").animate({scrollTop: $('#error_section_require_schedule').offset().top - 10}, "slow");
                                }
                            });
                        } else if (result.data === 'updated') {
                            $.each(result.message, (index, value) => {
                                if (index != 'message') {
                                    var schedule = index.split(':');
                                    var current_row = $(`#${schedule[1]}`).parent().parent().attr('id');
                                    // console.log(schedule);
                                    var new_row =  schedule[0];
                                    var schedule_id = schedule[1];
                                    var date = schedule[2];
                                    if(current_row !== new_row) {
                                        $(`#${schedule_id}`).attr('name', date);
                                        $(`#${new_row}`).append($(`#${schedule_id}`));
                                    }
                                    $(`#${new_row}`).parent().parent().parent().attr('hidden',false)
                                    $(`#${schedule_id}`).removeClass('btn-warning')
                                    $(`#${schedule_id}`).addClass('border-danger');
                                    $(`#temp-${schedule_id}`).removeClass('btn-warning')
                                    $(`#temp-${schedule_id}`).addClass('border-danger btn-success');
                                    var time = value.split(':');
                                    $(`#${schedule_id}`).text(time[0] + ':' + time[1]);
                                    $(`#temp-${schedule_id}`).text(time[0] + ':' + time[1]);
                                    formData.find(`input[id='schedule-${schedule_id}']`).remove();
                                    formData.find(`input[id='schedule_${schedule_id}_lesson']`).remove();
                                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed('' + schedule_id, current_row);
                                } else {
                                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_require_schedule', value);
                                    $("html, body").animate({scrollTop: $('#error_section_require_schedule').offset().top - 10}, "slow");
                                }
                            });
                        }
                        else if (result.data === 'error_premium_is_expired') {
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_require_choose_student',result.message)
                            $("html, body").animate({scrollTop: $('#error_require_choose_student').offset().top - 10}, "slow");
                        }
                        else if (result.message === 'Success') {
                            $('#confirm').html(MANAGER_TEACHER_BOOKING_SUBSTITUTE.displayPopup(Arr_BOOKING));
                            $('#modalConfirm-Booking').modal('toggle');
                        }
                    },
                    error: function error(_error) {
                        alert("Error server");
                    }
                });
            }
            // console.log('count:'+ count);
            if(!(count > 0)) {
                $('#btnConfirm').prop('disabled', true);
            }
            else {
                $('#btnConfirm').prop('disabled', false);
            }
        });
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeSchedule = (value) => {
        $(`#${value}`).removeClass('btn-warning btn-success')
        $(`#${value}`).addClass('btn-secondary border-danger');
        $(`#${value}`).prop('disabled', true);
        $(`#temp-${value}`).removeClass('btn-warning btn-success')
        $(`#temp-${value}`).addClass('btn-secondary border-danger');
        $(`#temp-${value}`).prop('disabled', true);
        formData.find(`input[id='schedule-${value}']`).remove();
        formData.find(`input[id='schedule_${value}_lesson']`).remove();
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.clickBtnConfirm = () => {
        $('#btnConfirm').click(function () {
            let data = formData.serialize();
            MANAGER_TEACHER_BOOKING_SUBSTITUTE.validateSchedule(data);
        });
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.validateSchedule = (data) => {
        $('#loading').addClass('d-block');
        $.ajax({
            type: "POST",
            url: routeStudentBookingValidation,
            data: data,
            success: function success(result) {
                if (result.data === 'error') {
                    $('#loading').removeClass('d-block');
                    $.each(result.message, (index, value) => {
                        if (index != 'message') {
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeSchedule(value);
                            var row = $(`#${value}`).parent().parent().attr('id');
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed('' + value, row);
                        } else {
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_confirm', value)
                        }
                    });
                } else if (result.data === 'updated') {
                    $('#loading').removeClass('d-block');
                    $.each(result.message, (index, value) => {
                        if (index != 'message') {
                            var schedule = index.split(':');
                            var current_row = $(`#${schedule[1]}`).parent().parent().attr('id');
                            var new_row =  schedule[0];
                            var schedule_id = schedule[1];
                            var date = schedule[2];
                            if(current_row !== new_row) {
                                $(`#${schedule_id}`).attr('name', date);
                                $(`#${new_row}`).append($(`#${schedule_id}`));
                            }
                            $(`#${new_row}`).parent().parent().parent().attr('hidden',false)
                            $(`#${schedule_id}`).removeClass('btn-warning')
                            $(`#${schedule_id}`).addClass('border-danger');
                            $(`#temp-${schedule_id}`).removeClass('btn-warning')
                            $(`#temp-${schedule_id}`).addClass('border-danger btn-secondary');
                            var time = value.split(':');
                            $(`#${schedule_id}`).text(time[0] + ':' + time[1]);
                            $(`#temp-${schedule_id}`).text(time[0] + ':' + time[1]);
                            $(`#temp-${schedule_id}`).prop('disabled', true);
                            formData.find(`input[id='schedule-${schedule_id}']`).remove();
                            formData.find(`input[id='schedule_${schedule_id}_lesson']`).remove();
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.removeScheduleFailed('' + schedule_id, current_row);
                        } else {
                            MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_confirm', value)
                        }
                    });
                }
                else if (result.data === 'error_premium_is_expired') {
                    $('#loading').removeClass('d-block');
                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_require_choose_student',result.message)
                    $("html, body").animate({scrollTop: $('#error_require_choose_student').offset().top - 10}, "slow");
                }
                else if (result.message === 'Success') {
                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.validateStudentMemberShip(data);
                }

                if(!(count > 0)) {
                    $('#btnConfirm').prop('disabled', true);
                }
                else {
                    $('#btnConfirm').prop('disabled', false);
                }
            },
            error: function error(_error) {
                $('#loading').removeClass('d-block');
                alert("Error server");
            }
        });
    }

    MANAGER_TEACHER_BOOKING_SUBSTITUTE.validateStudentMemberShip = (data) => {
        $.ajax({
            type: "POST",
            url: routeValidateStudentCoin,
            data: data,
            success: function success(result) {
                if (result.data === 'lackOfCoin') {
                    $('#loading').removeClass('d-block');
                    $('#modalNotificationWhenLackOfCoin').modal('show');
                    $('#modalConfirm-Booking').modal('toggle');
                } else if (result.message === 'Success') {
                    $('#booking_lesson_list').submit();
                } else if (result.data === 'empty'){
                    $('#loading').removeClass('d-block');
                    $('#modalConfirm-Booking').modal('toggle');
                    MANAGER_TEACHER_BOOKING_SUBSTITUTE.addMessageErrorCommon('error_section_require_schedule', M012);
                    $("html, body").animate({scrollTop: $('#error_section_require_schedule').offset().top - 10}, "slow");
                }
            },
            error: function error(_error) {
                $('#loading').removeClass('d-block');
                alert("Error server");
            }
        });
    };
});

$(document).ready(function () {
    MANAGER_TEACHER_BOOKING_SUBSTITUTE.init();
});
