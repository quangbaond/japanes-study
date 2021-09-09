@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('notification_create') }}
@endsection

@section('stylesheets')

@endsection

@section('title_screen', '通知追加')


@section('content')
    <style>
        .has-error .select2-selection {
            border-color: #dc3545 !important;
        }

        @media (max-width:575px) {
            .mx-md-2 {
                margin-left: -6px;
                margin-right: 5px;
            }
        }
        .animated {
            -webkit-transition: height 0.2s;
            -moz-transition: height 0.2s;
            transition: height 0.2s;
        }
    </style>
    <div class="content">
        <div class="container-fluid">
            <div class="card">
                <form action="{{route('admin-notification.insert')}}" method="post" id="notification_add">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-md-2 col-form-label">送信対象者</label>
{{--                                    <label for="" class=" col-form-label text-danger">*</label>--}}

                                        <div class="col-sm-2 ">
                                            <div class="input-group">
                                                <input name="receiverClass" type="radio" checked class="m-2" @if(old('receiverClass') == "1") {{"checked"}} @endif  id="general" value="1" >
                                                <label for="general" class="m-1">共通</label>
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 ">
                                            <div class="input-group d-sm-flex ">
                                                <input name="receiverClass" type="radio" class="m-2" @if(old('receiverClass') == "2") {{"checked"}} @endif  id="teacher" value="2" >
                                                <label for="teacher" class="m-1">講師のみ</label>
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2 ">
                                            <div class="input-group d-sm-flex ">
                                                <input name="receiverClass" type="radio" class="m-2"  @if(old('receiverClass') == "3") {{"checked"}} @endif  id="student" value="3" >
                                                <label for="student" class="m-1">生徒のみ</label>
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3 ">
                                            <div class="input-group d-sm-flex text-sm-center ">
                                                <input name="receiverClass" type="radio" class="m-2" @if(old('receiverClass') == "4") {{"checked"}} @endif  id="people" value="4" >
                                                <label for="people" class="m-1">個人のユーザに送信</label>
                                                <span class="invalid-feedback" role="alert"></span>
                                            </div>
                                        </div>

                                </div>
                            </div>
                        </div>
                        <div class="row dateTime @if ($errors->has('address')) {{'d-none'}} @else {{'d-block'}} @endif">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="title" class="col-sm-2 col-form-label">表示期間</label>
                                    <label for="" class=" col-form-label text-danger">*</label>
                                    <div class="col-sm-9 pr-0 row">
                                        <div class="input-group col item-input-date pr-0" style="max-height: 50px; cursor: pointer;">
                                            <input type="text" name="start_date" value="{{old('start_date')}}" autocomplete="off"  id="start_date" class="start_date form-control datepicker  @error('start_date') is-invalid @enderror"/>
                                            <div class="input-group-append "  style="max-height: 38px">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @error('start_date')
                                            <span class="invalid-feedback ml-1" role="alert">
                                                {{ $message }}
                                            </span>
                                            @enderror
                                        </div>
                                        <div class="input-form col-sm-2 text-center pl-0" style="">
                                            <h2 for="end_date" class="col-sm-10 pl-0">~</h2>
                                        </div>
                                        <div class="input-group col item-input-date pr-0"  style="max-height: 50px; cursor: pointer; padding-left: 0">
                                            <label for="" class=" col-form-label mx-md-2 text-danger" style="">*</label>
                                            <input type="text" name="end_date" value="{{old('end_date')}}" autocomplete="off" id="end_date" class="form-control end_date datepicker  @error('end_date') is-invalid @enderror"/>
                                            <div class="input-group-append" style="max-height: 38px" >
                                                <div class="input-group-text" style=""><i class="fa fa-calendar"></i></div>
                                            </div>
                                            @error('end_date')
                                            <span class="invalid-feedback ml-4" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="row @if ($errors->has('address')) {{'d-block'}} @else {{'d-none'}} @endif  people-form">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="adress" class="col-12 col-sm-12 col-md-2 col-form-label">宛名</label>
                                    <label for="" class=" col-form-label text-danger">*</label>
                                    <div class="col-11 col-sm-11 col-md-9">
                                        <div class="input-group sendToUser @error('address') has-error @enderror ">
                                            <select class="itemName form-control  " id="address" style="width:100%;" data-user="{{old('address[]')}}" multiple="multiple" name="address[]"></select>
                                            @error('address')
                                                <span class="invalid-feedback-custom" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row my-3">
                            <label for="title" class="col-12 col-sm-12 col-md-2 col-form-label">タイトル</label>
                            <label for="" class=" col-form-label  text-danger">*</label>
                            <div class="col-11 col-sm-11 col-md-9">
                                <div class="input-group">
                                    <textarea spellcheck="false" rows="1" style="z-index: 1;" maxlength="200" value="{{old('title')}}" id="title" class=" animated form-control @error('title') is-invalid @enderror" name="title" ></textarea>
                                    @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        {{ $message }}
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-sm-9 offset-sm-2 d-sm-flex justify-content-sm-end">
                                <span class=" text-right" id="titleNum" style="opacity: 0.7"></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label for="description" class="col-12 col-sm-12 col-md-2 col-form-label">内容</label>
                                    <label for="" class=" col-form-label text-danger">*</label>
                                    <div class="col-11 col-sm-11 col-md-9">
                                        <div class="input-group">
                                            <textarea  maxlength="4294967295" id="description" rows="10" class="form-control @error('description') is-invalid @enderror" name="description">{{old('description')}}</textarea>
                                            @error('description')
                                                <span class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-9 offset-sm-2 d-sm-flex justify-content-sm-end">
                                        <span class=" text-right" id="descriptionNum" style="opacity: 0.7"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-right">
                            <button type="button" id="reset" class="btn btn-secondary btn-flat">クリア</button>
                            <button type="submit" class="btn btn-primary btn-flat">送信</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/managers/notification/create.js') }}"></script>
