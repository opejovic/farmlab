<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'FARMLAB') }} | @yield('pageTitle')</title>

    <link rel="stylesheet" href="{!! asset('css/vendor.css') !!}" />
    <link rel="stylesheet" href="{!! asset('css/app.css') !!}" />

</head>
<body class=" {{ auth()->guest() ? 'gray-bg' : '' }}">

    @if(auth()->guest())
        <!-- Log in view -->
        @yield('content')

    @else
    <!-- Wrapper-->
    <div id="wrapper">
        
        <!-- Navigation -->
        @include ('layouts.navigation', ['user' => auth()->user()])

        <!-- Page wraper -->
        <div id="page-wrapper" class="gray-bg">

            <!-- Page wrapper -->
            @include('layouts.topnavbar')

            <!-- Main view  -->
            @yield('content')
            
            <!-- Footer -->
            @include('layouts.footer')

        </div>
        <!-- End page wrapper-->

    </div>
    <!-- End wrapper-->
    @endif

    <script src="{!! asset('js/app.js') !!}" type="text/javascript"></script>
    @include('layouts.flash')

    @yield('scripts')
</body>
</html>
