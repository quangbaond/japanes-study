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
                        <h2 class="text-center">Japanese Study</h2>
                        <p class="text-center">URLの有効期限が切れました。再度登録を実行してください。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
@endsection
