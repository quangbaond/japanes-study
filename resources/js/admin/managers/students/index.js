let MANAGER_STUDENT_INDEX = {};
let formDeleteAllStudent = $("#formDeleteAllStudent");
let notificationConfirmDelete = $("[name=confirm-delete]").attr('content');
let messageDeleteSuccess = $("[name=delete-success]").attr('content');
let routeStudentDelete = $("[name=route-student-delete]").attr('content');
let routeStudentValidation = $("[name=route-student-validation]").attr('content');

$(function () {
    MANAGER_STUDENT_INDEX.init = function () {
        MANAGER_STUDENT_INDEX.checkboxDeleteAllStudent();
        MANAGER_STUDENT_INDEX.confirmDeleteAllStudent();
        MANAGER_STUDENT_INDEX.clearForm();
        MANAGER_STUDENT_INDEX.clickSearch();
    };

    MANAGER_STUDENT_INDEX.checkboxDeleteAllStudent = function() {
        // check all
        $('#check_all').on('click', function(e) {
            let check = $(".chk_item");
            $("#formDeleteAllStudent").find("input[name='user_id[]'").remove();
            if($(this).prop("checked")) {
                if (check.length > 0) {
                    $("#delete-all-student").attr("disabled", false);
                }
                check.prop('checked', true);
                check.each(function() {
                    $("#formDeleteAllStudent").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
                });
            } else {
                check.prop('checked',false);
                $("#delete-all-student").attr("disabled", true);
                check.each(function() {
                    $("#id-" + $(this).val()).remove();
                });
            }
        });

        // ckeck page
        $('#students').on('draw.dt', function() {
            $("#formDeleteAllStudent").find("input[name='user_id[]'").remove();
            $("#check_all").prop('checked', false);
            $("#delete-all-student").attr("disabled", true);
        });

        //check item
        $("body").on("change", ".chk_item", function(){
            if (false == $(this).prop("checked")) {
                $("#check_all").prop('checked', false);
            };
            if ( $('.chk_item:checked').length == $('.chk_item').length ) {
                $("#check_all").prop('checked', true);
            };
            if ($(this).prop("checked")) {
                $("#formDeleteAllStudent").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
            } else {
                $("#id-" + $(this).val()).remove();
            }
            if ($('.chk_item:checked').length > 0) {
                $("#delete-all-student").attr("disabled", false);
            } else {
                $("#delete-all-student").attr("disabled", true);
            }
        });
    };

    MANAGER_STUDENT_INDEX.confirmDeleteAllStudent = function () {
        $('#delete-all-student').on('click', function(e) {
            common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
                if (r) {
                    let data = $("#formDeleteAllStudent").serialize(); // Ajax
                    $.ajax({
                        type: "POST",
                        url: routeStudentDelete,
                        data: data,
                        success: function success(result) {
                            if (result.status) {
                                $('#area_message').html(`
                                    <section class="content-header">
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <i class="icon fa fa-check"></i>
                                            ${messageDeleteSuccess}
                                        </div>
                                    </section>
                                `);
                                $(window).scrollTop(0);
                                $('#students').DataTable().draw(true);
                            } else {
                                alert("Error server");
                            }
                        },
                        error: function error(_error) {
                            alert("Error server");
                        }
                    });
                }
            });
        });
    };

    MANAGER_STUDENT_INDEX.clickSearch = function() {
        $('#btnSearch').click(function () {

            $("#error_section").css('display', 'none');
            let invalid = true;
            $('#searchStudent').find('select,input').each(function() {
                if ($(this).val() != '' ) {
                    console.log($(this).val());
                    invalid = false;
                }
            });
            if (invalid){
                $("#error_mes").html('検索項目を入力してください。');
                $("#error_section").css('display', 'block');
                return false;
            }

            // Clear error
            $('body').find('input').removeClass('is-invalid');
            $(".invalid-feedback-custom").html(''); // Get data form search teacher

            var data = $("#formSearchStudent").serialize(); // Ajax

            $.ajax({
                type: "POST",
                url: routeStudentValidation,
                data: data,
                success: function success(result) {
                    if (result.status) {
                        $('#students').DataTable().draw(true);
                    } else {
                        $.each(result.message, function (key, value) {
                            if (key === "created_at_to") {
                                $('.created_at_from').addClass('is-invalid');
                                $('#format_created_at_from').html(value[0]);
                            } else if (key === "format_created_at_from") {
                                $('.format_created_at_from').addClass('is-invalid');
                                $('#format_created_at_from').html(value[0]);
                            } else if (key === "format_created_at_to") {
                                $('.format_created_at_to').addClass('is-invalid');
                                $('#format_created_at_to').html(value[0]);
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

    MANAGER_STUDENT_INDEX.clearForm = function() {
        $('#btnClearForm').click(function(){
            let isThis = $(this);
            let formSearchClear = '.form-search-clear';
            common.clearValueFormSearch(isThis, formSearchClear);
            $("#error_section").css('display', 'none');
            $('#students').DataTable().draw(true);
        });
    };
});

$(document).ready(function () {
    MANAGER_STUDENT_INDEX.init();
});
