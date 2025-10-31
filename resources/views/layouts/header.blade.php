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

    // Sous-pages pour le dropdown
    $subPages = [
        [
            'name' => 'Location',
            'path' => '/location',
            'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>'
        ],
        [
            'name' => 'À propos',
            'path' => '/about',
            'icon' => '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
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
        mobileMenuOpen: false,
        userMenuOpen: false,
        currentLanguage: '{{ $currentLanguage }}',
        currentCurrency: '{{ $currentCurrency }}',
        theme: localStorage.getItem('theme') || 'light',
        isScrolled: false,
        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.setItem('theme', this.theme);
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        },
        changeCurrency(currency) {
            fetch('/currency/change', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ currency: currency })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.currentCurrency = currency;
                    // Reload the page to update all prices with new currency
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Erreur lors du changement de devise:', error);
            });
        }
    }"
    x-init="
        const storedTheme = localStorage.getItem('theme') || 'light';
        theme = storedTheme;
        document.documentElement.classList.toggle('dark', storedTheme === 'dark');
    "
    x-on:scroll.window="isScrolled = window.scrollY > 50"
    @click.away="userMenuOpen = false"
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-500 bg-white"
>
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-20">
            {{-- Logo --}}
            <a href="{{ url('/') }}" class="flex items-center space-x-3 group">
                <div class="w-12 h-12 bg-gradient-to-br from-purple-600 to-purple-700 rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <span class="text-2xl font-black text-white">C</span>
                </div>
                <div class="hidden md:block">
                    <div class="text-xl font-black text-gray-900 dark:text-white">
                        CARRÉ PREMIUM
                    </div>
                    <div class="text-xs font-medium text-gray-500 dark:text-gray-400">
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
                            'text-gray-700 hover:bg-gray-100' => !isActiveLink($link['path']),
                        ])
                    >
                        {!! $link['icon'] !!}
                        <span>{{ $link['name'] }}</span>
                    </a>
                @endforeach

                {{-- Dropdown for sub-pages --}}
                <div class="relative" x-data="{ subMenuOpen: false }">
                    <button
                        x-on:click="subMenuOpen = !subMenuOpen"
                        class="px-6 py-2.5 rounded-full font-semibold text-sm transition-all duration-300 flex items-center space-x-2 text-gray-700 hover:bg-gray-100"
                        @click.away="subMenuOpen = false"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                        </svg>
                        <span>Plus</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>

                    {{-- Sub-menu dropdown --}}
                    <div
                        x-show="subMenuOpen"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 origin-top-right"
                        style="display: none;"
                    >
                        @foreach ($subPages as $subPage)
                            <a
                                href="{{ url($subPage['path']) }}"
                                x-on:click="subMenuOpen = false"
                                class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors"
                            >
                                {!! $subPage['icon'] !!}
                                <span>{{ $subPage['name'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            </nav>
            {{-- Right Actions --}}
            <div class="flex items-center space-x-3">
                {{-- Language Selector --}}
                <div class="hidden md:flex items-center space-x-1 rounded-full p-1 border bg-gray-100 border-gray-200 transition-all duration-300">
                    <button
                        x-on:click="currentLanguage = 'fr'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300"
                        :class="currentLanguage === 'fr'
                            ? 'bg-purple-600 text-white shadow-lg'
                            : 'text-gray-600 hover:bg-gray-200'"
                    >
                        FR
                    </button>
                    <button
                        x-on:click="currentLanguage = 'en'"
                        class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all duration-300"
                        :class="currentLanguage === 'en'
                            ? 'bg-purple-600 text-white shadow-lg'
                            : 'text-gray-600 hover:bg-gray-200'"
                    >
                        EN
                    </button>
                </div>

                {{-- Currency Selector --}}
                <select
                    x-model="currentCurrency"
                    x-on:change="changeCurrency($event.target.value)"
                    class="hidden md:block px-4 py-2 rounded-full text-sm font-semibold border bg-gray-100 text-gray-700 border-gray-200 transition-all duration-300 cursor-pointer focus:outline-none focus:ring-2 focus:ring-purple-500"
                >
                    <option value="XOF">XOF</option>
                    <option value="EUR">EUR</option>
                    <option value="USD">USD</option>
                    <option value="GBP">GBP</option>
                </select>



                {{-- Authentication Links / User Menu --}}
                @if (!$isAuthenticated)
                    <div class="hidden md:flex items-center space-x-3">
                        <a
                            href="{{ route('login') }}"
                            class="px-4 py-2 rounded-full font-semibold text-sm transition-all duration-300 bg-gray-100 text-gray-700 hover:bg-gray-200"
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
                        class="flex items-center space-x-2 p-2.5 rounded-full bg-gray-100 text-gray-700 transition-all duration-300 hover:scale-110"
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
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50 origin-top-right"
                            style="display: none;"
                        >
                            <div class="px-4 py-2 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-800">
                                    {{ $user->name ?? 'Utilisateur' }}
                                </p>
                                <p class="text-xs text-gray-600">
                                    {{ $user->email ?? '' }}
                                </p>
                            </div>
                            <a
                                href="{{ route('profile') }}"
                                x-on:click="userMenuOpen = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <i class="fas fa-user mr-2"></i>
                                Mon Profil
                            </a>
                            <a
                                href="{{ url('/account/bookings') }}"
                                x-on:click="userMenuOpen = false"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                            >
                                <i class="fas fa-ticket-alt mr-2"></i>
                                Mes Réservations
                            </a>
                            <form method="POST" action="{{ route('logout') }}" x-on:submit="userMenuOpen = false">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
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
                    class="relative p-2.5 rounded-full bg-gray-100 text-gray-700 transition-all duration-300 hover:scale-110 hover:bg-gray-200"
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
                    class="lg:hidden p-2.5 rounded-full bg-gray-100 text-gray-700 transition-all duration-300 hover:scale-110 hover:bg-gray-200"
                    aria-label="Toggle mobile menu"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="mobileMenuOpen" x-transition:enter="transition ease-out duration-200" x-transition:leave="transition ease-in duration-150">
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
        class="lg:hidden bg-white border-t border-gray-200 shadow-xl"
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
                        'text-gray-700 hover:bg-gray-100' => !isActiveLink($link['path']),
                    ])
                >
                    <span class="text-xl">{!! $link['icon'] !!}</span>
                    <span>{{ $link['name'] }}</span>
                </a>
            @endforeach

            {{-- Sub-pages in mobile menu --}}
            @foreach ($subPages as $subPage)
                <a
                    href="{{ url($subPage['path']) }}"
                    x-on:click="mobileMenuOpen = false"
                    class="flex items-center space-x-3 px-4 py-3 rounded-xl font-semibold transition-all duration-300 text-gray-700 hover:bg-gray-100"
                >
                    <span class="text-xl">{!! $subPage['icon'] !!}</span>
                    <span>{{ $subPage['name'] }}</span>
                </a>
            @endforeach

            <div class="pt-4 border-t border-gray-200 space-y-3">
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
                            class="block w-full px-4 py-3 bg-gray-100 text-gray-700 rounded-lg font-semibold text-center hover:bg-gray-200 transition-colors"
                        >
                            Inscription
                        </a>
                    </div>
                @else
                    <div class="px-4 space-y-2">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm font-semibold text-gray-800">
                                {{ $user->name ?? 'Utilisateur' }}
                            </p>
                            <p class="text-xs text-gray-600">
                                {{ $user->email ?? '' }}
                            </p>
                        </div>
                        <a
                            href="{{ route('profile') }}"
                            x-on:click="mobileMenuOpen = false"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg"
                        >
                            <i class="fas fa-user mr-2"></i>
                            Mon Profil
                        </a>
                        <a
                            href="{{ url('/account/bookings') }}"
                            x-on:click="mobileMenuOpen = false"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 rounded-lg"
                        >
                            <i class="fas fa-ticket-alt mr-2"></i>
                            Mes Réservations
                        </a>
                        <form method="POST" action="{{ route('logout') }}" x-on:submit="mobileMenuOpen = false">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 rounded-lg">
                                <i class="fas fa-sign-out-alt mr-2"></i>
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @endif

                {{-- Language Selector Mobile --}}
                <div class="flex items-center justify-between px-4">
                    <span class="text-sm font-semibold text-gray-700">Langue</span>
                    <div class="flex space-x-2">
                        <button
                            x-on:click="currentLanguage = 'fr'"
                            class="px-4 py-2 rounded-lg text-sm font-semibold"
                            :class="currentLanguage === 'fr'
                                ? 'bg-purple-600 text-white'
                                : 'bg-gray-100 text-gray-700'"
                        >
                            FR
                        </button>
                        <button
                            x-on:click="currentLanguage = 'en'"
                            class="px-4 py-2 rounded-lg text-sm font-semibold"
                            :class="currentLanguage === 'en'
                                ? 'bg-purple-600 text-white'
                                : 'bg-gray-100 text-gray-700'"
                        >
                            EN
                        </button>
                    </div>
                </div>

                {{-- Currency Selector Mobile --}}
                <div class="flex items-center justify-between px-4">
                    <span class="text-sm font-semibold text-gray-700">Devise</span>
                    <select
                        x-model="currentCurrency"
                        x-on:change="changeCurrency($event.target.value)"
                        class="px-4 py-2 rounded-lg text-sm font-semibold bg-gray-100 text-gray-700 border-0 focus:outline-none focus:ring-2 focus:ring-purple-500"
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
