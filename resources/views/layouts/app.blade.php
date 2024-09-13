<!doctype html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>@yield('title', 'IT Ops Today')</title>
    <meta name="description" content="" />
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="https://iconape.com/wp-content/png_logo_vector/pt-kaltim-prima-coal-logo.png" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/remixicon/remixicon.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <!-- Page CSS -->
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    @stack('head-scripts')
</head>

<body>
    @yield('content')
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            @auth
            @if(Auth::user()->role == 'Admin')
            @include('components.sidebar')
            @endif
            @endauth
            <div class="layout-page">
                <div class="content-wrapper">
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
<!-- Core JS -->
<script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}" defer></script>
<script src="{{ asset('assets/vendor/libs/popper/popper.js') }}" defer></script>
<script src="{{ asset('assets/vendor/js/bootstrap.js') }}" defer></script>
<script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}" defer></script>
<script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}" defer></script>
<script src="{{ asset('assets/vendor/js/menu.js') }}" defer></script>
<!-- Vendors JS -->
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}" defer></script>
<!-- Main JS -->
<script src="{{ asset('assets/js/main.js') }}" defer></script>
<!-- Page JS -->
<script src="{{ asset('assets/js/dashboards-analytics.js') }}" defer></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
    @stack('scripts')
</body>

</html>