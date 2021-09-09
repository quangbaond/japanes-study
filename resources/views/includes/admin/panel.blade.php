<div class="container-fluid
@if(is_null($zoom_link))
    d-none
@endif" style="overflow: hidden !important;" id="message_join_meeting">
    <div class="row">
        <div class="col-12 px-2">
            <div class="card">
                <div class="card-body">
                    <div class="row d-flex align-items-center">
                        <div
                            class="col-12 col-sm-12 d-flex justify-content-between align-items-center d-sm-flex justify-content-sm-between align-items-sm-center">
                            <p class="mb-0"> {{ __('panel_rejoin.notification')  }}</p>
                            <a href="{{ $zoom_link }}" target="_blank" class="btn btn-success text-end"
                               id="zoom_link">{{ __('panel_rejoin.btn_join') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid
@if((is_null($teacher_zoom_link->link_zoom) || $teacher_zoom_link->link_zoom ==='') && $teacher_zoom_link->role == 2)
    d-block
@else
    d-none
@endif" style="overflow: hidden !important;" id="area_require_zoom_link">
    <div class="row">
        <div class="col-12 px-2">
            <div class="callout callout-danger">
                <p>
                    <span class="text-warning" style="font-size: 20px"><i class="fas fa-exclamation-triangle"></i></span>
                    {{ __('validation_custom.M065_01') }}
                    <a href="{{ route('teacher.edit-profile') }}" style="color:blue">{{ __('validation_custom.M065_02') }}</a>
                    {{ __('validation_custom.M065_03') }}
                </p>
            </div>
        </div>
    </div>
</div>