<script>
    $(document).ready(function() {
        $.fn.select2.defaults.set('language', {
            // errorLoading: function () {
            //     return "ERROR_LOADING";
            // },
            // inputTooLong: function (args) {
            //     return "INPUT_TOO_LONG";
            // },
            // inputTooShort: function (args) {
            //     return "INPUT_TOO_SHORT";
            // },
            // loadingMore: function () {
            //     return "LOADING_MORE";
            // },
            // maximumSelected: function (args) {
            //     return "MAX_SELECTED";
            // },
            noResults: function () {
                return " 該当がありません";
            },
            searching: function () {
                return "検索中";
            }
        });
            function delayedResize () {
                window.setTimeout(resize, 0);
            }
        $('.itemName').select2({
            language: "jp",
            ajax: {
                url: '{{ route('select2.data-ajax') }}',
                dataType: 'json',
                delay: 100,
                async : false,
                processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.email,
                                id: item.id
                            }
                        })
                    };
                },
                cache: true
            }
        });
        $('#description').on('keyup', function () {
            var len = this.value.length;
            var maxlen = parseInt($(this).attr('maxlength'))
            if (len > maxlen) {
                this.value = this.value.substring(0, maxlen);
            } else {
                $('#descriptionNum').text(`${len} / ${maxlen}`);
            }
        });

        $('#title').on('keyup', function () {
            $(this).css('overflow' , 'hidden')
            var height = this.scrollHeight + 'px'
            $(this).css('height' , height)
            var len = this.value.length;
            var maxlen = parseInt($(this).attr('maxlength'))
            if (len > maxlen) {
                this.value = this.value.substring(0, maxlen);
            } else {
                $('#titleNum').text(`${len} / ${maxlen}`);
            }
        });
        $('.item-input-date').click(function() {
            var itemDate =  $(this).find('input').focus()
        })
        $('#people').change(function(e) {
            $('.dateTime').addClass('d-none')
            $('.dateTime').removeClass('d-block')
            $('.people-form').removeClass('d-none')
            $('.itemName').select2({
                ajax: {
                    url: '{{ route('select2.data-ajax') }}',
                    dataType: 'json',
                    delay: 100,
                    async : false,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.email,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        })
        $('#teacher').change(function(e){
            $('.people-form').addClass('d-none')
            $('.dateTime').removeClass('d-none')
            $('.people-form').removeClass('d-block')
        })
        $('#student').change(function(e){
            $('.people-form').addClass('d-none')
            $('.dateTime').removeClass('d-none')
            $('.people-form').removeClass('d-block')

        })
        $('#general').change(function(e){
            $('.people-form').addClass('d-none')
            $('.dateTime').removeClass('d-none')
            $('.people-form').removeClass('d-block')

        })
        $('#notification_add').on('click', '#reset', function () {
            $('#notification_add').find('span.invalid-feedback').each(function() {
                $(this).html('');
                $('.itemName').val('').trigger('change')
                $('#descriptionNum').text("")
                $('#titleNum').text("")
            });
            $('#area_message').html('');
            $('input[name="start_date"]').val('');
            $('.start_date').removeClass('is-invalid');

            $('input[name="end_date"]').val('');
            $('.end_date').removeClass('is-invalid');

            $('#title').val('');
            $('#title').removeClass('is-invalid');

            $('#description').val('');
            $('#description').removeClass('is-invalid');
        })

    })
</script>

@endpush
