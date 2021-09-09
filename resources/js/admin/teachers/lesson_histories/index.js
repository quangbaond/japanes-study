const LESSON_HISTORIES = {};

const routeGetLessonHistories = $("[name=route-get-list-lesson-histories]").attr('content');
const routeSearchLiveNickname = $("[name=route-search-live-nickname]").attr('content');
const routeSearchLiveEmail = $("[name=route-search-live-email]").attr('content');
const routeValidateSearchForm = $('[name=route-search-form]').attr('content');

$(function () {
    LESSON_HISTORIES.init = function () {
        LESSON_HISTORIES.dataTables()
        LESSON_HISTORIES.searchLive();
        LESSON_HISTORIES.searchForm();
        LESSON_HISTORIES.clearForm();
    }
    LESSON_HISTORIES.dataTables = function() {
        var table = $('#lessonHistories').DataTable({
            'lengthChange': false,
            'searching'   : false,
            "order": [[ 9, "desc" ]],
            'autoWidth'   : false,
            "pagingType": "full_numbers",
            language: {
                "url": "/Japanese.json"
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: routeGetLessonHistories,
                type: 'GET',
                data: function (d) {
                    d.email             = $('#studentEmail').val();
                    d.nickname         = $('#studentID').val();
                    d.date_from         = $('#date_from').val();
                    d.date_to           = $('#date_to').val();
                }
            },
            columns: [
                { data: 'lesson_histories_date', name: 'lesson_histories_date' },
                { data: 'lesson_histories_time', name: 'lesson_histories_time', class : 'lesson_histories_time' },
                { data: 'student_id', name: 'student_id', class:'student_id'},
                { data: 'student_nickname', name: 'student_nickname', class : 'student_nickname' },
                { data: 'student_email', name: 'student_email', class : 'student_email' },
                { data: 'course_name', name: 'course_name '},
                { data: 'lesson_content', name: 'lesson_content '},
                { data: 'lesson_histories_coin', name: 'lesson_histories_coin '},
                { data: 'action', name: 'action' },
                { data: 'created_at', name: 'created_at', "visible": false},
            ],
            "createdRow": function (row, data, rowIndex) {
                $.each($('td[class=" student_nickname"]', row), function (colIndex,data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
                $.each($('td[class=" student_email"]', row), function (colIndex,data) {
                    $(this).attr('data-toggle', "tooltip");
                    $(this).attr('data-placement', "top");
                    $(this).attr('data-original-title', $(data).html());
                });
            }
        });
    }

    LESSON_HISTORIES.searchLive = function () {
        $.fn.select2.defaults.set('language', {
            noResults: function () {
                return " 該当がありません";
            },
            searching: function () {
                return "検索中";
            }
        });
        $('#studentID').select2({
            ajax: {
                url: routeSearchLiveNickname,
                dataType: 'json',
                delay: 100,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.nickname,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('#studentEmail').select2({
            ajax: {
                url: routeSearchLiveEmail,
                dataType: 'json',
                delay: 100,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.email,
                                id: item.email
                            }
                        })
                    };
                },
                cache: true
            }
        });
    }


    LESSON_HISTORIES.searchForm = function() {
        $("#btnSearch").click(() => {
            if(checkInternet()) {
                $('#loading').removeClass('d-none');
                $('#loading').addClass('d-block');

                let invalid = true;
                $('#searchLessonHistories').find('select,input').each(function() {
                    if ($(this).val() != '' ) {
                        invalid = false;
                    }
                });
                if (invalid){
                    $('#loading').removeClass('d-block');
                    $('#loading').addClass('d-none');
                    $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     検索項目を入力してください。
                                </div>
                            </section>
                    `);
                    return false;
                }

                let data = $('#formSearchLessonHistories').serializeArray();
                //clear error
                $("#date_to").removeClass("is-invalid");
                $('#err_date').html("");
                $("#err_date").html("");
                $("#from_date").removeClass("is-invalid");
                $("#err_date").html("");
                $("#date_to").removeClass("is-invalid");

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: routeValidateSearchForm,
                    data: data,
                    success: function(result){
                        $('#loading').removeClass('d-block');
                        $('#loading').addClass('d-none');
                        if (!result.status) {
                            if (result.message.date_to) {
                                $("#err_date").html(result.message.date_to);
                                $("#date_to").addClass("is-invalid");
                            }
                            if (result.message.format_date_from) {
                                $("#err_date").html(result.message.format_date_from);
                                $("#from_date").addClass("is-invalid");
                            }
                            if (result.message.format_date_to) {
                                $("#err_date").html(result.message.format_date_to);
                                $("#date_to").addClass("is-invalid");
                            }
                        } else {
                            //submit form
                            $('#lessonHistories').DataTable().draw(true);
                        }
                    },
                    error: function(result){
                        $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     更新が失敗しました。
                                </div>
                            </section>
                    `);
                    }
                });
            }
        })
    }

    LESSON_HISTORIES.clearForm = function () {
        $('#btnClearForm').click( function () {
            //clear error
            $("#date_to").removeClass("is-invalid");
            $('#err_date').html("");
            $("#err_date").html("");
            $("#from_date").removeClass("is-invalid");
            $("#err_date").html("");
            $("#date_to").removeClass("is-invalid");
            $('#searchLessonHistories').find('input').each(function() {
                if ($(this).val() != '' ) {
                    $(this).val('');
                }
            });
            $(".select2").val(null).trigger('change');
            $('#lessonHistories').DataTable().draw(true);
        })
    }
    function checkInternet() {
        let ifConnected = window.navigator.onLine;
        if(ifConnected) {
            $('#area_message').html('');
            return true;
        }else {
            $('#area_message').html(`
                            <section class="content-header">
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                    <i class="icon fa fa-ban"></i>
                                     更新が失敗しました。
                                </div>
                        </section>
                    `);
            $("html, body").animate({ scrollTop: 0 }, "slow");
        }
    }
})
$(document).ready(function () {
    LESSON_HISTORIES.init()
})
