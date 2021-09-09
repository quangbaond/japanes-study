let MANAGER_ADMIN_LOGIN = {};

$(function () {
    MANAGER_ADMIN_LOGIN.init = function () {
        MANAGER_ADMIN_LOGIN.handleInput();
    };

    MANAGER_ADMIN_LOGIN.handleInput = function() {
        var fieldInput = $('#email');
        var fldLength= fieldInput.val().length;
        fieldInput.focus();
        fieldInput[0].setSelectionRange(fldLength, fldLength);
    }
});

$(document).ready(function () {
    MANAGER_ADMIN_LOGIN.init();
});
