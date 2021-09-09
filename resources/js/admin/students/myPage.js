let MY_PAGE_STUDENT = {};
let routeCheckCancelTrialPayment = $("[name=check-cancel-trial-payment]").attr('content');
let routeCheckCancelPremiumPayment = $("[name=check-cancel-premium-plan]").attr('content');
let M050 = $("[name=M050]").attr('content');
let M051 = $("[name=M051]").attr('content');
let M052 = $("[name=M052]").attr('content');

$(function () {
    MY_PAGE_STUDENT.init = function () {
        MY_PAGE_STUDENT.cancelTrialPayment();
        MY_PAGE_STUDENT.clickOpenModalTrial();
        MY_PAGE_STUDENT.clickOpenModalPremium();
        MY_PAGE_STUDENT.btnSubmitTrial();
        MY_PAGE_STUDENT.btnSubmitPremium();
        MY_PAGE_STUDENT.cancelPremiumPayment();
    };

    MY_PAGE_STUDENT.clickOpenModalTrial = function() {
        $('#buttonOpenModalTrial').click(function() {
            $('#error-message-cancel').html('');
            $('#modalTrial').modal('show');
        });
    };

    MY_PAGE_STUDENT.clickOpenModalPremium = function() {
        $('#buttonOpenModalPremium').click(function() {
            $('#error-message-cancel-premium').html('');
            $('#modalPremium').modal('show');
        });
    };

    MY_PAGE_STUDENT.btnSubmitTrial = function() {
        $('#btnSubmitTrial').click(function() {
            $('#formCancelTrialPayment').submit();
        })
    };

    MY_PAGE_STUDENT.btnSubmitPremium = function() {
        $('#btnSubmitPremium').click(function() {
            $('#formCancelPremiumPayment').submit();
        })
    };

    MY_PAGE_STUDENT.cancelTrialPayment = function() {
        $('#cancelTrialPayment').click(function() {
            common.bootboxConfirmMultiLanguage(M051, 'small', function (r) {
                if (r) {
                    $('#loading').show();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    let data = new FormData();
                    data.append('trial_end_date', $('[name=trial_end_date]').val());

                    $.ajax({
                        type: "POST",
                        url: routeCheckCancelTrialPayment,
                        data: data,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: function success(result) {
                            $("#loading").hide();
                            if (result.status) {
                                // Success
                                if (result.data.length > 0) {
                                    $('#list_booking tbody').html('');
                                    var total_coin_refund = 0;
                                    $.each(result.data, function( index, value ) {
                                        if (value.status_coin_refund == 'NO') {
                                            $('#list_booking tbody').append(`
                                                 <tr style="background-color: #FDD692">
                                                      <td>${value.start_date}</td>
                                                      <td>${value.start_hour}</td>
                                                      <td>${value.teacher_id}</td>
                                                      <td>${value.nickname_teacher}</td>
                                                      <td>${value.email_teacher}</td>
                                                      <td>${value.coin}</td>
                                                      <td>0</td>
                                                </tr>
                                            `);
                                        } else {
                                            $('#list_booking tbody').append(`
                                                 <tr>
                                                      <td>${value.start_date}</td>
                                                      <td>${value.start_hour}</td>
                                                      <td>${value.teacher_id}</td>
                                                      <td>${value.nickname_teacher}</td>
                                                      <td>${value.email_teacher}</td>
                                                      <td>${value.coin}</td>
                                                      <td>${value.coin}</td>
                                                </tr>
                                            `);
                                            total_coin_refund = total_coin_refund + parseInt(value.coin);
                                        }
                                    });
                                    $('#total_coin_refund').html(total_coin_refund);
                                    $('#modalCancelBookingTrial').modal('show');
                                } else {
                                    $('#formCancelTrialPayment').submit();
                                }
                            } else {
                                // Error
                                $('#error-message-cancel').html(`
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h6><i class="icon fas fa-ban"></i> ${M050}</h6>
                                    </div>
                                `);
                            }
                        },
                        error: function error(_error) {
                            $("#loading").hide();
                            alert('Error server');
                        }
                    });
                }
            });
        });
    };

    MY_PAGE_STUDENT.cancelPremiumPayment = function() {
        $('#cancelPremiumPayment').click(function() {
            common.bootboxConfirmMultiLanguage(M052, 'small', function (r) {
                if (r) {
                    $('#loading').show();
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    let data = new FormData();
                    data.append('premium_end_date', $('[name=premium_end_date]').val());

                    $.ajax({
                        type: "POST",
                        url: routeCheckCancelPremiumPayment,
                        data: data,
                        cache:false,
                        contentType: false,
                        processData: false,
                        success: function success(result) {
                            $("#loading").hide();
                            if (result.status) {
                                // Success
                                if (result.data.length > 0) {
                                    $('#list_booking_premium tbody').html('');
                                    var total_coin_refund = 0;
                                    $.each(result.data, function( index, value ) {
                                        $('#list_booking_premium tbody').append(`
                                             <tr>
                                                  <td>${value.start_date}</td>
                                                  <td>${value.start_hour}</td>
                                                  <td>${value.teacher_id}</td>
                                                  <td>${value.nickname_teacher}</td>
                                                  <td>${value.email_teacher}</td>
                                                  <td>${value.coin}</td>
                                            </tr>
                                        `);
                                        total_coin_refund = total_coin_refund + parseInt(value.coin);
                                    });
                                    $('#total_coin_refund_premium').html(total_coin_refund);
                                    $('#modalCancelBookingPremium').modal('show');
                                } else {
                                    $('#formCancelPremiumPayment').submit();
                                }
                            } else {
                                // Error
                                $('#error-message-cancel-premium').html(`
                                    <div class="alert alert-danger alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                        <h6><i class="icon fas fa-ban"></i> ${M050}</h6>
                                    </div>
                                `);
                            }
                        },
                        error: function error(_error) {
                            $("#loading").hide();
                            alert('Error server');
                        }
                    });
                }
            });
        });
    }
});
$(document).ready(function () {
    MY_PAGE_STUDENT.init();
});

