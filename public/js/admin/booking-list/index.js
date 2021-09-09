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
/******/ 	return __webpack_require__(__webpack_require__.s = 45);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/booking-list/index.js":
/*!**************************************************!*\
  !*** ./resources/js/admin/booking-list/index.js ***!
  \**************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_BOOKING_LIST = {};
var routeDataTables = $("[name=route-data-tables]").attr('content');
var routeSearchLiveNickname = $("[name=route-search-live-nickname]").attr('content');
var routeSearchLiveEmail = $("[name=route-search-live-email]").attr('content');
var routeValidateSearchForm = $('[name=route-search-form]').attr('content');
var routeGetBookingDetail = $('[name=route-get-booking-detail]').attr('content');
var routeDeleteBooking = $('[name=route-delete-booking]').attr('content');
$(function () {
  MANAGER_BOOKING_LIST.init = function () {
    MANAGER_BOOKING_LIST.dataTables();
    MANAGER_BOOKING_LIST.searchLive();
    MANAGER_BOOKING_LIST.searchForm();
    MANAGER_BOOKING_LIST.removeBooking();
  };

  MANAGER_BOOKING_LIST.dataTables = function () {
    var table = $('#bookingHistory').DataTable({
      'lengthChange': false,
      'searching': false,
      "order": [[8, "asc"]],
      'autoWidth': false,
      "pagingType": "full_numbers",
      language: {
        "url": "/Japanese.json"
      },
      processing: true,
      serverSide: true,
      ajax: {
        url: routeDataTables,
        type: 'GET',
        data: function data(d) {
          d.email = $('#email').val();
          d.nickname = $('#user_id').val();
          d.from_date = $('#from_date').val();
          d.to_date = $('#to_date').val();
        }
      },
      columns: [{
        data: 'start_date',
        name: 'start_date'
      }, {
        data: 'start_hour',
        name: 'start_hour',
        "class": 'start_hour'
      }, {
        data: 'teacher_id',
        name: 'teacher_id',
        "class": 'teacher_id'
      }, {
        data: 'teacher_email',
        name: 'teacher_email',
        "class": 'teacher_email'
      }, {
        data: 'student_id',
        name: 'student_id'
      }, {
        data: 'student_email',
        name: 'student_email',
        "class": 'student_email'
      }, {
        data: 'coin',
        name: 'coin '
      }, {
        data: 'action',
        name: 'action',
        orderable: false
      }, {
        data: 'created_at',
        name: 'created_at',
        "visible": false
      }, {
        data: 'booking_id',
        name: 'booking_id',
        "visible": false
      }],
      "createdRow": function createdRow(row, data, rowIndex) {
        $.each($('td[class=" teacher_email"]', row), function (colIndex, data) {
          $(this).attr('data-toggle', "tooltip");
          $(this).attr('data-placement', "top");
          $(this).attr('data-original-title', $(data).html());
        });
        $.each($('td[class=" student_email"]', row), function (colIndex, data) {
          $(this).attr('data-toggle', "tooltip");
          $(this).attr('data-placement', "top");
          $(this).attr('data-original-title', $(data).html());
        });
      }
    });
    $('#bookingHistory tbody').on('click', 'td button', function (e) {
      var data_row = table.row($(this).closest('tr')).data();
      var booking_id = data_row.booking_id;

      if (checkInternet()) {
        $('#loading').removeClass('d-none');
        $('#loading').addClass('d-block');
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "get",
          url: routeGetBookingDetail,
          data: {
            id: booking_id
          },
          success: function success(result) {
            $('#loading').removeClass('d-block');
            $('#loading').addClass('d-none');

            if (!result.status) {
              if (result.message.to_date) {
                $('#area_message').html("\n                                                    <section class=\"content-header\">\n                                                        <div class=\"alert alert-danger alert-dismissible\">\n                                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                                            <i class=\"icon fa fa-ban\"></i>\n                                                             \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                                        </div>\n                                                    </section>\n                                            ");
              }
            } else {
              var time = result.data.start_hour.split(':'); //submit form

              $('#booking_id').val(result.data.booking_id);
              $('#teacher_nickname').html(result.data.teacher_email);
              $('#student_nickname').html(result.data.student_email);
              $('#start_date').html(result.data.start_date);
              $('#start_hour').html(time[0] + ":" + time[1]);
              $('#coin').html(result.data.coin);
              $('#modalRemoveBookingByAdmin').modal('show');
            }
          },
          error: function error(result) {
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                    ");
          }
        });
      }
    });
  };

  MANAGER_BOOKING_LIST.searchLive = function () {
    $.fn.select2.defaults.set('language', {
      noResults: function noResults() {
        return " 該当がありません";
      },
      searching: function searching() {
        return "検索中";
      }
    });
    $('#user_id').select2({
      ajax: {
        url: routeSearchLiveNickname,
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
    $('#email').select2({
      ajax: {
        url: routeSearchLiveEmail,
        dataType: 'json',
        delay: 100,
        processResults: function processResults(data) {
          return {
            results: $.map(data, function (item) {
              return {
                text: item.email,
                id: item.email
              };
            })
          };
        },
        cache: true
      }
    });
  };

  MANAGER_BOOKING_LIST.searchForm = function () {
    $("#btnSearch").click(function () {
      if (checkInternet()) {
        $('#loading').removeClass('d-none');
        $('#loading').addClass('d-block');
        var invalid = true;
        $('#searchTeacher').find('select,input').each(function () {
          if ($(this).val() != '') {
            invalid = false;
          }
        });

        if (invalid) {
          $('#loading').removeClass('d-block');
          $('#loading').addClass('d-none');
          $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u691C\u7D22\u9805\u76EE\u3092\u5165\u529B\u3057\u3066\u304F\u3060\u3055\u3044\u3002\n                                </div>\n                            </section>\n                    ");
          return false;
        }

        var data = $('#searchForm').serializeArray(); //clear error

        $("#to_date").removeClass("is-invalid");
        $('#error_date').html("");
        $("#error_date").html("");
        $("#from_date").removeClass("is-invalid");
        $("#error_date").html("");
        $("#to_date").removeClass("is-invalid");
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "post",
          url: routeValidateSearchForm,
          data: data,
          success: function success(result) {
            $('#loading').removeClass('d-block');
            $('#loading').addClass('d-none');

            if (!result.status) {
              if (result.message.to_date) {
                $("#error_date").html(result.message.to_date);
                $("#to_date").addClass("is-invalid");
              }

              if (result.message.format_date_from) {
                $("#error_date").html(result.message.format_date_from);
                $("#from_date").addClass("is-invalid");
              }

              if (result.message.format_date_to) {
                $("#error_date").html(result.message.format_date_to);
                $("#to_date").addClass("is-invalid");
              }
            } else {
              //submit form
              $('#bookingHistory').DataTable().draw(true);
            }
          },
          error: function error(result) {
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                    ");
          }
        });
      }
    });
  };

  MANAGER_BOOKING_LIST.removeBooking = function () {
    $('#btnConfirmRemove').on('click', function (e) {
      var booking_id = $('#booking_id').val();

      if (checkInternet()) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          type: "post",
          url: routeDeleteBooking,
          data: {
            id: booking_id
          },
          success: function success(result) {
            $('#loading').removeClass('d-block');
            $('#loading').addClass('d-none');
            $('#modalRemoveBookingByAdmin').modal('hide');

            if (!result.status) {
              $('#area_message').html("\n                                        <section class=\"content-header\">\n                                            <div class=\"alert alert-danger alert-dismissible\">\n                                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                                <i class=\"icon fa fa-ban\"></i>\n                                                 ".concat(result.message, "\n                                            </div>\n                                        </section>\n                                    "));
            } else {
              $('#area_message').html("\n                                                <section class=\"content-header\">\n                                                    <div class=\"alert alert-success alert-dismissible\">\n                                                        <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                                        <i class=\"icon fa fa-check\"></i>\n                                                         ".concat(result.message, "\n                                                    </div>\n                                                </section>\n                                            "));
              $("html, body").animate({
                scrollTop: 0
              }, "slow"); //draw again

              $('#bookingHistory').DataTable().draw(true);
            }
          },
          error: function error(result) {
            $('#modalRemoveBookingByAdmin').modal('hide');
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                    ");
          }
        });
      }
    });
  };

  function checkInternet() {
    var ifConnected = window.navigator.onLine;

    if (ifConnected) {
      $('#area_message').html('');
      return true;
    } else {
      $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                        </section>\n                    ");
      $("html, body").animate({
        scrollTop: 0
      }, "slow");
    }
  }
});
$(document).ready(function () {
  MANAGER_BOOKING_LIST.init();
});

/***/ }),

/***/ 45:
/*!********************************************************!*\
  !*** multi ./resources/js/admin/booking-list/index.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\booking-list\index.js */"./resources/js/admin/booking-list/index.js");


/***/ })

/******/ });