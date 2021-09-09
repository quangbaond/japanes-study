@extends('layouts.student.app')
@section('admin_title')
    {{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
    <meta name="route-push-notification" content="{{ route('student.push-notification-to-teacher',['id' =>  $teacher_information->user_id]) }}">
    <meta name="route-book-schedule" content="{{ route('student.book-schedule',['id' =>  $teacher_information->user_id]) }}">
    <meta name="teacher_id" content="{{ $teacher_information->user_id }}">
    <meta name="route-timeout" content="{{ route("student.push-request-cancel") }}">
    <meta name="route-cancel-schedule" content="{{ route('student.push-notification-to-teacher-when-canceled',['id' =>  $teacher_information->user_id]) }}">
    <meta name="checkCoin" content="{{  ($coinPerStudy == 0 || $totalCoinOfStudent > $coinPerStudy ) ? true : false }}">
    <meta name="route-validate-book-lesson" content="{{route('student.book-lesson.validate', $teacher_information->user_id) }}">
    <meta name="route-validate-student-coin" content="{{route('student.book-lesson.validate-coin', $teacher_information->user_id) }}">
    <meta name="route-get-teacher-schedule" content="{{route('student.get-teacher-schedule', $teacher_information->user_id) }}">
    <meta name="message-teacher-status" content="{{ __('sudden_lesson.dont_have_course')  }}">
    <meta name="route-get-lessons-by-course" content="{{route('student.get-lesson-by-course', $teacher_information->user_id)}}">
    <meta name="checkTeacherCanTeach" content="{{(int)$checkTeacherCanTeach}}">
    <meta name="route-get-student-lesson-info" content="{{route('student.get-student-lesson-info', $teacher_information->user_id)}}">
    <meta name="M071" content="{{ __('validation_custom.M071') }}">
    <meta name="M074" content="{{ __('validation_custom.M074') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/products/create.css')  }}">
@endsection
@section('panel')
    @include('includes.student.panel')
@endsection
@section('content')
    @php
        use Stichoza\GoogleTranslate\GoogleTranslate;
        use Illuminate\Support\Facades\Request;
            $lang = Session::get('language');
            $tr = new GoogleTranslate($lang);
    @endphp
    <style>
        @media only screen and (max-width: 576px) {
            #avatar_image {
                width: 45px;
                height: 45px;
            }
            .custom-nickname-css {
                font-size: 25px !important;
                max-width: 150px !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                overflow: hidden !important;
            }
            .custom-nationality-css {
                max-width: 180px !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                overflow: hidden !important;
            }
            .fa-star {
                font-size: 1em;
            }
        }

        @media only screen and (min-width: 576px) {
            #avatar_image {
                width: 100px;
                height: 100px;
            }
            .custom-nickname-css {
                font-size: 25px !important;
                max-width: 150px !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                overflow: hidden !important;
            }
            .custom-nationality-css {
                max-width: 180px !important;
                text-overflow: ellipsis !important;
                white-space: nowrap !important;
                overflow: hidden !important;
            }
        }
        .modal {
            overflow-y:auto;
        }
        .rating-symbol{
            width: auto;
        }
        .box-number_rating .symbol-empty{
            color : #FFFFFF;
            width: 32px;
        }
        .review_content .rating-symbol-background.symbol-empty.fa.fa-star{
            width: 32px;
        }
    </style>
    <form action="{{route('student.toBookingLessonList')}}" method="post" id="booking_lesson_list">
        @csrf
    </form>
    <div class="container">
        <section id="error_premium_is_expired">

        </section>
        <div class="row">
            <div class="card w-100">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-9">
                            <div class="w-100 d-flex mb-3 align-items-center">
                                <div class="col-12 col-sm-12 py-1 px-0">
                                    <iframe class="w-100 px-0 introduce-video border" width="560" height="350"
                                            src="{{$teacher_information->link_youtube}}"
                                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                            allowfullscreen></iframe>
                                </div>
                            </div>
                            <div class="w-100 d-flex mb-3 align-items-center">
                                <div class="col-3 col-sm-3 d-flex justify-content-center align-items-center d-sm-flex justify-content-sm-center align-items-sm-center">
                                    <img class="img-circle elevation-2" id="avatar_image"
                                         src="  @if(!$teacher_information->image_photo == "")
                                                {{$teacher_information->image_photo}}
                                                @else
                                                {{asset('images/avatar_2.png')}}
                                                @endif"
                                         alt="User Avatar" width="90px" height="90px">
                                </div>
                                <div class="col-10 col-sm-9">
                                    <div class="row w-100 w-sm-100 h-50 border-bottom d-flex d-sm-flex pb-2">
                                        <div class="col-7 col-sm-4 d-sm-flex justify-content-sm-start ">
                                            <div class="mb-0 mt-2 font-weight-bold custom-nickname-css" data-toggle="tooltip" data-placement="top" title="{{ $teacher_information->nickname }}">
                                                {{$teacher_information->nickname}}
                                            </div>
                                        </div>
                                        <div class="col-5 col-sm-3 d-flex justify-content-end d-sm-flex justify-content-sm-start">
                                            <span class="mt-3 pb-1">({{$teacher_information->age}} {{ __('student_book_lesson.old') }})</span>
                                        </div>
                                        <div class="col-8 col-sm-4 d-sm-flex justify-content-sm-start">
                                            <p class="mb-0  mt-3 font-weight-bold">{{ __('student_book_lesson.number_of_lessons') }}</p>
                                        </div>
                                        <div class="col-4 col-sm-1 d-flex justify-content-end d-sm-flex justify-content-sm-end">
                                            <p class="mb-0 mt-3"><img width="15px" height="15px" src="data:image/svg+xml;base64,PHN2ZyBpZD0iRmxhdCIgaGVpZ2h0PSI1MTIiIHZpZXdCb3g9IjAgMCA1MTIgNTEyIiB3aWR0aD0iNTEyIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPjxnPjxyZWN0IGZpbGw9IiNiM2U5ZmYiIGhlaWdodD0iMzA0IiByeD0iMTAiIHdpZHRoPSI0NjQiIHg9IjI0IiB5PSI5NiIvPjxwYXRoIGQ9Im0zNTIgNDg4aC0xOTJ2LTkuNTg1YTIwIDIwIDAgMCAxIDEzLjY3NS0xOC45NzNsNi45NzQtMi4zMjVhNDAgNDAgMCAwIDAgMjcuMzUxLTM3Ljk0N3YtMjcuMTdoOTZ2MjcuMTdhNDAgNDAgMCAwIDAgMjcuMzUxIDM3Ljk0N2w2Ljk3NCAyLjMyNWEyMCAyMCAwIDAgMSAxMy42NzUgMTguOTczeiIgZmlsbD0iIzEyN2JiMyIvPjxwYXRoIGQ9Im0yNCAzMzZoNDY0YTAgMCAwIDAgMSAwIDB2NTRhMTAgMTAgMCAwIDEgLTEwIDEwaC00NDRhMTAgMTAgMCAwIDEgLTEwLTEwdi01NGEwIDAgMCAwIDEgMCAweiIgZmlsbD0iIzI1YjFmYSIvPjxwYXRoIGQ9Im0yMzIgMzYwaDQ4djE2aC00OHoiIGZpbGw9IiNiM2U5ZmYiLz48cGF0aCBkPSJtNTYgMjRoNDAwdjI4MGgtNDAweiIgZmlsbD0iI2ZlZWNkNiIvPjxwYXRoIGQ9Im04OCAxMDRoMjQ4djE2MGgtMjQ4eiIgZmlsbD0iI2ZmZmVmYSIvPjxwYXRoIGQ9Im0zNjggMTA0aDU2djMyaC01NnoiIGZpbGw9IiNmYWEyMzEiLz48cGF0aCBkPSJtMzY4IDE2OGg1NnYzMmgtNTZ6IiBmaWxsPSIjZmFhMjMxIi8+PHBhdGggZD0ibTM2OCAyMzJoNTZ2MzJoLTU2eiIgZmlsbD0iI2ZhYTIzMSIvPjxwYXRoIGQ9Im01NiAyNGg0MDB2NDhoLTQwMHoiIGZpbGw9IiNmYWEyMzEiLz48ZyBmaWxsPSIjZmVlY2Q2Ij48cGF0aCBkPSJtNDE2IDQwaDE2djE2aC0xNnoiLz48cGF0aCBkPSJtMTQ0IDQwaDE2djE2aC0xNnoiLz48cGF0aCBkPSJtODAgNDBoMTZ2MTZoLTE2eiIvPjxwYXRoIGQ9Im0xMTIgNDBoMTZ2MTZoLTE2eiIvP
                                            jwvZz48cGF0aCBkPSJtMTg0IDIyNHYtODBsODAgNDB6IiBmaWxsPSIjZmFhMjMxIi8+PHBhdGggZD0ibTI4MCAxMjhoMzJ2MTZoLTMyeiIgZmlsbD0iI2ZhYTIzMSIvPjwvZz48L3N2Zz4="/>
                                                {{$teacher_information->number_of_lessons}}</p>
                                        </div>
                                    </div>
                                    <div class="row w-100 h-50 d-flex">
                                        <div class="col-5 col-sm-2 d-sm-flex justify-content-sm-start">
                                            <p class="mb-0 mt-2 font-weight-bold">{{ __('student_book_lesson.nationality') }}: </p>
                                        </div>
                                        <div class="col-7 col-sm-5 d-flex justify-content-end d-sm-flex justify-content-sm-start">
                                            <p class="mb-0 mt-2 custom-nationality-css" data-toggle="tooltip" data-placement="top" title="{{ config('nation.'.$teacher_information->nationality) }}">
                                                <img width="30px" height="30px"
                                                     src="https://www.countryflags.io/{{$teacher_information->nationality}}/flat/64.png"
                                                     class=" mr-1"/>
                                                {{config('nation.'.$teacher_information->nationality)}}
                                            </p>
                                        </div>
                                        <div class="col-9 d-flex col-sm-4 d-sm-flex justify-content-sm-start">
                                            <p class="mb-0 mt-2 font-weight-bold">{{ __('student_book_lesson.number_of_reservations') }}</p>
                                        </div>
                                        <div
                                            class="col-3 col-sm-1 d-flex justify-content-end align-items-center d-sm-flex justify-content-sm-end">
                                            <p class="mb-0 mt-2"><img width="15px" height="15px" src="data:image/svg+xml;base64,PHN2ZyBoZWlnaHQ9IjUxMnB0IiB2aWV3Qm94PSItMjQgMCA1MTIgNTEyIiB3aWR0aD0iNTEycHQiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgZmlsbD0iI2U1ZWJmNSI+PHBhdGggZD0ibTcuNSAxNTAuMjczNDM4aDQ0OS40MTAxNTZ2MzU0LjIyNjU2MmgtNDQ5LjQxMDE1NnptMCAwIi8+PHBhdGggZD0ibTEyOS40NzI2NTYgMTAyLjY4MzU5NGgtMjcuMTA5Mzc1Yy0zLjA1MDc4MS0zLjA1MDc4Mi00Ljc1NzgxMi00Ljc1NzgxMy03LjgwODU5My03LjgwODU5NHYtNzkuNTY2NDA2YzMuMDUwNzgxLTMuMDUwNzgyIDQuNzU3ODEyLTQuNzU3ODEzIDcuODA4NTkzLTcuODA4NTk0aDI3LjEwOTM3NWMzLjA0Njg3NSAzLjA1MDc4MSA0Ljc1NzgxMyA0Ljc1NzgxMiA3LjgwNDY4OCA3LjgwODU5NHY3OS41NjY0MDZjLTMuMDQ2ODc1IDMuMDUwNzgxLTQuNzU3ODEzIDQuNzU3ODEyLTcuODA0Njg4IDcuODA4NTk0em0wIDAiLz48cGF0aCBkPSJtMzM0LjkzNzUgMTAyLjY4MzU5NGgyNy4xMDkzNzVjMy4wNTA3ODEtMy4wNTA3ODIgNC43NTc4MTMtNC43NTc4MTMgNy44MDg1OTQtNy44MDg1OTR2LTc5LjU2NjQwNmMtMy4wNTA3ODEtMy4wNTA3ODItNC43NTc4MTMtNC43NTc4MTMtNy44MDg1OTQtNy44MDg1OTRoLTI3LjEwOTM3NWMtMy4wNDY4NzUgMy4wNTA3ODEtNC43NTc4MTIgNC43NTc4MTItNy44MDQ2ODggNy44MDg1OTR2NzkuNTY2NDA2YzMuMDQ2ODc2IDMuMDUwNzgxIDQuNzU3ODEzIDQuNzU3ODEyIDcuODA0Njg4IDcuODA4NTk0em0wIDAiLz48L2c+PHBhdGggZD0ibTM2OS44NTU0NjkgNTUuMDg5ODQ0djM5Ljc4NTE1NmMtMy4wNTA3ODEgMy4wNTA3ODEtNC43NTc4MTMgNC43NTc4MTItNy44MDg1OTQgNy44MDg1OTRoLTI3LjEwOTM3NWMtMy4wNDY4NzUtMy4wNTA3ODItNC43NTc4MTItNC43NTc4MTMtNy44MDQ2ODgtNy44MDg1OTR2LTM5Ljc4NTE1NmgtMTg5Ljg1NTQ2OHYzOS43ODUxNTZjLTMuMDQ2ODc1IDMuMDUwNzgxLTQuNzU3ODEzIDQuNzU3ODEyLTcuODA0Njg4IDcuODA4NTk0aC0yNy4xMDkzNzVjLTMuMDUwNzgxLTMuMDUwNzgyLTQuNzU3ODEyLTQuNzU3ODEzLTcuODA4NTkzLTcuODA4NTk0di0zOS43ODUxNTZoLTg3LjA1NDY4OHY5NS4xODM1OTRoNDQ5LjQxMDE1NnYtOTUuMTgzNTk0em0wIDAiIGZpbGw9IiNkMTU1NzMiLz48cGF0aCBkPSJtMzcwLjE2NDA2MiAyNTguMzcxMDk0LTQ5LjY3NTc4MS00OS42Nzk2ODgtMTI0LjU3MDMxMiAxMjQuNTc0MjE5LTUxLjk5NjA5NC01MS45OTYwOTQtNDkuNjc1NzgxIDQ5LjY3NTc4MSAxMDEuNjcxODc1IDEwMS42NzE4NzZ6bTAgMCIgZmlsbD0iI2UyZmM4NSIvPjxwYXRoIGQ9Im03LjUgMTUwLjI3MzQzOGgzMHYzNTQuMjI2NTYyaC0zMHptMCAwIiBmaWxsPSIjY2FkOGVhIi8+PHBhdGggZD0ibTEyNC41NTQ2ODggOTQuODc1di03OS41NjY0MDZjMi41MzEyNS0yLjUzMTI1IDQuMTQ0NTMxLTQuMTQ4NDM4IDYuMzYzMjgxLTYuMzYzMjgybC0xLjQ0NTMxMy0xLjQ0NTMxMmgtMjcuMTA5Mzc1Yy0zLjA1MDc4MSAzLjA1MDc4MS00Ljc1NzgxMiA0Ljc1NzgxMi03LjgwODU5MyA3LjgwODU5NHY3OS41NjY0MDZsNy44MDg1OTMgNy44MDg1OTRoMjcuMTA5Mzc1bDEuNDQ1MzEzLTEuNDQ1MzEzYy0yLjIxODc1LTIuMjE4NzUtMy44MzIwMzEtMy44MzU5MzctNi4zNjMyODEtNi4zNjMyODF6bTAgMCIgZmlsbD0iI2NhZDhlYSIvPjxwYXRoIGQ9Im0zNTcuMTMyODEyIDk0Ljg3NXYtNzkuNTY2NDA2YzIuNTI3MzQ0LTIuNTMxMjUgNC4xNDQ1MzItNC4xNDg0MzggNi4zNTkzNzYtNi4zNjMyODItLjQ1MzEyNi0uNDUzMTI0LS45MjU3ODItLjkyNTc4MS0xLjQ0NTMxMy0xLjQ0NTMxMmgtMjcuMTA5Mzc1Yy0zLjA0Njg3NSAzLjA1MDc4MS00Ljc1NzgxMiA0Ljc1NzgxMi03LjgwNDY4OCA3LjgwODU5NHY3OS41NjY0MDZjMy4wNDY4NzYgMy4wNTA3ODEgNC43NTc4MTMgNC43NTc4MTIgNy44MDQ2ODggNy44MDg1OTRoMjcuMTA5Mzc1bDEuNDQ1MzEzLTEuNDQ1MzEzYy0yLjIxNDg0NC0yLjIxODc1LTMuODMyMDMyLTMuODM1OTM3LTYuMzU5Mzc2LTYuMzYzMjgxem0wIDAiIGZpbGw9IiNjYWQ4ZWEiLz48cGF0aCBkPSJtMzY0LjkzNzUgMTAyLjY4MzU5NGgyNy4xMDkzNzVjMy4wNTA3ODEtMy4wNTA3ODIgNC43NTc4MTMtNC43NTc4MTMgNy44MDg1OTQtNy44MDg1OTR2LTM5Ljc4NTE1NmgtMzB2MzkuNzg1MTU2Yy0yLjUzMTI1IDIuNTI3MzQ0LTQuMTQ0NTMxIDQuMTQ0NTMxLTYuMzYzMjgxIDYuMzU5Mzc1em0wIDAiIGZpbGw9IiNjMjFkNDQiLz48cGF0aCBkPSJtNy41IDU1LjA4OTg0NGgzMHY5NS4xODM1O
                                            TRoLTMwem0wIDAiIGZpbGw9IiNjMjFkNDQiLz48cGF0aCBkPSJtMTMyLjM2MzI4MSAxMDIuNjgzNTk0aDI3LjEwOTM3NWMzLjA0Njg3NS0zLjA1MDc4MiA0Ljc1NzgxMy00Ljc1NzgxMyA3LjgwNDY4OC03LjgwODU5NHYtMzkuNzg1MTU2aC0zMHYzOS43ODUxNTZjLTIuNTI3MzQ0IDIuNTI3MzQ0LTQuMTQ0NTMyIDQuMTQ0NTMxLTYuMzU5Mzc1IDYuMzU5Mzc1em0wIDAiIGZpbGw9IiNjMjFkNDQiLz48cGF0aCBkPSJtMTI0LjI0NjA5NCAzMzAuOTQ1MzEyIDM0LjY3NTc4MS0zNC42NzU3ODEtMTUtMTUtNDkuNjc1NzgxIDQ5LjY3NTc4MSAxMDEuNjcxODc1IDEwMS42NzE4NzYgMTUtMTV6bTAgMCIgZmlsbD0iIzZhZDM0ZCIvPjxwYXRoIGQ9Im0yNTIuMjAzMTI1IDExMC4xODM1OTRoLTE1di0xNWgxNXptLTI1IDBoLTE1di0xNWgxNXptMCAwIiBmaWxsPSIjZmZmIi8+PHBhdGggZD0ibTQ0OS40MTAxNTYgNDk3aC00MzQuNDEwMTU2di0zMjYuNzI2NTYyaC0xNXYzNDEuNzI2NTYyaDQ2NC40MTAxNTZ2LTM0MS43MjY1NjJoLTE1em0wIDAiLz48cGF0aCBkPSJtMTMyLjU3ODEyNSAxMTAuMTgzNTk0IDEyLjE5OTIxOS0xMi4yMDMxMjV2LTg1Ljc4MTI1bC0xMi4xOTkyMTktMTIuMTk5MjE5aC0zMy4zMjAzMTNsLTEyLjIwMzEyNCAxMi4xOTkyMTl2ODUuNzgxMjVsMTIuMjAzMTI0IDEyLjIwMzEyNXptLTMwLjUyMzQzNy05MS43Njk1MzIgMy40MTQwNjItMy40MTQwNjJoMjAuODk0NTMxbDMuNDE0MDYzIDMuNDE0MDYydjczLjM1NTQ2OWwtMy40MTQwNjMgMy40MTQwNjNoLTIwLjg5NDUzMWwtMy40MTQwNjItMy40MTQwNjN6bTAgMCIvPjxwYXRoIGQ9Im0zNjUuMTUyMzQ0IDExMC4xODM1OTQgMTIuMjAzMTI1LTEyLjIwMzEyNXYtODUuNzgxMjVsLTEyLjIwMzEyNS0xMi4xOTkyMTloLTMzLjMyMDMxM2wtMTIuMTk5MjE5IDEyLjE5OTIxOXY4NS43ODEyNWwxMi4xOTkyMTkgMTIuMjAzMTI1em0tMzAuNTE5NTMyLTkxLjc2OTUzMiAzLjQxNDA2My0zLjQxNDA2MmgyMC44OTQ1MzFsMy40MTQwNjMgMy40MTQwNjJ2NzMuMzU1NDY5bC0zLjQxNDA2MyAzLjQxNDA2M2gtMjAuODk0NTMxbC0zLjQxNDA2My0zLjQxNDA2M3ptMCAwIi8+PHBhdGggZD0ibTM4OS44NTU0NjkgNDcuNTg5ODQ0djE1aDU5LjU1NDY4N3Y4MC4xODM1OTRoLTQzNC40MTAxNTZ2LTgwLjE4MzU5NGg1OS41NTQ2ODh2LTE1aC03NC41NTQ2ODh2MTEwLjE4MzU5NGg0NjQuNDEwMTU2di0xMTAuMTgzNTk0em0wIDAiLz48cGF0aCBkPSJtMTU3LjI3NzM0NCA0Ny41ODk4NDRoMTQ5Ljg1NTQ2OHYxNWgtMTQ5Ljg1NTQ2OHptMCAwIi8+PHBhdGggZD0ibTMyMC40ODgyODEgMTk4LjA4NTkzOC0xMjQuNTcwMzEyIDEyNC41NzAzMTItNTEuOTk2MDk0LTUxLjk5NjA5NC02MC4yODUxNTYgNjAuMjg1MTU2IDExMi4yNzczNDMgMTEyLjI3NzM0NCAxODQuODU1NDY5LTE4NC44NTE1NjJ6bS0yMTUuNjM2NzE5IDEzMi44NTkzNzQgMzkuMDcwMzEzLTM5LjA3MDMxMiA1MS45OTYwOTQgNTEuOTk2MDk0IDEyNC41NzAzMTItMTI0LjU3MDMxMyAzOS4wNzAzMTMgMzkuMDcwMzEzLTE2My42NDA2MjUgMTYzLjY0MDYyNXptMCAwIi8+PC9zdmc+"/>
                                                {{$teacher_information->number_of_reservations}}</p>
                                        </div>
                                    </div>
                                    <div class="row w-100 h-50 d-flex pt-1">
                                        <div class="col-4 col-md-2 col-sm-4 d-sm-flex justify-content-sm-start">
                                            <p class="mb-0 mt-2 font-weight-bold">{{ __('student.review') }}: </p>
                                        </div>
                                        <div class="col-8 col-md-8 col-sm-8  my-2 ">
                                            @if(\App\Helpers\Helper::starTeacher($teacher_information->user_id) > 0)
                                                <input type="hidden" class="rating"  disabled data-filled="symbol-filled fa fa-star" data-empty="symbol-empty fa fa-star" data-fractions="2"  value="{{\App\Helpers\Helper::starTeacher($teacher_information->user_id)}}">
                                                <span class="">{{\App\Helpers\Helper::starTeacher($teacher_information->user_id)}}</span>
                                            @else
                                                <span class="">{{__('student.no_reviews')}}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="w-100 mb-2 align-items-center">
                                <div class="col-12">
                                    <label for="nationality">{{ __('student_book_lesson.lesson_history_with_this_teacher.lesson_history_with_this_teacher') }}</label>
                                    @if(sizeof($lesson_histories) > 0)
                                        <div class="card-body table-responsive p-0"
                                             @if(sizeof($lesson_histories) >= 5)
                                                style="height: 290px;"
                                             @endif>
                                            <table id="schedule" class="table table-head-fixed table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th>{{ __('student_book_lesson.lesson_history_with_this_teacher.date') }}</th>
                                                    <th>{{ __('student_book_lesson.lesson_history_with_this_teacher.time') }}</th>
                                                    <th>{{ __('student_book_lesson.lesson_history_with_this_teacher.course') }}</th>
                                                    <th>{{ __('student_book_lesson.lesson_history_with_this_teacher.lesson_name') }}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($lesson_histories as $lesson_history)
                                                    <tr>
                                                        <td>{{ \App\Helpers\Helper::formatDate($lesson_history->date) }}</td>
                                                        <td>{{\Carbon\Carbon::parse($lesson_history->time)->format('H:i')}}</td>
                                                        <td>{{$lesson_history->course_name}}</td>
                                                        <td>{{$lesson_history->lesson_name}}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <div>
                                            <span>{{ __('student_book_lesson.lesson_history_with_this_teacher.dont_have_history') }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <hr>
                            <div class="w-100 mb-3 align-items-center">
                                <div class="col-12">
                                    <label for="nationality">{{ __('student_book_lesson.self-introduction') }}</label>
                                    <div>
                                        <span>
                                            @if(Request::get('language') == 'vi' && Session::get('language') == 'vi')
                                                {{ $tr->translate($teacher_information->self_introduction) }}
                                            @else
                                                {{ $teacher_information->self_introduction }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class=" w-100 mb-3 align-items-center">
                                <div class="col-12">
                                    <label
                                        for="nationality">{{ __('student_book_lesson.introduction_from_the_staff') }}</label>
                                    <div>
                                        <span>
                                            @if(Request::get('language') == 'vi' && Session::get('language') == 'vi')
                                                {{ $tr->translate($teacher_information->introduction_from_admin) }}
                                            @else
                                                {{ $teacher_information->introduction_from_admin }}
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class=" w-100 mb-3 align-items-center">
                                <div class="col-12">
                                    <label for="nationality">{{ __('student_book_lesson.teaching_experience') }}</label>
                                    <div>
                                        <span>
                                            @if(Request::get('language') == 'vi' && Session::get('language') == 'vi')
                                            {{ $tr->translate($teacher_information->experience) }}
                                            @else
                                                {{ $teacher_information->experience }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-3">
                            <div class="card mt-1 mb-3">
                                <div class="card-body mb-3">
                                    <div class="w-100 mb-3 align-items-center">
                                        <input type="hidden" value="{{ $coinPerStudy }}" id="coin"/>
{{--                                        <input type="hidden" value="{{ ($countSuddenLesson < 2) ? 1 : 2  }}" id="type"/>--}}
                                        <input type="hidden" value="1" id="type"/>
                                        <label
                                            for="nationality">{{ __('student_book_lesson.lesson_now',['attribute'=>$coinPerStudy, 'attributeForCoins' => $coinPerStudy > 0 ? 's' : '']) }}</label>
                                        <div class="d-flex flex-column justify-content-center align-items-center">
                                            @if( !is_null($schedule) )
                                                <input type="hidden" value="{{ $schedule->start_hour }}"
                                                       id="start_hour"/>
                                                <input type="hidden" value="{{ $schedule->start_date }}"
                                                       id="start_date"/>
                                            @endif
                                            <input type="hidden" value="{{ Auth::id() }}" id="student_id"/>
                                            <input type="hidden" value="{{ $student_last_lesson->lesson_id }}" id="start_lesson_id"/>
                                            <input type="hidden" value="{{ $student_last_lesson->course_id }}" id="start_course_id"/>
                                            <button class="btn btn-warning w-75 " id="btnBookSchedule"
                                                @if( is_null($schedule) )
                                                    disabled
                                                    @endif
                                                    @if(in_array($student_membership_status, [1,4,5]))
                                                    disabled
                                                @endif>
                                                @if( is_null($schedule) )
                                                    {{ __('sudden_lesson.dont_have_course') }}
                                                @else
                                                    {{ __('student_book_lesson.start_lesson_now') }}
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                    <div class="w-100 mb-3 align-items-center">
                                        <label for="nationality">{{ __('student_book_lesson.book_lesson',['attribute'=>$teacher_coin]) }}</label>
                                        <div class=" d-flex justify-content-center">
                                            <button class="btn btn-success w-75"
                                                    id="btn_get_teacher_schedule"
                                                    @if(in_array($student_membership_status, [1,4,5]))
                                                    disabled
                                                @endif>
                                                {{ __('student_book_lesson.booking') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card mt-1 mb-5">
                                <div class="card-body mb-3">
                                    <div class="w-100 mb-3 align-items-center">
                                        <span for="" class="font-weight-bold">{{ __('student_book_lesson.teaching_courses') }}</span>
                                        <div class="ml-4 mt-1">
                                            @foreach($teacher_information->courses as $course)
                                                <p>{{$course->course_name}}</p>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card w-100">
                <div class="card-header">
                    <h5 class="card-title font-weight-bold">{{__('student.review')}}
                        <span class="font-weight-bold">({{$getNumberStar['result']}} @if($teacher_information->number_of_lessons > 1) {{__('student.reviews')}}) @else {{__('student.review')}}) @endif</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="box_review card" style="border: 1px solid #ccc; height: 230px">
                                <div class="card-header">
                                    <h5 class="font-weight-bold card-title">{{__('student.user_evaluation')}}</h5>
                                </div>
                                <div class="card-body  text-center ">
                                    <div class="py-5">
                                        <input type="hidden" class="rating"  disabled data-filled="symbol-filled fa fa-star" data-empty="symbol-empty fa fa-star" data-fractions="2"  value="{{\App\Helpers\Helper::starTeacher($teacher_information->user_id)}}">
                                        <span class="mx-1 font-weight-bold">{{\App\Helpers\Helper::starTeacher($teacher_information->user_id)}}</span>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6 col-md-4">
                            <div class="card box-number_rating" style="border: 1px solid #ccc; height: 230px">
                                <div class="card-body py-3 text-center">
                                    <div class="row p-0">
                                        <p class="col-3 font-weight-bold">5 {{__('student.star')}}</p>
                                        <div class="progress col-6 my-1 px-0">
                                            <div class="progress-bar" role="progressbar" style="width: {{number_format($getNumberStar['5'] , 2)}}%; background-color : #f0ad4e;" aria-valuenow="{{(int)$getNumberStar['5']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="col-3 font-weight-bold">{{$getNumberStar['5star']}}/{{$getNumberStar['result']}}</p>
                                    </div>
                                    <div class="row p-0">
                                        <p class="col-3 font-weight-bold">4 {{__('student.star')}}</p>
                                        <div class="progress col-6 my-1 px-0">
                                            <div class="progress-bar" role="progressbar" style="width: {{number_format($getNumberStar['4'] , 2)}}%; background-color : #f0ad4e;" aria-valuenow="{{(int)$getNumberStar['4']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="col-3 font-weight-bold">{{$getNumberStar['4star']}}/{{$getNumberStar['result']}}</p>

                                    </div>
                                    <div class="row p-0">
                                        <p class="col-3 font-weight-bold">3 {{__('student.star')}}</p>
                                        <div class="progress col-6 my-1 px-0">
                                            <div class="progress-bar" role="progressbar" style="width: {{number_format($getNumberStar['3'] , 3)}}%; background-color : #f0ad4e;" aria-valuenow="{{(int)$getNumberStar['3']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="col-3 font-weight-bold">{{$getNumberStar['3star']}}/{{$getNumberStar['result']}}</p>

                                    </div>
                                    <div class="row p-0">
                                        <p class="col-3 font-weight-bold">2 {{__('student.star')}}</p>
                                        <div class="progress col-6 my-1 px-0">
                                            <div class="progress-bar" role="progressbar" style="width: {{number_format($getNumberStar['2'] , 2)}}%; background-color : #f0ad4e;" aria-valuenow="{{(int)$getNumberStar['2']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="col-3 font-weight-bold">{{$getNumberStar['2star']}}/{{$getNumberStar['result']}}</p>

                                    </div>
                                    <div class="row p-0">
                                        <p class="col-3 font-weight-bold">1 {{__('student.star')}}</p>
                                        <div class="progress col-6 my-1 px-0">
                                            <div class="progress-bar" role="progressbar" style="width: {{number_format($getNumberStar['1'] , 2)}}%; background-color : #f0ad4e;" aria-valuenow="{{(int)$getNumberStar['1']}}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <p class="col-3 font-weight-bold">{{$getNumberStar['1star']}}/{{$getNumberStar['result']}}</p>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="review_detail" id="review_detail">
                        @include('admin.students.review_teacher_detail')
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div class="modal fade" id="modal-lg">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title" style="height: 30px">
                        <h4 class="font-weight-bold">{{ __('student_book_lesson.teacher_schedule.teacher_schedule') }}</h4>
                    </div>
                    <hr>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class=" d-flex  float-left">
                                <section class="col-sm-12" id="error_section">
                                </section>
                            </div>
                            <div class="card-header d-flex justify-content-center float-right">
                                <div class="d-sm-flex justify-content-sm-center">
                                    <span class="badge badge-secondary mr-1" style="padding: 15px"> </span>
                                    <span class="text-center pb-0 mr-3 "> : {{ __('student_book_lesson.teacher_schedule.unavailable') }}</span>
                                </div>
                                <div class="d-sm-flex justify-content-sm-center">
                                    <span class="badge badge-success mr-1" style="padding: 15px"> </span>
                                    <span class="text-center pb-0 mr-3 ">: {{ __('student_book_lesson.teacher_schedule.available') }}</span>
                                </div>
                                <div class="d-sm-flex justify-content-sm-center">
                                    <span class="badge badge-warning mr-1" style="padding: 15px"> </span>
                                    <span class="text-center pb-0 ">: {{ __('student_book_lesson.teacher_schedule.your_booking') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive " id="tabledata">
                        <table id="table-booking" class="table table-bordered table-hover">

                        </table>
                    </div>
                    <p>※ {{ __('student_book_lesson.teacher_schedule.note1') }}</p>
                    <p>※ {{ __('student_book_lesson.teacher_schedule.note2') }}</p>
                    <p>※ {{ __('student_book_lesson.teacher_schedule.note3') }}
                    </p>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <form action="" method="post" id="validateBookLesson">
                        @csrf
                        <button type="button" class="btn btn-secondary btn-flat btnCancel" data-dismiss="modal"
                                id="btnCancelBooking">{{ __('student_book_lesson.teacher_schedule.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary btn-flat" id="validate" data-toggle="modal"
                                disabled>
                            {{ __('student_book_lesson.teacher_schedule.booking') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="modalConfirm-Booking" style="overflow: auto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="border-0 px-4 py-2">
                    <h4 class="modal-title font-weight-bold ">  {{ __('student_book_lesson.confirm_booking.confirm_booking') }}</h4>
                    <hr class="" style="border-color :  #ccc; margin :10px 0"/>
                    <p class="modal-title font-weight-bold ">{{ __('student_book_lesson.confirm_booking.note1') }}</p>
                    <div class="d-flex  float-left">
                        <section class="col-sm-12" id="error_section_confirm">
                        </section>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="table-responsive " id="tabledata">
                        <table id="" class="table table-bordered table-hover">
                            <tbody id="confirm">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-secondary btn-flat btnCancel" data-dismiss="modal"
                            id="btnCancelConfirm">{{ __('student_book_lesson.confirm_booking.cancel') }}
                    </button>
                    <button type="button" class="btn btn-primary btn-flat" id="btnConfirm">
                        {{ __('student_book_lesson.confirm_booking.booking') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_require_add_coin" style="overflow: auto">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="border-0 px-4 py-2">
                </div>
                <div class="modal-body" id="require_add_coin">
                </div>
                <div class="modal-footer justify-content-center border-0">
                    <button type="button" class="btn btn-secondary btn-flat" data-dismiss="modal" id="btnOK">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalNotificationWhenLackOfCoin" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="d-flex flex-column justify-content-center align-items-center my-3">
                        <h6>{{ __('validation_custom.M038_1')}},<a href="{{ route('student.add-coin') }}">{{ __('validation_custom.M038_2')}}</a></h6>
                    </div>

                    <div class="col-12 d-flex justify-content-center col-sm-12 d-sm-flex justify-content-sm-center">
                        <button type="button" class="btn btn-secondary mr-2" data-dismiss="modal">OK</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal-lesson" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width: 500px;!important" role="document">
            <div class="modal-content">
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
                <div class="border-0 px-4 py-2">
                    <h4 class="modal-title font-weight-bold">  {{ __('student_book_lesson.course_selection') }}</h4>
                    <hr class="" style="border-color : #ccc; margin :10px 0"/>
                    <div class="d-flex float-left">
{{--                        //latest lesson--}}
{{--                        <span class="text-warning mr-2 latestLesson @if($current_student_lesson->lesson_id == $latestLesson->id) d-none @endif " style="font-size: 20px"><i class="fas fa-exclamation-triangle"></i></span>--}}
{{--                        <span class='latestLesson @if($latestLesson->id == $current_student_lesson->lesson_id) d-none @endif ' style="color: #E53A40"> {{ __('validation_custom.M074') }}</span>--}}
{{--                        --}}
                        <p class="modal-title font-weight-bold @if(!$checkTeacherCanTeach) d-none @endif" id="teacherCanTeach">{{ __('student_book_lesson.select_lesson') }}</p>
                        <span class="text-warning mr-2 teacherCantTeach @if($checkTeacherCanTeach) d-none @endif " style="font-size: 20px"><i class="fas fa-exclamation-triangle"></i></span>
                        <span class='teacherCantTeach @if($checkTeacherCanTeach) d-none @endif ' style="color: #E53A40" id="messageCantTeach"> {{ __('validation_custom.M071') }}</span>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('student_panel.course') }}</label>
                        <select class="form-control" id="course_id_select" name="course_id_select">
                            @if(!$checkTeacherCanTeach)<option value="" selected id="course_empty"></option>@endif
                            @foreach($teacher_courses as $course)
                                <option value="{{$course->course_id}}">
                                    {{$course->course_name}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('student_panel.lesson') }}</label>
                        <select class="form-control" name="lesson_id_select" id="lesson_id_select" @if(!$checkTeacherCanTeach) disabled @endif >
{{--                            @foreach($lessons as $lesson)--}}
{{--                                <option value="{{$lesson->id}}" @if($student_last_lesson->lesson_id === $lesson->id) selected @endif>--}}
{{--                                    {{$lesson->name}}--}}
{{--                                </option>--}}
{{--                            @endforeach--}}
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-sm-12">
                        <form id="form-get-lesson">
                            <button type="button" class="btn btn-primary btn-sm float-right" id="btnChooseLesson"  @if(!$checkTeacherCanTeach) disabled @endif>{{ __('button.ok') }}</button>
                            <button type="button" class="btn btn-default btn-sm float-left" data-dismiss="modal" id="btnCancelChooseLesson">{{ __('button.cancel') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/students/lessons/student_book_lesson.js') }}"></script>
    <script>
        $(document).ready(function(){
            $(document).on('click', '.pagination a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                getPosts(page);
            });
            function getPosts(page)
            {
                $.ajax({
                    type: "GET",
                    url: '?page='+ page,
                    success : function (data) {
                        $('#review_detail').html(data)
                        $('input.rating').rating()
                    }
                })
            }
        })



    </script>
@endpush
