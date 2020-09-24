<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ secure_asset('css/app.css') }}" type="text/css">
        <link rel="shortcut icon" href="{{secure_asset('favicon.ico')}}" type="image/x-icon">

        <!-- Scripts -->
        <script type="text/javascript">
            window.csrf_token = "{{ csrf_token() }}";
        </script>
    </head>
    <body>
        <div id="app">

        </div>


        <!-- Scripts -->
        <script type="text/javascript" src="{{ secure_asset('/js/manifest.js') }}"></script>
        <script type="text/javascript" src="{{ secure_asset('/js/vendor.js') }}"></script>
        <script type="text/javascript" src="{{ secure_asset('js/app.js') }}"></script>
    </body>
</html>
