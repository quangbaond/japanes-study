let MANAGER_TEACHER_DETAIL = {};
const routeResetPassword = $("[name=route-reset-password]").attr('content');
const routeUpdateProfile = $("[name=route-update-profile]").attr('content');
$(function() {
    MANAGER_TEACHER_DETAIL.init = function() {
        MANAGER_TEACHER_DETAIL.resetPassword();
        MANAGER_TEACHER_DETAIL.updateProfileForTeacher();
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

        //introduction_from_admin
        $("#error_introduction_from_admin").html('');
        $("#introduction_from_admin").removeClass("is-invalid");

        //experience
        $("#error_experience").html('');
        $("#experience").removeClass("is-invalid");

        //certification
        $("#error_certification").html('');
        $("#certification").removeClass("is-invalid");
    }
    MANAGER_TEACHER_DETAIL.resetPassword = function() {
        $('#btnResetPasswordTeacher').click(() => {
            $('#area_message').html('');
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
    MANAGER_TEACHER_DETAIL.updateProfileForTeacher = () => {
        $('#btnUpdateProfile').click(() => {
            let isError = false;
            $('#loading').removeClass('d-none');
            $('#loading').addClass('d-block');
            $('#area_message').html('');

            var formData = new FormData();
            formData.append( 'course', $( '#course' ).val() );


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
                            isError = true;
                        }
                        if (result.message.day) {
                            $("#error_birthday").html(result.message.birthday[0]);
                            $("#day").css("border", "1px solid #f10");
                            isError = true;
                        }
                        if (result.message.month) {
                            $("#error_birthday").html(result.message.birthday[0]);
                            $("#month").css("border", "1px solid #f10");
                            isError = true;
                        }
                        if (result.message.year) {
                            $("#error_birthday").html(result.message.birthday[0]);
                            $("#year").css("border", "1px solid #f10");
                            isError = true;
                        }

                        $.each(result.message, (index, val) => {
                            if(index == 'nickname') {
                                isError = true;
                            }
                            if(index == 'course') {
                                $("#error_" + index).closest('div').addClass('has-error')
                            }
                            $('#'+index).addClass('is-invalid');
                            $("#error_" + index).html(val);
                        })

                        if(isError) {
                            $("html, body").animate({ scrollTop: '250px' }, "slow");
                        }
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
})
$(document).ready(function() {
    MANAGER_TEACHER_DETAIL.init();
});
