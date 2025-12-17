@php
    // Navigation structure with submenus
    $navigation = [
        [
            'name' => __('Home'),
            'name_fr' => 'Accueil',
            'path' => '#',
            'icon' => 'fa-home',
            'color' => 'purple',
            'submenu' => [
                ['name' => __('About Us'), 'name_fr' => 'À propos', 'path' => '/about', 'icon' => 'fa-info-circle'],
                ['name' => __('Contact Us'), 'name_fr' => 'Nous contacter', 'path' => '/contact', 'icon' => 'fa-envelope'],
            ]
        ],
        [
            'name' => __('Ticketing'),
            'name_fr' => 'Billetterie Voyages',
            'path' => '#',
            'icon' => 'fa-ticket-alt',
            'color' => 'blue',
            'submenu' => [
                ['name' => __('Travel'), 'name_fr' => 'Voyage', 'path' => '/flights', 'icon' => 'fa-plane'],
                ['name' => __('packages'), 'name_fr' => 'Packages touristiques', 'path' => '/packages', 'icon' => 'fa-shopping-bag'],

            ]
        ],
        [
            'name' => __('Event Ticketing'),
            'name_fr' => 'Billeterie Event',
            'path' => '#',
            'icon' => 'fa-calendar-star',
            'color' => 'pink',
            'submenu' => [
                ['name' => __('Cultural'), 'name_fr' => 'Culturel', 'path' => '/events?type=culturel', 'icon' => 'fa-theater-masks'],
                ['name' => __('Sports'), 'name_fr' => 'Sportif', 'path' => '/events?type=sportif', 'icon' => 'fa-futbol'],
            ]
        ],
        [
            'name' => __('Concierge'),
            'name_fr' => 'Conciergerie',
            'path' => '#',
            'icon' => 'fa-concierge-bell',
            'color' => 'orange',
            'submenu' => [
                ['name' => __('Luxury'), 'name_fr' => 'Luxe', 'path' => '/concierge/luxury', 'icon' => 'fa-gem'],
                ['name' => __('Vehicle Rental'), 'name_fr' => 'Location de véhicule', 'path' => '/location', 'icon' => 'fa-car'],
                ['name' => __('Personal Shopper'), 'name_fr' => 'Personal Shopper', 'path' => '/concierge/personal-shopper', 'icon' => 'fa-shopping-bag'],

            ]
        ],
    ];
    // Function to check if a navigation item is active
    function isActive($path) {
        if ($path === '/') {
            return request()->is('/');
        }
        return request()->is(ltrim($path, '/')) || request()->is(ltrim($path, '/').'/*');
    }
@endphp

