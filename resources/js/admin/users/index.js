let USER = {};
let formDeleteAllUser = $("#formDeleteAllUser");
let notificationConfirmDelete = $("[name=confirm-delete]").attr('content');

$(function () {
    USER.init = function () {
        USER.confirmDeleteAllUser();
    };

    USER.confirmDeleteAllUser = function () {
        $("#delete-all-user").click(function(){
            common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
                if(r){
                    formDeleteAllUser.submit();
                }
            });
        });
    };
});

$(document).ready(function () {
    USER.init();
});
