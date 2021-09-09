let MANAGE_PAYMENT_INTENTS = {};
const routeValidateSearchForm = $("[name=route-validate-search-form]").attr('content');

$(function () {
    MANAGE_PAYMENT_INTENTS.init = () => {
        MANAGE_PAYMENT_INTENTS.validateSearchForm();
    }

    MANAGE_PAYMENT_INTENTS.validateSearchForm = () => {
        $('#btnSearch').on('click', () => {
            let invalid = true;
            $('#formSearchPaymentIntents').find('select,input').each(function() {
                if ($(this).val() != '' ) {
                    invalid = false;
                }
            });
            if (invalid){
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

            let data = $('#formSearchPaymentIntents').serializeArray();
            //clear error
            $("#to_date").removeClass("is-invalid");
            $('#error_date').html("");

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
                    } else {
                        //submit form
                        $('#formSearchPaymentIntents').submit();
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
        });
    }
})
$(document).ready(function () {
    MANAGE_PAYMENT_INTENTS.init();
})
