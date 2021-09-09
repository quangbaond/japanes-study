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
/******/ 	return __webpack_require__(__webpack_require__.s = 23);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/register/index.js":
/*!*******************************************************!*\
  !*** ./resources/js/admin/students/register/index.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var STUDENT_REGISTER = {};
var routeRegisterStudentValidation = $("[name=route-register-student-validation]").attr('content');
var inputErrorCommon = $("[name=input-error-common]").attr('content');
var inputErrorCheckRequired = $("[name=input-error-check-required]").attr('content');
var routeSendMailToUpdateAuth = $("[name=route-send-mail-to-update-auth]").attr('content');
var routeRegisterStudentSave = $("[name=route-register-student-save]").attr('content');
var token = $("[name=csrf-token]").attr('content');
var imageSize = $("[name=image-size]").attr('content');
var imageFormat = $("[name=image-format]").attr('content');
var inputErrorRadioRequired = $("[name=input-error-radio-required]").attr('content');
var routeRegisterStudentPayment = $("[name=route-register-student-payment]").attr('content');
var routeValidationStudentPayment = $("[name=route-validation-student-payment]").attr('content');
var routeShowDateDeadline = $("[name=route-show-date-deadline]").attr('content');
var M046 = $("[name=m046]").attr('content');
var M047 = $("[name=m047]").attr('content');
var date_deadline = '';
$(function () {
  /*
     Function init js
  */
  STUDENT_REGISTER.init = function () {
    STUDENT_REGISTER.handleChoiceImage();
    STUDENT_REGISTER.clickClearImage();
    STUDENT_REGISTER.validationRegisterStudent();
    STUDENT_REGISTER.backStep1();
    STUDENT_REGISTER.registerStudentStep2();
    STUDENT_REGISTER.showPopupStep2();
    STUDENT_REGISTER.checkRemember();
    STUDENT_REGISTER.handleGoToStep3();
    STUDENT_REGISTER.showFormCredit();
    STUDENT_REGISTER.closeFormCredit();
    STUDENT_REGISTER.handleBackStep2();
    STUDENT_REGISTER.registerStudentStep3();
    STUDENT_REGISTER.validationPaymentCredit();
  };
  /*
      Function back screen step 1
  */


  STUDENT_REGISTER.backStep1 = function () {
    $('#btnBackStep1').click(function () {
      $('#step1').removeClass('d-none');
      $('#step2').addClass('d-none');
      $('#account').addClass('active');
      $('#personal').removeClass('active');
    });
  };
  /*
      Function handle choice image avatar
  */


  STUDENT_REGISTER.handleChoiceImage = function () {
    // Choice image
    $("#choice_image").click(function () {
      $('#image_photo').trigger('click');
    }); // Change image

    $("#image_photo").change(function () {
      if (this.files && this.files[0]) {
        var pic_size = $('#image_photo')[0].files[0].size / 1024 / 1024; //get file size (MB)

        var reader = new FileReader();

        reader.onload = function (e) {
          if (validImage("#image_photo")) {
            if (pic_size >= 5) {
              STUDENT_REGISTER.addMessageErrorCommon(inputErrorCommon);
              $('#error_image').html(imageSize);
              $("#image_photo").val(null);
            } else {
              $('#image').attr('src', e.target.result);
              $('#clearImage').removeClass('d-none');
              $('#error_image').html('');
              $('#error_section').html('');
            }
          } else {
            STUDENT_REGISTER.addMessageErrorCommon(inputErrorCommon);
            $('#error_image').html(imageFormat);
            $("#image_photo").val(null);
          }
        };

        reader.readAsDataURL(this.files[0]);
      }
    }); // Valid Image

    function validImage(file_id) {
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
    }
  };
  /*
      Function delete image when choice avatar
  */


  STUDENT_REGISTER.clickClearImage = function () {
    $('#clearImage').click(function () {
      $('#image').attr('src', '/images/avatar_2.png');
      $("#image_photo").val(null);
      $(this).addClass('d-none');
      $('#error_image').html('');
      $('#error_section').html('');
    });
  };
  /*
      Function clear all error validation in form
  */


  STUDENT_REGISTER.clearError = function () {
    $('#error_section').html('');
    $('body').find('input').removeClass('is-invalid');
    $(".invalid-feedback-custom").html('');
    $('#card-error').html('');
  };
  /*
      Get data form
      Return formData
  */


  STUDENT_REGISTER.getDataForm = function () {
    var data = new FormData();
    data.append("email", $('#email').val());
    data.append("email_confirm", $('#email_confirm').val());
    data.append("password", $('#password').val());
    data.append("password_confirm", $('#password_confirm').val());
    data.append("nickname", $('#nickname').val());
    data.append("year", $('select#year option:selected').val());
    data.append("month", $('select#month option:selected').val());
    data.append("day", $('select#day option:selected').val());
    data.append("sex", typeof $("input[name=sex]:checked").val() == 'undefined' ? "" : $("input[name=sex]:checked").val());
    data.append("area_code", $('select[name=area_code] option:selected').val());
    data.append("phone_number", $('#phone_number').val());
    data.append("nationality", $('select[name=nationality] option:selected').val());
    data.append("image_photo", typeof $("#image_photo")[0].files[0] != 'undefined' ? $("#image_photo")[0].files[0] : '');
    data.append("birthday", $('select#year option:selected').val() + '-' + $('select#month option:selected').val() + '-' + $('select#day option:selected').val());
    return data;
  };
  /*
      Function add message error common
      @Return boolean
  */


  STUDENT_REGISTER.addMessageErrorCommon = function (message) {
    $('#error_section').html("\n            <div class=\"alert alert-danger alert-dismissible\">\n                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                <i class=\"icon fa fa-ban\"></i>\n                <span id=\"error_mes\">".concat(message, "</span>\n            </div>\n        "));
  };
  /*
      Handle validation register student
      @Return boolean
  */


  STUDENT_REGISTER.validationRegisterStudent = function () {
    $('#btnGoToStep2').click(function (e) {
      // Function clear error
      STUDENT_REGISTER.clearError();
      $('#year').removeClass('error-brithday');
      $('#month').removeClass('error-brithday');
      $('#day').removeClass('error-brithday'); // Ajax validation

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      e.preventDefault();
      var data = STUDENT_REGISTER.getDataForm();
      $("#loading").show();
      $.ajax({
        type: "POST",
        url: routeRegisterStudentValidation,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $("#loading").hide();

          if (result.status) {
            // Function check terms
            if (!$('#agreeTerms').prop("checked")) {
              $('#show-error-agree').html(inputErrorCheckRequired);
              STUDENT_REGISTER.addMessageErrorCommon(inputErrorCommon);
              return "false";
            } // Change active process


            $('#account').removeClass('active');
            $('#personal').addClass('active'); // Go to step 2

            $('#step1').addClass('d-none');
            $('#step2').removeClass('d-none');
          } else {
            // Show validation input form
            if (result.data == 'validation') {
              STUDENT_REGISTER.addMessageErrorCommon(inputErrorCommon);
              $.each(result.message, function (key, value) {
                if (typeof value !== "undefined") {
                  $('#' + key).addClass('is-invalid');
                  $('#' + key).closest('.form-group').find('strong').html(value[0]);
                }

                if (typeof key !== "undefined" && (key == 'day' || key == 'month' || key == 'year' || key == 'birthday')) {
                  $('#year').addClass('error-brithday');
                  $('#month').addClass('error-brithday');
                  $('#day').addClass('error-brithday');
                }
              });
            } // Case: email is isset (users.auth == 1 and users.deleted_at == null)


            if (result.data == 'email_isset') {
              STUDENT_REGISTER.addMessageErrorCommon(result.message);
            } // Case: email not authentication (users.auth = 0)


            if (result.data == 'email_not_auth') {
              if (!$('#agreeTerms').prop("checked")) {
                $('#show-error-agree').html(inputErrorCheckRequired);
                return "false";
              }

              common.bootboxConfirmMultiLanguage(result.message, 'large', function (r) {
                if (r) {
                  // Send mail auth user
                  STUDENT_REGISTER.sendMailUpdateAuthUser();
                }
              });
            } // Case: email is deleted (users.deleted_at != null)


            if (result.data == 'email_delete') {
              STUDENT_REGISTER.addMessageErrorCommon(result.message);
            }
          }
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };
  /*
      Send mail to update users.auth for student
  */


  STUDENT_REGISTER.sendMailUpdateAuthUser = function () {
    $("#loading").show();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    var data = new FormData();
    data.append("email", $('#email').val());
    $.ajax({
      type: "POST",
      url: routeSendMailToUpdateAuth,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      success: function success(result) {
        $("#loading").hide();
        $('#step1').addClass('d-none');
        $('#step4').removeClass('d-none');
        $('#confirm').addClass('active');
        $('#account').removeClass('active');
      },
      error: function error(_error) {
        $("#loading").hide();
        alert("Error server");
      }
    });
  };
  /*
      Register student (insert student to DB)
      Return formData
  */


  STUDENT_REGISTER.registerStudentStep2 = function () {
    $('#btnRegisterStudent').click(function () {
      $("#loading").show();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var data = STUDENT_REGISTER.getDataForm();
      $.ajax({
        type: "POST",
        url: routeRegisterStudentSave,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $("#loading").hide();

          if (result.status) {
            // Go to step 4
            $('#step2').addClass('d-none');
            $('#step4').removeClass('d-none');
            $('#confirm').addClass('active');
            $('#personal').removeClass('active');
          } else {
            console.log("error");
          }
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };
  /*
      Register student (insert student to DB)
      Return formData
  */


  STUDENT_REGISTER.registerStudentStep3 = function () {
    $('#btnRegisterStep3').click(function () {
      $("#loading").show();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var data = STUDENT_REGISTER.getDataForm();
      $.ajax({
        type: "POST",
        url: routeRegisterStudentSave,
        data: data,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $("#loading").hide();

          if (result.status) {
            // Go to step 4
            $('#step3').addClass('d-none');
            $('#step4').removeClass('d-none');
            $('#confirm').addClass('active');
            $('#payment').removeClass('active');
          } else {
            console.log("error");
          }
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };

  STUDENT_REGISTER.showPopupStep2 = function () {
    $('#showPopupStep2').click(function () {
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
        var d = new Date();
        var dateFormat = d.getFullYear() + "/" + (d.getMonth() + 1) + "/" + d.getDate();
        data.append("date", dateFormat);
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

  STUDENT_REGISTER.checkRemember = function () {
    $('#remember').click(function () {
      if ($(this).is(":checked")) {
        $('#btnGoToStep3').prop('disabled', false);
      } else {
        $('#btnGoToStep3').prop('disabled', true);
      }
    });
  };

  STUDENT_REGISTER.handleGoToStep3 = function () {
    $('#btnGoToStep3').click(function () {
      $('#modal-confirm-plans').modal('hide');
      $('#step2').addClass('d-none');
      $('#step3').removeClass('d-none');
      $('#personal').removeClass('active');
      $('#payment').addClass('active');
      $('#step3_email_from').html($('#email').val());

      if ($('#phone_number').val() != '') {
        $('#step3_area_code').html('(' + $('select[name=area_code] option:selected').val() + ')');
        $('#step3_phone').html($('#phone_number').val());
      } else {
        $('#step3_area_code').html('');
        $('#step3_phone').html('');
      }

      var plan_name = $("input[name=plans]:checked").attr('data-value');
      var plan_cost = $("input[name=plans]:checked").attr('data-cost');
      var plan_interval = $("input[name=plans]:checked").attr('data-interval');
      var plan_interval_count = $("input[name=plans]:checked").attr('data-interval-count');
      $('#plan_name').html(plan_name);
      $('#plan_cost').html(plan_cost + ' VND');
      $('#plan_interval').html(plan_interval_count + ' ' + plan_interval);
    });
  };

  STUDENT_REGISTER.showFormCredit = function () {
    $('#showFormCredit').click(function () {
      $('#formCredit').removeClass('d-none');
    });
  };

  STUDENT_REGISTER.closeFormCredit = function () {
    $('#btnCloseFormCredit').click(function () {
      $('#formCredit').addClass('d-none');
    });
  };

  STUDENT_REGISTER.handleBackStep2 = function () {
    $('#btnBackStep2').click(function () {
      $('#step2').removeClass('d-none');
      $('#step3').addClass('d-none');
      $('#personal').addClass('active');
      $('#payment').removeClass('active');
    });
  };

  STUDENT_REGISTER.validationPaymentCredit = function () {
    $('#btnSubmitCredit').click(function () {
      $("#loading").show(); // Function clear error

      STUDENT_REGISTER.clearError();
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      }); // Data

      var data = STUDENT_REGISTER.getDataForm();
      data.append("name_card", $('input[name=name_card]').val());
      data.append("number_card", $('input[name=number_card]').val());
      data.append("cvc", $('input[name=cvc]').val());
      data.append("date_expiration", $('input[name=date_expiration]').val());
      data.append("plan_id", $("input[name=plans]:checked").val());
      data.append("date_deadline", date_deadline);
      $.ajax({
        type: "POST",
        url: routeValidationStudentPayment,
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
                STUDENT_REGISTER.paymentCredit(data, paymentMethod);
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
  /*
      Button payment subscriptions
  */


  STUDENT_REGISTER.paymentCredit = function (data, paymentMethod) {
    $("#loading").show();
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    }); // Data

    data.append("payment_method", paymentMethod);
    $.ajax({
      type: "POST",
      url: routeRegisterStudentPayment,
      data: data,
      cache: false,
      contentType: false,
      processData: false,
      success: function success(result) {
        $("#loading").hide();

        if (result.status) {
          $('#step3').addClass('d-none');
          $('#step4').removeClass('d-none');
          $('#payment').removeClass('active');
          $('#confirm').addClass('active');
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
  STUDENT_REGISTER.init();
});

/***/ }),

/***/ 23:
/*!*************************************************************!*\
  !*** multi ./resources/js/admin/students/register/index.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\register\index.js */"./resources/js/admin/students/register/index.js");


/***/ })

/******/ });