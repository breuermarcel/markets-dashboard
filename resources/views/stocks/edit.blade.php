@extends("markets-dashboard::main")

@section("content")
    <form class="row g-3" method="POST" action="{{ route('stocks.update', $stock) }}">
        @csrf
        @method('PUT')

        <div class="col-md-4 form-floating">
            <input value="{{ $stock->symbol }}" name="symbol" type="text" class="form-control @error('symbol') is-invalid @enderror" id="symbol" readonly>
            <label for="symbol" class="ps-4">{{ trans('Symbol') }}</label>

            @error("symbol")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-4 form-floating">
            <input value="{{ $stock->wkn }}" name="wkn" type="text" class="form-control @error('wkn') is-invalid @enderror" id="wkn">
            <label for="wkn" class="ps-4">{{ trans('WKN') }}</label>

            @error("wkn")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-4 form-floating">
            <input value="{{ $stock->isin }}" name="isin" type="text" class="form-control @error('isin') is-invalid @enderror" id="isin">
            <label for="isin" class="ps-4">{{ trans('ISIN') }}</label>

            @error("isin")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-md-12 form-floating">
            <input value="{{ $stock->name }}" name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name" required>
            <label for="name" class="ps-4">{{ trans('Name') }}</label>

            @error("name")
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Aktualisieren') }}</button>
        </div>
    </form>
@endsection
