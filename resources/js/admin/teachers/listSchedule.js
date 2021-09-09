let TEACHER_LIST_SCHEDULE = {};
let getRemoveImage = $("[name=removeImage]").attr('content');
let routeValidateTime = $("[name=route-validate-time]").attr('content');
let distance = $("[name=distance]").attr('content');
let lengthArray = [];
let listRemove = [];
let listExits = [];
let lengthTemp = [];
let listSaveRemove = [];
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
        if($('.bs-timepicker').length > 0){
            $('.bs-timepicker').timepicker();

        }
        for (var i = 1; i <= distance; i++) {
            var length = $(`.timepicker${i}`).length;
            lengthArray.push(length - 1);
            lengthTemp.push(length - 1);
        }
    }
    TEACHER_LIST_SCHEDULE.clickChangeButton = function () {
        $('#changeButton').on('click', function () {
            console.log(2);
            listRemove = [];
            listExits = [];
            if ($(this).hasClass('btn-primary')) {
                //change button #changeInput
                $(this).removeClass('btn-primary');
                $(this).addClass('btn-secondary');

                //add button +
                var length = $('.addButton').length;
                console.log(length);
                for (var i = 1; i <= length; i++) {
                    var temp = `<div class="float-right">
                                    <btnSavebutton class="btn btn-primary btnAddTimePicker" id="button${i}">
                                        <i class="fas fa-plus"></i>
                                    </btnSavebutton>
                                </div>`;
                    // $('#Row').append(temp); // Element(s) are now enabled.
                    var check = $(`#row${i}`).attr('data-disable');
                    if(parseInt(check) == 0){
                        $(`#divRow${i}`).append(temp);
                    }
                }

                //change input value to timepicker
                if($('.bs-timepicker').length > 0){
                    $('.bs-timepicker').prop("disabled", false); // Element(s) are now enabled.
                    $('.bs-timepicker').prop("readonly", true); // Element(s) are now enabled.
                    $('.bs-timepicker').timepicker();
                    $(".bs-timepicker").keypress(function(event) {event.preventDefault();});

                }

                //add button submit at the end
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
                $('#divSave').append(temp);
                //add remove timepicker button
                for (var i = 1; i <= $('.addButton').length; i++) {
                    var length = $(`.timepicker${i}`).length;

                    lengthArray.push(length - 1);
                    for (var j = 0; j <= lengthArray[i - 1]; j++) {
                        temp = ` <img class="removeTimepicker position-absolute" style="width: 15px; height: 15px"
                            src="${getRemoveImage}" id="${i}-${j}">`;
                        $(`#removeTimepicker${i}-${j}`).append(temp);
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
    }
    TEACHER_LIST_SCHEDULE.rawHtml = function (){
        var list  = $('.addButton');
        for (var i = 0; i < list.length; i++) {
            var tmp = list[i].id;

            tmp  = tmp.split('divRow');
            listSaveRemove[parseInt(tmp[1])] = $(`#row${tmp[1]}`).html();
            console.log($(`#row${tmp[1]}`).html());
        }
        //console.log(listSaveRemove);
    }
    TEACHER_LIST_SCHEDULE.restoreHtml = function (){
        var list  = $('.addButton');
        for (var i = 0; i < list.length; i++) {
            var tmp = list[i].id;

            tmp  = tmp.split('divRow');
            $(`#row${tmp[1]}`).html(listSaveRemove[parseInt(tmp[1])]);
        }
    }
    TEACHER_LIST_SCHEDULE.clickRemoveTimepicker = function () {
        $('.removeTimepicker').click(function () {
        	if($(`#timepicker${this.id}`).attr('data-id') !== undefined){
                listRemove.push($(`#timepicker${this.id}`).attr('data-id'));
            }
            var index = this.id.split('-')[0];
            lengthArray[index - 1]--;
            $(`#divTimepicker${this.id}`).remove();

        })
    }

    TEACHER_LIST_SCHEDULE.clickRemoveTimepickerTmp = function () {
        for (var i = 1; i <= 7; i++) {
            $(`#${i}-0`).click(function () {
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
                if((lengthTemp[index - 1] + 1) < 24){
                    var temp = `<div class="d-flex" id="divTimepicker${index}-${lengthArray[index - 1] + 1}">
                                    <div class="mb-2 mr-2" id="removeTimepicker${index}-${lengthArray[index - 1] + 1}">
                                    </div>
                                    <input type="text"
                                        id="timepicker${index}-${lengthArray[index - 1] + 1}"
                                        class="bs-timepicker mr-lg-5 mr-4 mb-3 mt-1 text-center"
                                        style="width: 65px" value="" readonly/>
                               </div>`;
                    $(`#row${index}`).append(temp);
                    temp = `<img class="removeTimepicker position-absolute" style="width: 15px; height: 15px"
                            src="${getRemoveImage}" id="${index}-${lengthArray[index - 1] + 1}">`
                    //$(`#removeTimepicker${index}-${lengthArray[index - 1] + 1}`).append(temp);
                    //lengthArray[index - 1]++;
                    //$('.bs-timepicker').timepicker();
                    //TEACHER_LIST_SCHEDULE.clickRemoveTimepicker();
                    $(`#removeTimepicker${index}-${lengthArray[index - 1] + 1}`).append(temp);
                    $('.bs-timepicker').timepicker();
                    $(`#${index}-${lengthArray[index - 1] + 1}`).click(function () {
                        $(`#divTimepicker${this.id}`).remove();
                        index = this.id.split('-')[0];
                        lengthTemp[index - 1]--;
                    });
                    lengthArray[index - 1]++;
                    lengthTemp[index - 1]++;
                }

        })
    }
    TEACHER_LIST_SCHEDULE.submitFormSearch = function (){
        $('#btnSearch').on('click' ,function(){
            if ($(this).is("[disabled]")) {
                event.preventDefault();
            } else {
                $('#formSchedule').submit();
            }

        });
    }
    TEACHER_LIST_SCHEDULE.disableButton = function (){
        $('#btnSearch').attr("disabled","disabled");
        $('#btnAdd').addClass("disabled");
        $('#btnClear').attr("disabled","disabled");
        $("#formSchedule input").prop("disabled", true);
        $('#btnAdd').removeClass('btn-success');
        $('#btnAdd').addClass('btn-secondary');
        $('#btnAdd button').removeClass('btn-success');
        $('#btnAdd button').addClass('btn-secondary');
        $('#changeButton').attr('disabled',"disabled");


    }
    TEACHER_LIST_SCHEDULE.removedisableButton = function (){
        $('#btnSearch').removeAttr("disabled");
        $('#btnAdd').removeClass("disabled");
        $('#btnClear').removeAttr("disabled");
        $('#changeButton').removeAttr("disabled");
        $("#formSchedule input").prop("disabled", false);
        $('#btnAdd').removeClass('btn-secondary');
        $('#btnAdd').addClass('btn-success');
    }
    TEACHER_LIST_SCHEDULE.clickDisable = function (){
        $('#btnAdd').on("click", function(){
            window.location.replace($(this).attr("data-url"));
        });
    }
    TEACHER_LIST_SCHEDULE.createPicker = function () {
        $('#from_time').timepicker();
        $('#to_time').timepicker();

        // $('#icon_to_date').on("click", function(){
        //     $('#to_date').focus();
        // })
        // $('#icon_from_date').on("click", function(){
        //     $('#from_date').focus();
        // })
    }
    TEACHER_LIST_SCHEDULE.saveForm = function (){
        $('#divSave').on('click','#btnSave', function(){
            $('#loading').addClass('d-block');
            //console.log(1);
            var list = $('.addButton');
            // console.log(list);
            var dataForm = {};
            for (var i = list.length - 1; i >= 0; i--) {
                console.log(!list[i].id);
                if(list[i].id) {
                    var listInput = $('#' + list[i].id + ' input');
                    var countInput = $('#' + list[i].id + ' input').length;
                    var total = parseInt($('#' + list[i].id).attr('data-total'));
                    var time = $('#' + list[i].id).attr('data-time');

                    //console.log(listInput.length);
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
            var submitForm = {'data' : dataForm, 'remove': listRemove, 'exits': listExits};

            //e.preventDefault();
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
                    if(result.status == false){
                        $('#loading').removeClass('d-block');
                        var errors = result.message;
                        var msg;
                        $('.border-danger').removeClass('border-danger');
                        if(typeof errors === 'object'){
                            for(var i in errors){
                                msg = errors[i];
                                $('#'+i).removeClass('border-dark');
                                $('#'+i).addClass('border-danger');
                            }
                        } else {
                            msg = result.message;
                        }
                        console.log(msg);
                        $('#success_section').html('');
                        $('#error_section').html(`
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="icon fa fa-ban"></i>
                                <span id="error_mes">${msg}</span>
                            </div>
                        `);
                        $("html, body").animate({scrollTop:$('#error_section').offset().top - 10}, "slow");
                    } else {
                        $('#btnSave').attr('disabled', true);
                        $('#loading').removeClass('d-block');
                        $('#error_section').html('');
                        var msg = result.message;
                        console.log(msg);
                        $('#success_section').html(`
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="icon fa fa-check"></i>
                                <span id="error_mes">${msg}</span>
                            </div>
                        `);
                        $("html, body").animate({scrollTop:$('#success_section').offset().top -10}, "slow");
                        //location.reload();
                        setTimeout(function(){ location.reload(); }, 1000);
                    }
                },
                error: function error(error) {
                    $('#loading').removeClass('d-block');
                    $('#error_section').html(`
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                <i class="icon fa fa-ban"></i>
                                <span id="error_mes">更新が失敗しました。</span>
                            </div>
                    `);
                    $("html, body").animate({scrollTop:$('#error_section').offset().top - 10}, "slow");
                }
            });
        });
        $('#divCancel').on('click','#btnCancel', function(){

            var list = $('.addButton');
            var dataForm = {};
            TEACHER_LIST_SCHEDULE.restoreHtml();
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
                $('.bs-timepicker').prop("disabled", true); // Element(s) are now enabled.
                //$('.bs-timepicker').timepicker();
            }
            //remove button submit
            $('#btnCancel').remove();
            $('#btnSave').remove();
            //Remove remove timepicker button
            console.log(lengthArray);
            for (var i = 1; i <= 7; i++) {

                for (var j = 0; j <= lengthArray[i - 1]; j++) {
                    $(`#${i}-${j}`).remove();
                }
            }
            lengthArray = [];
            lengthTemp = [];
            TEACHER_LIST_SCHEDULE.countNumofTimepicker();
            //lengthArray = [];
            // for (var k = 1; k <= $('.addButton').length; k++) {
            //     var length = $(`.timepicker${i}`).length;
            //     lengthArray.push(length - 1);
            // }
        });
    }
    TEACHER_LIST_SCHEDULE.clearBtnSearch = function (){
        $('#btnClear').on("click", function(){
            window.location.replace($(this).attr("data-url"));

            //localStorage.setItem("test", "true");

        });
        // if(localStorage.getItem("test") === "true") {
        //     localStorage.setItem("test","");

        //     $("#formSchedule input[type='text']").val('');
        //     $("#formSchedule input[type='radio']").prop("checked", false);
        //     $('#allradio').prop("checked", true);
        // }
    }
});

$(document).ready(function () {
    TEACHER_LIST_SCHEDULE.init();
});
