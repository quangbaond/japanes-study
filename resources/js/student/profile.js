let PROFILE = {};
const routeChangeNickname = $("[name=route-change-nickname]").attr('content');
const routeChangePassword = $("[name=route-change-password]").attr('content');
const routeChangeEmail    = $("[name=route-change-email]").attr('content');
const routeUpdateProfile  = $("[name=route-update-profile]").attr('content');
const messageM049         = $("[name=message-M049]").attr('content');
const messageM024         = $("[name=message-M024]").attr('content');
const messageM019         = $("[name=message-M019]").attr('content');
let urlInitialAvatarImage = $("[name=url-avatar-image]").attr('content');
let urlAvatarImage = $("[name=url-avatar-image]").attr('content');
const urlAvatarImageDefault = $("[name=url-avatar-image-default]").attr('content');
const messageChangeNicknameSuccess = $("[name=change-nickname-success]").attr('content');
const messageChangePasswordSuccess = $("[name=change-password-success]").attr('content');

const CONST_UPDATE_IMAGE_STATUS = 1;
const CONST_REMOVE_IMAGE_STATUS = 2;
var check_avatar_image = CONST_UPDATE_IMAGE_STATUS; // 1 update image, 2 remove image
let checkErrorImage = false;
$(function () {
    PROFILE.init = function () {
        PROFILE.changeNickname();
        PROFILE.changePassword();
        PROFILE.openModal();
        PROFILE.changeEmail();

        //image
        PROFILE.setAvatarImage();
        PROFILE.changeAvatar();
        PROFILE.removeAvatarImage();

        //update profile
        PROFILE.updateProfile();
    };

    PROFILE.changeNickname = () => {
        $('#btnChangeNickname').click( () => {
            $('#error_new_nickname').html("");
            $('#new_nickname').removeClass('is-invalid');
            let data = new FormData();
            data.append( 'old_nickname',$('#old_nickname').val());
            data.append( 'new_nickname',$('#new_nickname').val());
            data.append( '_token',$("[name=csrf-token]").attr('content'));
            $.ajax({
                type: "POST",
                url: routeChangeNickname,
                data: data,
                contentType: false,
                processData: false,
                success: function(result){
                    if (!result.status) {
                        $('#error_new_nickname').html(result.message.new_nickname);
                        $('#new_nickname').addClass('is-invalid');
                    } else {
                        $('.nickname').html($('#new_nickname').val());
                        $('#modalUpdateNickname').modal('hide');
                        // $('#modalUpdateNicknameSuccessfully').modal('show');
                        $('#old_nickname').val($('#new_nickname').val());
                        $('#new_nickname').val('');
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${messageChangeNicknameSuccess}
                                </div>
                            </section>
                        `);
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
    PROFILE.changePassword = () => {
        $("#btnChangePassword").click(function(){
            $('#area_message').html("");
            $("#error_old_password").html('');
            $("#error_new_password").html('');
            $("#error_new_password_confirmation").html('');
            let data = $("#formChangePassword").serialize();
            $.ajax({
                type: "POST",
                url: routeChangePassword,
                data: data,
                success: function(result){
                    if (!result.status) {
                        $("#error_old_password").html("");
                        $("#error_new_password").html("");
                        $("#error_new_password_confirmation").html("");
                        $('#old_password').removeClass('is-invalid');
                        $('#new_password').removeClass('is-invalid');
                        $('#new_password_confirmation').removeClass('is-invalid');
                        $.each(result.message, (index, val) => {
                            // console.log('')
                            $('#'+index).addClass('is-invalid');
                            $("#error_" + index).html(val);
                        })
                    } else {
                        $("#old_password").val('');
                        $("#new_password").val('');
                        $("#new_password_confirmation").val('');
                        $("#error_old_password").html("");
                        $("#error_new_password").html("");
                        $("#error_new_password_confirmation").html("");
                        $('#old_password').removeClass('is-invalid');
                        $('#new_password').removeClass('is-invalid');
                        $('#new_password_confirmation').removeClass('is-invalid');
                        $("#modalChangePassword").modal('hide');
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${messageChangePasswordSuccess}
                                </div>
                            </section>
                        `);

                        // $("#modalUpdatePasswordSuccessfully").modal('show');
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
        });
    }

    PROFILE.changeEmail = () => {
        $('#btnSendMailConfirm').click( () => {
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            $('#area_message').html("");
            let data = $("#formChangeEmail").serializeArray();
            var fd = new FormData();
            fd.append( 'old_email', $( 'input[name="old_email"]' ).val() );

            data.map((da) => {
                fd.append(da.name,da.value);
            });
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: routeChangeEmail,
                data: fd,
                contentType: false,
                processData: false,
                success: function(result){
                    if (!result.status) {
                        $("#loading").removeClass('d-block');
                        $('#loading').addClass("d-none");
                        $('#error_old_email').html("");
                        $('#error_new_email').html("");
                        $('#error_new_email_confirmation').html("");
                        $("#old_email").removeClass('is-invalid');
                        $("#new_email").removeClass('is-invalid');
                        $("#new_email_confirmation").removeClass('is-invalid');
                        $.each(result.message, (index, val) => {
                            $('#'+index).addClass('is-invalid');
                            $("#error_" + index).html(val);
                        })
                    } else {
                        $('#error_old_email').html("");
                        $('#error_new_email').html("");
                        $('#error_new_email_confirmation').html("");
                        $('#email').html('<b>' + $('#new_email').val()+ '</b>' +`<br><span class="text-warning">&nbsp;<i class="fas fa-exclamation-triangle"></i>&nbsp; ${messageM049}</span>`);
                        $('#new_email').val("");
                        $('#new_email_confirmation').val("");
                        $('#modalUpdateEmail').modal('hide');
                        $("#loading").removeClass('d-block');
                        $('#loading').addClass("d-none");
                        $('#modalSendEmailSuccessfully').modal('show');
                        $("#old_email").removeClass('is-invalid');
                        $("#new_email").removeClass('is-invalid');
                        $("#new_email_confirmation").removeClass('is-invalid');
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
        });
    }

    //remove value and error when click open modal
    PROFILE.openModal = () => {
        $('a[data-target="#modalChangePassword"]').click( () => {
            removeValue();
        })
        $('a[data-target="#modalUpdateNickname"]').click( () => {
            removeValue();
        })
        $('a[data-target="#modalUpdateEmail"]').click( () => {
            removeValue();
        })
    }
    const removeValue = () => {
        //popup change nickname
        $('#new_nickname').val("");
        $('#new_nickname').removeClass("is-invalid");
        $('#error_new_nickname').html("");

        //popup change password
        $('#new_password_confirmation').val("");
        $('#new_password_confirmation').removeClass("is-invalid");
        $('#error_new_password_confirmation').html("");

        $('#old_password').val("");
        $('#old_password').removeClass("is-invalid");
        $('#error_old_password').html("");

        $('#new_password').val("");
        $('#new_password').removeClass("is-invalid");
        $('#error_new_password').html("");

        //popup change email
        $('#error_old_email').html("");

        $('#new_email').val("");
        $('#new_email').removeClass("is-invalid");
        $('#error_new_email').html("");

        $('#new_email_confirmation').val("");
        $('#new_email_confirmation').removeClass("is-invalid");
        $('#error_new_email_confirmation').html("");
    }

    //change avatar image
    PROFILE.changeAvatar = () => {
        $("#upload-photo").change(function () {
            $('#area_message').html("");
            check_avatar_image = CONST_UPDATE_IMAGE_STATUS;
            readURL(this);
        });
        const readURL= (input) => {
            if (input.files && input.files[0]) {
                $('#error_upload_photo').html('');
                var pic_size = input.files[0].size/1024/1024;//get file size (MB)
                let reader = new FileReader();
                reader.onload = function (e) {
                    if (validImage("#upload-photo")) {
                        if(pic_size >= 5){
                            $('#error_upload_photo').html(messageM019);
                            checkErrorImage = true;
                        }
                        else {
                            $('#error_upload_photo').html('');
                            $("#box").css("background-image", "url(" + e.target.result + ")");
                            checkErrorImage = false;
                        }
                    }
                    else {
                        $('#error_upload_photo').html(messageM024);
                        checkErrorImage = true;
                    }

                };
                reader.readAsDataURL(input.files[0]);
            }
        }
        // Valid Image
        const validImage = file_id => {
            let fileExtension = ['jpg', 'jpeg', 'png','gif'];
            let valid = true;
            let msg = "";
            if ($(file_id).val() == '') {
                valid = false;
            } else {
                var fileName = $(file_id).val();
                var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
                if ($.inArray(fileNameExt, fileExtension) == -1) {
                    valid = false;
                }
            }
            return valid //true or false
        }
    }
    PROFILE.setAvatarImage = () => {
        urlAvatarImage ? $("#box").css("background-image", `url("${urlAvatarImage}")`) : false;
        urlInitialAvatarImage = urlInitialAvatarImage || urlAvatarImageDefault;
    }
    PROFILE.removeAvatarImage = () => {
        $('#remove-image').click( ()=> {
            $('#error_upload_photo').html("");
            $('#area_message').html("");
            checkErrorImage = false;
            if( $('#upload-photo').val() === ""  ) {
                $("#box").css("background-image", `url("${urlAvatarImageDefault}")`);
                check_avatar_image = CONST_REMOVE_IMAGE_STATUS;
            }
            else {
                $("#box").css("background-image", `url("${urlAvatarImage || urlAvatarImageDefault}")`);
                $('#upload-photo').val(null);
            }
        });
    }
    PROFILE.updateProfile = function() {
        //Change password

        $("#btnUpdateProfile").click(function(){
            if(checkInternet()) {
                $("#loading").removeClass('d-none');
                $('#loading').addClass("d-block");
                $('#area_message').html("");
                var formData = new FormData();
                if(!checkErrorImage) {
                    let upload_photo = $( '#upload-photo' )[0].files[0];
                    if (upload_photo) {
                        formData.append( 'image_photo', upload_photo );
                    }
                    if (check_avatar_image === 2) {
                        formData.append( 'check_avatar_image', check_avatar_image );
                    }
                }
                // let data = $("#update-profile").serializeArray();
                // data.map((da) => {
                //     formData.append(da.name,da.value);
                // });
                formData.append( '_token',$("[name=csrf-token]").attr('content'));
                formData.append( 'year', $('#year').val() );
                formData.append( 'month', $('#month').val() );
                formData.append( 'day', $('#day').val() );
                formData.append( 'sex', $('input[name=sex]:checked').val() || "");
                formData.append( 'nationality', $('select[name="nationality"]').val() );
                formData.append( 'area_code', $('select[name="area_code"]').val() );
                formData.append( 'phone_number', $('#phone_number').val() );

                $.ajax({
                    type: "POST",
                    dataType: 'json',
                    url: routeUpdateProfile,
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result){
                        $("#loading").removeClass('d-block');
                        $('#loading').addClass("d-none");
                        if (!result.status) {
                            $("#error_birthday").html('');
                            $("#error_sex").html('');
                            $("#error_nationality").html('');
                            $("#error_phone_number").html('');
                            $('#error_upload_photo').html('');

                            $("#year").removeClass("is-invalid");
                            $("#month").removeClass("is-invalid");
                            $("#day").removeClass("is-invalid");
                            $("#phone_number").removeClass("is-invalid");
                            $("#sex").removeClass("is-invalid");
                            $("#nationality").removeClass("is-invalid");

                            $("#day").css("border", "");
                            $("#month").css("border", "");
                            $("#year").css("border", "");

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

                            if (result.message.sex) {
                                $("#error_sex").html(result.message.sex[0]);
                                $("#sex").addClass("is-invalid");
                            }

                            if (result.message.phone_number) {
                                $("#error_phone_number").html(result.message.phone_number[0]);
                                $("#phone_number").addClass("is-invalid");
                            }


                        } else {
                            urlAvatarImage = result.data.image_photo;
                            urlLinkYoutubeDefault = result.data.link_youtube;
                            console.log(urlAvatarImage);
                            if(urlAvatarImage === null) {
                                // $("#box").css("background-image", "url(" + urlInitialAvatarImage + ")");
                                $("#box").css("background-image", "url(" + urlAvatarImageDefault + ")");
                            }
                            $('#error_upload_photo').html('');
                            checkErrorImage = false;
                            $("#day").css("border", "");
                            $("#month").css("border", "");
                            $("#year").css("border", "");

                            $("#error_birthday").html('');
                            $("#error_sex").html('');
                            $("#error_nationality").html('');
                            $("#error_phone_number").html('');

                            $("#birthday").removeClass("is-invalid");
                            $("#sex").removeClass("is-invalid");
                            $("#nationality").removeClass("is-invalid");
                            $("#phone_number").removeClass("is-invalid");

                            $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.message}
                                </div>
                            </section>
                        `);
                            $( '#upload-photo' ).val(null);
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
            }
        });
    }
    function checkInternet() {
        let ifConnected = window.navigator.onLine;
        if(ifConnected) {
            $('#area_message').html('');
            return true;
        }else {
            $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     Update failed.
                                </div>
                        </section>
                    `);
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }
    }
});

$(document).ready(function(){
    PROFILE.init();
});
