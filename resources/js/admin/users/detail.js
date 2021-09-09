let USER_DETAIL = {};
let formDeleteUser = $("#formDeleteUser");
let notificationConfirmDelete = $("[name=confirm-delete]").attr('content');

$(function () {
    USER_DETAIL.init = function () {
        USER_DETAIL.confirmDeleteUser();
    };

    USER_DETAIL.confirmDeleteUser = function () {
        $("#delete-user").click(function(){
            common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
                if(r){
                    formDeleteUser.submit();
                }
            });
        });
    };

});

$(document).ready(function () {
    USER_DETAIL.init();
});
