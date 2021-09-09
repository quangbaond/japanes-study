let STUDENT_LESSON_HISTORY = {};
let RouteGetListHistory = $("[name=route-list-history-datatable]").attr('content');
let student_email = $("[name=student-email]").attr('content');
let google_form_url = "https://docs.google.com/forms/d/e/1FAIpQLSezjtn7Cfsmy6DEIgZHmFDSD7Kd7Ut-IFxshFa15uyZ9MRFrg/viewform";
let language = $("[name=language]").attr('content');
$(function () {
    var comment = "";
    STUDENT_LESSON_HISTORY.init = function () {
        STUDENT_LESSON_HISTORY.listHistoryDatatable();
        STUDENT_LESSON_HISTORY.clickBtnClaim();
    }

    STUDENT_LESSON_HISTORY.clickBtnClaim = () => {

    }
    STUDENT_LESSON_HISTORY.listHistoryDatatable = () => {
        $(function () {
            $('[data-toggle="tooltip"]').tooltip()
        });
        $('body').tooltip({selector: '[data-toggle="tooltip"]'});
        var table = $('#list_history').DataTable({
            'lengthChange': false,
            'searching': false,
            'autoWidth': false,
            'ordering': true,
            'pagingType': "full_numbers",
            'pageLength': 10,
            language: {
                "url": language
            },
            "order": [[0, "desc"], [1, 'desc']],
            processing: true,
            serverSide: true,
            ajax: {
                url: RouteGetListHistory,
                type: 'GET',
            },
            columns: [
                {data: 'date_name'},
                {
                    data: 'time', render: function (data) {
                        time = data.split(':');
                        return time[0] + ":" + time[1];
                    }
                },
                {data: 'teacher_id'},
                {data: 'nickname', class: 'teacher_nickname'},
                {data: 'email', class: 'teacher_email'},
                {data: 'course_name', defaultContent: "", class: 'course_name'},
                {data: 'lesson_name', defaultContent: "", class: 'lesson_name'},
                {data: 'coin'},
                {data: 'history_status', class: 'history_status'},
                {data: 'btn_claim'},
                {data: 'btn_review'},
            ],
            "createdRow": function (row, data, rowIndex) {
                $.each($('td[class=" teacher_nickname"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" course_name"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" lesson_name"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" teacher_email"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" history_status"]', row), function (colIndex, data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data)[0].innerText);
                });
            },
            "columnDefs": [
                {
                    "targets": 8,
                    "orderable": false
                },
                {
                    "targets": 9,
                    "orderable": false
                },
                {
                    "targets": 10,
                    "orderable": false
                }
            ],
            "initComplete": function (settings) {
                $('#list_history thead th').each(function () {
                    var $td = $(this);
                    $td.attr('title', $td.text());
                });

                /* Apply the tooltips */
                $('#list_history thead th[title]').tooltip(
                    {
                        "container": 'body'
                    });
            }
        });

        $('#list_history tbody').on('click', 'td button#claim', function (e) {
            var data_row = table.row($(this).closest('tr')).data();
            console.log(data_row);
            time = data_row['time'].split(':');
            date = data_row['date'].split('-');
            console.log(date);
            data = "?entry.2056352704=" + data_row['nickname'] + "&entry.914870771=" + data_row['email']
                + "&entry.801451962_hour=" + time[0] + "&entry.801451962_minute=" + time[1]
                + "&entry.2049001707_year=" + date[0] + "&entry.2049001707_month=" + date[1]
                + "&entry.2049001707_day=" + date[2] + "&entry.1483914846=" + student_email;
            window.open(google_form_url + "" + data, '_blank');
        });
        $('#list_history tbody').on('click', '#review', function () {
            var data_row = table.row($(this).closest('tr')).data();

            if (data_row['comment'] != null) {
                if (data_row['comment'].includes('&amp;')) {
                    comment = data_row['comment'].replace('&amp;', "&");
                } else if (data_row['comment'].includes('&lt;')) {
                    comment = data_row['comment'].replace('&lt;', "<");
                } else if (data_row['comment'].includes("&gt;")) {
                    comment =  data_row['comment'].replace("&gt;", ">");
                } else if (data_row['comment'].includes('&quot;')) {
                    comment = data_row['comment'].replace('&quot;', "\"");
                } else if (data_row['comment'].includes('&#x27;')) {
                    comment = data_row['comment'].replace('&#x27;', "'");
                } else if (data_row['comment'].includes("&#x60;")) {
                    comment = data_row['comment'].replace("&#x60;", "`");
                }
                else {
                    comment = data_row['comment']
                }
            }
            if(comment != data_row['comment'] || $('#modal_review_lesson').find('textarea#comment').text() != data_row['comment']){
                $('#modal_review_lesson').find('textarea#comment').val(data_row['comment']);
            }
            else {
                $('#modal_review_lesson').find('textarea#comment').text(comment);
            }
            $('#modal_review_lesson').find('input#id_lesson').val(data_row['id']);

            if (data_row['teacher_review_id'] != null) {
                $('#modal_review_lesson').find('input#id_teacher_review').val(data_row['teacher_review_id']);
            }
            $('.rating-symbol').css('width' , '50px')
            $('#modal_review_lesson').find('input[name=star]').rating('rate', data_row['star']);
            $('#modal_review_lesson').modal('show');
        });
    }
});

$(document).ready(function () {
    STUDENT_LESSON_HISTORY.init();
});
