let MANAGER_BOOKING_LIST = {};
let routeDataTables = $("[name=route-data-tables]").attr('content');
let routeSearchLiveNickname = $("[name=route-search-live-nickname]").attr('content');
let routeSearchLiveEmail = $("[name=route-search-live-email]").attr('content');
let routeValidateSearchForm = $('[name=route-search-form]').attr('content');
let routeGetBookingDetail = $('[name=route-get-booking-detail]').attr('content');
let routeDeleteBooking = $('[name=route-delete-booking]').attr('content');
$(function() {
    MANAGER_BOOKING_LIST.init= () => {
        MANAGER_BOOKING_LIST.dataTables();
        MANAGER_BOOKING_LIST.searchLive();
        MANAGER_BOOKING_LIST.searchForm();
        MANAGER_BOOKING_LIST.removeBooking();
    }

    MANAGER_BOOKING_LIST.dataTables = () => {
        var table = $('#bookingHistory').DataTable({
            'lengthChange': false,
            'searching'   : false,
            "order": [[ 8, "asc" ]],
            'autoWidth'   : false,
            "pagingType": "full_numbers",
            language: {
                "url": "/Japanese.json"
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: routeDataTables,
                type: 'GET',
                data: function (d) {
                    d.email             = $('#email').val();
                    d.nickname         = $('#user_id').val();
                    d.from_date         = $('#from_date').val();
                    d.to_date           = $('#to_date').val();
                }
            },
            columns: [
                { data: 'start_date', name: 'start_date' },
                { data: 'start_hour', name: 'start_hour', class : 'start_hour' },
                { data: 'teacher_id', name: 'teacher_id', class:'teacher_id'},
                { data: 'teacher_email', name: 'teacher_email', class : 'teacher_email' },
                { data: 'student_id', name: 'student_id' },
                { data: 'student_email', name: 'student_email', class : 'student_email' },
                { data: 'coin', name: 'coin '},
                { data: 'action', name: 'action', orderable: false },
                { data: 'created_at', name: 'created_at', "visible": false},
                { data: 'booking_id', name: 'booking_id', "visible": false},
            ],
            "createdRow": function (row, data, rowIndex) {
                $.each($('td[class=" teacher_email"]', row), function (colIndex,data) {
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
        $('#bookingHistory tbody').on('click', 'td button', function (e) {
            var data_row = table.row($(this).closest('tr')).data();
            let booking_id = data_row.booking_id;
            if(checkInternet()) {
                $('#loading').removeClass('d-none');
                $('#loading').addClass('d-block');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "get",
                    url: routeGetBookingDetail,
                    data: {
                        id: booking_id
                    },
                    success: function(result){
                        $('#loading').removeClass('d-block');
                        $('#loading').addClass('d-none');
                        if (!result.status) {
                            if (result.message.to_date) {
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
                        } else {
                            let time = result.data.start_hour.split(':');

                            //submit form
                            $('#booking_id').val(result.data.booking_id);
                            $('#teacher_nickname').html(result.data.teacher_email);
                            $('#student_nickname').html(result.data.student_email);
                            $('#start_date').html(result.data.start_date);
                            $('#start_hour').html(time[0] + ":" + time[1]);
                            $('#coin').html(result.data.coin);
                            $('#modalRemoveBookingByAdmin').modal('show');
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
        });
    }

    MANAGER_BOOKING_LIST.searchLive = () => {
        $.fn.select2.defaults.set('language', {
            noResults: function () {
                return " 該当がありません";
            },
            searching: function () {
                return "検索中";
            }
        });
        $('#user_id').select2({
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
        $('#email').select2({
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

    MANAGER_BOOKING_LIST.searchForm = () => {
        $("#btnSearch").click(() => {
            if(checkInternet()) {
                $('#loading').removeClass('d-none');
                $('#loading').addClass('d-block');

                let invalid = true;
                $('#searchTeacher').find('select,input').each(function() {
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

                let data = $('#searchForm').serializeArray();
                //clear error
                $("#to_date").removeClass("is-invalid");
                $('#error_date').html("");
                $("#error_date").html("");
                $("#from_date").removeClass("is-invalid");
                $("#error_date").html("");
                $("#to_date").removeClass("is-invalid");

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
                            if (result.message.to_date) {
                                $("#error_date").html(result.message.to_date);
                                $("#to_date").addClass("is-invalid");
                            }
                            if (result.message.format_date_from) {
                                $("#error_date").html(result.message.format_date_from);
                                $("#from_date").addClass("is-invalid");
                            }
                            if (result.message.format_date_to) {
                                $("#error_date").html(result.message.format_date_to);
                                $("#to_date").addClass("is-invalid");
                            }
                        } else {
                            //submit form
                            $('#bookingHistory').DataTable().draw(true);
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

    MANAGER_BOOKING_LIST.removeBooking = () => {
        $('#btnConfirmRemove').on('click', function (e) {
            let booking_id = $('#booking_id').val();
            if(checkInternet()) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: "post",
                    url: routeDeleteBooking,
                    data: {
                        id: booking_id
                    },
                    success: function(result){
                        $('#loading').removeClass('d-block');
                        $('#loading').addClass('d-none');
                        $('#modalRemoveBookingByAdmin').modal('hide');
                        if (!result.status) {
                            $('#area_message').html(`
                                        <section class="content-header">
                                            <div class="alert alert-danger alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                <i class="icon fa fa-ban"></i>
                                                 ${result.message}
                                            </div>
                                        </section>
                                    `);
                        } else {
                            $('#area_message').html(`
                                                <section class="content-header">
                                                    <div class="alert alert-success alert-dismissible">
                                                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                                        <i class="icon fa fa-check"></i>
                                                         ${result.message}
                                                    </div>
                                                </section>
                                            `);
                            $("html, body").animate({ scrollTop: 0 }, "slow");
                            //draw again
                            $('#bookingHistory').DataTable().draw(true);
                        }
                    },
                    error: function(result){
                        $('#modalRemoveBookingByAdmin').modal('hide');
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
});
$(document).ready(() => {
    MANAGER_BOOKING_LIST.init();
})
