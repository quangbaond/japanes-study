let MANAGER_STUDENT_DETAIL = {};
const routeResetPassword = $("[name=route-reset-password]").attr('content');
const routeUpdateProfile = $("[name=route-update-profile]").attr('content');
const routeRefundCoin = $("[name=route-refund-coin]").attr('content');
$(function() {
    MANAGER_STUDENT_DETAIL.init = function() {
        MANAGER_STUDENT_DETAIL.resetPassword();
        MANAGER_STUDENT_DETAIL.updateProfileForStudent();
        MANAGER_STUDENT_DETAIL.refundCoin();
        MANAGER_STUDENT_DETAIL.clearForm();
    };
    const clearError = () => {
        // nickname
        $("#error_nickname").html("");
        $('#nickname').removeClass('is-invalid');

        //birthday
        $("#day").css("border", "");
        $("#month").css("border", "");
        $("#year").css("border", "");
        $("#error_birthday").html('');

        //sex
        $("#error_sex").html('');
        $("#sex").removeClass("is-invalid");

        //nationality
        $("#error_nationality").html('');
        $("#nationality").removeClass("is-invalid");

        //phone_number
        $("#error_phone_number").html('');
        $("#phone_number").removeClass("is-invalid");

        //coin
        $("#error_theNumberOfCoin").html('');
        $("#theNumberOfCoin").removeClass("is-invalid");
        $("#theNumberOfCoin").val("");

    }

    MANAGER_STUDENT_DETAIL.clearForm = () => {
        $('.btnCancel').click(() => {
            clearError();
        })
    }
    MANAGER_STUDENT_DETAIL.resetPassword = function() {
        $('#area_message').html('');
        $('#btnResetPasswordStudent').click(() => {
            $('#modalResetPassword').modal('hide')
            $('#loading').removeClass('d-none');
            $('#loading').addClass('d-block');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                type: "POST",
                url: routeResetPassword,
                data: {},
                success: function(result){
                    $('#loading').removeClass('d-block');
                    $('#loading').addClass('d-none');
                    if (!result.status) {

                    } else {
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.message}
                                </div>
                            </section>
                        `);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(result){
                    $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                     更新が失敗しました。
                                </div>
                            </section>
                    `);
                }
            });
        })
    }
    MANAGER_STUDENT_DETAIL.updateProfileForStudent = () => {
        $('#btnUpdateProfile').click(() => {

            var ifConnected = window.navigator.onLine;

            if (ifConnected) {
                $('#loading').removeClass('d-none');
                $('#loading').addClass('d-block');
                $('#area_message').html('');

                var formData = new FormData();
                formData.append('company_id', $('#company_id').val());

                var data = $('#update-profile').serializeArray();
                data.map((da) => {
                    formData.append(da.name,da.value);
                });

                $.ajax({
                    type: "POST",
                    url: routeUpdateProfile,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result){
                        $('#loading').removeClass('d-block');
                        $('#loading').addClass('d-none');
                        clearError();
                        if (!result.status) {
                            if (result.message.birthday && !result.message.month && !result.message.year) {
                                $("#error_birthday").html(result.message.birthday[0]);
                                $("#day").css("border", "1px solid #f10");
                            }
                            if (result.message.day) {
                                $("#error_birthday").html(result.message.birthday[0]);
                                $("#day").css("border", "1px solid #f10");
                            }
                            if (result.message.month) {
                                $("#error_birthday").html(result.message.birthday[0]);
                                $("#month").css("border", "1px solid #f10");
                            }
                            if (result.message.year) {
                                $("#error_birthday").html(result.message.birthday[0]);
                                $("#year").css("border", "1px solid #f10");
                            }

                            $.each(result.message, (index, val) => {
                                // console.log('')
                                $('#'+index).addClass('is-invalid');
                                $("#error_" + index).html(val);
                            })
                        } else {
                            $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.message}
                                </div>
                            </section>
                        `);
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                        }
                    },
                    error: function(result){
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     更新が失敗しました。
                                </div>
                        </section>
                    `);
                    }
                });
            } else {
                $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     更新が失敗しました。
                                </div>
                        </section>
                    `);
                $("html, body").animate({ scrollTop: 0 }, "slow");
            }

        })
    }
    MANAGER_STUDENT_DETAIL.refundCoin = () => {
        $('#btnRefundCoin').click(() => {
            $('#loading').removeClass('d-none');
            $('#loading').addClass('d-block');
            $('#area_message').html('');
            $('#theNumberOfCoin').removeClass('is-invalid');
            $("#error_theNumberOfCoin").html('');

            var formData = new FormData();
            var data = $('#formRefundPassword').serializeArray();
            data.map((da) => {
                formData.append(da.name,da.value);
            });

            $.ajax({
                type: "POST",
                url: routeRefundCoin,
                data: formData,
                contentType: false,
                processData: false,
                success: function(result){
                    $('#loading').removeClass('d-block');
                    $('#loading').addClass('d-none');
                    if (!result.status) {
                        $.each(result.message, (index, val) => {
                            // console.log('')
                            $('#'+index).addClass('is-invalid');
                            $("#error_" + index).html(val);
                        })
                    } else {
                        clearError();
                        $('#modalRefundCoin').modal('hide');
                        $('.total_coin').html(result.data);
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.message}
                                </div>
                            </section>
                        `);
                        $("html, body").animate({ scrollTop: 0 }, "slow");
                    }
                },
                error: function(result){
                    $('#modalRefundCoin').modal('hide');
                    $('#loading').removeClass('d-block');
                    $('#loading').addClass('d-none');
                    $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     更新が失敗しました。
                                </div>
                            </section>
                    `);
                }
            });
        })
    }
})
$(document).ready(function() {
    MANAGER_STUDENT_DETAIL.init();
});
