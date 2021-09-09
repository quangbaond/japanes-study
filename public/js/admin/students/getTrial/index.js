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
/******/ 	return __webpack_require__(__webpack_require__.s = 27);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/getTrial/index.js":
/*!*******************************************************!*\
  !*** ./resources/js/admin/students/getTrial/index.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var STUDENT_PAYMENT_TRIAL = {};
var inputErrorRadioRequired = $("[name=input-error-radio-required]").attr('content');
var routeStudentPaymentTrial = $("[name=route-student-payment-trial]").attr('content');
var routeStudentPaymentValidation = $("[name=route-student-payment-validation]").attr('content');
var routeShowDateDeadline = $("[name=route-show-date-deadline]").attr('content');
var M046 = $("[name=m046]").attr('content');
var M047 = $("[name=m047]").attr('content');
var date_deadline = '';
$(function () {
  /*
     Function init js
  */
  STUDENT_PAYMENT_TRIAL.init = function () {
    STUDENT_PAYMENT_TRIAL.goToStep2();
    STUDENT_PAYMENT_TRIAL.checkRemember();
    STUDENT_PAYMENT_TRIAL.handleGoToStep2();
    STUDENT_PAYMENT_TRIAL.handleBackStep1();
    STUDENT_PAYMENT_TRIAL.showFormCredit();
    STUDENT_PAYMENT_TRIAL.closeFormCredit();
    STUDENT_PAYMENT_TRIAL.validationPaymentCredit();
  };

  STUDENT_PAYMENT_TRIAL.goToStep2 = function () {
    $('#goToStep2').click(function () {
      $('#error_plans').removeClass('text-danger');
      $('.error_plan_label').removeClass('text-danger');
      var plans_value = $("input[name=plans]:checked").val();

      if (typeof plans_value == 'undefined') {
        $('#error_plans').addClass('text-danger');
        $('.error_plan_label').addClass('text-danger');
      } else {
        $("#loading").show();
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        var data = new FormData();
        $.ajax({
          type: "POST",
          url: routeShowDateDeadline,
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          success: function success(result) {
            $("#loading").hide();

            if (result.status) {
              date_deadline = result.data;
              var label_plan = $("input[name=plans]:checked").attr('data-value');
              var plan_cost = $("input[name=plans]:checked").attr('data-cost');
              var plan_interval = $("input[name=plans]:checked").attr('data-interval');
              var plan_interval_count = $("input[name=plans]:checked").attr('data-interval-count');
              $('#name_plan_choice').html(label_plan);
              $('#cost_plan').html(plan_cost);
              $('#interval_count').html(plan_interval_count);
              $('#interval').html(plan_interval);
              $('#date_deadline').html(result.data);
              $('#modal-confirm-plans').modal('show');
            } else {
              alert("Error server");
            }
          },
          error: function error(_error) {
            $("#loading").hide();
            alert("Error server");
          }
        });
      }
    });
  };

  STUDENT_PAYMENT_TRIAL.checkRemember = function () {
    $('#remember').click(function () {
      if ($(this).is(":checked")) {
        $('#btnGoToStep2').prop('disabled', false);
      } else {
        $('#btnGoToStep2').prop('disabled', true);
      }
    });
  };

  STUDENT_PAYMENT_TRIAL.handleGoToStep2 = function () {
    $('#btnGoToStep2').click(function () {
      $('#modal-confirm-plans').modal('hide');
      $('#step1').addClass('d-none');
      $('#step2').removeClass('d-none');
      var plan_name = $("input[name=plans]:checked").attr('data-value');
      var plan_cost = $("input[name=plans]:checked").attr('data-cost');
      var plan_interval = $("input[name=plans]:checked").attr('data-interval');
      var plan_interval_count = $("input[name=plans]:checked").attr('data-interval-count');
      $('#plan_name').html(plan_name);
      $('#plan_cost').html(plan_cost + ' VND');
      $('#plan_interval').html(plan_interval_count + ' ' + plan_interval);
    });
  };

  STUDENT_PAYMENT_TRIAL.handleBackStep1 = function () {
    $('#btnBackStep1').click(function () {
      $('#step1').removeClass('d-none');
      $('#step2').addClass('d-none');
    });
  };

  STUDENT_PAYMENT_TRIAL.showFormCredit = function () {
    $('#showFormCredit').click(function () {
      $('#formCredit').removeClass('d-none');
    });
  };

  STUDENT_PAYMENT_TRIAL.closeFormCredit = function () {
    $('#btnCloseFormCredit').click(function () {
      $('#formCredit').addClass('d-none');
    });
  };
  /*
      Function clear all error validation in form
  */


  STUDENT_PAYMENT_TRIAL.clearError = function () {
    $('#error_section').html('');
    $('body').find('input').removeClass('is-invalid');
    $(".invalid-feedback-custom").html('');
    $('#card-error').html('');
  };

  STUDENT_PAYMENT_TRIAL.validationPaymentCredit = function () {
    $('#btnSubmitCredit').click(function () {
      $("#loading").show(); // Function clear error

      STUDENT_PAYMENT_TRIAL.clearError();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      }); // Data

      var data = new FormData();
      data.append("name_card", $('input[name=name_card]').val());
      data.append("number_card", $('input[name=number_card]').val());
      data.append("cvc", $('input[name=cvc]').val());
      data.append("date_expiration", $('input[name=date_expiration]').val());
      data.append("plan_id", $("input[name=plans]:checked").val());
      data.append('choice_payment', '2');
      data.append('date_deadline', date_deadline);
      $.ajax({
        type: "POST",
        url: routeStudentPaymentValidation,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $("#loading").hide();

          if (result.status) {
            var paymentMethod = '';

            if (result.data != null) {
              paymentMethod = result.data.id;
            }

            common.bootboxConfirmMultiLanguage(M046, 'small', function (r) {
              if (r) {
                STUDENT_PAYMENT_TRIAL.paymentCredit(data, paymentMethod);
              }
            });
          } else {
            $.each(result.message, function (key, value) {
              if (typeof value !== "undefined") {
                $('#' + key).addClass('is-invalid');
                $('#' + key).closest('.form-group').find('strong').html(value);
              }
            });
          }
        },
        error: function error(_error) {
          $("#loading").hide();
          alert('Error server');
        }
      });
    });
  };
  /*
      Button payment subscriptions
  */


  STUDENT_PAYMENT_TRIAL.paymentCredit = function (data, paymentMethod) {
    $("#loading").show();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }); // Data

    data.append("payment_method", paymentMethod);
    $.ajax({
      type: "POST",
      url: routeStudentPaymentTrial,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      success: function success(result) {
        $("#loading").hide();

        if (result.status) {
          $('#step2').addClass('d-none');
          $('#step3').removeClass('d-none');
        } else {
          if (result.message != '') {
            $('#card-error').html("\n                            <div class=\"alert alert-danger alert-dismissible\">\n                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">\xD7</button>\n                                ".concat(M047, "\n                            </div>\n                        "));
          }
        }
      },
      error: function error(_error) {
        $("#loading").hide();
        alert('Error server');
      }
    });
  };
});
$(document).ready(function () {
  STUDENT_PAYMENT_TRIAL.init();
});

/***/ }),

/***/ 27:
/*!*************************************************************!*\
  !*** multi ./resources/js/admin/students/getTrial/index.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\getTrial\index.js */"./resources/js/admin/students/getTrial/index.js");


/***/ })

/******/ });