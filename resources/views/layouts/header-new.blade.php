@php
    $companyName = config('carre_premium.company.name');
    $supportEmail = config('carre_premium.contact.support_email');
    $mobileDisplay = config('carre_premium.contact.mobile_display');
    $mobileLink = config('carre_premium.contact.mobile_link');
    $landlineDisplay = config('carre_premium.contact.landline_display');
    $landlineLink = config('carre_premium.contact.landline_link');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');
    $currentLocale = strtoupper(session('locale', 'fr'));
    $currentCurrency = session('currency', 'XOF');
    $searchQuery = request('q', '');
    $userName = $user?->name ?: trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''));
    $userName = $userName !== '' ? $userName : __('My Account');
    $userInitial = strtoupper(substr($userName, 0, 1));

    $mainNavigation = [
        [
            'label' => 'Accueil',
            'route' => 'home',
            'patterns' => ['home'],
            'summary' => 'Vue d’ensemble de nos services premium',
        ],
        [
            'label' => 'Événements',
            'route' => 'events',
            'patterns' => ['events', 'events.*'],
            'summary' => 'Billets sportifs et culturels',
        ],
        [
            'label' => 'Packages',
            'route' => 'packages',
            'patterns' => ['packages', 'packages.*'],
            'summary' => 'Séjours et expériences organisées',
        ],
        [
            'label' => 'Location',
            'route' => 'location',
            'patterns' => ['location'],
            'summary' => 'Véhicules premium et sur mesure',
        ],
        [
            'label' => 'Vols accompagnés',
            'route' => 'flights.index',
            'patterns' => ['flights.*'],
            'summary' => 'Demandes traitées avec un conseiller',
        ],
    ];

    $secondaryNavigation = [
        ['label' => 'À propos', 'route' => 'about'],
        ['label' => 'Contact', 'route' => 'contact'],
        ['label' => 'FAQ', 'route' => 'faq'],
        ['label' => 'Partenariat', 'route' => 'partnership'],
    ];

    $isRouteActive = function (array $patterns): bool {
        foreach ($patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    };
@endphp

<div
    x-data="{ mobileMenuOpen: false }"
    @keydown.escape.window="mobileMenuOpen = false"
    class="fixed inset-x-0 top-0 z-50"
>
    <header class="theme-shell-header px-3 pt-3 lg:px-4 lg:pt-4">
        <div class="cp-shell hidden lg:block">
            <div class="cp-glass flex items-center justify-between rounded-[1.75rem] px-5 py-3">
                <div class="flex flex-wrap items-center gap-3 text-sm">
                    <a href="{{ $landlineLink }}" class="cp-pill">
                        <i class="fa-solid fa-phone-volume text-[0.7rem]"></i>
                        <span>{{ $landlineDisplay }}</span>
                    </a>
                    <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-pill">
                        <i class="fa-brands fa-whatsapp text-[0.8rem]"></i>
                        <span>WhatsApp</span>
                    </a>
                    <span class="text-sm font-medium text-[color:var(--cp-ink-muted)]">
                        Vols accompagnés, événements VIP, packages et location premium
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    @foreach($secondaryNavigation as $item)
                        <a href="{{ route($item['route']) }}" class="cp-link-muted rounded-full px-3 py-2 text-sm font-semibold">
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    <div x-data="{ open: false }" class="relative">
                        <button
                            type="button"
                            @click="open = !open"
                            class="cp-icon-button w-auto gap-2 px-3 text-sm font-semibold"
                            aria-label="Changer la langue"
                        >
                            <i class="fa-solid fa-globe text-xs"></i>
                            <span>{{ $currentLocale }}</span>
                        </button>
                        <div
                            x-cloak
                            x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute right-0 mt-3 w-44 rounded-3xl border border-[color:var(--cp-border)] bg-white p-2 shadow-2xl"
                        >
                            <button type="button" onclick="changeLanguage('fr')" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#efe2ff] text-[color:var(--cp-plum-800)]">FR</span>
                                <span>Français</span>
                            </button>
                            <button type="button" onclick="changeLanguage('en')" class="mt-1 flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#efe2ff] text-[color:var(--cp-plum-800)]">EN</span>
                                <span>English</span>
                            </button>
                        </div>
                    </div>

                    <div x-data="{ open: false }" class="relative">
                        <button
                            type="button"
                            @click="open = !open"
                            class="cp-icon-button w-auto gap-2 px-3 text-sm font-semibold"
                            aria-label="Changer la devise"
                        >
                            <i class="fa-solid fa-wallet text-xs"></i>
                            <span>{{ $currentCurrency }}</span>
                        </button>
                        <div
                            x-cloak
                            x-show="open"
                            @click.outside="open = false"
                            x-transition
                            class="absolute right-0 mt-3 w-40 rounded-3xl border border-[color:var(--cp-border)] bg-white p-2 shadow-2xl"
                        >
                            @foreach(['XOF', 'EUR', 'USD', 'GBP'] as $currency)
                                <button type="button" onclick="changeCurrency('{{ $currency }}')" class="flex w-full items-center justify-between rounded-2xl px-4 py-3 text-left text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                    <span>{{ $currency }}</span>
                                    @if($currentCurrency === $currency)
                                        <i class="fa-solid fa-check text-[color:var(--cp-plum-800)]"></i>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cp-shell mt-3">
            <div class="cp-glass rounded-[2rem] px-4 py-4 lg:px-6">
                <div class="flex items-center gap-3 lg:gap-5">
                    <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-3 sm:flex-none">
                        <span class="inline-flex h-12 w-12 flex-none items-center justify-center overflow-hidden rounded-2xl border border-white/50 bg-white shadow-lg">
                            <img src="{{ asset('logos/logo2.jpg') }}" alt="{{ $companyName }}" class="h-full w-full object-cover">
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-base font-black tracking-[0.08em] text-[color:var(--cp-plum-950)] sm:text-lg">{{ strtoupper($companyName) }}</span>
                            <span class="block truncate text-xs font-semibold uppercase tracking-[0.24em] text-[color:var(--cp-ink-muted)]">Travel, Events, Concierge</span>
                        </span>
                    </a>

                    <nav class="hidden lg:flex flex-1 items-center justify-center gap-2">
                        @foreach($mainNavigation as $item)
                            @php($active = $isRouteActive($item['patterns']))
                            <a
                                href="{{ route($item['route']) }}"
                                class="{{ $active ? 'bg-[color:var(--cp-plum-900)] text-white shadow-lg' : 'text-[color:var(--cp-ink-soft)] hover:bg-white/70 hover:text-[color:var(--cp-plum-900)]' }} rounded-full px-4 py-3 text-sm font-extrabold transition"
                                @if($active) aria-current="page" @endif
                            >
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <form action="{{ route('search') }}" method="GET" class="hidden xl:flex min-w-[18rem] max-w-[24rem] flex-1 items-center gap-3 rounded-full border border-[color:var(--cp-border)] bg-white/80 px-4 py-3">
                        <i class="fa-solid fa-magnifying-glass text-sm text-[color:var(--cp-ink-muted)]"></i>
                        <input
                            type="search"
                            name="q"
                            value="{{ $searchQuery }}"
                            placeholder="Rechercher un événement, un service ou un package"
                            class="w-full border-0 bg-transparent p-0 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none placeholder:text-[color:var(--cp-ink-muted)]"
                        >
                    </form>

                    <div class="hidden sm:flex items-center gap-2">
                        <button
                            id="theme-toggle"
                            type="button"
                            class="cp-icon-button"
                            aria-label="Changer le thème"
                            title="Changer le thème"
                        >
                            🌙
                        </button>

                        <a href="{{ route('contact') }}" class="cp-primary-button hidden md:inline-flex">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <span>Parler à un conseiller</span>
                        </a>

                        @if(!$isAuthenticated)
                            <a href="{{ route('login') }}" class="cp-secondary-button hidden md:inline-flex">Connexion</a>
                        @else
                            <div x-data="{ open: false }" class="relative">
                                <button
                                    type="button"
                                    @click="open = !open"
                                    class="flex items-center gap-3 rounded-full border border-[color:var(--cp-border)] bg-white/90 px-3 py-2 text-left shadow-sm transition hover:border-[color:var(--cp-border-strong)]"
                                >
                                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[color:var(--cp-plum-900)] text-sm font-black text-white">
                                        {{ $userInitial }}
                                    </span>
                                    <span class="hidden xl:block">
                                        <span class="block max-w-[11rem] truncate text-sm font-extrabold text-[color:var(--cp-plum-950)]">{{ $userName }}</span>
                                        <span class="block text-xs font-semibold text-[color:var(--cp-ink-muted)]">Compte client</span>
                                    </span>
                                    <i class="fa-solid fa-chevron-down text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </button>

                                <div
                                    x-cloak
                                    x-show="open"
                                    @click.outside="open = false"
                                    x-transition
                                    class="absolute right-0 mt-3 w-64 rounded-[1.75rem] border border-[color:var(--cp-border)] bg-white p-2 shadow-2xl"
                                >
                                    <div class="rounded-[1.3rem] bg-[#f8f4ff] px-4 py-4">
                                        <p class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $userName }}</p>
                                        <p class="mt-1 text-xs font-medium text-[color:var(--cp-ink-muted)]">{{ $user?->email }}</p>
                                    </div>
                                    <div class="mt-2 space-y-1">
                                        <a href="{{ route('profile') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                            <i class="fa-regular fa-user text-[color:var(--cp-plum-800)]"></i>
                                            <span>Mon profil</span>
                                        </a>
                                        <a href="{{ route('bookings') }}" class="flex items-center gap-3 rounded-2xl px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                            <i class="fa-regular fa-calendar-check text-[color:var(--cp-plum-800)]"></i>
                                            <span>Mes réservations</span>
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-3 rounded-2xl px-4 py-3 text-left text-sm font-semibold text-[#b42318] hover:bg-[#fff1f1]">
                                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                                <span>Déconnexion</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <button
                        type="button"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="cp-icon-button lg:hidden"
                        :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                        aria-controls="mobile-main-menu"
                    >
                        <i class="fa-solid fa-bars-staggered text-base" x-show="!mobileMenuOpen"></i>
                        <i class="fa-solid fa-xmark text-base" x-show="mobileMenuOpen" x-cloak></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <div
        x-cloak
        x-show="mobileMenuOpen"
        @click="mobileMenuOpen = false"
        class="fixed inset-0 top-[5.5rem] bg-[#1f1230]/38 backdrop-blur-sm lg:hidden"
    ></div>

    <div class="cp-shell lg:hidden">
        <div
            id="mobile-main-menu"
            x-cloak
            x-show="mobileMenuOpen"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-4"
            class="relative mt-3 rounded-[2rem] border border-[color:var(--cp-border)] bg-[color:var(--cp-panel-strong)] p-5 shadow-2xl"
        >
            <div class="cp-kicker">
                <span class="cp-eyebrow-dot"></span>
                <span>Navigation claire</span>
            </div>

            <form action="{{ route('search') }}" method="GET" class="mt-4 flex items-center gap-3 rounded-[1.4rem] border border-[color:var(--cp-border)] bg-white px-4 py-4">
                <i class="fa-solid fa-magnifying-glass text-sm text-[color:var(--cp-ink-muted)]"></i>
                <input
                    type="search"
                    name="q"
                    value="{{ $searchQuery }}"
                    placeholder="Rechercher un service, un événement ou un package"
                    class="w-full border-0 bg-transparent p-0 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none placeholder:text-[color:var(--cp-ink-muted)]"
                >
            </form>

            <div class="mt-4 grid grid-cols-2 gap-3">
                <a href="{{ $mobileLink }}" class="cp-secondary-button !rounded-[1.2rem] !px-4 !py-4 text-sm">
                    <i class="fa-solid fa-phone"></i>
                    <span>Appeler</span>
                </a>
                <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !rounded-[1.2rem] !px-4 !py-4 text-sm">
                    <i class="fa-brands fa-whatsapp"></i>
                    <span>WhatsApp</span>
                </a>
            </div>

            <div class="mt-6 space-y-2">
                @foreach($mainNavigation as $item)
                    @php($active = $isRouteActive($item['patterns']))
                    <a
                        href="{{ route($item['route']) }}"
                        @click="mobileMenuOpen = false"
                        class="{{ $active ? 'border-[color:var(--cp-plum-800)] bg-[#f4edff]' : 'border-transparent bg-white/75' }} block rounded-[1.5rem] border px-4 py-4 shadow-sm"
                    >
                        <span class="block text-base font-black text-[color:var(--cp-plum-950)]">{{ $item['label'] }}</span>
                        <span class="mt-1 block text-sm text-[color:var(--cp-ink-muted)]">{{ $item['summary'] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="mt-6 rounded-[1.6rem] bg-[#281b37] p-4 text-white">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">Accès direct</p>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    @foreach($secondaryNavigation as $item)
                        <a href="{{ route($item['route']) }}" @click="mobileMenuOpen = false" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold text-white/90 transition hover:bg-white/15">
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                <div class="rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white/80 p-4">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Langue</p>
                    <div class="mt-3 flex gap-2">
                        <button type="button" onclick="changeLanguage('fr')" class="cp-secondary-button !rounded-full !px-4 !py-3 text-sm">FR</button>
                        <button type="button" onclick="changeLanguage('en')" class="cp-secondary-button !rounded-full !px-4 !py-3 text-sm">EN</button>
                    </div>
                </div>

                <div class="rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white/80 p-4">
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Devise</p>
                    <div class="mt-3 flex flex-wrap gap-2">
                        @foreach(['XOF', 'EUR', 'USD', 'GBP'] as $currency)
                            <button type="button" onclick="changeCurrency('{{ $currency }}')" class="cp-secondary-button !rounded-full !px-4 !py-3 text-sm">{{ $currency }}</button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="mt-4 rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white/80 p-4">
                <button id="theme-toggle-mobile" type="button" class="flex w-full items-center justify-between gap-4 rounded-[1.2rem] bg-[#f6f0ff] px-4 py-4 text-left text-sm font-black text-[color:var(--cp-plum-900)]">
                    <span>Changer le thème</span>
                    <span>🌙</span>
                </button>
            </div>

            <div class="mt-6 space-y-3 border-t border-[color:var(--cp-border)] pt-5">
                @if(!$isAuthenticated)
                    <a href="{{ route('login') }}" @click="mobileMenuOpen = false" class="cp-secondary-button !flex !w-full !rounded-[1.25rem] !py-4">Connexion</a>
                    <a href="{{ route('register') }}" @click="mobileMenuOpen = false" class="cp-primary-button !flex !w-full !rounded-[1.25rem] !py-4">Créer un compte</a>
                @else
                    <div class="rounded-[1.5rem] bg-[#f8f4ff] px-4 py-4">
                        <p class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $userName }}</p>
                        <p class="mt-1 text-xs font-medium text-[color:var(--cp-ink-muted)]">{{ $user?->email }}</p>
                    </div>
                    <a href="{{ route('profile') }}" @click="mobileMenuOpen = false" class="cp-secondary-button !flex !w-full !justify-start !rounded-[1.25rem] !py-4">
                        <i class="fa-regular fa-user"></i>
                        <span>Mon profil</span>
                    </a>
                    <a href="{{ route('bookings') }}" @click="mobileMenuOpen = false" class="cp-secondary-button !flex !w-full !justify-start !rounded-[1.25rem] !py-4">
                        <i class="fa-regular fa-calendar-check"></i>
                        <span>Mes réservations</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-[1.25rem] border border-[#f2c9c4] bg-[#fff5f4] px-4 py-4 text-sm font-black text-[#b42318]">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                @endif
            </div>

            <div class="mt-5 rounded-[1.5rem] bg-[#1e112c] px-4 py-4 text-white">
                <p class="text-sm font-black">{{ $mobileDisplay }}</p>
                <p class="mt-1 text-xs font-medium text-white/70">{{ $supportEmail }}</p>
            </div>
        </div>
    </div>
</div>

<script>
    function postPreference(url, payload) {
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        }).then(() => window.location.reload());
    }

    function changeLanguage(language) {
        postPreference('{{ route('language.change') }}', { language });
    }

    function changeCurrency(currency) {
        postPreference('{{ route('currency.change') }}', { currency });
    }
</script>
