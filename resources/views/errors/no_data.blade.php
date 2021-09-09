@extends('layouts.admin.app')
@section('admin_title')
    {{ "Manager" }}
@endsection
@section('stylesheets')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="callout callout-warning">
            <h4>Warning!</h4>
            <p>{{ __('notification.no_data') }}</p>
        </div>
    </section>
    <!-- /.content -->
@endsection

