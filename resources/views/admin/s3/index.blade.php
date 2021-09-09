@extends('layouts.admin.app')
@section('stylesheets')
    <style>
        #image_url {
            width: 0px;
            height: 0px;
            overflow: hidden;
        }
    </style>
@endsection
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                {!! Form::open(array('route' => 'admin.s3.create','method'=>'POST', 'id' => 'formS3', 'enctype'=>'multipart/form-data')) !!}
                <div class="box">

                    <div class="box-body no-padding">
                        <table class="table table-condensed">

                            {{-- image --}}
                            <tr>
                                <td style="width: 30%"><strong>{{__('shop.image')}}</strong></td>
                                <td>
                                    <input type="file" name="image_url" id="image_url" value="">
                                    <img id="image" src="{{ asset('images/avatar_2.png') }}" alt="Image" style="height: 100px; width: 125px">
                                    <br>
                                    <button id="choice_image" type="button" class="btn btn-primary btn-flat ">ファイルを選択</button>
                                    @error('image_url') <span class="help-block" style="color: #dd4b39">{{ $message }}</span> @enderror
                                </td>
                            </tr>

                            <buton type="button" class="btn btn-primary pull-right" id="btnUpdate">更新</buton>
                        </table>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            // Choice image
            $("#choice_image").click(function () {
                $('#image_url').trigger('click');
            });

            // Change image
            $("#image_url").change(function () {
                if (this.files && this.files[0]) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        if (validImage("#image_url")) {
                            $('#image').attr('src', e.target.result);
                        } else {
                            alert('画像形式が正しくありません。')
                        }
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            });
            // Valid Image
            function validImage(file_id) {
                let fileExtension = ['jpg', 'jpeg', 'png'];
                let valid = true;
                let msg = "";
                if ($(file_id).val() == '') {
                    valid = false;
                } else {
                    var fileName = $(file_id).val();
                    var fileNameExt = fileName.substr(fileName.lastIndexOf('.') + 1).toLowerCase();
                    if ($.inArray(fileNameExt, fileExtension) == -1) {
                        valid = false;
                    }
                }
                return valid //true or false
            }

            $('#btnUpdate').click(function(){
                $('#formS3').submit();
            });
        });
    </script>
@endpush
