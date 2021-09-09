<!-- Modal -->
<div class="modal fade" id="modalNotificationWhenBooked" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <input type="hidden" value ="" id="start_hour">
                <input type="hidden" value ="" id="start_date">
                <input type="hidden" value ="" id="lesson_id">
                <input type="hidden" value ="" id="course_id">
                <input type="hidden" value ="" id="student_id">
                <input type="hidden" value ="" id="coin">
                <input type="hidden" value ="" id="type">
                <input type="hidden" value ="" id="book_type">
                <input type="hidden" value ="" id="teacher_id">
                <div class="d-flex flex-column justify-content-center align-items-center my-3">
                    <input type="hidden" value="" id="schedule_id">
                    <h5>今すぐレッスンを始めるために選ばれました。</h5>
                    <button  class="text-center mt-1 btn btn-primary" id="startMeetingWithStudent">レッスンスタート</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.Modal -->
<div class="modal fade" id="modalLessonUnavailableNow" tabindex="-1" aria-labelledby="exampleModalLabel"
     aria-hidden="true" style="top:100px">
    <div class="modal-dialog" style="max-width: 300px!important;">
        <div class="modal-content">
            <div class="modal-body">
                <div class="col-12 col-sm-12">
                    <div class="d-flex flex-column align-items-center">
                        <h3>お知らせ</h3>
                    </div>
                    <div class="d-flex flex-column">
                        <p class="mb-2">{{ __('validation_custom.M048') }}</p>
                    </div>
                </div>
                <div class="modal-footer py-1">
                    <div>
                        <button type="button" class="btn btn-default btn-flat" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
