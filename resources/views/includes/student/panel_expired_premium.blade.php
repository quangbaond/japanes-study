
@if(!is_null($expire_premium))
    @if($message != '')
        <div class="container" style="overflow: hidden !important;" id="message_expired_premium">
            <div class="row">
                <div class="col-12 px-0">
                    <div class="card">
                        <div class="card-body">
                            <div class="row d-flex align-items-center">
                                <div class="col-12 col-sm-12" style="color:red">
                                    {!! $message !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endif



