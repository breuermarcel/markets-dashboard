<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" translate="no">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex"/>
    <meta name="google" content="notranslate">

    <title>Finance-Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;700&display=swap" rel="stylesheet">

    <link href="{{ asset('vendor/finance-dashboard/css/main.css') }}" rel="stylesheet">
</head>
<body id="bm__body">
    @include('finance-dashboard::components.navbar')
    <div class="container-fluid">
        <div class="row">
            @include('finance-dashboard::components.sidebar')
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 my-sm-3 my-2" id="bm__main">
                @include('finance-dashboard::components.breadcrumb')

                <div id="bm__searchResultsContainer"></div>

                @include("finance-dashboard::components.status")

                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('vendor/finance-dashboard/js/main.js') }}"></script>
</body>
</html>
