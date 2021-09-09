@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('create_plan') }}
@endsection

@section('stylesheets')

@endsection

@section('title_screen', '追加')

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <form action="{{route('plans.store')}}" method="post">
                    @csrf

                    {{-- name --}}
                    <div class="form-group">
                        <label for="plan name">プラン名: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')}}">
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- .name --}}

                    {{-- cost --}}
                    <div class="form-group">
                        <label for="cost">料金: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('cost') is-invalid @enderror" name="cost" value="{{old('cost')}}">
                        @error('cost')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- .cost --}}

                    {{-- interval --}}
                    <div class="form-group">
                        <label for="interval">期間単位: <span class="text-danger">*</span></label>
                        <select class="form-control" name="interval">
                            <option value="day">day</option>
                            <option value="week">week</option>
                            <option value="month">month</option>
                            <option value="year">year</option>
                        </select>
                    </div>
                    {{-- .interval --}}

                    {{-- interval_count --}}
                    <div class="form-group">
                        <label for="interval_count">量: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('interval_count') is-invalid @enderror" name="interval_count" value="{{old('interval_count')}}">
                        @error('interval_count')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- .interval_count --}}

                    {{-- description --}}
                    <div class="form-group">
                        <label for="cost">説明: <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" name="description" value="{{old('description')}}">
                        @error('description')
                            <span class="invalid-feedback" role="alert">
                                {{ $message }}
                            </span>
                        @enderror
                    </div>
                    {{-- .description --}}

                    <button type="submit" class="btn btn-primary btn-flat float-right">登録</button>
                </form>
            </div>
        </div>
    </div>
@endsection
