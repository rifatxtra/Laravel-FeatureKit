<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', \App\Models\Setting::get('app_name', config('app.name')))</title>

    <link rel="icon" type="image/x-icon" href="{{ \App\Models\Setting::get('app_favicon', asset('favicon.ico')) }}">

    <meta name="description" content="@yield('description', 'Default description')">
    <meta name="keywords" content="@yield('keywords', 'laravel, starter kit, feature-driven, web development')">
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', \App\Models\Setting::get('app_name', config('app.name')))">
    <meta property="og:description" content="@yield('og_description', 'Default description')">
    <meta property="og:image" content="@yield('og_image', asset('default.jpg'))">

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="{{ url()->current() }}">
    <meta name="twitter:title" content="@yield('og_title', \App\Models\Setting::get('app_name', config('app.name')))">
    <meta name="twitter:description" content="@yield('og_description', 'Default description')">
    <meta name="twitter:image" content="@yield('og_image', asset('default.jpg'))">
    <meta name="twitter:site" content="@yield('twitter_site', '@rifatxtra')">
    <meta name="twitter:creator" content="@yield('twitter_creator', '@rifatxtra')">

    @vite(['resources/css/app.css', 'resources/js/app.jsx'])
</head>
<body class="antialiased min-h-screen flex flex-col bg-base">
    @include('layout.header')

    <main class="flex-grow">
        @yield('content')
    </main>

    @include('layout.footer')

    @include('pages.components.ui.loading-spinner')

    <script>
        window.addEventListener('submit', function() {
            const spinner = document.getElementById('loading-spinner');
            if (spinner) spinner.classList.remove('hidden');
        });
    </script>
</body>
</html>
