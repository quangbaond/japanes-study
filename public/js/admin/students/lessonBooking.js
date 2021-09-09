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
/******/ 	return __webpack_require__(__webpack_require__.s = 33);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/lessonBooking.js":
/*!******************************************************!*\
  !*** ./resources/js/admin/students/lessonBooking.js ***!
  \******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var STUDENT_LESSON_BOOKING = {};
var routeBookSchedule;
var routePushNotificationWhenCanceled;
var routePushNotification;
var csrf_token = $("[name=csrf-token]").attr('content');
var message_no_data = $("[name=message-no-data]").attr('content');
var the_number_of_record = $("[name=the-number-of-record]").attr('content');
var routeCheckTimeRemove = $("[name=route-check-time]").attr('content');
var routeGetCourseCanTeach = $('[name=route-get-course-can-teach]').attr('content');
var routeUpdateLessonBooked = $('[name=route-update-lesson-booked]').attr('content');
var textDocument = $('[name=text-document]').attr('content');
var teacher_id;
var checkCloseModalConfirm = false;
var idBooking;
var dataLesson;
var teacher_schedule_id;
$(function () {
  var Arr_BOOKING = [];

  STUDENT_LESSON_BOOKING.init = function () {
    STUDENT_LESSON_BOOKING.changeUserTimepicker();
    STUDENT_LESSON_BOOKING.limitContent();
    STUDENT_LESSON_BOOKING.removeBookingList();
    STUDENT_LESSON_BOOKING.bookingScheduleConfirmation();
    STUDENT_LESSON_BOOKING.cancelSchedule();
    STUDENT_LESSON_BOOKING.startLesson();
    STUDENT_LESSON_BOOKING.cancelScheduleWhenClickOutside();
    STUDENT_LESSON_BOOKING.showVideo();
    STUDENT_LESSON_BOOKING.changeLesson();
    STUDENT_LESSON_BOOKING.changeLessonBooked();
  };

  STUDENT_LESSON_BOOKING.showVideo = function () {
    $('body').on('click', '.btnShowVideo', function () {
      var videoLink = $(this).attr('data-video_link');
      var html = "<video id=\"clip\" controls preload=auto playsinline muted autoplay class=\"intro-video\" data-setup=\"{}\">\n                        <source src=\"".concat(videoLink, "\" type='video/mp4'/>\n                    </video>");
      $('#modalShowVideo').find('.modal-body').html(html);
      $('#modalShowVideo').modal('show');
    });
  };

  STUDENT_LESSON_BOOKING.changeLessonBooked = function () {
    $('body').on('click', '#btnUpdateLesson', function () {
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      var lesson_id = $('#lesson_id_select').val();
      var lesson_name = $('#lesson_id_select').find(":selected").text();
      var course_name = $('#course_id_select').find(":selected").text();
      var formData = new FormData();
      formData.append('lesson_id', lesson_id);
      formData.append('teacher_schedule_id', teacher_schedule_id);
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routeUpdateLessonBooked,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $('#loading').removeClass("d-block");
          $("#loading").addClass('d-none');

          if (!result.status) {
            toastr.error(result.message);
          } else {
            var row = $('body').find("[data-teacher_schedule_id=".concat(teacher_schedule_id, "]"));
            row.attr('data-lesson_id', result.data.lesson_id);
            row.attr('data-course_id', result.data.course_id);
            row.closest('tr').find('#course_name').html(result.data.course_name);
            row.closest('tr').find('#lesson_name').html(result.data.lesson_name);

            if (result.data.text_link === '') {
              row.closest('tr').find('.openTabPdfField').html("<button class=\"btn py-1 mb-0\" style=\"background-color: #F6B352; padding: 1px 10px\" disabled> ".concat(textDocument, " </button>"));
            } else {
              row.closest('tr').find('.openTabPdfField').html("<a class=\" btn btnNewTagPdf py-1 mb-0\" href=\"".concat(result.data.text_link, "\" target=\"_blank\" style=\"background-color: #F6B352; padding: 1px 10px\">").concat(textDocument, "</a>"));
            }

            if (result.data.text_link === '') {
              row.closest('tr').find('.showVideoField').html("<button class=\"btn py-1 mb-0 \" style=\"background-color: #F68657; padding: 1px 10px\" disabled> Video </button>");
            } else {
              row.closest('tr').find('.showVideoField').html("<a class=\"btn btnShowVideo py-1 mb-0\" href=\"javascript:;\" data-video_link=\"".concat(result.data.video_link, "\" style=\"background-color: #F68657; padding: 1px 10px\">Video</a>"));
            }

            row.closest('tr').find('.startLesson').attr('data-lesson_id', result.data.lesson_id);
            row.closest('tr').find('.startLesson').attr('data-course_id', result.data.course_id);
            $('#modalChangeLesson').modal('hide');
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-success alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                    ".concat(result.message, "\n                                </div>\n                            </section>\n                        "));
            $("html, body").animate({
              scrollTop: 0
            }, "slow");
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_LESSON_BOOKING.changeLesson = function () {
    $('body').on('click', '.btnChangeLesson', function () {
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      var lesson_id = $(this).attr('data-lesson_id');
      var course_id = $(this).attr('data-course_id');
      var teacher_id = $(this).attr('data-teacher_id');
      teacher_schedule_id = $(this).attr('data-teacher_schedule_id');
      var formData = new FormData();
      formData.append('teacher_id', teacher_id);
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routeGetCourseCanTeach,
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $('#loading').removeClass("d-block");
          $("#loading").addClass('d-none');

          if (!result.status) {
            toastr.error(result.message);
          } else {
            dataLesson = _objectSpread({}, result.data);
            showModalChangeLesson(course_id, lesson_id);
            $('#modalChangeLesson').modal('show');
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
    $('body').on('change', '#course_id_select', function () {
      showModalChangeLesson($(this).val(), 1);
    });
  };

  var showModalChangeLesson = function showModalChangeLesson(course_id, lesson_id) {
    var course_html = '';
    var lesson_html = '';
    var data = Object.values(dataLesson).reduce(function (acc, val) {
      if (acc[val.course_id] === undefined) {
        acc[val.course_id] = [];
      }

      ;
      acc[val.course_id].push(val);
      return acc;
    }, {});
    $.each(data, function (index, value) {
      if (value[0].course_id === parseInt(course_id)) {
        course_html += "<option value=\"".concat(value[0].course_id, "\" selected>\n                                    ").concat(value[0].course_name, "\n                                </option>");
      } else {
        course_html += "<option value=\"".concat(value[0].course_id, "\">\n                                    ").concat(value[0].course_name, "\n                                </option>");
      }

      if (value[0].course_id == parseInt(course_id)) {
        for (var i = 0; i < value.length; ++i) {
          if (value[i].lesson_id === parseInt(lesson_id)) {
            lesson_html += "<option value=\"".concat(value[i].lesson_id, "\" selected>\n                                    ").concat(value[i].lesson_name, "\n                                </option>");
            continue;
          }

          lesson_html += "<option value=\"".concat(value[i].lesson_id, "\">\n                                    ").concat(value[i].lesson_name, "\n                                </option>");
        }
      }
    });
    $('#course_id_select').html(course_html);
    $('#lesson_id_select').html(lesson_html);
  };

  STUDENT_LESSON_BOOKING.changeUserTimepicker = function () {
    $('.bs-timepicker').click(function (e) {
      var temp = e.target.id;
      $("#".concat(temp)).toggleClass('btn-warning');
      var hasClass = $("#".concat(temp)).hasClass('btn-warning');
      var time = $("#".concat(temp)).val();
      var dateTime = $("#".concat(temp)).parents("tr:first").children("td:first").children().text();
      var row = $("#".concat(temp)).parent().parent().attr('id');

      if (hasClass === true) {
        var OBJ_BOOKING = {
          timemer: [time],
          row: row,
          date: dateTime.trim()
        };
        var index = Arr_BOOKING.findIndex(function (el) {
          return el.row === row;
        });

        if (index === -1) {
          return Arr_BOOKING.push(OBJ_BOOKING);
        } else {
          Arr_BOOKING.filter(function (item) {
            if (item.row === row) {
              item.timemer.push(time);
            }
          });
        }
      } else {
        Arr_BOOKING.map(function (item) {
          for (var i = 0; i < item.timemer.length; i++) {
            if (item.row === row) {
              if (item.timemer[i] === time) {
                return item.timemer.splice(item.timemer[i], 1);
              }
            }
          }
        });
      }
    });
  };

  $('#confirm-modal').click(function () {
    $('#modalConfirm-Booking').modal('toggle');
    $('#modal-lg').modal('toggle');
    $('#comfirm__booking').html(STUDENT_LESSON_BOOKING.displayPopup(Arr_BOOKING));
  });

  STUDENT_LESSON_BOOKING.limitContent = function () {
    $('.ellipsis').each(function () {
      $(this).attr('data-toggle', "tooltip");
      $(this).attr('data-placement', "right");
      $(this).attr('data-original-title', $(this).html());
    });
  };

  STUDENT_LESSON_BOOKING.displayPopup = function (OBJ) {
    if (OBJ.length < 0) return;
    var html = "";

    for (var i = 0; i < OBJ.length; i++) {
      var text = "";
      var classText = ""; // console.log(OBJ[i]);

      var _iterator = _createForOfIteratorHelper(OBJ[i].timemer),
          _step;

      try {
        for (_iterator.s(); !(_step = _iterator.n()).done;) {
          var k = _step.value;
          OBJ[i].date = OBJ[i].date.trim();

          if (OBJ[i].date.includes('Sat')) {
            classText = "text-primary";
          } else if (OBJ[i].date.includes('Sun')) {
            classText = "text-danger";
          }

          text += " <input type=\"text\" id=\"timepicker1-0\"\n                class=\"  btn btn-warning mr-lg-5 mb-3 mt-1 text-center timepicker1\"\n                style=\"width: 65px\" value=\"".concat(k, "\" readonly=\"\">");
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      html += "<tr>\n                    <th hidden=\"\"></th>\n                    <td style=\"width: 150px\">\n                        <div class=\"justify-content-center text-center\">\n                            <span class=\"text-center ".concat(classText, "\">").concat(OBJ[i].date, "</span>\n                        </div>\n                    </td>\n                    <td>\n                        <div class=\"d-flex justify-content-between mt-1 addButton\" id=\"divRow1\">\n                            <div class=\"row ml-2\" id=\"row1\">\n                                <div class=\"d-flex\" id=\"divTimepicker1-0\">\n                                    <div class=\"mb-2 mr-2\" id=\"removeTimepicker1-0\">\n                                    </div>\n                                   ").concat(text, "\n                                </div>\n                            </div>\n                        </div>\n                    </td>\n                </tr>");
    }

    return html;
  };

  STUDENT_LESSON_BOOKING.bookingScheduleConfirmation = function () {
    $('#btnBookingScheduleConfirmation').click(function () {
      $('#loading_wait_teacher').show();
      checkCloseModalConfirm = true;
      var fd = new FormData();
      fd.append('start_hour', $('#start_hour').val());
      fd.append('start_date', $('#start_date').val());
      fd.append('course_id', $('#course_number').val());
      fd.append('lesson_id', $('#lesson_number').val());
      fd.append('student_id', $('#student_id').val());
      fd.append('coin', $('#coin').val());
      fd.append('type', $('#type').val());
      fd.append('book_type', $('#book_type').val());
      var dataCookies = {
        "start_hour": $('#start_hour').val(),
        "start_date": $('#start_date').val(),
        "course_id": $('#course_id').val(),
        "lesson_id": $('#lesson_number').val(),
        "student_id": $('#student_id').val(),
        "coin": $('#coin').val(),
        "type": $('#type').val()
      };
      var expDate = new Date();

      var data_cookies = _objectSpread(_objectSpread({}, dataCookies), {}, {
        'expires': expDate.getTime() + 3 * 60 * 1000
      });

      expDate.setTime(expDate.getTime() + 3 * 60 * 1000); // add 3 minutes

      $.cookie('notification_student_booked', JSON.stringify(data_cookies), {
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
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          if (!result.status) {
            $('#modalSuccessfulBooking').modal('hide'); // $(`[data-idbooking="${idBooking}"]`).attr('disabled',"disabled");

            toastr.error(result.message);
          } else {
            $('#modalSuccessfulBooking').modal('hide'); // $(`[data-idbooking="${idBooking}"]`).attr('disabled',"disabled");
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
      timeoutOfStudent = setTimeout(function () {
        $('#loading_wait_teacher').hide();
        $('#modalCancelRequest').modal('show');
      }, 60 * 1000 * 3);
    });
  };

  STUDENT_LESSON_BOOKING.cancelScheduleWhenClickOutside = function () {
    $('#modalSuccessfulBooking').on('hide.bs.modal', function () {
      if (checkCloseModalConfirm) {
        checkCloseModalConfirm = false;
        return true;
      }

      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotificationWhenCanceled,
        data: {},
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_LESSON_BOOKING.startLesson = function () {
    $('.startLesson').click(function () {
      var start_hour = $(this).attr('data-start_hour');
      var start_date = $(this).attr('data-start_date');
      var coin = $(this).data('coin');
      routeBookSchedule = $(this).attr('data-route_booked_confirm');
      routePushNotification = $(this).attr('data-route_booked');
      routePushNotificationWhenCanceled = $(this).attr('data-route_canceled');
      idBooking = $(this).attr('data-idbooking');
      var lesson_id = $(this).attr('data-lesson_id');
      var course_id = $(this).attr('data-course_id');
      $('#start_hour').val(start_hour);
      $('#start_date').val(start_date);
      $('#lesson_number').val(lesson_id);
      $('#course_number').val(course_id);
      $('#coin').val(coin);
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotification,
        data: {
          bookingId: idBooking
        },
        success: function success(result) {
          if (result.data.expired) {
            $("[data-idbooking=".concat(idBooking, "]")).attr('disabled', 'disabled');
            $('#modalLessonUnavailableNow').modal('show');
          } else {
            if (!result.status) {
              $('#modalSuddenTeacher').modal('hide');
              $('#modalFailedBooking').modal('show');
            } else {
              $('#modalSuccessfulBooking').modal('show');
            }
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_LESSON_BOOKING.cancelSchedule = function () {
    $('#btnCancelSchedule').click(function () {
      checkCloseModalConfirm = true;
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotificationWhenCanceled,
        data: {},
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };

  STUDENT_LESSON_BOOKING.removeBookingList = function () {
    $('.cancelBooking').click(function () {
      var idBooking = $(this).attr('data-idbooking');
      var teacherName = $(this).attr('data-teacherName');
      var date = $(this).attr('data-start_date');
      var time = $(this).attr('data-start_hour');
      var coin = $(this).attr('data-coin');
      var startDate = $(this).attr('data-date');
      $('#btnConfirmRemove').attr('data-idbooking', idBooking);
      $('#btnConfirmRemove').attr('data-start_date', date);
      $('#btnConfirmRemove').attr('data-start_hour', time);
      $('#btnConfirmRemove').attr('data-coin', coin);
      $('#teacherName').text(teacherName);
      $('#date').text(date);
      $('#time').text(time);
      $('#coin_techer').text(coin);
      $('#btnConfirmRemove').attr('data-date', startDate);
      $('#btnConfirmRemoveStep2').attr('data-date', startDate);
    });
    $('#btnConfirmRemove').click(function () {
      var start_date = $(this).attr('data-start_date');
      var start_hour = $(this).attr('data-start_hour');
      var idBooking = $(this).attr('data-idbooking');
      var checkTimeExpired = true;
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      $.ajax({
        url: routeCheckTimeRemove,
        dataType: "json",
        type: 'post',
        async: false,
        data: {
          bookingId: idBooking
        },
        headers: {
          "X-CSRF-TOKEN": csrf_token
        },
        success: function success(result) {
          $("#loading").addClass('d-none');
          $('#loading').removeClass("d-block");

          if (result.data.expired && $('#btnConfirmRemove').attr('data-coin') > 0) {
            checkTimeExpired = false;
            return true;
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });

      if (!checkTimeExpired) {
        $('#booking_id').val(idBooking);
        $('#modal-removeBooking').modal('hide');
        $('#modalConfirmRemoveBooking').modal('show');
      } else {
        var startDate = $(this).attr('data-date');
        $.ajax({
          url: "/student/remove-booking/list",
          dataType: "json",
          type: 'post',
          data: {
            idBooking: idBooking
          },
          headers: {
            "X-CSRF-TOKEN": csrf_token
          },
          beforeSend: function beforeSend() {
            $(this).html("<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span>Loading...");
          },
          success: function success(result) {
            if (result.status) {
              $('#modal-removeBooking').modal('hide');
              $('#area_message').html("\n                                <section class=\"content-header px-0\">\n                                    <div class=\"alert alert-success alert-dismissible\">\n                                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                        <i class=\"icon fa fa-check\"></i>\n                                        ".concat(result.data, "\n                                    </div>\n                                </section>\n                            "));
              $('#row-' + idBooking).parent().remove();
              var rowspan = $("td[data-start_date=\"".concat(startDate, "\"]")).attr('rowspan');
              $("td[data-start_date=\"".concat(startDate, "\"]")).attr('rowspan', rowspan - 1);

              if (rowspan == 2) {
                $("td[data-start_date=\"".concat(startDate, "\"]")).remove();
              }

              the_number_of_record -= 1;

              if (the_number_of_record >= 2) {
                $('#the_number_of_records').html(the_number_of_record + " Records");
              } else if (the_number_of_record === 1) {
                $('#the_number_of_records').html(the_number_of_record + " Record");
              } else {
                $('#no-data').removeClass('d-none');
                $('#no-data').addClass('d-block');
                $('#no-data').html(message_no_data);
                $('#the_number_of_records').addClass('d-none');
                $('#booking_list').addClass('d-none');
              }
            } else {
              toastr.error(result.message);
            }
          },
          error: function error(result) {
            toastr.error(result.message);
          }
        });
      }
    });
    $('#btnConfirmRemoveStep2').click(function () {
      var idBooking = $('#booking_id').val();
      var startDate = $(this).attr('data-date');
      $.ajax({
        url: "/student/remove-booking/list",
        dataType: "json",
        type: 'post',
        data: {
          idBooking: idBooking
        },
        headers: {
          "X-CSRF-TOKEN": csrf_token
        },
        beforeSend: function beforeSend() {
          $(this).html("<span class=\"spinner-border spinner-border-sm\" role=\"status\" aria-hidden=\"true\"></span>Loading...");
        },
        success: function success(result) {
          if (result.status) {
            $('#modal-removeBooking').modal('hide');
            $('#area_message').html("\n                            <section class=\"content-header px-0\">\n                                <div class=\"alert alert-success alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-check\"></i>\n                                    ".concat(result.data, "\n                                </div>\n                            </section>\n                        "));
            $('#row-' + idBooking).parent().remove();
            var rowspan = $("td[data-start_date=\"".concat(startDate, "\"]")).attr('rowspan');
            $("td[data-start_date=\"".concat(startDate, "\"]")).attr('rowspan', rowspan - 1);

            if (rowspan == 2) {
              $("td[data-start_date=\"".concat(startDate, "\"]")).remove();
            }

            the_number_of_record -= 1;

            if (the_number_of_record >= 2) {
              $('#the_number_of_records').html(the_number_of_record + " Records");
            } else if (the_number_of_record === 1) {
              $('#the_number_of_records').html(the_number_of_record + " Record");
            } else {
              $('#no-data').removeClass('d-none');
              $('#no-data').addClass('d-block');
              $('#no-data').html(message_no_data);
              $('#the_number_of_records').addClass('d-none');
              $('#booking_list').addClass('d-none');
            }

            $('#modalConfirmRemoveBooking').modal('hide');
          } else {
            toastr.error(result.message);
            $('#modalConfirmRemoveBooking').modal('hide');
          }
        },
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };
});
$(document).ready(function () {
  STUDENT_LESSON_BOOKING.init();
});

/***/ }),

/***/ 33:
/*!************************************************************!*\
  !*** multi ./resources/js/admin/students/lessonBooking.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\lessonBooking.js */"./resources/js/admin/students/lessonBooking.js");


/***/ })

/******/ });