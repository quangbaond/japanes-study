const CourseDetail = {};

$(function(){
    CourseDetail.init = function() {
        CourseDetail.showVideo();
    }

    CourseDetail.showVideo = function() {
        $('body').on('click', '.btnShowVideo', function () {
            let videoLink = $(this).attr('data-video_link');
            let html = `<video id="clip" controls preload=auto playsinline muted autoplay class="intro-video" data-setup="{}">
                        <source src="${videoLink}" type='video/mp4'/>
                    </video>`;
            $('#modalShowVideo').find('.modal-body').html(html);
            $('#modalShowVideo').modal('show');
        })
    }
})
$(document).ready(function () {
    CourseDetail.init()
})
