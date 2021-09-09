let MANAGER_NOTIFICATION_DETAIL = {};
let routeNotificationValidation = $("[name=notification-validate]").attr('content');
let routeUpdateNotification = $("[name=notification-update]").attr('content');
let csrf_token = $("[name=csrf-token]").attr('content');

$(function () {
    MANAGER_NOTIFICATION_DETAIL.init = function () {
        MANAGER_NOTIFICATION_DETAIL.clickValidate();
        MANAGER_NOTIFICATION_DETAIL.clickClear();

    };
    MANAGER_NOTIFICATION_DETAIL.clearError = () => {
        $('#to_date').removeClass('is-invalid');
        $('#from_date').removeClass('is-invalid');
        $('#title').removeClass('is-invalid');
        $('#content').removeClass('is-invalid');
        $('#format_date_from').html('');
        $('#format_date_to').html('');
        $('#title_err').html('');
        $('#content_err').html('');
    };

    MANAGER_NOTIFICATION_DETAIL.clickClear = () => {
        $('#btnClear').click(() => {
            location.reload();
        })
    };

    MANAGER_NOTIFICATION_DETAIL.updateNotification = () => {
        var data = $('#formUpdateNotification').serialize();
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": csrf_token
            }
        });
        $.ajax({
            type: 'POST',
            url: routeUpdateNotification,
            data: data,
            success: function success(result) {
                $('#to_list_notification').submit();
            },
            error: function error(_error) {
                alert("Error server");
            }
        });
    };

    MANAGER_NOTIFICATION_DETAIL.clickValidate = () => {
        $('#btnSubmit').click(function () {
            MANAGER_NOTIFICATION_DETAIL.clearError();
            var data = $('#formUpdateNotification').serialize();

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            $.ajax({
                type: 'POST',
                url: routeNotificationValidation,
                data: data,
                success: function success(result) {
                    if (result.status) {
                        MANAGER_NOTIFICATION_DETAIL.updateNotification();
                    } else {
                        console.log(result.message);
                        $.each(result.message, function (key, value) {
                            if (key === "to_date") {
                                $('#to_date').addClass('is-invalid');
                                $('#format_date_to').html(value[0]);
                            } else if (key === "from_date") {
                                $('#from_date').addClass('is-invalid');
                                $('#format_date_from').html(value[0]);
                            } else if (key === "format_date_from") {
                                $('#from_date').addClass('is-invalid');
                                $('#format_date_from').html(value[0]);
                            } else if (key === "format_date_to") {
                                $('#to_date').addClass('is-invalid');
                                $('#format_date_to').html(value[0]);
                            } else if (key === "title") {
                                $('#title').addClass('is-invalid');
                                $('#title_err').html(value[0]);
                            } else if (key === "content") {
                                $('#content').addClass('is-invalid');
                                $('#content_err').html(value[0]);
                            }
                        });
                    }
                },
                error: function error(_error) {
                    alert("Error server");
                }
            })
        })
    }
});

$(document).ready(function () {
    MANAGER_NOTIFICATION_DETAIL.init();
});
