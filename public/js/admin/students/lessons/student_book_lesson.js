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
/******/ 	return __webpack_require__(__webpack_require__.s = 29);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/lessons/student_book_lesson.js":
/*!********************************************************************!*\
  !*** ./resources/js/admin/students/lessons/student_book_lesson.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var STUDENT_BOOK_LESSON = {};
var getRemoveImage = $("[name=removeImage]").attr('content');
var formData = $('#validateBookLesson');
var routeValidateBookLesson = $("[name=route-validate-book-lesson]").attr('content');
var routeValidateCoin = $("[name=route-validate-student-coin]").attr('content');
var routeBookSchedule = $("[name=route-book-schedule]").attr('content');
var teacher_id = $("[name=teacher_id]").attr('content');
var routeGetTeacherSchedule = $("[name=route-get-teacher-schedule]").attr('content');
var routeGetLessonsByCourse = $("[name=route-get-lessons-by-course]").attr('content');
var routePushNotificationWhenCanceled = $("[name=route-cancel-schedule]").attr('content');
var routePushNotification = $("[name=route-push-notification]").attr('content');
var textDontLesson = $("[name=message-teacher-status]").attr('content');
var checkTeacherCanTeach = parseInt($("[name=checkTeacherCanTeach]").attr('content'));
var routeRequestCancel = $("[name=route-timeout]").attr('content');
var checkCoin = $("[name=checkCoin]").attr('content');
var csrf_token = $("[name=csrf-token]").attr('content');
var routeGetStudentLessonInfo = $("[name=route-get-student-lesson-info]").attr('content');
var count = 0;
var Arr_BOOKING = [];
var checkCloseModalConfirm = false;
var choosing_schedule = null;
var choosing_lesson = null;
var previous_lesson = $('#lesson_id_select').val();
var previous_lesson_temp = null;
var previous_course = $('#course_id_select').val();
var arrayChooseLesson = [];
var M071 = $("[name=M071]").attr('content');
var M074 = $("[name=M074]").attr('content');
$(function () {
  STUDENT_BOOK_LESSON.init = function () {
    // $("#modal-lesson").modal("show");
    STUDENT_BOOK_LESSON.clickBtnGetTeacherSchedule();
    STUDENT_BOOK_LESSON.clickBtnConfirm();
    STUDENT_BOOK_LESSON.clickbtnCancelConfirm();
    STUDENT_BOOK_LESSON.clickbtnOK();
    STUDENT_BOOK_LESSON.clickbtnCancelBooking();
    STUDENT_BOOK_LESSON.clickbtnValidate();
    STUDENT_BOOK_LESSON.bookSchedule();
    STUDENT_BOOK_LESSON.bookingScheduleConfirmation();
    STUDENT_BOOK_LESSON.cancelSchedule();
    STUDENT_BOOK_LESSON.selectCourse();
    STUDENT_BOOK_LESSON.clickBtnChooseLesson();
    STUDENT_BOOK_LESSON.clickBtnCloseChooseLesson();
  };

  STUDENT_BOOK_LESSON.clickBtnCloseChooseLesson = function () {
    $('#btnCancelChooseLesson').click(function () {
      if (!checkTeacherCanTeach) {
        $('#teacherCanTeach').addClass('d-none');
        $('.teacherCantTeach').removeClass('d-none');
        $('#lesson_id_select').html('');
        $('#lesson_id_select').attr('disabled', true);
        $('#course_id_select option').attr('selected', false);
        $('#course_id_select').find('option[id=course_empty]').remove();
        $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>');
        $('#btnChooseLesson').attr('disabled', true);
      } else {
        var now_course = $('#course_id_select').val();
        console.log('now: ' + now_course, 'previous: ' + previous_course);

        if (now_course != previous_course) {
          $('#course_id_select').val(previous_course);
          $('#course_id_select').change();
        }

        console.log('previous_lesson_temp:' + previous_lesson_temp);
        $('#lesson_id_select option').attr('selected', false);
        $('#lesson_id_select').find("option[value=".concat(previous_lesson_temp, "]")).attr('selected', true);
      }

      var row = $("#".concat(choosing_schedule)).parent().attr('id');
      STUDENT_BOOK_LESSON.removeScheduleFailed('' + choosing_schedule, row);
      formData.find("input[id='schedule-".concat(choosing_schedule, "']")).remove();
      formData.find("input[id='schedule_".concat(choosing_schedule, "_lesson']")).remove();
      $("#".concat(choosing_schedule)).removeClass('btn-warning');
      $("#".concat(choosing_schedule)).addClass('btn-success');
      choosing_schedule = null;
    });
  };

  STUDENT_BOOK_LESSON.clickBtnChooseLesson = function () {
    $('#btnChooseLesson').click(function () {
      checkTeacherCanTeach = 1;
      choosing_lesson = $('#lesson_id_select').val();
      console.log('choosing_lesson: ' + choosing_lesson);
      previous_lesson = parseInt(choosing_lesson) + 1; // console.log(choosing_schedule + ' : ' + choosing_lesson);

      formData.find("input[id='schedule_".concat(choosing_schedule, "_lesson']")).remove();
      var temp = "<input type=\"text\" value=\"".concat(choosing_schedule, ":").concat(choosing_lesson, "\" name=\"schedule_lesson[]\" hidden id=\"schedule_").concat(choosing_schedule, "_lesson\">");
      formData.append(temp);
      $('#modal-lesson').modal('hide');
      var course_id = parseInt($('#course_id_select option:selected').val());
      var lesson_id = parseInt($('#lesson_id_select option:selected').val());
      arrayChooseLesson.push(course_id + ':' + lesson_id);
      console.log(previous_lesson_temp, arrayChooseLesson);
      STUDENT_BOOK_LESSON.selectNextLesson();
    });
  };

  STUDENT_BOOK_LESSON.selectNextLesson = function () {
    var check_course = $('#course_id_select option:selected').next().is('option');

    if (check_course) {
      var check_lesson = $('#lesson_id_select option:selected').next().is('option'); // console.log(1 +' : '+ check_lesson);

      if (check_lesson) {
        // console.log(2)
        setTimeout(function () {
          var temp = $('#lesson_id_select option:selected');
          temp.attr('selected', false);
          temp.next().attr('selected', true);
          previous_lesson_temp = $('#lesson_id_select').val();
        }, 100);
      } else {
        // console.log(3);
        // setTimeout(() => {
        $('#loading').css('display', 'block');

        var _temp = $('#course_id_select option:selected'); // console.log(temp);


        var val = _temp.next().val();

        $('#course_id_select').val(val);

        _temp.attr('selected', false);

        _temp.next().attr('selected', true);

        previous_course = $('#course_id_select').val(); // }, 100)

        _temp = "<input type=\"text\" value=\"".concat(val, "\" name=\"get_lesson_by_course\" id=\"get_lesson_by_course\" hidden>");
        $('#form-get-lesson').append(_temp);
        var data = $('#form-get-lesson').serialize();
        $.ajax({
          type: "GET",
          url: routeGetLessonsByCourse,
          data: data,
          success: function success(result) {
            $('#form-get-lesson').find("input[id='get_lesson_by_course']").remove();

            if (result.data === 'lessons') {
              var options = '';
              $.each(result.message, function (index, value) {
                options += "<option value=\"".concat(value.id, "\">").concat(value.name, "</option>");
              });
              $('#lesson_id_select').html(options);
              $('#lesson_id_select option:first').attr('selected', true);
            }

            previous_lesson_temp = $('#lesson_id_select').val();
            $('#loading').css('display', 'none');
          },
          error: function error(_error) {
            $('#loading').css('display', 'none');
            alert("Error server");
          }
        });
      }
    } else {
      var check = $('#lesson_id_select option:selected').next().is('option'); // console.log(4 + ': ' +check);

      if (check) {
        setTimeout(function () {
          var temp = $('#lesson_id_select option:selected');
          temp.attr('selected', false);
          temp.next().attr('selected', true);
          previous_lesson_temp = $('#lesson_id_select').val();
        }, 100);
      } else {
        // console.log(5);
        previous_lesson_temp = $('#lesson_id_select').val();
        previous_course = $('#course_id_select').val();
        return 1;
      }
    }
  };

  STUDENT_BOOK_LESSON.selectCourse = function () {
    $('#course_id_select').on('change', function () {
      $('#loading').css('display', 'block');

      if (count === 1) {
        $('#course_id_select').find('option[id=course_empty]').remove();
        $('#lesson_id_select').attr('disabled', false);
        $('#btnChooseLesson').attr('disabled', false);
      }

      var val = $('#course_id_select').val();
      previous_course = val;
      console.log('value: ' + val);
      temp = "<input type=\"text\" value=\"".concat(val, "\" name=\"get_lesson_by_course\" id=\"get_lesson_by_course\" hidden>");
      $('#form-get-lesson').append(temp);
      var data = $('#form-get-lesson').serialize();
      $.ajax({
        type: "GET",
        url: routeGetLessonsByCourse,
        data: data,
        success: function success(result) {
          $('#form-get-lesson').find("input[id='get_lesson_by_course']").remove();

          if (result.data === 'lessons') {
            if (result.message.length > 0) {
              $('#teacherCanTeach').removeClass('d-none');
              $('.teacherCantTeach').addClass('d-none');
            } else {
              $('#teacherCanTeach').addClass('d-none');
              $('.teacherCantTeach').removeClass('d-none');
            }

            var options = '';

            if (result.message.length < 1) {
              var options = "<option selected></option>";
              $('#lesson_id_select').html(options);
              return 1;
            }

            $.each(result.message, function (index, value) {
              options += "<option value=\"".concat(value.id, "\" ").concat(value.id == previous_lesson_temp ? 'selected' : '', ">").concat(value.name, "</option>");
            });
            $('#lesson_id_select').html(options); // previous_lesson_temp = previous_lesson;

            previous_lesson = $('#lesson_id_select option:first').val();
          }

          $('#loading').css('display', 'none');
        },
        error: function error(_error) {
          $('#loading').css('display', 'none');
          alert("Error server");
        }
      });
    });
  };

  STUDENT_BOOK_LESSON.clickbtnOK = function () {
    $('#btnOK').click(function () {
      $('#btnCancelBooking').click();
    });
  };

  STUDENT_BOOK_LESSON.clickBtnGetTeacherSchedule = function () {
    $('#btn_get_teacher_schedule').click(function () {
      $('#loading').css('display', 'block');
      arrayChooseLesson = [];
      $('#lesson_id_select').html('');
      var form_data = new FormData();
      form_data.append('_token', csrf_token); //get student lesson info

      $.ajax({
        type: "post",
        url: routeGetStudentLessonInfo,
        data: form_data,
        contentType: false,
        processData: false,
        success: function success(result) {
          // console.log(result);
          if (result.data === 'last_lesson') {
            if (!result.message.check_teacher_can_teach) {
              if (result.message.check_latest_lesson) {
                $('#messageCantTeach').html(M074);
              } else {
                $('#messageCantTeach').html(M071);
              }

              checkTeacherCanTeach = 0;
              $('#course_id_select').find('option[id=course_empty]').remove();
              $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>');
              $('#btnChooseLesson').attr('disabled', true);
              $('#lesson_id_select').attr('disabled', true);
              $('#teacherCanTeach').addClass('d-none');
              $('.teacherCantTeach').removeClass('d-none');
              arrayChooseLesson.push('0:0');
            } else {
              checkTeacherCanTeach = 1;
              $('#teacherCanTeach').removeClass('d-none');
              $('.teacherCantTeach').addClass('d-none');
              $('#lesson_id_select').attr('disabled', false);
              $('#btnChooseLesson').attr('disabled', false);
              $('#course_id_select').find('option[id=course_empty]').remove();
              var options = '';
              $('#course_id_select').find("option[value=\"".concat(result.message.last_lesson.course_id, "\"]")).attr('selected', true);
              $.each(result.message.lessons, function (index, value) {
                options += "<option value=\"".concat(value.id, "\" ").concat(value.id === result.message.last_lesson.lesson_id ? 'selected' : '', ">").concat(value.name, "</option>");
              });
              arrayChooseLesson.push(result.message.last_lesson.course_id + ':' + result.message.last_lesson.lesson_id);
              previous_course = result.message.last_lesson.course_id;
              previous_lesson = result.message.last_lesson.lesson_id;
              previous_lesson_temp = previous_lesson;
              $('#lesson_id_select').html(options);
            }
          }
        },
        error: function error(_error) {
          alert('Error server');
        }
      });
      $('#btnCancelBooking').click();
      $.ajax({
        type: "GET",
        url: routeGetTeacherSchedule,
        success: function success(result) {
          $('#loading').css('display', 'none');

          if (result.status === true) {
            $('#table-booking').html("<tbody>".concat(result.data.content, "</tbody>"));
            STUDENT_BOOK_LESSON.toggleChangeTimepicker();
            $('#modal-lg').modal('toggle');
          } else if (result.status === false) {
            console.log(result.message);
            $('#error_premium_is_expired').html("<div class=\"alert alert-danger alert-dismissible\">\n                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                <i class=\"icon fa fa-ban\"></i>\n                                <span id=\"error_mes\">".concat(result.message, "</span>\n                               </div>"));
            $("html").animate({
              scrollTop: $('#error_premium_is_expired').offset()
            });
            STUDENT_BOOK_LESSON.premiumIsExpired();
          }
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };

  STUDENT_BOOK_LESSON.clickbtnCancelBooking = function () {
    $('#btnCancelBooking').click(function () {
      choosing_schedule = null;
      choosing_lesson = null;
      previous_lesson_temp = null;
      $('#error_section_confirm').html('');
      $('#error_section').html('');
      $.each(Arr_BOOKING, function (index, value) {
        $.each(value.schedule_id, function (i, v) {
          $("#".concat(v)).removeClass('btn-warning');
          formData.find("input[id='schedule-".concat(v, "']")).remove();
          formData.find("input[id='schedule_".concat(v, "_lesson']")).remove();
          var row = $("#".concat(v)).parent().attr('id');
        });
      });
      Arr_BOOKING = [];
      count = 0;
      $('#validate').prop('disabled', true);
      $('#btnConfirm').prop('disabled', true);
    });
  };

  STUDENT_BOOK_LESSON.clickbtnCancelConfirm = function () {
    $('#btnCancelConfirm').click(function () {
      $('#btnCancelBooking').click();
    });
  };

  STUDENT_BOOK_LESSON.sortArray = function () {
    for (i = 0; i < Arr_BOOKING.length; i++) {
      for (j = i + 1; j < Arr_BOOKING.length; j++) {
        if (Arr_BOOKING[i].row.split('row')[1] > Arr_BOOKING[j].row.split('row')[1]) {
          obj = {};
          obj = Arr_BOOKING[i];
          Arr_BOOKING[i] = Arr_BOOKING[j];
          Arr_BOOKING[j] = obj;
        }
      }
    }
  };

  STUDENT_BOOK_LESSON.toggleChangeTimepicker = function () {
    $('.bs-timepicker').click(function (e) {
      var schedule_id = e.target.id;
      choosing_schedule = schedule_id; // console.log("choosing_schedule " +choosing_schedule)

      var date = $("#".concat(schedule_id)).parents("tr:first").children("td:first").children().text();
      var text = $("#".concat(schedule_id)).text();
      var row = $("#".concat(schedule_id)).parent().attr('id'); // console.log(row);

      var name = $("#".concat(schedule_id)).attr('name');

      if ($(this).hasClass('btn-success')) {
        $(this).toggleClass('btn-warning');

        if ($(this).hasClass('btn-warning')) {
          choosing_schedule = schedule_id;
          $('#modal-lesson').modal('show');
          count++;
          name = e.target.name;
          value = e.target.value;
          id = e.target.id;
          temp = "<input type=\"text\" value=\"".concat(value, "\" name=\"").concat(name, "[]\" hidden id=\"schedule-").concat(id, "\">");
          formData.append(temp);
          var OBJ_BOOKING = {
            schedule_id: [schedule_id],
            value: [text],
            name: [name],
            row: row,
            date: date.trim()
          };
          var index = Arr_BOOKING.findIndex(function (el) {
            return el.row === row;
          });

          if (index === -1) {
            Arr_BOOKING.push(OBJ_BOOKING);
          } else {
            Arr_BOOKING.filter(function (item) {
              if (item.row === row) {
                item.schedule_id.push(schedule_id);
                item.value.push(text);
                item.name.push(name);
              }
            });
          }
        } else {
          $('#loading').css('display', 'block');
          arrayChooseLesson.pop();
          var lesson = arrayChooseLesson[arrayChooseLesson.length - 1];
          console.log(lesson, arrayChooseLesson);
          lesson = lesson.split(':');
          var now_course = $('#course_id_select').val();

          if (now_course != lesson[0]) {
            $('#course_id_select').val(lesson[0]);

            if (count === 1) {
              $('#course_id_select').find('option[id=course_empty]').remove();
              $('#lesson_id_select').attr('disabled', false);
              $('#btnChooseLesson').attr('disabled', false);
            }

            var val = $('#course_id_select').val();
            previous_course = val;
            console.log('value: ' + lesson[0]);
            temp = "<input type=\"text\" value=\"".concat(lesson[0], "\" name=\"get_lesson_by_course\" id=\"get_lesson_by_course\" hidden>");
            $('#form-get-lesson').append(temp);
            var data = $('#form-get-lesson').serialize();
            $.ajax({
              type: "GET",
              url: routeGetLessonsByCourse,
              data: data,
              success: function success(result) {
                $('#form-get-lesson').find("input[id='get_lesson_by_course']").remove();

                if (result.data === 'lessons') {
                  if (result.message.length > 0) {
                    $('#teacherCanTeach').removeClass('d-none');
                    $('.teacherCantTeach').addClass('d-none');
                  } else {
                    $('#teacherCanTeach').addClass('d-none');
                    $('.teacherCantTeach').removeClass('d-none');
                  }

                  var options = '';
                  $.each(result.message, function (index, value) {
                    options += "<option value=\"".concat(value.id, "\">").concat(value.name, "</option>");
                  });
                  $('#lesson_id_select').html(options);
                }

                if (lesson[1] == 0 && lesson[0] == 0) {
                  checkTeacherCanTeach = false;
                }

                previous_lesson = previous_lesson_temp = lesson[1]; // previous_course = lesson[0];

                choosing_lesson = lesson[1];
                $('#lesson_id_select option').attr('selected', false);
                $('#lesson_id_select').find("option[value=".concat(lesson[1], "]")).attr('selected', true);
                console.log('previous_lesson_temp: ' + previous_lesson_temp);

                if (arrayChooseLesson.length > 1) {
                  console.log('select next'); // previous_lesson = previous_lesson_temp = parseInt(previous_lesson_temp) + 1;

                  STUDENT_BOOK_LESSON.selectNextLesson();
                } else if (!checkTeacherCanTeach) {
                  $('#lesson_id_select').html('');
                  $('#lesson_id_select').attr('disabled', true);
                  $('#course_id_select option').attr('selected', false);
                  $('#course_id_select').find('option[id=course_empty]').remove();
                  $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>');
                  $('#btnChooseLesson').attr('disabled', true);
                }

                $('#loading').css('display', 'none');
              },
              error: function error(_error) {
                $('#loading').css('display', 'none');
                alert("Error server");
              }
            });
          } else {
            if (lesson[1] == 0 && lesson[0] == 0) {
              checkTeacherCanTeach = false;
            }

            previous_lesson = previous_lesson_temp = lesson[1]; // previous_course = lesson[0];

            choosing_lesson = lesson[1];
            $('#lesson_id_select option').attr('selected', false);
            $('#lesson_id_select').find("option[value=".concat(lesson[1], "]")).attr('selected', true);

            if (arrayChooseLesson.length > 1) {
              console.log('select next');
              STUDENT_BOOK_LESSON.selectNextLesson();
            } else if (!checkTeacherCanTeach) {
              $('#lesson_id_select').html('');
              $('#lesson_id_select').attr('disabled', true);
              $('#course_id_select option').attr('selected', false);
              $('#course_id_select').find('option[id=course_empty]').remove();
              $('#course_id_select').prepend('<option value="" selected id="course_empty"></option>');
              $('#btnChooseLesson').attr('disabled', true);
            }

            $('#loading').css('display', 'none');
          }

          STUDENT_BOOK_LESSON.removeScheduleFailed(schedule_id, row);
          var _id = e.target.id;
          formData.find("input[id='schedule-".concat(_id, "']")).remove();
          formData.find("input[id='schedule_".concat(_id, "_lesson']")).remove();
        }
      }

      STUDENT_BOOK_LESSON.sortArray();

      if (count === 0) {
        $('#validate').prop('disabled', true);
        $('#btnConfirm').prop('disabled', true);
      } else {
        $('#validate').prop('disabled', false);
        $('#btnConfirm').prop('disabled', false);
      }
    });
  };

  STUDENT_BOOK_LESSON.removeScheduleFailed = function (schedule_id, row) {
    Arr_BOOKING.map(function (item) {
      for (var i = 0; i < item.schedule_id.length; i++) {
        if (item.row === row) {
          if (item.schedule_id[i] === schedule_id) {
            item.schedule_id.splice(i, 1);
            item.value.splice(i, 1);
            item.name.splice(i, 1);
          }
        }
      }
    });
    count--;

    if (count === 0) {
      $('#validate').prop('disabled', true);
      $('#btnConfirm').prop('disabled', true);
    } else {
      $('#validate').prop('disabled', false);
      $('#btnConfirm').prop('disabled', false);
    }
  };

  STUDENT_BOOK_LESSON.displayPopup = function (OBJ) {
    if (OBJ.length < 0) return;
    var html = "";

    for (var i = 0; i < OBJ.length; i++) {
      var text = "";
      var classText = ""; // console.log(OBJ[i]);

      if (OBJ[i].schedule_id.length === 0) continue;

      for (var k = 0; k < OBJ[i].schedule_id.length; k++) {
        OBJ[i].date = OBJ[i].date.trim();

        if (OBJ[i].date.includes('Sat')) {
          classText = "text-primary";
        } else if (OBJ[i].date.includes('Sun')) {
          classText = "text-danger";
        }

        text += " <button name=\"".concat(OBJ[i].name[k], "\"\n                                class=\"bs-timepicker btn\n                                btn-warning\n                                mr-lg-5 mb-2 mt-1 text-center \"\n                                style=\"width: 65px; height: 40px\"\n                                value=\"\"\n                                id=\"temp-").concat(OBJ[i].schedule_id[k], "\">\n                                ").concat(OBJ[i].value[k], "\n                          </button>");
      }

      html += "<tr>\n                    <th hidden=\"\"></th>\n                    <td style=\"width: 130px\">\n                        <div class=\"mt-2\">\n                            <span class=\"text-center ".concat(classText, "\">").concat(OBJ[i].date, "</span>\n                        </div>\n                    </td>\n                    <td>\n                        <div class=\"d-flex justify-content-between\">\n                            <div class=\"row ml-2\" id=\"\">\n                                <div class=\"d-flex\">\n                                    <div class=\"mb-2 mr-2\">\n                                    </div>\n                                    ").concat(text, "\n                                </div>\n                            </div>\n                        </div>\n                    </td>\n                </tr>");
    }

    return html;
  };

  STUDENT_BOOK_LESSON.addMessageErrorCommon = function (message) {
    $('#error_section').html("\n            <div class=\"alert alert-danger alert-dismissible\">\n                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                <i class=\"icon fa fa-ban\"></i>\n                <span id=\"error_mes\">".concat(message, "</span>\n            </div>\n        "));
  };

  STUDENT_BOOK_LESSON.removeSchedule = function (value) {
    $("#".concat(value)).removeClass('btn-warning btn-success');
    $("#".concat(value)).addClass('btn-secondary border-danger');
    $("#".concat(value)).prop('disabled', true);
    $("#temp-".concat(value)).removeClass('btn-warning btn-success');
    $("#temp-".concat(value)).addClass('btn-secondary border-danger');
    $("#temp-".concat(value)).prop('disabled', true);
    formData.find("input[id='schedule-".concat(value, "']")).remove();
    formData.find("input[id='schedule_".concat(value, "_lesson']")).remove();
  };

  STUDENT_BOOK_LESSON.premiumIsExpired = function () {
    $('#btn_get_teacher_schedule').attr('disabled', true);
    $('#btnBookSchedule').html(textDontLesson);
    $('#btnBookSchedule').attr('disabled', true);
  };

  STUDENT_BOOK_LESSON.clickbtnValidate = function () {
    $('#validate').click(function () {
      var data = formData.serialize();
      $.ajax({
        type: "POST",
        url: routeValidateBookLesson,
        data: data,
        success: function success(result) {
          if (result.data === 'error') {
            $.each(result.message, function (index, value) {
              if (index != 'message') {
                // console.log(value);
                STUDENT_BOOK_LESSON.removeSchedule(value);
                var row = $("#".concat(value)).parent().attr('id');
                STUDENT_BOOK_LESSON.removeScheduleFailed('' + value, row);
              } else {
                STUDENT_BOOK_LESSON.addMessageErrorCommon(value);
              }
            });
            $(".modal").animate({
              scrollTop: 0
            });
          } else if (result.data === 'updated') {
            $.each(result.message, function (index, value) {
              if (index != 'message') {
                var schedule = index.split(':');
                var current_row = $("#".concat(schedule[1])).parent().attr('id'); // console.log(schedule);

                var new_row = schedule[0];
                var schedule_id = schedule[1];
                var date = schedule[2];

                if (current_row !== new_row) {
                  $("#".concat(schedule_id)).attr('name', date);
                  $("#".concat(new_row)).append($("#".concat(schedule_id)));
                }

                $("#".concat(schedule_id)).removeClass('btn-warning');
                $("#".concat(schedule_id)).addClass('border-danger');
                $("#temp-".concat(schedule_id)).removeClass('btn-warning');
                $("#temp-".concat(schedule_id)).addClass('border-danger btn-success');
                var time = value.split(':');
                $("#".concat(schedule_id)).text(time[0] + ':' + time[1]);
                $("#temp-".concat(schedule_id)).text(time[0] + ':' + time[1]);
                formData.find("input[id='schedule-".concat(schedule_id, "']")).remove();
                formData.find("input[id='schedule_".concat(schedule_id, "_lesson']")).remove();
                STUDENT_BOOK_LESSON.removeScheduleFailed('' + schedule_id, current_row); // console.log(count);
              } else {
                STUDENT_BOOK_LESSON.addMessageErrorCommon(value);
              }
            });
            $(".modal").animate({
              scrollTop: 0
            });
          } else if (result.data === 'error_premium_is_expired') {
            STUDENT_BOOK_LESSON.addMessageErrorCommon(result.message);
            STUDENT_BOOK_LESSON.premiumIsExpired();
            $(".modal").animate({
              scrollTop: 0
            });
          } else if (result.message === 'Success') {
            $('#confirm').html(STUDENT_BOOK_LESSON.displayPopup(Arr_BOOKING));
            $('#modal-lg').modal('toggle');
            $('#modalConfirm-Booking').modal('toggle');
          }

          console.log(count);
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };

  STUDENT_BOOK_LESSON.clickBtnConfirm = function () {
    $('#btnConfirm').click(function () {
      $('#loading').addClass('d-block');
      var data = formData.serialize();
      $.ajax({
        type: "POST",
        url: routeValidateBookLesson,
        data: data,
        success: function success(result) {
          if (result.data === 'error') {
            $('#loading').removeClass('d-block');
            $.each(result.message, function (index, value) {
              if (index != 'message') {
                // console.log(value);
                STUDENT_BOOK_LESSON.removeSchedule(value);
                var row = $("#".concat(value)).parent().attr('id');
                STUDENT_BOOK_LESSON.removeScheduleFailed('' + value, row);
              } else {
                $('#error_section_confirm').html("\n                                        <div class=\"alert alert-danger alert-dismissible\">\n                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                            <i class=\"icon fa fa-ban\"></i>\n                                            <span id=\"error_mes\">".concat(value, "</span>\n                                        </div>"));
              }
            });
            $(".modal").animate({
              scrollTop: 0
            });
          } else if (result.data === 'updated') {
            $('#loading').removeClass('d-block');
            $.each(result.message, function (index, value) {
              if (index !== 'message') {
                var schedule = index.split(':');
                var schedule_id = schedule[1]; // var time = value.split(':');
                // $(`#temp-${schedule_id}`).text(time[0] + ':' + time[1]);

                STUDENT_BOOK_LESSON.removeSchedule(schedule_id);
                var row = $("#".concat(schedule_id)).parent().attr('id');
                STUDENT_BOOK_LESSON.removeScheduleFailed('' + schedule_id, row);
              } else {
                $('#error_section_confirm').html("\n                                        <div class=\"alert alert-danger alert-dismissible\">\n                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                            <i class=\"icon fa fa-ban\"></i>\n                                            <span id=\"error_mes\">".concat(value, "</span>\n                                        </div>"));
              }
            });
            $(".modal").animate({
              scrollTop: 0
            });
          } else if (result.data === 'error_premium_is_expired') {
            $('#loading').removeClass('d-block');
            $('#error_section_confirm').html("\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                    <span id=\"error_mes\">".concat(result.message, "</span>\n                                </div>"));
            STUDENT_BOOK_LESSON.premiumIsExpired();
            $(".modal").animate({
              scrollTop: 0
            });
          } else if (result.message === 'Success') {
            STUDENT_BOOK_LESSON.validateStudentCoin();
          }

          console.log(count);
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };

  STUDENT_BOOK_LESSON.validateStudentCoin = function () {
    // $("#loading").removeClass('d-none');
    // $('#loading').addClass("d-block");
    temp = "<input type=\"text\" value=\"".concat(count, "\" name=\"numOfSchedule\" hidden>");
    formData.append(temp);
    var data = formData.serialize();
    $.ajax({
      type: "POST",
      url: routeValidateCoin,
      data: data,
      success: function success(result) {
        if (result.data === 'error') {
          $("#loading").removeClass('d-block'); // $('#loading').addClass("d-none");
          // $(`#require_add_coin`).html(result.message);

          $('#modalNotificationWhenLackOfCoin').modal('show');
          $('#modalConfirm-Booking').modal('toggle'); // $('#modal_require_add_coin').modal('toggle');
        } else if (result.message === 'Success') {
          $('#booking_lesson_list').submit();
        }
      },
      error: function error(_error) {
        $('#loading').removeClass('d-block');
        alert("Error server");
      }
    });
  };

  STUDENT_BOOK_LESSON.bookingScheduleConfirmation = function () {
    $('#btnBookingScheduleConfirmation').click(function () {
      checkCloseModalConfirm = true;
      $('#modalSuccessfulBooking').modal('hide');
      $('#loading_wait_teacher').show();
      var formData = new FormData();
      formData.append('start_hour', $('#start_hour').val());
      formData.append('start_date', $('#start_date').val());
      formData.append('course_id', $('#start_course_id').val());
      formData.append('lesson_id', $('#start_lesson_id').val());
      formData.append('student_id', $('#student_id').val());
      formData.append('coin', $('#coin').val());
      formData.append('type', $('#type').val());
      var dataCookies = {
        "start_hour": $('#start_hour').val(),
        "start_date": $('#start_date').val(),
        "course_id": $('#start_course_id').val(),
        "lesson_id": $('#start_lesson_id').val(),
        "student_id": $('#student_id').val(),
        "coin": $('#coin').val(),
        "type": $('#type').val(),
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
          if (!result.status) {
            $('#modalSuccessfulBooking').modal('hide');
            toastr.error(result.message);
          } else {
            $('#modalSuccessfulBooking').modal('hide');
          }
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

  STUDENT_BOOK_LESSON.bookSchedule = function () {
    $('#btnBookSchedule').on("click", function () {
      var start_hour = $('#start_hour').val();
      var start_date = $('#start_date').val();

      if (!checkCoin) {
        $('#modalNotificationWhenLackOfCoin').modal('show');
        return false;
      }

      var fd = new FormData();
      fd.append('start_hour', $('#start_hour').val());
      fd.append('start_date', $('#start_date').val());
      fd.append('course_id', $('#start_course_id').val());
      fd.append('lesson_id', $('#start_lesson_id').val()); // fd.append('bookingId', $('#booking_id').val());

      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotification,
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          if (!result.status) {
            if (result.data === 'expired') {
              $('#area_message').html("\n                                            <section class=\"content-header\">\n                                                <div class=\"alert alert-danger alert-dismissible\">\n                                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                                    <i class=\"icon fa fa-ban\"></i>\n                                                     ".concat(result.message, "\n                                                </div>\n                                        </section>\n                                    "));
              $('#btnBookSchedule').attr('disabled', 'disabled');
              $('#btnBookSchedule').html(textDontLesson);
              $('#btn_get_teacher_schedule').attr('disabled', 'disabled');
            } else {
              $('#modalSuddenTeacher').modal('hide');
              $('#modalFailedBooking').modal('show');
            }
          } else {
            if (result.data.expired) {
              $('#btnBookSchedule').html(textDontLesson);
              $('#btnBookSchedule').attr('disabled', 'disabled');
              $('#modalLessonUnavailableNow').modal('show');
              return false;
            }

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

  STUDENT_BOOK_LESSON.cancelSchedule = function () {
    $('#btnCancelSchedule').click(function () {
      checkCloseModalConfirm = true;
      var fd = new FormData();
      fd.append('start_hour', $('#start_hour').val());
      fd.append('start_date', $('#start_date').val());
      fd.append('course_id', $('#course_id').val());
      fd.append('lesson_id', $('#lesson_id').val());
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotificationWhenCanceled,
        data: fd,
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
    $('#modalSuccessfulBooking').on('hide.bs.modal', function () {
      if (checkCloseModalConfirm) {
        checkCloseModalConfirm = false;
        return true;
      }

      var fd = new FormData();
      fd.append('start_hour', $('#start_hour').val());
      fd.append('start_date', $('#start_date').val());
      fd.append('course_id', $('#course_id').val());
      fd.append('lesson_id', $('#lesson_id').val());
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: routePushNotificationWhenCanceled,
        data: fd,
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {
          toastr.error(result.message);
        }
      });
    });
  };
});
$(document).ready(function () {
  STUDENT_BOOK_LESSON.init();
});

/***/ }),

/***/ 29:
/*!**************************************************************************!*\
  !*** multi ./resources/js/admin/students/lessons/student_book_lesson.js ***!
  \**************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\lessons\student_book_lesson.js */"./resources/js/admin/students/lessons/student_book_lesson.js");


/***/ })

/******/ });