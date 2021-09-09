let MANAGER_LESSON_HISTORY = {};
let csrf_token = $("[name=csrf-token]").attr('content');
let routeGetNameById = $("[name=route-get-nickname-by-id]").attr('content');
let routeGetNameByEmail = $("[name=route-get-nickname-by-email]").attr('content');
let routeValidationSearch = $("[name=route-validation-search]").attr('content');
let routeExportToExcel = $("[name=route-export-to-excel]").attr('content');
let msgConfirm = 'エクセルに出力しますか？';
$(function () {
    MANAGER_LESSON_HISTORY.init = function () {
        MANAGER_LESSON_HISTORY.selectById();
        MANAGER_LESSON_HISTORY.selectByEmail();
        MANAGER_LESSON_HISTORY.clickSearch();
        MANAGER_LESSON_HISTORY.clearForm();
        MANAGER_LESSON_HISTORY.exportToExcel();
        MANAGER_LESSON_HISTORY.formatDate();
    };

    MANAGER_LESSON_HISTORY.formatDate = (date) => {
         day = date.getDate() > 10 ? '/'+ date.getDate(): '/0'+date.getDate();
         month = date.getMonth() +1 > 10 ? '/'+ (date.getMonth() + 1): '/0' + (date.getMonth() + 1)
         year = date.getFullYear();
        return year +month+day;
    };

    MANAGER_LESSON_HISTORY.exportToExcel = () => {
        $('#exportExcel').click(() => {
            common.bootboxConfirm(msgConfirm, 'small', function (r) {
                if (r) {
                    if ($('#teacher_id').val().length > 0) {
                        $.each($('#teacher_id').val(), function (index, value) {
                            $('#formExportToExcel').append(`<input name="teacher_id[]" hidden value="${value}">`)
                        })
                    }
                    if ($('#teacher_email').val().length > 0) {
                        $.each($('#teacher_email').val(), function (index, value) {
                            $('#formExportToExcel').append(`<input name="teacher_email[]" hidden value="${value}">`)
                        })
                    }
                    if ($('#date_from').val() != "") {
                        $('#formExportToExcel').append(`<input name="date_from" hidden value="${$('#date_from').val()}">`)
                    }
                    if ($('#date_to').val() != "") {
                        $('#formExportToExcel').append(`<input name="date_to" hidden value="${$('#date_to').val()}">`)
                    }

                    $('#formExportToExcel').submit();
                }
            });

        })
    };


    MANAGER_LESSON_HISTORY.selectById = () => {
        $('#teacher_id').select2({
            language: 'ja',
            ajax: {
                url: routeGetNameById,
                dataType: 'json',
                delay: 100,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.nickname,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    }

    MANAGER_LESSON_HISTORY.selectByEmail = () => {
        $('#teacher_email').select2({
            language: 'ja',
            ajax: {
                url: routeGetNameByEmail,
                dataType: 'json',
                delay: 100,
                processResults: function (data) {
                    return {
                        results: $.map(data, function (item) {
                            return {
                                text: item.email,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
    };

    MANAGER_LESSON_HISTORY.clickSearch = () => {
        $('#btnSearch').click(() => {
            $("#error_section").css('display', 'none');
            let invalid = true;
            $('#searchLessonHistories').find('input').each(function () {
                if ($(this).val() !== '') {
                    invalid = false;
                } else if ($('.itemTeacherId').val().length !== 0) {
                    invalid = false;
                } else if ($('.itemTeacherEmail').val().length !== 0) {
                    invalid = false;
                }
            });
            if (invalid) {
                $("#error_mes").html('検索項目を入力してください。');
                $("#error_section").css('display', 'block');
                return false;
            }

            // Clear error
            $('body').find('input').removeClass('is-invalid');
            $(".invalid-feedback-custom").html(''); // Get data form search teacher

            var data = $("#formSearchLessonHistories").serialize(); // Ajax

            $.ajax({
                type: "POST",
                url: routeValidationSearch,
                data: data,
                success: function success(result) {
                    if (result.status) {
                        $('#statistics').DataTable().draw(true);
                        $('#lessonHistories').DataTable().draw(true);

                    } else {
                        $.each(result.message, function (key, value) {
                            $.each(result.message, function (key, value) {
                                if (key === "date_to") {
                                    $('.date_to').addClass('is-invalid');
                                    $('#err_date').html(value[0]);
                                } else if (key === "format_date_from") {
                                    $('.format_date_from').addClass('is-invalid');
                                    $('#format_date_from').html(value[0]);
                                } else if (key === "format_date_to") {
                                    $('.format_date_to').addClass('is-invalid');
                                    $('#format_date_to').html(value[0]);
                                }
                            });
                        });
                    }
                },
                error: function error(_error) {
                    alert("Error server");
                }
            });

        })
    };

    MANAGER_LESSON_HISTORY.setEmptyValue = () => {
        $('#date_to').val('');
        $('#date_from').val('');
        $('#teacher_id').val('');
        $('#teacher_email').val('');
        $('.select2-selection__choice').remove();
    };


    MANAGER_LESSON_HISTORY.clearForm = function () {
        $('#btnClearForm').click(function () {
            location.reload();
            // MANAGER_LESSON_HISTORY.setEmptyValue();
            // var date = new Date();
            // var firstDay = new Date(date.getFullYear(), date.getMonth(), 1)
            // $('#date_to').val(MANAGER_LESSON_HISTORY.formatDate(date));
            // $('#date_from').val(MANAGER_LESSON_HISTORY.formatDate(firstDay));
            // $("#error_section").css('display', 'none');
            // $('#lessonHistories').DataTable().draw(true);
            // $('#statistics').DataTable().draw(true);
        });
    };
});

$(document).ready(function () {
    MANAGER_LESSON_HISTORY.init();
});
