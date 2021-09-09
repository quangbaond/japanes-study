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
/******/ 	return __webpack_require__(__webpack_require__.s = 37);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/myPage.js":
/*!***********************************************!*\
  !*** ./resources/js/admin/students/myPage.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MY_PAGE_STUDENT = {};
var routeCheckCancelTrialPayment = $("[name=check-cancel-trial-payment]").attr('content');
var routeCheckCancelPremiumPayment = $("[name=check-cancel-premium-plan]").attr('content');
var M050 = $("[name=M050]").attr('content');
var M051 = $("[name=M051]").attr('content');
var M052 = $("[name=M052]").attr('content');
$(function () {
  MY_PAGE_STUDENT.init = function () {
    MY_PAGE_STUDENT.cancelTrialPayment();
    MY_PAGE_STUDENT.clickOpenModalTrial();
    MY_PAGE_STUDENT.clickOpenModalPremium();
    MY_PAGE_STUDENT.btnSubmitTrial();
    MY_PAGE_STUDENT.btnSubmitPremium();
    MY_PAGE_STUDENT.cancelPremiumPayment();
  };

  MY_PAGE_STUDENT.clickOpenModalTrial = function () {
    $('#buttonOpenModalTrial').click(function () {
      $('#error-message-cancel').html('');
      $('#modalTrial').modal('show');
    });
  };

  MY_PAGE_STUDENT.clickOpenModalPremium = function () {
    $('#buttonOpenModalPremium').click(function () {
      $('#error-message-cancel-premium').html('');
      $('#modalPremium').modal('show');
    });
  };

  MY_PAGE_STUDENT.btnSubmitTrial = function () {
    $('#btnSubmitTrial').click(function () {
      $('#formCancelTrialPayment').submit();
    });
  };

  MY_PAGE_STUDENT.btnSubmitPremium = function () {
    $('#btnSubmitPremium').click(function () {
      $('#formCancelPremiumPayment').submit();
    });
  };

  MY_PAGE_STUDENT.cancelTrialPayment = function () {
    $('#cancelTrialPayment').click(function () {
      common.bootboxConfirmMultiLanguage(M051, 'small', function (r) {
        if (r) {
          $('#loading').show();
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          var data = new FormData();
          data.append('trial_end_date', $('[name=trial_end_date]').val());
          $.ajax({
            type: "POST",
            url: routeCheckCancelTrialPayment,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function success(result) {
              $("#loading").hide();

              if (result.status) {
                // Success
                if (result.data.length > 0) {
                  $('#list_booking tbody').html('');
                  var total_coin_refund = 0;
                  $.each(result.data, function (index, value) {
                    if (value.status_coin_refund == 'NO') {
                      $('#list_booking tbody').append("\n                                                 <tr style=\"background-color: #FDD692\">\n                                                      <td>".concat(value.start_date, "</td>\n                                                      <td>").concat(value.start_hour, "</td>\n                                                      <td>").concat(value.teacher_id, "</td>\n                                                      <td>").concat(value.nickname_teacher, "</td>\n                                                      <td>").concat(value.email_teacher, "</td>\n                                                      <td>").concat(value.coin, "</td>\n                                                      <td>0</td>\n                                                </tr>\n                                            "));
                    } else {
                      $('#list_booking tbody').append("\n                                                 <tr>\n                                                      <td>".concat(value.start_date, "</td>\n                                                      <td>").concat(value.start_hour, "</td>\n                                                      <td>").concat(value.teacher_id, "</td>\n                                                      <td>").concat(value.nickname_teacher, "</td>\n                                                      <td>").concat(value.email_teacher, "</td>\n                                                      <td>").concat(value.coin, "</td>\n                                                      <td>").concat(value.coin, "</td>\n                                                </tr>\n                                            "));
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
                $('#error-message-cancel').html("\n                                    <div class=\"alert alert-danger alert-dismissible\">\n                                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">\xD7</button>\n                                        <h6><i class=\"icon fas fa-ban\"></i> ".concat(M050, "</h6>\n                                    </div>\n                                "));
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

  MY_PAGE_STUDENT.cancelPremiumPayment = function () {
    $('#cancelPremiumPayment').click(function () {
      common.bootboxConfirmMultiLanguage(M052, 'small', function (r) {
        if (r) {
          $('#loading').show();
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
          });
          var data = new FormData();
          data.append('premium_end_date', $('[name=premium_end_date]').val());
          $.ajax({
            type: "POST",
            url: routeCheckCancelPremiumPayment,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            success: function success(result) {
              $("#loading").hide();

              if (result.status) {
                // Success
                if (result.data.length > 0) {
                  $('#list_booking_premium tbody').html('');
                  var total_coin_refund = 0;
                  $.each(result.data, function (index, value) {
                    $('#list_booking_premium tbody').append("\n                                             <tr>\n                                                  <td>".concat(value.start_date, "</td>\n                                                  <td>").concat(value.start_hour, "</td>\n                                                  <td>").concat(value.teacher_id, "</td>\n                                                  <td>").concat(value.nickname_teacher, "</td>\n                                                  <td>").concat(value.email_teacher, "</td>\n                                                  <td>").concat(value.coin, "</td>\n                                            </tr>\n                                        "));
                    total_coin_refund = total_coin_refund + parseInt(value.coin);
                  });
                  $('#total_coin_refund_premium').html(total_coin_refund);
                  $('#modalCancelBookingPremium').modal('show');
                } else {
                  $('#formCancelPremiumPayment').submit();
                }
              } else {
                // Error
                $('#error-message-cancel-premium').html("\n                                    <div class=\"alert alert-danger alert-dismissible\">\n                                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">\xD7</button>\n                                        <h6><i class=\"icon fas fa-ban\"></i> ".concat(M050, "</h6>\n                                    </div>\n                                "));
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
});
$(document).ready(function () {
  MY_PAGE_STUDENT.init();
});

/***/ }),

/***/ 37:
/*!*****************************************************!*\
  !*** multi ./resources/js/admin/students/myPage.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\myPage.js */"./resources/js/admin/students/myPage.js");


/***/ })

/******/ });