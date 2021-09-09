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
/******/ 	return __webpack_require__(__webpack_require__.s = 32);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./resources/js/admin/teachers/listSchedule.js":
/*!*****************************************************!*\
  !*** ./resources/js/admin/teachers/listSchedule.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

function _typeof(obj) { "@babel/helpers - typeof"; if (typeof Symbol === "function" && typeof Symbol.iterator === "symbol") { _typeof = function _typeof(obj) { return typeof obj; }; } else { _typeof = function _typeof(obj) { return obj && typeof Symbol === "function" && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj; }; } return _typeof(obj); }

var TEACHER_LIST_SCHEDULE = {};
var getRemoveImage = $("[name=removeImage]").attr('content');
var routeValidateTime = $("[name=route-validate-time]").attr('content');
var distance = $("[name=distance]").attr('content');
var lengthArray = [];
var listRemove = [];
var listExits = [];
var lengthTemp = [];
var listSaveRemove = [];
$(function () {
  TEACHER_LIST_SCHEDULE.init = function () {
    TEACHER_LIST_SCHEDULE.saveForm();
    TEACHER_LIST_SCHEDULE.clickChangeButton();
    TEACHER_LIST_SCHEDULE.clickRemoveTimepicker();
    TEACHER_LIST_SCHEDULE.clickButtonAddTimepicker();
    TEACHER_LIST_SCHEDULE.submitFormSearch();
    TEACHER_LIST_SCHEDULE.createPicker();
    TEACHER_LIST_SCHEDULE.clickDisable();
    TEACHER_LIST_SCHEDULE.clearBtnSearch();
    TEACHER_LIST_SCHEDULE.countNumofTimepicker();
    TEACHER_LIST_SCHEDULE.clickRemoveTimepickerTmp();
    TEACHER_LIST_SCHEDULE.rawHtml();
  };

  TEACHER_LIST_SCHEDULE.countNumofTimepicker = function () {
    if ($('.bs-timepicker').length > 0) {
      $('.bs-timepicker').timepicker();
    }

    for (var i = 1; i <= distance; i++) {
      var length = $(".timepicker".concat(i)).length;
      lengthArray.push(length - 1);
      lengthTemp.push(length - 1);
    }
  };

  TEACHER_LIST_SCHEDULE.clickChangeButton = function () {
    $('#changeButton').on('click', function () {
      console.log(2);
      listRemove = [];
      listExits = [];

      if ($(this).hasClass('btn-primary')) {
        //change button #changeInput
        $(this).removeClass('btn-primary');
        $(this).addClass('btn-secondary'); //add button +

        var length = $('.addButton').length;
        console.log(length);

        for (var i = 1; i <= length; i++) {
          var temp = "<div class=\"float-right\">\n                                    <btnSavebutton class=\"btn btn-primary btnAddTimePicker\" id=\"button".concat(i, "\">\n                                        <i class=\"fas fa-plus\"></i>\n                                    </btnSavebutton>\n                                </div>"); // $('#Row').append(temp); // Element(s) are now enabled.

          var check = $("#row".concat(i)).attr('data-disable');

          if (parseInt(check) == 0) {
            $("#divRow".concat(i)).append(temp);
          }
        } //change input value to timepicker


        if ($('.bs-timepicker').length > 0) {
          $('.bs-timepicker').prop("disabled", false); // Element(s) are now enabled.

          $('.bs-timepicker').prop("readonly", true); // Element(s) are now enabled.

          $('.bs-timepicker').timepicker();
          $(".bs-timepicker").keypress(function (event) {
            event.preventDefault();
          });
        } //add button submit at the end
        // temp = `<div class="float-right mt-2" id="divCancel">
        //             <button class="btn btn-default mr-4" id="btnCancel">キャンセル</button>
        //         </div>
        //         <div class="float-right mt-2" id="divSave">
        //             <button class="btn btn-primary" id="btnSave">更新</button>
        //         </div>`;
        // $('#tabledata').append(temp);


        temp = '<button class="btn btn-default mr-4" id="btnCancel">キャンセル</button>';
        $('#divCancel').append(temp);
        temp = '<button class="btn btn-primary" id="btnSave">更新</button>';
        $('#divSave').append(temp); //add remove timepicker button

        for (var i = 1; i <= $('.addButton').length; i++) {
          var length = $(".timepicker".concat(i)).length;
          lengthArray.push(length - 1);

          for (var j = 0; j <= lengthArray[i - 1]; j++) {
            temp = " <img class=\"removeTimepicker position-absolute\" style=\"width: 15px; height: 15px\"\n                            src=\"".concat(getRemoveImage, "\" id=\"").concat(i, "-").concat(j, "\">");
            $("#removeTimepicker".concat(i, "-").concat(j)).append(temp);
          }
        }

        TEACHER_LIST_SCHEDULE.clickRemoveTimepicker();
        TEACHER_LIST_SCHEDULE.clickButtonAddTimepicker();
        TEACHER_LIST_SCHEDULE.disableButton();
      }
      /* else if ($(this).hasClass('btn-secondary')) {
                  for (var j = 0; j <= lengthArray[i - 1]; j++) {
                  $(`#${i}-${j}`).remove();
              }
          }
          // var list = $('.addButton');
          // var dataForm = {};
          // for (var i = list.length - 1; i >= 0; i--) {
          //     //console.log('#'+list[i].id+' input');
            //     var listInput = $('#'+list[i].id+' input');
          //     for(var j = 0; j < listInput.length; j++){
          //         var tmp  = $('#'+listInput[j].id).attr('data-value');
          //         if($('#'+listInput[j].id).hasClass('timpicker-exits')){
          //             if(tmp !== undefined){
          //                 listInput[j].value = tmp;
          //             }
          //         } else {
          //             var index = listInput[j].id.split('timepicker')[1];
          //             console.log(index);
          //             $('#divTimepicker'+index).remove();
          //         }
          //     }
          // }
          TEACHER_LIST_SCHEDULE.restoreHtml();
          $('#error_section').html('');
          $('#success_section').html('');
          TEACHER_LIST_SCHEDULE.clickRemoveTimepicker();
          TEACHER_LIST_SCHEDULE.clickButtonAddTimepicker();
          TEACHER_LIST_SCHEDULE.removedisableButton();
          //change button #changeInput
          $('#changeButton').removeClass('btn-secondary');
          $('#changeButton').addClass('btn-primary');
            //remove button +
          var length = $('.addButton').length;
          for (var i = 1; i <= length; i++) {
              $(`#button${i}`).remove();
          }
            //change input value to disable
          if($('.bs-timepicker').length > 0){
              $('.bs-timepicker').prop("disabled", false); // Element(s) are now enabled.
              //$('.bs-timepicker').timepicker();
          }
          //remove button submit
          $('#btnCancel').remove();
          $('#btnSave').remove();
          //Remove remove timepicker button
            for (var i = 1; i <= 7; i++) {
                for (var j = 0; j <= lengthArray[i - 1]; j++) {
                  $(`#${i}-${j}`).remove();
              }
          }
          lengthArray = [];
          lengthTemp = [];
          TEACHER_LIST_SCHEDULE.countNumofTimepicker();
      }*/

    });
  };

  TEACHER_LIST_SCHEDULE.rawHtml = function () {
    var list = $('.addButton');

    for (var i = 0; i < list.length; i++) {
      var tmp = list[i].id;
      tmp = tmp.split('divRow');
      listSaveRemove[parseInt(tmp[1])] = $("#row".concat(tmp[1])).html();
      console.log($("#row".concat(tmp[1])).html());
    } //console.log(listSaveRemove);

  };

  TEACHER_LIST_SCHEDULE.restoreHtml = function () {
    var list = $('.addButton');

    for (var i = 0; i < list.length; i++) {
      var tmp = list[i].id;
      tmp = tmp.split('divRow');
      $("#row".concat(tmp[1])).html(listSaveRemove[parseInt(tmp[1])]);
    }
  };

  TEACHER_LIST_SCHEDULE.clickRemoveTimepicker = function () {
    $('.removeTimepicker').click(function () {
      if ($("#timepicker".concat(this.id)).attr('data-id') !== undefined) {
        listRemove.push($("#timepicker".concat(this.id)).attr('data-id'));
      }

      var index = this.id.split('-')[0];
      lengthArray[index - 1]--;
      $("#divTimepicker".concat(this.id)).remove();
    });
  };

  TEACHER_LIST_SCHEDULE.clickRemoveTimepickerTmp = function () {
    for (var i = 1; i <= 7; i++) {
      $("#".concat(i, "-0")).click(function () {
        //$(`#divTimepicker${this.id}`).remove();
        index = this.id.split('-')[0];
        lengthTemp[index - 1]--;
      });
    }
  };

  TEACHER_LIST_SCHEDULE.clickButtonAddTimepicker = function () {
    $('.btnAddTimePicker').click(function () {
      // console.log(this.id.split('button')[1]);
      var index = this.id.split('button')[1];
      console.log(lengthTemp[index - 1]);

      if (lengthTemp[index - 1] + 1 < 24) {
        var temp = "<div class=\"d-flex\" id=\"divTimepicker".concat(index, "-").concat(lengthArray[index - 1] + 1, "\">\n                                    <div class=\"mb-2 mr-2\" id=\"removeTimepicker").concat(index, "-").concat(lengthArray[index - 1] + 1, "\">\n                                    </div>\n                                    <input type=\"text\"\n                                        id=\"timepicker").concat(index, "-").concat(lengthArray[index - 1] + 1, "\"\n                                        class=\"bs-timepicker mr-lg-5 mr-4 mb-3 mt-1 text-center\"\n                                        style=\"width: 65px\" value=\"\" readonly/>\n                               </div>");
        $("#row".concat(index)).append(temp);
        temp = "<img class=\"removeTimepicker position-absolute\" style=\"width: 15px; height: 15px\"\n                            src=\"".concat(getRemoveImage, "\" id=\"").concat(index, "-").concat(lengthArray[index - 1] + 1, "\">"); //$(`#removeTimepicker${index}-${lengthArray[index - 1] + 1}`).append(temp);
        //lengthArray[index - 1]++;
        //$('.bs-timepicker').timepicker();
        //TEACHER_LIST_SCHEDULE.clickRemoveTimepicker();

        $("#removeTimepicker".concat(index, "-").concat(lengthArray[index - 1] + 1)).append(temp);
        $('.bs-timepicker').timepicker();
        $("#".concat(index, "-").concat(lengthArray[index - 1] + 1)).click(function () {
          $("#divTimepicker".concat(this.id)).remove();
          index = this.id.split('-')[0];
          lengthTemp[index - 1]--;
        });
        lengthArray[index - 1]++;
        lengthTemp[index - 1]++;
      }
    });
  };

  TEACHER_LIST_SCHEDULE.submitFormSearch = function () {
    $('#btnSearch').on('click', function () {
      if ($(this).is("[disabled]")) {
        event.preventDefault();
      } else {
        $('#formSchedule').submit();
      }
    });
  };

  TEACHER_LIST_SCHEDULE.disableButton = function () {
    $('#btnSearch').attr("disabled", "disabled");
    $('#btnAdd').addClass("disabled");
    $('#btnClear').attr("disabled", "disabled");
    $("#formSchedule input").prop("disabled", true);
    $('#btnAdd').removeClass('btn-success');
    $('#btnAdd').addClass('btn-secondary');
    $('#btnAdd button').removeClass('btn-success');
    $('#btnAdd button').addClass('btn-secondary');
    $('#changeButton').attr('disabled', "disabled");
  };

  TEACHER_LIST_SCHEDULE.removedisableButton = function () {
    $('#btnSearch').removeAttr("disabled");
    $('#btnAdd').removeClass("disabled");
    $('#btnClear').removeAttr("disabled");
    $('#changeButton').removeAttr("disabled");
    $("#formSchedule input").prop("disabled", false);
    $('#btnAdd').removeClass('btn-secondary');
    $('#btnAdd').addClass('btn-success');
  };

  TEACHER_LIST_SCHEDULE.clickDisable = function () {
    $('#btnAdd').on("click", function () {
      window.location.replace($(this).attr("data-url"));
    });
  };

  TEACHER_LIST_SCHEDULE.createPicker = function () {
    $('#from_time').timepicker();
    $('#to_time').timepicker(); // $('#icon_to_date').on("click", function(){
    //     $('#to_date').focus();
    // })
    // $('#icon_from_date').on("click", function(){
    //     $('#from_date').focus();
    // })
  };

  TEACHER_LIST_SCHEDULE.saveForm = function () {
    $('#divSave').on('click', '#btnSave', function () {
      $('#loading').addClass('d-block'); //console.log(1);

      var list = $('.addButton'); // console.log(list);

      var dataForm = {};

      for (var i = list.length - 1; i >= 0; i--) {
        console.log(!list[i].id);

        if (list[i].id) {
          var listInput = $('#' + list[i].id + ' input');
          var countInput = $('#' + list[i].id + ' input').length;
          var total = parseInt($('#' + list[i].id).attr('data-total'));
          var time = $('#' + list[i].id).attr('data-time'); //console.log(listInput.length);

          if (listInput.length > 0) {
            var tmp = {};

            for (var j = 0; j < listInput.length; j++) {
              if ($('#' + listInput[j].id).hasClass('timpicker-exits')) {
                var a = {
                  'id': $('#' + listInput[j].id).attr('data-id'),
                  'id_div': listInput[j].id,
                  'time': $('#' + listInput[j].id).attr('data-time')
                };
                listExits.push(a);
              }

              if (listInput[j].value != '') {
                tmp[listInput[j].id] = listInput[j].value;
              }
            }

            dataForm[time] = tmp;
          }
        }
      }

      var submitForm = {
        'data': dataForm,
        'remove': listRemove,
        'exits': listExits
      }; //e.preventDefault();

      $.ajax({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        type: "POST",
        url: routeValidateTime,
        data: JSON.stringify(submitForm),
        cache: false,
        contentType: false,
        processData: false,
        dataType: 'JSON',
        success: function success(result) {
          listExits = [];

          if (result.status == false) {
            $('#loading').removeClass('d-block');
            var errors = result.message;
            var msg;
            $('.border-danger').removeClass('border-danger');

            if (_typeof(errors) === 'object') {
              for (var i in errors) {
                msg = errors[i];
                $('#' + i).removeClass('border-dark');
                $('#' + i).addClass('border-danger');
              }
            } else {
              msg = result.message;
            }

            console.log(msg);
            $('#success_section').html('');
            $('#error_section').html("\n                            <div class=\"alert alert-danger alert-dismissible\">\n                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                <i class=\"icon fa fa-ban\"></i>\n                                <span id=\"error_mes\">".concat(msg, "</span>\n                            </div>\n                        "));
            $("html, body").animate({
              scrollTop: $('#error_section').offset().top - 10
            }, "slow");
          } else {
            $('#btnSave').attr('disabled', true);
            $('#loading').removeClass('d-block');
            $('#error_section').html('');
            var msg = result.message;
            console.log(msg);
            $('#success_section').html("\n                            <div class=\"alert alert-success alert-dismissible\">\n                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                <i class=\"icon fa fa-check\"></i>\n                                <span id=\"error_mes\">".concat(msg, "</span>\n                            </div>\n                        "));
            $("html, body").animate({
              scrollTop: $('#success_section').offset().top - 10
            }, "slow"); //location.reload();

            setTimeout(function () {
              location.reload();
            }, 1000);
          }
        },
        error: function error(error) {
          $('#loading').removeClass('d-block');
          $('#error_section').html("\n                            <div class=\"alert alert-danger alert-dismissible\">\n                                <button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button>\n                                <i class=\"icon fa fa-ban\"></i>\n                                <span id=\"error_mes\">\u66F4\u65B0\u304C\u5931\u6557\u3057\u307E\u3057\u305F\u3002</span>\n                            </div>\n                    ");
          $("html, body").animate({
            scrollTop: $('#error_section').offset().top - 10
          }, "slow");
        }
      });
    });
    $('#divCancel').on('click', '#btnCancel', function () {
      var list = $('.addButton');
      var dataForm = {};
      TEACHER_LIST_SCHEDULE.restoreHtml(); // for (var i = list.length - 1; i >= 0; i--) {
      //     //console.log('#'+list[i].id+' input');
      //     var listInput = $('#'+list[i].id+' input');
      //     for(var j = 0; j < listInput.length; j++){
      //         var tmp  = $('#'+listInput[j].id).attr('data-value');
      //         if($('#'+listInput[j].id).hasClass('timpicker-exits')){
      //             if(tmp !== undefined){
      //                 listInput[j].value = tmp;
      //             }
      //         } else {
      //             var index = listInput[j].id.split('timepicker')[1];
      //             console.log(index);
      //             $('#divTimepicker'+index).remove();
      //         }
      //     }
      // }

      $('#error_section').html('');
      $('#success_section').html('');
      TEACHER_LIST_SCHEDULE.clickRemoveTimepicker();
      TEACHER_LIST_SCHEDULE.clickButtonAddTimepicker();
      TEACHER_LIST_SCHEDULE.removedisableButton(); //change button #changeInput

      $('#changeButton').removeClass('btn-secondary');
      $('#changeButton').addClass('btn-primary'); //remove button +

      var length = $('.addButton').length;

      for (var i = 1; i <= length; i++) {
        $("#button".concat(i)).remove();
      } //change input value to disable


      if ($('.bs-timepicker').length > 0) {
        $('.bs-timepicker').prop("disabled", true); // Element(s) are now enabled.
        //$('.bs-timepicker').timepicker();
      } //remove button submit


      $('#btnCancel').remove();
      $('#btnSave').remove(); //Remove remove timepicker button

      console.log(lengthArray);

      for (var i = 1; i <= 7; i++) {
        for (var j = 0; j <= lengthArray[i - 1]; j++) {
          $("#".concat(i, "-").concat(j)).remove();
        }
      }

      lengthArray = [];
      lengthTemp = [];
      TEACHER_LIST_SCHEDULE.countNumofTimepicker(); //lengthArray = [];
      // for (var k = 1; k <= $('.addButton').length; k++) {
      //     var length = $(`.timepicker${i}`).length;
      //     lengthArray.push(length - 1);
      // }
    });
  };

  TEACHER_LIST_SCHEDULE.clearBtnSearch = function () {
    $('#btnClear').on("click", function () {
      window.location.replace($(this).attr("data-url")); //localStorage.setItem("test", "true");
    }); // if(localStorage.getItem("test") === "true") {
    //     localStorage.setItem("test","");
    //     $("#formSchedule input[type='text']").val('');
    //     $("#formSchedule input[type='radio']").prop("checked", false);
    //     $('#allradio').prop("checked", true);
    // }
  };
});
$(document).ready(function () {
  TEACHER_LIST_SCHEDULE.init();
});

/***/ }),

/***/ 32:
/*!***********************************************************!*\
  !*** multi ./resources/js/admin/teachers/listSchedule.js ***!
  \***********************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! C:\Users\charo\OneDrive\Desktop\JapaneseStudy-Server\Source_Code\resources\js\admin\teachers\listSchedule.js */"./resources/js/admin/teachers/listSchedule.js");


/***/ })

/******/ });