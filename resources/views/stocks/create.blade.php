@extends("markets-dashboard::main")

@section("content")
    <form class="row g-3" method="POST" action="{{ route(config("markets-dashboard.routing.as") .'stocks.store') }}">
        @method("POST")
        @csrf
        <div class="col-md-4 form-floating">
            <input name="symbol" type="text" class="form-control @error('symbol') is-invalid @enderror" id="symbol"
                   placeholder="{{ trans('TSLA') }}" value="{{ old('symbol') }}" required
                   autofocus>
            <label for="symbol" class="ps-4">{{ trans('Symbol') }}</label>

            @error("symbol")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-floating">
            <input name="wkn" type="text" class="form-control @error('wkn') is-invalid @enderror" id="wkn"
                   placeholder="{{ trans('A1CX3T') }}" value="{{ old('wkn') }}">
            <label for="wkn" class="ps-4">{{ trans('WKN') }}</label>

            @error("wkn")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-4 form-floating">
            <input name="isin" type="text" class="form-control @error('isin') is-invalid @enderror" id="isin"
                   placeholder="{{ trans('US88160R1014') }}" value="{{ old('isin') }}">
            <label for="isin" class="ps-4">{{ trans('ISIN') }}</label>

            @error("isin")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-md-12 form-floating">
            <input name="name" type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                   placeholder="{{ trans('Tesla Inc.') }}" value="{{ old('name') }}">
            <label for="name" class="ps-4">{{ trans('Name') }}</label>

            @error("name")
            <div class="invalid-feedback">
                {{ $message }}
            </div>
            @enderror
        </div>

        <div class="col-12">
            <button class="btn btn-outline-secondary">{{ trans('Create') }}</button>
        </div>
    </form>
@endsection
