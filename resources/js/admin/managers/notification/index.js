let MANAGER_NOTIFICATION_INDEX = {};
let routeNotificationValidation = $("[name=route-notification-validation]").attr('content');
let msgConfirm = $("[name=delete-confirm]").attr('content');
let msgDeleteSuccess = $("[name=delete-success]").attr('content');
let routeNotificationDelete = $("[name=route-notification-delete]").attr('content');
let routeGetEmail = $("[name=route-get-email]").attr('content');
$(function () {
    MANAGER_NOTIFICATION_INDEX.init = function () {
        MANAGER_NOTIFICATION_INDEX.clearForm();
        MANAGER_NOTIFICATION_INDEX.clickSearch();
        MANAGER_NOTIFICATION_INDEX.selectChange();
        MANAGER_NOTIFICATION_INDEX.setEmptyValue();
        MANAGER_NOTIFICATION_INDEX.deleteAllNotification();
        MANAGER_NOTIFICATION_INDEX.clickDeleteNotification();
        MANAGER_NOTIFICATION_INDEX.selectEmail();
    };

    MANAGER_NOTIFICATION_INDEX.clearSuccessMsg = () => {
        $('#area_message').html('');
    };

    MANAGER_NOTIFICATION_INDEX.selectEmail = () => {
        $('.itemEmail').select2({
            language:'ja',
            ajax: {
                url: routeGetEmail,
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
    }

    MANAGER_NOTIFICATION_INDEX.clickDeleteNotification = () => {
        $('#btnDelete').click(function () {
            common.bootboxConfirm(msgConfirm, 'small', function (r) {
                if (r) {
                    let data = $("#formDeleteNotification").serialize(); // Ajax
                    $.ajax({
                        type: "POST",
                        url: routeNotificationDelete,
                        data: data,
                        success: function success(result) {
                            if (result.status) {
                                $('#area_message').html(`
                                    <section class="content-header">
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <i class="icon fa fa-check"></i>
                                            ${msgDeleteSuccess}
                                        </div>
                                    </section>
                                `);
                                $(window).scrollTop(0);
                                $('#notifications').DataTable().draw(true);
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

    MANAGER_NOTIFICATION_INDEX.setEmptyValue = () => {
        $('#created_at_from').val('');
        $('#created_at_to').val('');
        $('.itemEmail').val('');
        $('#select2-email-container').html('');
        $('#title').val('');
    };

    MANAGER_NOTIFICATION_INDEX.selectChange = () => {
        $('#icon_created_at_from').on("click", function(){
            $('#created_at_from').focus();
        });
        $('#icon_created_at_to').on("click", function(){
            $('#created_at_to').focus();
        });

        $('#select_box').on('change', function () {
            // $('#input').html('');
            switch (this.value) {
                case 'title':
                    $('#inputTitle').hasClass('d-none') ? $('#inputTitle').removeClass('d-none') : true;
                    $('#inputDate').hasClass('d-none') ? true : $('#inputDate').addClass('d-none');
                    $('#inputEmail').hasClass('d-none') ? true : $('#inputEmail').addClass('d-none');
                    MANAGER_NOTIFICATION_INDEX.setEmptyValue();
                    break;
                case 'date':
                    $('#inputDate').hasClass('d-none') ? $('#inputDate').removeClass('d-none') : true;
                    $('#inputTitle').hasClass('d-none') ? true : $('#inputTitle').addClass('d-none');
                    $('#inputEmail').hasClass('d-none') ? true : $('#inputEmail').addClass('d-none');
                    MANAGER_NOTIFICATION_INDEX.setEmptyValue();
                    break;
                case 'email':
                    $('#inputEmail').hasClass('d-none') ? $('#inputEmail').removeClass('d-none') : true;
                    $('#inputTitle').hasClass('d-none') ? true : $('#inputTitle').addClass('d-none');
                    $('#inputDate').hasClass('d-none') ? true : $('#inputDate').addClass('d-none');
                    MANAGER_NOTIFICATION_INDEX.setEmptyValue();
                    break;
            }
        });
    }
    MANAGER_NOTIFICATION_INDEX.clickSearch = function () {
        $('#btnSearch').click(function () {
            MANAGER_NOTIFICATION_INDEX.clearSuccessMsg();
            $("#error_section").css('display', 'none');
            let invalid = true;
            $('#searchNotification').find('input').each(function () {
                if ($(this).val() != '') {
                    invalid = false;
                }
                else if($('.itemEmail').val() != null){
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

            var data = $("#formSearchNotification").serialize(); // Ajax

            $.ajax({
                type: "POST",
                url: routeNotificationValidation,
                data: data,
                success: function success(result) {
                    if (result.status) {
                        $('#notifications').DataTable().draw(true);
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

    MANAGER_NOTIFICATION_INDEX.clearForm = function () {
        $('#btnClearForm').click(function () {
            MANAGER_NOTIFICATION_INDEX.clearSuccessMsg();
            MANAGER_NOTIFICATION_INDEX.setEmptyValue();
            $("#error_section").css('display', 'none');
            $('.format_created_at_from').removeClass('is-invalid')
            $('#format_created_at_from').html('');
            $('.format_created_at_to').removeClass('is-invalid')
            $('#format_created_at_to').html('');
            $('#notifications').DataTable().draw(true);
        });
    };

    MANAGER_NOTIFICATION_INDEX.deleteAllNotification = () => {
        // Check all
        $('#check_all').on('click', function (e) {
            let check = $(".chk_item");
            $("#formDeleteNotification").find("input[name='user_id[]'").remove();
            if ($(this).prop("checked")) {
                if (check.length > 0) {
                    $("#btnDelete").attr("disabled", false);
                }
                check.prop('checked', true);
                check.each(function () {
                    $("#formDeleteNotification").append('<input type="hidden" id="id-' + $(this).val() + '" name="notification_id[]" value="' + $(this).val() + '">');
                });
            } else {
                check.prop('checked', false);
                $("#btnDelete").attr("disabled", true);
                check.each(function () {
                    $("#id-" + $(this).val()).remove();
                });
            }
        });

        // Check item
        $("body").on("change", ".chk_item", function () {
            if (false == $(this).prop("checked")) {
                $("#check_all").prop('checked', false);
                $("#id-" + $(this).val()).remove();
            } else {
                $("#formDeleteNotification").append('<input type="hidden" id="id-' + $(this).val() + '" name="notification_id[]" value="' + $(this).val() + '">');
            }
            if ($('.chk_item:checked').length == $('.chk_item').length) {
                $("#check_all").prop('checked', true);
            }
            if ($('.chk_item:checked').length > 0) {
                $("#btnDelete").attr("disabled", false);
            } else {
                $("#btnDelete").attr("disabled", true);
            }
        });

        // ckeck page
        $('#notifications').on('draw.dt', function () {
            $("#formDeleteNotification").find("input[name='notification_id[]'").remove();
            $("#check_all").prop('checked', false);
            $("#btnDelete").attr("disabled", true);
        });
    };
});

$(document).ready(function () {
    MANAGER_NOTIFICATION_INDEX.init();
});
