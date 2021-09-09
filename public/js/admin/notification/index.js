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
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/notification/index.js":
/*!**************************************************!*\
  !*** ./resources/js/admin/notification/index.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var NOTIFICATION = {};
var routeStartMeetingRoom = $("[name=route-start-meeting-room]").attr('content');
var routeCancelMeetingRoom = $("[name=route-cancel-meeting-room]").attr('content');
var routeNotifyStartLesson = $("[name=routeNotifyStartLesson]").attr('content');
var routeTeacherMyPage = $("[name=route-teacher-mypage]").attr('content');
var routStudentBookingList = $("[name=route-student-booking-list]").attr('content');
var routeNotifyAfter5Minutes = $("[name=routeNotifyAfter5Minutes]").attr('content');
var routeNotifyToTeacher = $("[name=routeNotifyToTeacher]").attr('content');
var messageTeacherStatus = $("[name=message-teacher-status]").attr('content');
var messageTeacherInviteJoinLesson = $("[name=msg-teacher-invite-join-lesson]").attr('content');
var messageTeacherCancelLesson = $("[name=msg-teacher-cancel-lesson]").attr('content');
var routeNotification = $("[name=routeNotification]").attr('content');
var csrf_token = $("[name=csrf-token]").attr('content');
var timeoutOfTeacher;
var M040_content = $("[name=M040_content]").attr('content');
var M040_title = $("[name=M040_title]").attr('content');
var BOOKING_STATUS = 1;
var BOOKED_STATUS = 2;
var CANCEL_STATUS = 3;
var TEACHER_START_MEETING_ROOM_STATUS = 1;
var TEACHER_CANCEL_MEETING_ROOM_STATUS = 2;
var TEACHER_START_LESSON_STATUS = 3;
var countTime = null;
var toggleModal = null;
var checkCloseModalConfirm = false;
var teacher_id_notify = 0;
var schedule_time = "00:00:00";
var schedule_id = null;
$(function () {
  NOTIFICATION.init = function () {
    NOTIFICATION.pushNotification();
    NOTIFICATION.startMeeting();
    NOTIFICATION.cancelMeeting();
    NOTIFICATION.clickStartLesson();
    NOTIFICATION.notify();
    $('.notification-active').first().addClass('active');
  };

  NOTIFICATION.notify = function () {
    var data = $.cookie('notification');

    if (typeof data !== 'undefined') {
      data = JSON.parse(data);
      var expDate = new Date();
      var time = data.expires - expDate.getTime();
      $('#start_hour').val(data.start_hour);
      $('#start_date').val(data.start_date);
      $('#lesson_id').val(data.lesson_id);
      $('#course_id').val(data.course_id);
      $('#student_id').val(data.student_id);
      $('#coin').val(data.coin);
      $('#type').val(data.type);
      $('#book_type').val(data.book_type);
      $('#modalNotificationWhenBooked').modal({
        backdrop: 'static'
      });
      $('#modalNotificationWhenBooked').modal('show');

      window.onbeforeunload = function (e) {
        // cancelRequest();
        $('#modalNotificationWhenBooked').modal('hide');
        var fd = new FormData();
        fd.append('student_id', $('#student_id').val());
        fd.append('start_hour', $('#start_hour').val());
        fd.append('start_date', $('#start_date').val());
        fd.append('lesson_id', $('#lesson_id').val());
        fd.append('course_id', $('#course_id').val());
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: routeCancelMeetingRoom,
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          success: function success(result) {},
          error: function error(result) {// console.log(result);
          }
        });
        return null;
      };

      timeoutOfTeacher = setTimeout(function () {
        // cancelRequest();
        $('#modalNotificationWhenBooked').modal('hide');
        var fd = new FormData();
        fd.append('student_id', $('#student_id').val());
        fd.append('start_hour', $('#start_hour').val());
        fd.append('start_date', $('#start_date').val());
        fd.append('lesson_id', $('#lesson_id').val());
        fd.append('course_id', $('#course_id').val());
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "POST",
          url: routeCancelMeetingRoom,
          data: fd,
          cache: false,
          contentType: false,
          processData: false,
          success: function success(result) {},
          error: function error(result) {// console.log(result);
          }
        });
      }, time);
      data_to_cancel = data;
    }
  };

  NOTIFICATION.pushNotification = function () {
    var channel_user = pusher.subscribe('notification-user-' + $("#user_login").val());
    channel_user.bind('my-event', function (data) {
      //Add 1 for count_unread_notification
      var count_unread_notification = $("#count_unread_notification").text();
      $("#count_unread_notification").text(parseInt(count_unread_notification) + 1);
      var count_record = $('#element_notification .dropdown-item').length;

      if (count_record >= 10) {
        $('#element_notification .dropdown-item:last-child').remove();
      } // url notication detail


      routeNotification = routeNotification.replace(':id', data.id); // Add detail notification

      var html = "\n                    <div class=\"dropdown-divider\"></div>\n                    <a href=\"".concat(routeNotification, "\"  class=\"dropdown-item choice_notification\" data-id=\"").concat(data.id, "\">\n                        <i class=\"fas fa-envelope mr-2\"></i><strong>").concat(data.title, "</strong>\n                        <span class=\"float-right text-muted text-sm\">").concat(data.created_at, "</span>\n                    </a>\n                ");
      $("#element_notification").prepend(html);
      $(document).Toasts('create', {
        "class": 'bg-warning',
        title: data.title,
        subtitle: data.created_at,
        // body: "Nội dung: " + data.content,
        icon: 'fas fa-envelope fa-lg',
        autohide: true,
        delay: 5000
      });
      $('#toastsContainerTopRight').on('click', function () {
        window.location.href = routeNotification;
      });
    }); // let channel_all_user = pusher.subscribe('notification-all-user');
    // channel_all_user.bind('my-event', function(data) {
    //     $(document).Toasts('create', {
    //         class: 'bg-info',
    //         icon: 'fas fa-envelope fa-lg',
    //         title: data.title,
    //         subtitle: data.created_at,
    //         autohide: true,
    //         delay: 5000,
    //     })
    // });

    var channel_teacher_is_booked = pusher.subscribe('notification-open-lesson-teacher-' + $("#user_login").val());
    channel_teacher_is_booked.bind('my-event', function (data) {
      $(document).ready(function () {
        if (data.type === BOOKING_STATUS) {
          $(document).Toasts('create', {
            "class": 'bg-info',
            title: 'お知らせ',
            body: "今すぐレッスンするようにリクエスト生徒がいます。",
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 5000
          });
        } else if (data.type === BOOKED_STATUS) {
          $.removeCookie('notification_to_student_start_lesson', {
            path: '/'
          });
          $('#loading').addClass('d-none');
          $('#loading').removeClass('d-block');
          $('#message_join_meeting').removeClass('d-none');
          var expDate = new Date(); // console.log(data.data);

          var data_cookies = _objectSpread(_objectSpread({}, data.data), {}, {
            'expires': expDate.getTime() + 3 * 60 * 1000
          });

          expDate.setTime(expDate.getTime() + 3 * 60 * 1000); // add 3 minutes

          $.cookie('notification', JSON.stringify(data_cookies), {
            path: '/',
            expires: expDate
          });
          $('#start_hour').val(data.data.start_hour);
          $('#start_date').val(data.data.start_date);
          $('#lesson_id').val(data.data.lesson_id);
          $('#course_id').val(data.data.course_id);
          $('#student_id').val(data.data.student_id);
          $('#coin').val(data.data.coin);
          $('#type').val(data.data.type);
          $('#type').val(data.data.type);
          $('#book_type').val(data.data.book_type);
          $('#teacher_id').val(data.data.teacher_id);
          $('#modalNotificationWhenBooked').modal({
            backdrop: 'static'
          });
          $('#modalNotificationWhenBooked').modal('show');

          window.onbeforeunload = function (e) {
            // cancelRequest();
            $('#modalNotificationWhenBooked').modal('hide');
            var fd = new FormData();
            fd.append('student_id', $('#student_id').val());
            fd.append('start_hour', $('#start_hour').val());
            fd.append('start_date', $('#start_date').val());
            fd.append('lesson_id', $('#lesson_id').val());
            fd.append('course_id', $('#course_id').val());
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
              type: "POST",
              url: routeCancelMeetingRoom,
              data: fd,
              cache: false,
              contentType: false,
              processData: false,
              success: function success(result) {},
              error: function error(result) {// console.log(result);
              }
            });
            return null;
          };

          timeoutOfTeacher = setTimeout(function () {
            // cancelRequest();
            $('#modalNotificationWhenBooked').modal('hide');
            var fd = new FormData();
            fd.append('student_id', $('#student_id').val());
            fd.append('start_hour', $('#start_hour').val());
            fd.append('start_date', $('#start_date').val());
            fd.append('lesson_id', $('#lesson_id').val());
            fd.append('course_id', $('#course_id').val());
            $.ajaxSetup({
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              }
            });
            $.ajax({
              type: "POST",
              url: routeCancelMeetingRoom,
              data: fd,
              cache: false,
              contentType: false,
              processData: false,
              success: function success(result) {},
              error: function error(result) {// console.log(result);
              }
            });
          }, 60000 * 3); //60s * 3p

          data_to_cancel = data.data;
        } else if (data.type === CANCEL_STATUS) {
          $(document).Toasts('create', {
            "class": 'bg-warning',
            title: 'お知らせ',
            body: "生徒がリクエストをキャンセルしました。",
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 5000
          });
        }
      });
    });
    var channel_student_when_started_room_by_teacher = pusher.subscribe('notification-open-lesson-student-' + $("#user_login").val());
    channel_student_when_started_room_by_teacher.bind('my-event', function (data) {
      $(document).ready(function () {
        if (data.type === TEACHER_START_MEETING_ROOM_STATUS) {
          $('#loading_wait_teacher').hide();
          $('#zoom_link').attr('href', data.data);
          $('#message_join_meeting').removeClass('d-none');
          $('#message_join_meeting').addClass('d-block');
          $('#btnBookSchedule').html(messageTeacherStatus);
          $('#btnBookSchedule').attr('disabled', 'disabled');
          $('#btnCreateTeacher').attr('disabled', 'disabled');
          window.open(data.data, '_blank');
          $.removeCookie('notification_student_sudden_lesson', {
            path: '/'
          });
          $.removeCookie('notification_student_booked', {
            path: '/'
          });

          if (window.location.pathname === '/student/lesson/list') {
            window.location.reload();
          }
        }

        if (data.type === TEACHER_CANCEL_MEETING_ROOM_STATUS) {
          var data_sudden = $.cookie('notification_student_sudden_lesson');
          var data_booked = $.cookie('notification_student_booked');

          if (typeof data_sudden !== 'undefined' || typeof data_booked !== 'undefined') {
            $.removeCookie('notification_student_sudden_lesson', {
              path: '/'
            });
            $.removeCookie('notification_student_booked', {
              path: '/'
            });
            $('#loading_wait_teacher').hide();
            $('#modalCancelRequest').modal('show');
          }
        }
      });
    }); //channel notify to student when teacher start lesson on teacher's my-page

    var channel_notification_teacher_start_lesson_to_student = pusher.subscribe('notification-student-teacher-my-page-' + $("#user_login").val());
    channel_notification_teacher_start_lesson_to_student.bind('my-event', function (data) {
      $(document).ready(function () {
        teacher_id_notify = data.teacher_id;

        if (data.type === TEACHER_START_MEETING_ROOM_STATUS) {
          $(document).Toasts('create', {
            "class": 'bg-info',
            title: 'Notification',
            body: "".concat(messageTeacherInviteJoinLesson),
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 5000
          });
          $('#modalSuddenTeacher').modal('hide'); // $('#modal_teacher_start_lesson').modal('hide');
          // $('#modal_teacher_invite_join_lesson').modal('show');
          // $('#modal_teacher_cancel_lesson').modal('hide');
        } else if (data.type === TEACHER_CANCEL_MEETING_ROOM_STATUS) {
          $(document).Toasts('create', {
            "class": 'bg-warning',
            title: 'Notification',
            body: "".concat(messageTeacherCancelLesson),
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 5000
          });
          $('#modalSuddenTeacher').modal('hide'); // $('#modal_teacher_start_lesson').modal('hide');
          // $('#modal_teacher_invite_join_lesson').modal('hide');
          // $('#modal_teacher_cancel_lesson').modal('show');
        } else if (data.type === TEACHER_START_LESSON_STATUS) {
          console.log(data);
          $('#modal_review_lesson').modal('hide');
          $('#modalSuddenTeacher').modal('hide');
          $.removeCookie('notification_student_sudden_lesson', {
            path: '/'
          });
          $.removeCookie('notification_student_booked', {
            path: '/'
          });
          $('#loading_wait_teacher').hide();
          var dataCookies = {
            "student_id": $('#user_login').val(),
            "schedule_id": data.schedule_id,
            'teacher_id': data.teacher_id,
            'schedule_time': data.schedule_time
          };
          var expDate = new Date();

          var data_cookies = _objectSpread(_objectSpread({}, dataCookies), {}, {
            'expires': expDate.getTime() + 300000
          });

          expDate.setTime(expDate.getTime() + 300000); // add 5 minutes

          $.cookie('notification_require_student_join_lesson', JSON.stringify(data_cookies), {
            path: '/',
            expires: expDate
          });
          $('#loading').addClass('d-none');
          $('#loading').removeClass('d-block');
          $('#modal_teacher_start_lesson').modal({
            backdrop: 'static'
          });
          $('#modal_teacher_start_lesson').modal('show'); // $('#modal_teacher_invite_join_lesson').modal('hide');
          // $('#modal_teacher_cancel_lesson').modal('hide');

          schedule_time = data.schedule_time;
          schedule_id = data.schedule_id;
          toggleModal = setTimeout(function () {
            // NOTIFICATION.notifyTeacherDontStartLessonAfter5minutes();
            $('#modal_teacher_start_lesson').modal('hide');
          }, 300000);
        }
      });
    });
    var channel_notification_teacher_when_student_start_lesson = pusher.subscribe('notification-student-notify-teacher-' + $("#user_login").val());
    channel_notification_teacher_when_student_start_lesson.bind('my-event', function (data) {
      $(document).ready(function () {
        if (data.type === 4) {
          $(document).Toasts('create', {
            "class": 'bg-info',
            title: "".concat(data.data.title),
            body: "<a href=\"".concat(routeTeacherMyPage, "\" style=\"color: white\">").concat(data.data.content, "</a>"),
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 10000
          });
        }
      });
    });
    var channel_admin_notify_to_student_and_teacher = pusher.subscribe('notification-admin-notify-' + $("#user_login").val());
    channel_admin_notify_to_student_and_teacher.bind('my-event', function (data) {
      $(document).ready(function () {
        // console.log(data);
        if (data.type === 1) {
          $(document).Toasts('create', {
            "class": 'bg-info',
            title: "".concat(M040_title),
            body: "<a href=\"".concat(routStudentBookingList, "\" style=\"color: white\">").concat(M040_content, "</a>"),
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 10000
          });
        }

        if (data.type === 2) {
          $(document).Toasts('create', {
            "class": 'bg-info',
            title: "".concat(M040_title),
            body: "<a href=\"".concat(routeTeacherMyPage, "\" style=\"color: white\">").concat(M040_content, "</a>"),
            icon: 'fas fa-envelope fa-lg',
            autohide: true,
            delay: 10000
          });
        }
      });
    });
  };

  NOTIFICATION.notifyTeacherStudentStartLesson = function () {
    $("#loading").removeClass('d-none');
    $('#loading').addClass("d-block");
    var data = $.cookie('notification_require_student_join_lesson');
    data = JSON.parse(data);
    var fd = new FormData();
    fd.append('teacher_id', data.teacher_id);
    fd.append('schedule_time', data.schedule_time);
    fd.append('schedule_id', data.schedule_id);
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": csrf_token
      }
    });
    $.ajax({
      type: "POST",
      url: routeNotifyStartLesson,
      data: fd,
      cache: false,
      contentType: false,
      processData: false,
      success: function success(result) {
        $('#modal_teacher_start_lesson').modal('hide');
        $("#loading").removeClass('d-block');
        $('#loading').addClass("d-none");
        $('#zoom_link').attr('href', result.data.zoom_url);
        $('#message_join_meeting').removeClass('d-none');
        window.open(result.data.zoom_url, '_blank');
        $.removeCookie('notification_require_student_join_lesson', {
          path: '/'
        });
      },
      error: function error(result) {}
    });
  };

  NOTIFICATION.clickStartLesson = function () {
    $('#btn_student_start_lesson').click(function () {
      clearTimeout(toggleModal);
      clearTimeout(countTime);
      NOTIFICATION.notifyTeacherStudentStartLesson();
    });
  }; //start meeting room of teacher
  //write here because i can not import js file


  NOTIFICATION.startMeeting = function () {
    $('#startMeetingWithStudent').click(function () {
      $.removeCookie('notification', {
        path: '/'
      });
      $("#loading").removeClass('d-none');
      $('#loading').addClass("d-block");
      clearTimeout(timeoutOfTeacher);
      checkCloseModalConfirm = true;
      var fd = new FormData();
      fd.append('start_hour', $('#start_hour').val());
      fd.append('start_date', $('#start_date').val());
      fd.append('course_id', $('#course_id').val());
      fd.append('lesson_id', $('#lesson_id').val());
      fd.append('student_id', $('#student_id').val());
      fd.append('coin', $('#coin').val());
      fd.append('type', $('#type').val());
      fd.append('book_type', $('#book_type').val());
      fd.append('teacher_id', $('#teacher_id').val());
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        url: routeStartMeetingRoom,
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          $('#modalNotificationWhenBooked').modal('hide');
          $("#loading").removeClass('d-block');
          $('#loading').addClass("d-none");

          if (!result.status) {// console.log(result);
          } else {
            $('#zoom_link').attr('href', result.data.data);
            $('#message_join_meeting').removeClass('d-none');
            $('#message_join_meeting').addClass('d-block');
            window.open(result.data.data, '_blank');
          }
        },
        error: function error(result) {// console.log(result);
        }
      });
    });
  };

  NOTIFICATION.cancelMeeting = function () {
    $('#modalNotificationWhenBooked').on('hide.bs.modal', function () {
      if (checkCloseModalConfirm === true) {
        checkCloseModalConfirm = false;
        return true;
      }

      $.removeCookie('notification', {
        path: '/'
      });
      var fd = new FormData();
      fd.append('student_id', $('#student_id').val());
      fd.append('start_hour', $('#start_hour').val());
      fd.append('start_date', $('#start_date').val());
      fd.append('lesson_id', $('#lesson_id').val());
      fd.append('course_id', $('#course_id').val());
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        type: "POST",
        url: routeCancelMeetingRoom,
        data: fd,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {},
        error: function error(result) {// console.log(result);
        }
      });
    });
  };
});
$(document).ready(function () {
  NOTIFICATION.init();
});

/***/ }),

/***/ 3:
/*!********************************************************!*\
  !*** multi ./resources/js/admin/notification/index.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\notification\index.js */"./resources/js/admin/notification/index.js");


/***/ })

/******/ });