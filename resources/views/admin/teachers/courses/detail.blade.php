@extends('layouts.admin.app')
@section('stylesheets')

@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('course.detail') }}
@endsection
@section('title_screen', 'コース詳細')
@section('content')
    <style>
        .lesson:hover {
            cursor: pointer;
            background-color: #fffcee
        }
        @media only screen and (min-width: 786px) {
            .div-avatar-image {
                width: 103px;
                height: 137px;
                background-color: white;
            }
            .avatar-image {
                width: 100px;
                height: 135px;
                margin: 0 auto !important;
            }
            .intro-video {
                width: 1037px;
                height: 500px;
            }
        }
        @media only screen and (min-width: 601px) and (max-width: 785px) {
            .intro-video {
                width: 650px;
                height: 400px;
            }
        }
        @media only screen and (max-width: 600px) {
            .div-avatar-image {
                width: 53px;
                height: 77px;
                background-color: white;
            }
            .avatar-image {
                width: 50px;
                height: 75px;
                margin: 0 auto !important;
            }
            .custom-w-95 {
                width: 95% !important;
                margin: 0.5rem auto !important;
            }
            .custom-w-45 {
                width: 45% !important;
            }
            .custom-w-55 {
                width: 55% !important;
            }
            .intro-video {
                width: 320px;
                height: 200px;
            }
        }
    </style>
    <section class="content">
        <div class="container-fluid d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="card w-75 custom-w-95" style="margin-left: 8%">
                        <div class="card-body">
                            <div class="row border rounded lesson">
                                <div class="col-3 col-sm-2 d-flex d-sm-flex justify-content-center align-items-center justify-content-sm-start align-items-sm-center my-2">
                                    <div class="div-avatar-image border">
                                        @if(!empty($course->photo))
                                            <img src="{{ $course->photo }}" class="avatar-image">
                                        @endif
                                    </div>
                                </div>
                                <div class="col-9 col-sm-10 d-flex flex-column d-sm-flex flex-sm-column justify-content-between justify-content-sm-between" >
                                    <div class="mt-1 d-flex flex-column justify-content-between align-items-start d-sm-flex flex-sm-row justify-content-sm-between align-items-sm-center">
                                        <h4 class="mb-0 border-bottom custom-color mt-sm-0 mt-1">{{$course->name}}</h4>
                                        <h6 class="mb-0 custom-color"><i class="fas fa-bookmark"></i> レッスン数 : {{$course->num_of_lessons}}</h6>
                                    </div>
                                    <div>
                                        <h5>
                                            <span class="badge badge-dark custom-color mr-2">レベル</span>
                                            @for($i = 5; $i>=1; $i--)
                                                <span class="badge @if($i >= $course->level_id) badge-warning @else  badge-secondary @endif  custom-color">N{{$i}}</span>
                                            @endfor
                                        </h5>
                                    </div>
                                    <div>
                                        <p>
                                            {{$course->description}}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($lessons as $lesson)
                                    <div class="w-100 mt-2">
                                        <div class="w-100 border rounded d-flex justify-content-between align-items-center" style="height: calc(2.25rem + 2px)">
                                            <div class="w-100 d-flex justify-content-between align-items-center pulldown">
                                                <div class="custom-w-45 d-flex">
                                                    <span class="ml-1 ml-sm-3 lessonNumber">{{$lesson->number}}. </span>
                                                    <span class="ml-1 ml-sm-2">{{$lesson->name}}</span>
                                                </div>
                                                <div class="custom-w-55 d-flex justify-content-end">
                                                    @if(empty($lesson->text_link))
                                                        <button class="btn mr-1 mr-sm-3" style="background-color: #F6B352; padding: 1px 10px" disabled> テキスト </button>
                                                    @else
                                                        <a class="mr-1 mr-sm-3 btn" href="{{$lesson->text_link}}" target="_blank" style="background-color: #F6B352; padding: 1px 10px">テキスト</a>
                                                    @endif
                                                    @if(empty($lesson->text_link))
                                                        <button class="btn mr-1 mr-sm-2" style="background-color: #F68657; padding: 1px 10px" disabled> ビデオ </button>
                                                    @else
                                                        <a class="mr-1 mr-sm-2 btn btnShowVideo" href="javascript:;" data-video_link="{{$lesson->video_link}}" style="background-color: #F68657; padding: 1px 10px">ビデオ</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="modalShowVideo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" style="max-width: 1100px!important;">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body d-flex justify-content-center">
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
@push('scripts')
    <script src="{{ asset('js/admin/teachers/courses/detail.js') }}"></script>
@endpush
