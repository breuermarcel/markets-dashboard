<nav class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3"
       href="{{ route(config("markets-dashboard.routing.as") . 'stocks.index') }}">
        Markets-Dashboard
    </a>

    <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <input id="bm__searchInput" onkeypress="callSearch(event)" class="form-control form-control-dark w-100" type="text"
           placeholder="{{ trans('Search') }}" aria-label="Search">
</nav>
