{{--<!DOCTYPE html>--}}
{{--<html lang="en">--}}
{{--<head>--}}
{{--    <meta charset="UTF-8">--}}
{{--    <meta name="viewport" content="width=device-width, initial-scale=1.0">--}}
{{--    <title>Document</title>--}}
{{--</head>--}}
{{--<body>--}}
{{--    <h3 style="text-align: center;">メールアドレス変更手続きが完了しました。</h3>--}}
{{--    <p style="text-align: center;">ログインしてメールアドレスが変更されている事を確認してください。</p>--}}
{{--    <p style="text-align: center;"><a href="{{ route('login.teacher') }}" >ログイン</a></p>--}}
{{--</body>--}}
{{--</html>--}}

@extends('layouts.app')

@section('stylesheets')
    <link rel="stylesheet" href="{{ asset('css/admin/students/step.css') }}"/>
@endsection

@section('content')
    <br>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- info row -->
                    <div class="card-body box-profile">
                        <h2 class="text-center">メールアドレス変更手続きが完了しました。</h2>
                        <p class="text-center">ログインしてメールアドレスが変更されている事を確認してください。</p>
                        <p style="text-align: center;"><a href="{{ route('login.teacher') }}" >ログイン</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
