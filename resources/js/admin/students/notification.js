let STUDENT_NOTIFICATION = {};
let routeNotification = $("[name=route-notification-list]").attr('content');
let empty_table = $("[name=lang_table_empty_table]").attr('content');
let no_result = $("[name=lang_table_no_result]").attr('content');
$(function() {
    STUDENT_NOTIFICATION.init = function() {
        STUDENT_NOTIFICATION.Table()
        STUDENT_NOTIFICATION.eventInput()
        STUDENT_NOTIFICATION.validationNotification()
        STUDENT_NOTIFICATION.clearForm()
    }
    STUDENT_NOTIFICATION.Table = function () {
        $('#notifications').DataTable({
            drawCallback: function () {
                let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                pagination.toggle(this.api().page.info().pages > 0);
                info.toggle(this.api().page.info().pages > 0);
            },
            'lengthChange': false,
            'searching': false,
            "order": [[3, "desc"]],
            'autoWidth': false,
            "pagingType": "full_numbers",

            language: {
                paginate: {
                  next: '>', // or '→'
                  previous: '<', // or '←'
                  sFirst: '<<',
                  sLast : '>>',
                },
                "emptyTable": empty_table,
                // "zeroRecords": "No match was found for your search",
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: routeNotification,
                type: 'GET',
                data: function (d) {
                    d.title = $('#title').val();
                    d.created_at_from = $('#created_at_from').val();
                    d.created_at_to = $('#created_at_to').val();
                },
            },
            columnDefs: [
                { targets: 'no-sort', orderable: false }
            ],
            columns: [
                {data: 'title', name: 'title', class: 'title'},
                {data: 'created_by', name: 'created_by' , class : "created_by"},
                {data: 'user_created_at', name: 'user_created_at'},
                {data: 'btn_notification_detail', name: 'btn_notification_detail' , class:"text-center"},
            ],

            "createdRow":
                function (row, data, rowIndex) {
                    $.each($('td[class=" title"]', row), function (colIndex, data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).text());
                    });
                    $.each($('td[class=" created_by"]', row), function (colIndex, data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).text());
                    });
                }
        })
    }
    STUDENT_NOTIFICATION.eventInput = function () {
        $('#search').change(function(e) {
            var value = e.target.value
            if(value == "created_at"){
                $('#inputCreated').removeClass('d-none')
                $('#inputTitle').addClass('d-none')
            }
            else if(value == "title"){
                $('#inputTitle').removeClass('d-none')
                $('#inputCreated').addClass('d-none')
            }
        })
    }
    STUDENT_NOTIFICATION.validationNotification = function () {
        $('#btnSearch').click(function () {

            $("#error_section").css('display', 'none');
            let invalid = true;
            $('#searchNotification').find('input').each(function () {
                if ($(this).val() != '') {
                    invalid = false;
                }
                else if($('.itemEmail').val() != null){
                    invalid = false;
                }
            });
            if (invalid) {
                $("#error_mes").html($('[name=error-input]').attr('content'));
                $("#error_section").css('display', 'block');
                return false;
            }

            // Clear error
            $('body').find('input').removeClass('is-invalid');
            $(".invalid-feedback-custom").html(''); // Get data form search teacher
            var data = $("#formSearchNotification").serialize(); // Ajax
            let routeNotificationValidation = $("[name=route-notification-validation]").attr('content');
            $.ajax({
                type: "POST",
                url: routeNotificationValidation,
                data: data,
                success: function success(result) {
                    if (result.status) {
                        $('#notifications').DataTable().draw(true)
                        var table = $('#notifications').DataTable()
                        table.on( 'draw', function () {
                            $('.dataTables_empty').text(no_result);
                        } );
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
    }
    STUDENT_NOTIFICATION.clearForm = function () {
        $('#btnClearForm').click(function () {
            $('.invalid-feedback-custom').html("")
            $('form#formSearchNotification').trigger('reset')
            $('input[name=title]').val("")
            $('input[name=created_at_from]').val("")
            $('input[name=created_at_to]').val("")
            $('input').removeClass('is-invalid');
            $('#area_message').html('');
            $('#inputCreated').addClass('d-none')
            $('#inputTitle').removeClass('d-none')
            $('.itemName').val('').trigger('change')
            $('#notifications').DataTable().draw(true)
        })
    }
})
$(document).ready(function() {
    STUDENT_NOTIFICATION.init();
});
