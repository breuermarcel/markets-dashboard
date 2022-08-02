<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('stocks.index') }}">
        <img src="{{ asset('images/Logo.png') }}" title="Consulting-Breuer" alt="Logo"/>
    </a>

    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <input id="searchInput" class="form-control form-control-dark w-100" type="text" placeholder="{{ trans('Search') }}" aria-label="Search">

    <script>
        $('#searchInput').on('keyup', function (event) {
            if (event.keyCode === 13) {
                $.get({
                    type: 'GET',
                    url: '/search',
                    data: {
                        'sword': this.value
                    },
                    success: function (response) {
                        let container = $('#bm__searchResultsContainer');
                        container.empty().append(response);

                        let searchResultsContainer = new bootstrap.Modal(document.getElementById('searchResults'));
                        searchResultsContainer.toggle();
                    },
                });
            }
        });
    </script>
</nav>
