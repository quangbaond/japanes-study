/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 14);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/teachers/edit.js":
/*!*********************************************!*\
  !*** ./resources/js/admin/teachers/edit.js ***!
  \*********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var Days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // index => month [0-11]

var TEACHER_EDIT = {};
var routeUpdateProfile = $("[name=route-update-profile]").attr('content');
var routeChangePassword = $("[name=route-change-password]").attr('content');
var urlInitialAvatarImage = $("[name=url-avatar-image]").attr('content');
var urlAvatarImage = $("[name=url-avatar-image]").attr('content');
var routeChangeEmail = $("[name=route-change-email]").attr('content');
var urlAvatarImageDefault = $("[name=url-avatar-image-default]").attr('content');
var routeTeacherLogin = $("[name=route-teacher-login]").attr('content');
var routeRemoveCourse = $("[name=route-remove-course]").attr('content');
var routeAddCourse = $("[name=route-add-course]").attr('content');
var token = $("[name=csrf-token]").attr('content');
var urlLinkYoutubeDefault = $("[name=url-link-youtube]").attr('content');
var routeValidateLinkYoutube = $("[name=route-validate-link-youtube]").attr('content');
var messageRequired = $("[name=message-required]").attr('content');
var messageYoutubeLinkInvalid = $("[name=message-youtube-link-invalid]").attr('content');
var CONST_UPDATE_IMAGE_STATUS = 1;
var CONST_REMOVE_IMAGE_STATUS = 2;
var check_avatar_image = CONST_UPDATE_IMAGE_STATUS; // 1 update image, 2 remove image

var checkErrorImage = false;
$(function () {
  TEACHER_EDIT.init = function () {
    TEACHER_EDIT.changePassword();
    TEACHER_EDIT.changeAvatar();
    TEACHER_EDIT.updateProfile();
    TEACHER_EDIT.setAvatarImage();
    TEACHER_EDIT.changeEmail(); // TEACHER_EDIT.changeCourse();

    TEACHER_EDIT.changeLinkYoutube();
    TEACHER_EDIT.removeVideo();
    TEACHER_EDIT.removeAvatarImage();
    TEACHER_EDIT.removeValueWhenCancel();
  };

  TEACHER_EDIT.setAvatarImage = function () {
    urlAvatarImage ? $("#box").css("background-image", "url(\"".concat(urlAvatarImage, "\")")) : false;
    urlInitialAvatarImage = urlInitialAvatarImage || urlAvatarImageDefault;
  };

  TEACHER_EDIT.changePassword = function () {
    //Change password
    $("#btnChangePassword").click(function () {
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      $('#area_message').html("");
      $("#error_password_old").html('');
      $("#error_password_new").html('');
      $("#error_password_confirm").html('');
      var data = $("#formChangePassword").serialize();
      $.ajax({
        type: "POST",
        url: routeChangePassword,
        data: data,
        success: function success(result) {
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
            $("#modalChangePassword").modal('hide'); // $("#modalChangePasswordSuccessfully").modal('show');

            $('#area_message').html("\n                                <section class=\"content-header px-0\">\n                                    <div class=\"alert alert-success alert-dismissible\">\n                                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                        <i class=\"icon fa fa-check\"></i>\n                                        ".concat(result.message, "\n                                    </div>\n                                </section>\n                        "));
            $("html, body").animate({
              scrollTop: '0px'
            }, "slow");
          }
        },
        error: function error(result) {
          $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                    ");
        }
      });
    });
    $('#btnResetPasswordTeacher').click(function () {
      $('#modalChangePasswordSuccessfully').modal('show');
    });
  };

  TEACHER_EDIT.changeAvatar = function () {
    $("#upload-photo").change(function () {
      $('#area_message').html("");
      check_avatar_image = CONST_UPDATE_IMAGE_STATUS;
      readURL(this);
    });

    var readURL = function readURL(input) {
      if (input.files && input.files[0]) {
        $('#error-upload_photo').html('');
        var pic_size = input.files[0].size / 1024 / 1024; //get file size (MB)

        var reader = new FileReader();

        reader.onload = function (e) {
          if (validImage("#upload-photo")) {
            if (pic_size >= 5) {
              $('#error-upload_photo').html('写真ファイルを5MB以下のサイズにしてください。'); // $("#image_url").val('');

              checkErrorImage = true;
            } else {
              $('#error-upload_photo').html('');
              $("#box").css("background-image", "url(" + e.target.result + ")");
              checkErrorImage = false;
            }
          } else {
            $('#error-upload_photo').html('画像形式が正しくありません。対応する画像形式は（JPEG・JPG・PNG・GIF）です。');
            checkErrorImage = true;
          }
        };

        reader.readAsDataURL(input.files[0]);
      }
    }; // Valid Image


    var validImage = function validImage(file_id) {
      var fileExtension = ['jpg', 'jpeg', 'png', 'gif'];
      var valid = true;
      var msg = "";

      if ($(file_id).val() == '') {
        valid = false;
      } else {
        var fileName = $(file_id).val();
        var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();

        if ($.inArray(fileNameExt, fileExtension) == -1) {
          valid = false;
        }
      }

      return valid; //true or false
    };
  };

  TEACHER_EDIT.updateProfile = function () {
    //Change password
    $("#btnUpdateProfile").click(function () {
      var isError = false;
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      $('#area_message').html("");
      var fd = new FormData();
      fd.append('link_youtube', $('#link_youtube').attr('src'));

      if (!checkErrorImage) {
        var upload_photo = $('#upload-photo')[0].files[0];

        if (upload_photo) {
          fd.append('image_photo', upload_photo);
        }

        if (check_avatar_image === 2) {
          fd.append('check_avatar_image', check_avatar_image);
        }
      }

      fd.append('course', $('#course').val());
      var data = $("#update-profile").serializeArray();
      data.map(function (da) {
        fd.append(da.name, da.value);
      });
      $.ajax({
        type: "POST",
        dataType: 'json',
        url: routeUpdateProfile,
        data: fd,
        contentType: false,
        processData: false,
        success: function success(result) {
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

            if (isError) {
              $("html, body").animate({
                scrollTop: '200px'
              }, "slow");
            }
          } else {
            urlAvatarImage = result.data.image_photo;
            urlLinkYoutubeDefault = result.data.link_youtube;

            if (urlAvatarImage === null) {
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
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-success alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                    ".concat(result.message, "\n                                </div>\n                            </section>\n                        "));
            $('#area_require_zoom_link').html('');
            $('#upload-photo').val(null);
            $("html, body").animate({
              scrollTop: 0
            }, "slow");
          }
        },
        error: function error(result) {
          $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                    ");
        }
      });
    });
  };

  TEACHER_EDIT.changeEmail = function () {
    $('#btnSendMailConfirm').click(function () {
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      $('#area_message').html("");
      var data = $("#formChangeEmail").serializeArray();
      var fd = new FormData();
      fd.append('old_email', $('input[name="old_email"]').val());
      data.map(function (da) {
        fd.append(da.name, da.value);
      });
      $.ajax({
        type: "POST",
        dataType: 'json',
        url: routeChangeEmail,
        data: fd,
        contentType: false,
        processData: false,
        success: function success(result) {
          if (!result.status) {
            $("#loading").removeClass('d-block');
            $('#loading').addClass("d-none");
            $('#error_old_email').html("");
            $('#error_new_email').html("");
            $('#error_new_email_confirmation').html("");
            $("#old_email").removeClass('is-invalid');
            $("#new_email").removeClass('is-invalid');
            $("#new_email_confirmation").removeClass('is-invalid');

            if (result.message.old_email) {
              $('#old_email').addClass('is-invalid');
              $('#error_old_email').html(result.message.old_email[0]);
            }

            if (result.message.new_email) {
              $('#new_email').addClass('is-invalid');
              $('#error_new_email').html(result.message.new_email[0]);
            }

            if (result.message.new_email_confirmation[0]) {
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
        error: function error(result) {
          $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                    ");
        }
      });
    });
  };

  TEACHER_EDIT.changeLinkYoutube = function () {
    $('#btnChangeLinkYoutube').click(function () {
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      $('#area_message').html("");
      var url = $('#input_link_youtube').val();

      if (url == undefined || url == '') {
        $("#loading").removeClass('d-block');
        $('#loading').addClass("d-none");
        $('#input_link_youtube').addClass("is-invalid");
        $('#error-link_youtube').html(messageRequired);
      } else if (url != undefined || url != '') {
        var regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=|\?v=)([^#\&\?]*).*/;
        var match = url.match(regExp);

        if (match && match[2].length == 11) {
          $('#error-link_youtube').html("");
          $("#loading").removeClass('d-block');
          $('#loading').addClass("d-none");
          url = 'https://www.youtube.com/embed/' + match[2];
          $('#link_youtube').attr('src', url);
          $('#input_link_youtube').val("");
          $('#input_link_youtube').removeClass("is-invalid");
          $('#modalChangeLinkYoutube').modal("hide"); // $('#videoObject').attr('src', 'https://www.youtube.com/embed/' + match[2] + '?autoplay=1&enablejsapi=1');
        } else {
          $("#loading").removeClass('d-block');
          $('#loading').addClass("d-none");
          $('#error-link_youtube').html("");
          $('#input_link_youtube').addClass("is-invalid");
          $('#error-link_youtube').html(messageYoutubeLinkInvalid);
        }
      } // var fd = new FormData();
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
  };

  TEACHER_EDIT.removeVideo = function () {
    $('#btnRemoveVideo').click(function () {
      $('#area_message').html("");
      var currentLinkYoutube = $('#link_youtube').attr('src');

      if (currentLinkYoutube !== urlLinkYoutubeDefault) {
        $('#link_youtube').attr('src', urlLinkYoutubeDefault);
      } else if (currentLinkYoutube === urlLinkYoutubeDefault) {
        $('#link_youtube').attr('src', "");
      }
    });
  };

  TEACHER_EDIT.removeAvatarImage = function () {
    $('#remove-image').click(function () {
      $('#area_message').html("");
      $('#error-upload_photo').html("");
      checkErrorImage = false;

      if ($('#upload-photo').val() === "") {
        $("#box").css("background-image", "url(\"".concat(urlAvatarImageDefault, "\")"));
        check_avatar_image = CONST_REMOVE_IMAGE_STATUS;
      } else {
        $("#box").css("background-image", "url(\"".concat(urlAvatarImage || urlAvatarImageDefault, "\")"));
        $('#upload-photo').val(null);
      }
    });
  };

  TEACHER_EDIT.removeValueWhenCancel = function () {
    $('.btnCancel').click(function () {
      removeValue();
    });
    $('#modalChangeLinkYoutube').on('hide.bs.modal', function () {
      removeValue();
    });
  };

  var removeValue = function removeValue() {
    //popup change link
    $('#input_link_youtube').val("");
    $('#input_link_youtube').removeClass("is-invalid");
    $('#error-link_youtube').html(""); //popup change password

    $('#new_password_confirmation').val("");
    $('#new_password_confirmation').removeClass("is-invalid");
    $('#error_new_password_confirmation').html("");
    $('#old_password').val("");
    $('#old_password').removeClass("is-invalid");
    $('#error_old_password').html("");
    $('#new_password').val("");
    $('#new_password').removeClass("is-invalid");
    $('#error_new_password').html(""); //popup change email

    $('#error_old_email').html("");
    $('#new_email').val("");
    $('#new_email').removeClass("is-invalid");
    $('#error_new_email').html("");
    $('#new_email_confirmation').val("");
    $('#new_email_confirmation').removeClass("is-invalid");
    $('#error_new_email_confirmation').html("");
  };
});
$(document).ready(function () {
  TEACHER_EDIT.init();
});

/***/ }),

/***/ 14:
/*!***************************************************!*\
  !*** multi ./resources/js/admin/teachers/edit.js ***!
  \***************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\teachers\edit.js */"./resources/js/admin/teachers/edit.js");


/***/ })

/******/ });