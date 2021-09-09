let STUDENT_ADD_COIN = {};
let routeValidationPaymentCoin = $("[name=route-validation-payment-coin]").attr('content');
let routeCheckCancelPremium = $("[name=route-check-cancel-premium]").attr('content');
let routePaymentCoin = $("[name=route-payment-coin]").attr('content');
let M043 = $("[name=m043]").attr('content');
let M046 = $("[name=m046]").attr('content');
let M047 = $("[name=m047]").attr('content');
let M054 = $("[name=M054]").attr('content');
let cardNumber = $("[name=card-number]").attr('content');
let checkRadioRequired = $("[name=check_radio_required]").attr('content');
let id_master_coin = "";

$(function () {
    STUDENT_ADD_COIN.init = function () {
        STUDENT_ADD_COIN.openModalAddCoin();
        STUDENT_ADD_COIN.showHideFormCard();
        STUDENT_ADD_COIN.validationPayment();
    };

    STUDENT_ADD_COIN.openModalAddCoin = function() {
        $('.openModalAddCoin').click(function() {

            // Clear error
            $('#formAddCoin').addClass('d-none');
            $('#card-error').html('');
            $('#formPaymentAddCoinForStudent').find('input').removeClass('is-invalid');
            $('#formPaymentAddCoinForStudent').find('input[type=text], input[type=number]').val('');
            $("input[name=choicePayment][value='1']").prop("checked",true);

            let id = $(this).attr('data-id');
            let coin = $(this).attr('data-coin');
            let bonus_coin = $(this).attr('data-bonus-coin');
            let amount = $(this).attr('data-amount');

            $('#loading').show();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let data = new FormData();

            $.ajax({
                type: "POST",
                url: routeCheckCancelPremium,
                data: data,
                cache:false,
                contentType: false,
                processData: false,
                success: function success(result) {
                    $("#loading").hide();
                    if (result.status) {
                        if (result.data) {
                            id_master_coin = id;
                            $('#coin-show').html(coin);
                            $('#bonus-coin-show').html(bonus_coin);
                            $('#amount-coin').html(amount + ' VND');
                            $('#modal-add-coin').modal('show');
                        } else {
                            location.reload();
                        }
                    } else {
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     ${M054}
                                </div>
                            </section>
                        `);
                    }
                },
                error: function error(_error) {
                    $("#loading").hide();
                    alert('Error server');
                }
            });
        });
    };

    STUDENT_ADD_COIN.showHideFormCard = function() {
        $('input[name=choicePayment]').click(function() {
            if ($(this).val() == 1) {
                $('#formAddCoin').addClass('d-none');
            } else if($(this).val() == 2) {
                $('#formAddCoin').removeClass('d-none');
            }
        });
    };

    STUDENT_ADD_COIN.clearErrorChoicePaymentAddCoin = function() {
        $('#area_message_choice_payment').addClass('d-none');
        $('#message_choice_payment').html('');
        $('#radioPrimary2').closest('.form-group').find('label').removeClass('text-danger');
        $('#formPaymentAddCoinForStudent').find('input').removeClass('is-invalid');
        $(".invalid-feedback-custom").html('');
        $('#card-error').html('');
    };

    STUDENT_ADD_COIN.validationPayment = function() {
        $('#btnSubmitPayment').click(function() {
            $('#loading').show();
            // // Clear error
            STUDENT_ADD_COIN.clearErrorChoicePaymentAddCoin();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Data
            let choicePayment = $("input[name=choicePayment]:checked").val();
            if (typeof choicePayment == 'undefined') {
                $('#area_message_choice_payment').removeClass('d-none');
                $('#message_choice_payment').html(checkRadioRequired);
                $('#radioPrimary2').closest('.form-group').find('label').addClass('text-danger');
                $("#loading").hide();
                return false;
            }

            let data = new FormData();
            data.append("id_master_coin", id_master_coin);
            data.append('choice_payment', typeof choicePayment != 'undefined' ? choicePayment : '');
            data.append("name_card", $('input[name=name_card]').val());
            data.append("number_card", $('input[name=number_card]').val());
            data.append("cvc", $('input[name=cvc]').val());
            data.append("date_expiration", $('input[name=date_expiration]').val());

            $.ajax({
                type: "POST",
                url: routeValidationPaymentCoin,
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
                        common.bootboxConfirmMultiLanguage(cardNumber + ': **** **** **** ' + result.data.card.last4 + '<br>' + M046, 'small', function (r) {
                            if (r) {
                                STUDENT_ADD_COIN.submitPayment(data, paymentMethod);
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
                    alert('Error server');
                }
            });
        });
    };

    STUDENT_ADD_COIN.submitPayment = function(data, paymentMethod) {
        $('#loading').show();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        data.append("payment_method", paymentMethod);
        $.ajax({
            type: "POST",
            url: routePaymentCoin,
            data: data,
            cache:false,
            contentType: false,
            processData: false,
            success: function success(result) {
                $("#loading").hide();
                if (result.status) {
                    $('.total-coin').html(result.data.total_coin);
                    $('#expiration_date').html(result.data.expiration_date_timezone);
                    $('#modal-add-coin').modal('hide');
                    $('#area_message_success').removeClass('d-none');
                    $('#message_success').html(M043);
                    $('#history_use_coin').DataTable().draw(true);
                    $('#history_use_coin').removeClass('d-none');
                    $('#M070').html('');
                    $('html, body').animate({scrollTop: $("#history_use_coin").offset().top}, 500);
                    $('#confirm_deadline').attr('disabled', false);
                } else {
                    if (result.message != '') {
                        $('#card-error').html(`
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                ${M047}
                            </div>
                        `);
                        $('#modal-add-coin .modal-body').animate({scrollTop: $("#card-error").offset().top}, 500);
                    }
                }
            },
            error: function error(_error) {
                $("#loading").hide();
                alert('Error server');
            }
        });
    };


});

$(document).ready(function () {
    STUDENT_ADD_COIN.init();
});
