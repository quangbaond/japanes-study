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
/******/ 	return __webpack_require__(__webpack_require__.s = 47);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/AdminList/index.js":
/*!********************************************************!*\
  !*** ./resources/js/admin/managers/AdminList/index.js ***!
  \********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var ADMINLIST = {};
var routeGetListAdmins = $("[name=route-get-list-admins]").attr('content');
var routeValidateSearchForm = $("[name=route-validate-search-form]").attr('content');
var routeDeleteAdmins = $("[name=route-delete-admins]").attr('content');
var notificationConfirmDelete = $("[name=confirm-delete]").attr('content');
var messageDeleteSuccess = $("[name=delete-success]").attr('content');
$(function () {
  ADMINLIST.init = function () {
    ADMINLIST.checkRecord();
    ADMINLIST.listAdmin();
    ADMINLIST.validateSearchForm();
    ADMINLIST.clearForm();
    ADMINLIST.deleteAdmins();
  };

  ADMINLIST.listAdmin = function () {
    $(document).ready(function () {
      $('#admins').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "responsive": true,
        "pagingType": "full_numbers",
        "order": [[8, "desc"]],
        'autoWidth': false,
        language: {
          "url": "/Japanese.json"
        },
        processing: true,
        serverSide: true,
        ajax: {
          url: routeGetListAdmins,
          type: 'GET',
          data: function data(d) {
            d.email = $('#email').val();
            d.admin_id = $('#admin_id').val();
            d.phone_number = $('#phone_number').val();
            d.area_code = $('#area_code option:selected').val();
            d.role = $('#role option:selected').val();
            d.from_date = $('#from_date').val();
            d.to_date = $('#to_date').val();
          }
        },
        columns: [{
          data: 'checkbox',
          name: 'checkbox',
          orderable: false,
          searchable: false
        }, {
          data: 'id',
          name: 'id'
        }, {
          data: 'nickname',
          name: 'nickname',
          "class": 'nickname'
        }, {
          data: 'email',
          name: 'email',
          "class": 'email'
        }, {
          data: 'phone_number',
          name: 'phone_number'
        }, {
          data: 'role',
          name: 'role'
        }, {
          data: 'created_at',
          name: 'created_at'
        }, {
          data: 'action',
          name: 'action',
          orderable: false
        }, {
          data: 'originalSearch',
          name: 'originalSearch',
          "visible": false
        }],
        "createdRow": function createdRow(row, data, rowIndex) {
          $.each($('td[class=" nickname"]', row), function (colIndex, data) {
            $(this).attr('data-toggle', "tooltip");
            $(this).attr('data-placement', "top");
            $(this).attr('data-original-title', $(data).html());
          });
          $.each($('td[class=" email"]', row), function (colIndex, data) {
            $(this).attr('data-toggle', "tooltip");
            $(this).attr('data-placement', "top");
            $(this).attr('data-original-title', $(data).html());
          });
        }
      });
    });
  };

  ADMINLIST.validateSearchForm = function () {
    $("#btnSearch").click(function () {
      if (checkInternet()) {
        $('#loading').removeClass('d-none');
        $('#loading').addClass('d-block');
        var invalid = true;
        $('#searchAdmins').find('select,input').each(function () {
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

        var data = $('#formSearchStudent').serializeArray(); // Clear error

        $('body').find('input').removeClass('is-invalid');
        $(".invalid-feedback-custom").html(''); // Get data form search teacher

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
              $.each(result.message, function (key, value) {
                if (typeof value !== "undefined") {
                  $('#' + key).addClass('is-invalid');
                  $('#' + key).closest('.form-group').find('span[role=alert]').html(value[0]);
                }
              });
            } else {
              //submit form
              $('#admins').DataTable().draw(true);
            }
          },
          error: function error(result) {
            $('#area_message').html("\n                            <section class=\"content-header\">\n                                <div class=\"alert alert-danger alert-dismissible\">\n                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                    <i class=\"icon fa fa-ban\"></i>\n                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                </div>\n                            </section>\n                        ");
          }
        });
      }
    });
  };

  ADMINLIST.checkRecord = function () {
    // check all
    $('#check_all').on('click', function (e) {
      var check = $(".chk_item");
      $("#formDeleteAllAdmin").find("input[name='user_id[]'").remove();

      if ($(this).prop("checked")) {
        if (check.length > 0) {
          $("#delete-all-admin").attr("disabled", false);
        }

        check.prop('checked', true);
        check.each(function () {
          $("#formDeleteAllAdmin").append('<input type="hidden" id="id-' + $(this).val() + '" name="user_id[]" value="' + $(this).val() + '">');
        });
      } else {
        check.prop('checked', false);
        $("#delete-all-admin").attr("disabled", true);
        check.each(function () {
          $("#id-" + $(this).val()).remove();
        });
      }
    });
    $('#admins').on('draw.dt', function () {
      $("#formDeleteAllAdmin").find("input[name='user_id[]'").remove();
      $("#check_all").prop('checked', false);
      $("#delete-all-admin").attr("disabled", true);
    }); //check item

    $("body").on("change", ".chk_item", function () {
      if (false == $(this).prop("checked")) {
        $("#check_all").prop('checked', false);
      }

      ;

      if ($('.chk_item:checked').length == $('.chk_item').length) {
        $("#check_all").prop('checked', true);
      }

      ;

      if ($(this).prop("checked")) {
        $("#formDeleteAllAdmin").append('<input type="hidden" id="id-' + $(this).val() + '" name="user_id[]" value="' + $(this).val() + '">');
      } else {
        $("#id-" + $(this).val()).remove();
      }

      if ($('.chk_item:checked').length > 0) {
        $("#delete-all-admin").attr("disabled", false);
      } else {
        $("#delete-all-admin").attr("disabled", true);
      }
    });
  };

  ADMINLIST.clearForm = function () {
    $('#btnClearForm').on('click', function () {
      var isThis = $(this);
      var formSearchClear = '.form-search-clear';
      common.clearValueFormSearch(isThis, formSearchClear);
      $("#error_section").css('display', 'none');
      $('#area_message').html('');
      $('#admins').DataTable().draw(true);
    });
  };

  ADMINLIST.deleteAdmins = function () {
    $('#delete-all-admin').on('click', function () {
      common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
        if (r) {
          if (checkInternet()) {
            $('#loading').removeClass('d-none');
            $('#loading').addClass('d-block');
            var data = $("#formDeleteAllAdmin").serialize(); // Ajax

            $.ajax({
              type: "POST",
              url: routeDeleteAdmins,
              data: data,
              success: function success(result) {
                $('#loading').removeClass('d-block');
                $('#loading').addClass('d-none');

                if (result.status) {
                  $('#area_message').html("\n                                    <section class=\"content-header\">\n                                        <div class=\"alert alert-success alert-dismissible\">\n                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                            <i class=\"icon fa fa-check\"></i>\n                                            ".concat(messageDeleteSuccess, "\n                                        </div>\n                                    </section>\n                                "));
                  $(window).scrollTop(0);
                  $('#admins').DataTable().draw(true);
                } else {
                  $('#area_message').html("\n                                            <section class=\"content-header\">\n                                                <div class=\"alert alert-danger alert-dismissible\">\n                                                    <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                                    <i class=\"icon fa fa-ban\"></i>\n                                                     \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                                </div>\n                                            </section>\n                                    ");
                }
              },
              error: function error(_error) {
                $('#area_message').html("\n                                    <section class=\"content-header\">\n                                        <div class=\"alert alert-danger alert-dismissible\">\n                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                            <i class=\"icon fa fa-ban\"></i>\n                                             \u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002\n                                        </div>\n                                    </section>\n                                ");
              }
            });
          }
        }
      });
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
  ADMINLIST.init();
});

/***/ }),

/***/ 47:
/*!**************************************************************!*\
  !*** multi ./resources/js/admin/managers/AdminList/index.js ***!
  \**************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\AdminList\index.js */"./resources/js/admin/managers/AdminList/index.js");


/***/ })

/******/ });