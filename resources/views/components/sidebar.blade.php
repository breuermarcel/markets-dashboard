<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky">
        <h6 class="sidebar-heading px-3 text-muted mt-sm-4">{{ trans('Basics') }}</h6>
        <ul class="nav flex-column mb-5">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stocks.index') }}">{{ trans('Stocks') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stocks.analysis') }}">{{ trans('Analysis') }}</a>
            </li>
        </ul>

        <h6 class="sidebar-heading px-3 text-muted">{{ trans('Settings') }}</h6>
        <ul class="nav flex-column mb-5">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('analysis.index') }}">{{ trans('Analytics Criteria') }}</a>
            </li>
        </ul>
    </div>
</nav>
