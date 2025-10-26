<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - Carré Premium</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- TailwindCSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#9333EA',
                        secondary: '#D4AF37',
                        dark: '#1F2937',
                    },
                    fontFamily: {
                        montserrat: ['Montserrat', 'sans-serif'],
                        poppins: ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                        'bounce-slow': 'bounce 3s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-100%)' },
                            '100%': { transform: 'translateX(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Montserrat', sans-serif;
        }

        /* Sidebar Styles */
        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(135deg, #9333EA 0%, #7C3AED 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        .sidebar-link.active::before {
            transform: scaleY(1);
        }
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.1) 0%, rgba(124, 58, 237, 0.1) 100%);
            color: #9333EA;
            font-weight: 600;
        }
        .sidebar-link:hover {
            background: rgba(147, 51, 234, 0.05);
            transform: translateX(4px);
        }
        .sidebar-link.active:hover {
            background: linear-gradient(135deg, rgba(147, 51, 234, 0.15) 0%, rgba(124, 58, 237, 0.15) 100%);
        }

        /* Glassmorphism Effect */
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #9333EA;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #7C3AED;
        }

        /* Card Hover Effect */
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Gradient Text */
        .gradient-text {
            background: linear-gradient(135deg, #9333EA 0%, #D4AF37 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Mobile Sidebar */
        @media (max-width: 768px) {
            #sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
            }
            #sidebar.show {
                transform: translateX(0);
            }
        }

        /* Loading Animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(147, 51, 234, 0.3);
            border-radius: 50%;
            border-top-color: #9333EA;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Notification Badge Pulse */
        .notification-badge {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        /* Smooth Page Transitions */
        .page-transition {
            animation: fadeIn 0.3s ease-in-out;
        }

        /* Stats Card Gradient */
        .stats-card {
            background: linear-gradient(135deg, var(--tw-gradient-stops));
            position: relative;
            overflow: hidden;
        }
        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            animation: pulse-slow 3s ease-in-out infinite;
        }

        /* Dropdown Animation */
        .dropdown-menu {
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 glass shadow-2xl flex-shrink-0 flex flex-col transition-all duration-300 ease-in-out">
            <!-- Logo -->
            <div class="h-16 flex items-center justify-center border-b border-gray-200 bg-gradient-to-r from-primary to-purple-600 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-pulse-slow"></div>
                <div class="relative z-10 flex items-center">
                    <i class="fas fa-crown text-secondary text-2xl mr-2"></i>
                    <h1 class="text-xl font-bold text-white font-montserrat">Carré Premium</h1>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 overflow-y-auto py-4 px-3">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-700' }}">
                    <i class="fas fa-chart-line w-5 text-lg"></i>
                    <span class="ml-3 font-medium">Dashboard</span>
                </a>

                <div class="mt-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                        <i class="fas fa-grip-horizontal mr-2"></i>
                        Gestion
                    </p>

                    <a href="{{ route('admin.users.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-users w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Utilisateurs</span>
                        <span class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">0</span>
                    </a>

                    <a href="{{ route('admin.bookings.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-ticket-alt w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Réservations</span>
                        <span class="ml-auto bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">0</span>
                    </a>
                </div>

                <div class="mt-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                        <i class="fas fa-box mr-2"></i>
                        Produits
                    </p>

                    <a href="{{ route('admin.flights.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.flights.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-plane w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Vols</span>
                    </a>

                    <a href="{{ route('admin.events.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.events.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-calendar-alt w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Événements</span>
                    </a>

                    <a href="{{ route('admin.packages.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.packages.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-suitcase w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Packages</span>
                    </a>

                    <a href="{{ route('admin.categories.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-folder w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Catégories</span>
                    </a>
                </div>

                <div class="mt-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                        <i class="fas fa-palette mr-2"></i>
                        Contenu
                    </p>

                    <a href="{{ route('admin.carousels.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.carousels.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-images w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Carrousels</span>
                    </a>
                </div>

                <div class="mt-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                        <i class="fas fa-store mr-2"></i>
                        Marketing
                    </p>

                    <a href="{{ route('admin.reviews.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-star w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Avis Clients</span>
                        <span class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">0</span>
                    </a>

                    <a href="{{ route('admin.promo-codes.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.promo-codes.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-tags w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Codes Promo</span>
                        <span class="ml-auto bg-pink-100 text-pink-800 text-xs px-2 py-1 rounded-full">New</span>
                    </a>
                </div>

                <div class="mt-6">
                    <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                        <i class="fas fa-cogs mr-2"></i>
                        Configuration
                    </p>

                    <a href="{{ route('admin.settings.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-sliders-h w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Paramètres</span>
                    </a>

                    <a href="{{ route('admin.pricing-rules.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.pricing-rules.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-percentage w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Règles de Prix</span>
                    </a>

                    <a href="{{ route('admin.api-config.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.api-config.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-plug w-5 text-lg"></i>
                        <span class="ml-3 font-medium">APIs</span>
                    </a>

                    <a href="{{ route('admin.payment-gateways.index') }}" class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.payment-gateways.*') ? 'active' : 'text-gray-700' }}">
                        <i class="fas fa-credit-card w-5 text-lg"></i>
                        <span class="ml-3 font-medium">Paiements</span>
                    </a>
                </div>
            </nav>

            <!-- User Info -->
            <div class="border-t border-gray-200 p-4 bg-gradient-to-r from-purple-50 to-pink-50">
                <a href="{{ route('admin.profile') }}" class="flex items-center hover:bg-white p-3 rounded-lg transition-all duration-300 group">
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-primary to-purple-600 flex items-center justify-center text-white font-bold shadow-lg group-hover:shadow-xl transition-shadow">
                            {{ substr(auth('admin')->user()->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
                    </div>
                    <div class="ml-3 flex-1">
                        <p class="text-sm font-semibold text-gray-800">{{ auth('admin')->user()->name }}</p>
                        <p class="text-xs text-gray-500">{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}</p>
                    </div>
                    <i class="fas fa-chevron-right text-gray-400 group-hover:text-primary transition-colors"></i>
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            @if(!request()->routeIs('admin.dashboard'))
            <!-- Top Bar -->
            <header class="h-16 glass shadow-lg flex items-center justify-between px-4 md:px-6 relative z-30">
                <div class="flex items-center">
                    <button id="sidebar-toggle" class="md:hidden text-gray-600 hover:text-primary mr-4 p-2 hover:bg-purple-50 rounded-lg transition-all">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div>
                        <h2 class="text-lg md:text-xl font-bold text-gray-800 font-montserrat">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-xs text-gray-500 hidden sm:block">{{ now()->format('l, d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center space-x-2 md:space-x-4">
                    <!-- Search -->
                    <button class="hidden md:flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        <i class="fas fa-search text-gray-600 mr-2"></i>
                        <span class="text-sm text-gray-600">Rechercher...</span>
                    </button>

                    <!-- Notifications -->
                    <a href="{{ route('admin.notifications') }}" class="relative text-gray-600 hover:text-primary p-2 hover:bg-purple-50 rounded-lg transition-all">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="notification-badge absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center font-bold shadow-lg">3</span>
                    </a>

                    <!-- Profile -->
                    <a href="{{ route('admin.profile') }}" class="hidden sm:block text-gray-600 hover:text-primary p-2 hover:bg-purple-50 rounded-lg transition-all">
                        <i class="fas fa-user-circle text-xl"></i>
                    </a>

                    <!-- Logout -->
                    <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-3 md:px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="hidden sm:inline text-sm font-medium">Déconnexion</span>
                        </button>
                    </form>
                </div>
            </header>
            @endif

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-4 md:p-6 page-transition">
                @if(session('success'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-2xl mr-3"></i>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-md animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            <footer class="glass border-t border-gray-200 px-6 py-3 text-center text-sm text-gray-600">
                <p>&copy; {{ date('Y') }} <span class="gradient-text font-bold">Carré Premium</span>. Tous droits réservés.</p>
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('hidden');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('show');
                sidebarOverlay.classList.add('hidden');
            });
        }

        // Auto-hide alerts after 5 seconds
        setTimeout(() => {
            const alerts = document.querySelectorAll('[class*="animate-fade-in"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s ease-out';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);

        // Add loading state to buttons
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                if (submitBtn && !submitBtn.disabled) {
                    submitBtn.disabled = true;
                    const originalText = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="loading mr-2"></span> Chargement...';
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 3000);
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
