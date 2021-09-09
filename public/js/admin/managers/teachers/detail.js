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
/******/ 	return __webpack_require__(__webpack_require__.s = 15);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/teachers/detail.js":
/*!********************************************************!*\
  !*** ./resources/js/admin/managers/teachers/detail.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_TEACHER_DETAIL = {};
var routeResetPassword = $("[name=route-reset-password]").attr('content');
var routeUpdateProfile = $("[name=route-update-profile]").attr('content');
$(function () {
  MANAGER_TEACHER_DETAIL.init = function () {
    MANAGER_TEACHER_DETAIL.resetPassword();
    MANAGER_TEACHER_DETAIL.updateProfileForTeacher();
  };

  var clearError = function clearError() {
    // nickname
    $("#error_nickname").html("");
    $('#nickname').removeClass('is-invalid'); //birthday

    $("#day").css("border", "");
    $("#month").css("border", "");
    $("#year").css("border", "");
    $("#error_birthday").html(''); //sex

    $("#error_sex").html('');
    $("#sex").removeClass("is-invalid"); //nationality

    $("#error_nationality").html('');
    $("#nationality").removeClass("is-invalid"); //phone_number

    $("#error_phone_number").html('');
    $("#phone_number").removeClass("is-invalid"); //introduction_from_admin

    $("#error_introduction_from_admin").html('');
    $("#introduction_from_admin").removeClass("is-invalid"); //experience

    $("#error_experience").html('');
    $("#experience").removeClass("is-invalid"); //certification

    $("#error_certification").html('');
    $("#certification").removeClass("is-invalid");
  };

  MANAGER_TEACHER_DETAIL.resetPassword = function () {
    $('#btnResetPasswordTeacher').click(function () {
      $('#area_message').html('');
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

  MANAGER_TEACHER_DETAIL.updateProfileForTeacher = function () {
    $('#btnUpdateProfile').click(function () {
      var isError = false;
      $('#loading').removeClass('d-none');
      $('#loading').addClass('d-block');
      $('#area_message').html('');
      var formData = new FormData();
      formData.append('course', $('#course').val());
      var data = $('#update-profile').serializeArray();
      data.map(function (da) {
        formData.append(da.name, da.value);
      });
      $.ajax({
        type: "POST",
        url: routeUpdateProfile,
        data: formData,
        contentType: false,
        processData: false,
        success: function success(result) {
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

            $.each(result.message, function (index, val) {
              if (index == 'nickname') {
                isError = true;
              }

              if (index == 'course') {
                $("#error_" + index).closest('div').addClass('has-error');
              }

              $('#' + index).addClass('is-invalid');
              $("#error_" + index).html(val);
            });

            if (isError) {
              $("html, body").animate({
                scrollTop: '250px'
              }, "slow");
            }
          } else {
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
});
$(document).ready(function () {
  MANAGER_TEACHER_DETAIL.init();
});

/***/ }),

/***/ 15:
/*!**************************************************************!*\
  !*** multi ./resources/js/admin/managers/teachers/detail.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\teachers\detail.js */"./resources/js/admin/managers/teachers/detail.js");


/***/ })

/******/ });