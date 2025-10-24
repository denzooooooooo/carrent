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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gray-100 antialiased">

    @php
        $isAuthenticated = auth()->check(); 
        $user = auth()->user();
        $cartItemsCount = session('cart_count', 0);
        $currentLanguage = session('language', 'fr');
        $currentCurrency = session('currency', 'XOF');
    @endphp

    @include('layouts.header', [
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

    <script src="//unpkg.com/alpinejs" defer></script>
</body>
</html>
