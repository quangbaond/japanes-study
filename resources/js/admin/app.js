let APP = {};
let bootBoxConfirmTrue = $("[name=bootbox-confirm-true]").attr('content');
let bootBoxConfirmFalse = $("[name=bootbox-confirm-false]").attr('content');

$(function () {
    APP.init = function () {
        $('.select2').select2();
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
        APP.choiceNotification();
        APP.datepicker();
        APP.formatVisaCard();
    };

    APP.choiceNotification = function () {
        $("body").on('click', '.choice_notification', function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let formData = {
                id: $(this).data("id")
            };
            $(this).remove();
            $.ajax({
                type: "GET",
                url: '/notification/detail',
                data: formData,
                success: function(result){
                    console.log(result);
                    if (result.status) {
                        $('#modal_notification').modal('show');
                        $('#modal_notification').find('.modal-content').html(result.data['content']);

                        //Add 1 for count_unread_notification
                        if (result.data['read_at'] == "0") {
                            let count_unread_notification = $("#count_unread_notification").text();
                            $("#count_unread_notification").text(parseInt(count_unread_notification) - 1);
                        }

                    } else {
                        alert("Error");
                    }
                },
                error: function(result){
                    console.log(result);
                }
            });

        });
    };

    APP.datepicker = function() {
        $(".datepicker").datepicker({
            format: 'yyyy/mm/dd',
            todayHighlight: true,
            autoClose: true,
            forceParse :false,
            // keepInvalid:true
            // autocomplete:false,
        })
    }
    /*
       Function format card visa for input
    */
    APP.formatVisaCard = function() {
        $( ".format_visa" ).keypress(function(e) {
            if ((e.which < 48 || e.which > 57) && (e.which !== 8) && (e.which !== 0)) {
                return false;
            }
            let value = APP.formatNumber($(this).val());
            $(this).val(value);
        });
    };

    /*
       Format number for input
    */
    APP.formatNumber = function(value) {
        let v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
        let matches = v.match(/\d{4,16}/g);
        let match = matches && matches[0] || '';
        let parts = [];

        for (i=0, len=match.length; i<len; i+=4) {
            parts.push(match.substring(i, i+4))
        }
        if (parts.length) {
            return parts.join(' ')
        } else {
            return value
        }
    }

});

$(document).ready(function () {
    APP.init();
});

window.common = {
    bootboxConfirm: function(msg, size, callback){
        bootbox.confirm({
            message: msg,
            size: size,
            buttons: {
                confirm: {
                    label: 'はい',
                    className: 'btn-primary btn-sm mr-6 btn-flat'
                },
                cancel: {
                    label: 'いいえ',
                    className: 'btn-default btn-sm btn-flat'
                }
            },
            callback: function (result) {
                callback(result);
            }
        });
    },

    bootboxConfirmMultiLanguage: function(msg, size, callback){
        bootbox.confirm({
            message: msg,
            size: size,
            buttons: {
                confirm: {
                    label: bootBoxConfirmTrue,
                    className: 'btn-primary btn-sm btn-flat'
                },
                cancel: {
                    label: bootBoxConfirmFalse,
                    className: 'btn-default btn-sm btn-flat'
                }
            },
            callback: function (result) {
                callback(result);
            }
        });
    },

    bootboxAlert: function(msg, size){
        bootbox.alert({
            message: msg,
            size: size,
            buttons: {
                ok: {
                    label: 'OK',
                    className: 'btn-default btn-sm btn-flat'
                }
            }
        });
    },

    clearValueFormSearch: function(isThis, formSearchClear) {
        isThis.closest(formSearchClear).find('.select2').val(null).trigger('change');
        isThis.closest(formSearchClear).find('input, select').val('');
        isThis.closest(formSearchClear).find('input').removeClass('is-invalid');
        isThis.closest(formSearchClear).find('.invalid-feedback-custom').html('');
    },

    getToken: function(){
        return $("[name=csrf-token]").attr('content');
    },
};
