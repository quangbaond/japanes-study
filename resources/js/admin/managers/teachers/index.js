let MANAGER_TEACHER_INDEX = {};
let notificationConfirmDelete = $("[name=delete-confirm]").attr('content');
let messageDeleteSuccess = $("[name=delete-success]").attr('content');
let routeTeacherValidation = $("[name=route-teacher-validation]").attr('content');
let routeTeacherDelete = $("[name=route-teacher-delete]").attr('content');

$(function () {
    MANAGER_TEACHER_INDEX.init = function () {
        MANAGER_TEACHER_INDEX.clickSearch();
        MANAGER_TEACHER_INDEX.clearForm();
        MANAGER_TEACHER_INDEX.deleteAllTeacher();
        MANAGER_TEACHER_INDEX.clickDeleteTeacher();
    };

    MANAGER_TEACHER_INDEX.clickDeleteTeacher = function() {
        $('#btnDelete').click(function() {
            common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
                if(r){
                    let data = $("#formDeleteTeacher").serialize(); // Ajax
                    $.ajax({
                        type: "POST",
                        url: routeTeacherDelete,
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
                                $('#teachers').DataTable().draw(true);
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

    MANAGER_TEACHER_INDEX.clickSearch = function() {
        $('#btnSearch').click(function() {

            // Message
            $("#error_section").css('display', 'none');
            let invalid = true;
            $('#searchTeacher').find('select,input').each(function() {
                if ($(this).val() != '' ) {
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
            $(".invalid-feedback-custom").html('');

            // Get data form search teacher
            let data = $("#formSearchTeacher").serialize();

            // Ajax
            $.ajax({
                type: "POST",
                url: routeTeacherValidation,
                data: data,
                success: function(result){
                    if (result.status) {
                        $('#teachers').DataTable().draw(true);
                    } else {
                        $.each( result.message, function( key, value ) {
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
                error: function(error){
                    alert("Error server");
                }
            });
        });
    };

    MANAGER_TEACHER_INDEX.clearForm = function() {
        $('#btnClearForm').click(function(){
            let isThis = $(this);
            let formSearchClear = '.form-search-clear';
            common.clearValueFormSearch(isThis, formSearchClear);
            $("#error_section").css('display', 'none');
            $('#teachers').DataTable().draw(true);
        });
    };

    MANAGER_TEACHER_INDEX.deleteAllTeacher = function() {
        // Check all
        $('#check_all').on('click', function(e) {
            let check = $(".chk_item");
            $("#formDeleteTeacher").find("input[name='user_id[]'").remove();
            if ($(this).prop("checked")) {
                if (check.length > 0) {
                    $("#btnDelete").attr("disabled", false);
                }
                check.prop('checked', true);

                check.each(function() {
                    $("#formDeleteTeacher").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
                });
            } else {
                check.prop('checked',false);
                $("#btnDelete").attr("disabled", true);
                check.each(function() {
                    $("#id-" + $(this).val()).remove();
                });
            }
        });

        // Check item
        $("body").on("change", ".chk_item", function(){
            if (false == $(this).prop("checked")) {
                $("#check_all").prop('checked', false);
                $("#id-" + $(this).val()).remove();
            } else {
                $("#formDeleteTeacher").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
            }
            if ( $('.chk_item:checked').length == $('.chk_item').length ) {
                $("#check_all").prop('checked', true);
            }
            if ($('.chk_item:checked').length > 0) {
                $("#btnDelete").attr("disabled", false);
            } else {
                $("#btnDelete").attr("disabled", true);
            }
        });

        // ckeck page
        $('#teachers').on('draw.dt', function() {
            $("#formDeleteTeacher").find("input[name='user_id[]'").remove();
            $("#check_all").prop('checked', false);
            $("#btnDelete").attr("disabled", true);
        });
    };

});

$(document).ready(function () {
    MANAGER_TEACHER_INDEX.init();
});
