<div class="review_content">
    @foreach($teacherReview as $item)
        <div class="review_detail card">
            <div class="card-header bg-gray-light">
                <div class="col-12 pt-2 row align-content-center">
                    <h6  class="font-weight-bold col-md-4 text-center text-md-left  col-12 align-content-center">{{$item->nickname}}</h6>
                    <div class="col-md-4 col-12 text-center align-content-center ">
                        <input type="hidden" class="rating"  disabled data-filled="symbol-filled fa fa-star" data-empty="symbol-empty fa fa-star" data-fractions="2"  value="{{$item->star}}">
                    </div>
                    <p class="col-md-4 col-12 text-md-right align-content-center text-center">
                        {{\App\Helpers\Helper::formatDate($item->created_at)}}
                    </p>
                </div>
            </div>
            <div class="card-body">
                <p>{{$item->comment}}</p>
            </div>
        </div>
    @endforeach
<div class="float-right">
    {{ $teacherReview->links()}}
</div>
</div>




