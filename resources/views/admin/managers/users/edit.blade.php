@extends('layouts.admin.app')
@section('stylesheets')
    <meta name="confirm-delete" content="{{ __('user.confirm-delete') }}">
@endsection
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <!-- Profile Image -->
                    <div class="card card-primary card-outline">
                        <div class="card-body box-profile">
                            <div class="text-center">
                                <img class="profile-user-img img-fluid img-circle"
                                     src="{{ asset('template/admin/dist/img/user3-128x128.jpg') }}"
                                     alt="User profile picture">
                            </div>

                            <h3 class="profile-username text-center">{{ $user->name }}</h3>

                            <p class="text-muted text-center">{{ $user->email }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <!-- /.card -->
                    <!-- Horizontal Form -->
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('user.edit_user') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <div class="card-body">
                            <div class="tab-pane">
                                {!! Form::open(array('route' => 'user.update','method'=>'POST', 'id' => 'formEdit', 'enctype'=>'multipart/form-data')) !!}
                                    <input type="hidden" value="{{$user->id}}" name="user_id">
                                    <div class="form-group row  @error('name') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{__('user.name')}}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}">
                                            @error('name') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row  @error('email') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{__('user.email')}}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="email" id="name" value="{{ $user->email }}">
                                            @error('email') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row  @error('role') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('user.role') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="role" id="role" value="{{ $user->role }}">
                                            @error('role') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row  @error('auth') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('user.auth') }}</label>
                                        <div class="col-sm-10">
                                            <select class="form-control @error('auth') has-error @enderror" name="auth">
                                                <option @if($user->auth == 0) selected @endif value="0">No</option>
                                                <option @if($user->auth == 1) selected @endif value="1">Yes</option>
                                            </select>
                                            @error('auth') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row  @error('status') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('user.status') }}</label>
                                        <div class="col-sm-10">
                                            <select class="form-control  @error('status') has-error @enderror" name="status">
                                                <option @if($user->status == 0) selected @endif value="0">No</option>
                                                <option @if($user->status == 1) selected @endif value="1">Yes</option>
                                            </select>
                                            @error('status') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row  @error('user_create') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('user.user_create') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="user_create" id="user_create" value="{{ $user->user_create }}">
                                            @error('user_create') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <a href="{{ route('user') }}"><buton type="button" class="btn btn-default btn-flat">{{ __('button.cancel') }}</buton></a>
                                            <buton type="button" class="btn btn-primary float-right btn-flat" id="btnUpdate">{{ __('button.update') }}</buton>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/users/edit.js') }}"></script>
@endpush

