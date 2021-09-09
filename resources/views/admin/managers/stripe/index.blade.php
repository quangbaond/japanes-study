@extends('layouts.admin.app')
@section('admin_title')
    {{ "Manager" }}
@endsection
@section('stylesheets')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách package</h3>
                        <a href="{{route('create.plan')}}">
                            <button type="button" class="btn btn-success btn-flat btn-sm float-right">Create</button>
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Name</th>
                                <th>Active</th>
                                <th>Description</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($list_product as $item)
                                    <tr>
                                        <td>{{ $item->id }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->active }}</td>
                                        <td>{{ $item->description }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
    </div>
@endsection
@push('scripts')

@endpush
