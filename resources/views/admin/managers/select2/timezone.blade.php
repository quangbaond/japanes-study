@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('create_plan') }}
@endsection

@section('stylesheets')

@endsection

@section('title_screen', 'Timezone')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Timezone</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form action="{{route('select2.convertTimezone')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <input type="text" class="form-control" name="timezone" value="2021-03-18 13:00:00">
                                </div>
                                <button type="submit" class="btn btn-primary btn-flat float-right">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush




