@if(Auth::user()->role == config('constants.role.teacher'))
    @if(count($panelNotificationTeacher) > 0)
        <div class="container-fluid p-3">
            <div id="carousel-panel" class="carousel slide row" data-ride="carousel">
                <div class="carousel-inner panel-notifications" >
                    @foreach($panelNotificationTeacher as $item)
                        <div class="carousel-item notification-active">
                            <a href="{{route ('teacher-notification-detail' , $item->id)}}" class="w-100">
                                <div class="alert-notification">
                                    <div style="" class="row p-1 mx-2" >
                                        <div id="image_bell-panel__wrap text-center" class="col-2   col-md-1">
                                            <img id="image_bell-panel__item"  class="d-block w-50 mx-auto" src="{{ asset('images/bell_icon.png') }}" alt="">
                                        </div>
                                        <div style="color: white" class="col">
                                            <h5 class="ml-2 mt-2 w-100"><strong>{{$item->title , 120}}</strong></h5>
                                            <p class="ml-2 w-100">{{$item->content , 120}}</p>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
                <a class="carousel-control-prev" href="#carousel-panel" role="button" data-slide="prev" class="justify-content-left" style="justify-content: left;">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carousel-panel" role="button" data-slide="next" style="justify-content: flex-end;">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            </div>
        </div>
    @endif
@endif
