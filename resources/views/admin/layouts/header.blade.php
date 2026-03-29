<!-- Sidebar -->
<aside id="sidebar" class="w-72 flex-shrink-0 flex flex-col transition-all duration-300 ease-in-out border-r border-white/10 bg-[linear-gradient(180deg,#26163f_0%,#2f1c55_48%,#36215e_100%)] text-white shadow-2xl">
    <!-- Logo -->
    <div
        class="h-20 flex items-center justify-center border-b border-white/10 bg-[radial-gradient(circle_at_top,_rgba(255,255,255,0.18),_transparent_45%),linear-gradient(90deg,rgba(255,255,255,0.04),rgba(255,255,255,0))] relative overflow-hidden">
        <div
            class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-20 animate-pulse-slow">
        </div>
        <div class="relative z-10 flex items-center">
            <i class="fas fa-crown text-secondary text-2xl mr-3"></i>
            <div>
                <p class="text-[11px] font-semibold uppercase tracking-[0.28em] text-white/55">Admin Console</p>
                <h1 class="text-lg font-bold text-white font-montserrat">Carré Premium</h1>
            </div>
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
            <p class="px-4 text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-grip-horizontal mr-2"></i>
                Gestion
            </p>

            @if(auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->isAdmin())
            <a href="{{ route('admin.members.index') }}"
                    class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.members.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 text-lg"></i>
                <span class="ml-3 font-medium">Membres</span>
                <span
                    class="ml-auto rounded-full bg-white/10 px-2.5 py-1 text-xs font-semibold text-white/80">{{ \App\Models\Admin::count() }}</span>
            </a>

            <a href="{{ route('admin.users.index') }}"
                    class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users w-5 text-lg"></i>
                <span class="ml-3 font-medium">Utilisateurs</span>
                <span
                    class="ml-auto rounded-full bg-white/10 px-2.5 py-1 text-xs font-semibold text-white/80">{{ \App\Models\User::count() }}</span>
            </a>
            @endif

            <a href="{{ route('admin.bookings.index') }}"
                    class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <i class="fas fa-ticket-alt w-5 text-lg"></i>
                <span class="ml-3 font-medium">Réservations</span>
                <span class="ml-auto rounded-full bg-white/10 px-2.5 py-1 text-xs font-semibold text-white/80">{{ \App\Models\Booking::count() }}</span>
            </a>
        </div>

        <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-box mr-2"></i>
                Produits
            </p>

            @if(auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->isAdmin())
            <!-- <a href="{{ route('admin.flights.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.flights.*') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-plane w-5 text-lg"></i>
                <span class="ml-3 font-medium">Vols</span>
            </a> -->

            <a href="{{ route('admin.events.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                <i class="fas fa-calendar-alt w-5 text-lg"></i>
                <span class="ml-3 font-medium">Événements</span>
            </a>

            <a href="{{ route('admin.packages.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.packages.*') ? 'active' : '' }}">
                <i class="fas fa-suitcase w-5 text-lg"></i>
                <span class="ml-3 font-medium">Packages</span>
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-folder w-5 text-lg"></i>
                <span class="ml-3 font-medium">Catégories</span>
            </a>

            <a href="{{ route('admin.locations.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.locations.*') ? 'active' : '' }}">
                <i class="fas fa-car w-5 text-lg"></i>
                <span class="ml-3 font-medium">Locations</span>
            </a>
            @endif
        </div>

        <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-palette mr-2"></i>
                Contenu
            </p>

            @if(auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->isAdmin())
            <a href="{{ route('admin.carousels.index') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.carousels.*') ? 'active' : '' }}">
                <i class="fas fa-images w-5 text-lg"></i>
                <span class="ml-3 font-medium">Carrousels</span>
            </a>
            @endif
        </div>

        @if(auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->isAdmin() || auth('admin')->user()->isAccountant())
        <div class="mt-6">
            <p class="px-4 text-xs font-semibold text-white/40 uppercase tracking-wider mb-3 flex items-center">
                <i class="fas fa-calculator mr-2"></i>
                Comptabilité
            </p>

            <a href="{{ route('admin.accountant.reports') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-2xl {{ request()->routeIs('admin.accountant.reports') ? 'active' : '' }}">
                <i class="fas fa-file-alt w-5 text-lg"></i>
                <span class="ml-3 font-medium">Rapports</span>
            </a>

            <!-- <a href="{{ route('admin.accountant.payment-gateways') }}"
                class="sidebar-link flex items-center px-4 py-3 mb-2 rounded-lg {{ request()->routeIs('admin.accountant.payment-gateways') ? 'active' : 'text-gray-700' }}">
                <i class="fas fa-credit-card w-5 text-lg"></i>
                <span class="ml-3 font-medium">Paiements</span>
            </a> -->
        </div>
        @endif

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
    <div class="border-t border-white/10 p-4 bg-white/5">
        <a href="{{ route('admin.profile') }}"
            class="flex items-center hover:bg-white/10 p-3 rounded-2xl transition-all duration-300 group">
            <div class="relative">
                <div
                    class="w-11 h-11 rounded-full bg-[linear-gradient(135deg,#ffffff_0%,#f0e7ff_100%)] flex items-center justify-center text-[#4b2386] font-bold shadow-lg group-hover:shadow-xl transition-shadow">
                    {{ substr(auth('admin')->user()->name, 0, 1) }}
                </div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white"></div>
            </div>
            <div class="ml-3 flex-1">
                <p class="text-sm font-semibold text-white">{{ auth('admin')->user()->name }}</p>
                <p class="text-xs text-white/55">{{ ucfirst(str_replace('_', ' ', auth('admin')->user()->role)) }}</p>
            </div>
            <i class="fas fa-chevron-right text-white/35 group-hover:text-white transition-colors"></i>
        </a>
    </div>
</aside>
