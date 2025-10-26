@php
    // Les variables $isAuthenticated, $user, etc. doivent être passées par l'appelant (@include)
    // ou définies dans le layout principal (app.blade.php) comme montré précédemment.

    // Définition des liens de navigation (similaire au tableau JS)
    $navLinks = [
        [
            'name' => 'Accueil',
            'path' => '/',
            'activeClass' => 'bg-purple-600 text-white shadow-lg shadow-purple-500/30',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>'
        ],
        [
            'name' => 'Vols',
            'path' => '/flights',
            'activeClass' => 'bg-gradient-to-r from-amber-500 to-amber-600 text-white shadow-lg shadow-amber-500/30',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>'
        ],
        [
            'name' => 'Événements',
            'path' => '/events',
            'activeClass' => 'bg-gradient-to-r from-rose-500 to-pink-600 text-white shadow-lg shadow-rose-500/30',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" /></svg>'
        ],
        [
            'name' => 'Packages',
            'path' => '/packages',
            'activeClass' => 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-500/30',
            'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
        ],
    ];

    // Fonction Blade pour déterminer la classe active (simule location.pathname === link.path)
    function isActiveLink($path) {
        return request()->is($path === '/' ? $path : ltrim($path, '/'));
    }

    // Pour l'action du formulaire/bouton, vous devrez créer les routes Laravel
    // pour changer la langue, la devise, le thème et gérer la déconnexion.

@endphp

<header
    x-data="{
        isScrolled: window.scrollY > 50,
        mobileMenuOpen: false,
        userMenuOpen: false,
        currentLanguage: '{{ $currentLanguage }}',
        currentCurrency: '{{ $currentCurrency }}'
    }"
    x-on:scroll.window="isScrolled = window.scrollY > 50"
    @click.away="userMenuOpen = false"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500"
    :class="isScrolled
        ? 'bg-white/80 dark:bg-gray-900/80 backdrop-blur-xl shadow-lg'
        : 'bg-transparent'"
