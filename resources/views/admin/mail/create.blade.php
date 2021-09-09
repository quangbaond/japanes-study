@extends('layouts.admin.app')

@section('stylesheets')

@endsection

@section('content')
    <section class="content">
        <div class="container-fluid">
            <form action="{{ route('mail.send') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-primary">Send mail</button>
            </form>
        </div>
    </section>
@endsection
@push('scripts')

@endpush
