<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- SEO Meta Tags (can be overridden per page via Inertia Head) --}}
    <meta name="robots" content="index,follow">
    <meta name="description" content="{{ config('app.meta_description', config('app.name', 'Laravel')) }}">
    <meta name="author" content="{{ config('app.name', 'Laravel') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Favicons --}}
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="icon" type="image/png" href="/favicon.png">
    <meta name="theme-color" content="#0f172a">

    {{-- Open Graph Meta --}}
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ config('app.name', 'Laravel') }}">
    <meta property="og:title" content="{{ config('app.name', 'Laravel') }}">
    <meta property="og:description" content="{{ config('app.meta_description', config('app.name', 'Laravel')) }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset(config('app.og_image', '/og-image.png')) }}">

    {{-- Twitter Card Meta --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ config('app.name', 'Laravel') }}">
    <meta name="twitter:description" content="{{ config('app.meta_description', config('app.name', 'Laravel')) }}">
    <meta name="twitter:image" content="{{ asset(config('app.og_image', '/og-image.png')) }}">

    {{-- Page Title (overridable via Inertia) --}}
    <title inertia>{{ config('app.name', 'Laravel') }}</title>
    
    {{-- Inertia Head for per-page overrides --}}
    @inertiaHead
    
    {{-- Vite Assets --}}
    @viteReactRefresh
    @vite('resources/js/app.jsx')
</head>
<body class="antialiased">
    @inertia
</body>
</html>
