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
    $userName = $user?->name ?: trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''));
    $userName = $userName !== '' ? $userName : __('My Account');
    $userInitial = strtoupper(substr($userName, 0, 1));

    $mainNavigation = [
        [
            'label' => 'Accueil',
            'route' => 'home',
            'patterns' => ['home'],
            'summary' => 'Voir d’un coup d’œil les services, les accès rapides et la logique générale du site.',
            'highlight' => 'Commencer ici',
            'icon' => 'fa-house',
        ],
        [
            'label' => 'Événements',
            'route' => 'events',
            'patterns' => ['events', 'events.*'],
            'summary' => 'Trouver un billet ou une expérience VIP sportive et culturelle, puis réserver simplement.',
            'highlight' => 'Billets & VIP',
            'icon' => 'fa-ticket',
        ],
        [
            'label' => 'Packages',
            'route' => 'packages',
            'patterns' => ['packages', 'packages.*'],
            'summary' => 'Comparer les séjours et expériences déjà structurés, avec prix et inclusions lisibles.',
            'highlight' => 'Séjours organisés',
            'icon' => 'fa-suitcase-rolling',
        ],
        [
            'label' => 'Location',
            'route' => 'location',
            'patterns' => ['location', 'location.*'],
            'summary' => 'Choisir un véhicule premium, avec ou sans chauffeur, sans se perdre dans la présentation.',
            'highlight' => 'Mobilité premium',
            'icon' => 'fa-car-side',
        ],
        [
            'label' => 'Vols accompagnés',
            'route' => 'flights.index',
            'patterns' => ['flights.*'],
            'summary' => 'Envoyer une demande de vol accompagnée par un conseiller, pas un moteur opaque.',
            'highlight' => 'Demande assistée',
            'icon' => 'fa-plane-up',
        ],
    ];

    $secondaryNavigation = [
        ['label' => 'Contact', 'route' => 'contact', 'icon' => 'fa-envelope'],
        ['label' => 'FAQ', 'route' => 'faq', 'icon' => 'fa-circle-question'],
        ['label' => 'À propos', 'route' => 'about', 'icon' => 'fa-building'],
        ['label' => 'Partenariat', 'route' => 'partnership', 'icon' => 'fa-handshake'],
    ];

    $isRouteActive = function (array $patterns): bool {
        foreach ($patterns as $pattern) {
            if (request()->routeIs($pattern)) {
                return true;
            }
        }

        return false;
    };

    $activeNavigationItem = collect($mainNavigation)->first(fn (array $item) => $isRouteActive($item['patterns'])) ?? $mainNavigation[0];
@endphp

<div
    x-data="{ mobileMenuOpen: false }"
    @keydown.escape.window="mobileMenuOpen = false"
    class="fixed inset-x-0 top-0 z-50"
