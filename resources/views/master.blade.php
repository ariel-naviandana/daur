<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'My App')</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    @vite(['resources/js/app.ts', 'resources/css/app.css'])
</head>
<body>
<div id="app" data-page="@yield('page')">
    @yield('content')
</div>
</body>
</html>
