@extends("finance-dashboard::main")

@section("content")

<form class="row g-3" action="{{ route('stocks.do_import') }}" method="POST" enctype="multipart/form-data">
    @method("POST")
    @csrf

    <div class="col-12">
    <label for="file" class="form-label">Datei</label>
    <input type="file" name="file" id="file" class="form-control" aria-describedby="fileHelpBlock">
    <div id="fileHelpBlock" class="form-text">
        Erlaubte Dateiformate: CSV.
    </div>
    </div>

<div class="col-12">
    <button type="submit" class="btn btn-primary">Hochladen</button>
</div>

</form>

@endsection
