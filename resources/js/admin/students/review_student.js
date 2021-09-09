let STUDENT_REVIEW = {};
let routeReview = $("[name=route-review-student]").attr('content');
$(function() {
    STUDENT_REVIEW.init = function () {
        STUDENT_REVIEW.getLessonHistories();
        STUDENT_REVIEW.processTextaria();
    }
    STUDENT_REVIEW.getLessonHistories = function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url : routeReview,
            data : { id_lesson : window.localStorage.getItem('review')},
            method : 'post',
            dataType : 'json',
            success : function (data) {
                if(data.status) {
                    $('#modal_review_lesson').find('#id_lesson').val(data.data)
                    $('#close_review').click(function () {
                        window.localStorage.setItem('review' , data.data)
                    })
                    $('.rating-symbol').css('width' , '50px')
                    $('#modal_review_lesson').modal('show')
                    // $('.')
                    $('#modal_review_lesson').modal({backdrop: 'static', keyboard: false})
                }
            }
        })
    }
    STUDENT_REVIEW.processTextaria = function () {
        $('#comment').on('keyup', function () {
            var len = this.value.length;
            var maxlen = parseInt($(this).attr('maxlength'))
            if (len > maxlen) {
                this.value = this.value.substring(0, maxlen);
            } else {
                $('#commentLength').text(`${len} / ${maxlen}`);
            }
        });
    }
})
$(document).ready(function() {
    STUDENT_REVIEW.init();
});
