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
/******/ 	return __webpack_require__(__webpack_require__.s = 18);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/notification/detail.js":
/*!************************************************************!*\
  !*** ./resources/js/admin/managers/notification/detail.js ***!
  \************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_NOTIFICATION_DETAIL = {};
var routeNotificationValidation = $("[name=notification-validate]").attr('content');
var routeUpdateNotification = $("[name=notification-update]").attr('content');
var csrf_token = $("[name=csrf-token]").attr('content');
$(function () {
  MANAGER_NOTIFICATION_DETAIL.init = function () {
    MANAGER_NOTIFICATION_DETAIL.clickValidate();
    MANAGER_NOTIFICATION_DETAIL.clickClear();
  };

  MANAGER_NOTIFICATION_DETAIL.clearError = function () {
    $('#to_date').removeClass('is-invalid');
    $('#from_date').removeClass('is-invalid');
    $('#title').removeClass('is-invalid');
    $('#content').removeClass('is-invalid');
    $('#format_date_from').html('');
    $('#format_date_to').html('');
    $('#title_err').html('');
    $('#content_err').html('');
  };

  MANAGER_NOTIFICATION_DETAIL.clickClear = function () {
    $('#btnClear').click(function () {
      location.reload();
    });
  };

  MANAGER_NOTIFICATION_DETAIL.updateNotification = function () {
    var data = $('#formUpdateNotification').serialize();
    $.ajaxSetup({
      headers: {
        "X-CSRF-TOKEN": csrf_token
      }
    });
    $.ajax({
      type: 'POST',
      url: routeUpdateNotification,
      data: data,
      success: function success(result) {
        $('#to_list_notification').submit();
      },
      error: function error(_error) {
        alert("Error server");
      }
    });
  };

  MANAGER_NOTIFICATION_DETAIL.clickValidate = function () {
    $('#btnSubmit').click(function () {
      MANAGER_NOTIFICATION_DETAIL.clearError();
      var data = $('#formUpdateNotification').serialize();
      $.ajaxSetup({
        headers: {
          "X-CSRF-TOKEN": csrf_token
        }
      });
      $.ajax({
        type: 'POST',
        url: routeNotificationValidation,
        data: data,
        success: function success(result) {
          if (result.status) {
            MANAGER_NOTIFICATION_DETAIL.updateNotification();
          } else {
            console.log(result.message);
            $.each(result.message, function (key, value) {
              if (key === "to_date") {
                $('#to_date').addClass('is-invalid');
                $('#format_date_to').html(value[0]);
              } else if (key === "from_date") {
                $('#from_date').addClass('is-invalid');
                $('#format_date_from').html(value[0]);
              } else if (key === "format_date_from") {
                $('#from_date').addClass('is-invalid');
                $('#format_date_from').html(value[0]);
              } else if (key === "format_date_to") {
                $('#to_date').addClass('is-invalid');
                $('#format_date_to').html(value[0]);
              } else if (key === "title") {
                $('#title').addClass('is-invalid');
                $('#title_err').html(value[0]);
              } else if (key === "content") {
                $('#content').addClass('is-invalid');
                $('#content_err').html(value[0]);
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
});
$(document).ready(function () {
  MANAGER_NOTIFICATION_DETAIL.init();
});

/***/ }),

/***/ 18:
/*!******************************************************************!*\
  !*** multi ./resources/js/admin/managers/notification/detail.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\notification\detail.js */"./resources/js/admin/managers/notification/detail.js");


/***/ })

/******/ });