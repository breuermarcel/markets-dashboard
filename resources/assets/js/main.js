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
