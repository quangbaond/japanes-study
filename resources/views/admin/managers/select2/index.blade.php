@extends('layouts.admin.app')

@section('breadcrumb')
    {{ Breadcrumbs::render('create_plan') }}
@endsection

@section('stylesheets')

@endsection

@section('title_screen', '追加')

@section('content')
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card">
                <div class="card-header">
                    <h4>Ajax select2</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <select class="itemName form-control" multiple="multiple" name="itemName"></select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('.itemName').select2({
                placeholder: 'Hãy nhập tên user để tìm kiếm',
                ajax: {
                    url: '{{ route('select2.data-ajax') }}',
                    dataType: 'json',
                    delay: 100,
                    processResults: function (data) {
                        return {
                            results:  $.map(data, function (item) {
                                return {
                                    text: item.email,
                                    id: item.id
                                }
                            })
                        };
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush




