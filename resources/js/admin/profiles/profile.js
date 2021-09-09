let USER_EDIT = {};

$(function () {
    USER_EDIT.init = function () {
        USER_EDIT.changePassword();
    };

    USER_EDIT.changePassword = function() {
        //Change password
        $("#btnChangePassword").click(function(){
            $("#error_password_old").html('');
            $("#error_password_new").html('');
            $("#error_password_confirm").html('');
            let data = $("#formChangePassword").serialize();
            $.ajax({
                type: "POST",
                url: "/profile/change-password",
                data: data,
                success: function(result){
                    if (!result.status) {
                        if (typeof(result.message.password_old) != "undefined") {
                            $("#error_password_old").html(result.message.password_old);
                        }
                        if (typeof(result.message.password_new) != "undefined") {
                            $("#error_password_new").html(result.message.password_new);
                        }
                        if (typeof(result.message.password_confirm) != "undefined") {
                            $("#error_password_confirm").html(result.message.password_confirm);
                        }
                    } else {
                        toastr.success(result.message);
                        $("#password_old").val('');
                        $("#password_new").val('');
                        $("#password_confirm").val('');
                        $("#modalChangePassword").modal('hide');
                    }
                },
                error: function(result){
                    toastr.error(result.message);
                }
            });
        });
    }

});

$(document).ready(function () {
    USER_EDIT.init();
});
