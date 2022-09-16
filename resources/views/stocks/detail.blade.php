@extends("finance-dashboard::main")

@section("content")
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" async></script>

    <div class="row g-4" id="bm__stock-detail-container">

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        $(document).ready(function() {
            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'recommendations',
                'container': '#bm__stock-detail-container #bm__recommendations-container',
                'html': 'true'
            });

            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'balance_sheet',
                'container': '#bm__stock-detail-container #bm__balance_sheet-container',
                'html': 'true'
            });

            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'cashflow',
                'container': '#bm__stock-detail-container #bm__cashflow-container',
                'html': 'true'
            });

            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'income',
                'container': '#bm__stock-detail-container #bm__income-container',
                'html': 'true'
            });

            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'esg',
                'container': '#bm__stock-detail-container #bm__esg-container',
                'html': 'true'
            });

            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'profile',
                'container': '#bm__stock-detail-container #bm__profile-container',
                'html': 'true'
            });

            callAPI({
                'symbol': '{{ $stock->symbol }}',
                'module': 'chart',
                'container': '#bm__stock-detail-container #bm__chart-container'
            });
        });
    </script>
@endsection
