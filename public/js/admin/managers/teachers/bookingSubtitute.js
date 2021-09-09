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
/******/ 	return __webpack_require__(__webpack_require__.s = 11);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/teachers/bookingSubtitute.js":
/*!******************************************************************!*\
  !*** ./resources/js/admin/managers/teachers/bookingSubtitute.js ***!
  \******************************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _createForOfIteratorHelper(o, allowArrayLike) { var it; if (typeof Symbol === "undefined" || o[Symbol.iterator] == null) { if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") { if (it) o = it; var i = 0; var F = function F() {}; return { s: F, n: function n() { if (i >= o.length) return { done: true }; return { done: false, value: o[i++] }; }, e: function e(_e) { throw _e; }, f: F }; } throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); } var normalCompletion = true, didErr = false, err; return { s: function s() { it = o[Symbol.iterator](); }, n: function n() { var step = it.next(); normalCompletion = step.done; return step; }, e: function e(_e2) { didErr = true; err = _e2; }, f: function f() { try { if (!normalCompletion && it["return"] != null) it["return"](); } finally { if (didErr) throw err; } } }; }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var MANAGER_TEACHER_BOOKING_SUBTUTE = {};
$(function () {
  var Arr_BOOKING = [];

  MANAGER_TEACHER_BOOKING_SUBTUTE.init = function () {
    MANAGER_TEACHER_BOOKING_SUBTUTE.confirmBooking();
  };

  MANAGER_TEACHER_BOOKING_SUBTUTE.confirmBooking = function () {
    $('.bs-timepicker').click(function (e) {
      console.log(e);
      var temp = e.target.id;
      $("#".concat(temp)).toggleClass('btn-warning');
      var hasClass = $("#".concat(temp)).hasClass('btn-warning');
      var time = $("#".concat(temp)).val();
      var dateTime = $("#".concat(temp)).parents("tr:first").children("td:first").children().text();
      var row = $("#".concat(temp)).parent().attr('id');
      console.log(row);

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
    $('#comfirm__booking').html(MANAGER_TEACHER_BOOKING_SUBTUTE.displayPopup(Arr_BOOKING));
  });

  MANAGER_TEACHER_BOOKING_SUBTUTE.displayPopup = function (OBJ) {
    if (OBJ.length < 0) return;
    var html = "";

    for (var i = 0; i < OBJ.length; i++) {
      var text = "";
      var classText = "";
      console.log(OBJ[i]);

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

          text += " <input type=\"text\" id=\"timepicker1-0\"\n                class=\"  btn btn-warning mr-lg-5 mb-3 mt-1  timepicker1\"\n                style=\"width: 65px\" value=\"".concat(k, "\" readonly=\"\">");
        }
      } catch (err) {
        _iterator.e(err);
      } finally {
        _iterator.f();
      }

      html += "<tr>\n                    <th hidden=\"\"></th>\n                    <td style=\"width: 150px\">\n                        <div class=\"justify-content-center \">\n                            <span class=\"".concat(classText, "\">").concat(OBJ[i].date, "</span>\n                        </div>\n                    </td>\n                    <td>\n                        <div class=\"d-flex justify-content-between mt-1 addButton\" id=\"divRow1\">\n                            <div class=\"row ml-2\" id=\"row1\">\n                                <div class=\"d-flex\" id=\"divTimepicker1-0\">\n                                    <div class=\"mb-2 mr-2\" id=\"removeTimepicker1-0\">\n                                    </div>\n                                   ").concat(text, "\n                                </div>\n                            </div>\n                        </div>\n                    </td>\n                </tr>");
    }

    return html;
  };
});
$(document).ready(function () {
  MANAGER_TEACHER_BOOKING_SUBTUTE.init();
});

/***/ }),

/***/ 11:
/*!************************************************************************!*\
  !*** multi ./resources/js/admin/managers/teachers/bookingSubtitute.js ***!
  \************************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Xampp1\htdocs\PHP-MCrew\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\teachers\bookingSubtitute.js */"./resources/js/admin/managers/teachers/bookingSubtitute.js");


/***/ })

/******/ });