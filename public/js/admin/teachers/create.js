/******/ (function(modules) { // webpackBootstrap
/******/  // The module cache
/******/  var installedModules = {};
/******/
/******/  // The require function
/******/  function __webpack_require__(moduleId) {
/******/
/******/    // Check if module is in cache
/******/    if(installedModules[moduleId]) {
/******/      return installedModules[moduleId].exports;
/******/    }
/******/    // Create a new module (and put it into the cache)
/******/    var module = installedModules[moduleId] = {
/******/      i: moduleId,
/******/      l: false,
/******/      exports: {}
/******/    };
/******/
/******/    // Execute the module function
/******/    modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/    // Flag the module as loaded
/******/    module.l = true;
/******/
/******/    // Return the exports of the module
/******/    return module.exports;
/******/  }
/******/
/******/
/******/  // expose the modules object (__webpack_modules__)
/******/  __webpack_require__.m = modules;
/******/
/******/  // expose the module cache
/******/  __webpack_require__.c = installedModules;
/******/
/******/  // define getter function for harmony exports
/******/  __webpack_require__.d = function(exports, name, getter) {
/******/    if(!__webpack_require__.o(exports, name)) {
/******/      Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/    }
/******/  };
/******/
/******/  // define __esModule on exports
/******/  __webpack_require__.r = function(exports) {
/******/    if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/      Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/    }
/******/    Object.defineProperty(exports, '__esModule', { value: true });
/******/  };
/******/
/******/  // create a fake namespace object
/******/  // mode & 1: value is a module id, require it
/******/  // mode & 2: merge all properties of value into the ns
/******/  // mode & 4: return value when already ns object
/******/  // mode & 8|1: behave like require
/******/  __webpack_require__.t = function(value, mode) {
/******/    if(mode & 1) value = __webpack_require__(value);
/******/    if(mode & 8) return value;
/******/    if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/    var ns = Object.create(null);
/******/    __webpack_require__.r(ns);
/******/    Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/    if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/    return ns;
/******/  };
/******/
/******/  // getDefaultExport function for compatibility with non-harmony modules
/******/  __webpack_require__.n = function(module) {
/******/    var getter = module && module.__esModule ?
/******/      function getDefault() { return module['default']; } :
/******/      function getModuleExports() { return module; };
/******/    __webpack_require__.d(getter, 'a', getter);
/******/    return getter;
/******/  };
/******/
/******/  // Object.prototype.hasOwnProperty.call
/******/  __webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/  // __webpack_public_path__
/******/  __webpack_require__.p = "/";
/******/
/******/
/******/  // Load entry module and return exports
/******/  return __webpack_require__(__webpack_require__.s = 9);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/create.js":
/*!***********************************************!*\
  !*** ./resources/js/admin/students/create.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var Days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31]; // index => month [0-11]

$(document).ready(function () {
  var option = '<option value="0" selected></option>';
  //var option = "";
  var selectedDay = "day";

  for (var i = 1; i <= Days[0]; i++) {
    option += '<option value="' + i + '">' + i + '</option>';
  }

  $('#day').append(option);
  $('#day').val(selectedDay);
  var option = '<option value="0" selected></option>';
  //var option = "";
  var selectedMon = "month";

  for (var _i = 1; _i <= 12; _i++) {
    option += '<option value="' + _i + '">' + _i + '</option>';
  }

  $('#month').append(option);
  $('#month').val(selectedMon);
  var d = new Date();
  var option = '<option value="0" selected></option>';
  //var option = "";
  var selectedYear = "year";

  for (var _i2 = 1930; _i2 <= d.getFullYear(); _i2++) {
    option += '<option value="' + _i2 + '">' + _i2 + '</option>';
  }

  $('#year').append(option);
  $('#year').val(selectedYear);
});

function isLeapYear(year) {
  year = parseInt(year);

  if (year % 4 != 0) {
    return false;
  } else if (year % 400 == 0) {
    return true;
  } else if (year % 100 == 0) {
    return false;
  } else {
    return true;
  }
}

function change_year(select) {
  if (isLeapYear($(select).val())) {
    Days[1] = 29;
  } else {
    Days[1] = 28;
  }

  if ($("#month").val() == 2) {
    var day = $('#day');
    var val = $(day).val();
    $(day).empty();
    var option = '<option value="day"></option>';

    for (var i = 1; i <= Days[1]; i++) {
      option += '<option value="' + i + '">' + i + '</option>';
    }

    $(day).append(option);

    if (val > Days[month]) {
      val = 1;
    }

    $(day).val(val);
  }
}

function change_month(select) {
  var day = $('#day');
  var val = $(day).val();
  $(day).empty();
  var option = '<option value="day"></option>';
  var month = parseInt($(select).val()) - 1;

  for (var i = 1; i <= Days[month]; i++) {
    option += '<option value="' + i + '">' + i + '</option>';
  }

  $(day).append(option);

  if (val > Days[month]) {
    val = 1;
  }

  $(day).val(val);
}

/***/ }),

/***/ 9:
/*!*****************************************************!*\
  !*** multi ./resources/js/admin/students/create.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Applications/XAMPP/xamppfiles/htdocs/Apache-project/MCREW-TECH/JapaneseStudy-Server/Source_Code/resources/js/admin/students/create.js */"./resources/js/admin/students/create.js");


/***/ })

/******/ });
 $(document).ready(function () {
    // Choice image
    $("#choice_image").click(function () {
        $('#image_url').trigger('click');
    });

    // Change image
    $("#image_url").change(function () {
        if (this.files && this.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                if (validImage("#image_url")) {
                    $('#image').attr('src', e.target.result);
                } else {
                    alert('画像形式が正しくありません。')
                }
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
    // Valid Image
    function validImage(file_id) {
        let fileExtension = ['jpg', 'jpeg', 'png'];
        let valid = true;
        let msg = "";
        if ($(file_id).val() == '') {
            valid = false;
        } else {
            var fileName = $(file_id).val();
            var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
            if ($.inArray(fileNameExt, fileExtension) == -1) {
                valid = false;
            }
        }
        return valid //true or false
    }
});