@extends('layouts.student.app')
@section('admin_title')
{{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
    <style>
        textarea:read-only , input:read-only {
            background-color: #fff !important;
        }
        .input-group > a{
            max-height: 15px;
        }
    </style>
@endsection

@section('content')

<div class="container px-0">
    <section class="content">
        <div class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-bold card-title">{{__('student.notification')}} {{__('student.detail')}}</h3>
                </div>
                <div class="card-body">
                    <div class="tab-pane col-sm-12 px-0">
                        <form action="" method="post">
                            @csrf
                            {{-- title --}}
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label">{{__('student.title')}}</label>
                                <div class="input-group col-sm-10 px-0">
                                    <textarea  rows="1" readonly id="title"  class="form-control" name="title">{{$data->title}}</textarea>
                                </div>
                            </div>

                            {{-- content --}}
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label ">{{__('student.content')}}</label>
{{--                                <div class="input-group col-sm-10">--}}
{{--                                    <textarea rows="15" readonly  id="fieldIntro" maxlength="500" name="content" type="text" class="form-control ">{{$data->content}}</textarea>--}}
{{--                                </div>--}}
                                <div class="input-group col-sm-10 border rounded px-0" style="min-height: 300px">
                                    <div class="p-3">
                                        {!! $data->content !!}
                                    </div>
                                </div>
                                <label class="col-sm-3 col-form-label"></label>
                            </div>

                            {{-- button --}}

                        </form>
                    </div>
                </div>
                <div class="card-footer">
                    <a  class="btn btn-secondary float-left btn-flat" id="backUrl">{{__('student.back')}}</a>
                </div>
            </div>
        </div>
    </section>
</div>

@endsection
@push('scripts')
    <script>
        $('textarea').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        })
        $('#backUrl').click(function (e) {
            e.preventDefault();
            window.history.back();
        })
    </script>
@endpush
