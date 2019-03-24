<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{!! config('app.name') !!}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed sidebar-lg-show">
        <div id="app">
            @include('layouts.header')
            <div class="app-body">
                @include('layouts.sidebar')
                <main class="main">
                    <div class="container-fluid">
                        <div class="animated fadeIn">
                            @yield('content')
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>