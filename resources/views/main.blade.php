<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Finance-Dashboard</title>

    <link href="{{ asset('css/bm_fd.css') }}" rel="stylesheet">
    <script src="{{ asset('js/bm_fd.js') }}"></script>
</head>
<body>
    @include('finance-dashboard::components.navbar')

    <main class="bm__fd">
        @include('finance-dashboard::components.sidebar')
        <main class="bm__main">
            @include('finance-dashboard::components.breadcrumb')
                <div id="bm__searchResultsContainer"></div>
            @include('finance-dashboard::components.errors')
            @yield('content')
        </main>
    </main>
</body>
</html>
