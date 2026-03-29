<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

    {{-- SEO Meta Tags --}}
    <meta name="description" content="{{ $meta_description ?? 'Carré Premium - Conciergerie privée spécialisée dans les voyages de luxe, événements sportifs et culturels VIP, vols privés et packages touristiques exclusifs en Côte d\'Ivoire.' }}">
    <meta name="keywords" content="{{ $meta_keywords ?? 'conciergerie privée, voyages luxe, événements VIP, vols privés, packages touristiques, Côte d\'Ivoire, Abidjan, sports, culture, hélicoptère, jet privé' }}">
    <meta name="author" content="Carré Premium">
    <meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url()->current() }}">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="{{ $og_type ?? 'website' }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $og_title ?? ($title ?? config('app.name', 'Laravel')) }}">
    <meta property="og:description" content="{{ $og_description ?? 'Carré Premium - Conciergerie privée spécialisée dans les voyages de luxe, événements sportifs et culturels VIP, vols privés et packages touristiques exclusifs en Côte d\'Ivoire.' }}">
    <meta property="og:image" content="{{ $og_image ?? asset('logos/LOGO CARRE PREMIUM-Conciergerie privée.jpg') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:site_name" content="Carré Premium">
    <meta property="og:locale" content="{{ str_replace('_', '-', app()->getLocale()) }}">

    {{-- Twitter --}}
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="{{ $og_title ?? ($title ?? config('app.name', 'Laravel')) }}">
    <meta property="twitter:description" content="{{ $og_description ?? 'Carré Premium - Conciergerie privée spécialisée dans les voyages de luxe, événements sportifs et culturels VIP, vols privés et packages touristiques exclusifs en Côte d\'Ivoire.' }}">
    <meta property="twitter:image" content="{{ $og_image ?? asset('logos/LOGO CARRE PREMIUM-Conciergerie privée.jpg') }}">

    {{-- Additional SEO --}}
    <meta name="theme-color" content="#2A163D">
    <meta name="msapplication-TileColor" content="#2A163D">
    <link rel="alternate" hreflang="fr" href="{{ url()->current() }}">
    <link rel="alternate" hreflang="en" href="{{ url()->current() }}?lang=en">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:400,500,600,700,800,900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Theme CSS - MUST be loaded first -->
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <link rel="stylesheet" href="{{ asset('css/separate-tickets.css') }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon-16x16.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon-32x32.png') }}">

    <!-- Theme Initialization Script - Must be in head to prevent flash -->
    <script>
        // Initialize theme IMMEDIATELY to prevent flash
        (function () {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>

    @stack('styles')

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="cp-site-body antialiased">

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

    <main class="pt-[5rem] lg:pt-[5.75rem]">
    @yield('content')
</main>

     @include('layouts.footer')

                @if(env('OPENAI_API_KEY'))
                    @include('components.chatbot-widget')
                @endif
    
    <!-- Theme JavaScript -->
    <script src="{{ asset('js/theme.js') }}"></script>
           <script src="//unpkg.com/alpinejs" defer></script>
    @stack('scripts')   

    @yield('scripts')
</body>
</html> 
