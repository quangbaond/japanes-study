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
/******/ 	return __webpack_require__(__webpack_require__.s = 34);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/addCoin/index.js":
/*!******************************************************!*\
  !*** ./resources/js/admin/students/addCoin/index.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var STUDENT_ADD_COIN = {};
var routeValidationPaymentCoin = $("[name=route-validation-payment-coin]").attr('content');
var routeCheckCancelPremium = $("[name=route-check-cancel-premium]").attr('content');
var routePaymentCoin = $("[name=route-payment-coin]").attr('content');
var M043 = $("[name=m043]").attr('content');
var M046 = $("[name=m046]").attr('content');
var M047 = $("[name=m047]").attr('content');
var M054 = $("[name=M054]").attr('content');
var cardNumber = $("[name=card-number]").attr('content');
var checkRadioRequired = $("[name=check_radio_required]").attr('content');
var id_master_coin = "";
$(function () {
  STUDENT_ADD_COIN.init = function () {
    STUDENT_ADD_COIN.openModalAddCoin();
    STUDENT_ADD_COIN.showHideFormCard();
    STUDENT_ADD_COIN.validationPayment();
  };

  STUDENT_ADD_COIN.openModalAddCoin = function () {
    $('.openModalAddCoin').click(function () {
      // Clear error
      $('#formAddCoin').addClass('d-none');
      $('#card-error').html('');
      $('#formPaymentAddCoinForStudent').find('input').removeClass('is-invalid');
      $('#formPaymentAddCoinForStudent').find('input[type=text], input[type=number]').val('');
      $("input[name=choicePayment][value='1']").prop("checked", true);
      var id = $(this).attr('data-id');
      var coin = $(this).attr('data-coin');
      var bonus_coin = $(this).attr('data-bonus-coin');
      var amount = $(this).attr('data-amount');
      $('#loading').show();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var data = new FormData();
      $.ajax({
        type: "POST",
        url: routeCheckCancelPremium,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $("#loading").hide();

          if (result.status) {
            if (result.data) {
              id_master_coin = id;
              $('#coin-show').html(coin);
              $('#bonus-coin-show').html(bonus_coin);
              $('#amount-coin').html(amount + ' VND');
              $('#modal-add-coin').modal('show');
            } else {
              location.reload();
            }
          } else {
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     ".concat(M054, "\n                                </div>\n                            </section>\n                        "));
          }
        },
        error: function error(_error) {
          $("#loading").hide();
          alert('Error server');
        }
      });
    });
  };

  STUDENT_ADD_COIN.showHideFormCard = function () {
    $('input[name=choicePayment]').click(function () {
      if ($(this).val() == 1) {
        $('#formAddCoin').addClass('d-none');
      } else if ($(this).val() == 2) {
        $('#formAddCoin').removeClass('d-none');
      }
    });
  };

  STUDENT_ADD_COIN.clearErrorChoicePaymentAddCoin = function () {
    $('#area_message_choice_payment').addClass('d-none');
    $('#message_choice_payment').html('');
    $('#radioPrimary2').closest('.form-group').find('label').removeClass('text-danger');
    $('#formPaymentAddCoinForStudent').find('input').removeClass('is-invalid');
    $(".invalid-feedback-custom").html('');
    $('#card-error').html('');
  };

  STUDENT_ADD_COIN.validationPayment = function () {
    $('#btnSubmitPayment').click(function () {
      $('#loading').show(); // // Clear error

      STUDENT_ADD_COIN.clearErrorChoicePaymentAddCoin();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      }); // Data

      var choicePayment = $("input[name=choicePayment]:checked").val();

      if (typeof choicePayment == 'undefined') {
        $('#area_message_choice_payment').removeClass('d-none');
        $('#message_choice_payment').html(checkRadioRequired);
        $('#radioPrimary2').closest('.form-group').find('label').addClass('text-danger');
        $("#loading").hide();
        return false;
      }

      var data = new FormData();
      data.append("id_master_coin", id_master_coin);
      data.append('choice_payment', typeof choicePayment != 'undefined' ? choicePayment : '');
      data.append("name_card", $('input[name=name_card]').val());
      data.append("number_card", $('input[name=number_card]').val());
      data.append("cvc", $('input[name=cvc]').val());
      data.append("date_expiration", $('input[name=date_expiration]').val());
      $.ajax({
        type: "POST",
        url: routeValidationPaymentCoin,
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

            common.bootboxConfirmMultiLanguage(cardNumber + ': **** **** **** ' + result.data.card.last4 + '<br>' + M046, 'small', function (r) {
              if (r) {
                STUDENT_ADD_COIN.submitPayment(data, paymentMethod);
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
          alert('Error server');
        }
      });
    });
  };

  STUDENT_ADD_COIN.submitPayment = function (data, paymentMethod) {
    $('#loading').show();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    data.append("payment_method", paymentMethod);
    $.ajax({
      type: "POST",
      url: routePaymentCoin,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      success: function success(result) {
        $("#loading").hide();

        if (result.status) {
          $('.total-coin').html(result.data.total_coin);
          $('#expiration_date').html(result.data.expiration_date_timezone);
          $('#modal-add-coin').modal('hide');
          $('#area_message_success').removeClass('d-none');
          $('#message_success').html(M043);
          $('#history_use_coin').DataTable().draw(true);
          $('#history_use_coin').removeClass('d-none');
          $('#M070').html('');
          $('html, body').animate({
            scrollTop: $("#history_use_coin").offset().top
          }, 500);
          $('#confirm_deadline').attr('disabled', false);
        } else {
          if (result.message != '') {
            $('#card-error').html("\n                            <div class=\"alert alert-danger alert-dismissible\">\n                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">\xD7</button>\n                                ".concat(M047, "\n                            </div>\n                        "));
            $('#modal-add-coin .modal-body').animate({
              scrollTop: $("#card-error").offset().top
            }, 500);
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
  STUDENT_ADD_COIN.init();
});

/***/ }),

/***/ 34:
/*!************************************************************!*\
  !*** multi ./resources/js/admin/students/addCoin/index.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\addCoin\index.js */"./resources/js/admin/students/addCoin/index.js");


/***/ })

/******/ });