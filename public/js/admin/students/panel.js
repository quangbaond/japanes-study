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
/******/ 	return __webpack_require__(__webpack_require__.s = 36);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/panel.js":
/*!**********************************************!*\
  !*** ./resources/js/admin/students/panel.js ***!
  \**********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var STUDENT_UPDATE_COURSE = {};
var RouteUpdateCourse = $("[name=routeUpdateCourseLesson]").attr('content');
$(function () {
  STUDENT_UPDATE_COURSE.init = function () {
    STUDENT_UPDATE_COURSE.selectChange();
    STUDENT_UPDATE_COURSE.clickbtnSubmit();
    STUDENT_UPDATE_COURSE.showPopupJoinLesson();
  };

  STUDENT_UPDATE_COURSE.showPopupJoinLesson = function () {
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

  STUDENT_UPDATE_COURSE.selectChange = function () {
    $('#course_id').change(function (e) {
      course_id = $('#course_id').val(); // Ajax validation

      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      e.preventDefault();
      $.ajax({
        type: "POST",
        url: RouteUpdateCourse,
        data: course_id,
        cache: false,
        contentType: false,
        processData: false,
        success: function success(result) {
          if (result.status) {
            if (result.data == 'next_lesson') {
              lesson_name = result.message.lesson_name;
              $('#lesson_name').val(lesson_name);
            }
          }
        },
        error: function error(error) {// alert("Error");
        }
      });
    });
  };

  STUDENT_UPDATE_COURSE.clickbtnSubmit = function () {
    $('#btnSubmit').click(function () {
      var name = $("#course_id  option:selected").text();
      $('#current_course').html(name);
      $('#next_lesson').html($('#lesson_name').val());
    });
  };
});
$(document).ready(function () {
  STUDENT_UPDATE_COURSE.init();
});

/***/ }),

/***/ 36:
/*!****************************************************!*\
  !*** multi ./resources/js/admin/students/panel.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\panel.js */"./resources/js/admin/students/panel.js");


/***/ })

/******/ });