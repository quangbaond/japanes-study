const ADMIN_DETAIL = {}

let urlInitialAvatarImage = $("[name=url-avatar-image]").attr('content');
let urlAvatarImage = $("[name=url-avatar-image]").attr('content');
const routeUpdateProfile  = $("[name=route-update-profile]").attr('content');
const urlAvatarImageDefault = $("[name=url-avatar-image-default]").attr('content');
const routeChangePassword = $("[name=route-change-password]").attr('content');
const routeResetPassword = $("[name=route-reset-password]").attr('content');
const messageM049         = $("[name=message-M049]").attr('content');
const messageM024         = $("[name=message-M024]").attr('content');
const messageM019         = $("[name=message-M019]").attr('content');
const CONST_UPDATE_IMAGE_STATUS = 1;
const CONST_REMOVE_IMAGE_STATUS = 2;
var check_avatar_image = CONST_UPDATE_IMAGE_STATUS; // 1 update image, 2 remove image
let checkErrorImage = false;
$(function() {
    ADMIN_DETAIL.init = function() {
        //image
        ADMIN_DETAIL.setAvatarImage();
        ADMIN_DETAIL.changeAvatar();
        ADMIN_DETAIL.removeAvatarImage();

        //update profile
        ADMIN_DETAIL.updateProfile();
        ADMIN_DETAIL.changePassword();
        ADMIN_DETAIL.resetPassword();
    }
    //change avatar image
    ADMIN_DETAIL.changeAvatar = () => {
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
    ADMIN_DETAIL.setAvatarImage = () => {
        urlAvatarImage ? $("#box").css("background-image", `url("${urlAvatarImage}")`) : false;
        urlInitialAvatarImage = urlInitialAvatarImage || urlAvatarImageDefault;
    }
    ADMIN_DETAIL.removeAvatarImage = () => {
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

    ADMIN_DETAIL.updateProfile = function() {
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
                formData.append( '_token',$("[name=csrf-token]").attr('content'));
                formData.append( 'user_id', $('#user_id').val() );
                formData.append( 'year', $('#year').val() );
                formData.append( 'month', $('#month').val() );
                formData.append( 'day', $('#day').val() );
                formData.append( 'sex', $('input[name=sex]:checked').val() || "");
                formData.append( 'nationality', $('select[name="nationality"]').val() );
                formData.append( 'area_code', $('select[name="area_code"]').val() );
                formData.append( 'phone_number', $('#phone_number').val() );
                formData.append( 'nickname', $('#nickname').val() );
                formData.append( 'role', $('input[name=role]:checked').val() );

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

                            $('.error-custom').html('');

                            $('body').find('select,input').each(function() {
                                $(this).removeClass('is-invalid');
                            });

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

                            // if (result.message.phone_number) {
                            //     $("#error_phone_number").html(result.message.phone_number[0]);
                            //     $("#phone_number").addClass("is-invalid");
                            // }
                            $.each(result.message, (index, val) => {
                                // console.log('')
                                $('#'+index).addClass('is-invalid');
                                $("#error_" + index).html(val);
                            })


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

                            $('.error-custom').html('');

                            $('body').find('select,input').each(function() {
                                $(this).removeClass('is-invalid');
                            });

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

    ADMIN_DETAIL.changePassword = () => {
        $('#resetPassword').on('click', function() {
            removeValue();
        })
        $("#btnChangePassword").click(function(){
            if(checkInternet()) {
                $("#loading").removeClass('d-none');
                $('#loading').addClass("d-block");
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
                        $("#loading").removeClass('d-block');
                        $('#loading').addClass("d-none");
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
                                    ${result.message}
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
                                     ${result.message}
                                </div>
                            </section>
                    `);
                    }
                });
            }
        });
    }

    ADMIN_DETAIL.resetPassword = function() {
        $('#area_message').html('');
        $('#btnResetPasswordAdmin').click(() => {
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

    const removeValue = () => {
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
                                     更新が失敗しました。
                                </div>
                        </section>
                    `);
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }
    }
})
$(document).ready(function() {
    ADMIN_DETAIL.init();
})
