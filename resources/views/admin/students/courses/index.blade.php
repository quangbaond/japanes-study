@extends('layouts.student.app')
@section('admin_title')
@endsection

@section('stylesheets')
    <link href="{{ asset('css/student/home.css') }}" rel="stylesheet">
@endsection

@section('content')
    <style>
        .lesson:hover {
            cursor: pointer;
            background-color: #fffcee
        }
        @media only screen and (min-width: 600px) {
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
        }
    </style>
    <div class="container">
        <div class="row">
            <div class="card w-100">
                <div class="card-header">
                    <h3 class="card-title text-bold">{{ __('courses.course_list') }}</h3>
                </div>
                <div class="card-body">
                    @foreach($courses as $course)
                        <a href="{{route('student.course.detail', $course->id)}}" style="color: black;">
                            <div class="row border rounded lesson my-2 my-sm-2">
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
                                        <h6 class="mb-0 custom-color"><i class="fas fa-bookmark"></i> {{ __('courses.the_number_of_lesson') }} : {{$course->num_of_lessons}}</h6>
                                    </div>
                                    <div>
                                        <h5>
                                            <span class="badge badge-dark custom-color mr-2">{{ __('courses.level') }}</span>
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
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')

@endpush

