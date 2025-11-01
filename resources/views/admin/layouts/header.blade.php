<!-- Sidebar -->
<aside id="sidebar" class="w-64 glass shadow-2xl flex-shrink-0 flex flex-col transition-all duration-300 ease-in-out">
    <!-- Logo -->
    <div
        class="h-16 flex items-center justify-center border-b border-gray-200 bg-gradient-to-r from-primary to-purple-600 relative overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-pulse-slow">
        </div>
        <div class="relative z-10 flex items-center">
            <i class="fas fa-crown text-secondary text-2xl mr-2"></i>
            <h1 class="text-xl font-bold text-white font-montserrat">Carré Premium</h1>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-4 px-3">
        <a href="{{ route('admin.dashboard') }}"
            class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active' : 'text-gray-700' }}">
            <i class="fas fa-chart-line w-5 text-lg"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>

        <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-grip-horizontal mr-2"></i>
                Gestion
            </p>

            <a href="{{ route('admin.members.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.members.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-users w-5 text-lg"></i>
                <span class="ml-3 font-medium">Membres</span>
                <span
                    class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">{{ \App\Models\Admin::count() }}</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.users.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-users w-5 text-lg"></i>
                <span class="ml-3 font-medium">Utilisateurs</span>
                <span
                    class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">{{ \App\Models\User::count() }}</span>
            </a>

            <a href="{{ route('admin.bookings.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-ticket-alt w-5 text-lg"></i>
                <span class="ml-3 font-medium">Réservations</span>
                <span class="ml-auto bg-orange-100 text-orange-800 text-xs px-2 py-1 rounded-full">{{ \App\Models\Booking::count() }}</span>
            </a>
        </div>

        <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-box mr-2"></i>
                Produits
            </p>

            <!-- <a href="{{ route('admin.flights.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.flights.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-plane w-5 text-lg"></i>
                <span class="ml-3 font-medium">Vols</span>
            </a> -->

            <a href="{{ route('admin.events.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.events.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-calendar-alt w-5 text-lg"></i>
                <span class="ml-3 font-medium">Événements</span>
            </a>

            <a href="{{ route('admin.packages.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.packages.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-suitcase w-5 text-lg"></i>
                <span class="ml-3 font-medium">Packages</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.categories.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-folder w-5 text-lg"></i>
                <span class="ml-3 font-medium">Catégories</span>
            </a>
        </div>

        <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-palette mr-2"></i>
                Contenu
            </p>

            <a href="{{ route('admin.carousels.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.carousels.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-images w-5 text-lg"></i>
                <span class="ml-3 font-medium">Carrousels</span>
            </a>
        </div>

        <!-- <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-store mr-2"></i>
                Marketing
            </p>

            <a href="{{ route('admin.reviews.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.reviews.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-star w-5 text-lg"></i>
                <span class="ml-3 font-medium">Avis Clients</span>
                <span class="ml-auto bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded-full">0</span>
            </a>

            <a href="{{ route('admin.promo-codes.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.promo-codes.*') ? 'active' : 'text-gray-700' }}">
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

            <a href="{{ route('admin.settings.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.settings.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-sliders-h w-5 text-lg"></i>
                <span class="ml-3 font-medium">Paramètres</span>
            </a>

            <a href="{{ route('admin.pricing-rules.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.pricing-rules.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-percentage w-5 text-lg"></i>
                <span class="ml-3 font-medium">Règles de Prix</span>
            </a>

            <a href="{{ route('admin.api-config.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.api-config.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-plug w-5 text-lg"></i>
                <span class="ml-3 font-medium">APIs</span>
            </a>

            <a href="{{ route('admin.payment-gateways.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.payment-gateways.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-credit-card w-5 text-lg"></i>
                <span class="ml-3 font-medium">Paiements</span>
            </a>
        </div> -->

    </nav>

    <!-- User Info -->
    <div class="border-t border-gray-200 p-4 bg-gradient-to-r from-purple-50 to-pink-50">
        <a href="{{ route('admin.profile') }}"
            class="flex items-center hover:bg-white p-3 rounded-lg transition-all duration-300 group">
            <div class="relative">
                <div
                    class="w-10 h-10 rounded-full bg-gradient-to-r from-primary to-purple-600 flex items-center justify-center text-white font-bold shadow-lg group-hover:shadow-xl transition-shadow">
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