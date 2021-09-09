@extends('layouts.admin.app')

@section('content')
    <div class="container">
        <form action="{{ route('notification.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label>Tạo thông báo realtime</label>
            </div>
            <div class="form-group">
                <label for="sel1">Chọn user nhận thông :</label>
                <select class="form-control" name="user">
                    @foreach(\App\Models\User::all() as $value)
                        @if($value->id != Auth::user()->id)
                            <option value="{{ $value->id }}">{{ $value->email }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Title</label>
                <input name="title" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Content</label>
                <input name="content" type="text" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
