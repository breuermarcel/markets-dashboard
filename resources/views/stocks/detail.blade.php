@extends("finance-dashboard::main")

@section("content")
    <script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" async></script>

    <div class="row g-4" id="bm__stock-detail-container">

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <script>
        function loadStockInformation(data) {
            $.get({
                type: 'GET',
                url: '{{ route("api.show", $stock) }}',
                data: data,
                success: function (response) {
                    $(data["container"]).remove();
                    $('#bm__stock-detail-container').prepend(response);

                    let bm__msnry = new Masonry(document.getElementById("bm__stock-detail-container"), {
                        percentPosition: false
                    });

                    bm__msnry.layout();
                }
            });
        }

        $(document).ready(function() {
            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'recommendations',
                'container': '#bm__stock-detail-container #bm__recommendations-container',
                'html': 'true'
            });

            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'balance_sheet',
                'container': '#bm__stock-detail-container #bm__balance_sheet-container',
                'html': 'true'
            });

            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'cashflow',
                'container': '#bm__stock-detail-container #bm__cashflow-container',
                'html': 'true'
            });

            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'income',
                'container': '#bm__stock-detail-container #bm__income-container',
                'html': 'true'
            });

            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'esg',
                'container': '#bm__stock-detail-container #bm__esg-container',
                'html': 'true'
            });

            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'profile',
                'container': '#bm__stock-detail-container #bm__profile-container',
                'html': 'true'
            });

            loadStockInformation({
                'symbol': '{{ $stock->symbol }}',
                'module': 'chart',
                'container': '#bm__stock-detail-container #bm__chart-container'
            });
        });
    </script>
@endsection
