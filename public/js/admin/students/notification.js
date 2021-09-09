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
/******/ 	return __webpack_require__(__webpack_require__.s = 35);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/students/notification.js":
/*!*****************************************************!*\
  !*** ./resources/js/admin/students/notification.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var STUDENT_NOTIFICATION = {};
var routeNotification = $("[name=route-notification-list]").attr('content');
var empty_table = $("[name=lang_table_empty_table]").attr('content');
var no_result = $("[name=lang_table_no_result]").attr('content');
$(function () {
  STUDENT_NOTIFICATION.init = function () {
    STUDENT_NOTIFICATION.Table();
    STUDENT_NOTIFICATION.eventInput();
    STUDENT_NOTIFICATION.validationNotification();
    STUDENT_NOTIFICATION.clearForm();
  };

  STUDENT_NOTIFICATION.Table = function () {
    $('#notifications').DataTable({
      drawCallback: function drawCallback() {
        var pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
        var info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
        pagination.toggle(this.api().page.info().pages > 0);
        info.toggle(this.api().page.info().pages > 0);
      },
      'lengthChange': false,
      'searching': false,
      "order": [[3, "desc"]],
      'autoWidth': false,
      "pagingType": "full_numbers",
      language: {
        paginate: {
          next: '>',
          // or '→'
          previous: '<',
          // or '←'
          sFirst: '<<',
          sLast: '>>'
        },
        "emptyTable": empty_table // "zeroRecords": "No match was found for your search",

      },
      processing: true,
      serverSide: true,
      ajax: {
        url: routeNotification,
        type: 'GET',
        data: function data(d) {
          d.title = $('#title').val();
          d.created_at_from = $('#created_at_from').val();
          d.created_at_to = $('#created_at_to').val();
        }
      },
      columnDefs: [{
        targets: 'no-sort',
        orderable: false
      }],
      columns: [{
        data: 'title',
        name: 'title',
        "class": 'title'
      }, {
        data: 'created_by',
        name: 'created_by',
        "class": "created_by"
      }, {
        data: 'user_created_at',
        name: 'user_created_at'
      }, {
        data: 'btn_notification_detail',
        name: 'btn_notification_detail',
        "class": "text-center"
      }],
      "createdRow": function createdRow(row, data, rowIndex) {
        $.each($('td[class=" title"]', row), function (colIndex, data) {
          $(this).attr('data-toggle', "tooltip");
          $(this).attr('data-placement', "top");
          $(this).attr('data-original-title', $(data).text());
        });
        $.each($('td[class=" created_by"]', row), function (colIndex, data) {
          $(this).attr('data-toggle', "tooltip");
          $(this).attr('data-placement', "top");
          $(this).attr('data-original-title', $(data).text());
        });
      }
    });
  };

  STUDENT_NOTIFICATION.eventInput = function () {
    $('#search').change(function (e) {
      var value = e.target.value;

      if (value == "created_at") {
        $('#inputCreated').removeClass('d-none');
        $('#inputTitle').addClass('d-none');
      } else if (value == "title") {
        $('#inputTitle').removeClass('d-none');
        $('#inputCreated').addClass('d-none');
      }
    });
  };

  STUDENT_NOTIFICATION.validationNotification = function () {
    $('#btnSearch').click(function () {
      $("#error_section").css('display', 'none');
      var invalid = true;
      $('#searchNotification').find('input').each(function () {
        if ($(this).val() != '') {
          invalid = false;
        } else if ($('.itemEmail').val() != null) {
          invalid = false;
        }
      });

      if (invalid) {
        $("#error_mes").html($('[name=error-input]').attr('content'));
        $("#error_section").css('display', 'block');
        return false;
      } // Clear error


      $('body').find('input').removeClass('is-invalid');
      $(".invalid-feedback-custom").html(''); // Get data form search teacher

      var data = $("#formSearchNotification").serialize(); // Ajax

      var routeNotificationValidation = $("[name=route-notification-validation]").attr('content');
      $.ajax({
        type: "POST",
        url: routeNotificationValidation,
        data: data,
        success: function success(result) {
          if (result.status) {
            $('#notifications').DataTable().draw(true);
            var table = $('#notifications').DataTable();
            table.on('draw', function () {
              $('.dataTables_empty').text(no_result);
            });
          } else {
            $.each(result.message, function (key, value) {
              if (key === "created_at_to") {
                $('.created_at_from').addClass('is-invalid');
                $('#format_created_at_from').html(value[0]);
              } else if (key === "format_created_at_from") {
                $('.format_created_at_from').addClass('is-invalid');
                $('#format_created_at_from').html(value[0]);
              } else if (key === "format_created_at_to") {
                $('.format_created_at_to').addClass('is-invalid');
                $('#format_created_at_to').html(value[0]);
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

  STUDENT_NOTIFICATION.clearForm = function () {
    $('#btnClearForm').click(function () {
      $('.invalid-feedback-custom').html("");
      $('form#formSearchNotification').trigger('reset');
      $('input[name=title]').val("");
      $('input[name=created_at_from]').val("");
      $('input[name=created_at_to]').val("");
      $('input').removeClass('is-invalid');
      $('#area_message').html('');
      $('#inputCreated').addClass('d-none');
      $('#inputTitle').removeClass('d-none');
      $('.itemName').val('').trigger('change');
      $('#notifications').DataTable().draw(true);
    });
  };
});
$(document).ready(function () {
  STUDENT_NOTIFICATION.init();
});

/***/ }),

/***/ 35:
/*!***********************************************************!*\
  !*** multi ./resources/js/admin/students/notification.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\students\notification.js */"./resources/js/admin/students/notification.js");


/***/ })

/******/ });