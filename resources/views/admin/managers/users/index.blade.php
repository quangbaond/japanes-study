@extends('layouts.admin.app')
@section('stylesheets')
    <meta name="confirm-delete" content="{{ __('user.confirm-delete') }}">
@endsection

@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <div class="col-sm-3">
                                <select class="form-control" name="fieldTable" id="fieldTable">
                                    <option value="name">{{ __('user.name')}}</option>
                                    <option value="email">{{ __('user.email')}}</option>
                                </select>
                            </div>
                            <div class="col-sm-7">
                                <input type="text" name="valueSearch" id="valueSearch" class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-default" id="btnSearch">{{ __('button.search')}}</button>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <!-- delete all & create -->
                        {!! Form::open([ 'method' => 'POST', 'route' => ['user.delete-all'], 'style' => 'display:inline', 'id' => 'formDeleteAllUser' ]) !!}
                        <buton type="submit" class="btn btn-danger btn-flat" id="delete-all-user">{{ __('button.delete_all') }}</buton>
                        {!! Form::close() !!}

                        <table id="users" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="check_all">
                                </th>
                                <th class="hidden">{{ __('user.id')}}</th>
                                <th>{{ __('user.name')}}</th>
                                <th>{{ __('user.email')}}</th>
                                <th>{{ __('user.role')}}</th>
                                <th>{{ __('user.auth')}}</th>
                                <th>{{ __('user.user_online')}}</th>
                                <th>{{ __('user.created_at')}}</th>
                                <th>{{ __('user.action')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
@push('scripts')
<script src="{{ asset('js/admin/users/index.js') }}"></script>
<script>
    $(document).ready(function () {
        $('#users').DataTable({
            drawCallback: function() {
                let pagination = $(this).closest('.dataTables_wrapper').find('.dataTables_paginate');
                let info = $(this).closest('.dataTables_wrapper').find('.dataTables_info');
                pagination.toggle(this.api().page.info().pages > 0);
                info.toggle(this.api().page.info().pages > 0);
            },
            'lengthChange': false,
            'searching'   : false,
            "order": [[ 0, "desc" ]],
            'autoWidth'   : false,
            language: {
                "url": "{{ asset('admin/Japanese.json') }}"
            },
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('user.data') }}",
                type: 'GET',
                data: function (d) {
                    d.fieldTable = $('#fieldTable option:selected').val();
                    d.valueSearch = $('#valueSearch').val();
                }
            },
            columns: [
                { data: 'checkbox', name: 'checkbox', orderable: false, searchable: false },
                { data: 'id', name: 'id', "visible": false },
                { data: 'nickname', name: 'nickname' },
                { data: 'email', name: 'email' },
                { data: 'role', name: 'role' },
                { data: 'auth', name: 'auth' },
                { data: 'user-online', name: 'user-online' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action' }
            ]
        })

        $('#btnSearch').click(function() {
            $('#users').DataTable().draw(true);
        });

        // check all
        $('#check_all').on('click', function(e) {
            let check = $(".chk_item");
            $("#formDeleteAllUser").find("input[name='user_id[]'").remove();
            if($(this).prop("checked")) {
                check.prop('checked', true);
                check.each(function() {
                    $("#formDeleteAllUser").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
                });
            } else {
                check.prop('checked',false);
                check.each(function() {
                    $("#id-" + $(this).val()).remove();
                });
            }
        });

        //check item
        $("body").on("change", ".chk_item", function(){
            if (false == $(this).prop("checked")) {
                $("#check_all").prop('checked', false);
            };
            if ( $('.chk_item:checked').length == $('.chk_item').length ) {
                $("#check_all").prop('checked', true);
            };
            if ($(this).prop("checked")) {
                $("#formDeleteAllUser").append('<input type="hidden" id="id-'+$(this).val()+'" name="user_id[]" value="'+$(this).val()+'">');
            } else {
                $("#id-" + $(this).val()).remove();
            }
        });
    });
</script>
@endpush
