let STUDENT_PAYMENT_HISTORIES = {};
var routeGetPaymentHistories = $("[name=route-get-data-payment-histories]").attr('content');
$(() => {
    STUDENT_PAYMENT_HISTORIES.init = () => {
        STUDENT_PAYMENT_HISTORIES.getData();
    }

    STUDENT_PAYMENT_HISTORIES.getData = () => {

    }
})
$(document).ready(function(){
    STUDENT_PAYMENT_HISTORIES.init();
});
