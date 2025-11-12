<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKx3nfrF0gY3jA1M05j1w5oA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Theme CSS - MUST be loaded first -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-32x32.png') }}">

    <!-- Theme Initialization Script - Must be in head to prevent flash -->
    <script>
        // Initialize theme IMMEDIATELY to prevent flash
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-100 dark:bg-gray-900 antialiased">

    @php
        $isAuthenticated = auth()->check();
        $user = auth()->user();
        $cartItemsCount = session('cart_count', 0);
        $currentLanguage = session('language', 'fr');
        $currentCurrency = session('currency', 'XOF');
    @endphp

    @include('layouts.header-new', [
        'isAuthenticated' => $isAuthenticated,
        'user' => $user,
        'cartItemsCount' => $cartItemsCount,
        'currentLanguage' => $currentLanguage,
        'currentCurrency' => $currentCurrency
    ])

    <main class="pt-20">
        @yield('content')
    </main>

    @include('layouts.footer')


    @include('components.chatbot-widget')
    
    <!-- Theme JavaScript -->
    <script src="{{ asset('js/theme.js') }}"></script>
            
    <script src="//unpkg.com/alpinejs" defer></script>
    @yield('scripts')
</body>
</html> 
