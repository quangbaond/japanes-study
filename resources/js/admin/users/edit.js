let USER_EDIT = {};

$(function () {
    USER_EDIT.init = function () {
        USER_EDIT.submitFormEdit();
    };

    USER_EDIT.submitFormEdit = function () {
        $("#btnUpdate").click(function(){
            $("#formEdit").submit();
        });
    }

});

$(document).ready(function () {
    USER_EDIT.init();
});
