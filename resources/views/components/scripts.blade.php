<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    function callSearch(event) {
        if (event.keyCode === 13) {
            $.get({
                type: 'GET',
                url: '{{ route(config("markets-dashboard.routing.as") . "search") }}',
                data: {
                    'sword': event.target.value
                },
                success: function (response) {
                    $('#bm__searchResultsContainer').empty().append(response);

                    let searchResultsContainer = new bootstrap.Modal(document.getElementById('bm__searchResults'));
                    searchResultsContainer.toggle();
                },
            });
        }
    }

    function storeStockInformation(symbol) {
        $.get({
            type: 'GET',
            url: '{{ route(config("markets-dashboard.routing.as") . "api.store") }}',
            data: {
                "_token": "{{ csrf_token() }}",
                symbol: symbol
            }
        }).done(function (response) {

        });
    }
</script>
