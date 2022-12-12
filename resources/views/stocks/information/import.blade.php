@extends("markets-dashboard::main")

@section("content")
    <script>
        const stocks = {!! $stocks !!};

        $(document).ready(function($) {
            $.each(stocks, function(i, el) {
                storeStockInformation(el["symbol"]);
            });
        });
    </script>
@endsection
