<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<script>
$('#bm__searchInput').on('keyup', function (event) {
    if (event.keyCode === 13) {
        $.get({
            type: 'GET',
            url: '/finance-dashboard/search',
            data: {
                'sword': this.value
            },
            success: function (response) {
                let container = $('#bm__searchResultsContainer');
                container.empty().append(response);

                let searchResultsContainer = new bootstrap.Modal(document.getElementById('bm__searchResults'));
                searchResultsContainer.toggle();
            },
        });
    }
});
</script>
