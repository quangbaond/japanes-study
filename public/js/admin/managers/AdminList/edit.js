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
/******/ 	return __webpack_require__(__webpack_require__.s = 49);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/AdminList/edit.js":
/*!*******************************************************!*\
  !*** ./resources/js/admin/managers/AdminList/edit.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var ADMIN_DETAIL = {};
var urlInitialAvatarImage = $("[name=url-avatar-image]").attr('content');
var urlAvatarImage = $("[name=url-avatar-image]").attr('content');
var routeUpdateProfile = $("[name=route-update-profile]").attr('content');
var urlAvatarImageDefault = $("[name=url-avatar-image-default]").attr('content');
var routeChangePassword = $("[name=route-change-password]").attr('content');
var routeResetPassword = $("[name=route-reset-password]").attr('content');
var messageM049 = $("[name=message-M049]").attr('content');
var messageM024 = $("[name=message-M024]").attr('content');
var messageM019 = $("[name=message-M019]").attr('content');
var CONST_UPDATE_IMAGE_STATUS = 1;
var CONST_REMOVE_IMAGE_STATUS = 2;
var check_avatar_image = CONST_UPDATE_IMAGE_STATUS; // 1 update image, 2 remove image

var checkErrorImage = false;
$(function () {
  ADMIN_DETAIL.init = function () {
    //image
    ADMIN_DETAIL.setAvatarImage();
    ADMIN_DETAIL.changeAvatar();
    ADMIN_DETAIL.removeAvatarImage(); //update profile

    ADMIN_DETAIL.updateProfile();
    ADMIN_DETAIL.changePassword();
    ADMIN_DETAIL.resetPassword();
  }; //change avatar image


  ADMIN_DETAIL.changeAvatar = function () {
    $("#upload-photo").change(function () {
      $('#area_message').html("");
      check_avatar_image = CONST_UPDATE_IMAGE_STATUS;
      readURL(this);
    });

    var readURL = function readURL(input) {
      if (input.files && input.files[0]) {
        $('#error_upload_photo').html('');
        var pic_size = input.files[0].size / 1024 / 1024; //get file size (MB)

        var reader = new FileReader();

        reader.onload = function (e) {
          if (validImage("#upload-photo")) {
            if (pic_size >= 5) {
              $('#error_upload_photo').html(messageM019);
              checkErrorImage = true;
            } else {
              $('#error_upload_photo').html('');
              $("#box").css("background-image", "url(" + e.target.result + ")");
              checkErrorImage = false;
            }
          } else {
            $('#error_upload_photo').html(messageM024);
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

  ADMIN_DETAIL.setAvatarImage = function () {
    urlAvatarImage ? $("#box").css("background-image", "url(\"".concat(urlAvatarImage, "\")")) : false;
    urlInitialAvatarImage = urlInitialAvatarImage || urlAvatarImageDefault;
  };

  ADMIN_DETAIL.removeAvatarImage = function () {
    $('#remove-image').click(function () {
      $('#error_upload_photo').html("");
      $('#area_message').html("");
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

  ADMIN_DETAIL.updateProfile = function () {
    //Change password
    $("#btnUpdateProfile").click(function () {
      if (checkInternet()) {
        $("#loading").removeClass('d-none');
        $('#loading').addClass("d-block");
        $('#area_message').html("");
        var formData = new FormData();

        if (!checkErrorImage) {
          var upload_photo = $('#upload-photo')[0].files[0];

          if (upload_photo) {
            formData.append('image_photo', upload_photo);
          }

          if (check_avatar_image === 2) {
            formData.append('check_avatar_image', check_avatar_image);
          }
        }

        formData.append('_token', $("[name=csrf-token]").attr('content'));
        formData.append('user_id', $('#user_id').val());
        formData.append('year', $('#year').val());
        formData.append('month', $('#month').val());
        formData.append('day', $('#day').val());
        formData.append('sex', $('input[name=sex]:checked').val() || "");
        formData.append('nationality', $('select[name="nationality"]').val());
        formData.append('area_code', $('select[name="area_code"]').val());
        formData.append('phone_number', $('#phone_number').val());
        formData.append('nickname', $('#nickname').val());
        formData.append('role', $('input[name=role]:checked').val());
        $.ajax({
          type: "POST",
          dataType: 'json',
          url: routeUpdateProfile,
          data: formData,
          contentType: false,
          processData: false,
          success: function success(result) {
            $("#loading").removeClass('d-block');
            $('#loading').addClass("d-none");

            if (!result.status) {
              $('.error-custom').html('');
              $('body').find('select,input').each(function () {
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
              } // if (result.message.phone_number) {
              //     $("#error_phone_number").html(result.message.phone_number[0]);
              //     $("#phone_number").addClass("is-invalid");
              // }


              $.each(result.message, function (index, val) {
                // console.log('')
                $('#' + index).addClass('is-invalid');
                $("#error_" + index).html(val);
              });
            } else {
              urlAvatarImage = result.data.image_photo;
              urlLinkYoutubeDefault = result.data.link_youtube;
              console.log(urlAvatarImage);

              if (urlAvatarImage === null) {
                // $("#box").css("background-image", "url(" + urlInitialAvatarImage + ")");
                $("#box").css("background-image", "url(" + urlAvatarImageDefault + ")");
              }

              $('#error_upload_photo').html('');
              checkErrorImage = false;
              $("#day").css("border", "");
              $("#month").css("border", "");
              $("#year").css("border", "");
              $('.error-custom').html('');
              $('body').find('select,input').each(function () {
                $(this).removeClass('is-invalid');
              });
              $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-success alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                    ".concat(result.message, "\n                                </div>\n                            </section>\n                        "));
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
      }
    });
  };

  ADMIN_DETAIL.changePassword = function () {
    $('#resetPassword').on('click', function () {
      removeValue();
    });
    $("#btnChangePassword").click(function () {
      if (checkInternet()) {
        $("#loading").removeClass('d-none');
        $('#loading').addClass("d-block");
        $('#area_message').html("");
        $("#error_old_password").html('');
        $("#error_new_password").html('');
        $("#error_new_password_confirmation").html('');
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
              $.each(result.message, function (index, val) {
                // console.log('')
                $('#' + index).addClass('is-invalid');
                $("#error_" + index).html(val);
              });
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
              $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-success alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                    ".concat(result.message, "\n                                </div>\n                            </section>\n                        ")); // $("#modalUpdatePasswordSuccessfully").modal('show');
            }
          },
          error: function error(result) {
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                     ".concat(result.message, "\n                                </div>\n                            </section>\n                    "));
          }
        });
      }
    });
  };

  ADMIN_DETAIL.resetPassword = function () {
    $('#area_message').html('');
    $('#btnResetPasswordAdmin').click(function () {
      $('#modalResetPassword').modal('hide');
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
        success: function success(result) {
          $('#loading').removeClass('d-block');
          $('#loading').addClass('d-none');

          if (!result.status) {} else {
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-success alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                    ".concat(result.message, "\n                                </div>\n                            </section>\n                        "));
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

  var removeValue = function removeValue() {
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
  };

  function checkInternet() {
    var ifConnected = window.navigator.onLine;

    if (ifConnected) {
      $('#area_message').html('');
      return true;
    } else {
      $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                        </section>\n                    ");
      $("html, body").animate({
        scrollTop: 0
      }, "slow");
    }
  }
});
$(document).ready(function () {
  ADMIN_DETAIL.init();
});

/***/ }),

/***/ 49:
/*!*************************************************************!*\
  !*** multi ./resources/js/admin/managers/AdminList/edit.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\AdminList\edit.js */"./resources/js/admin/managers/AdminList/edit.js");


/***/ })

/******/ });