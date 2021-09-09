@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('create_curriculum') }}
@endsection

@section('stylesheets')
{{--    <link rel="stylesheet" href="{{ asset('css/admin/manager/students/create.css') }}"/>--}}
@endsection

@section('title_screen', 'カリキュラム追加')

@section('content')
    <style>
        .div-avatar {
            padding: 3px;
            border: 3px solid #adb5bd;
            /*border-radius: 50%;*/
        }
        .box{
            /*border-radius: 50%;*/
            height: 100px;
            width:100px;
            {{--background-image: url("{{ $adminInformation->image_photo ?? asset('images/avatar_2.png') }}");--}}
            background-position: center;
            background-size: cover;
            position: relative;
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

        #upload-photo {
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
        .custom-file-input ~ .custom-file-label::after {
            content: "ブラウズ" !important;
        }
    </style>
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-body">
                <div class="tab-pane col-sm-9">
                    <form>
                        <div class="form-group row">
                            <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                <p class="mb-0">カリキュラム名</p>
                            </div>
                            <div class="input-group col-sm-9">
                                <div class="w-100">
                                    <input type="text" class="form-control" name="" id="" >
                                </div>
                                <span class="text-danger error-custom" id="error_nickname"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                <p class="mb-0">説明</p>
                            </div>
                            <div class="input-group col-sm-9">
                                <div class="w-100">
                                    <textarea type="text" class="form-control" name="" id=""></textarea>
                                </div>
                                <span class="text-danger error-custom" id="error_nickname"></span>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                <p class="mb-0">レベル</p>
                            </div>
                            <div class="input-group col-sm-9">
                                <div class="w-100">
                                    <div class="input-group">
                                        <select class="form-control select2" id="" style="width: 100%;" name="">
                                            <option>N5</option>
                                            <option>N4</option>
                                            <option>N3</option>
                                            <option>N2</option>
                                            <option>N1</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center">
                                <p class="mb-0">写真</p>
                            </div>
                            <div class="col-9 col-sm-9 d-flex d-sm-flex justify-content-start align-items-center justify-content-sm-start align-items-sm-center">
                                <div class="div-avatar">
                                    <div class="box" id="box">
                                        <div class="upload">
                                            <input type='file' name="photo" id="upload-photo" />
                                            <label for="upload-photo"><i class="fas fa-camera"></i></label>
                                        </div>
                                        <div class="remove-image" id="remove-image">
                                            <span class="text-danger "><i class="far fa-times-circle rounded-circle " style="background-color: white"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <span class="text-danger ml-5" id="error_upload_photo"></span>
                            </div>
                        </div>
                        <div class="form-group row d-flex d-sm-flex align-items-start">
                            <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                <p class="mb-0 mt-3">説明</p>
                            </div>
                            <div class="input-group col-sm-9 course">
                                @for($i = 1; $i <= 3; ++$i)

                                    <div class="w-100 divLesson mt-3">
                                        <div class="w-100 border rounded d-flex justify-content-between align-items-center" style="height: calc(2.25rem + 2px)">
                                            <div class="w-100 d-flex justify-content-between align-items-center pulldown">
                                                <span class="ml-3 lessonNumber">レッスン {{$i}}</span>
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
                                            <div class="w-100 mt-3 d-flex d-sm-flex">
                                                <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                                    <p class="mb-0">テキスト</p>
                                                </div>
                                                <div class="input-group col-sm-9 px-0">
                                                    <div class="w-100">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="inputGroupFileAddon01">アップロード</span>
                                                            </div>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input pdf_link" name="pdf_link[]" id="pdf_link" aria-describedby="inputGroupFileAddon01">
                                                                <label class="custom-file-label" for="pdf_link"> ファイルを選ぶ</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="w-100 mt-3 d-flex d-sm-flex">
                                                <div class="col-3 col-sm-3 d-flex d-sm-flex align-items-sm-center justify-content-sm-between">
                                                    <p class="mb-0">ビデオ</p>
                                                </div>
                                                <div class="input-group col-sm-9 px-0">
                                                    <div class="w-100">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                <span class="input-group-text" id="inputGroupFileAddon01">アップロード</span>
                                                            </div>
                                                            <div class="custom-file">
                                                                <input type="file" class="custom-file-input video" name="video[]" id="video" aria-describedby="inputGroupFileAddon01">
                                                                <label class="custom-file-label" for="inputGroupFile01">ファイルを選ぶ</label>
                                                            </div>
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
                            <div class="input-group col-sm-9 offset-sm-3">
                                <div class="w-100">
                                    <a href="javascript:;" class="btn btn-success" id="addLesson"><i class="fas fa-plus-square"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="float-right">
                                <a href="#"><buton type="button" class="btn btn-secondary btn-flat">キャンセル</buton></a>
                                <buton type="button" class="btn btn-primary btn-flat ml-2" id="btnUpdateProfile">登録</buton>
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
