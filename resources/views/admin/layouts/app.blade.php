
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
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Sora:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- TailwindCSS (fallback) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5B21B6',
                        secondary: '#C88A2A',
                        dark: '#1F2430',
                    },
                    fontFamily: {
                        montserrat: ['Sora', 'sans-serif'],
                        poppins: ['Manrope', 'sans-serif'],
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
        :root {
            --admin-bg: #f4efe8;
            --admin-bg-alt: #fbf8f4;
            --admin-surface: rgba(255, 255, 255, 0.92);
            --admin-surface-strong: #ffffff;
            --admin-surface-muted: #f8f2ea;
            --admin-line: #eadfce;
            --admin-text: #201d25;
            --admin-muted: #6e6677;
            --admin-brand: #5b21b6;
            --admin-brand-strong: #43158e;
            --admin-brand-soft: #efe7fb;
            --admin-accent: #c88a2a;
            --admin-accent-soft: #fbf0dc;
            --admin-success: #1f7a5b;
            --admin-success-soft: #e7f5ef;
            --admin-warning: #b86a16;
            --admin-warning-soft: #fdf0df;
            --admin-danger: #b42318;
            --admin-danger-soft: #fde8e6;
            --admin-shadow-soft: 0 12px 35px rgba(38, 24, 59, 0.08);
            --admin-shadow-strong: 0 22px 60px rgba(38, 24, 59, 0.12);
        }

        body {
            font-family: 'Manrope', sans-serif;
            color: var(--admin-text);
            background:
                radial-gradient(circle at top left, rgba(91, 33, 182, 0.12), transparent 34%),
                radial-gradient(circle at top right, rgba(200, 138, 42, 0.12), transparent 30%),
                linear-gradient(180deg, var(--admin-bg-alt) 0%, var(--admin-bg) 100%);
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-family: 'Sora', sans-serif;
            letter-spacing: -0.02em;
        }

        .admin-app {
            min-height: 100vh;
        }

        .admin-main {
            position: relative;
        }

        .admin-main::before {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            background:
                linear-gradient(135deg, rgba(255, 255, 255, 0.38), transparent 32%),
                radial-gradient(circle at bottom right, rgba(91, 33, 182, 0.06), transparent 28%);
        }

        .admin-main > * {
            position: relative;
            z-index: 1;
        }

        .admin-app main main {
            padding: 0 !important;
            overflow: visible !important;
            background: transparent !important;
        }

        .admin-page-header {
            display: flex;
            flex-wrap: wrap;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1.75rem;
            border: 1px solid var(--admin-line);
            border-radius: 1.5rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.95), rgba(248, 242, 234, 0.92));
            box-shadow: var(--admin-shadow-soft);
        }

        .admin-panel {
            border: 1px solid var(--admin-line);
            border-radius: 1.5rem;
            background: var(--admin-surface);
            box-shadow: var(--admin-shadow-soft);
        }

        .admin-panel-soft {
            border: 1px solid rgba(91, 33, 182, 0.12);
            border-radius: 1.25rem;
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.96), rgba(239, 231, 251, 0.75));
            box-shadow: var(--admin-shadow-soft);
        }

        .admin-kpi {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(91, 33, 182, 0.12);
            border-radius: 1.5rem;
            background: linear-gradient(160deg, rgba(255, 255, 255, 0.96), rgba(248, 242, 234, 0.96));
            box-shadow: var(--admin-shadow-soft);
        }

        .admin-kpi::after {
            content: '';
            position: absolute;
            inset: auto -14% -52% auto;
            width: 12rem;
            height: 12rem;
            border-radius: 9999px;
            background: radial-gradient(circle, rgba(91, 33, 182, 0.14), transparent 66%);
        }

        .admin-kpi-accent::after {
            background: radial-gradient(circle, rgba(200, 138, 42, 0.18), transparent 66%);
        }

        .admin-btn-primary,
        .admin-btn-secondary,
        .admin-btn-ghost {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.625rem;
            border-radius: 9999px;
            font-weight: 700;
            transition: all 0.25s ease;
        }

        .admin-btn-primary {
            background: linear-gradient(135deg, var(--admin-brand), #6d28d9);
            color: #fff;
            box-shadow: 0 12px 25px rgba(91, 33, 182, 0.24);
        }

        .admin-btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 30px rgba(91, 33, 182, 0.3);
        }

        .admin-btn-secondary {
            background: linear-gradient(135deg, var(--admin-accent), #d9a14d);
            color: #1f2430;
            box-shadow: 0 12px 25px rgba(200, 138, 42, 0.22);
        }

        .admin-btn-secondary:hover {
            transform: translateY(-1px);
        }

        .admin-btn-ghost {
            border: 1px solid var(--admin-line);
            background: rgba(255, 255, 255, 0.88);
            color: var(--admin-text);
        }

        .admin-btn-ghost:hover {
            background: #fff;
            border-color: rgba(91, 33, 182, 0.18);
        }

        /* Sidebar Styles */
        .sidebar-link {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            border: 1px solid transparent;
            color: rgba(255, 255, 255, 0.82);
        }

        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 4px;
            background: linear-gradient(180deg, var(--admin-accent) 0%, #ffe0a8 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .sidebar-link.active::before {
            transform: scaleY(1);
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.14) 0%, rgba(200, 138, 42, 0.18) 100%);
            border-color: rgba(255, 255, 255, 0.08);
            color: #fff4df;
            font-weight: 600;
        }

        .sidebar-link:hover {
            background: rgba(255, 255, 255, 0.08);
            transform: translateX(4px);
        }

        .sidebar-link.active:hover {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.16) 0%, rgba(200, 138, 42, 0.2) 100%);
        }

        .glass {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(234, 223, 206, 0.9);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: #efe7db;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(91, 33, 182, 0.45);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(91, 33, 182, 0.7);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: var(--admin-shadow-strong);
        }

        .gradient-text {
            background: linear-gradient(135deg, var(--admin-brand) 0%, var(--admin-accent) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-app input[type='text'],
        .admin-app input[type='email'],
        .admin-app input[type='date'],
        .admin-app input[type='number'],
        .admin-app input[type='password'],
        .admin-app input[type='tel'],
        .admin-app textarea,
        .admin-app select {
            border-color: #ddcfbb;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.92);
            color: var(--admin-text);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0.4);
        }

        .admin-app input:focus,
        .admin-app textarea:focus,
        .admin-app select:focus {
            border-color: rgba(91, 33, 182, 0.45) !important;
            box-shadow: 0 0 0 4px rgba(91, 33, 182, 0.12) !important;
            outline: none;
        }

        .admin-app table thead {
            background: #f8f3ec;
        }

        .admin-app table thead th {
            color: #6a6174;
            font-weight: 800;
            letter-spacing: 0.08em;
        }

        .admin-app table tbody tr {
            transition: background-color 0.2s ease;
        }

        .admin-app table tbody tr:hover {
            background: rgba(239, 231, 251, 0.32) !important;
        }

        .admin-app .bg-white.rounded-lg,
        .admin-app .bg-white.rounded-xl,
        .admin-app .bg-white.rounded-2xl,
        .admin-app .bg-white.rounded-3xl {
            border: 1px solid var(--admin-line);
            box-shadow: var(--admin-shadow-soft);
        }

        .admin-app .text-primary,
        .admin-app .text-blue-600,
        .admin-app .text-indigo-600,
        .admin-app .text-purple-600 {
            color: var(--admin-brand) !important;
        }

        .admin-app .bg-primary,
        .admin-app .bg-blue-600,
        .admin-app .bg-indigo-600,
        .admin-app .bg-purple-600 {
            background-color: var(--admin-brand) !important;
        }

        .admin-app .hover\:bg-blue-700:hover,
        .admin-app .hover\:bg-indigo-700:hover,
        .admin-app .hover\:bg-purple-700:hover {
            background-color: var(--admin-brand-strong) !important;
        }

        .admin-app .from-primary,
        .admin-app .from-purple-500,
        .admin-app .from-purple-600,
        .admin-app .from-purple-700,
        .admin-app .from-blue-500,
        .admin-app .from-blue-600,
        .admin-app .from-blue-900,
        .admin-app .from-purple-900,
        .admin-app .from-indigo-500,
        .admin-app .from-indigo-600 {
            --tw-gradient-from: var(--admin-brand) var(--tw-gradient-from-position) !important;
            --tw-gradient-to: rgba(91, 33, 182, 0) var(--tw-gradient-to-position) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }

        .admin-app .to-primary,
        .admin-app .to-purple-600,
        .admin-app .to-purple-700,
        .admin-app .to-blue-600,
        .admin-app .to-blue-700,
        .admin-app .to-indigo-600,
        .admin-app .to-indigo-700 {
            --tw-gradient-to: #6d28d9 var(--tw-gradient-to-position) !important;
        }

        .admin-app .from-green-500,
        .admin-app .from-green-600,
        .admin-app .from-green-900 {
            --tw-gradient-from: var(--admin-success) var(--tw-gradient-from-position) !important;
            --tw-gradient-to: rgba(31, 122, 91, 0) var(--tw-gradient-to-position) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }

        .admin-app .to-green-600,
        .admin-app .to-green-700 {
            --tw-gradient-to: #248c69 var(--tw-gradient-to-position) !important;
        }

        .admin-app .from-amber-500,
        .admin-app .from-amber-600,
        .admin-app .from-yellow-900,
        .admin-app .from-orange-500 {
            --tw-gradient-from: var(--admin-accent) var(--tw-gradient-from-position) !important;
            --tw-gradient-to: rgba(200, 138, 42, 0) var(--tw-gradient-to-position) !important;
            --tw-gradient-stops: var(--tw-gradient-from), var(--tw-gradient-to) !important;
        }

        .admin-app .to-amber-600,
        .admin-app .to-orange-500,
        .admin-app .to-orange-600,
        .admin-app .to-yellow-600 {
            --tw-gradient-to: #daa04b var(--tw-gradient-to-position) !important;
        }

        .admin-app .border-blue-500,
        .admin-app .border-purple-500,
        .admin-app .border-indigo-500 {
            border-color: rgba(91, 33, 182, 0.35) !important;
        }

        .admin-app .text-green-700,
        .admin-app .text-green-800,
        .admin-app .text-green-600 {
            color: var(--admin-success) !important;
        }

        .admin-app .bg-green-50,
        .admin-app .bg-green-100 {
            background-color: var(--admin-success-soft) !important;
        }

        .admin-app .border-green-400,
        .admin-app .border-green-500 {
            border-color: rgba(31, 122, 91, 0.32) !important;
        }

        .admin-app .text-yellow-700,
        .admin-app .text-yellow-800,
        .admin-app .text-amber-700,
        .admin-app .text-amber-800 {
            color: var(--admin-warning) !important;
        }

        .admin-app .bg-yellow-50,
        .admin-app .bg-yellow-100,
        .admin-app .bg-amber-50,
        .admin-app .bg-amber-100 {
            background-color: var(--admin-warning-soft) !important;
        }

        .admin-app .border-yellow-500,
        .admin-app .border-amber-500 {
            border-color: rgba(184, 106, 22, 0.34) !important;
        }

        .admin-app .text-red-700,
        .admin-app .text-red-800,
        .admin-app .text-red-600 {
            color: var(--admin-danger) !important;
        }

        .admin-app .bg-red-50,
        .admin-app .bg-red-100 {
            background-color: var(--admin-danger-soft) !important;
        }

        .admin-app .border-red-400,
        .admin-app .border-red-500 {
            border-color: rgba(180, 35, 24, 0.3) !important;
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
            border: 3px solid rgba(91, 33, 182, 0.2);
            border-radius: 50%;
            border-top-color: var(--admin-brand);
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
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
            background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
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

<body class="admin-app">
    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden md:hidden"></div>

    <div class="flex h-screen overflow-hidden">
        @include('admin.layouts.header')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- @yield('admin.layouts.header-content') -->
            @include('admin.layouts.header-content')

            <!-- Page Content -->
            <main class="admin-main flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 page-transition">
                @if(session('success'))
                    <div
                        class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-2xl mr-3"></i>
                            <p class="font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div
                        class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-md animate-fade-in">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                            <p class="font-medium">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>

            @include('admin.layouts.footer')
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Sidebar toggle for mobile
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');

        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function () {
                sidebar.classList.toggle('show');
                sidebarOverlay.classList.toggle('hidden');
            });
        }

        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', function () {
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
            form.addEventListener('submit', function (e) {
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

    <script>
        // Vérifie si la page est chargée depuis le cache BFCache (cas typique du bouton Retour)
        window.addEventListener('pageshow', function (event) {
            if (event.persisted) {
                // Si c'est le cas, forcer le rechargement pour éviter d'afficher la page déconnectée
                window.location.reload();
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
