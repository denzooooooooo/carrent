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
