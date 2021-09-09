@extends('layouts.student.app')
@section('admin_title')

@endsection
@section('stylesheets')
    <meta name="route-list-history-datatable" content="{{ route('student.list-history-datatable') }}">
    <meta name="student-email" content="{{ $student_email->student_email }}">
    <meta name="language" content="{{ Config::get('app.locale') == 'en' ? "/English.json" : "/Vietnamese.json" }}">
    <link rel="stylesheet" href="{{ asset('css/admin/students/lessons/lesson_history.css') }}"/>
@endsection
@section('content')
    <div class="container">
        <div class="col-12">
            <div class="row">
                <div class="card w-100">
                    <div class="card-header">
                        <h3 class="card-title text-bold">{{__('student_lesson_history.lesson_history')}}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @if($counter['total_lesson']>0)
                                <div class="w-100 align-items-center">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.total_lesson')}}">{{__('student_lesson_history.total_lesson')}}</th>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.this_week')}}">{{__('student_lesson_history.this_week')}}</th>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.last_week')}}">{{__('student_lesson_history.last_week')}}</th>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.this_month')}}">{{__('student_lesson_history.this_month')}}</th>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.last_month')}}">{{__('student_lesson_history.last_month')}}</th>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.this_year')}}">{{__('student_lesson_history.this_year')}}</th>
                                                    <th data-toggle="tooltip" data-placement="top" data-original-title="{{__('student_lesson_history.last_year')}}">{{__('student_lesson_history.last_year')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['total_lesson']}}">{{$counter['total_lesson']}}</td>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['this_week']}}">{{$counter['this_week']}}</td>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['last_week']}}">{{$counter['last_week']}}</td>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['this_month']}}">{{$counter['this_month']}}</td>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['last_month']}}">{{$counter['last_month']}}</td>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['this_year']}}">{{$counter['this_year']}}</td>
                                                    <td data-toggle="tooltip" data-placement="top" data-original-title="{{$counter['last_year']}}">{{$counter['last_year']}}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 mb-3 align-items-center">
                                    <div class="col-12">
                                        <div class="table-responsive">
                                            <table id="list_history" class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>{{__('student_lesson_history.date')}}</th>
                                                    <th>{{__('student_lesson_history.time')}}</th>
                                                    <th>{{__('student_lesson_history.teacher_id')}}</th>
                                                    <th>{{__('student_lesson_history.nickname')}}</th>
                                                    <th style="width: 110px">{{__('student_lesson_history.email')}}</th>
                                                    <th>{{__('student_lesson_history.course')}}</th>
                                                    <th>{{__('student_lesson_history.lesson_content')}}</th>
                                                    <th>{{__('student_lesson_history.coin')}}</th>
                                                    <th style="width:90px">{{__('student_lesson_history.status')}}</th>
                                                    <th></th>
                                                    <th style="width: 140px!important;"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        @else
                            <div class="w-100 mb-3 align-items-center">
                                <div class="col-12">
                                    <span>{{__('student_lesson_history.have_no_record_list_history')}}</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/students/lessons/student_lesson_history.js') }}"></script>
@endpush
