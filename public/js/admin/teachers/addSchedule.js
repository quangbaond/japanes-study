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
/******/ 	return __webpack_require__(__webpack_require__.s = 31);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/teachers/addSchedule.js":
/*!****************************************************!*\
  !*** ./resources/js/admin/teachers/addSchedule.js ***!
  \****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var TEACHER_ADD_SCHEDULE = {};
var getRemoveImage = $("[name=removeImage]").attr('content');
var M007 = $("[name=M007]").attr('content');
var RouteValidateSchedule = $("[name=routeValidateSchedule]").attr('content');
var lengthArray = [];
var lengthTemp = [];
$(function () {
  TEACHER_ADD_SCHEDULE.init = function () {
    TEACHER_ADD_SCHEDULE.countNumofTimepicker();
    TEACHER_ADD_SCHEDULE.clickButtonAddTimepicker();
    TEACHER_ADD_SCHEDULE.clickRemoveTimepicker();
    TEACHER_ADD_SCHEDULE.clickButtonSubmit();
    TEACHER_ADD_SCHEDULE.getScheduleData();
    TEACHER_ADD_SCHEDULE.clickButtonClear();
  };

  TEACHER_ADD_SCHEDULE.countNumofTimepicker = function () {
    $('.bs-timepicker').timepicker();

    for (var i = 0; i <= 6; i++) {
      var length = $(".timepicker".concat(i + 1)).length;
      lengthArray[i] = length - 1;
      lengthTemp[i] = length - 1;
    }
  };

  TEACHER_ADD_SCHEDULE.clickRemoveTimepicker = function () {
    for (var i = 1; i <= 7; i++) {
      $("#".concat(i, "-0")).click(function () {
        $("#divTimepicker".concat(this.id)).remove();
        index = this.id.split('-')[0];
        lengthTemp[index - 1]--;
      });
    }
  };

  TEACHER_ADD_SCHEDULE.clickButtonAddTimepicker = function () {
    $('.btnAddTimePicker').click(function () {
      // console.log("lengthArray: " + lengthArray);
      // var string = (Math.floor(Math.random() * 23) + 1) + ':' + (Math.floor(Math.random() * 59) + 1)
      // console.log(string);
      var index = this.id.split('button')[1];

      if (lengthTemp[index - 1] <= 22) {
        var temp = "<div class=\"d-flex\" id=\"divTimepicker".concat(index, "-").concat(lengthArray[index - 1] + 1, "\">\n                            <div class=\"mb-2 mr-2\" id=\"removeTimepicker").concat(index, "-").concat(lengthArray[index - 1] + 1, "\">\n                            </div>\n                            <input type=\"text\"\n                                id=\"timepicker").concat(index, "-").concat(lengthArray[index - 1] + 1, "\"\n                                class=\"bs-timepicker border border-dark mr-lg-5 mb-3 mt-1 text-center\"\n                                style=\"width: 65px\" value=\"\" readonly/>\n                            </div>");
        $("#row".concat(index)).append(temp);
        temp = "<img class=\"removeTimepicker position-absolute\" style=\"width: 15px; height: 15px\"\n                    src=\"".concat(getRemoveImage, "\" id=\"").concat(index, "-").concat(lengthArray[index - 1] + 1, "\">");
        $("#removeTimepicker".concat(index, "-").concat(lengthArray[index - 1] + 1)).append(temp);
        $('.bs-timepicker').timepicker();
        $("#".concat(index, "-").concat(lengthArray[index - 1] + 1)).click(function () {
          $("#divTimepicker".concat(this.id)).remove();
          index = this.id.split('-')[0];
          lengthTemp[index - 1]--;
        });
        lengthArray[index - 1]++;
        lengthTemp[index - 1]++;
      } // console.log("lengthTemp: " + lengthTemp);

    });
  };

  TEACHER_ADD_SCHEDULE.getScheduleData = function () {
    var data = new Array();
    var data1 = new FormData();

    for (var i = 1; i <= 7; i++) {
      var temp = {};

      for (var j = 0; j <= lengthArray[i - 1]; j++) {
        if ($("#timepicker".concat(i, "-").concat(j)).val() != null) {
          temp["".concat(i, "-").concat(j)] = $("#timepicker".concat(i, "-").concat(j)).val();
        }
      }

      data.push(temp);
    }

    return JSON.stringify(data);
  };

  TEACHER_ADD_SCHEDULE.unFailMark = function (array) {
    for (var i = 1; i <= 7; i++) {
      for (var j = 0; j <= lengthArray[i - 1]; j++) {
        if ($.inArray("".concat(i, "-").concat(j), array) == -1) {
          if ($("#timepicker".concat(i, "-").concat(j)).hasClass('border-danger')) {
            $("#timepicker".concat(i, "-").concat(j)).removeClass('border-danger');
            $("#timepicker".concat(i, "-").concat(j)).addClass('border-dark');
          }
        }
      }
    }
  };

  TEACHER_ADD_SCHEDULE.clickButtonSubmit = function () {
    $('#btnSubmit').click(function (e) {
      $('#loading').addClass('d-block');

      if (navigator.onLine) {
        // Ajax validation
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        e.preventDefault();
        var data = TEACHER_ADD_SCHEDULE.getScheduleData();
        $.ajax({
          type: "POST",
          url: RouteValidateSchedule,
          data: data,
          cache: false,
          contentType: false,
          processData: false,
          dataType: 'JSON',
          success: function success(result) {
            if (!result.status) {
              if (result.data == 'scheduleFailed') {
                $('#loading').removeClass('d-block');
                TEACHER_ADD_SCHEDULE.addMessageErrorCommon(result.message['msgErr']);
                TEACHER_ADD_SCHEDULE.unFailMark(result.message);
                $("html, body").animate({
                  scrollTop: 0
                }, "slow");
                $.each(result.message, function (index, value) {
                  if (index != "msgErr") {
                    console.log("#timepicker".concat(value));
                    $("#timepicker".concat(value)).removeClass('border-dark');
                    $("#timepicker".concat(value)).addClass('border-danger');
                  }
                });
              } else if (result.data == 'error') {
                $('#loading').removeClass('d-block');
                TEACHER_ADD_SCHEDULE.addMessageErrorCommon(result.message);
                $("html, body").animate({
                  scrollTop: 0
                }, "slow");
              }
            } else {
              $('#listSchedule').submit();
            }
          },
          error: function error(error) {
            $('#loading').removeClass('d-block');
            alert("Error Server");
          }
        });
      } else {
        TEACHER_ADD_SCHEDULE.addMessageErrorCommon(M007);
      }
    });
  };

  TEACHER_ADD_SCHEDULE.clickButtonClear = function () {
    $('#btnClear').click(function (e) {
      for (var i = 1; i <= 7; i++) {
        $("#row".concat(i)).html('');
        var index = i;
        var temp = "<div class=\"d-flex\" id=\"divTimepicker".concat(index, "-0\">\n                            <div class=\"mb-2 mr-2\" id=\"removeTimepicker").concat(index, "-0\">\n                            </div>\n                            <input type=\"text\"\n                                id=\"timepicker").concat(index, "-0\"\n                                class=\"bs-timepicker border border-dark mr-lg-5 mb-3 mt-1 text-center timepicker").concat(i, "\"\n                                style=\"width: 65px\" value=\"\" readonly/>\n                            </div>");
        $("#row".concat(index)).append(temp);
        temp = "<img class=\"removeTimepicker position-absolute\" style=\"width: 15px; height: 15px\"\n                    src=\"".concat(getRemoveImage, "\" id=\"").concat(index, "-0\">");
        $("#removeTimepicker".concat(index, "-0")).append(temp);
        $("#".concat(index, "-0")).click(function () {
          $("#divTimepicker".concat(this.id)).remove();
        });
      }

      $('.bs-timepicker').timepicker();
      TEACHER_ADD_SCHEDULE.countNumofTimepicker();
      console.log("2 ", lengthTemp);
      $('#error_section').html('');
      $("html, body").animate({
        scrollTop: 0
      }, "slow");
    });
  };

  TEACHER_ADD_SCHEDULE.addMessageErrorCommon = function (message) {
    $('#error_section').html("\n            <div class=\"alert alert-danger alert-dismissible\">\n                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                <i class=\"icon fa fa-ban\"></i>\n                <span id=\"error_mes\">".concat(message, "</span>\n            </div>\n        "));
  };
});
$(document).ready(function () {
  TEACHER_ADD_SCHEDULE.init();
});

/***/ }),

/***/ 31:
/*!**********************************************************!*\
  !*** multi ./resources/js/admin/teachers/addSchedule.js ***!
  \**********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\teachers\addSchedule.js */"./resources/js/admin/teachers/addSchedule.js");


/***/ })

/******/ });