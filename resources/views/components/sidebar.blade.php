<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky">
        <h6 class="sidebar-heading px-3 text-muted mt-sm-4">{{ trans('Basics') }}</h6>
        <ul class="nav flex-column mb-5">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stocks.index') }}">{{ trans('Stocks') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stocks.analysis.index') }}">{{ trans('Analysis') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('wiki.index') }}">{{ trans('Knowledge Base') }}</a>
            </li>
        </ul>

        <h6 class="sidebar-heading px-3 text-muted">{{ trans('Settings') }}</h6>
        <ul class="nav flex-column mb-5">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('stocks.analysis.settings.index') }}">{{ trans('Analytics Criteria') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('profile.edit', auth()->id()) }}">{{ trans('Profile') }}</a>
            </li>
            @if(auth()->user()->isAdmin())
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('users.index') }}">{{ trans('Users') }}</a>
                </li>
            @endif
        </ul>

        @if(auth()->user()->isAdmin())
            <h6 class="sidebar-heading px-3 text-muted">{{ trans('Database') }}</h6>
            <ul class="nav flex-column mb-5">
                <li class="nav-item">
                    <div class="d-flex align-items-center">
                        <a class="nav-link" href="{{ route('stocks.analysis.call-api.create') }}">{{ trans('Renew Statistic') }}</a>
                    </div>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('stocks.import.index') }}">{{ trans('Import Stocks') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="{{ route('maintenance.logs.index') }}">{{ trans('Logs') }}</a>
                </li>
            </ul>
        @endif
    </div>
</nav>
