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
/******/ 	return __webpack_require__(__webpack_require__.s = 1);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/app.js":
/*!***********************************!*\
  !*** ./resources/js/admin/app.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var APP = {};
var bootBoxConfirmTrue = $("[name=bootbox-confirm-true]").attr('content');
var bootBoxConfirmFalse = $("[name=bootbox-confirm-false]").attr('content');
$(function () {
  APP.init = function () {
    $('.select2').select2();
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
    APP.choiceNotification();
    APP.datepicker();
    APP.formatVisaCard();
  };

  APP.choiceNotification = function () {
    $("body").on('click', '.choice_notification', function () {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      var formData = {
        id: $(this).data("id")
      };
      $(this).remove();
      $.ajax({
        type: "GET",
        url: '/notification/detail',
        data: formData,
        success: function success(result) {
          console.log(result);

          if (result.status) {
            $('#modal_notification').modal('show');
            $('#modal_notification').find('.modal-content').html(result.data['content']); //Add 1 for count_unread_notification

            if (result.data['read_at'] == "0") {
              var count_unread_notification = $("#count_unread_notification").text();
              $("#count_unread_notification").text(parseInt(count_unread_notification) - 1);
            }
          } else {
            alert("Error");
          }
        },
        error: function error(result) {
          console.log(result);
        }
      });
    });
  };

  APP.datepicker = function () {
    $(".datepicker").datepicker({
      format: 'yyyy/mm/dd',
      todayHighlight: true,
      autoClose: true,
      forceParse: false // keepInvalid:true
      // autocomplete:false,

    });
  };
  /*
     Function format card visa for input
  */


  APP.formatVisaCard = function () {
    $(".format_visa").keypress(function (e) {
      if ((e.which < 48 || e.which > 57) && e.which !== 8 && e.which !== 0) {
        return false;
      }

      var value = APP.formatNumber($(this).val());
      $(this).val(value);
    });
  };
  /*
     Format number for input
  */


  APP.formatNumber = function (value) {
    var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
    var matches = v.match(/\d{4,16}/g);
    var match = matches && matches[0] || '';
    var parts = [];

    for (i = 0, len = match.length; i < len; i += 4) {
      parts.push(match.substring(i, i + 4));
    }

    if (parts.length) {
      return parts.join(' ');
    } else {
      return value;
    }
  };
});
$(document).ready(function () {
  APP.init();
});
window.common = {
  bootboxConfirm: function bootboxConfirm(msg, size, _callback) {
    bootbox.confirm({
      message: msg,
      size: size,
      buttons: {
        confirm: {
          label: 'はい',
          className: 'btn-primary btn-sm mr-6 btn-flat'
        },
        cancel: {
          label: 'いいえ',
          className: 'btn-default btn-sm btn-flat'
        }
      },
      callback: function callback(result) {
        _callback(result);
      }
    });
  },
  bootboxConfirmMultiLanguage: function bootboxConfirmMultiLanguage(msg, size, _callback2) {
    bootbox.confirm({
      message: msg,
      size: size,
      buttons: {
        confirm: {
          label: bootBoxConfirmTrue,
          className: 'btn-primary btn-sm btn-flat'
        },
        cancel: {
          label: bootBoxConfirmFalse,
          className: 'btn-default btn-sm btn-flat'
        }
      },
      callback: function callback(result) {
        _callback2(result);
      }
    });
  },
  bootboxAlert: function bootboxAlert(msg, size) {
    bootbox.alert({
      message: msg,
      size: size,
      buttons: {
        ok: {
          label: 'OK',
          className: 'btn-default btn-sm btn-flat'
        }
      }
    });
  },
  clearValueFormSearch: function clearValueFormSearch(isThis, formSearchClear) {
    isThis.closest(formSearchClear).find('.select2').val(null).trigger('change');
    isThis.closest(formSearchClear).find('input, select').val('');
    isThis.closest(formSearchClear).find('input').removeClass('is-invalid');
    isThis.closest(formSearchClear).find('.invalid-feedback-custom').html('');
  },
  getToken: function getToken() {
    return $("[name=csrf-token]").attr('content');
  }
};

/***/ }),

/***/ 1:
/*!*****************************************!*\
  !*** multi ./resources/js/admin/app.js ***!
  \*****************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\app.js */"./resources/js/admin/app.js");


/***/ })

/******/ });