>
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl font-black text-white">C</span>
                </div>
                <div class="hidden md:block">
                    <div class="text-xl font-black bg-gradient-to-r from-purple-600 to-purple-700 bg-clip-text text-transparent">
                        CARRÉ PREMIUM
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 font-medium">
                        Voyages d'Exception
                    </div>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <nav class="hidden lg:flex items-center space-x-2">
                @foreach ($navLinks as $link)
                    <a
                        href="{{ url($link['path']) }}"
                        class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 flex items-center space-x-2"
                        @class([
                            $link['activeClass'] => isActiveLink($link['path']),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' => isActiveLink($link['path']) ? false : true,
                            'text-white hover:bg-white/20 backdrop-blur-md' => isActiveLink($link['path']) ? false : false,
                        ])
                        :class="{
                            'text-white hover:bg-white/20 backdrop-blur-md': !isScrolled && !({{ isActiveLink($link['path']) ? 'true' : 'false' }}),
                            'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800': isScrolled && !({{ isActiveLink($link['path']) ? 'true' : 'false' }})
                        }"
                    >
                        {!! $link['icon'] !!}
                        <span>{{ $link['name'] }}</span>
                    </a>
                @endforeach
            </nav>

            {{-- Right Actions --}}
            <div class="flex items-center space-x-3">
                {{-- Language Selector --}}
                <div class="hidden md:flex items-center space-x-1 bg-white/10 dark:bg-gray-800/50 backdrop-blur-md rounded-full p-1 border border-white/20">
                    <button
                        x-on:click="currentLanguage = 'fr'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300"
                        :class="currentLanguage === 'fr'
                            ? 'bg-purple-600 text-white shadow-lg'
                            : isScrolled
                            ? 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
                            : 'text-white/80 hover:bg-white/20'"
                    >
                        FR
                    </button>
                    <button
                        x-on:click="currentLanguage = 'en'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300"
                        :class="currentLanguage === 'en'
                            ? 'bg-purple-600 text-white shadow-lg'
                            : isScrolled
                            ? 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'
                            : 'text-white/80 hover:bg-white/20'"
                    >
                        EN
                    </button>
                </div>

                {{-- Currency Selector --}}
                <select
                    x-model="currentCurrency"
                    class="hidden md:block px-4 py-2 rounded-full text-sm font-semibold border transition-all duration-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500"
                    :class="isScrolled
                        ? 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-700'
                        : 'bg-white/10 text-white border-white/20 backdrop-blur-md'"
                >
                    <option value="XOF">XOF</option>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                </select>

                {{-- Theme Toggle --}}
                <button
                    x-on:click="theme = theme === 'dark' ? 'light' : 'dark'; $root.parentElement.classList.toggle('dark')"
                    class="p-2.5 rounded-full transition-all duration-300 hover:scale-110"
                    :class="isScrolled
                        ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                        : 'bg-white/10 text-white backdrop-blur-md border border-white/20'"
                    aria-label="Toggle theme"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="theme === 'dark'">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="theme === 'light'">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                    </svg>
                </button>

                {{-- Authentication Links / User Menu --}}
                @if (!$isAuthenticated)
                    <div class="hidden md:flex items-center space-x-3">
                        <a
                            href="{{ route('login') }}"
                            class="px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300"
                            :class="isScrolled
                                ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700'
                                : 'bg-white/10 text-white backdrop-blur-md border border-white/20 hover:bg-white/20'"
                        >
                            Connexion
                        </a>
                        <a
                            href="{{ route('register') }}"
                            class="px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300 bg-purple-600 text-white hover:bg-purple-700"
                        >
                            Inscription
                        </a>
                    </div>
                @else
                    <div class="relative user-menu-container">
                        <button
                            x-on:click="userMenuOpen = !userMenuOpen"
                            class="flex items-center space-x-2 p-2.5 rounded-full transition-all duration-300 hover:scale-110"
                            :class="isScrolled
                                ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                                : 'bg-white/10 text-white backdrop-blur-md border border-white/20'"
                        >
                            <div class="w-6 h-6 bg-purple-600 rounded-full flex items-center justify-center">
                                <span class="text-xs font-bold text-white">
                                    {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                                </span>
                            </div>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        {{-- User Menu Dropdown --}}
                        <div
                            x-show="userMenuOpen"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-900 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-2 z-50 origin-top-right"
                            style="display: none;"
                        >
                            <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                    {{ $user->name ?? 'Utilisateur' }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                    {{ $user->email ?? '' }}
                                </p>
                            </div>
                            <a
                                href="{{ url('/account/profile') }}"
                                x-on:click="userMenuOpen = false"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <i class="fas fa-user mr-2"></i>
                                Mon Profil
                            </a>
                            <a
                                href="{{ url('/account/bookings') }}"
                                x-on:click="userMenuOpen = false"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                            >
                                <i class="fas fa-ticket-alt mr-2"></i>
                                Mes Réservations
                            </a>
                            <form method="POST" action="{{ route('logout') }}" x-on:submit="userMenuOpen = false">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                @endif

                {{-- Cart --}}
                <a
                    href="{{ url('/cart') }}"
                    class="relative p-2.5 rounded-full transition-all duration-300 hover:scale-110"
                    :class="isScrolled
                        ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                        : 'bg-white/10 text-white backdrop-blur-md border border-white/20'"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    @if ($cartItemsCount > 0)
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-yellow-400 text-purple-900 text-xs font-bold rounded-full flex items-center justify-center shadow-lg">
                            {{ $cartItemsCount }}
                        </span>
                    @endif
                </a>

                {{-- Mobile Menu Button --}}
                <button
                    x-on:click="mobileMenuOpen = !mobileMenuOpen"
                    class="lg:hidden p-2.5 rounded-full transition-all duration-300"
                    :class="isScrolled
                        ? 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'
                        : 'bg-white/10 text-white backdrop-blur-md border border-white/20'"
                    aria-label="Toggle mobile menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div
        x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-1"
        class="lg:hidden bg-white dark:bg-gray-900 border-t border-gray-200 dark:border-gray-800 shadow-xl"
        style="display: none;"
    >
        <div class="container mx-auto px-4 py-4 space-y-2">
            @foreach ($navLinks as $link)
                <a
                    href="{{ url($link['path']) }}"
                    x-on:click="mobileMenuOpen = false"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300"
                    @class([
                        $link['activeClass'] => isActiveLink($link['path']),
                        'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' => !isActiveLink($link['path']),
                    ])
                >
                    <span class="text-xl">{!! $link['icon'] !!}</span>
                    <span>{{ $link['name'] }}</span>
                </a>
            @endforeach

            <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-3">
                {{-- Authentication Links Mobile --}}
                @if (!$isAuthenticated)
                    <div class="px-4 space-y-2">
                        <a
                            href="{{ route('login') }}"
                            x-on:click="mobileMenuOpen = false"
                            class="block w-full px-4 py-3 bg-purple-600 text-white rounded-lg font-semibold text-center hover:bg-purple-700 transition-colors"
                        >
                            Connexion
                        </a>
                        <a
                            href="{{ route('register') }}"
                            x-on:click="mobileMenuOpen = false"
                            class="block w-full px-4 py-3 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-lg font-semibold text-center hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors"
                        >
                            Inscription
                        </a>
                    </div>
                @else
                    <div class="px-4 space-y-2">
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-3">
                            <p class="text-sm font-semibold text-gray-800 dark:text-white">
                                {{ $user->name ?? 'Utilisateur' }}
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $user->email ?? '' }}
                            </p>
                        </div>
                        <a
                            href="{{ url('/account/profile') }}"
                            x-on:click="mobileMenuOpen = false"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
                        >
                            <i class="fas fa-user mr-2"></i>
                            Mon Profil
                        </a>
                        <a
                            href="{{ url('/account/bookings') }}"
                            x-on:click="mobileMenuOpen = false"
                            class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg"
                        >
                            <i class="fas fa-ticket-alt mr-2"></i>
                            Mes Réservations
                        </a>
                        <form method="POST" action="{{ route('logout') }}" x-on:submit="mobileMenuOpen = false">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Language Selector Mobile --}}
                <div class="flex items-center justify-between px-4">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Langue</span>
                    <div class="flex space-x-2">
                        <button
                            x-on:click="currentLanguage = 'fr'"
                            class="px-4 py-2 rounded-lg text-sm font-semibold"
                            :class="currentLanguage === 'fr'
                                ? 'bg-purple-600 text-white'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                        >
                            FR
                        </button>
                        <button
                            x-on:click="currentLanguage = 'en'"
                            class="px-4 py-2 rounded-lg text-sm font-semibold"
                            :class="currentLanguage === 'en'
                                ? 'bg-purple-600 text-white'
                                : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300'"
                        >
                            EN
                        </button>
                    </div>
                </div>

                {{-- Currency Selector Mobile --}}
                <div class="flex items-center justify-between px-4">
                    <span class="text-sm font-semibold text-gray-700 dark:text-gray-300">Devise</span>
                    <select
                        x-model="currentCurrency"
                        class="px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-0 focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                        <option value="XOF">XOF</option>
                        <option value="EUR">EUR</option>
                        <option value="USD">USD</option>
                        <option value="GBP">GBP</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</header>
