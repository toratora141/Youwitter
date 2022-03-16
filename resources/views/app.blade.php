<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale())}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css">
<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" class="bi bi-chevron-right" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/></svg>
    <head>

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-widthm initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}">
        {{-- <title>{{ config('app.name', 'Youwitter') }}</title>
         --}}
         <title>Youwitter</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
    </head>
    <body>
        <div id="app">
            <header-component></header-component>
            <router-view></router-view>
        </div>
        <script src="{{ mix('/js/app.js') }}" defer></script>
    </body>
</html>
