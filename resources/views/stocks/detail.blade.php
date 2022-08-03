@extends("finance-dashboard::main")

@section("content")
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" async></script>

    <div class="row g-4" data-masonry='{"percentPosition": true }'>
        @if (!empty($history))
            @include('finance-dashboard::stocks.components.graph')
        @endif

        @if (!empty($information))
            @include('finance-dashboard::stocks.components.informations')
        @endif
    </div>
@endsection
