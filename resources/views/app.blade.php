<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name'))</title>

    <meta name="description" content="@yield('description', 'Default description')">
    <meta name="keywords" content="@yield('keywords', 'courses, learning')">

    {{-- Open Graph --}}
    <meta property="og:title" content="@yield('og_title', config('app.name'))">
    <meta property="og:description" content="@yield('og_description', 'Default description')">
    <meta property="og:image" content="@yield('og_image', asset('default.jpg'))">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="@yield('og_title', config('app.name'))">
    <meta name="twitter:description" content="@yield('og_description', 'Default description')">
    <meta name="twitter:image" content="@yield('og_image', asset('default.jpg'))">

    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">

    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
    @inertiaHead
</head>
<body class="bg-background text-foreground antialiased font-sans">
    @inertia
</body>
</html>