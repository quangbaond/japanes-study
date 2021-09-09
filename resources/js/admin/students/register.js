var Days = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];// index => month [0-11]
$(document).ready(function () {
    //format birthday
    var option = '<option value="day"></option>';
    let selectedDay = "day";
    for (let i = 1; i <= Days[0]; i++) {
        option += '<option value="' + i + '">' + i + '</option>';
    }
    $('#day').append(option);
    $('#day').val(selectedDay);

    var option = '<option value="month"></option>';
    let selectedMon = "month";
    for (let i = 1; i <= 12; i++) {
        option += '<option value="' + i + '">' + i + '</option>';
    }
    $('#month').append(option);
    $('#month').val(selectedMon);

    let d = new Date();
    var option = '<option value="year"></option>';
    let selectedYear = "year";
    for (let i = 1930; i <= d.getFullYear(); i++) {
        option += '<option value="' + i + '">' + i + '</option>';
    }
    $('#year').append(option);
    $('#year').val(selectedYear);

    // register confirm
    $('#remember').on('click', function (e) {
        if ($(this).is(':checked')) {
            $('#submitButton').removeAttr("disabled");
        } else {
            $('#submitButton').attr("disabled", "true");
        }
    });

    for (i = 1; i <= 3; i++) {
        $(`#checkboxPrimary${i}`).on('click', function (e) {
            if ($(this).is(':checked')) {
                i = this.id.split('checkboxPrimary')[1];
                for (j = 1; j <= 3; j++) {
                    if (j != i) {
                        $(`#checkboxPrimary${j}`).prop('checked', false); // Checks it
                    }
                }
            }
        });
    }
});

function isLeapYear(year) {
    year = parseInt(year);
    if (year % 4 != 0) {
        return false;
    } else if (year % 400 == 0) {
        return true;
    } else if (year % 100 == 0) {
        return false;
    } else {
        return true;
    }
}

function change_year(select) {
    if (isLeapYear($(select).val())) {
        Days[1] = 29;
    } else {
        Days[1] = 28;
    }
    if ($("#month").val() == 2) {
        let day = $('#day');
        let val = $(day).val();
        $(day).empty();
        let option = '<option value="day"></option>';
        for (let i = 1; i <= Days[1]; i++) {
            option += '<option value="' + i + '">' + i + '</option>';
        }
        $(day).append(option);
        if (val > Days[month]) {
            val = 1;
        }
        $(day).val(val);
    }
}

function change_month(select) {
    let day = $('#day');
    var val = $(day).val();
    $(day).empty();
    var option = '<option value="day"></option>';
    let month = parseInt($(select).val()) - 1;
    for (var i = 1; i <= Days[month]; i++) {
        option += '<option value="' + i + '">' + i + '</option>';
    }
    $(day).append(option);
    if (val > Days[month]) {
        val = 1;
    }
    $(day).val(val);
}
