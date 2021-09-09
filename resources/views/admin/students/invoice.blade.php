@extends('layouts.admin.app')
@section('admin_title')
    {{--    {{ $data->title }}--}}
@endsection
@section('stylesheets')
    {{--    <link rel="stylesheet" href="{{ asset('css/admin/products/create.css')  }}">--}}
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="callout callout-info">
                    <h5><i class="fas fa-info"></i> Note:</h5>
                    This page has been enhanced for printing. Click the print button at the bottom of the invoice to test.
                </div>


                <!-- Main content -->
                <div class="invoice p-3 mb-3">
                    <!-- title row -->
                    <div class="row">
                        <div class="col-12">
                            <h4>
                                <i class="fas fa-globe"></i> AdminLTE, Inc.
                                <small class="float-right">Date: 2/10/2014</small>
                            </h4>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- info row -->
                    <div class="row invoice-info">
                        <div class="col-sm-4 invoice-col">
                            From
                            <address>
                                <strong>Admin, Inc.</strong><br>
                                795 Folsom Ave, Suite 600<br>
                                San Francisco, CA 94107<br>
                                Phone: (804) 123-5432<br>
                                Email: info@almasaeedstudio.com
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            To
                            <address>
                                <strong>John Doe</strong><br>
                                795 Folsom Ave, Suite 600<br>
                                San Francisco, CA 94107<br>
                                Phone: (555) 539-1037<br>
                                Email: john.doe@example.com
                            </address>
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-4 invoice-col">
                            <b>Invoice #007612</b><br>
                            <br>
                            <b>Order ID:</b> 4F3S8J<br>
                            <b>Payment Due:</b> 2/22/2014<br>
                            <b>Account:</b> 968-34567
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- Table row -->
                    <div class="row">
                        <div class="col-12 table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Qty</th>
                                    <th>Product</th>
                                    <th>Serial #</th>
                                    <th>Description</th>
                                    <th>Subtotal</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>MH-01</td>
                                    <td>455-981-221</td>
                                    <td>El snort testosterone trophy driving gloves handsome</td>
                                    <td>$10.00</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <div class="row">
                        <!-- accepted payments column -->
                        <div class="col-6">
                            <p class="lead">Payment Methods:</p>
                            <img src="{{ asset('template/admin/dist/img/credit/visa.png') }}" alt="Visa">
                            <img src="{{ asset('template/admin/dist/img/credit/mastercard.png') }}" alt="Mastercard">
                            <img src="{{ asset('template/admin/dist/img/credit/american-express.png') }}" alt="American Express">
                            <img src="{{ asset('template/admin/dist/img/credit/paypal2.png') }}" alt="Paypal">
                        </div>
                        <!-- /.col -->
                        <div class="col-6">
                            <p class="lead">Amount Due 2/22/2014</p>

                            <div class="table-responsive">
                                <table class="table">
                                    <tr>
                                        <th style="width:50%">Subtotal:</th>
                                        <td>$10.00</td>
                                    </tr>
                                    <tr>
                                        <th>Tax</th>
                                        <td>$0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Shipping:</th>
                                        <td>$0.00</td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td>$10.00</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <!-- /.col -->
                    </div>
                    <!-- /.row -->

                    <!-- this row will not appear when printing -->
                    <div class="row no-print">
                        <div class="col-12">
                            <a href="" rel="noopener" target="_blank" class="btn btn-default"><i class="fas fa-print"></i> Print</a>
                            <a href="{{ route('student-payment') }}">
                                <button type="button" class="btn btn-success float-right"><i class="far fa-credit-card"></i> Paypal payment</button>
                            </a>
                            <a href="{{ route('stripe.index') }}">
                                <button type="button" class="btn btn-primary float-right" style="margin-right: 5px;"><i class="far fa-credit-card"></i> Visa payment</button>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- /.invoice -->
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
@endsection
@push('scripts')
    {{--    <script src="{{ asset('js/admin/products/create.js') }}"></script>--}}
@endpush
