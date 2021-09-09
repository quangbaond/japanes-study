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
/******/ 	return __webpack_require__(__webpack_require__.s = 41);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/student/home.js":
/*!**************************************!*\
  !*** ./resources/js/student/home.js ***!
  \**************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var STUDENT_HOME_PAGE = {};
var routeStudentSearch = $("[name=route-student-search]").attr('content');
var routeBookSchedule = $("[name=route-book-schedule]").attr('content');
var routePushNotificationWhenCanceled = $("[name=route-cancel-schedule]").attr('content');
var routePushNotification = $("[name=route-push-notification]").attr('content');
var teacher_id = $("[name=teacher_id]").attr('content');
var routeRequestCancel = $("[name=route-timeout]").attr('content');
var csrf_token = $("[name=csrf-token]").attr('content');
var checkCloseModalConfirm = false;
$(function () {
  STUDENT_HOME_PAGE.init = function () {
    STUDENT_HOME_PAGE.showPopup();
    STUDENT_HOME_PAGE.showOption();
    STUDENT_HOME_PAGE.redirectCard();
    STUDENT_HOME_PAGE.studentSearch();
    STUDENT_HOME_PAGE.bookSchedule();
    STUDENT_HOME_PAGE.bookingScheduleConfirmation();
    STUDENT_HOME_PAGE.cancelSchedule();
    STUDENT_HOME_PAGE.changeDate();
    STUDENT_HOME_PAGE.clearFormHomeSearch();
    STUDENT_HOME_PAGE.checkRadioHomeSearch();
    STUDENT_HOME_PAGE.cancelScheduleWhenClickOutside();
  };

  STUDENT_HOME_PAGE.checkRadioHomeSearch = function () {
    var btnRadio = $('input[name="btnRadio"]:checked').val();
    var btnRadioStatus = $('input[name="btnRadioStatus"]:checked').val();

    if (btnRadio == 2) {
      $('.showMaterial').removeClass('d-flex');
      $('.showMaterial').removeClass('d-sm-flex');
      $('.showMaterial').addClass('d-none');
      $('.showMaterial').addClass('d-sm-none');
      $('.showSpecify').removeClass('d-none');
      $('.showSpecify').removeClass('d-sm-none');
      $('.showSpecify').addClass('d-flex');
      $('.showSpecify').addClass('d-sm-flex');
    }

    if (btnRadioStatus == 3) {
      $('.showDate').removeClass('d-none');
      $('.showDate').removeClass('d-sm-none');
      $('.showDate').addClass('d-flex');
      $('.showDate').addClass('d-sm-flex');
    }
  };

  STUDENT_HOME_PAGE.showPopup = function () {
    var data = $.cookie('notification_student_sudden_lesson');
    var data1 = $.cookie('notification_require_student_join_lesson');

    if (typeof data === 'undefined' && typeof data1 === 'undefined') {
      $('#modalSuddenTeacher').modal('show');
    } else if (JSON.parse(data1).student_id == $('#user_login').val()) {
      $('#modal_teacher_start_lesson').modal({
        backdrop: 'static'
      });
      $('#modal_teacher_start_lesson').modal('show'); // $('#modalSuddenTeacher').modal('hide');

      setTimeout(function () {
        $('#modal_teacher_start_lesson').modal('hide');
      }, JSON.parse(data1).expires - new Date().getTime());
    }
  };

  STUDENT_HOME_PAGE.showOption = function () {
    $('input:radio[name="btnRadioStatus"]').click(function () {
      if ($('input:radio[id="status-3"]').is(':checked')) {
        $('.showDate').removeClass('d-none');
        $('.showDate').removeClass('d-sm-none');
        $('.showDate').addClass('d-flex');
        $('.showDate').addClass('d-sm-flex');
      } else {
        $('input[name=time_from]').val('');
        $('input[name=time_to]').val('');
        $('.showDate').removeClass('d-flex');
        $('.showDate').removeClass('d-sm-flex');
        $('.showDate').addClass('d-none');
        $('.showDate').addClass('d-sm-none');
      }
    });
  };

  STUDENT_HOME_PAGE.redirectCard = function () {
    $('body').on('click', '.btnRedirect', function () {
      var linkRedirect = $(this).find('input[name=linkRedirect]').val();
      window.location.href = linkRedirect;
    });
  };

  STUDENT_HOME_PAGE.bookingScheduleConfirmation = function () {
    $('#btnBookingScheduleConfirmation').click(function () {
      checkCloseModalConfirm = true;
      $('#modalSuccessfulBooking').modal('hide');
      $('#loading_wait_teacher').show();
      var formData = new FormData();
      formData.append('start_hour', $('#start_hour').val());
      formData.append('start_date', $('#start_date').val());
      formData.append('course_id', $('#course_id').val());
      formData.append('lesson_id', $('#lesson_number').val());
      formData.append('student_id', $('#student_id').val());
      formData.append('coin', '0');
      formData.append('type', '1');
      var dataCookies = {
        "start_hour": $('#start_hour').val(),
        "start_date": $('#start_date').val(),
        "course_id": $('#course_id').val(),
        "lesson_id": $('#lesson_number').val(),
        "student_id": $('#student_id').val(),
        "coin": "0",
        "type": "1",
        "teacher_id": teacher_id
      };
      var expDate = new Date();

      var data_cookies = _objectSpread(_objectSpread({}, dataCookies), {}, {
        'expires': expDate.getTime() + 3 * 60 * 1000
      });

      expDate.setTime(expDate.getTime() + 3 * 60 * 1000); // add 3 minutes

      $.cookie('notification_student_sudden_lesson', JSON.stringify(data_cookies), {
        path: '/',
        expires: expDate
      });
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routeBookSchedule,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $('#loading_wait_teacher').show();
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
      timeoutOfStudent = setTimeout(function () {
        //after 3 minutes don't have response from teacher, student will send the request to change teacher schedule status back 3(free time)
        formData.append('teacher_id', teacher_id);
        $.ajaxSetup({
          headers: {
            "X-CSRF-TOKEN": csrf_token
          }
        });
        $.ajax({
          type: "POST",
          url: routeRequestCancel,
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          success: function success(result) {
            if (!result.status) {
              toastr.error(result.message);
            } else {
              $('#loading_wait_teacher').hide();
              $('#modalCancelRequest').modal('show');
            }
          },
          error: function error(result) {
            toastr.error(result.message);
          }
        });
      }, 60 * 1000 * 3);
    });
  };

  STUDENT_HOME_PAGE.bookSchedule = function () {
    $('#btnBookSchedule').on("click", function () {
      var formData = new FormData();
      formData.append('start_hour', $('#start_hour').val());
      formData.append('start_date', $('#start_date').val());
      formData.append('course_id', $('#course_id').val());
      formData.append('lesson_id', $('#lesson_number').val());
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotification,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          if (!result.status) {
            if (result.data === 'expired') {
              $('#messageTheExpiredPremium').html(result.message);
              $('#modalSuddenTeacher').modal('hide');
              $('#message_expired_premium').show();
              $('#modalTheExpiredPremium').modal('show');
            } else {
              $('#modalSuddenTeacher').modal('hide');
              $('#modalFailedBooking').modal('show');
            }
          } else {
            $('#modalSuddenTeacher').modal('hide');
            $('#modalSuccessfulBooking').modal('show');
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_HOME_PAGE.cancelSchedule = function () {
    $('#btnCancelSchedule').click(function () {
      var formData = new FormData();
      checkCloseModalConfirm = true;
      formData.append('start_hour', $('#start_hour').val());
      formData.append('start_date', $('#start_date').val());
      formData.append('course_id', $('#course_id').val());
      formData.append('lesson_id', $('#lesson_number').val());
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotificationWhenCanceled,
        data: formData,
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_HOME_PAGE.cancelScheduleWhenClickOutside = function () {
    $('#modalSuccessfulBooking').on('hide.bs.modal', function () {
      if (checkCloseModalConfirm) {
        checkCloseModalConfirm = false;
        return true;
      }

      var formData = new FormData();
      formData.append('start_hour', $('#start_hour').val());
      formData.append('start_date', $('#start_date').val());
      formData.append('course_id', $('#course_id').val());
      formData.append('lesson_id', $('#lesson_number').val());
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotificationWhenCanceled,
        data: formData,
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_HOME_PAGE.clearFormHomeSearch = function () {
    $('#clearFormHomeSearch').click(function () {
      $('#formSearchHome').find('input:not([name=_token])').val('');
      $('#formSearchHome').find('.select2').val(null).trigger('change');
      $('#formSearchHome').find('input[type=radio]').prop('checked', false);
      $('#formSearchHome').submit();
    });
  };

  STUDENT_HOME_PAGE.studentSearch = function () {
    $('#btnHomeSearch').click(function () {
      // Clear error
      $(".invalid-feedback-custom").html('');
      $('.highlight-error').removeClass('is-invalid'); // Get data form search teacher

      var data = $("#formSearchHome").serialize(); //get date

      var date_time = "";
      $('#formSearchHome .btn-warning').each(function (key, val) {
        var year = $(this).attr('year');
        var day = $(this).text().trim();
        $date_year = (year + "/" + day).substring(0, 10);
        date_time = date_time + "|" + $date_year;
      });
      $('#date_time').val(date_time); // Ajax

      $.ajax({
        type: "POST",
        url: routeStudentSearch,
        data: data,
        success: function success(result) {
          if (result.status) {
            $('#formSearchHome').submit();
          } else {
            $.each(result.message, function (key, value) {
              if (typeof value !== "undefined") {
                $('#' + key).addClass('is-invalid');
                $('#' + key).closest('.form-group').find('span').html(value[0]);
              }
            });
          }
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };

  STUDENT_HOME_PAGE.changeDate = function () {
    var _loop = function _loop(i) {
      $("#date".concat(i)).click(function () {
        var date = $("#date".concat(i)).text();

        if ($("#date".concat(i)).hasClass('btn-outline-warning border-dark')) {
          $("#date".concat(i)).removeClass('btn-outline-warning border-dark').addClass("btn-warning");
        } else {
          $("#date".concat(i)).removeClass('btn-warning').addClass("btn-outline-warning border-dark");
        } // if ($(`#date${i}`).hasClass('btn-info')) {
        //     $(`#date${i}`).removeClass('btn-info').addClass('btn-secondary');
        // }else if ($(`#date${i}`).hasClass('btn-danger')) {
        //     $(`#date${i}`).removeClass('btn-danger').addClass('btn-secondary');
        // }else if ($(`#date${i}`).hasClass('btn-primary')) {
        //     $(`#date${i}`).removeClass('btn-primary').addClass('btn-secondary');
        // }else if ($(`#date${i}`).hasClass('btn-secondary')) {
        //     var class_check = $(`#date${i}`).attr('checkclass')
        //     $(`#date${i}`).removeClass('btn-secondary').addClass("btn-warning");
        // }

      });
    };

    for (var i = 1; i <= 7; i++) {
      _loop(i);
    }
  };

  STUDENT_HOME_PAGE.readURL = function (input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();

      reader.onload = function (e) {
        $('#image_profile').attr('src', e.target.result);
      };

      reader.readAsDataURL(input.files[0]); // convert to base64 string
    }
  };

  $("#upload-photo").change(function () {
    STUDENT_HOME_PAGE.readURL(this);
  });
});
$(document).ready(function () {
  STUDENT_HOME_PAGE.init();
});

/***/ }),

/***/ 41:
/*!********************************************!*\
  !*** multi ./resources/js/student/home.js ***!
  \********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\student\home.js */"./resources/js/student/home.js");


/***/ })

/******/ });