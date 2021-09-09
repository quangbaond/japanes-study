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
/******/ 	return __webpack_require__(__webpack_require__.s = 48);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/managers/AdminList/create.js":
/*!*********************************************************!*\
  !*** ./resources/js/admin/managers/AdminList/create.js ***!
  \*********************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

var MANAGER_ADMIN_CREATE = {};
$(function () {
  MANAGER_ADMIN_CREATE.init = function () {
    MANAGER_ADMIN_CREATE.handleChoiceImage();
    MANAGER_ADMIN_CREATE.clickCreateAdmin();
    MANAGER_ADMIN_CREATE.clickClearImage();
  };

  MANAGER_ADMIN_CREATE.handleChoiceImage = function () {
    // Choice image
    $("#choice_image").click(function () {
      $('#image_url').trigger('click');
    }); // Change image

    $("#image_url").change(function () {
      if (this.files && this.files[0]) {
        $('#error-photo').html('');
        var pic_size = $('#image_url')[0].files[0].size / 1024 / 1024; //get file size (MB)

        console.log(pic_size);
        var reader = new FileReader();

        reader.onload = function (e) {
          if (validImage("#image_url")) {
            if (pic_size >= 5) {
              $('#error-photo').html('写真ファイルを5MB以下のサイズにしてください。');
              $("#image_url").val(null);
            } else {
              // console.log(e.target.result);
              $('#image').attr('src', e.target.result);
            }
          } else {
            $('#error-photo').html('画像形式が正しくありません。対応する画像形式は（JPEG・JPG・PNG・GIF）です。');
            $("#image_url").val(null);
          }
        };

        reader.readAsDataURL(this.files[0]);
      }
    }); // Valid Image

    function validImage(file_id) {
      var fileExtension = ['jpg', 'jpeg', 'png', 'gif'];
      var valid = true;
      var msg = "";

      if ($(file_id).val() == '') {
        valid = false;
      } else {
        var fileName = $(file_id).val();
        var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();

        if ($.inArray(fileNameExt, fileExtension) == -1) {
          valid = false;
        }
      }

      return valid; //true or false
    }
  };

  MANAGER_ADMIN_CREATE.clickCreateAdmin = function () {
    $('#btnCreateAdmin').click(function () {
      if (checkInternet()) {
        $('#loading').removeClass('d-none');
        $('#loading').addClass('d-block');
        $('#formCreateAdmin').submit();
      }
    });
  };

  MANAGER_ADMIN_CREATE.clickClearImage = function () {
    $('#clearImage').click(function () {
      $('#image').attr('src', '/images/avatar_2.png');
      $("#image_url").val(null);
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
  MANAGER_ADMIN_CREATE.init();
});

/***/ }),

/***/ 48:
/*!***************************************************************!*\
  !*** multi ./resources/js/admin/managers/AdminList/create.js ***!
  \***************************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\managers\AdminList\create.js */"./resources/js/admin/managers/AdminList/create.js");


/***/ })

/******/ });