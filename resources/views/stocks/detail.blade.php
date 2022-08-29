@extends("finance-dashboard::main")

@section("content")
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" async></script>

    <div class="row g-4" data-masonry='{"percentPosition": true }' id="stock-detail-container" data-symbol="{{ $stock->symbol }}">
        @include('finance-dashboard::stocks.components.graph')
        @include('finance-dashboard::stocks.components.informations')
    </div>
@endsection
