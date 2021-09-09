@extends('layouts.admin.app')
@section('stylesheets')

@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <img src="{{ $imageUrl }}" alt="" style="height: 300px; width: 500px;">
            </div>
        </div>
    </section>
@endsection
@push('scripts')

@endpush
