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
/******/ 	return __webpack_require__(__webpack_require__.s = 40);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/student/app.js":
/*!*************************************!*\
  !*** ./resources/js/student/app.js ***!
  \*************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var APP = {};
var routeRequestCancel = $("[name=route-timeout]").attr('content');
$(function () {
  APP.init = function () {
    $('.select2').select2();
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    APP.waitingTeacherStartLesson();
    $('.bs-timepicker').timepicker();
    APP.datepicker();
  };

  APP.datepicker = function () {
    $(".datepicker").datepicker({
      format: 'yyyy/mm/dd',
      todayHighlight: true,
      autoclose: true
    });
  };

  APP.waitingTeacherStartLesson = function () {
    var data = $.cookie('notification_student_sudden_lesson');

    if (typeof data !== 'undefined') {
      data = JSON.parse(data);
      var expDate = new Date();
      var time = data.expires - expDate.getTime();
      $('#modalSuddenTeacher').modal('hide');
      $('#loading_wait_teacher').show();
      timeoutOfTeacher = setTimeout(function () {
        // cancelRequest();
        var formData = new FormData();
        formData.append('start_hour', data.start_hour);
        formData.append('start_date', data.start_date);
        formData.append('course_id', data.course_id);
        formData.append('lesson_id', data.lesson_id);
        formData.append('student_id', data.student_id);
        formData.append('coin', data.coin);
        formData.append('type', data.type);
        formData.append('teacher_id', data.teacher_id);
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
            $('#loading_wait_teacher').hide(); // if (typeof $.cookie('notification_student_sudden_lesson') !== 'undefined'){
            //     $('#modalCancelRequest').modal('show');
            // }

            $('#modalCancelRequest').modal('show');
          },
          error: function error(result) {}
        });
      }, time);
    } //case: start lesson at booking list so it will not change from booked to free time in teacher_schedule table


    var data_booked = $.cookie('notification_student_booked');

    if (typeof data_booked !== 'undefined') {
      data_booked = JSON.parse(data_booked);

      var _expDate = new Date();

      var _time = data_booked.expires - _expDate.getTime();

      $('#modalSuddenTeacher').modal('hide');
      $('#loading_wait_teacher').show();
      timeoutOfTeacher = setTimeout(function () {
        $('#loading_wait_teacher').hide();
        $('#modalCancelRequest').modal('show');
      }, _time);
    }
  };
});
$(document).ready(function () {
  APP.init();
});

/***/ }),

/***/ 40:
/*!*******************************************!*\
  !*** multi ./resources/js/student/app.js ***!
  \*******************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\student\app.js */"./resources/js/student/app.js");


/***/ })

/******/ });