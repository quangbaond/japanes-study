@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('curriculum_detail') }}
@endsection

@section('stylesheets')
    {{--    <link rel="stylesheet" href="{{ asset('css/admin/manager/students/create.css') }}"/>--}}
@endsection

@section('title_screen', 'カリキュラム詳細')

@section('content')
    <style>
        .div-avatar {
            padding: 3px;
            border: 3px solid #adb5bd;
            /*border-radius: 50%;*/
        }
        .upload{
            display: flex;
            flex-direction: row;
            justify-content: center;
            align-items: center;
            font-size: 50px;
            color: #FFF;
            position: absolute;
            height: 50px;
            background: linear-gradient(0deg, rgba(0,212,255,1) 0%, rgba(14,13,13,1) 0%, rgba(0,0,0,0) 100%);
            width: inherit;
            top:50px;
            /*border-radius: 0 0 50px 50px;*/
            opacity: 0;
        }
        .upload > label {
            font-size: 50%;
        }

        #upload-photo, #upload_video, #upload_pdf {
            opacity: 0;
            position: absolute;
            z-index: 5;
            width: 100px;
            overflow: hidden!important;
        }
        .remove-image {
            position: absolute;
            top: -20px;
            right: -15px;
            /* left: 3px; */
            font-size: 25px;
            opacity: 0;
            font-weight: bold;
        }

        .upload:hover{
            opacity: 0.5;
            cursor: pointer;
        }
        #box:hover #remove-image {
            opacity: 1;
        }
        #remove-image:hover{
            cursor: pointer;
        }
        .box:hover{
            cursor: pointer;
        }
        .divLesson {
            position: relative;
        }
        .remove-div-lesson {
            position: absolute;
            top: -20px;
            right: -12px;
            /* left: 3px; */
            font-size: 25px;
            opacity: 0;
            font-weight: bold;
        }

        .custom-border {
            border: 3px solid #adb5bd;
        }
        .custom-color {
            opacity: 0.8;
        }
        @media only screen and (max-width: 575px) {
            #remove-video {
                height:100px;
                width: 400px;
                border: 2px solid #dee2e6;
            }
            .box{
                /*border-radius: 50%;*/
                height: 50px;
                width:50px;
                background-image: url("{{ $adminInformation->image_photo ?? asset('images/AdminLTELogo.png') }}");
                background-position: center;
                background-size: cover;
                position: relative;
            }
            #link_youtube {
                width: 150px;
                height: 100px;
            }
        }
        @media only screen and (max-width: 785px) and (min-width: 576px) {
            #remove-video {
                height:320px;
                width: 200px;
                border: 2px solid #dee2e6;
            }
            .box{
                /*border-radius: 50%;*/
                height: 70px;
                width:70px;
                background-image: url("{{ $adminInformation->image_photo ?? asset('images/AdminLTELogo.png') }}");
                background-position: center;
                background-size: cover;
                position: relative;
            }
        }
        @media only screen and (min-width: 786px) {
            #remove-video {
                height:320px;
                width: 700px;
                border: 2px solid #dee2e6;
            }
            .box{
                /*border-radius: 50%;*/
                height: 100px;
                width:100px;
                background-image: url("{{ $adminInformation->image_photo ?? asset('images/AdminLTELogo.png') }}");
                background-position: center;
                background-size: cover;
                position: relative;
            }
        }
        .lessonInformation {
            transition: all 2s linear;
        }
    </style>
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-body">
                <div class="tab-pane col-sm-9 offset-sm-1">
                    <form>
                        <div class="row  border rounded" style="background-color: #fffcee">
                            <div class="col-3 col-sm-2 d-flex d-sm-flex justify-content-start align-items-center justify-content-sm-start align-items-sm-center my-2">
                                <div class="div-avatar rounded bg-white">
                                    <div class="box" id="box">
                                        <div class="upload">
                                            <input type='file' name="photo" id="upload-photo" />
                                            <label for="upload-photo"><i class="fas fa-camera"></i></label>
                                        </div>
                                        <div class="remove-image" id="remove-image">
                                            <span class="text-danger"><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger ml-5" id="error_upload_photo"></span>
                            </div>
                            <div class="col-9 col-sm-10 d-flex flex-column d-sm-flex flex-sm-column justify-content-between justify-content-sm-between" >
                                <div class="d-flex flex-column d-sm-flex flex-sm-row justify-content-between align-items-start justify-content-sm-between align-items-sm-center">
                                    <h3 class="mb-0 border-bottom custom-color">初心者コース</h3>
                                    <h5 class="mb-0 custom-color"><i class="fas fa-bookmark"></i> レッスン数 : 22</h5>
                                </div>
                                <div class="my-2 my-sm-0">
                                    <h5>
                                        <span class="badge badge-dark custom-color mr-2">Level</span>
                                        <span class="badge badge-warning custom-color">N5</span>
                                        <span class="badge badge-warning custom-color">N4</span>
                                        <span class="badge badge-secondary custom-color">N3</span>
                                        <span class="badge badge-secondary custom-color">N2</span>
                                        <span class="badge badge-secondary custom-color">N1</span>
                                    </h5>
                                </div>
                                <div>
                                    <p>
                                        英会話を初めて学習する方、英語であいさつ・自己紹介ができるようになりたい方を対象としたコースです。
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row d-flex d-sm-flex align-items-start">
                            <div class="input-group col-12 col-sm-12 course">
                                @for($i = 1; $i <= 3; ++$i)
                                    <div class="w-100 divLesson mt-3">
                                        <div class="w-100 border rounded d-flex justify-content-between align-items-center" style="height: calc(2.25rem + 2px)">
                                            <div class="w-100 d-flex justify-content-between align-items-center pulldown">
                                                <span class="ml-3 lessonNumber">レッスン {{$i}}: 挨拶</span>
                                                <span class="mr-3 "><i class="fas fa-angle-down"></i></span>
                                            </div>
                                        </div>
                                        <div class="remove-div-lesson">
                                            <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                        </div>
                                        <div class="w-100 d-none lessonInformation">
                                            <div class="w-100 mt-3 d-flex d-sm-flex">
                                                <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                                    <p class="mb-0">レッスン名</p>
                                                </div>
                                                <div class="input-group col-sm-9 px-0">
                                                    <div class="w-100">
                                                        <input type="text" class="form-control lesson_name" name="lesson_name[]" >
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-100 mt-3 mb-4 mb-sm-0 d-flex d-sm-flex">
                                                <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                                    <p class="mb-0">テキスト</p>
                                                </div>
                                                <div class="input-group col-9 col-sm-9 px-0">
                                                    <div class="w-100 d-flex">
                                                        <div class="w-50 d-flex align-items-center">
                                                            <a href="https://www.youtube.com/" target="_blank">document.pdf</a>
                                                        </div>
                                                        <div class="w-50 d-flex justify-content-end">
                                                            <input type='file' name="upload_pdf" id="upload_pdf" />
                                                            <label for="upload_pdf" style="height: 25px"><button class="btn btn-primary">ファイル変更</button></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-100 mt-3 d-flex d-sm-flex">
                                                <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-start justify-content-sm-between align-items-start justify-content-sm-between">
                                                    <p class="mb-0">ビデオ</p>
                                                </div>
                                                <div class="input-group col-9 col-sm-9 px-0">
                                                    <div class="w-100 d-flex flex-column d-sm-flex flex-sm-row">
                                                        <div class="input-group mb-3 w-75">
                                                            <div class="" id="remove-video">
                                                                <iframe class="w-100 px-0 introduce-video" width="700px" height="320px" id="link_youtube"
                                                                        src="https://www.youtube.com/embed/wirN-zPO3Tg" frameborder="0"
                                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                                                        allowfullscreen></iframe>
                                                            </div>
                                                        </div>
                                                        <div class="w-75 d-flex justify-content-end">
                                                            <input type='file' name="upload_video" id="upload_video" />
                                                            <label for="upload_video" style="height: 62px"><button class="btn btn-primary">ビデオ変更</button></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="input-group col-12 col-sm-12">
                                <div class="w-100">
                                    <a href="javascript:;" class="btn btn-success" id="addLesson"><i class="fas fa-plus-square"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a href="#"><buton type="button" class="btn btn-secondary btn-flat">クリア</buton></a>
                                <buton type="button" class="btn btn-primary btn-flat ml-2" id="btnUpdateProfile">更新</buton>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    {{--    <script src="{{ asset('js/admin/managers/AdminList/create.js') }}"></script>--}}
    <script>
        $(document).ready(function(){
            $("body").on('click', '.pulldown', function(){

                let result = $(this).closest('.divLesson').children('.lessonInformation');
                if((result.attr('class')).includes('d-none')) {
                    $(this).find('i').css('transform', 'rotate(180deg)');
                    result.removeClass('d-none');
                    result.addClass('d-block');
                }
                else {
                    $(this).find('i').css('transform', 'rotate(0deg)');
                    result.removeClass('d-block');
                    result.addClass('d-none');
                }
            });

            //add lesson
            $('body').on('click', '#addLesson', function() {
                let divLesson = $('.course .divLesson:last').clone();
                let theNameOfLesson = divLesson.find('.lessonNumber').html();
                theNameOfLesson = theNameOfLesson.split(" ");
                let theNumberOfNextLesson = parseInt(theNameOfLesson[1]) + 1;
                divLesson.find('.lessonNumber').html(theNameOfLesson[0] + " " + theNumberOfNextLesson);
                divLesson.find('.lessonInformation').removeClass('d-none d-block').addClass('d-none');
                divLesson.find('i').css('transform', 'rotate(0deg)');
                divLesson.find('.lesson_name').val("");
                divLesson.find('.pdf_link').val("");
                divLesson.find('.video').val("");
                $('.course').append(divLesson);
            })
            $('body').on('mouseover', '.divLesson', function() {
                $(this).find('.remove-div-lesson').css('opacity', '1');
            })
            $('body').on('mouseout', '.divLesson', function() {
                $(this).find('.remove-div-lesson').css('opacity', '0');
            })
            $('body').on('click', '.remove-div-lesson', function() {
                $(this).closest('.divLesson').remove();
            })

        });
    </script>
@endpush
