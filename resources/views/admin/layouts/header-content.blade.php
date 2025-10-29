<!-- Top Bar -->
<header class="h-16 glass shadow-lg flex items-center justify-between px-4 md:px-6 relative z-30">
    <div class="flex items-center">
        <button id="sidebar-toggle"
            class="md:hidden text-gray-600 hover:text-primary mr-4 p-2 hover:bg-purple-50 rounded-lg transition-all">
            <i class="fas fa-bars text-xl"></i>
        </button>
        <div>
            <h2 class="text-lg md:text-xl font-bold text-gray-800 font-montserrat">
                @yield('title', 'Dashboard')
            </h2>
            </h2>
            <p class="text-xs text-gray-500 hidden sm:block">{{ now()->format('l, d F Y') }}</p>
        </div>
    </div>

    <div class="flex items-center space-x-2 md:space-x-4">
        <!-- Search -->
        <button
            class="hidden md:flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
            <i class="fas fa-search text-gray-600 mr-2"></i>
            <span class="text-sm text-gray-600">Rechercher...</span>
        </button>

        <!-- Notifications -->
        <!-- <a href="{{ route('admin.notifications') }}" class="relative text-gray-600 hover:text-primary p-2 hover:bg-purple-50 rounded-lg transition-all">
            <i class="fas fa-bell text-xl"></i>
            <span class="notification-badge absolute -top-1 -right-1 w-5 h-5 bg-red-500 rounded-full text-xs text-white flex items-center justify-center font-bold shadow-lg">3</span>
        </a> -->
        <!-- Notification Bell -->
        <div x-data="{ open: false }" class="relative">
            <!-- Bouton cloche -->
            <button @click="open = !open"
                class="relative flex items-center justify-center p-2 rounded-lg hover:bg-gray-100 transition">
                <i class="fas fa-bell text-xl text-gray-700"></i>

                @php
                    $unreadCount = auth('admin')->user()->unreadNotifications()->count();
                @endphp

                @if($unreadCount > 0)
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-semibold rounded-full px-1.5 py-0.5 shadow-md">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            <!-- Modal notifications -->
            <div x-show="open" @click.away="open = false" x-transition
                class="absolute right-0 mt-3 w-80 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50">
                <!-- Header -->
                <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-sm font-semibold text-gray-700">Notifications</h3>
                    <button @click="open = false" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>

                <!-- Liste notifications -->
                <div class="max-h-80 overflow-y-auto divide-y divide-gray-100">
                    @forelse(auth('admin')->user()->notifications()->latest()->take(10)->get() as $notification)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <p class="text-sm font-medium text-gray-800">
                                {{ $notification->data['title'] ?? 'Nouvelle activité' }}
                            </p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $notification->data['message'] ?? 'Une mise à jour a été effectuée.' }}
                            </p>
                            <p class="text-[11px] text-gray-400 mt-1">
                                {{ $notification->created_at->diffForHumans() }}
                            </p>
                        </div>
                    @empty
                        <div class="p-6 text-center text-sm text-gray-500">
                            Aucune notification pour le moment
                        </div>
                    @endforelse
                </div>

                <!-- Footer -->
                <div class="px-4 py-2 bg-gray-50 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.notifications') }}"
                        class="text-primary font-medium text-sm hover:underline">
                        Voir toutes les notifications
                    </a>
                </div>
            </div>
        </div>


        <!-- Profile -->
        <a href="{{ route('admin.profile') }}"
            class="hidden sm:block text-gray-600 hover:text-primary p-2 hover:bg-purple-50 rounded-lg transition-all">
            <i class="fas fa-user-circle text-xl"></i>
        </a>

        <!-- Logout -->
        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
            @csrf
            <button type="submit"
                class="flex items-center space-x-2 px-3 md:px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-sign-out-alt"></i>
                <span class="hidden sm:inline text-sm font-medium">Déconnexion</span>
            </button>
        </form>
    </div>
</header>