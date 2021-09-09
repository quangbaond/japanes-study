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
/******/ 	return __webpack_require__(__webpack_require__.s = 15);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/admin/notification.js":
/*!***********************************************************!*\
  !*** ./resources/js/admin/managers/admin/notification.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var ADMIN_NOTIFICATION = {};
$(function () {
  ADMIN_NOTIFICATION.init = function () {
    ADMIN_NOTIFICATION.customTable();
    ADMIN_NOTIFICATION.eventCheckAll();
  };

  ADMIN_NOTIFICATION.customTable = function () {
    var oTable = $('#search-notification').DataTable({
      // ajax: 'https://gyrocode.github.io/files/jquery-datatables/arrays_id.json',
      language: {
        paginate: {
          next: '<i class="fas fa-arrow-right"></i>',
          previous: '<i class="fas fa-arrow-left"></i>'
        },
        sInfo: "_END_ / _TOTAL_"
      },
      lengthChange: false,
      columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
      }],
      order: [[1, 'asc']]
    }); // search tabel

    $('#input-search').keyup(function () {
      oTable.search($(this).val()).draw();
    });
  };

  ADMIN_NOTIFICATION.eventCheckAll = function () {
    $("#check_all").change(function () {
      $('input:checkbox').not(this).prop('checked', this.checked);
    });
    $(".checkSingle").click(function () {
      if ($(this).is(":checked")) {
        var isAllChecked = 0;
        $(".checkSingle").each(function () {
          if (!this.checked) isAllChecked = 1;
        });

        if (isAllChecked == 0) {
          $("#check_all").prop("checked", true);
        }
      } else {
        $("#check_all").prop("checked", false);
      }
    }); // table changer content

    $("#search-notification").bind("DOMSubtreeModified", function () {
      var countRecord = $('tr').length;
      var atLeastOneIsChecked = $('.checkSingle:checked').length + 1;

      if (countRecord == atLeastOneIsChecked) {
        $("#check_all").prop("checked", true);
      } else {
        $("#check_all").prop("checked", false);
      }
    });
  };
});
$(document).ready(function () {
  ADMIN_NOTIFICATION.init();
});

/***/ }),

/***/ 15:
/*!*****************************************************************!*\
  !*** multi ./resources/js/admin/managers/admin/notification.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Applications/XAMPP/xamppfiles/htdocs/Apache-project/MCREW-TECH/JapaneseStudy-Server/Source_Code/resources/js/admin/managers/admin/notification.js */"./resources/js/admin/managers/admin/notification.js");


/***/ })

/******/ });