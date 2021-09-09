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
/******/ 	return __webpack_require__(__webpack_require__.s = 19);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/lessons/lessonHistory.js":
/*!**************************************************************!*\
  !*** ./resources/js/admin/managers/lessons/lessonHistory.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_LESSON_HISTORY = {};
var csrf_token = $("[name=csrf-token]").attr('content');
var routeGetNameById = $("[name=route-get-nickname-by-id]").attr('content');
var routeGetNameByEmail = $("[name=route-get-nickname-by-email]").attr('content');
var routeValidationSearch = $("[name=route-validation-search]").attr('content');
var routeExportToExcel = $("[name=route-export-to-excel]").attr('content');
var msgConfirm = 'エクセルに出力しますか？';
$(function () {
  MANAGER_LESSON_HISTORY.init = function () {
    MANAGER_LESSON_HISTORY.selectById();
    MANAGER_LESSON_HISTORY.selectByEmail();
    MANAGER_LESSON_HISTORY.clickSearch();
    MANAGER_LESSON_HISTORY.clearForm();
    MANAGER_LESSON_HISTORY.exportToExcel();
    MANAGER_LESSON_HISTORY.formatDate();
  };

  MANAGER_LESSON_HISTORY.formatDate = function (date) {
    day = date.getDate() > 10 ? '/' + date.getDate() : '/0' + date.getDate();
    month = date.getMonth() + 1 > 10 ? '/' + (date.getMonth() + 1) : '/0' + (date.getMonth() + 1);
    year = date.getFullYear();
    return year + month + day;
  };

  MANAGER_LESSON_HISTORY.exportToExcel = function () {
    $('#exportExcel').click(function () {
      common.bootboxConfirm(msgConfirm, 'small', function (r) {
        if (r) {
          if ($('#teacher_id').val().length > 0) {
            $.each($('#teacher_id').val(), function (index, value) {
              $('#formExportToExcel').append("<input name=\"teacher_id[]\" hidden value=\"".concat(value, "\">"));
            });
          }

          if ($('#teacher_email').val().length > 0) {
            $.each($('#teacher_email').val(), function (index, value) {
              $('#formExportToExcel').append("<input name=\"teacher_email[]\" hidden value=\"".concat(value, "\">"));
            });
          }

          if ($('#date_from').val() != "") {
            $('#formExportToExcel').append("<input name=\"date_from\" hidden value=\"".concat($('#date_from').val(), "\">"));
          }

          if ($('#date_to').val() != "") {
            $('#formExportToExcel').append("<input name=\"date_to\" hidden value=\"".concat($('#date_to').val(), "\">"));
          }

          $('#formExportToExcel').submit();
        }
      });
    });
  };

  MANAGER_LESSON_HISTORY.selectById = function () {
    $('#teacher_id').select2({
      language: 'ja',
      ajax: {
        url: routeGetNameById,
        dataType: 'json',
        delay: 100,
        processResults: function processResults(data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.nickname,
                id: item.id
              };
            })
          };
        },
        cache: true
      }
    });
  };

  MANAGER_LESSON_HISTORY.selectByEmail = function () {
    $('#teacher_email').select2({
      language: 'ja',
      ajax: {
        url: routeGetNameByEmail,
        dataType: 'json',
        delay: 100,
        processResults: function processResults(data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.email,
                id: item.id
              };
            })
          };
        },
        cache: true
      }
    });
  };

  MANAGER_LESSON_HISTORY.clickSearch = function () {
    $('#btnSearch').click(function () {
      $("#error_section").css('display', 'none');
      var invalid = true;
      $('#searchLessonHistories').find('input').each(function () {
        if ($(this).val() !== '') {
          invalid = false;
        } else if ($('.itemTeacherId').val().length !== 0) {
          invalid = false;
        } else if ($('.itemTeacherEmail').val().length !== 0) {
          invalid = false;
        }
      });

      if (invalid) {
        $("#error_mes").html('検索項目を入力してください。');
        $("#error_section").css('display', 'block');
        return false;
      } // Clear error


      $('body').find('input').removeClass('is-invalid');
      $(".invalid-feedback-custom").html(''); // Get data form search teacher

      var data = $("#formSearchLessonHistories").serialize(); // Ajax

      $.ajax({
        type: "POST",
        url: routeValidationSearch,
        data: data,
        success: function success(result) {
          if (result.status) {
            $('#statistics').DataTable().draw(true);
            $('#lessonHistories').DataTable().draw(true);
          } else {
            $.each(result.message, function (key, value) {
              $.each(result.message, function (key, value) {
                if (key === "date_to") {
                  $('.date_to').addClass('is-invalid');
                  $('#err_date').html(value[0]);
                } else if (key === "format_date_from") {
                  $('.format_date_from').addClass('is-invalid');
                  $('#format_date_from').html(value[0]);
                } else if (key === "format_date_to") {
                  $('.format_date_to').addClass('is-invalid');
                  $('#format_date_to').html(value[0]);
                }
              });
            });
          }
        },
        error: function error(_error) {
          alert("Error server");
        }
      });
    });
  };

  MANAGER_LESSON_HISTORY.setEmptyValue = function () {
    $('#date_to').val('');
    $('#date_from').val('');
    $('#teacher_id').val('');
    $('#teacher_email').val('');
    $('.select2-selection__choice').remove();
  };

  MANAGER_LESSON_HISTORY.clearForm = function () {
    $('#btnClearForm').click(function () {
      location.reload(); // MANAGER_LESSON_HISTORY.setEmptyValue();
      // var date = new Date();
      // var firstDay = new Date(date.getFullYear(), date.getMonth(), 1)
      // $('#date_to').val(MANAGER_LESSON_HISTORY.formatDate(date));
      // $('#date_from').val(MANAGER_LESSON_HISTORY.formatDate(firstDay));
      // $("#error_section").css('display', 'none');
      // $('#lessonHistories').DataTable().draw(true);
      // $('#statistics').DataTable().draw(true);
    });
  };
});
$(document).ready(function () {
  MANAGER_LESSON_HISTORY.init();
});

/***/ }),

/***/ 19:
/*!********************************************************************!*\
  !*** multi ./resources/js/admin/managers/lessons/lessonHistory.js ***!
  \********************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\lessons\lessonHistory.js */"./resources/js/admin/managers/lessons/lessonHistory.js");


/***/ })

/******/ });