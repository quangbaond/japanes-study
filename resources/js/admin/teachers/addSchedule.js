let TEACHER_ADD_SCHEDULE = {};
let getRemoveImage = $("[name=removeImage]").attr('content');
let M007 = $("[name=M007]").attr('content');
let RouteValidateSchedule = $("[name=routeValidateSchedule]").attr('content');
let lengthArray = [];
let lengthTemp = [];
$(function () {
    TEACHER_ADD_SCHEDULE.init = function () {
        TEACHER_ADD_SCHEDULE.countNumofTimepicker();
        TEACHER_ADD_SCHEDULE.clickButtonAddTimepicker();
        TEACHER_ADD_SCHEDULE.clickRemoveTimepicker();
        TEACHER_ADD_SCHEDULE.clickButtonSubmit();
        TEACHER_ADD_SCHEDULE.getScheduleData();
        TEACHER_ADD_SCHEDULE.clickButtonClear();
    };

    TEACHER_ADD_SCHEDULE.countNumofTimepicker = function () {
        $('.bs-timepicker').timepicker();
        for (var i = 0; i <= 6; i++) {
            var length = $(`.timepicker${i + 1}`).length;
            lengthArray[i] = length - 1;
            lengthTemp[i] = length - 1;
        }
    }
    TEACHER_ADD_SCHEDULE.clickRemoveTimepicker = function () {
        for (var i = 1; i <= 7; i++) {
            $(`#${i}-0`).click(function () {
                $(`#divTimepicker${this.id}`).remove();
                index = this.id.split('-')[0];
                lengthTemp[index - 1]--;
            });
        }
    };


    TEACHER_ADD_SCHEDULE.clickButtonAddTimepicker = function () {
        $('.btnAddTimePicker').click(function () {
            // console.log("lengthArray: " + lengthArray);
            // var string = (Math.floor(Math.random() * 23) + 1) + ':' + (Math.floor(Math.random() * 59) + 1)
            // console.log(string);
            var index = this.id.split('button')[1];
            if (lengthTemp[index - 1] <= 22) {
                var temp = `<div class="d-flex" id="divTimepicker${index}-${lengthArray[index - 1] + 1}">
                            <div class="mb-2 mr-2" id="removeTimepicker${index}-${lengthArray[index - 1] + 1}">
                            </div>
                            <input type="text"
                                id="timepicker${index}-${lengthArray[index - 1] + 1}"
                                class="bs-timepicker border border-dark mr-lg-5 mb-3 mt-1 text-center"
                                style="width: 65px" value="" readonly/>
                            </div>`;
                $(`#row${index}`).append(temp);

                temp = `<img class="removeTimepicker position-absolute" style="width: 15px; height: 15px"
                    src="${getRemoveImage}" id="${index}-${lengthArray[index - 1] + 1}">`

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
            // console.log("lengthTemp: " + lengthTemp);
        })
    }

    TEACHER_ADD_SCHEDULE.getScheduleData = () => {
        var data = new Array();
        var data1 = new FormData();
        for (var i = 1; i <= 7; i++) {
            var temp = {};
            for (var j = 0; j <= lengthArray[i - 1]; j++) {
                if ($(`#timepicker${i}-${j}`).val() != null) {
                    temp[`${i}-${j}`] = $(`#timepicker${i}-${j}`).val();
                }
            }
            data.push(temp);
        }
        return JSON.stringify(data);
    }

    TEACHER_ADD_SCHEDULE.unFailMark = (array) => {
        for (var i = 1; i <= 7; i++) {
            for (var j = 0; j <= lengthArray[i - 1]; j++) {
                if ($.inArray(`${i}-${j}`, array) == -1) {
                    if ($(`#timepicker${i}-${j}`).hasClass('border-danger')) {
                        $(`#timepicker${i}-${j}`).removeClass('border-danger');
                        $(`#timepicker${i}-${j}`).addClass('border-dark');
                    }
                }
            }
        }
    }

    TEACHER_ADD_SCHEDULE.clickButtonSubmit = () => {
        $('#btnSubmit').click(function (e) {
            $('#loading').addClass('d-block');
            if(navigator.onLine) {
                // Ajax validation
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                e.preventDefault();
                let data = TEACHER_ADD_SCHEDULE.getScheduleData();
                $.ajax({
                    type: "POST",
                    url: RouteValidateSchedule,
                    data: data,
                    cache: false,
                    contentType: false,
                    processData: false,
                    dataType: 'JSON',
                    success: function success(result) {
                        if (!result.status) {
                            if (result.data == 'scheduleFailed') {
                                $('#loading').removeClass('d-block');
                                TEACHER_ADD_SCHEDULE.addMessageErrorCommon(result.message['msgErr']);
                                TEACHER_ADD_SCHEDULE.unFailMark(result.message);
                                $("html, body").animate({scrollTop: 0}, "slow");
                                $.each(result.message, (index, value) => {
                                    if (index != "msgErr") {
                                        console.log(`#timepicker${value}`);
                                        $(`#timepicker${value}`).removeClass('border-dark');
                                        $(`#timepicker${value}`).addClass('border-danger');
                                    }
                                });
                            } else if (result.data == 'error') {
                                $('#loading').removeClass('d-block');
                                TEACHER_ADD_SCHEDULE.addMessageErrorCommon(result.message);
                                $("html, body").animate({scrollTop: 0}, "slow");
                            }
                        } else {
                            $('#listSchedule').submit();
                        }
                    },
                    error: function error(error) {
                        $('#loading').removeClass('d-block');
                        alert("Error Server");
                    }
                });
            }
            else {
                TEACHER_ADD_SCHEDULE.addMessageErrorCommon(M007);
            }
        });
    }

    TEACHER_ADD_SCHEDULE.clickButtonClear = () => {
        $('#btnClear').click(function (e) {
            for (var i = 1; i <= 7; i++) {
                $(`#row${i}`).html('');
                var index = i;
                var temp = `<div class="d-flex" id="divTimepicker${index}-0">
                            <div class="mb-2 mr-2" id="removeTimepicker${index}-0">
                            </div>
                            <input type="text"
                                id="timepicker${index}-0"
                                class="bs-timepicker border border-dark mr-lg-5 mb-3 mt-1 text-center timepicker${i}"
                                style="width: 65px" value="" readonly/>
                            </div>`;
                $(`#row${index}`).append(temp);

                temp = `<img class="removeTimepicker position-absolute" style="width: 15px; height: 15px"
                    src="${getRemoveImage}" id="${index}-0">`

                $(`#removeTimepicker${index}-0`).append(temp);
                $(`#${index}-0`).click(function () {
                    $(`#divTimepicker${this.id}`).remove();
                });
            }
            $('.bs-timepicker').timepicker();
            TEACHER_ADD_SCHEDULE.countNumofTimepicker();
            console.log("2 ", lengthTemp);
            $('#error_section').html('');
            $("html, body").animate({scrollTop: 0}, "slow");
        });
    };
    TEACHER_ADD_SCHEDULE.addMessageErrorCommon = (message) => {
        $('#error_section').html(`
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <i class="icon fa fa-ban"></i>
                <span id="error_mes">${message}</span>
            </div>
        `);
    };

});
$(document).ready(function () {
    TEACHER_ADD_SCHEDULE.init();
});

