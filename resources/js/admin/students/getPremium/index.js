let STUDENT_PAYMENT_PREMIUM = {};
let inputErrorRadioRequired = $("[name=input-error-radio-required]").attr('content');
let routeStudentPaymentPremium = $("[name=route-student-payment-premium]").attr('content');
let routeStudentPaymentValidation = $("[name=route-student-payment-validation]").attr('content');
let M046 = $("[name=m046]").attr('content');
let M047 = $("[name=m047]").attr('content');
let checkRadioRequired = $("[name=check_radio_required]").attr('content');

$(function () {
    /*
       Function init js
    */
    STUDENT_PAYMENT_PREMIUM.init = function () {
        STUDENT_PAYMENT_PREMIUM.goToStep2();
        STUDENT_PAYMENT_PREMIUM.checkRemember();
        STUDENT_PAYMENT_PREMIUM.handleGoToStep2();
        STUDENT_PAYMENT_PREMIUM.handleBackStep1();
        STUDENT_PAYMENT_PREMIUM.showFormCredit();
        STUDENT_PAYMENT_PREMIUM.closeFormCredit();
        STUDENT_PAYMENT_PREMIUM.validationPaymentCredit();
        STUDENT_PAYMENT_PREMIUM.showHideFormCard();
    };

    STUDENT_PAYMENT_PREMIUM.goToStep2 = function() {
        $('#goToStep2').click(function() {
            $('#error_plans').removeClass('text-danger');
            $('.error_plan_label').removeClass('text-danger');
            let plans_value = $("input[name=plans]:checked").val();
            if (typeof plans_value == 'undefined') {
                $('#error_plans').addClass('text-danger');
                $('.error_plan_label').addClass('text-danger');
            } else {
                let label_plan = $("input[name=plans]:checked").attr('data-value');
                let premium_end_date = $("input[name=plans]:checked").attr('data-premium-end-date');
                let plan_cost = $("input[name=plans]:checked").attr('data-cost');
                let plan_interval = $("input[name=plans]:checked").attr('data-interval');
                let plan_interval_count = $("input[name=plans]:checked").attr('data-interval-count');
                $('#name_plan_choice').html(label_plan);
                $('#premium_end_date').html(premium_end_date);
                $('#cost_plan').html(plan_cost);
                $('#interval_count').html(plan_interval_count);
                $('#interval').html(plan_interval);
                $('#modal-confirm-plans').modal('show');
            }
        });
    };

    STUDENT_PAYMENT_PREMIUM.checkRemember = function() {
        $('#remember').click(function() {
            if ($(this).is(":checked")) {
                $('#btnGoToStep2').prop('disabled', false);
            } else {
                $('#btnGoToStep2').prop('disabled', true);
            }
        })
    };

    STUDENT_PAYMENT_PREMIUM.handleGoToStep2 = function() {
        $('#btnGoToStep2').click(function() {
            $('#modal-confirm-plans').modal('hide');
            $('#step1').addClass('d-none');
            $('#step2').removeClass('d-none');
            let plan_name = $("input[name=plans]:checked").attr('data-value');
            let plan_cost = $("input[name=plans]:checked").attr('data-cost');
            let plan_interval = $("input[name=plans]:checked").attr('data-interval');
            let plan_interval_count = $("input[name=plans]:checked").attr('data-interval-count');
            $('#plan_name').html(plan_name);
            $('#plan_cost').html(plan_cost + ' VND');
            $('#plan_interval').html(plan_interval_count + ' ' + plan_interval);
        });
    };

    STUDENT_PAYMENT_PREMIUM.handleBackStep1 = function(){
        $('#btnBackStep1').click(function() {
            $('#step1').removeClass('d-none');
            $('#step2').addClass('d-none');
        })
    };

    STUDENT_PAYMENT_PREMIUM.showFormCredit = function() {
        $('#showFormCredit').click(function() {
            $('#formCredit').removeClass('d-none');
        });
    };

    STUDENT_PAYMENT_PREMIUM.closeFormCredit = function() {
        $('#btnCloseFormCredit').click(function() {
            $('#formCredit').addClass('d-none');
        })
    };

    /*
        Function clear all error validation in form
    */
    STUDENT_PAYMENT_PREMIUM.clearError = function() {
        $('#error_section').html('');
        $('body').find('input').removeClass('is-invalid');
        $(".invalid-feedback-custom").html('');
        $('#card-error').html('');
    };

    STUDENT_PAYMENT_PREMIUM.showHideFormCard = function() {
        $('input[name=choicePayment]').click(function() {
            if ($(this).val() == 1) {
                $('#formCredit').addClass('d-none');
            } else if($(this).val() == 2) {
                $('#formCredit').removeClass('d-none');
            }
        });
    };

    STUDENT_PAYMENT_PREMIUM.validationPaymentCredit = function() {
        $('#btnSubmitCredit').click(function() {
            $("#loading").show();
            // Function clear error
            STUDENT_PAYMENT_PREMIUM.clearError();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let choicePayment = $("input[name=choicePayment]:checked").val();
            if (typeof choicePayment == 'undefined') {
                $('#area_message_choice_payment').removeClass('d-none');
                $('#message_choice_payment').html(checkRadioRequired);
                $('#radioPrimary2').closest('.form-group').find('label').addClass('text-danger');
                $("#loading").hide();
                return false;
            }

            // Data
            let data = new FormData();
            data.append("name_card", $('input[name=name_card]').val());
            data.append("number_card", $('input[name=number_card]').val());
            data.append("cvc", $('input[name=cvc]').val());
            data.append("date_expiration", $('input[name=date_expiration]').val());
            data.append("plan_id", $("input[name=plans]:checked").val());
            data.append('choice_payment', typeof choicePayment != 'undefined' ? choicePayment : '');

            $.ajax({
                type: "POST",
                url: routeStudentPaymentValidation,
                data: data,
                cache:false,
                contentType: false,
                processData: false,
                success: function success(result) {
                    $("#loading").hide();
                    if (result.status) {
                        let paymentMethod = '';
                        if (result.data != null) {
                            paymentMethod = result.data.id
                        }
                        common.bootboxConfirmMultiLanguage(M046, 'small', function (r) {
                            if (r) {
                                STUDENT_PAYMENT_PREMIUM.paymentCredit(data, paymentMethod);
                            }
                        });
                    } else {
                        $.each(result.message, function (key, value) {
                            if (typeof value !== "undefined") {
                                $('#' + key).addClass('is-invalid');
                                $('#' + key).closest('.form-group').find('strong').html(value);
                            }
                        });
                    }
                },
                error: function error(_error) {
                    $("#loading").hide();
                    alert('Error server');
                }
            });
        })
    };

    /*
        Button payment subscriptions
    */
    STUDENT_PAYMENT_PREMIUM.paymentCredit = function(data, paymentMethod) {
        $("#loading").show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // Data
        data.append("payment_method", paymentMethod);

        $.ajax({
            type: "POST",
            url: routeStudentPaymentPremium,
            data: data,
            cache:false,
            contentType: false,
            processData: false,
            success: function success(result) {
                $("#loading").hide();
                if (result.status) {
                    $('#step2').addClass('d-none');
                    $('#start_date').html($("input[name=plans]:checked").attr('data-premium-start-date'));
                    $('#next_renewal_date').html($("input[name=plans]:checked").attr('data-premium-end-date'));
                    $('#amount').html($("input[name=plans]:checked").attr('data-cost') + ' VND');
                    $('#step3').removeClass('d-none');
                } else {
                    if (result.message != '') {
                        $('#card-error').html(`
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                ${M047}
                            </div>
                        `);
                    }
                }
            },
            error: function error(_error) {
                $("#loading").hide();
                alert('Error server');
            }
        });
    }

});

$(document).ready(function () {
    STUDENT_PAYMENT_PREMIUM.init();
});


