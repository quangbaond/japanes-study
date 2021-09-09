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
/******/ 	return __webpack_require__(__webpack_require__.s = 7);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/students/index.js":
/*!*******************************************************!*\
  !*** ./resources/js/admin/managers/students/index.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_STUDENT_INDEX = {};
var formDeleteAllStudent = $("#formDeleteAllStudent");
var notificationConfirmDelete = $("[name=confirm-delete]").attr('content');
var messageDeleteSuccess = $("[name=delete-success]").attr('content');
var routeStudentDelete = $("[name=route-student-delete]").attr('content');
var routeStudentValidation = $("[name=route-student-validation]").attr('content');
$(function () {
  MANAGER_STUDENT_INDEX.init = function () {
    MANAGER_STUDENT_INDEX.checkboxDeleteAllStudent();
    MANAGER_STUDENT_INDEX.confirmDeleteAllStudent();
    MANAGER_STUDENT_INDEX.clearForm();
    MANAGER_STUDENT_INDEX.clickSearch();
  };

  MANAGER_STUDENT_INDEX.checkboxDeleteAllStudent = function () {
    // check all
    $('#check_all').on('click', function (e) {
      var check = $(".chk_item");
      $("#formDeleteAllStudent").find("input[name='user_id[]'").remove();

      if ($(this).prop("checked")) {
        if (check.length > 0) {
          $("#delete-all-student").attr("disabled", false);
        }

        check.prop('checked', true);
        check.each(function () {
          $("#formDeleteAllStudent").append('<input type="hidden" id="id-' + $(this).val() + '" name="user_id[]" value="' + $(this).val() + '">');
        });
      } else {
        check.prop('checked', false);
        $("#delete-all-student").attr("disabled", true);
        check.each(function () {
          $("#id-" + $(this).val()).remove();
        });
      }
    }); // ckeck page

    $('#students').on('draw.dt', function () {
      $("#formDeleteAllStudent").find("input[name='user_id[]'").remove();
      $("#check_all").prop('checked', false);
      $("#delete-all-student").attr("disabled", true);
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
        $("#formDeleteAllStudent").append('<input type="hidden" id="id-' + $(this).val() + '" name="user_id[]" value="' + $(this).val() + '">');
      } else {
        $("#id-" + $(this).val()).remove();
      }

      if ($('.chk_item:checked').length > 0) {
        $("#delete-all-student").attr("disabled", false);
      } else {
        $("#delete-all-student").attr("disabled", true);
      }
    });
  };

  MANAGER_STUDENT_INDEX.confirmDeleteAllStudent = function () {
    $('#delete-all-student').on('click', function (e) {
      common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
        if (r) {
          var data = $("#formDeleteAllStudent").serialize(); // Ajax

          $.ajax({
            type: "POST",
            url: routeStudentDelete,
            data: data,
            success: function success(result) {
              if (result.status) {
                $('#area_message').html("\n                                    <section class=\"content-header\">\n                                        <div class=\"alert alert-success alert-dismissible\">\n                                            <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                            <i class=\"icon fa fa-check\"></i>\n                                            ".concat(messageDeleteSuccess, "\n                                        </div>\n                                    </section>\n                                "));
                $(window).scrollTop(0);
                $('#students').DataTable().draw(true);
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

  MANAGER_STUDENT_INDEX.clickSearch = function () {
    $('#btnSearch').click(function () {
      $("#error_section").css('display', 'none');
      var invalid = true;
      $('#searchStudent').find('select,input').each(function () {
        if ($(this).val() != '') {
          console.log($(this).val());
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

      var data = $("#formSearchStudent").serialize(); // Ajax

      $.ajax({
        type: "POST",
        url: routeStudentValidation,
        data: data,
        success: function success(result) {
          if (result.status) {
            $('#students').DataTable().draw(true);
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

  MANAGER_STUDENT_INDEX.clearForm = function () {
    $('#btnClearForm').click(function () {
      var isThis = $(this);
      var formSearchClear = '.form-search-clear';
      common.clearValueFormSearch(isThis, formSearchClear);
      $("#error_section").css('display', 'none');
      $('#students').DataTable().draw(true);
    });
  };
});
$(document).ready(function () {
  MANAGER_STUDENT_INDEX.init();
});

/***/ }),

/***/ 7:
/*!*************************************************************!*\
  !*** multi ./resources/js/admin/managers/students/index.js ***!
  \*************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\students\index.js */"./resources/js/admin/managers/students/index.js");


/***/ })

/******/ });