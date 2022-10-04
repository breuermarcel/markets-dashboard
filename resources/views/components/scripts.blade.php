<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
    function callSearch(event) {
        if (event.keyCode === 13) {
            $.get({
                type: 'GET',
                url: '/finance-dashboard/search/',
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

    function callAPI(data) {
        $.get({
            type: 'GET',
            url: '/finance-dashboard/api/',
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
</script>
