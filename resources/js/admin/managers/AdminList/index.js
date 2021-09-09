const ADMINLIST = {};
const routeGetListAdmins = $("[name=route-get-list-admins]").attr('content');
const routeValidateSearchForm =  $("[name=route-validate-search-form]").attr('content');
const routeDeleteAdmins = $("[name=route-delete-admins]").attr('content');
const notificationConfirmDelete = $("[name=confirm-delete]").attr('content');
const messageDeleteSuccess = $("[name=delete-success]").attr('content');


$(function () {
    ADMINLIST.init = function () {
        ADMINLIST.checkRecord()
        ADMINLIST.listAdmin();
        ADMINLIST.validateSearchForm();
        ADMINLIST.clearForm();
        ADMINLIST.deleteAdmins()
    }

    ADMINLIST.listAdmin = function() {
        $(document).ready(function () {
            $('#admins').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "responsive": true,
                "pagingType": "full_numbers",
                "order": [[ 8, "desc" ]],
                'autoWidth'   : false,
                language: {
                    "url": "/Japanese.json"
                },
                processing: true,
                serverSide: true,
                ajax: {
                    url: routeGetListAdmins,
                    type: 'GET',
                    data: function (d) {
                        d.email             = $('#email').val();
                        d.admin_id          = $('#admin_id').val();
                        d.phone_number      = $('#phone_number').val();
                        d.area_code         = $('#area_code option:selected').val();
                        d.role              = $('#role option:selected').val();
                        d.from_date         = $('#from_date').val();
                        d.to_date           = $('#to_date').val();
                    }
                },
                columns: [
                    { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                    { data: 'id', name: 'id',},
                    { data: 'nickname', name: 'nickname', class : 'nickname' },
                    { data: 'email', name: 'email', class:'email'},
                    { data: 'phone_number', name: 'phone_number' },
                    { data: 'role', name: 'role' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'action', name: 'action', orderable: false },
                    { data: 'originalSearch', name: 'originalSearch', "visible": false},
                ],
                "createdRow": function (row, data, rowIndex) {
                    $.each($('td[class=" nickname"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).html());
                    });
                    $.each($('td[class=" email"]', row), function (colIndex,data) {
                        $(this).attr('data-toggle', "tooltip");
                        $(this).attr('data-placement', "top");
                        $(this).attr('data-original-title', $(data).html());
                    });
                }
            });
        });
    }

    ADMINLIST.validateSearchForm = function() {
        $("#btnSearch").click(() => {
            if(checkInternet()) {
                $('#loading').removeClass('d-none');
                $('#loading').addClass('d-block');

                let invalid = true;
                $('#searchAdmins').find('select,input').each(function() {
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

                let data = $('#formSearchStudent').serializeArray();

                // Clear error
                $('body').find('input').removeClass('is-invalid');
                $(".invalid-feedback-custom").html(''); // Get data form search teacher
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
                            $.each(result.message, function (key, value) {
                                if (typeof value !== "undefined") {
                                    $('#' + key).addClass('is-invalid');
                                    $('#' + key).closest('.form-group').find('span[role=alert]').html(value[0]);
                                }
                            });
                        } else {
                            //submit form
                            $('#admins').DataTable().draw(true);
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

    ADMINLIST.checkRecord = function () {
        // check all
        $('#check_all').on('click', function(e) {
            let check = $(".chk_item");
            $("#formDeleteAllAdmin").find("input[name='user_id[]'").remove();
            if($(this).prop("checked")) {
                if (check.length > 0) {
                    $("#delete-all-admin").attr("disabled", false);
                }
                check.prop('checked', true);
                check.each(function() {
                    $("#formDeleteAllAdmin").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
                });
            } else {
                check.prop('checked',false);
                $("#delete-all-admin").attr("disabled", true);
                check.each(function() {
                    $("#id-" + $(this).val()).remove();
                });
            }
        });

        $('#admins').on('draw.dt', function() {
            $("#formDeleteAllAdmin").find("input[name='user_id[]'").remove();
            $("#check_all").prop('checked', false);
            $("#delete-all-admin").attr("disabled", true);
        });

        //check item
        $("body").on("change", ".chk_item", function(){
            if (false == $(this).prop("checked")) {
                $("#check_all").prop('checked', false);
            };
            if ( $('.chk_item:checked').length == $('.chk_item').length ) {
                $("#check_all").prop('checked', true);
            };
            if ($(this).prop("checked")) {
                $("#formDeleteAllAdmin").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
            } else {
                $("#id-" + $(this).val()).remove();
            }
            if ($('.chk_item:checked').length > 0) {
                $("#delete-all-admin").attr("disabled", false);
            } else {
                $("#delete-all-admin").attr("disabled", true);
            }
        });
    }

    ADMINLIST.clearForm = function () {
        $('#btnClearForm').on('click', function () {
            let isThis = $(this);
            let formSearchClear = '.form-search-clear';
            common.clearValueFormSearch(isThis, formSearchClear);
            $("#error_section").css('display', 'none');
            $('#area_message').html('');
            $('#admins').DataTable().draw(true);
        })
    }

    ADMINLIST.deleteAdmins = function () {
        $('#delete-all-admin').on('click', function () {

            common.bootboxConfirm(notificationConfirmDelete, 'small', function (r) {
                if (r) {
                    if(checkInternet()) {
                        $('#loading').removeClass('d-none');
                        $('#loading').addClass('d-block');


                        let data = $("#formDeleteAllAdmin").serialize(); // Ajax
                        $.ajax({
                            type: "POST",
                            url: routeDeleteAdmins,
                            data: data,
                            success: function success(result) {
                                $('#loading').removeClass('d-block');
                                $('#loading').addClass('d-none');
                                if (result.status) {
                                    $('#area_message').html(`
                                    <section class="content-header">
                                        <div class="alert alert-success alert-dismissible">
                                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                                            <i class="icon fa fa-check"></i>
                                            ${messageDeleteSuccess}
                                        </div>
                                    </section>
                                `);
                                    $(window).scrollTop(0);
                                    $('#admins').DataTable().draw(true);
                                } else {
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
                            },
                            error: function error(_error) {
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
                }
            });
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
    ADMINLIST.init()
})
