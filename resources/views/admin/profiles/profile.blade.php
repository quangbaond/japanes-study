@extends('layouts.admin.app')
@section('admin_title')
    {{ "Manager" }}
@endsection
@section('stylesheets')

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
                            <h3 class="profile-username text-center">{{ $profile->name }}</h3>
                            <p class="text-muted text-center">{{ $profile->name }}</p>
                            <div class="text-center">
                                <button data-toggle="modal" data-target="#modalChangePassword" type="button" class="btn btn-primary btn-flat">
                                    {{ __('profile.change_password') }}
                                </button>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <!-- About Me Box -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('profile.about_me') }}</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <strong><i class="fas fa-book mr-1"></i>{{ __('profile.self_introduction') }}</strong>

                            <p class="text-muted">
                                {{ $profile->introduction }}
                            </p>

                            <hr>

                            <strong><i class="fas fa-map-marker-alt mr-1"></i> {{ __('profile.country') }}</strong>

                            <p class="text-muted">{{ $profile->country }}</p>

                            <hr>

                            <strong><i class="fas fa-pencil-alt mr-1"></i>{{ __('profile.skype') }}</strong>

                            <p class="text-muted">
                                <span class="tag tag-danger">{{ $profile->skype_id }}</span>
                            </p>

                            <hr>

                            <strong><i class="far fa-file-alt mr-1"></i>{{ __('profile.experience') }}</strong>

                            <p class="text-muted">{{ $profile->experience }}</p>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
                <div class="col-md-9">
                    <div class="card card-info">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('profile.profile') }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="tab-pane">
                                <form class="form-horizontal" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('user.email') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="email" value="{{ $profile->email }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group row  @error('name') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('user.name') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="name" id="name" value="{{ $profile->name }}">
                                            @error('name') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row @error('skype_id') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('profile.skype') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="skype_id" id="skype_id" value="{{ $profile->skype_id }}">
                                            @error('skype_id') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row @error('age') has-error @enderror">
                                        <label class="col-sm-2 col-form-label">{{ __('profile.age') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="age" id="age" value="{{ $profile->age }}">
                                            @error('age') <span class="help-block" style="color: red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('profile.sex') }}</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" name="sex">
                                                <option @if($profile->sex == 0) selected @endif value="0">{{__('profile.male')}}</option>
                                                <option @if($profile->sex == 1) selected @endif value="1">{{__('profile.female')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('profile.country') }}</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" name="country" id="country" value="{{ $profile->country }}">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('profile.experience') }}</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="experience" id="experience">{{ $profile->experience }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 col-form-label">{{ __('profile.self_introduction') }}</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="self-introduction" id="self-introduction">{{ $profile->introduction }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-flat">{{ __('button.update') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                                <!-- /.tab-pane -->
                        </div><!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

    <!-- Modal - change password - start -->
    <form action="" method="POST" id="formChangePassword" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="{{ $profile->id}}">
        @csrf
        <div id="modalChangePassword" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><span class="icon-key"></span>{{ __('profile.change_password') }}</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>{{ __('profile.password_old') }} <span style="color:red">*</span></label>
                            <input type="password" class="form-control" name="password_old" id="password_old">
                            <span style="color: #dd4b39;" id="error_password_old"></span>
                        </div>
                        <div class="form-group">
                            <label>{{ __('profile.password_new') }} <span style="color:red">*</span></label>
                            <input type="password" class="form-control" name="password_new" id="password_new">
                            <span style="color: #dd4b39;" id="error_password_new"></span>
                        </div>
                        <div class="form-group">
                            <label>{{ __('profile.password_confirm') }} <span style="color:red">*</span></label>
                            <input type="password" class="form-control" name="password_confirm" id="password_confirm">
                            <span style="color: #dd4b39;" id="error_password_confirm"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default btn-sm btn-flat" data-dismiss="modal">{{ __('profile.cancel') }}</button>
                        <button id="btnChangePassword" type="button" class="btn btn-success btn-sm btn-flat">{{ __('profile.submit') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- Modal - change password - end-->
@endsection
@push('scripts')
    <script src="{{ asset('js/admin/profiles/profile.js') }}"></script>
@endpush

