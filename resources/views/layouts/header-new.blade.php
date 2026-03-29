@php
    $companyName = config('carre_premium.company.name');
// $cartItemsCount provided from layout
    $supportEmail = config('carre_premium.contact.support_email');
    $mobileDisplay = config('carre_premium.contact.mobile_display');
    $mobileLink = config('carre_premium.contact.mobile_link');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');
    $currentLocale = strtoupper(session('locale', 'fr'));
    $currentCurrency = session('currency', 'XOF');
    $userName = $user?->name ?: trim(($user?->first_name ?? '') . ' ' . ($user?->last_name ?? ''));
    $userName = $userName !== '' ? $userName : __('My Account');
    $userInitial = strtoupper(substr($userName, 0, 1));

    $mainNavigation = [
        ['label' => 'Accueil', 'route' => 'home', 'patterns' => ['home']],
        ['label' => 'Événements', 'route' => 'events', 'patterns' => ['events', 'events.*']],
        ['label' => 'Packages', 'route' => 'packages', 'patterns' => ['packages', 'packages.*']],
        ['label' => 'Location', 'route' => 'location', 'patterns' => ['location', 'location.*']],
        ['label' => 'Vols', 'route' => 'flights.index', 'patterns' => ['flights.*']],
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
@endphp

<div data-header-root class="fixed inset-x-0 top-0 z-50">
    <header class="px-3 pt-3 lg:px-4 lg:pt-4">
        <div class="cp-shell">
            <div class="cp-header-shell" data-header-frame>
                <div class="flex items-center gap-3">
                    <a href="{{ route('home') }}" class="cp-header-brand flex min-w-0 flex-1 items-center gap-2.5 lg:flex-none">
                        <span class="cp-header-brand-mark">
                            <img src="{{ asset('logos/logo2.jpg') }}" alt="{{ $companyName }}" class="h-12 w-12 object-contain lg:h-16 lg:w-16" loading="lazy">
                        </span>
                        <span class="min-w-0">
                            <span class="block truncate text-sm font-black tracking-[0.08em] text-[color:var(--cp-plum-950)] sm:text-[0.95rem]">{{ strtoupper($companyName) }}</span>
                            <span class="hidden truncate text-[10px] font-semibold uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)] xl:block">Events · Travel · Concierge</span>
                        </span>
                    </a>

                    <nav class="hidden flex-1 items-center justify-center gap-1 xl:gap-2 lg:flex">
                        @foreach($mainNavigation as $item)
                            @php($active = $isRouteActive($item['patterns']))
                            <a
                                href="{{ route($item['route']) }}"
                                @class([
                                    'cp-header-link',
                                    'is-active' => $active,
                                ])
                                @if($active) aria-current="page" @endif
                            >
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                        
                        {{-- Cart Button --}}
                        <a href="/cart" class="relative cp-icon-button group" aria-label="Panier ({{ $cartItemsCount }})">
                            <i class="fa-solid fa-bag-shopping text-lg group-hover:text-[color:var(--cp-plum-900)]"></i>
                            @if($cartItemsCount > 0)
                                <span class="absolute -top-1 -right-1 flex size-5 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                    {{ $cartItemsCount }}
                                </span>
                            @endif
                        </a>

                    <div class="hidden items-center gap-2 lg:flex">
                        <a href="{{ route('contact') }}" class="cp-primary-button !px-4 !py-2.5 text-sm">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <span>Conseiller</span>
                        </a>

                        <div class="relative">
                            <button
                                type="button"
                                data-header-toggle="utility-panel"
                                class="cp-icon-button"
                                aria-expanded="false"
                                aria-controls="utility-panel"
                                aria-label="Ouvrir les outils"
                            >
                                <i class="fa-solid fa-sliders text-sm"></i>
                            </button>

                            <div
                                id="utility-panel"
                                data-header-panel
                                class="cp-header-floating-panel absolute right-0 mt-3 hidden w-[19rem] rounded-[1.45rem] border border-[color:var(--cp-border)] bg-white p-4 shadow-2xl"
                            >
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Aide & réglages</p>

                                <div class="mt-3 grid gap-2 sm:grid-cols-2">
                                    <a href="{{ $mobileLink }}" class="cp-secondary-button !justify-start !rounded-[0.95rem] !px-4 !py-3 text-sm">
                                        <i class="fa-solid fa-phone text-sm"></i>
                                        <span>Appeler</span>
                                    </a>
                                    <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !justify-start !rounded-[0.95rem] !px-4 !py-3 text-sm">
                                        <i class="fa-brands fa-whatsapp text-sm"></i>
                                        <span>WhatsApp</span>
                                    </a>
                                </div>

                                <div class="mt-4 rounded-[1.1rem] bg-[#faf6ff] px-4 py-4">
                                    <div class="grid gap-4">
                                        <div>
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Langue</p>
                                            <div class="mt-2 flex gap-2">
                                                <button
                                                    type="button"
                                                    onclick="changeLanguage('fr')"
                                                    @class([
                                                        'cp-secondary-button !min-w-[56px] !px-4 !py-2.5 text-sm',
                                                        '!border-[color:var(--cp-border-strong)] !bg-[#f0e7ff] !text-[color:var(--cp-plum-900)]' => $currentLocale === 'FR',
                                                    ])
                                                >
                                                    FR
                                                </button>
                                                <button
                                                    type="button"
                                                    onclick="changeLanguage('en')"
                                                    @class([
                                                        'cp-secondary-button !min-w-[56px] !px-4 !py-2.5 text-sm',
                                                        '!border-[color:var(--cp-border-strong)] !bg-[#f0e7ff] !text-[color:var(--cp-plum-900)]' => $currentLocale === 'EN',
                                                    ])
                                                >
                                                    EN
                                                </button>
                                            </div>
                                        </div>

                                        <div>
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Devise</p>
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                @foreach(['XOF', 'EUR', 'USD', 'GBP'] as $currency)
                                                    <button
                                                        type="button"
                                                        onclick="changeCurrency('{{ $currency }}')"
                                                        @class([
                                                            'cp-secondary-button !px-3.5 !py-2.5 text-sm',
                                                            '!border-[color:var(--cp-border-strong)] !bg-[#f0e7ff] !text-[color:var(--cp-plum-900)]' => $currentCurrency === $currency,
                                                        ])
                                                    >
                                                        {{ $currency }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <button
                                        id="theme-toggle"
                                        type="button"
                                        class="cp-secondary-button !mt-4 !w-full !justify-between !rounded-[0.95rem] !px-4 !py-3 text-sm"
                                        aria-label="Changer le thème"
                                        title="Changer le thème"
                                    >
                                        <span data-theme-toggle-label>Mode sombre</span>
                                        <span data-theme-toggle-icon>🌙</span>
                                    </button>
                                </div>

                                <div class="mt-4 grid gap-2 sm:grid-cols-2">
                                    @foreach($secondaryNavigation as $item)
                                        <a href="{{ route($item['route']) }}" class="cp-secondary-button !justify-start !rounded-[0.95rem] !px-4 !py-3 text-sm">
                                            <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
                                            <span>{{ $item['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>

                                <div class="mt-4 rounded-[1.1rem] bg-[#1e112c] px-4 py-4 text-white">
                                    <p class="text-sm font-black">{{ $mobileDisplay }}</p>
                                    <p class="mt-1 text-xs font-medium text-white/70">{{ $supportEmail }}</p>
                                </div>
                            </div>
                        </div>

                        @if(!$isAuthenticated)
                            <a href="{{ route('login') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">Connexion</a>
                        @else
                            <div class="relative">
                                <button
                                    type="button"
                                    data-header-toggle="account-panel"
                                    class="flex items-center gap-2 rounded-full border border-[color:var(--cp-border)] bg-white/92 px-2 py-1.5 shadow-sm transition hover:border-[color:var(--cp-border-strong)]"
                                    aria-expanded="false"
                                    aria-controls="account-panel"
                                >
                                    <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-[color:var(--cp-plum-900)] text-xs font-black text-white">
                                        {{ $userInitial }}
                                    </span>
                                    <span class="hidden max-w-[8rem] truncate text-sm font-bold text-[color:var(--cp-plum-950)] xl:block">{{ $userName }}</span>
                                    <i class="fa-solid fa-chevron-down text-[10px] text-[color:var(--cp-ink-muted)]"></i>
                                </button>

                                <div
                                    id="account-panel"
                                    data-header-panel
                                    class="cp-header-floating-panel absolute right-0 mt-3 hidden w-64 rounded-[1.45rem] border border-[color:var(--cp-border)] bg-white p-2 shadow-2xl"
                                >
                                    <div class="rounded-[1.1rem] bg-[#f8f4ff] px-4 py-4">
                                        <p class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $userName }}</p>
                                        <p class="mt-1 text-xs font-medium text-[color:var(--cp-ink-muted)]">{{ $user?->email }}</p>
                                    </div>

                                    <div class="mt-2 space-y-1">
                                        <a href="{{ route('profile') }}" class="flex items-center gap-3 rounded-[1rem] px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                            <i class="fa-regular fa-user text-[color:var(--cp-plum-800)]"></i>
                                            <span>Mon profil</span>
                                        </a>
                                        <a href="{{ route('bookings') }}" class="flex items-center gap-3 rounded-[1rem] px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)] hover:bg-[#f6f0ff]">
                                            <i class="fa-regular fa-calendar-check text-[color:var(--cp-plum-800)]"></i>
                                            <span>Mes réservations</span>
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-3 rounded-[1rem] px-4 py-3 text-left text-sm font-semibold text-[#b42318] hover:bg-[#fff1f1]">
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
                        <a href="/cart" class="relative cp-icon-button" aria-label="Panier">
                            <i class="fa-solid fa-bag-shopping text-sm"></i>
                            @if($cartItemsCount > 0)
                                <span class="absolute -top-0.5 -right-0.5 flex size-4 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white">
                                    {{ $cartItemsCount }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('contact') }}" class="cp-icon-button" aria-label="Contacter un conseiller">
                            <i class="fa-solid fa-headset text-sm"></i>
                        </a>

                        <button
                            type="button"
                            data-header-toggle="mobile-main-menu"
                            class="cp-icon-button"
                            aria-expanded="false"
                            aria-controls="mobile-main-menu"
                            aria-label="Ouvrir le menu principal"
                        >
                            <i class="fa-solid fa-bars text-base" data-menu-icon="open"></i>
                            <i class="fa-solid fa-xmark hidden text-base" data-menu-icon="close"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div
        data-header-overlay
        class="fixed inset-x-0 bottom-0 hidden bg-[#1f1230]/40 backdrop-blur-sm lg:hidden"
        style="top: calc(var(--cp-header-height, 4.9rem) + 0.35rem);"
    ></div>

    <div class="cp-shell lg:hidden">
        <div
            id="mobile-main-menu"
            data-header-panel
            class="cp-mobile-drawer relative mt-3 hidden max-h-[calc(100vh-6rem)] overflow-y-auto p-4"
        >
            <div class="rounded-[1.25rem] bg-gradient-to-br from-[#241233] via-[#4b2870] to-[#d89b43] px-4 py-4 text-white">
                <p class="text-xs font-black uppercase tracking-[0.2em] text-white/65">Navigation</p>
                <p class="mt-2 text-sm font-semibold text-white/86">{{ strtoupper($companyName) }}</p>
                <p class="mt-1 text-sm leading-6 text-white/74">{{ $mobileDisplay }} · {{ $supportEmail }}</p>
            </div>

            <nav class="mt-4 space-y-2">
                @foreach($mainNavigation as $item)
                    @php($active = $isRouteActive($item['patterns']))
                    <a
                        href="{{ route($item['route']) }}"
                        data-header-close
                        @class([
                            'flex items-center justify-between rounded-[1.05rem] border px-4 py-3.5 text-sm font-bold transition',
                            'border-[color:var(--cp-border)] bg-white text-[color:var(--cp-plum-950)]' => !$active,
                            'border-[color:var(--cp-border-strong)] bg-[#f4edff] text-[color:var(--cp-plum-900)] shadow-sm' => $active,
                        ])
                    >
                        <span>{{ $item['label'] }}</span>
                        <i class="fa-solid fa-arrow-right text-xs text-[color:var(--cp-ink-muted)]"></i>
                    </a>
                @endforeach
            </nav>

            <div class="mt-4 grid gap-2 sm:grid-cols-3">
                <a href="{{ route('contact') }}" data-header-close class="cp-primary-button !justify-center !rounded-[1rem] !px-4 !py-3 text-sm">
                    <i class="fa-solid fa-headset text-sm"></i>
                    <span>Conseiller</span>
                </a>
                <a href="{{ $mobileLink }}" class="cp-secondary-button !justify-center !rounded-[1rem] !px-4 !py-3 text-sm">
                    <i class="fa-solid fa-phone text-sm"></i>
                    <span>Appeler</span>
                </a>
                <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !justify-center !rounded-[1rem] !px-4 !py-3 text-sm">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    <span>WhatsApp</span>
                </a>
            </div>

            <div class="mt-4 grid grid-cols-2 gap-2">
                @foreach($secondaryNavigation as $item)
                    <a href="{{ route($item['route']) }}" data-header-close class="cp-secondary-button !justify-start !rounded-[1rem] !px-4 !py-3 text-sm">
                        <i class="fa-solid {{ $item['icon'] }} text-xs"></i>
                        <span>{{ $item['label'] }}</span>
                    </a>
                @endforeach
            </div>

            <div class="mt-4 rounded-[1.2rem] border border-[color:var(--cp-border)] bg-white p-4">
                <div class="grid gap-4">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Langue</p>
                        <div class="mt-3 flex gap-2">
                            <button
                                type="button"
                                onclick="changeLanguage('fr')"
                                @class([
                                    'cp-secondary-button !min-w-[56px] !px-4 !py-2.5 text-sm',
                                    '!border-[color:var(--cp-border-strong)] !bg-[#f0e7ff] !text-[color:var(--cp-plum-900)]' => $currentLocale === 'FR',
                                ])
                            >
                                FR
                            </button>
                            <button
                                type="button"
                                onclick="changeLanguage('en')"
                                @class([
                                    'cp-secondary-button !min-w-[56px] !px-4 !py-2.5 text-sm',
                                    '!border-[color:var(--cp-border-strong)] !bg-[#f0e7ff] !text-[color:var(--cp-plum-900)]' => $currentLocale === 'EN',
                                ])
                            >
                                EN
                            </button>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Devise</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach(['XOF', 'EUR', 'USD', 'GBP'] as $currency)
                                <button
                                    type="button"
                                    onclick="changeCurrency('{{ $currency }}')"
                                    @class([
                                        'cp-secondary-button !px-3.5 !py-2.5 text-sm',
                                        '!border-[color:var(--cp-border-strong)] !bg-[#f0e7ff] !text-[color:var(--cp-plum-900)]' => $currentCurrency === $currency,
                                    ])
                                >
                                    {{ $currency }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <button id="theme-toggle-mobile" type="button" class="cp-secondary-button !mt-4 !w-full !justify-between !rounded-[1rem] !px-4 !py-3 text-sm">
                    <span data-theme-toggle-label>Mode sombre</span>
                    <span data-theme-toggle-icon>🌙</span>
                </button>
            </div>

            <div class="mt-4 space-y-3 border-t border-[color:var(--cp-border)] pt-4">
                @if(!$isAuthenticated)
                    <a href="{{ route('login') }}" data-header-close class="cp-secondary-button !flex !w-full !rounded-[1rem] !py-3 text-sm">Connexion</a>
                    <a href="{{ route('register') }}" data-header-close class="cp-primary-button !flex !w-full !rounded-[1rem] !py-3 text-sm">Créer un compte</a>
                @else
                    <div class="rounded-[1.2rem] bg-[#f8f4ff] px-4 py-4">
                        <p class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $userName }}</p>
                        <p class="mt-1 text-xs font-medium text-[color:var(--cp-ink-muted)]">{{ $user?->email }}</p>
                    </div>

                    <a href="{{ route('profile') }}" data-header-close class="cp-secondary-button !flex !w-full !justify-start !rounded-[1rem] !py-3 text-sm">
                        <i class="fa-regular fa-user"></i>
                        <span>Mon profil</span>
                    </a>
                    <a href="{{ route('bookings') }}" data-header-close class="cp-secondary-button !flex !w-full !justify-start !rounded-[1rem] !py-3 text-sm">
                        <i class="fa-regular fa-calendar-check"></i>
                        <span>Mes réservations</span>
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex w-full items-center justify-center gap-3 rounded-[1rem] border border-[#f2c9c4] bg-[#fff5f4] px-4 py-3 text-sm font-black text-[#b42318]">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                @endif
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
        })
            .then((response) => {
                if (!response.ok) {
                    throw new Error('Preference update failed');
                }

                return response.json();
            })
            .then(() => window.location.reload())
            .catch((error) => {
                console.error(error);
            });
    }

    function changeLanguage(language) {
        postPreference('{{ route('language.change') }}', { language });
    }

    function changeCurrency(currency) {
        postPreference('{{ route('currency.change') }}', { currency });
    }
</script>
