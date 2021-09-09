@extends('layouts.admin.app')
@section('breadcrumb')
    {{ Breadcrumbs::render('index_plan') }}
@endsection

@section('stylesheets')

@endsection

@section('title_screen', 'プラン一覧')
@section('content')
    <style>
        thead > tr > th {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            width: 20px;
        }
    </style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a href="{{route('plans.create')}}">
                            <button type="button" class="btn btn-success btn-flat btn-sm float-right">追加</button>
                        </a>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body table-responsive pad">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>コード</th>
                                <th>プラン名</th>
                                <th>料金 (VND)</th>
                                <th>期間単位</th>
                                <th>量</th>
                                <th>説明</th>
                                <th>作成日</th>
{{--                                <th></th>--}}
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($plans as $item)
                                <tr>
                                    <td>{{ $item->id }}</td>
                                    <td>{{ $item->stripe_plan }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ number_format($item->cost) }}</td>
                                    <td>{{ $item->interval }}</td>
                                    <td>{{ $item->interval_count }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ \App\Helpers\Helper::formatDate($item->created_at) }}</td>
{{--                                    <td>--}}
{{--                                        <a href="{{ route('plans.edit', ['id' => $item->id]) }}"><button class="btn btn-sm btn-primary btn-flat">変更</button></a>--}}
{{--                                        <button class="btn btn-sm btn-danger btn-flat">削除</button>--}}
{{--                                    </td>--}}
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
