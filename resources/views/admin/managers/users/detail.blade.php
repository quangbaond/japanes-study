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
                            <h3 class="card-title">{{ __('user.detail_user') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="tab-pane">
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{__('user.id')}}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="id" id="id" value="{{ $user->id }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{__('user.name')}}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="name" id="name" value="{{ $user->name }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{__('user.email')}}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="email" id="name" value="{{ $user->email }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('user.role') }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="role" id="role" value="{{ $user->role }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('user.auth') }}</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="auth" disabled>
                                            <option @if($user->auth == 0) selected @endif value="0">No</option>
                                            <option @if($user->auth == 1) selected @endif value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('user.status') }}</label>
                                    <div class="col-sm-10">
                                        <select class="form-control" name="status" disabled>
                                            <option @if($user->status == 0) selected @endif value="0">No</option>
                                            <option @if($user->status == 1) selected @endif value="1">Yes</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{ __('user.user_create') }}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="user_create" id="user_create" value="{{ $user->user_create }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-sm-2 col-form-label">{{__('user.created_at')}}</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="created_at" id="created_at" value="{{ App\Helpers\Helper::formatDate($user->created_at) }}" disabled>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="offset-sm-2 col-sm-10">
                                        <a href="{{ route('user') }}"><buton type="button" class="btn btn-default btn-flat">{{ __('button.cancel') }}</buton></a>
                                        <buton type="button" class="btn btn-danger float-right btn-flat" id="delete-user">{{ __('button.delete') }}</buton>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>
    {!! Form::open([ 'method' => 'POST', 'route' => ['user.delete'], 'style' => 'display:inline', 'id' => 'formDeleteUser' ]) !!}
    <input type="hidden" value="{{$user->id}}" name="id">
    {!! Form::close() !!}
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/users/detail.js') }}"></script>
@endpush

