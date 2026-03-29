@extends('layouts.app')

@section('title', __('Événements sportifs et culturels VIP') . ' - Carré Premium')
@section('meta_description', 'Découvrez les événements sportifs et culturels VIP avec Carré Premium. Billets premium pour concerts, matchs de football, spectacles à Abidjan et Côte d\'Ivoire. Réservation exclusive.')
@section('meta_keywords', 'événements VIP, sports, culture, concerts, football, Côte d\'Ivoire, Abidjan, billets premium, Carré Premium')
@section('og_title', __('Événements sportifs et culturels VIP') . ' - Carré Premium')
@section('og_description', 'Réservez vos places pour les meilleurs événements sportifs et culturels en Côte d\'Ivoire avec Carré Premium. Service VIP exclusif.')

@push('styles')
<style>
    .event-filters-summary::-webkit-details-marker {
        display: none;
    }

    details[open] .event-filters-chevron {
        transform: rotate(180deg);
    }
</style>
@endpush

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $searchTerm = trim((string) request('q'));

    $familyTabs = [
        [
            'value' => 'all',
            'label' => $t('Voir tout', 'See all'),
            'description' => $t('Tous les événements Carré Premium dans une seule vue.', 'All Carré Premium events in one view.'),
            'icon' => 'fa-layer-group',
        ],
        [
            'value' => 'sportif',
            'label' => $t('Sportif', 'Sports'),
            'description' => $t('Football, tennis, sports mécaniques et grands rendez-vous VIP.', 'Football, tennis, motorsport and premium fixtures.'),
            'icon' => 'fa-futbol',
        ],
        [
            'value' => 'culturel',
            'label' => $t('Culturel', 'Cultural'),
            'description' => $t('Concerts, spectacles, festivals et expériences live.', 'Concerts, shows, festivals and live experiences.'),
            'icon' => 'fa-masks-theater',
        ],
    ];

    $familyLabels = collect($familyTabs)->mapWithKeys(fn ($tab) => [$tab['value'] => $tab['label']])->all();

    $selectedCategory = request('category')
        ? $categories->firstWhere('id', (int) request('category'))
        : null;

    $eventsUrl = function (array $overrides = [], array $remove = []) {
        $query = request()->except(array_merge(['page'], $remove));

        foreach ($overrides as $key => $value) {
            if ($value === null || $value === '') {
                unset($query[$key]);
                continue;
            }

            $query[$key] = $value;
        }

        return route('events', $query);
    };

    $activeFilters = collect([
        $selectedFamily !== 'all'
            ? ['label' => $t('Famille', 'Family'), 'value' => $familyLabels[$selectedFamily] ?? ucfirst($selectedFamily)]
            : null,
        $searchTerm !== ''
            ? ['label' => $t('Recherche', 'Search'), 'value' => $searchTerm]
            : null,
        $selectedCategory
            ? ['label' => $t('Catégorie', 'Category'), 'value' => $selectedCategory->name_fr]
            : null,
        request('city')
            ? ['label' => $t('Ville', 'City'), 'value' => request('city')]
            : null,
        request('country')
            ? ['label' => $t('Pays', 'Country'), 'value' => request('country')]
            : null,
        request('venue')
            ? ['label' => $t('Lieu', 'Venue'), 'value' => request('venue')]
            : null,
        request('date')
            ? ['label' => $t('Date', 'Date'), 'value' => request('date')]
            : null,
        request('start_date')
            ? ['label' => $t('Du', 'From'), 'value' => request('start_date')]
            : null,
        request('end_date')
            ? ['label' => $t('Au', 'To'), 'value' => request('end_date')]
            : null,
        request('min_price')
            ? ['label' => $t('Budget min', 'Min budget'), 'value' => request('min_price')]
            : null,
        request('max_price')
            ? ['label' => $t('Budget max', 'Max budget'), 'value' => request('max_price')]
            : null,
        request()->boolean('featured')
            ? ['label' => $t('Sélection', 'Selection'), 'value' => $t('À la une', 'Featured')]
            : null,
        request()->boolean('available_only')
            ? ['label' => $t('Disponibilité', 'Availability'), 'value' => $t('Places disponibles', 'Available only')]
            : null,
        $selectedSort !== 'soonest'
            ? ['label' => $t('Tri', 'Sort'), 'value' => $sortOptions[$selectedSort] ?? $selectedSort]
            : null,
    ])->filter()->values();

    $resetUrl = route('events', $viewMode === 'calendar'
        ? ['view' => 'calendar', 'month' => $calendarMonth->format('Y-m')]
        : []);

    $monthLabel = $calendarMonth->translatedFormat('F Y');
    $weekDays = [
        $t('Lun', 'Mon'),
        $t('Mar', 'Tue'),
        $t('Mer', 'Wed'),
        $t('Jeu', 'Thu'),
        $t('Ven', 'Fri'),
        $t('Sam', 'Sat'),
        $t('Dim', 'Sun'),
    ];

    $featuredEventTitle = $featuredEvent?->title;
    $featuredEventDescription = $featuredEvent
        ? \Illuminate\Support\Str::limit(
            app()->getLocale() === 'fr'
                ? ($featuredEvent->description_fr ?? '')
                : ($featuredEvent->description_en ?? $featuredEvent->description_fr ?? ''),
            180
        )
        : null;
    $featuredEventFamilyLabel = $featuredEvent ? ($familyLabels[$featuredEvent->family] ?? ucfirst($featuredEvent->family)) : null;
@endphp

