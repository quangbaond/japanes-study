var Days = [31,28,31,30,31,30,31,31,30,31,30,31];// index => month [0-11]
let TEACHER_EDIT = {};
const routeUpdateProfile = $("[name=route-update-profile]").attr('content');
const routeChangePassword = $("[name=route-change-password]").attr('content');
let urlInitialAvatarImage = $("[name=url-avatar-image]").attr('content');
let urlAvatarImage = $("[name=url-avatar-image]").attr('content');
const routeChangeEmail = $("[name=route-change-email]").attr('content');
const urlAvatarImageDefault = $("[name=url-avatar-image-default]").attr('content');
const routeTeacherLogin = $("[name=route-teacher-login]").attr('content');
const routeRemoveCourse = $("[name=route-remove-course]").attr('content');
const routeAddCourse = $("[name=route-add-course]").attr('content');
const token = $("[name=csrf-token]").attr('content');
let urlLinkYoutubeDefault = $("[name=url-link-youtube]").attr('content');
const routeValidateLinkYoutube = $("[name=route-validate-link-youtube]").attr('content');
const messageRequired = $("[name=message-required]").attr('content');
const messageYoutubeLinkInvalid = $("[name=message-youtube-link-invalid]").attr('content');

const CONST_UPDATE_IMAGE_STATUS = 1;
const CONST_REMOVE_IMAGE_STATUS = 2;
var check_avatar_image = CONST_UPDATE_IMAGE_STATUS; // 1 update image, 2 remove image
let checkErrorImage = false;
$(function () {
    TEACHER_EDIT.init = function () {
        TEACHER_EDIT.changePassword();
        TEACHER_EDIT.changeAvatar();
        TEACHER_EDIT.updateProfile();
        TEACHER_EDIT.setAvatarImage();
        TEACHER_EDIT.changeEmail();
        // TEACHER_EDIT.changeCourse();
        TEACHER_EDIT.changeLinkYoutube();
        TEACHER_EDIT.removeVideo();
        TEACHER_EDIT.removeAvatarImage();
        TEACHER_EDIT.removeValueWhenCancel();
    };
    TEACHER_EDIT.setAvatarImage = () => {
        urlAvatarImage ? $("#box").css("background-image", `url("${urlAvatarImage}")`) : false;
        urlInitialAvatarImage = urlInitialAvatarImage || urlAvatarImageDefault;
    }
    TEACHER_EDIT.changePassword = function() {
        //Change password
        $("#btnChangePassword").click(function(){
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            $('#area_message').html("");
            $("#error_password_old").html('');
            $("#error_password_new").html('');
            $("#error_password_confirm").html('');
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
                        if (result.message.old_password) {
                            $('#old_password').addClass('is-invalid');
                            $("#error_old_password").html(result.message.old_password[0]);
                        }
                        if (result.message.new_password) {
                            $('#new_password').addClass('is-invalid');
                            $("#error_new_password").html(result.message.new_password[0]);
                        }
                        if (result.message.new_password_confirmation) {
                            $('#new_password_confirmation').addClass('is-invalid');
                            $("#error_new_password_confirmation").html(result.message.new_password_confirmation[0]);
                        }
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
                        // $("#modalChangePasswordSuccessfully").modal('show');
                        $('#area_message').html(`
                                <section class="content-header px-0">
                                    <div class="alert alert-success alert-dismissible">
                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                        <i class="icon fa fa-check"></i>
                                        ${result.message}
                                    </div>
                                </section>
                        `);
                        $("html, body").animate({ scrollTop: '0px' }, "slow");
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
        $('#btnResetPasswordTeacher').click(function(){
            $('#modalChangePasswordSuccessfully').modal('show')
        })
    }
    TEACHER_EDIT.changeAvatar = () => {
        $("#upload-photo").change(function () {
            $('#area_message').html("");
            check_avatar_image = CONST_UPDATE_IMAGE_STATUS;
            readURL(this);
        });
        const readURL= (input) => {
            if (input.files && input.files[0]) {
                $('#error-upload_photo').html('');
                var pic_size = input.files[0].size/1024/1024;//get file size (MB)
                let reader = new FileReader();
                reader.onload = function (e) {
                    if (validImage("#upload-photo")) {
                        if(pic_size >= 5){
                            $('#error-upload_photo').html('写真ファイルを5MB以下のサイズにしてください。');
                            // $("#image_url").val('');
                            checkErrorImage = true;
                        }
                        else {
                            $('#error-upload_photo').html('');
                            $("#box").css("background-image", "url(" + e.target.result + ")");
                            checkErrorImage = false;
                        }
                    }
                    else {
                        $('#error-upload_photo').html('画像形式が正しくありません。対応する画像形式は（JPEG・JPG・PNG・GIF）です。');
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
    TEACHER_EDIT.updateProfile = function() {
        //Change password
        $("#btnUpdateProfile").click(function(){
            let isError = false;
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            $('#area_message').html("");
            var fd = new FormData();
            fd.append( 'link_youtube',$('#link_youtube').attr('src'));
            if(!checkErrorImage) {
                let upload_photo = $( '#upload-photo' )[0].files[0];
                if (upload_photo) {
                    fd.append( 'image_photo', upload_photo );
                }
                if (check_avatar_image === 2) {
                    fd.append( 'check_avatar_image', check_avatar_image );
                }
            }
            fd.append( 'course', $( '#course' ).val() );
            let data = $("#update-profile").serializeArray();
            data.map((da) => {
                fd.append(da.name,da.value);
            });
            $.ajax({
                type: "POST",
                dataType: 'json',
                url: routeUpdateProfile,
                data: fd,
                contentType: false,
                processData: false,
                success: function(result){
                    $("#loading").removeClass('d-block');
                    $('#loading').addClass("d-none");
                    if (!result.status) {
                        $("#error-nickname").html('');
                        $("#error-birthday").html('');
                        $("#error-sex").html('');
                        $("#error-nationality").html('');
                        $("#error-phone_number").html('');
                        $("#error-self_introduction").html('');
                        $("#error-experience").html('');
                        $("#error-certification").html('');
                        $("#error-course").html('');
                        $("#error-link_zoom").html('');
                        $('#error-upload_photo').html('');
                        $("#nickname").removeClass("is-invalid");
                        $("#birthday").removeClass("is-invalid");
                        $("#sex").removeClass("is-invalid");
                        $("#nationality").removeClass("is-invalid");
                        $("#phone_number").removeClass("is-invalid");
                        $("#self_introduction").removeClass("is-invalid");
                        $("#experience").removeClass("is-invalid");
                        $("#certification").removeClass("is-invalid");
                        $("#course").removeClass("is-invalid");
                        $("#day").css("border", "");
                        $("#month").css("border", "");
                        $("#year").css("border", "");
                        $("#link_zoom").removeClass("is-invalid");
                        if (result.message.nickname) {
                            $("#error-nickname").html(result.message.nickname[0]);
                            $("#nickname").addClass("is-invalid");
                            isError = true;
                        }
                        if (result.message.birthday && !result.message.month && !result.message.year) {
                            $("#error-birthday").html(result.message.birthday[0]);
                            $("#day").css("border", "1px solid #f10");
                            isError = true;
                        }
                        if (result.message.day) {
                            $("#error-birthday").html(result.message.birthday[0]);
                            $("#day").css("border", "1px solid #f10");
                            isError = true;
                        }
                        if (result.message.month) {
                            $("#error-birthday").html(result.message.birthday[0]);
                            $("#month").css("border", "1px solid #f10");
                            isError = true;
                        }
                        if (result.message.year) {
                            $("#error-birthday").html(result.message.birthday[0]);
                            $("#year").css("border", "1px solid #f10");
                            isError = true;
                        }
                        if (result.message.sex) {
                            $("#error-sex").html(result.message.sex[0]);
                            $("#sex").addClass("is-invalid");
                            isError = true;
                        }
                        if (result.message.nationality) {
                            $("#error-nationality").html(result.message.nationality[0]);
                            $("#nationality").addClass("is-invalid");
                        }
                        if (result.message.phone_number) {
                            $("#error-phone_number").html(result.message.phone_number[0]);
                            $("#phone_number").addClass("is-invalid");
                        }
                        if (result.message.self_introduction) {
                            $("#error-self_introduction").html(result.message.self_introduction[0]);
                            $("#self_introduction").addClass("is-invalid");
                        }
                        if (result.message.experience) {
                            $("#error-experience").html(result.message.experience[0]);
                            $("#experience").addClass("is-invalid");
                        }
                        if (result.message.certification) {
                            $("#error-certification").html(result.message.certification[0]);
                            $("#certification").addClass("is-invalid");
                        }
                        if (result.message.course) {
                            $("#error-course").html(result.message.course[0]);
                            $("#course").addClass("is-invalid");
                        }
                        if (result.message.link_zoom) {
                            $("#error-link_zoom").html(result.message.link_zoom[0]);
                            $("#link_zoom").addClass("is-invalid");
                        }
                        if(isError) {
                            $("html, body").animate({ scrollTop: '200px' }, "slow");
                        }
                    } else {
                        urlAvatarImage = result.data.image_photo;
                        urlLinkYoutubeDefault = result.data.link_youtube;
                        if(urlAvatarImage === null) {
                            $("#box").css("background-image", "url(" + urlInitialAvatarImage + ")");
                        }
                        $('#error-upload_photo').html('');
                        checkErrorImage = false;
                        $('#sidebar_nickname').html($('#nickname').val());
                        $("#day").css("border", "");
                        $("#month").css("border", "");
                        $("#year").css("border", "");
                        $("#error-nickname").html('');
                        $("#error-birthday").html('');
                        $("#error-sex").html('');
                        $("#error-nationality").html('');
                        $("#error-phone_number").html('');
                        $("#error-self_introduction").html('');
                        $("#error-link_zoom").html('');
                        $("#error-experience").html('');
                        $("#error-certification").html('');
                        $("#error-course").html('');
                        $("#nickname").removeClass("is-invalid");
                        $("#birthday").removeClass("is-invalid");
                        $("#sex").removeClass("is-invalid");
                        $("#nationality").removeClass("is-invalid");
                        $("#phone_number").removeClass("is-invalid");
                        $("#self_introduction").removeClass("is-invalid");
                        $("#experience").removeClass("is-invalid");
                        $("#certification").removeClass("is-invalid");
                        $("#course").removeClass("is-invalid");
                        $("#link_zoom").removeClass("is-invalid");
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-success alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-check"></i>
                                    ${result.message}
                                </div>
                            </section>
                        `);
                        $('#area_require_zoom_link').html('');
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
        });
    }
    TEACHER_EDIT.changeEmail = () => {
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
                        if(result.message.old_email) {
                            $('#old_email').addClass('is-invalid');
                            $('#error_old_email').html(result.message.old_email[0]);
                        }
                        if(result.message.new_email) {
                            $('#new_email').addClass('is-invalid');
                            $('#error_new_email').html(result.message.new_email[0]);
                        }
                        if(result.message.new_email_confirmation[0]) {
                            $('#new_email_confirmation').addClass('is-invalid');
                            $('#error_new_email_confirmation').html(result.message.new_email_confirmation[0]);
                        }
                    } else {
                        $('#error_old_email').html("");
                        $('#error_new_email').html("");
                        $('#error_new_email_confirmation').html("");
                        $('#email').html($('#new_email').val());
                        $('#new_email').val("");
                        $('#new_email_confirmation').val("");
                        $('#updateEmail').modal('hide');
                        $("#loading").removeClass('d-block');
                        $('#loading').addClass("d-none");
                        $('#sentMailConfirm').modal('show');
                        $("#old_email").removeClass('is-invalid');
                        $("#new_email").removeClass('is-invalid');
                        $("#new_email_confirmation").removeClass('is-invalid');
                        $('#warning-email').html('<span class="text-warning"><i class="fas fa-exclamation-triangle"></i></span>&nbsp;メールアドレスが未認証です。');
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
    TEACHER_EDIT.changeLinkYoutube = () => {
        $('#btnChangeLinkYoutube').click( () => {
            $("#loading").removeClass('d-none');
            $('#loading').addClass("d-block");
            $('#area_message').html("");

            let url = $('#input_link_youtube').val();
            if(url == undefined || url == '') {
                $("#loading").removeClass('d-block');
                $('#loading').addClass("d-none");
                $('#input_link_youtube').addClass("is-invalid");
                $('#error-link_youtube').html(messageRequired);
            }
            else if (url != undefined || url != '') {
                var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
                var match = url.match(regExp);
                if (match && match[2].length == 11) {
                    $('#error-link_youtube').html("");
                    $("#loading").removeClass('d-block');
                    $('#loading').addClass("d-none");
                    url = 'https://www.youtube.com/embed/' + match[2];
                    $('#link_youtube').attr('src',url);
                    $('#input_link_youtube').val("");
                    $('#input_link_youtube').removeClass("is-invalid");
                    $('#modalChangeLinkYoutube').modal("hide");
                    // $('#videoObject').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=1&enablejsapi=1');
                } else {
                    $("#loading").removeClass('d-block');
                    $('#loading').addClass("d-none");
                    $('#error-link_youtube').html("");
                    $('#input_link_youtube').addClass("is-invalid");
                    $('#error-link_youtube').html(messageYoutubeLinkInvalid);
                }
            }
            // var fd = new FormData();
            // fd.append( 'link_youtube', url );
            //
            // $.ajaxSetup({
            //     headers: {
            //         "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
            //     }
            // });
            // $.ajax({
            //     type: "POST",
            //     dataType: 'json',
            //     url: routeValidateLinkYoutube,
            //     data: fd,
            //     contentType: false,
            //     processData: false,
            //     success: function(result){
            //         if (!result.status) {
            //             $("#loading").removeClass('d-block');
            //             $('#loading').addClass("d-none");
            //             $('#error-link_youtube').html("");
            //             if(result.message.link_youtube) {
            //                 $('#input_link_youtube').addClass("is-invalid");
            //                 $('#error-link_youtube').html(result.message.link_youtube[0]);
            //             }
            //         } else {
            //             $('#error-link_youtube').html("");
            //             $("#loading").removeClass('d-block');
            //             $('#loading').addClass("d-none");
            //             url = url.replace("watch?v=", "embed/")
            //             $('#link_youtube').attr('src',url);
            //             $('#input_link_youtube').val("");
            //             $('#modalChangeLinkYoutube').modal("hide");
            //         }
            //     },
            //     error: function(result){
            //         $('#area_message').html(`
            //                 <section class="content-header">
            //                     <div class="alert alert-danger alert-dismissible">
            //                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            //                         <i class="icon fa fa-check"></i>
            //                          更新が失敗しました。
            //                     </div>
            //                 </section>
            //         `);
            //     }
            // });
        });
    }
    TEACHER_EDIT.removeVideo = () => {
        $('#btnRemoveVideo').click( ()=> {
            $('#area_message').html("");
            let currentLinkYoutube = $('#link_youtube').attr('src');
            if(currentLinkYoutube !== urlLinkYoutubeDefault) {
                $('#link_youtube').attr('src',urlLinkYoutubeDefault);
            }
            else if(currentLinkYoutube === urlLinkYoutubeDefault) {
                $('#link_youtube').attr('src',"");
            }
        });
    }
    TEACHER_EDIT.removeAvatarImage = () => {
        $('#remove-image').click( ()=> {
            $('#area_message').html("");
            $('#error-upload_photo').html("");
            checkErrorImage = false;
            if( $('#upload-photo').val() === "" ) {
                $("#box").css("background-image", `url("${urlAvatarImageDefault}")`);
                check_avatar_image = CONST_REMOVE_IMAGE_STATUS;
            }
            else {
                $("#box").css("background-image", `url("${urlAvatarImage || urlAvatarImageDefault}")`);
                $('#upload-photo').val(null);
            }
        });
    }
    TEACHER_EDIT.removeValueWhenCancel = () => {
        $('.btnCancel').click(() => {
            removeValue();
        });
        $('#modalChangeLinkYoutube').on('hide.bs.modal', () => {
            removeValue();
        });
    }

    const removeValue = () => {
        //popup change link
        $('#input_link_youtube').val("");
        $('#input_link_youtube').removeClass("is-invalid");
        $('#error-link_youtube').html("");



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
});
$(document).ready(function(){
    TEACHER_EDIT.init();
});
