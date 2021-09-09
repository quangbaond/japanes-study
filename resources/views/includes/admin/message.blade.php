@if ($message = Session::get('error'))
    <section class="content-header">
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-ban"></i>
            {{ $message }}
        </div>
    </section>
@endif

@if ($message = Session::get('info'))
    <section class="content-header">
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-info"></i>
            {{ $message }}
        </div>
    </section>
@endif

@if ($message = Session::get('warning'))
    <section class="content-header">
        <div class="alert alert-warning alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-warning"></i>
            {{ $message }}
        </div>
    </section>
@endif

@if ($message = Session::get('success'))
    <section class="content-header">
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <i class="icon fa fa-check"></i>
            {{ $message }}
        </div>
    </section>
@endif


