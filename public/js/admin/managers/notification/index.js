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
/******/ 	return __webpack_require__(__webpack_require__.s = 17);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/notification/index.js":
/*!***********************************************************!*\
  !*** ./resources/js/admin/managers/notification/index.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_NOTIFICATION_INDEX = {};
var routeNotificationValidation = $("[name=route-notification-validation]").attr('content');
var msgConfirm = $("[name=delete-confirm]").attr('content');
var msgDeleteSuccess = $("[name=delete-success]").attr('content');
var routeNotificationDelete = $("[name=route-notification-delete]").attr('content');
var routeGetEmail = $("[name=route-get-email]").attr('content');
$(function () {
  MANAGER_NOTIFICATION_INDEX.init = function () {
    MANAGER_NOTIFICATION_INDEX.clearForm();
    MANAGER_NOTIFICATION_INDEX.clickSearch();
    MANAGER_NOTIFICATION_INDEX.selectChange();
    MANAGER_NOTIFICATION_INDEX.setEmptyValue();
    MANAGER_NOTIFICATION_INDEX.deleteAllNotification();
    MANAGER_NOTIFICATION_INDEX.clickDeleteNotification();
    MANAGER_NOTIFICATION_INDEX.selectEmail();
  };

  MANAGER_NOTIFICATION_INDEX.clearSuccessMsg = function () {
    $('#area_message').html('');
  };

  MANAGER_NOTIFICATION_INDEX.selectEmail = function () {
    $('.itemEmail').select2({
      language: 'ja',
      ajax: {
        url: routeGetEmail,
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

  MANAGER_NOTIFICATION_INDEX.clickDeleteNotification = function () {
    $('#btnDelete').click(function () {
      common.bootboxConfirm(msgConfirm, 'small', function (r) {
        if (r) {
          var data = $("#formDeleteNotification").serialize(); // Ajax

          $.ajax({
            type: "POST",
            url: routeNotificationDelete,
            data: data,
            success: function success(result) {
              if (result.status) {
                $('#area_message').html("\n                                    <section class=\"content-header\">\n                                        <div class=\"alert alert-success alert-dismissible\">\n                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                            <i class=\"icon fa fa-check\"></i>\n                                            ".concat(msgDeleteSuccess, "\n                                        </div>\n                                    </section>\n                                "));
                $(window).scrollTop(0);
                $('#notifications').DataTable().draw(true);
              } else {
                alert("Error server");
              }
            },
            error: function error(_error) {
              alert("Error server");
            }
          });
        }
      });
    });
  };

  MANAGER_NOTIFICATION_INDEX.setEmptyValue = function () {
    $('#created_at_from').val('');
    $('#created_at_to').val('');
    $('.itemEmail').val('');
    $('#select2-email-container').html('');
    $('#title').val('');
  };

  MANAGER_NOTIFICATION_INDEX.selectChange = function () {
    $('#icon_created_at_from').on("click", function () {
      $('#created_at_from').focus();
    });
    $('#icon_created_at_to').on("click", function () {
      $('#created_at_to').focus();
    });
    $('#select_box').on('change', function () {
      // $('#input').html('');
      switch (this.value) {
        case 'title':
          $('#inputTitle').hasClass('d-none') ? $('#inputTitle').removeClass('d-none') : true;
          $('#inputDate').hasClass('d-none') ? true : $('#inputDate').addClass('d-none');
          $('#inputEmail').hasClass('d-none') ? true : $('#inputEmail').addClass('d-none');
          MANAGER_NOTIFICATION_INDEX.setEmptyValue();
          break;

        case 'date':
          $('#inputDate').hasClass('d-none') ? $('#inputDate').removeClass('d-none') : true;
          $('#inputTitle').hasClass('d-none') ? true : $('#inputTitle').addClass('d-none');
          $('#inputEmail').hasClass('d-none') ? true : $('#inputEmail').addClass('d-none');
          MANAGER_NOTIFICATION_INDEX.setEmptyValue();
          break;

        case 'email':
          $('#inputEmail').hasClass('d-none') ? $('#inputEmail').removeClass('d-none') : true;
          $('#inputTitle').hasClass('d-none') ? true : $('#inputTitle').addClass('d-none');
          $('#inputDate').hasClass('d-none') ? true : $('#inputDate').addClass('d-none');
          MANAGER_NOTIFICATION_INDEX.setEmptyValue();
          break;
      }
    });
  };

  MANAGER_NOTIFICATION_INDEX.clickSearch = function () {
    $('#btnSearch').click(function () {
      MANAGER_NOTIFICATION_INDEX.clearSuccessMsg();
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
        $("#error_mes").html('検索項目を入力してください。');
        $("#error_section").css('display', 'block');
        return false;
      } // Clear error


      $('body').find('input').removeClass('is-invalid');
      $(".invalid-feedback-custom").html(''); // Get data form search teacher

      var data = $("#formSearchNotification").serialize(); // Ajax

      $.ajax({
        type: "POST",
        url: routeNotificationValidation,
        data: data,
        success: function success(result) {
          if (result.status) {
            $('#notifications').DataTable().draw(true);
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

  MANAGER_NOTIFICATION_INDEX.clearForm = function () {
    $('#btnClearForm').click(function () {
      MANAGER_NOTIFICATION_INDEX.clearSuccessMsg();
      MANAGER_NOTIFICATION_INDEX.setEmptyValue();
      $("#error_section").css('display', 'none');
      $('.format_created_at_from').removeClass('is-invalid');
      $('#format_created_at_from').html('');
      $('.format_created_at_to').removeClass('is-invalid');
      $('#format_created_at_to').html('');
      $('#notifications').DataTable().draw(true);
    });
  };

  MANAGER_NOTIFICATION_INDEX.deleteAllNotification = function () {
    // Check all
    $('#check_all').on('click', function (e) {
      var check = $(".chk_item");
      $("#formDeleteNotification").find("input[name='user_id[]'").remove();

      if ($(this).prop("checked")) {
        if (check.length > 0) {
          $("#btnDelete").attr("disabled", false);
        }

        check.prop('checked', true);
        check.each(function () {
          $("#formDeleteNotification").append('<input type="hidden" id="id-' + $(this).val() + '" name="notification_id[]" value="' + $(this).val() + '">');
        });
      } else {
        check.prop('checked', false);
        $("#btnDelete").attr("disabled", true);
        check.each(function () {
          $("#id-" + $(this).val()).remove();
        });
      }
    }); // Check item

    $("body").on("change", ".chk_item", function () {
      if (false == $(this).prop("checked")) {
        $("#check_all").prop('checked', false);
        $("#id-" + $(this).val()).remove();
      } else {
        $("#formDeleteNotification").append('<input type="hidden" id="id-' + $(this).val() + '" name="notification_id[]" value="' + $(this).val() + '">');
      }

      if ($('.chk_item:checked').length == $('.chk_item').length) {
        $("#check_all").prop('checked', true);
      }

      if ($('.chk_item:checked').length > 0) {
        $("#btnDelete").attr("disabled", false);
      } else {
        $("#btnDelete").attr("disabled", true);
      }
    }); // ckeck page

    $('#notifications').on('draw.dt', function () {
      $("#formDeleteNotification").find("input[name='notification_id[]'").remove();
      $("#check_all").prop('checked', false);
      $("#btnDelete").attr("disabled", true);
    });
  };
});
$(document).ready(function () {
  MANAGER_NOTIFICATION_INDEX.init();
});

/***/ }),

/***/ 17:
/*!*****************************************************************!*\
  !*** multi ./resources/js/admin/managers/notification/index.js ***!
  \*****************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\notification\index.js */"./resources/js/admin/managers/notification/index.js");


/***/ })

/******/ });