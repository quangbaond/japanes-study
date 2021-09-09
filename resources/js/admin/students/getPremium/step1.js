let STUDENT_GET_PREMIUM_STEP1 = {};
let routePremiumStep2 = $("[name=routePremiumStep2]").attr('content');

$(function () {
    STUDENT_GET_PREMIUM_STEP1.init = function () {
        STUDENT_GET_PREMIUM_STEP1.clickNextButton();
        STUDENT_GET_PREMIUM_STEP1.unCheckedCheckbox();
    };
    STUDENT_GET_PREMIUM_STEP1.clickNextButton = () => {
        $('#btnNext').click(() => {
            if (!$('#remember').is(':checked')) {
                $('#error_section').html(`
                    <span class="text-danger">Please check this box if you want to proceed.</span>
                `);
            }
        });

        $('#remember').on('click', function (e) {
            if ($(this).is(':checked')) {
                $('#btnNextRoute').attr("href", `${routePremiumStep2}`);
            } else {
                $('#btnNextRoute').removeAttr("href");
            }
        });
    }
    STUDENT_GET_PREMIUM_STEP1.unCheckedCheckbox = () => {
        for (i = 1; i <= 3; i++) {
            $(`#checkboxPrimary${i}`).on('click', function (e) {
                if ($(this).is(':checked')) {
                    i = this.id.split('checkboxPrimary')[1];
                    for (j = 1; j <= 3; j++) {
                        if (j != i) {
                            $(`#checkboxPrimary${j}`).prop('checked', false); // Checks it
                        }
                    }
                }
            });
        }
    }
});

$(document).ready(function () {
    STUDENT_GET_PREMIUM_STEP1.init();
});


