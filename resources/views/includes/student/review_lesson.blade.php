
<style>

   .rating-symbol {
       /*width: 50px;*/
       font-weight: inherit;
   }
   textarea::placeholder {
       font-size: 14px;
       opacity: 0.5;
   }
   .fa-star{
       font-size: 1.2em;
   }
</style>
<form action="{{route('student.reviewLesson')}}" method="post">
    @csrf
    <div class="modal" tabindex="-1" role="dialog" id="modal_review_lesson" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title font-weight-bold">{{__('student.review_lesson')}}</h5>
                    <button type="button"  id="close_review" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p>{{__('student.quality_your_lesson')}}</p>
                    <input type="hidden" class="rating" name="star" id="rating" data-filled="symbol-filled fa fa-star" data-empty="symbol-empty fa fa-star" value="5"/>
                    <input type="hidden" name="id_lesson" value="" id="id_lesson">
                    <input type="hidden" name="id_teacher_review" value="" id="id_teacher_review">
                    <textarea maxlength="500" class="form-control my-2" name="comment" id="comment" rows="3" placeholder="{{__('student.favorites_lesson')}}"></textarea>
                    <span class="d-block text-right" id="commentLength" style="opacity: 0.7"></span>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary btn-flat">{{__('button.send')}}</button>
                </div>
            </div>
        </div>
    </div>
</form>


