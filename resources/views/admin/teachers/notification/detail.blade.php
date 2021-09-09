@extends('layouts.admin.app')
@section('stylesheets')
    <style>
        textarea:read-only , input:read-only {
            background-color: #fff !important;
        }
        .input-group > a{
            max-height: 15px;
        }
        @media only screen and (min-width: 786px) {
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
            .intro-video {
                width: 320px;
                height: 200px;
            }
        }
    </style>
@endsection
@section('breadcrumb')
    @if(app('router')->getRoutes()->match(app('request')->create(url()->previous()))->getName() == 'teacher-notification')
        {{ Breadcrumbs::render('teacher_notification_detail') }}
    @else
        {{ Breadcrumbs::render('teacher_notification_icon_detail') }}
    @endif
@endsection
@section('title_screen', '通知詳細')
@section('content')
    <div class="content">
    <div class="container-fluid">
        <div class="card card-info">
            <div class="card-body">
                <div class="tab-pane col-sm-12">
                    <form action="" method="post">
                        @csrf
                        {{-- title --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">タイトル</label>
                            <div class="input-group col-sm-10 px-0">
                                <textarea rows="1" type="text" class="form-control"  readonly name="title">{{$data->title}}</textarea>
                            </div>
                        </div>
                        {{-- content --}}
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">内容</label>
{{--                            <div class="input-group col-sm-10">--}}
{{--                                <textarea rows="15" id="fieldIntro" maxlength="500" name="content" disabled type="text" class="form-control">{{$data->content}}</textarea>--}}
{{--                            </div>--}}
                            <div class="input-group col-sm-10 border rounded px-0" style="min-height: 300px">
                                <div class="p-3">
                                    {!! $data->content !!}
                                </div>
                            </div>
                            <label class="col-sm-3 col-form-label"></label>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-footer">
                <a class="btn btn-secondary float-left btn-flat" href="{{url()->previous()}}">戻る</a>
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
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('textarea').each(function () {
            this.setAttribute('style', 'height:' + (this.scrollHeight) + 'px;overflow-y:hidden;');
        })
    </script>
    <script src="{{ asset('js/admin/teachers/notification/detail.js') }}"></script>
@endpush
