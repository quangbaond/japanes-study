@extends('layouts.admin.app')
@section('admin_title')
    {{ "Manager" }}
@endsection
@section('stylesheets')

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Danh sách các đơn hàng đang thanh toán tự động</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="subscriptions" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th style="width: 10px">ID</th>
                                <th>Customer</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($subscription as $item)
                                    @if ($customer_id != "" && $customer_id == $item->customer)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                {{ Auth::user()->email }}
                                            </td>
                                            <td>{{ $item->status }}</td>
                                            <td>
                                                <button class="btn btn-primary btn-flat btn-sm">Detail</button>
                                                <form method="POST" action="{{ route('subscription.cancel') }}" id="formCancelSub">
                                                    @csrf
                                                    <input type="hidden" value="{{$item->id}}" name="sub_id">
                                                    <button type="button" class="btn btn-default btn-flat btn-sm" id="cancel_subscription" data-sub-id="{{ $item->id }}">Cancel</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endif
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
        <!-- /.row -->
    </div>
@endsection
@push('scripts')
    <script>
        $(function () {
            $('#subscriptions').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

            $('body').on('click', '#cancel_subscription', function(){
                $("#formCancelSub").submit();
            });

            // $('body').on('click', '#cancel_subscription', function(){
            //     $.ajaxSetup({
            //         headers: {
            //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //         }
            //     });
            //     var formData = {
            //         sub_id: $(this).data("sub-id"),
            //     };
            //     $.ajax({
            //         type: "POST",
            //         url: "/manager/student/subscription/cancel",
            //         data: formData,
            //         success: function(result){
            //
            //         },
            //         error: function(result){
            //             console.log(result);
            //         }
            //     });
            // });
        });
    </script>
@endpush