<div class="fixed top-0 left-0 right-0 z-50">
    {{-- Top Bar with Register and B2B Partnership Buttons --}}
    <div class="bg-gray-100 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-end h-8 space-x-2">
                <a href="{{ route('register') }}" class="flex items-center space-x-2 px-2 py-1 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors whitespace-nowrap">
                    <i class="fas fa-user-plus"></i>
                    <span>Inscription</span>
                </a>
                <span class="text-gray-300 dark:text-gray-600">|</span>
                <a href="{{ route('partnership') }}" class="flex items-center space-x-2 px-2 py-1 text-xs font-semibold text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 transition-colors whitespace-nowrap">
                    <i class="fas fa-handshake"></i>
                    <span>Partenariat</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Main Header --}}
    <header 
        x-data="{
            mobileMenuOpen: false,
            activeDropdown: null,
            darkMode: false,
            init() {
                // Initialize dark mode from localStorage - DEFAULT IS FALSE (light mode)
                const savedTheme = localStorage.getItem('theme');
                this.darkMode = (savedTheme === 'dark');
                
                // Ensure light mode by default
                if (!savedTheme || savedTheme === 'light') {
                    this.darkMode = false;
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else if (savedTheme === 'dark') {
                    this.darkMode = true;
                    document.documentElement.classList.add('dark');
                }
            },
            toggleTheme() {
                this.darkMode = !this.darkMode;
                const theme = this.darkMode ? 'dark' : 'light';
                localStorage.setItem('theme', theme);
                
                if (this.darkMode) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                // Save to server
                fetch('/theme/change', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                    },
                    body: JSON.stringify({ theme })
                });
            },
            toggleDropdown(index) {
                this.activeDropdown = this.activeDropdown === index ? null : index;
            },
            closeDropdowns() {
                this.activeDropdown = null;
            }
        }"
        @click.away="closeDropdowns()"
        class="bg-white dark:bg-gray-900 shadow-md transition-colors duration-300"
    >
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            
            {{-- Logo --}}
            <a href="/" class="flex items-center space-x-3 group whitespace-nowrap">
                <img
                    src="{{ asset('logos/logo.jpg') }}"
                    alt="Carré Premium"
                    class="h-12 w-auto max-h-12"
                />
                <div class="flex flex-col">
                    <span class="text-base font-bold text-gray-900 dark:text-white leading-tight">Carré Premium</span>
                    <span class="text-xs text-gray-500 dark:text-gray-400 leading-tight">Conciergerie</span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center space-x-4 flex-nowrap text-sm">
                @foreach($navigation as $index => $item)
                    <div class="relative" x-data="{ open: false }">
                        @if(isset($item['submenu']))
                            {{-- Menu with dropdown --}}
                            <button
                                @click="toggleDropdown({{ $index }})"
                                @class([
                                    'flex items-center space-x-2 px-2 py-1 font-medium transition-all duration-200 relative group whitespace-nowrap',
                                    'text-'.$item['color'].'-600 dark:text-'.$item['color'].'-400' => isActive($item['path']),
                                    'text-gray-700 dark:text-gray-300' => !isActive($item['path'])
                                ])
                            >
                                <i class="fas {{ $item['icon'] }} text-xs"></i>
                                <span>{{ $item['name_fr'] ?? $item['name'] }}</span>
                                <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': activeDropdown === {{ $index }} }"></i>
                                {{-- Underline on hover --}}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-{{ $item['color'] }}-600 transition-all duration-300 group-hover:w-full"></span>
                            </button>

                            {{-- Dropdown Menu --}}
                            <div
                                x-show="activeDropdown === {{ $index }}"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 scale-95"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-150"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-95"
                                class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2"
                                style="display: none;"
                            >
                                @foreach($item['submenu'] as $subitem)
                                    <a 
                                        href="{{ $subitem['path'] }}"
                                        @click="closeDropdowns()"
                                        class="flex items-center space-x-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-150 hover:pl-6 whitespace-nowrap"
                                    >
                                        <i class="fas {{ $subitem['icon'] }} w-5"></i>
                                        <span>{{ $subitem['name_fr'] ?? $subitem['name'] }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            {{-- Simple menu item - Text with underline on hover --}}
                            <a
                                href="{{ $item['path'] }}"
                                @class([
                                    'flex items-center space-x-2 px-2 py-1 font-medium transition-all duration-200 relative group whitespace-nowrap',
                                    'text-'.$item['color'].'-600 dark:text-'.$item['color'].'-400' => isActive($item['path']),
                                    'text-gray-700 dark:text-gray-300' => !isActive($item['path'])
                                ])
                            >
                                <i class="fas {{ $item['icon'] }} text-xs"></i>
                                <span>{{ $item['name_fr'] ?? $item['name'] }}</span>
                                {{-- Underline on hover --}}
                                <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-{{ $item['color'] }}-600 transition-all duration-300 group-hover:w-full"></span>
                                {{-- Active indicator --}}
                                @if(isActive($item['path']))
                                    <span class="absolute bottom-0 left-0 w-full h-0.5 bg-{{ $item['color'] }}-600"></span>
                                @endif
                            </a>
                        @endif
                    </div>
                @endforeach
            </nav>

            {{-- Right Actions --}}
            <div class="flex items-center space-x-2">

                {{-- Enhanced Search form (site-wide) --}}
                <div class="hidden md:flex items-center relative">
                    <div class="relative">
                        <input
                            id="site-search"
                            name="q"
                            type="search"
                            placeholder="Rechercher..."
                            class="bg-gray-100 dark:bg-gray-800 text-sm px-4 py-2 pr-10 pl-10 outline-none text-gray-700 dark:text-gray-300 w-48 rounded-lg border border-gray-200 dark:border-gray-700 focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 transition-all"
                            aria-label="Recherche"
                            autocomplete="off"
                        />
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <div class="absolute right-3 top-1/2 transform -translate-y-1/2">
                            <div id="search-loading" class="hidden">
                                <div class="animate-spin h-4 w-4 border-2 border-purple-500 border-t-transparent rounded-full"></div>
                            </div>
                        </div>
                        <div id="search-suggestions" class="absolute left-0 mt-2 w-80 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-xl overflow-hidden hidden z-50 max-h-96 overflow-y-auto">
                            <div id="search-suggestions-header" class="px-4 py-2 bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Suggestions</p>
                            </div>
                            <ul id="search-suggestions-list" class="divide-y divide-gray-100 dark:divide-gray-700">
                                {{-- Suggestions will be populated here --}}
                            </ul>
                            <div id="search-suggestions-footer" class="px-4 py-3 bg-gray-50 dark:bg-gray-700 border-t border-gray-200 dark:border-gray-600 hidden">
                                <form action="/search" method="GET" class="flex items-center justify-between">
                                    <input type="hidden" name="q" id="search-full-query">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Voir tous les résultats pour "<span id="search-query-display"></span>"</span>
                                    <button type="submit" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-300 font-medium text-sm">
                                        Voir tout <i class="fas fa-arrow-right ml-1"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Mobile Search Button --}}
                <button 
                    id="mobile-search-toggle"
                    class="md:hidden p-2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
                    title="Rechercher"
                >
                    <i class="fas fa-search"></i>
                </button>
                
                {{-- Theme Toggle Button - Uses CSS Variables System --}}
                <button id="theme-toggle" class="flex items-center space-x-2 px-2 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all">
                    <i class="fas fa-adjust"></i>
                    <span class="text-xs font-medium hidden md:inline">Thème</span>
                </button>

                {{-- Language Selector --}}
                <div class="relative hidden md:block" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="flex items-center space-x-2 px-3 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all"
                    >
                        <i class="fas fa-globe"></i>
                        <span class="text-sm font-medium">{{ strtoupper(session('locale', 'fr')) }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-40 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2"
                        style="display: none;"
                    >
                        <button
                            onclick="changeLanguage('fr')"
                            class="flex items-center space-x-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <i class="fas fa-flag"></i>
                            <span>Français</span>
                        </button>
                        <button
                            onclick="changeLanguage('en')"
                            class="flex items-center space-x-3 w-full px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                        >
                            <i class="fas fa-flag-usa"></i>
                            <span>English</span>
                        </button>
                    </div>
                </div>

                {{-- Currency Selector --}}
                <div class="relative hidden md:block" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="flex items-center space-x-2 px-3 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all"
                    >
                        <span class="text-sm font-medium">{{ session('currency', 'XOF') }}</span>
                        <i class="fas fa-chevron-down text-xs"></i>
                    </button>

                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition
                        class="absolute right-0 mt-2 w-36 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2"
                        style="display: none;"
                    >
                        <button onclick="changeCurrency('XOF')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">XOF</button>
                        <button onclick="changeCurrency('EUR')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">EUR</button>
                        <button onclick="changeCurrency('USD')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">USD</button>
                        <button onclick="changeCurrency('GBP')" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">GBP</button>
                    </div>
                </div>

                {{-- Auth Buttons --}}
                @if(!$isAuthenticated)
                    <a href="{{ route('login') }}" class="hidden md:flex items-center px-4 py-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium transition-all">
                        <i class="fas fa-sign-in-alt"></i>
                    </a>
                @else
                    {{-- User Menu --}}
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-all"
                        >
                            <div class="w-8 h-8 rounded-full bg-purple-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                            </div>
                            <i class="fas fa-chevron-down text-xs text-gray-700 dark:text-gray-300"></i>
                        </button>

                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition
                            class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 py-2"
                            style="display: none;"
                        >
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-user w-5"></i>
                                <span>{{ __('My Profile') }}</span>
                            </a>
                            <a href="{{ route('bookings') }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <i class="fas fa-ticket-alt w-5"></i>
                                <span>{{ __('My Bookings') }}</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center space-x-3 w-full px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt w-5"></i>
                                    <span>{{ __('Logout') }}</span>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Mobile Menu Button --}}
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2.5 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700"
                >
                    <i class="fas fa-bars text-xl" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times text-xl" x-show="mobileMenuOpen"></i>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="mobileMenuOpen"
        x-transition
        class="lg:hidden border-t"
        style="display: none;"
    >
        <div class="container mx-auto px-4 py-4 space-y-2">
            {{-- Navigation Links --}}
            @foreach($navigation as $item)
                @if(isset($item['submenu']))
                    <div x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="flex items-center justify-between w-full px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium"
                        >
                            <div class="flex items-center space-x-3">
                                <i class="fas {{ $item['icon'] }}"></i>
                                <span>{{ $item['name'] }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </button>
                        <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                            @foreach($item['submenu'] as $subitem)
                                <a href="{{ $subitem['path'] }}" class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">
                                    <i class="fas {{ $subitem['icon'] }}"></i>
                                    <span>{{ $subitem['name'] }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $item['path'] }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium">
                        <i class="fas {{ $item['icon'] }}"></i>
                        <span>{{ $item['name'] }}</span>
                    </a>
                @endif
            @endforeach

            {{-- Settings Section --}}
            <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-3">
                <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Settings') }}</p>
                
                {{-- Theme Toggle --}}
                <button
                    id="theme-toggle-mobile"
                    class="flex items-center justify-between w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium"
                >
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-adjust"></i>
                        <span>{{ __('Theme') }}</span>
                    </div>
                    <i class="fas fa-chevron-right text-xs"></i>
                </button>

                {{-- Language Selector --}}
                <div x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium"
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-globe"></i>
                            <span>{{ __('Language') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-semibold">{{ strtoupper(session('locale', 'fr')) }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                        <button
                            onclick="changeLanguage('fr')"
                            class="flex items-center space-x-3 w-full px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
                        >
                            <i class="fas fa-flag"></i>
                            <span>Français</span>
                        </button>
                        <button
                            onclick="changeLanguage('en')"
                            class="flex items-center space-x-3 w-full px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg"
                        >
                            <i class="fas fa-flag-usa"></i>
                            <span>English</span>
                        </button>
                    </div>
                </div>

                {{-- Currency Selector --}}
                <div x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="flex items-center justify-between w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700 font-medium"
                    >
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-dollar-sign"></i>
                            <span>{{ __('Currency') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="text-sm font-semibold">{{ session('currency', 'XOF') }}</span>
                            <i class="fas fa-chevron-down text-xs transition-transform" :class="{ 'rotate-180': open }"></i>
                        </div>
                    </button>
                    <div x-show="open" x-transition class="ml-8 mt-2 space-y-1">
                        <button onclick="changeCurrency('XOF')" class="block w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">XOF</button>
                        <button onclick="changeCurrency('EUR')" class="block w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">EUR</button>
                        <button onclick="changeCurrency('USD')" class="block w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">USD</button>
                        <button onclick="changeCurrency('GBP')" class="block w-full text-left px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg">GBP</button>
                    </div>
                </div>
            </div>

            {{-- Auth Buttons --}}
            @if(!$isAuthenticated)
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                    <a href="{{ route('login') }}" class="flex items-center justify-center space-x-2 w-full px-4 py-3 rounded-lg bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-medium hover:bg-gray-200 dark:hover:bg-gray-700">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>{{ __('Login') }}</span>
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center space-x-2 w-full px-4 py-3 rounded-lg bg-purple-600 text-white font-medium hover:bg-purple-700 shadow-lg">
                        <i class="fas fa-user-plus"></i>
                        <span>{{ __('Register') }}</span>
                    </a>
                </div>
            @else
                {{-- User Menu Mobile --}}
                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 space-y-2">
                    <div class="px-4 py-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</p>
                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium">
                        <i class="fas fa-user"></i>
                        <span>{{ __('My Profile') }}</span>
                    </a>
                    <a href="{{ route('bookings') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium">
                        <i class="fas fa-ticket-alt"></i>
                        <span>{{ __('My Bookings') }}</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center space-x-3 w-full px-4 py-3 rounded-lg text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-800 font-medium">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>{{ __('Logout') }}</span>
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
    </header>
</div>

    <script>
    // Enhanced autocomplete for header search input with keyboard navigation and footer
    (function () {
        const input = document.getElementById('site-search');
        const container = document.getElementById('search-suggestions');
        const list = document.getElementById('search-suggestions-list');
        const footer = document.getElementById('search-suggestions-footer');
        const queryDisplay = document.getElementById('search-query-display');
        const queryHidden = document.getElementById('search-full-query');
        let timer = null;
        let suggestions = [];
        let selectedIndex = -1;
        let lastQuery = '';

        function hide() {
            container.classList.add('hidden');
            list.innerHTML = '';
            suggestions = [];
            selectedIndex = -1;
            if (footer) footer.classList.add('hidden');
        }

        function show(items, q) {
            list.innerHTML = '';
            suggestions = items || [];
            selectedIndex = -1;
            if (!items || items.length === 0) {
                hide();
                return;
            }
            items.forEach((it, idx) => {
                const li = document.createElement('li');
                li.className = 'px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer';
                li.tabIndex = -1;
                li.setAttribute('data-index', idx);
                li.innerHTML =
                    `<div class="flex items-center justify-between"><div><span class="font-medium">${escapeHtml(it.title)}</span><div class="text-xs text-gray-500 dark:text-gray-400">${escapeHtml(it.type)}</div></div><div class="ml-3 text-sm text-indigo-600">→</div></div>`;
                li.addEventListener('mousedown', function (e) {
                    e.preventDefault();
                    goToSuggestion(idx);
                });
                list.appendChild(li);
            });
            container.classList.remove('hidden');
            if (footer) {
                footer.classList.remove('hidden');
                if (queryDisplay) queryDisplay.textContent = q;
                if (queryHidden) queryHidden.value = q;
            }
        }

        function escapeHtml(unsafe) {
            return (unsafe || '').toString().replace(/[&<>\"']/g, function (m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": "&#039;" }[m];
            });
        }

        function fetchSuggestions(q) {
            lastQuery = q;
            if (!q || q.length < 2) { hide(); return; }
            fetch("{{ url('/search/suggest') }}?q=" + encodeURIComponent(q))
                .then(r => r.json())
                .then(data => {
                    show(data.results || [], q);
                })
                .catch(() => hide());
        }

        function goToSuggestion(idx) {
            if (!suggestions[idx]) return;
            const it = suggestions[idx];
            if (it.slug) {
                let path = '/';
                if (it.type === 'package') path = '/packages/' + it.slug;
                else if (it.type === 'event') path = '/events/' + it.slug;
                else if (it.type === 'page') path = '/' + it.slug;
                else path = '/search?q=' + encodeURIComponent(it.title);
                window.location.href = path;
            } else {
                window.location.href = '/search?q=' + encodeURIComponent(it.title);
            }
        }

        function updateActiveItem() {
            Array.from(list.children).forEach((li, idx) => {
                if (idx === selectedIndex) {
                    li.classList.add('bg-gray-100', 'dark:bg-gray-700');
                    li.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-700');
                    li.scrollIntoView({ block: 'nearest' });
                } else {
                    li.classList.remove('bg-gray-100', 'dark:bg-gray-700');
                    li.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-700');
                }
            });
        }

        if (input) {
            input.addEventListener('input', function (e) {
                clearTimeout(timer);
                const v = this.value.trim();
                timer = setTimeout(() => fetchSuggestions(v), 220);
            });
            input.addEventListener('keydown', function (e) {
                if (container.classList.contains('hidden')) return;
                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if (suggestions.length === 0) return;
                    selectedIndex = (selectedIndex + 1) % suggestions.length;
                    updateActiveItem();
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if (suggestions.length === 0) return;
                    selectedIndex = (selectedIndex - 1 + suggestions.length) % suggestions.length;
                    updateActiveItem();
                } else if (e.key === 'Enter') {
                    if (selectedIndex >= 0 && suggestions[selectedIndex]) {
                        e.preventDefault();
                        goToSuggestion(selectedIndex);
                    } else if (lastQuery.length > 0) {
                        // Submit the "Voir tous les résultats"
                        window.location.href = '/search?q=' + encodeURIComponent(lastQuery);
                    }
                } else if (e.key === 'Escape') {
                    hide();
                }
            });
        }
        document.addEventListener('click', function (e) {
            if (!input.contains(e.target) && !container.contains(e.target)) hide();
        });
    })();

    function changeLanguage(lang) {
        fetch('/language/change', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ language: lang })
        }).then(() => location.reload());
    }

    function changeCurrency(currency) {
        fetch('/currency/change', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ currency })
        }).then(() => location.reload());
    }
    </script>
