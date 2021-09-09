<form method="POST" action="{{ route('fetchFacebook') }}">
    @csrf

    <div class="form-group row mb-0">
        <div class="col-md-8 offset-md-4">
            <button type="submit" class="btn btn-primary">
                Get code
            </button>
        </div>
    </div>
</form>
