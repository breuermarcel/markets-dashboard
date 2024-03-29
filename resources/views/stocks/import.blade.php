@extends("markets-dashboard::main")

@section("content")

    <form class="row g-3" action="{{ route(config("markets-dashboard.routing.as") .'stocks.import.store') }}"
          method="POST" enctype="multipart/form-data">
        @method("POST")
        @csrf

        <div class="col-12">
            <label for="file" class="form-label">{{ trans("Datei") }}</label>
            <input type="file" name="file" id="file" class="form-control @error('file') is-invalid @enderror"
                   aria-describedby="fileHelpBlock">
            <div id="fileHelpBlock" class="form-text">
                {{ trans("Erlaubte Dateiformate: CSV.") }}
            </div>
            @error("file")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-12">
            <button type="submit" class="btn btn-outline-secondary">{{ trans("Hochladen") }}</button>
        </div>

    </form>

@endsection