<div class="min-h-screen">
    <section class="relative overflow-hidden bg-gradient-to-r from-purple-700 via-purple-600 to-amber-500">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute -left-20 top-12 h-56 w-56 rounded-full bg-white/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-amber-300/20 blur-3xl"></div>

        <div class="cp-shell relative z-10 py-14 md:py-20">
            <div class="grid gap-10 lg:grid-cols-[minmax(0,1.3fr)_minmax(320px,420px)] lg:items-end">
                <div class="max-w-4xl">
                    <p class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.28em] text-white/90 backdrop-blur">
                        <span class="h-2 w-2 rounded-full bg-amber-300"></span>
                        {{ $t('Billetterie événements VIP', 'VIP event ticketing') }}
                    </p>

                    <h1 class="mt-5 text-3xl font-black leading-tight text-white sm:text-4xl md:text-5xl">
                        {{ $t('Tous vos événements premium dans une page plus claire, plus élégante et plus simple à parcourir.', 'All your premium events in a clearer, more elegant and easier page to browse.') }}
                    </h1>

                    <p class="mt-4 max-w-3xl text-sm text-white/85 sm:text-base md:text-lg">
                        {{ $t('Retrouvez les expériences sportives et culturelles avec une navigation plus proche du reste du site: hero marque, filtres lisibles, cartes premium et calendrier mieux intégré.', 'Find sports and cultural experiences with a flow that matches the rest of the site: branded hero, readable filters, premium cards and a better integrated calendar.') }}
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-3xl border border-white/20 bg-white/10 p-5 text-white backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-white/70">{{ $t('Résultats', 'Results') }}</p>
                        <p class="mt-3 text-3xl font-black">{{ number_format($events->total(), 0, ',', ' ') }}</p>
                        <p class="mt-1 text-sm text-white/80">{{ $t('événements visibles avec vos filtres', 'events visible with your filters') }}</p>
                    </div>

                    <div class="rounded-3xl border border-white/20 bg-white/10 p-5 text-white backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-white/70">{{ $t('Mode actif', 'Active mode') }}</p>
                        <p class="mt-3 text-2xl font-black">{{ $viewMode === 'calendar' ? $t('Calendrier', 'Calendar') : $t('Liste', 'List') }}</p>
                        <p class="mt-1 text-sm text-white/80">
                            {{ $viewMode === 'calendar' ? $monthLabel : $t('Affichage par cartes', 'Card display') }}
                        </p>
                    </div>

                    <div class="rounded-3xl border border-white/20 bg-white/10 p-5 text-white backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-white/70">{{ $t('Sélection', 'Selection') }}</p>
                        <p class="mt-3 text-2xl font-black">{{ $familyLabels[$selectedFamily] ?? $t('Voir tout', 'See all') }}</p>
                        <p class="mt-1 text-sm text-white/80">
                            {{ number_format($familyCounts[$selectedFamily] ?? $familyCounts['all'] ?? 0, 0, ',', ' ') }}
                            {{ $t('événements dans cette sélection', 'events in this selection') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="relative -mt-8 bg-gray-50 pb-10 md:-mt-10 md:pb-14">
        <div class="cp-shell">
            <div class="mx-auto max-w-7xl rounded-3xl bg-white shadow-2xl shadow-purple-100/70 ring-1 ring-gray-100">
                <div class="flex flex-col gap-6 border-b border-gray-100 px-5 py-6 md:px-8 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-purple-600">{{ $t('Explorer les événements', 'Browse events') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-gray-900 md:text-3xl">{{ $t('Filtres et modes d’affichage', 'Filters and display modes') }}</h2>
                        <p class="mt-2 text-sm text-gray-500 md:text-base">
                            {{ $t('La page garde maintenant une présentation cohérente avec le reste du site, sans perdre la logique de filtres ni la bascule liste/calendrier.', 'The page now matches the rest of the site without losing the filter logic or the list/calendar switch.') }}
                        </p>
                    </div>

                    <div class="inline-flex rounded-2xl bg-gray-100 p-1">
                        <a
                            href="{{ $eventsUrl(['view' => 'list', 'month' => null]) }}"
                            @class([
                                'inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-bold transition-all',
                                'bg-gradient-to-r from-purple-600 to-amber-500 text-white shadow-lg shadow-purple-200' => $viewMode === 'list',
                                'text-gray-600 hover:text-gray-900' => $viewMode !== 'list',
                            ])
                        >
                            <i class="fa-solid fa-table-list text-xs"></i>
                            <span>{{ $t('Liste', 'List') }}</span>
                        </a>
                        <a
                            href="{{ $eventsUrl(['view' => 'calendar', 'month' => $calendarMonth->format('Y-m')]) }}"
                            @class([
                                'inline-flex items-center gap-2 rounded-2xl px-4 py-2.5 text-sm font-bold transition-all',
                                'bg-gradient-to-r from-purple-600 to-amber-500 text-white shadow-lg shadow-purple-200' => $viewMode === 'calendar',
                                'text-gray-600 hover:text-gray-900' => $viewMode !== 'calendar',
                            ])
                        >
                            <i class="fa-regular fa-calendar-days text-xs"></i>
                            <span>{{ $t('Calendrier', 'Calendar') }}</span>
                        </a>
                    </div>
                </div>

                <div class="space-y-6 px-5 py-6 md:px-8 md:py-8">
                    <details class="overflow-hidden rounded-[32px] border border-gray-200 bg-white shadow-sm" @if($activeFilterCount > 0) open @endif>
                        <summary class="event-filters-summary cursor-pointer list-none p-4 md:p-5">
                            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                                <div class="flex items-start gap-4">
                                    <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 text-white shadow-lg shadow-purple-100">
                                        <i class="fa-solid fa-sliders text-lg"></i>
                                    </span>

                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-purple-600">{{ $t('Filtres', 'Filters') }}</p>
                                        <h3 class="mt-1 text-xl font-black text-gray-900 md:text-2xl">{{ $t('Ouvrir le panneau de filtres', 'Open the filter panel') }}</h3>
                                        <p class="mt-2 text-sm text-gray-500">
                                            @if($activeFilterCount > 0)
                                                {{ $activeFilterCount }}
                                                {{ $activeFilterCount > 1 ? $t('filtres sont déjà actifs. Touchez ici pour les modifier.', 'filters are already active. Tap here to adjust them.') : $t('filtre est déjà actif. Touchez ici pour le modifier.', 'filter is already active. Tap here to adjust it.') }}
                                            @else
                                                {{ $t('La page reste plus légère au premier regard. Touchez l’icône pour afficher tous les critères.', 'The page stays lighter at first glance. Tap the icon to reveal all criteria.') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <div class="flex items-center gap-3">
                                    <span class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-gray-50 px-4 py-2 text-sm font-bold text-gray-700">
                                        <i class="fa-solid fa-filter text-xs text-purple-600"></i>
                                        <span>
                                            @if($activeFilterCount > 0)
                                                {{ $activeFilterCount }} {{ $activeFilterCount > 1 ? $t('actifs', 'active') : $t('actif', 'active') }}
                                            @else
                                                {{ $t('Afficher', 'Show') }}
                                            @endif
                                        </span>
                                    </span>

                                    <span class="flex h-11 w-11 items-center justify-center rounded-full border border-gray-200 bg-white text-gray-500 transition-transform duration-300 event-filters-chevron">
                                        <i class="fa-solid fa-chevron-down text-sm"></i>
                                    </span>
                                </div>
                            </div>
                        </summary>

                        <div class="space-y-6 border-t border-gray-100 px-4 py-5 md:px-5 md:py-6">
                            <div class="grid gap-4 lg:grid-cols-3">
                                @foreach($familyTabs as $tab)
                                    @php
                                        $isAllTab = $tab['value'] === 'all';
                                        $tabUrl = $isAllTab
                                            ? $eventsUrl(
                                                ['view' => $viewMode, 'month' => $viewMode === 'calendar' ? $calendarMonth->format('Y-m') : null],
                                                ['family', 'type', 'category']
                                            )
                                            : $eventsUrl([
                                                'family' => $tab['value'],
                                                'type' => null,
                                                'category' => null,
                                                'view' => $viewMode,
                                                'month' => $viewMode === 'calendar' ? $calendarMonth->format('Y-m') : null,
                                            ]);
                                    @endphp

                                    <a
                                        href="{{ $tabUrl }}"
                                        @class([
                                            'group rounded-3xl border p-5 transition-all duration-300',
                                            'border-transparent bg-gradient-to-r from-purple-600 to-amber-500 text-white shadow-xl shadow-purple-100' => $selectedFamily === $tab['value'],
                                            'border-gray-200 bg-white hover:-translate-y-1 hover:border-purple-200 hover:shadow-lg' => $selectedFamily !== $tab['value'],
                                        ])
                                    >
                                        <div class="flex items-start justify-between gap-4">
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-[0.24em] {{ $selectedFamily === $tab['value'] ? 'text-white/70' : 'text-purple-600' }}">
                                                    {{ $t('Famille', 'Family') }}
                                                </p>
                                                <div class="mt-2 flex items-center gap-2">
                                                    <h3 class="text-xl font-black">{{ $tab['label'] }}</h3>
                                                    <span class="inline-flex min-w-10 items-center justify-center rounded-full px-2.5 py-1 text-xs font-black {{ $selectedFamily === $tab['value'] ? 'bg-white/15 text-white' : 'bg-purple-50 text-purple-700' }}">
                                                        {{ number_format($familyCounts[$tab['value']] ?? 0, 0, ',', ' ') }}
                                                    </span>
                                                </div>
                                                <p class="mt-2 text-sm {{ $selectedFamily === $tab['value'] ? 'text-white/85' : 'text-gray-500' }}">
                                                    {{ $tab['description'] }}
                                                </p>
                                            </div>

                                            <span class="flex h-12 w-12 items-center justify-center rounded-2xl {{ $selectedFamily === $tab['value'] ? 'bg-white/15 text-white' : 'bg-purple-50 text-purple-600' }}">
                                                <i class="fa-solid {{ $tab['icon'] }}"></i>
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="flex flex-wrap gap-3">
                                @foreach($quickPresets as $preset)
                                    @php
                                        $presetUrl = $eventsUrl(array_merge(
                                            $preset['filters'],
                                            [
                                                'view' => $viewMode,
                                                'family' => $selectedFamily !== 'all' ? $selectedFamily : null,
                                                'month' => $viewMode === 'calendar'
                                                    ? ($preset['filters']['month'] ?? $calendarMonth->format('Y-m'))
                                                    : null,
                                            ]
                                        ));
                                    @endphp
                                    <a
                                        href="{{ $presetUrl }}"
                                        @class([
                                            'inline-flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-bold transition-all',
                                            'border-transparent bg-gradient-to-r from-purple-600 to-amber-500 text-white shadow-lg shadow-purple-100' => $preset['is_active'],
                                            'border-gray-200 bg-white text-gray-700 hover:border-purple-300 hover:text-purple-600' => !$preset['is_active'],
                                        ])
                                    >
                                        <i class="fa-solid {{ $preset['icon'] }} text-xs"></i>
                                        <span>{{ $preset['label'] }}</span>
                                    </a>
                                @endforeach
                            </div>

                            <form method="GET" action="{{ route('events') }}" class="rounded-3xl bg-gray-50 p-4 md:p-6">
                                <input type="hidden" name="view" value="{{ $viewMode }}">
                                @if($viewMode === 'calendar')
                                    <input type="hidden" name="month" value="{{ $calendarMonth->format('Y-m') }}">
                                @endif
                                @if($selectedFamily !== 'all')
                                    <input type="hidden" name="family" value="{{ $selectedFamily }}">
                                @endif

                                <div class="grid gap-4 lg:grid-cols-[minmax(0,2fr)_repeat(3,minmax(0,1fr))]">
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Recherche', 'Search') }}</label>
                                        <div class="relative">
                                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-purple-500">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </span>
                                            <input
                                                type="search"
                                                name="q"
                                                value="{{ request('q') }}"
                                                placeholder="{{ $t('Artiste, événement, salle, ville...', 'Artist, event, venue, city...') }}"
                                                class="w-full rounded-2xl border border-gray-200 bg-white py-3 pl-11 pr-4 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                            >
                                        </div>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Catégorie', 'Category') }}</label>
                                        <select name="category" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                            <option value="">{{ $t('Toutes les catégories', 'All categories') }}</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category->id }}" {{ (string) request('category') === (string) $category->id ? 'selected' : '' }}>
                                                    {{ $category->name_fr }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Tri', 'Sort') }}</label>
                                        <select name="sort" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                            @foreach($sortOptions as $sortValue => $sortLabel)
                                                <option value="{{ $sortValue }}" {{ $selectedSort === $sortValue ? 'selected' : '' }}>
                                                    {{ $sortLabel }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Ville', 'City') }}</label>
                                        <select name="city" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                            <option value="">{{ $t('Toutes les villes', 'All cities') }}</option>
                                            @foreach($cities as $city)
                                                <option value="{{ $city }}" {{ request('city') === $city ? 'selected' : '' }}>
                                                    {{ $city }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-4 lg:grid-cols-4">
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Pays', 'Country') }}</label>
                                        <select name="country" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                            <option value="">{{ $t('Tous les pays', 'All countries') }}</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}" {{ request('country') === $country ? 'selected' : '' }}>
                                                    {{ $country }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Lieu', 'Venue') }}</label>
                                        <select name="venue" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                            <option value="">{{ $t('Tous les lieux', 'All venues') }}</option>
                                            @foreach($venues as $venue)
                                                <option value="{{ $venue }}" {{ request('venue') === $venue ? 'selected' : '' }}>
                                                    {{ $venue }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Date précise', 'Specific date') }}</label>
                                        <input type="date" name="date" value="{{ request('date') }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Du', 'From') }}</label>
                                        <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Au', 'To') }}</label>
                                        <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Budget min', 'Min budget') }}</label>
                                        <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="0" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                    </div>

                                    <div>
                                        <label class="mb-2 block text-xs font-bold uppercase tracking-[0.22em] text-gray-500">{{ $t('Budget max', 'Max budget') }}</label>
                                        <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="0" class="w-full rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100">
                                    </div>
                                </div>

                                <div class="mt-4 grid gap-3 md:grid-cols-2">
                                    <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-purple-300">
                                        <input type="checkbox" name="featured" value="1" {{ request()->boolean('featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span>{{ $t('Afficher seulement les événements mis en avant', 'Show featured events only') }}</span>
                                    </label>

                                    <label class="flex items-center gap-3 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm font-semibold text-gray-700 transition hover:border-purple-300">
                                        <input type="checkbox" name="available_only" value="1" {{ request()->boolean('available_only') ? 'checked' : '' }} class="h-4 w-4 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <span>{{ $t('Masquer les événements complets', 'Hide sold out events') }}</span>
                                    </label>
                                </div>

                                <div class="mt-5 flex flex-col gap-4 border-t border-gray-200 pt-5 lg:flex-row lg:items-center lg:justify-between">
                                    <p class="max-w-3xl text-sm text-gray-500">
                                        {{ $t('Commencez par choisir une famille, puis affinez par ville, salle, date ou budget. Le calendrier reprend exactement les mêmes filtres que la liste.', 'Start with a family, then refine by city, venue, date or budget. The calendar uses exactly the same filters as the list.') }}
                                    </p>

                                    <div class="flex flex-col gap-3 sm:flex-row">
                                        @if($activeFilters->isNotEmpty())
                                            <a href="{{ $resetUrl }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-300 px-5 py-3 text-sm font-bold text-gray-700 transition hover:border-purple-400 hover:text-purple-600">
                                                <i class="fa-solid fa-rotate-left text-xs"></i>
                                                <span>{{ $t('Réinitialiser', 'Reset') }}</span>
                                            </a>
                                        @endif

                                        <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 px-6 py-3 text-sm font-bold text-white shadow-lg shadow-purple-100 transition hover:scale-[1.01]">
                                            <i class="fa-solid fa-sliders text-xs"></i>
                                            <span>{{ $t('Appliquer les filtres', 'Apply filters') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </form>

                            @if($activeFilters->isNotEmpty())
                                <div class="flex flex-wrap gap-2">
                                    @foreach($activeFilters as $filter)
                                        <span class="inline-flex items-center gap-2 rounded-full border border-purple-100 bg-purple-50 px-3 py-1.5 text-xs font-semibold text-purple-700">
                                            <span class="uppercase tracking-[0.2em] text-[10px] text-purple-500">{{ $filter['label'] }}</span>
                                            <span>{{ $filter['value'] }}</span>
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </details>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-gray-50 pb-12 md:pb-16">
        <div class="cp-shell">
            <div class="mx-auto max-w-7xl">
                <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.28em] text-purple-600">{{ $t('Résultats', 'Results') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-gray-900 md:text-3xl">{{ $t('Événements disponibles', 'Available events') }}</h2>
                        <p class="mt-2 text-sm text-gray-500">
                            {{ number_format($events->total(), 0, ',', ' ') }}
                            {{ $events->total() > 1 ? $t('événements trouvés', 'events found') : $t('événement trouvé', 'event found') }}
                            @if($activeFilterCount > 0)
                                · {{ $activeFilterCount }} {{ $activeFilterCount > 1 ? $t('filtres actifs', 'active filters') : $t('filtre actif', 'active filter') }}
                            @endif
                            @if($searchTerm !== '')
                                · {{ $t('Recherche', 'Search') }}: <span class="font-semibold text-gray-700">{{ $searchTerm }}</span>
                            @endif
                        </p>
                    </div>

                    @if($viewMode === 'calendar')
                        <div class="rounded-2xl bg-white px-4 py-3 text-sm text-gray-500 shadow-sm ring-1 ring-gray-100">
                            {{ $t('Mois affiché', 'Displayed month') }}:
                            <span class="font-bold text-gray-900">{{ $monthLabel }}</span>
                        </div>
                    @endif
                </div>

                @if($featuredEvent)
                    @php
                        $availabilityState = $featuredEvent->availability_state;
                    @endphp
                    <article class="mb-8 overflow-hidden rounded-[32px] bg-white shadow-2xl shadow-purple-100 ring-1 ring-gray-100">
                        <div class="grid lg:grid-cols-[minmax(320px,1.05fr)_minmax(0,1fr)]">
                            <div class="relative min-h-[320px] overflow-hidden">
                                <img src="{{ $featuredEvent->cover_image_url }}" alt="{{ $featuredEventTitle }}" class="absolute inset-0 h-full w-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/25 to-transparent"></div>

                                <div class="absolute left-5 top-5 flex flex-wrap gap-2">
                                    <span class="rounded-full bg-amber-400 px-3 py-1 text-[11px] font-black uppercase tracking-[0.2em] text-gray-900">
                                        {{ $t('Événement en vedette', 'Featured pick') }}
                                    </span>
                                    @if($featuredEventFamilyLabel)
                                        <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-purple-700">
                                            {{ $featuredEventFamilyLabel }}
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-5 left-5 right-5">
                                    <div class="flex flex-wrap items-center gap-3 text-white/90">
                                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold backdrop-blur">
                                            <i class="fa-regular fa-calendar"></i>
                                            {{ $featuredEvent->short_date_label }}
                                        </span>
                                        <span class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1.5 text-xs font-bold backdrop-blur">
                                            <i class="fa-solid fa-location-dot"></i>
                                            {{ $featuredEvent->city ?: $featuredEvent->venue_name }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col p-6 md:p-8">
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-purple-700">
                                        {{ $featuredEvent->category?->name_fr ?? $t('Événement', 'Event') }}
                                    </span>

                                    <span @class([
                                        'rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]',
                                        'bg-emerald-100 text-emerald-700' => $availabilityState === 'available',
                                        'bg-amber-100 text-amber-700' => $availabilityState === 'limited',
                                        'bg-rose-100 text-rose-700' => $availabilityState === 'sold_out',
                                    ])>
                                        {{ match ($availabilityState) {
                                            'limited' => $t('Places limitées', 'Limited seats'),
                                            'sold_out' => $t('Complet', 'Sold out'),
                                            default => $t('Disponible', 'Available'),
                                        } }}
                                    </span>
                                </div>

                                <h3 class="mt-4 text-3xl font-black leading-tight text-gray-900">{{ $featuredEventTitle }}</h3>

                                @if($featuredEventDescription)
                                    <p class="mt-4 text-sm leading-7 text-gray-600 md:text-base">
                                        {{ $featuredEventDescription }}
                                    </p>
                                @endif

                                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                                    <div class="rounded-2xl bg-gray-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">{{ $t('Lieu', 'Venue') }}</p>
                                        <p class="mt-2 text-base font-bold text-gray-900">{{ $featuredEvent->venue_name ?: $featuredEvent->city }}</p>
                                        <p class="mt-1 text-sm text-gray-500">{{ $featuredEvent->city }}@if($featuredEvent->country), {{ $featuredEvent->country }}@endif</p>
                                    </div>

                                    <div class="rounded-2xl bg-gray-50 p-4">
                                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-gray-400">{{ $t('Tarif', 'Pricing') }}</p>
                                        @if($featuredEvent->min_price)
                                            <p class="mt-2 text-2xl font-black text-gray-900">{{ \App\Helpers\CurrencyHelper::format($featuredEvent->min_price) }}</p>
                                            <p class="mt-1 text-sm text-gray-500">{{ $t('À partir de', 'Starting from') }}</p>
                                        @else
                                            <p class="mt-2 text-base font-bold text-gray-900">{{ $t('Sur demande', 'On request') }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-auto flex flex-col gap-3 border-t border-gray-100 pt-6 sm:flex-row">
                                    <a href="{{ route('events.show', $featuredEvent->slug) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 px-6 py-3 text-sm font-black text-white shadow-lg shadow-purple-100 transition hover:scale-[1.01]">
                                        <span>{{ $featuredEvent->is_sold_out ? $t('Voir les détails', 'View details') : $t('Réserver maintenant', 'Book now') }}</span>
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>

                                    <a href="{{ $eventsUrl(['featured' => 1, 'sort' => 'featured']) }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-gray-300 px-6 py-3 text-sm font-bold text-gray-700 transition hover:border-purple-400 hover:text-purple-600">
                                        <i class="fa-solid fa-star text-xs"></i>
                                        <span>{{ $t('Voir les autres mises en avant', 'See more featured events') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endif

                @if($viewMode === 'calendar')
                    @php
                        $startOfMonth = $calendarMonth->copy()->startOfMonth();
                        $startCalendar = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::MONDAY);
                        $endCalendar = $calendarMonth->copy()->endOfMonth()->endOfWeek(\Carbon\Carbon::SUNDAY);
                    @endphp

                    <div class="rounded-3xl bg-white p-4 shadow-xl shadow-purple-50 ring-1 ring-gray-100 md:p-6">
                        <div class="flex flex-col gap-4 border-b border-gray-100 pb-6 md:flex-row md:items-center md:justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-[0.28em] text-purple-600">{{ $t('Agenda mensuel', 'Monthly agenda') }}</p>
                                <h3 class="mt-2 text-2xl font-black text-gray-900">{{ $monthLabel }}</h3>
                                <p class="mt-2 text-sm text-gray-500">
                                    {{ $calendarMonthEvents->count() }}
                                    {{ $calendarMonthEvents->count() > 1 ? $t('événements planifiés sur ce mois', 'events scheduled this month') : $t('événement planifié sur ce mois', 'event scheduled this month') }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ $eventsUrl(['view' => 'calendar', 'month' => $calendarMonth->copy()->subMonth()->format('Y-m')]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 px-4 py-2.5 text-sm font-bold text-gray-700 transition hover:border-purple-300 hover:text-purple-600">
                                    <i class="fa-solid fa-arrow-left text-xs"></i>
                                    <span>{{ $t('Mois précédent', 'Previous month') }}</span>
                                </a>
                                <a href="{{ $eventsUrl(['view' => 'calendar', 'month' => now()->format('Y-m')]) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gray-100 px-4 py-2.5 text-sm font-bold text-gray-700 transition hover:bg-purple-50 hover:text-purple-600">
                                    <i class="fa-regular fa-calendar text-xs"></i>
                                    <span>{{ $t('Ce mois-ci', 'This month') }}</span>
                                </a>
                                <a href="{{ $eventsUrl(['view' => 'calendar', 'month' => $calendarMonth->copy()->addMonth()->format('Y-m')]) }}" class="inline-flex items-center gap-2 rounded-2xl border border-gray-200 px-4 py-2.5 text-sm font-bold text-gray-700 transition hover:border-purple-300 hover:text-purple-600">
                                    <span>{{ $t('Mois suivant', 'Next month') }}</span>
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>

                        <div class="mt-6 overflow-x-auto">
                            <div class="space-y-4 md:hidden">
                                @forelse($calendarMonthEvents->groupBy(fn ($event) => $event->event_date?->toDateString()) as $dateKey => $dayEvents)
                                    @php
                                        $agendaDate = \Carbon\Carbon::parse($dateKey);
                                    @endphp
                                    <div class="rounded-3xl border border-gray-200 bg-gray-50 p-4">
                                        <div class="mb-4 flex items-center justify-between gap-3">
                                            <div>
                                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-purple-600">{{ $agendaDate->translatedFormat('l') }}</p>
                                                <h4 class="mt-1 text-lg font-black text-gray-900">{{ $agendaDate->translatedFormat('d F Y') }}</h4>
                                            </div>
                                            <span class="rounded-full bg-white px-3 py-1 text-xs font-bold text-gray-600 shadow-sm">
                                                {{ $dayEvents->count() }} {{ $dayEvents->count() > 1 ? $t('événements', 'events') : $t('événement', 'event') }}
                                            </span>
                                        </div>

                                        <div class="space-y-3">
                                            @foreach($dayEvents as $event)
                                                <a href="{{ route('events.show', $event->slug) }}" class="flex items-start gap-3 rounded-2xl bg-white p-3 shadow-sm ring-1 ring-gray-100 transition hover:ring-purple-200">
                                                    <div class="flex h-12 w-12 flex-none items-center justify-center rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 text-sm font-black text-white">
                                                        {{ $event->event_date?->format('d') }}
                                                    </div>

                                                    <div class="min-w-0 flex-1">
                                                        <div class="flex flex-wrap items-center gap-2">
                                                            <p class="truncate text-sm font-black text-gray-900">{{ $event->title }}</p>
                                                            @if($event->is_featured)
                                                                <span class="rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-[0.18em] text-amber-700">
                                                                    {{ $t('À la une', 'Featured') }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <div class="mt-2 space-y-1 text-xs text-gray-500">
                                                            <p class="flex items-center gap-2">
                                                                <i class="fa-regular fa-clock text-purple-600"></i>
                                                                <span>{{ $event->event_time ?: '--:--' }}</span>
                                                            </p>
                                                            <p class="flex items-center gap-2">
                                                                <i class="fa-solid fa-location-dot text-amber-500"></i>
                                                                <span>{{ $event->venue_name ?: $event->city }}</span>
                                                            </p>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @empty
                                    <div class="rounded-3xl border border-dashed border-gray-300 bg-gray-50 px-5 py-10 text-center">
                                        <p class="text-lg font-black text-gray-900">{{ $t('Aucun événement sur ce mois.', 'No events this month.') }}</p>
                                        <p class="mt-2 text-sm text-gray-500">{{ $t('Essayez un autre mois ou assouplissez vos filtres.', 'Try another month or relax your filters.') }}</p>
                                    </div>
                                @endforelse
                            </div>

                            <div class="hidden md:block">
                            <div class="min-w-[860px]">
                                <div class="grid grid-cols-7 gap-3">
                                    @foreach($weekDays as $weekDay)
                                        <div class="rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 px-3 py-3 text-center text-sm font-black uppercase tracking-[0.18em] text-white">
                                            {{ $weekDay }}
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-3 grid grid-cols-7 gap-3">
                                    @for($date = $startCalendar->copy(); $date->lte($endCalendar); $date->addDay())
                                        @php
                                            $dateKey = $date->toDateString();
                                            $dayEvents = $eventsByDate->get($dateKey, collect());
                                            $isCurrentMonth = $date->isSameMonth($calendarMonth);
                                            $isToday = $date->isToday();
                                        @endphp

                                        <div @class([
                                            'min-h-[210px] rounded-3xl border p-3 transition-all',
                                            'border-purple-200 bg-purple-50/70' => $isToday,
                                            'border-gray-200 bg-white' => !$isToday && $isCurrentMonth,
                                            'border-gray-100 bg-gray-50' => !$isToday && !$isCurrentMonth,
                                        ])>
                                            <div class="mb-3 flex items-center justify-between gap-2">
                                                <span @class([
                                                    'flex h-10 w-10 items-center justify-center rounded-2xl text-sm font-black',
                                                    'bg-gradient-to-r from-purple-600 to-amber-500 text-white' => $isToday,
                                                    'bg-purple-100 text-purple-700' => !$isToday && $isCurrentMonth,
                                                    'bg-white text-gray-400' => !$isToday && !$isCurrentMonth,
                                                ])>
                                                    {{ $date->day }}
                                                </span>

                                                @if($dayEvents->isNotEmpty())
                                                    <span class="rounded-full bg-amber-100 px-2.5 py-1 text-[11px] font-bold text-amber-700">
                                                        {{ $dayEvents->count() }}
                                                    </span>
                                                @endif
                                            </div>

                                            <div class="space-y-2">
                                                @foreach($dayEvents->take(3) as $event)
                                                    @php
                                                        $eventTitle = app()->getLocale() === 'fr'
                                                            ? $event->title_fr
                                                            : ($event->title_en ?? $event->title_fr);
                                                    @endphp

                                                    <a
                                                        href="{{ route('events.show', $event->slug) }}"
                                                        class="block rounded-2xl border border-purple-100 bg-white px-3 py-2 transition hover:border-purple-300 hover:shadow-sm"
                                                        title="{{ $eventTitle }}"
                                                    >
                                                        <span class="block truncate text-xs font-bold text-gray-900">{{ $eventTitle }}</span>
                                                        <span class="mt-1 block truncate text-[11px] text-gray-500">
                                                            {{ $event->event_time ?: '--:--' }} · {{ $event->city ?: $event->venue_name }}
                                                        </span>
                                                    </a>
                                                @endforeach

                                                @if($dayEvents->count() > 3)
                                                    <div class="rounded-2xl bg-gray-100 px-3 py-2 text-[11px] font-bold text-gray-500">
                                                        +{{ $dayEvents->count() - 3 }} {{ $t('autres événements', 'more events') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <div class="mb-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <h4 class="text-lg font-black text-gray-900">{{ $t('Détail du mois', 'Month details') }}</h4>
                                <span class="text-sm text-gray-500">{{ $calendarMonthEvents->count() }} {{ $t('dans la vue mensuelle', 'in this monthly view') }}</span>
                            </div>

                            @if($calendarMonthEvents->isNotEmpty())
                                <div class="grid gap-4 lg:grid-cols-2">
                                    @foreach($calendarMonthEvents as $event)
                                        @php
                                            $eventTitle = app()->getLocale() === 'fr'
                                                ? $event->title_fr
                                                : ($event->title_en ?? $event->title_fr);
                                            $availabilityState = $event->availability_state;
                                        @endphp

                                        <a href="{{ route('events.show', $event->slug) }}" class="group flex flex-col overflow-hidden rounded-3xl border border-gray-200 bg-gray-50 transition hover:-translate-y-1 hover:border-purple-200 hover:bg-white hover:shadow-lg md:flex-row">
                                            <div class="relative h-52 overflow-hidden md:h-auto md:w-56">
                                                <img src="{{ $event->cover_image_url }}" alt="{{ $eventTitle }}" class="h-full w-full object-cover transition duration-500 group-hover:scale-105" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=600&h=420&fit=crop';">
                                                <div class="absolute left-4 top-4 rounded-2xl bg-white/95 px-3 py-2 text-center shadow">
                                                    <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-purple-600">{{ $event->event_date?->translatedFormat('M') }}</p>
                                                    <p class="text-lg font-black text-gray-900">{{ $event->event_date?->format('d') }}</p>
                                                </div>
                                            </div>

                                            <div class="flex flex-1 flex-col p-5">
                                                <div class="flex flex-wrap items-center gap-2">
                                                    <span class="rounded-full bg-purple-100 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-purple-700">
                                                        {{ $event->category?->name_fr ?? $t('Événement', 'Event') }}
                                                    </span>
                                                    @if($event->city)
                                                        <span class="rounded-full bg-white px-3 py-1 text-[11px] font-semibold text-gray-600">
                                                            {{ $event->city }}
                                                        </span>
                                                    @endif
                                                    <span @class([
                                                        'rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]',
                                                        'bg-emerald-100 text-emerald-700' => $availabilityState === 'available',
                                                        'bg-amber-100 text-amber-700' => $availabilityState === 'limited',
                                                        'bg-rose-100 text-rose-700' => $availabilityState === 'sold_out',
                                                    ])>
                                                        {{ match ($availabilityState) {
                                                            'limited' => $t('Places limitées', 'Limited seats'),
                                                            'sold_out' => $t('Complet', 'Sold out'),
                                                            default => $t('Disponible', 'Available'),
                                                        } }}
                                                    </span>
                                                </div>

                                                <h5 class="mt-3 text-xl font-black text-gray-900">{{ $eventTitle }}</h5>

                                                <div class="mt-3 space-y-2 text-sm text-gray-500">
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-regular fa-clock text-purple-600"></i>
                                                        <span>{{ $event->event_date?->format('d/m/Y') }} @if($event->event_time) · {{ $event->event_time }} @endif</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <i class="fa-solid fa-location-dot text-amber-500"></i>
                                                        <span>{{ $event->venue_name ?: $event->city }}</span>
                                                    </div>
                                                </div>

                                                <div class="mt-auto flex items-end justify-between gap-3 pt-5">
                                                    <div>
                                                        @if($event->min_price)
                                                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">{{ $t('À partir de', 'From') }}</p>
                                                            <p class="text-2xl font-black text-gray-900">{{ \App\Helpers\CurrencyHelper::format($event->min_price) }}</p>
                                                        @else
                                                            <p class="text-sm font-medium text-gray-500">{{ $t('Tarif sur demande', 'Price on request') }}</p>
                                                        @endif
                                                    </div>

                                                    <span class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 px-4 py-2 text-sm font-bold text-white transition group-hover:shadow-lg">
                                                        {{ $event->is_sold_out ? $t('Voir les détails', 'View details') : $t('Voir l’événement', 'View event') }}
                                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @else
                                <div class="rounded-3xl border border-dashed border-gray-300 bg-gray-50 px-6 py-10 text-center">
                                    <p class="text-lg font-black text-gray-900">{{ $t('Aucun événement sur ce mois avec les filtres actuels.', 'No event matches this month with the current filters.') }}</p>
                                    <p class="mt-2 text-sm text-gray-500">{{ $t('Essayez "Voir tout", changez de mois ou assouplissez vos filtres.', 'Try "See all", switch month or relax your filters.') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                        @forelse($events as $event)
                            @php
                                $eventTitle = app()->getLocale() === 'fr'
                                    ? $event->title_fr
                                    : ($event->title_en ?? $event->title_fr);
                                $availabilityState = $event->availability_state;
                            @endphp

                            <article class="group overflow-hidden rounded-3xl bg-white shadow-lg shadow-gray-100 ring-1 ring-gray-100 transition hover:-translate-y-1 hover:shadow-2xl">
                                <div class="relative overflow-hidden">
                                    <img src="{{ $event->cover_image_url }}" alt="{{ $eventTitle }}" class="h-60 w-full object-cover transition duration-700 group-hover:scale-105" onerror="this.onerror=null;this.src='https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=800&h=600&fit=crop';">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

                                    <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                                        <span class="rounded-full bg-white/95 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-purple-700">
                                            {{ $event->category?->name_fr ?? $t('Événement', 'Event') }}
                                        </span>
                                        @if($event->is_featured)
                                            <span class="rounded-full bg-amber-400 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-gray-900">
                                                {{ $t('À la une', 'Featured') }}
                                            </span>
                                        @endif
                                        <span @class([
                                            'rounded-full px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em]',
                                            'bg-emerald-100 text-emerald-700' => $availabilityState === 'available',
                                            'bg-amber-100 text-amber-700' => $availabilityState === 'limited',
                                            'bg-rose-100 text-rose-700' => $availabilityState === 'sold_out',
                                        ])>
                                            {{ match ($availabilityState) {
                                                'limited' => $t('Places limitées', 'Limited seats'),
                                                'sold_out' => $t('Complet', 'Sold out'),
                                                default => $t('Disponible', 'Available'),
                                            } }}
                                        </span>
                                    </div>

                                    <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                                        <div>
                                            <p class="text-xs font-bold uppercase tracking-[0.2em] text-white/75">{{ $t('Date', 'Date') }}</p>
                                            <p class="mt-1 text-lg font-black text-white">
                                                {{ $event->event_date?->format('d M Y') }}
                                                @if($event->event_time)
                                                    <span class="text-sm font-semibold text-white/80">· {{ $event->event_time }}</span>
                                                @endif
                                            </p>
                                        </div>

                                        @if($event->min_price)
                                            <div class="rounded-2xl bg-white/95 px-4 py-2 text-right shadow-lg">
                                                <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-gray-400">{{ $t('Dès', 'From') }}</p>
                                                <p class="text-lg font-black text-gray-900">{{ \App\Helpers\CurrencyHelper::format($event->min_price) }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col p-5 md:p-6">
                                    <h3 class="text-xl font-black text-gray-900">{{ $eventTitle }}</h3>

                                    <div class="mt-4 space-y-2 text-sm text-gray-500">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-location-dot text-purple-600"></i>
                                            <span>{{ $event->venue_name ?: $event->city }}@if($event->venue_name && $event->city), {{ $event->city }}@endif</span>
                                        </div>
                                        @if($event->country)
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-earth-africa text-amber-500"></i>
                                                <span>{{ $event->country }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="mt-6 flex items-center justify-between gap-3 border-t border-gray-100 pt-4">
                                        <div>
                                            @if($event->max_price && $event->min_price && $event->max_price > $event->min_price)
                                                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-gray-400">{{ $t('Jusqu’à', 'Up to') }}</p>
                                                <p class="text-sm font-bold text-gray-700">{{ \App\Helpers\CurrencyHelper::format($event->max_price) }}</p>
                                            @else
                                                <p class="text-sm font-medium text-gray-500">{{ $t('Disponibilité en direct', 'Live availability') }}</p>
                                            @endif
                                        </div>

                                        <a href="{{ route('events.show', $event->slug) }}" class="inline-flex items-center gap-2 rounded-2xl bg-gradient-to-r from-purple-600 to-amber-500 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-purple-100 transition hover:scale-[1.02]">
                                            <span>{{ $event->is_sold_out ? $t('Voir détails', 'View details') : $t('Réserver', 'Book') }}</span>
                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                        </a>
                                    </div>
                                </div>
                            </article>
                        @empty
                            <div class="col-span-full rounded-3xl border border-dashed border-gray-300 bg-white px-6 py-14 text-center">
                                <p class="text-2xl font-black text-gray-900">{{ $t('Aucun événement trouvé.', 'No events found.') }}</p>
                                <p class="mt-3 text-sm text-gray-500">{{ $t('Essayez "Voir tout" ou simplifiez vos filtres pour réafficher les événements sportifs et culturels.', 'Try "See all" or simplify your filters to bring sports and cultural events back.') }}</p>
                            </div>
                        @endforelse
                    </div>

                    @if($events->hasPages())
                        <div class="mt-8">
                            {{ $events->links() }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </section>

    <section class="bg-gradient-to-r from-purple-700 via-purple-600 to-amber-500 py-12 md:py-16">
        <div class="cp-shell">
            <div class="mx-auto max-w-5xl rounded-3xl border border-white/20 bg-white/10 px-6 py-8 text-center text-white backdrop-blur md:px-10 md:py-12">
                <p class="text-xs font-bold uppercase tracking-[0.28em] text-white/70">{{ $t('Accompagnement sur mesure', 'Tailored assistance') }}</p>
                <h2 class="mt-3 text-3xl font-black md:text-4xl">{{ $t('Vous cherchez un événement précis ou une offre VIP complète ?', 'Looking for a specific event or a complete VIP offer?') }}</h2>
                <p class="mx-auto mt-4 max-w-2xl text-sm text-white/85 md:text-base">
                    {{ $t('Notre équipe peut vous aider à trouver des places premium, un pack hospitalité, un transport ou une expérience complète avec conciergerie.', 'Our team can help you source premium tickets, hospitality packages, transport or a complete concierge-led experience.') }}
                </p>

                <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row">
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-white px-6 py-3 text-sm font-bold text-purple-700 transition hover:bg-purple-50">
                        <i class="fa-regular fa-envelope text-xs"></i>
                        <span>{{ $t('Demander un devis', 'Request a quote') }}</span>
                    </a>
                    <a href="{{ config('carre_premium.contact.mobile_link') }}" class="inline-flex items-center justify-center gap-2 rounded-2xl border border-white/30 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                        <i class="fa-solid fa-phone text-xs"></i>
                        <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
