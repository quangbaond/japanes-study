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
/******/ 	return __webpack_require__(__webpack_require__.s = 13);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/teachers/mypage.js":
/*!***********************************************!*\
  !*** ./resources/js/admin/teachers/mypage.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var TEACHER_MY_PAGE = {};
var RouteGetTodaySchedule = $("[name=routeGetTodaySchedule]").attr('content');
var RouteNotifyToStudent = $("[name=route-notify-student-start-lesson]").attr('content');
var RouteNotifyTeacherCancelLesson = $("[name=route-notify-teacher-cancel-lesson]").attr('content');
var RouteNotifyTeacherStartLesson = $("[name=route-notify-teacher-start-lesson]").attr('content');
var teacher_wait_student_message = $("[name=wait-student-message]").attr('content');
var csrf_token = $("[name=csrf-token]").attr('content');
var student_id = 0;
var schedule_time = "00:00:00";
var schedule_id = null;
var countTime = null;
$(function () {
  TEACHER_MY_PAGE.init = function () {
    TEACHER_MY_PAGE.todayScheduleDatatable();
    TEACHER_MY_PAGE.notifyToInviteStudent();
    TEACHER_MY_PAGE.notifyTeacherCancel();
    TEACHER_MY_PAGE.clickButtonNotify();
    TEACHER_MY_PAGE.notifyTeacherStartLesson();
    TEACHER_MY_PAGE.showVideo();
  };

  TEACHER_MY_PAGE.showVideo = function () {
    $('body').on('click', 'tbody td .btnShowVideo', function () {
      var videoLink = $(this).attr('data-video_link');
      var html = "<video id=\"clip\" controls preload=auto playsinline muted autoplay class=\"intro-video\" data-setup=\"{}\">\n                        <source src=\"".concat(videoLink, "\" type='video/mp4'/>\n                    </video>");
      $('#modalShowVideo').find('.modal-body').html(html);
      $('#modalShowVideo').modal('show');
    });
  };

  TEACHER_MY_PAGE.clickButtonNotify = function () {
    //channel notify to teacher if student dont click start-lesson button after 5 minutes or click button start-lesson
    var channel_notification_teacher_when_student_start_lesson = pusher.subscribe('notification-student-notify-teacher-' + $("#user_login").val());
    channel_notification_teacher_when_student_start_lesson.bind('my-event', function (data) {
      $(document).ready(function () {
        if (data.type === 1) {
          $.removeCookie('notification_to_student_start_lesson', {
            path: '/'
          });
          clearTimeout(countTime);
          $('#loading').addClass('d-none');
          $('#loading').removeClass('d-block');
          $('#message_join_meeting').removeClass('d-none');
          $('#modal_teacher_start_lesson').modal('hide');
          $('#zoom_link').attr('href', data.zoom_url);
          window.open(data.zoom_url, '_blank');
          location.reload();
        }
      });
    });
  };

  TEACHER_MY_PAGE.todayScheduleDatatable = function () {
    $('#today-schedule').DataTable({
      "paging": false,
      "lengthChange": false,
      "searching": false,
      "ordering": false,
      "info": false,
      "autoWidth": false,
      "responsive": true,
      "language": {
        "url": "/Japanese.json"
      },
      ajax: {
        url: RouteGetTodaySchedule,
        type: 'GET'
      },
      columns: [{
        data: 'start_hour',
        render: function render(data) {
          time = data.split(':');
          return time[0] + ":" + time[1];
        }
      }, {
        data: 'student_id'
      }, {
        data: 'user_nickname'
      }, {
        data: 'user_email'
      }, {
        data: 'course_name'
      }, {
        data: 'lesson_name'
      }, {
        data: 'actions'
      } // {data: 'video_link',},
      // {data: 'btn_start_lesson_now',},
      ],
      "columnDefs": [{
        "className": "dt-center",
        "targets": "_all"
      }]
    });
  };

  TEACHER_MY_PAGE.notifyToInviteStudent = function () {
    $('#today-schedule tbody').on('click', 'td button', function (e) {
      start_time = e.target.value;
      var before_time = new Date();
      before_time.setTime(before_time.getTime() + 5 * 60000);
      before_time = before_time.getTime();
      var after_time = new Date();
      after_time.setTime(after_time.getTime() - 10 * 60000);
      after_time = after_time.getTime();
      var today = new Date();
      var today_dd = today.getDate();
      var today_mm = today.getMonth() + 1;
      var today_yyyy = today.getFullYear();

      if (today_dd < 10) {
        today_dd = '0' + today_dd;
      }

      if (today_mm < 10) {
        today_mm = '0' + today_mm;
      }

      start_time = today_yyyy + '-' + today_mm + '-' + today_dd + " " + start_time;
      start_time = new Date(start_time.replace(' ', 'T')).getTime();

      if (start_time > after_time && start_time < before_time) {
        var formData = new FormData();
        formData.append('student_id', e.target.name);
        formData.append('teacher_id', $('#user_login').val());
        $.ajaxSetup({
          headers: {
            "X-CSRF-TOKEN": csrf_token
          }
        });
        $.ajax({
          type: "POST",
          url: RouteNotifyToStudent,
          data: formData,
          cache: false,
          contentType: false,
          processData: false,
          success: function success(result) {
            student_id = e.target.name;
            schedule_time = e.target.value;
            schedule_id = e.target.getAttribute('content');
            $('#modal_teacher_start_lesson').modal('show');
          },
          error: function error(result) {}
        });
      } else {
        $(this).attr('disabled', 'disabled');
        $('#modalLessonUnavailableNow').modal('show');
      }
    });
  };

  TEACHER_MY_PAGE.notifyTeacherCancel = function () {
    $('#btn_teacher_cancel').click(function () {
      var formdata = new FormData();
      formdata.append('student_id', student_id);
      formdata.append('teacher_id', $('#user_login').val());
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: RouteNotifyTeacherCancelLesson,
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          student_id = 0;
        },
        error: function error(result) {}
      });
    });
  };

  TEACHER_MY_PAGE.notifyTeacherStartLesson = function () {
    $('#btn_teacher_start').click(function () {
      var formdata = new FormData();
      formdata.append('student_id', student_id);
      formdata.append('teacher_id', $('#user_login').val());
      formdata.append('schedule_time', schedule_time);
      formdata.append('schedule_id', schedule_id);
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: "POST",
        url: RouteNotifyTeacherStartLesson,
        data: formdata,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          var dataCookies = {
            "student_id": student_id,
            "teacher_id": $('#user_login').val(),
            "schedule_id": schedule_id
          };
          var expDate = new Date();

          var data_cookies = _objectSpread(_objectSpread({}, dataCookies), {}, {
            'expires': expDate.getTime() + 300000
          });

          expDate.setTime(expDate.getTime() + 300000); // add 5 minutes

          $.cookie('notification_to_student_start_lesson', JSON.stringify(data_cookies), {
            path: '/',
            expires: expDate
          });
          $('#modal_teacher_start_lesson').modal('hide');
          student_id = 0;
          $('#loading').addClass('d-block');
          $('#loading').removeClass('d-none');
          $('#loading_message').html(teacher_wait_student_message);
          countTime = setTimeout(function () {
            $('#loading').addClass('d-none');
            $('#loading').removeClass('d-block');
            $('#loading_message').html("");
            $('#modal_after_5_minutes').modal('show');
            $('#modal_teacher_start_lesson').modal('hide');
          }, 300000);
        },
        error: function error(result) {}
      });
    });
  };
});
$(document).ready(function () {
  TEACHER_MY_PAGE.init();
});

/***/ }),

/***/ 13:
/*!*****************************************************!*\
  !*** multi ./resources/js/admin/teachers/mypage.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\teachers\mypage.js */"./resources/js/admin/teachers/mypage.js");


/***/ })

/******/ });