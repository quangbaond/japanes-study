@extends('layouts.admin.app')
@section('stylesheets')
    <meta name="routeGetTodaySchedule" content="{{ route('today-schedule.datatable') }}">
    <meta name="route-notify-student-start-lesson" content="{{ route('notify-student-start-lesson') }}">
    <meta name="route-notify-teacher-cancel-lesson" content="{{ route('teacher-cancel-lesson') }}">
    <meta name="route-notify-teacher-start-lesson" content="{{ route('teacher-start-lesson') }}">
    <meta name="wait-student-message" content="{{ __('teacher.wait_student') }}">
@endsection
@section('breadcrumb')
    {{ Breadcrumbs::render('my_page') }}
@endsection
@section('title_screen', 'マイページ')
@section('content')
    <style>
        table.dataTable {
            margin-top: 0px !important;
            border-top: 0px;
        }

        h3 > span {
            font-weight: 300 !important;
            font-size: 65% !important;
        }
        thead > tr > th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 20px;
        }
        td {
            max-width: 400px;
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }
        td.dt-center { text-align: center; }
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
    <form action="" method="post" id="notify-to-student">
        @csrf
    </form>
    <section class="content">
        <div class="container-fluid d-flex">
            <div class="w-100">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">今日の予定（{{Timezone::convertToLocal(\Carbon\Carbon::now(),'Y/m/d')}}）</h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm d-flex justify-content-end align-items-end"
                                         style="width: 150px;">
                                        <span class="text-center pb-0" >{{$countTodaySchedule}}件</span>
                                    </div>
                                </div>
                            </div>
                            @if($countTodaySchedule > 0)
                            <div class="card-body table-responsive p-0" @if($countTodaySchedule >= 6) style="height: 370px;" @endif>
                                <table id="today-schedule" class="table table-bordered table-head-fixed table-hover">
                                    <thead>
                                        <tr>
                                            <th>時間</th>
                                            <th>生徒ID</th>
                                            <th>ニックネーム</th>
                                            <th>メールアドレス</th>
                                            <th>コース</th>
                                            <th>レッスン</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="card-body">
                                    <span>{{config('constants.have_no_record_today_schedule')}}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">一週間の予約中</h3>
                                <div class="card-tools">
                                    <div class="input-group input-group-sm d-flex justify-content-end align-items-end"
                                         style="width: 150px;">
                                        <span class="text-center pb-0" >{{$num_booking}}件</span>
                                    </div>
                                </div>
                            </div>
                            @if($num_booking > 0)
                            <div class="card-body table-responsive">
                                <table id="" class="table table-bordered table-hover">
                                    <tbody>
                                    @for($i=0; $i<7; $i++)
                                        <tr>
                                            <th hidden></th>
                                            <td style="width: 130px;">
                                                <div class="d-flex justify-content-center mt-2">
                                                    <div class="">
                                                        <span class="mr-2
                                                            @if($date[$i]['name'] == "日")
                                                            text-danger
                                                            @elseif($date[$i]['name'] == "土")
                                                            text-primary
                                                            @endif">
                                                            {{$date[$i]['month'].'/'.$date[$i]['day'].' ('.$date[$i]['name'].')'}}
                                                        </span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex" >
                                                    @foreach($schedules as $schedule)
                                                        @if($schedule->start_date==$date[$i]['full'])
                                                            <span class="badge badge-warning mx-1 py-2 px-3" style="font-size: 15px; font-weight: normal">
                                                                {{\Carbon\Carbon::parse($schedule->start_hour)->format("H:i")}}
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </td>
                                        </tr>
                                    @endfor
                                    </tbody>
                                </table>
                            </div>
                            @else
                                <div class="card-body">
                                    <span>{{config('constants.have_no_record_today_schedule')}}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">レッスン数</h3>
                            </div>
                            <div class="card-body table-responsive">
                                <table class="table table-bordered table-hover" >
                                    <tbody>
                                    <tr>
                                        <th style="font-weight: normal">今週</th>
                                        <th style="font-weight: normal">先週</th>
                                        <th style="font-weight: normal">今月</th>
                                        <th style="font-weight: normal">先月</th>
                                        <th style="font-weight: normal">今年</th>
                                        <th style="font-weight: normal">去年</th>
                                    </tr>
                                    <tr>
                                        <td>{{$counter['this_week']}}</td>
                                        <td>{{$counter['last_week']}}</td>
                                        <td>{{$counter['this_month']}}</td>
                                        <td>{{$counter['last_month']}}</td>
                                        <td>{{$counter['this_year']}}</td>
                                        <td>{{$counter['last_year']}}</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade" id="modal_teacher_start_lesson" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true" style="top:100px">
        <div class="modal-dialog" style="max-width: 400px!important;">
            <div class="modal-content">
                <div class="modal-body mt-3">
                    <div class="col-12 col-sm-12">
                        <div class="d-flex flex-column align-items-center">
                            <h3> </h3>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="mb-1">ズームが作成しました。参加しますか？</p>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <div>
                            <button type="button" class="btn btn-default btn-flat" data-dismiss="modal" id="btn_teacher_cancel">いいえ
                            </button>
                            <button type="button" class="btn btn-primary btn-flat" id="btn_teacher_start">はい
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_after_5_minutes" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true" style="top:100px">
        <div class="modal-dialog" style="max-width: 600px!important;">
            <div class="modal-content">
                <div class="modal-body mt-3">
                    <div class="col-12 col-sm-12">
                        <div class="d-flex flex-column align-items-center">
                            <h3> </h3>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="mb-1">生徒はまだレッスンに参加していません。もう一度やり直してください。</p>
                        </div>
                    </div>
                    <div class="modal-footer mt-3">
                        <div>
                            <button type="button" class="btn btn-primary btn-flat" data-dismiss="modal" >OK
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/teachers/mypage.js') }}"></script>
@endpush