>
    <header class="theme-shell-header px-3 pt-3 lg:px-4 lg:pt-4">
        <div class="cp-shell">
            <div class="cp-glass rounded-[2.2rem] px-4 py-4 lg:px-6 lg:py-5">
                <div class="flex items-center gap-3 lg:gap-5">
                    <a href="{{ route('home') }}" class="flex min-w-0 flex-1 items-center gap-3">
                        <span class="inline-flex h-12 w-12 flex-none items-center justify-center overflow-hidden rounded-2xl border border-white/50 bg-white shadow-lg">
                            <img src="{{ asset('logos/logo2.jpg') }}" alt="{{ $companyName }}" class="h-full w-full object-cover">
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-base font-black tracking-[0.08em] text-[color:var(--cp-plum-950)] sm:text-lg">{{ strtoupper($companyName) }}</span>
                            <span class="block truncate text-[11px] font-semibold uppercase tracking-[0.24em] text-[color:var(--cp-ink-muted)]">Travel, Events, Concierge</span>
                        </span>
                    </a>

                    <div class="hidden min-w-0 flex-1 xl:flex">
                        <div class="w-full rounded-[1.4rem] border border-[color:var(--cp-border)] bg-white/74 px-4 py-3">
                            <div class="cp-kicker">
                                <span class="cp-eyebrow-dot"></span>
                                <span>Navigation simple</span>
                            </div>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                <span class="font-black text-[color:var(--cp-plum-950)]">{{ $activeNavigationItem['label'] }}</span>
                                :
                                {{ $activeNavigationItem['summary'] }}
                            </p>
                        </div>
                    </div>

                    <div class="hidden items-center gap-2 lg:flex">
                        <a href="{{ route('contact') }}" class="cp-primary-button !px-4 !py-3">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <span>Parler à un conseiller</span>
                        </a>

                        <button
                            id="theme-toggle"
                            type="button"
                            class="cp-icon-button"
                            aria-label="Changer le thème"
                            title="Changer le thème"
                        >
                            🌙
                        </button>

                        <div x-data="{ open: false }" class="relative">
                            <button
                                type="button"
                                @click="open = !open"
                                class="cp-secondary-button !px-4 !py-3"
                                aria-label="Préférences"
                            >
                                <i class="fa-solid fa-sliders text-sm"></i>
                                <span>FR · {{ $currentCurrency }}</span>
                            </button>

                            <div
                                x-cloak
                                x-show="open"
                                @click.outside="open = false"
                                x-transition
                                class="absolute right-0 mt-3 w-72 rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white p-3 shadow-2xl"
                            >
                                <div class="rounded-[1.3rem] bg-[#faf6ff] px-4 py-4">
                                    <p class="text-sm font-black text-[color:var(--cp-plum-950)]">Personnaliser l’affichage</p>
                                    <p class="mt-1 text-xs font-medium text-[color:var(--cp-ink-muted)]">Langue {{ $currentLocale }} · Devise {{ $currentCurrency }}</p>
                                </div>

                                <div class="mt-3">
                                    <p class="px-2 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Langue</p>
                                    <div class="mt-2 flex gap-2">
                                        <button type="button" onclick="changeLanguage('fr')" class="cp-secondary-button !px-4 !py-3 text-sm">FR</button>
                                        <button type="button" onclick="changeLanguage('en')" class="cp-secondary-button !px-4 !py-3 text-sm">EN</button>
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <p class="px-2 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Devise</p>
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach(['XOF', 'EUR', 'USD', 'GBP'] as $currency)
                                            <button type="button" onclick="changeCurrency('{{ $currency }}')" class="cp-secondary-button !px-4 !py-3 text-sm">
                                                {{ $currency }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if(!$isAuthenticated)
                            <a href="{{ route('login') }}" class="cp-secondary-button">Connexion</a>
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

                    <div class="flex items-center gap-2 lg:hidden">
                        <a href="{{ route('contact') }}" class="cp-secondary-button !px-4 !py-3 text-sm">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <span>Conseiller</span>
                        </a>

                        <button
                            type="button"
                            @click="mobileMenuOpen = !mobileMenuOpen"
                            class="cp-icon-button"
                            :aria-expanded="mobileMenuOpen ? 'true' : 'false'"
                            aria-controls="mobile-main-menu"
                        >
                            <i class="fa-solid fa-bars-staggered text-base" x-show="!mobileMenuOpen"></i>
                            <i class="fa-solid fa-xmark text-base" x-show="mobileMenuOpen" x-cloak></i>
                        </button>
                    </div>
                </div>

                <div class="mt-4 grid gap-3 lg:grid-cols-[minmax(0,1.2fr)_320px] lg:items-start">
                    <nav class="hidden gap-3 lg:grid lg:grid-cols-3 xl:grid-cols-5">
                        @foreach($mainNavigation as $item)
                            @php($active = $isRouteActive($item['patterns']))
                            <a
                                href="{{ route($item['route']) }}"
                                @class([
                                    'cp-nav-card p-4',
                                    'is-active' => $active,
                                ])
                                @if($active) aria-current="page" @endif
                            >
                                <div class="flex items-start justify-between gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-[1rem] bg-[#f6f0ff] text-[color:var(--cp-plum-800)]">
                                        <i class="fa-solid {{ $item['icon'] }} text-sm"></i>
                                    </span>
                                    <span class="rounded-full bg-white/80 px-2.5 py-1 text-[10px] font-black uppercase tracking-[0.16em] text-[color:var(--cp-ink-muted)]">
                                        {{ $item['highlight'] }}
                                    </span>
                                </div>
                                <p class="mt-4 text-base font-black text-[color:var(--cp-plum-950)]">{{ $item['label'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-muted)]">{{ $item['summary'] }}</p>
                            </a>
                        @endforeach
                    </nav>

                    <div class="hidden rounded-[1.75rem] border border-[color:var(--cp-border)] bg-white/80 p-4 lg:block">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">Commencer ici</p>
                        <h2 class="mt-3 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $activeNavigationItem['label'] }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $activeNavigationItem['summary'] }}</p>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @foreach($secondaryNavigation as $item)
                                <a href="{{ route($item['route']) }}" class="cp-secondary-button !rounded-full !px-4 !py-2.5 text-sm">
                                    <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            @endforeach
                        </div>

                        <div class="mt-4 grid gap-2 sm:grid-cols-2">
                            <a href="{{ $landlineLink }}" class="cp-utility-badge !justify-start">
                                <i class="fa-solid fa-phone-volume text-sm"></i>
                                <span>{{ $landlineDisplay }}</span>
                            </a>
                            <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-utility-badge !justify-start">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[1.4rem] border border-[color:var(--cp-border)] bg-white/74 px-4 py-4 text-sm text-[color:var(--cp-ink-soft)] lg:hidden">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">Votre besoin principal</p>
                                <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $activeNavigationItem['label'] }}</p>
                                <p class="mt-2 leading-6">{{ $activeNavigationItem['summary'] }}</p>
                            </div>
                            <div class="flex gap-2">
                                <a href="{{ $mobileLink }}" class="cp-icon-button">
                                    <i class="fa-solid fa-phone text-sm"></i>
                                </a>
                                <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-icon-button">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div
        x-cloak
        x-show="mobileMenuOpen"
        @click="mobileMenuOpen = false"
        class="fixed inset-0 top-[7.75rem] bg-[#1f1230]/40 backdrop-blur-sm lg:hidden"
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
            class="relative mt-3 max-h-[calc(100vh-8.75rem)] overflow-y-auto rounded-[2rem] border border-[color:var(--cp-border)] bg-[color:var(--cp-panel-strong)] p-5 shadow-2xl"
        >
            <div class="overflow-hidden rounded-[1.9rem] bg-gradient-to-br from-[#241233] via-[#4c2872] to-[#d89b43] p-5 text-white">
                <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                    <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                    <span>Choisir simplement</span>
                </div>

                <h2 class="mt-3 text-2xl font-black leading-tight">
                    Dites d’abord ce que vous cherchez.
                </h2>

                <p class="mt-3 text-sm leading-7 text-white/80">
                    Le site est organisé autour de quatre besoins simples : billets d’événements, packages, location premium et vols accompagnés.
                </p>

                <div class="mt-4 grid grid-cols-2 gap-3">
                    <a href="{{ $mobileLink }}" class="cp-secondary-button !rounded-[1.2rem] !border-white/20 !bg-white/12 !px-4 !py-4 !text-white hover:!bg-white/16">
                        <i class="fa-solid fa-phone text-sm"></i>
                        <span>Appeler</span>
                    </a>
                    <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !rounded-[1.2rem] !border-white/20 !bg-white/12 !px-4 !py-4 !text-white hover:!bg-white/16">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>WhatsApp</span>
                    </a>
                </div>
            </div>

            <div class="mt-6">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">Je veux</p>
                <div class="mt-3 space-y-3">
                    @foreach($mainNavigation as $item)
                        @php($active = $isRouteActive($item['patterns']))
                        <a
                            href="{{ route($item['route']) }}"
                            @click="mobileMenuOpen = false"
                            @class([
                                'cp-nav-card',
                                'is-active' => $active,
                            ])
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex min-w-0 gap-3">
                                    <span class="inline-flex h-11 w-11 flex-none items-center justify-center rounded-[1rem] bg-[#f6f0ff] text-[color:var(--cp-plum-800)]">
                                        <i class="fa-solid {{ $item['icon'] }} text-sm"></i>
                                    </span>
                                    <div class="min-w-0">
                                        <span class="block text-base font-black text-[color:var(--cp-plum-950)]">{{ $item['label'] }}</span>
                                        <span class="mt-1 block text-sm leading-6 text-[color:var(--cp-ink-muted)]">{{ $item['summary'] }}</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-arrow-right mt-1 text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 rounded-[1.6rem] bg-[#281b37] p-4 text-white">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">Aide rapide</p>
                <div class="mt-3 grid grid-cols-2 gap-2">
                    @foreach($secondaryNavigation as $item)
                        <a href="{{ route($item['route']) }}" @click="mobileMenuOpen = false" class="rounded-2xl bg-white/10 px-4 py-3 text-sm font-bold text-white/90 transition hover:bg-white/15">
                            <span class="flex items-center gap-2">
                                <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
                                <span>{{ $item['label'] }}</span>
                            </span>
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
                            <button type="button" onclick="changeCurrency('{{ $currency }}')" class="cp-secondary-button !rounded-full !px-4 !py-3 text-sm">
                                {{ $currency }}
                            </button>
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
