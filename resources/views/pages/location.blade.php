@extends('layouts.app')

@section('title', __('Location de véhicules premium') . ' - Carré Premium')
@section('meta_description', __('Découvrez nos véhicules premium en Côte d’Ivoire. Chauffeur, location privée et solutions sur mesure avec Carré Premium.'))
@section('meta_keywords', __('location de véhicules, voiture premium, transport privé, Côte d’Ivoire, Carré Premium'))
@section('og_title', __('Location de véhicules premium') . ' - Carré Premium')
@section('og_description', __('Réservez un véhicule premium avec un parcours plus clair, plus fiable et mieux structuré pour vos clients.'))

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $locations = $locations ?? new \Illuminate\Pagination\LengthAwarePaginator(
        collect(),
        0,
        9,
        request()->integer('page', 1),
        ['path' => route('location'), 'query' => request()->query()]
    );
    $categories = isset($categories) ? collect($categories)->filter()->values() : collect();
    $types = isset($types) ? collect($types)->filter()->values() : collect();
    $selectedSort = isset($selectedSort) && $selectedSort !== '' ? (string) $selectedSort : (string) request('sort', 'recommended');
    $sortOptions = $sortOptions ?? [
        'recommended' => $t('Recommandés', 'Recommended'),
        'price_low' => $t('Prix croissant', 'Price low to high'),
        'price_high' => $t('Prix décroissant', 'Price high to low'),
        'capacity_high' => $t('Grande capacité', 'Highest capacity'),
        'name' => $t('Nom A-Z', 'Name A-Z'),
    ];
    $totalLocationsCount = isset($totalLocationsCount) ? (int) $totalLocationsCount : (int) $locations->total();
    $startingPrice = $startingPrice ?? null;
    $searchTerm = trim((string) request('q'));
    $selectedCategory = (string) request('category');
    $selectedType = (string) request('type');
    $selectedCapacity = (string) request('capacity');

    $capacityLabels = [
        '1-2' => $t('1 à 2 passagers', '1 to 2 passengers'),
        '3-5' => $t('3 à 5 passagers', '3 to 5 passengers'),
        '6+' => $t('6 passagers et plus', '6 passengers and more'),
    ];

    $activeFilters = collect([
        $searchTerm !== '' ? ['label' => $t('Recherche', 'Search'), 'value' => $searchTerm] : null,
        $selectedCategory !== '' ? ['label' => $t('Catégorie', 'Category'), 'value' => ucfirst($selectedCategory)] : null,
        $selectedType !== '' ? ['label' => $t('Type', 'Type'), 'value' => ucfirst($selectedType)] : null,
        $selectedCapacity !== '' ? ['label' => $t('Capacité', 'Capacity'), 'value' => $capacityLabels[$selectedCapacity] ?? $selectedCapacity] : null,
        $selectedSort !== 'recommended' ? ['label' => $t('Tri', 'Sort'), 'value' => $sortOptions[$selectedSort] ?? $selectedSort] : null,
    ])->filter()->values();

    $resetUrl = route('location');
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] text-white shadow-[0_28px_90px_rgba(41,20,58,0.24)]">
                <div class="grid gap-8 px-5 py-8 sm:px-8 sm:py-10 lg:grid-cols-[minmax(0,1.18fr)_minmax(320px,420px)] lg:px-10 lg:py-12">
                    <div class="max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Location premium', 'Premium rental') }}</span>
                        </div>

                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">
                            {{ $t('Une flotte premium plus lisible, plus cohérente et plus simple à réserver.', 'A premium fleet that is clearer, more coherent and easier to book.') }}
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">
                            {{ $t('Le client doit comprendre immédiatement le type de véhicule, la capacité, le tarif journalier et la prochaine action. Cette page remet ces repères au premier plan.', 'Clients should immediately understand vehicle type, seating capacity, daily rate and the next action. This page brings those cues to the front.') }}
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="#location-filters" class="cp-primary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-car-side text-sm"></i>
                                <span>{{ $t('Filtrer la flotte', 'Filter the fleet') }}</span>
                            </a>
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Besoin d’un véhicule précis ?', 'Need a specific vehicle?') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Résultats', 'Results') }}</p>
                            <p class="mt-3 text-3xl font-black">{{ number_format($locations->total(), 0, ',', ' ') }}</p>
                            <p class="mt-2 text-sm text-white/78">{{ $t('véhicules visibles avec vos critères', 'vehicles visible with your current criteria') }}</p>
                        </div>

                        <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Catégories', 'Categories') }}</p>
                            <p class="mt-3 text-3xl font-black">{{ number_format(count($categories), 0, ',', ' ') }}</p>
                            <p class="mt-2 text-sm text-white/78">{{ $t('familles de véhicules actives', 'active vehicle families') }}</p>
                        </div>

                        <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('À partir de', 'Starting at') }}</p>
                            <p class="mt-3 text-2xl font-black">
                                {{ $startingPrice ? \App\Helpers\CurrencyHelper::format($startingPrice) : $t('Sur demande', 'On request') }}
                            </p>
                            <p class="mt-2 text-sm text-white/78">{{ $t('par jour sur la flotte active', 'per day across the active fleet') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="location-filters" class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-4 py-5 sm:px-6 sm:py-6">
                <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Parcours clarifié', 'Clearer flow') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Vrais filtres, lecture plus nette', 'Real filters, sharper reading') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Réduisez la flotte par catégorie, type ou capacité sans perdre de vue le prix journalier et le service attendu.', 'Filter the fleet by category, type or capacity without losing sight of daily pricing and expected service.') }}
                        </p>
                    </div>

                    @if($activeFilters->isNotEmpty())
                        <a href="{{ $resetUrl }}" class="cp-secondary-button !self-start lg:!self-auto">
                            <i class="fa-solid fa-rotate-left text-sm"></i>
                            <span>{{ $t('Réinitialiser', 'Reset') }}</span>
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('location') }}" class="mt-5 grid gap-4 xl:grid-cols-[minmax(0,1.5fr)_repeat(4,minmax(0,1fr))]">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Recherche', 'Search') }}</label>
                        <div class="flex items-center gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-sm text-[color:var(--cp-ink-muted)]"></i>
                            <input
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="{{ $t('Nom, catégorie, type, description…', 'Name, category, type, description…') }}"
                                class="w-full border-0 bg-transparent p-0 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none placeholder:text-[color:var(--cp-ink-muted)]"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Catégorie', 'Category') }}</label>
                        <select name="category" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            <option value="">{{ $t('Toutes les catégories', 'All categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ $selectedCategory === $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Type', 'Type') }}</label>
                        <select name="type" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            <option value="">{{ $t('Tous les types', 'All types') }}</option>
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ $selectedType === $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Capacité', 'Capacity') }}</label>
                        <select name="capacity" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            <option value="">{{ $t('Toutes les capacités', 'All capacities') }}</option>
                            @foreach($capacityLabels as $value => $label)
                                <option value="{{ $value }}" {{ $selectedCapacity === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Tri', 'Sort') }}</label>
                        <select name="sort" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            @foreach($sortOptions as $value => $label)
                                <option value="{{ $value }}" {{ ($selectedSort ?? 'recommended') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="xl:col-span-5 flex flex-col gap-3 sm:flex-row sm:justify-end">
                        <button type="submit" class="cp-primary-button !w-full sm:!w-auto">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                            <span>{{ $t('Appliquer les filtres', 'Apply filters') }}</span>
                        </button>
                    </div>
                </form>

                @if($activeFilters->isNotEmpty())
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach($activeFilters as $filter)
                            <span class="inline-flex items-center gap-2 rounded-full bg-[#f4edff] px-3 py-2 text-xs font-bold text-[color:var(--cp-plum-800)]">
                                <span class="uppercase tracking-[0.18em] text-[10px] text-[color:var(--cp-ink-muted)]">{{ $filter['label'] }}</span>
                                <span>{{ $filter['value'] }}</span>
                            </span>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="mb-5 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Flotte visible', 'Visible fleet') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Véhicules disponibles', 'Available vehicles') }}</h2>
                    <p class="mt-2 text-sm text-[color:var(--cp-ink-soft)]">
                        {{ number_format($locations->total(), 0, ',', ' ') }}
                        {{ $locations->total() > 1 ? $t('véhicules correspondent à vos critères', 'vehicles match your criteria') : $t('véhicule correspond à vos critères', 'vehicle matches your criteria') }}
                    </p>
                </div>

                <div class="cp-pill">
                    <i class="fa-solid fa-layer-group text-xs"></i>
                    <span>{{ number_format($totalLocationsCount, 0, ',', ' ') }} {{ $t('véhicules actifs au total', 'active vehicles in total') }}</span>
                </div>
            </div>

            @if($locations->count() > 0)
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($locations as $location)
                        @php
                            $locationImage = $location->image_url ?: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=900&h=700&fit=crop';
                            $locationFeatures = collect($location->features ?? [])->filter()->take(3);
                            $locationName = app()->getLocale() === 'fr'
                                ? ($location->name_fr ?? $location->name_en ?? $t('Véhicule premium', 'Premium vehicle'))
                                : ($location->name_en ?? $location->name_fr ?? $t('Véhicule premium', 'Premium vehicle'));
                            $locationDescription = app()->getLocale() === 'fr'
                                ? ($location->description_fr ?? $location->description_en ?? '')
                                : ($location->description_en ?? $location->description_fr ?? '');
                            $locationDescription = trim((string) $locationDescription) !== ''
                                ? $locationDescription
                                : $t('Solution de mobilité premium avec disponibilité à confirmer selon vos dates et votre besoin.', 'Premium mobility solution with availability to confirm according to your dates and needs.');
                            $capacityLabel = $location->capacity
                                ? $location->capacity . ' ' . $t('passagers', 'passengers')
                                : $t('Capacité sur demande', 'Capacity on request');
                            $priceLabel = $location->price_per_day
                                ? \App\Helpers\CurrencyHelper::format($location->price_per_day)
                                : $t('Sur demande', 'On request');
                        @endphp

                        <article class="group overflow-hidden rounded-[2rem] border border-[color:var(--cp-border)] bg-white/95 shadow-[0_18px_55px_rgba(24,37,67,0.10)] transition hover:-translate-y-1 hover:shadow-[0_28px_75px_rgba(24,37,67,0.16)]">
                            <div class="relative overflow-hidden">
                                <img src="{{ $locationImage }}" alt="{{ $location->name }}" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/72 via-black/10 to-transparent"></div>

                                <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                                    @if($location->category)
                                        <span class="rounded-full bg-[#f4edff] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                            {{ ucfirst($location->category) }}
                                        </span>
                                    @endif
                                    @if($location->type)
                                        <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                            {{ ucfirst($location->type) }}
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-white/70">{{ $t('Capacité', 'Capacity') }}</p>
                                        <p class="mt-1 text-lg font-black text-white">{{ $capacityLabel }}</p>
                                    </div>

                                    <div class="rounded-[1.1rem] bg-white/92 px-4 py-3 text-right shadow-lg">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Par jour', 'Per day') }}</p>
                                        <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $priceLabel }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 sm:p-6">
                                <h3 class="text-2xl font-black leading-tight text-[color:var(--cp-plum-950)]">
                                    {{ $locationName }}
                                </h3>

                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                    {{ \Illuminate\Support\Str::limit($locationDescription, 150) }}
                                </p>

                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Catégorie', 'Category') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $location->category ? ucfirst($location->category) : $t('Sur demande', 'On request') }}</p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Type', 'Type') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $location->type ? ucfirst($location->type) : $t('Sur demande', 'On request') }}</p>
                                    </div>
                                </div>

                                @if($locationFeatures->isNotEmpty())
                                    <div class="mt-5 flex flex-wrap gap-2">
                                        @foreach($locationFeatures as $feature)
                                            <span class="rounded-full bg-[#f6f0ff] px-3 py-2 text-xs font-bold text-[color:var(--cp-plum-800)]">
                                                {{ $feature }}
                                            </span>
                                        @endforeach
                                    </div>
                                @endif

                                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-xs font-bold uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Tarif indicatif', 'Indicative fare') }}</p>
                                        <span class="mt-1 block text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $priceLabel }}</span>
                                    </div>

                                    <a href="{{ route('location.show', $location) }}" class="cp-primary-button !w-full sm:!w-auto">
                                        <span>{{ $t('Voir le détail', 'View details') }}</span>
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($locations->hasPages())
                    <div class="mt-8">
                        {{ $locations->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-[2rem] border border-dashed border-[color:var(--cp-border-strong)] bg-white/70 px-6 py-14 text-center">
                    <p class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Aucun véhicule ne correspond à vos critères.', 'No vehicle matches your current criteria.') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                        {{ $t('Élargissez la catégorie, retirez un filtre ou contactez directement l’équipe pour une demande précise.', 'Broaden the category, remove one filter, or contact the team directly for a specific request.') }}
                    </p>
                    <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ $resetUrl }}" class="cp-secondary-button">{{ $t('Voir toute la flotte', 'See the full fleet') }}</a>
                        <a href="{{ route('contact') }}" class="cp-primary-button">{{ $t('Contacter un conseiller', 'Contact an advisor') }}</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <section class="cp-page-section-lg">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#26153a] via-[#4d2d72] to-[#d7a147] px-5 py-8 text-white shadow-[0_24px_70px_rgba(41,20,58,0.18)] sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Demande spécifique', 'Specific request') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Besoin d’un modèle précis, d’un chauffeur ou d’un trajet sur mesure ?', 'Need a precise model, a driver or a bespoke route?') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/80 sm:text-base">
                            {{ $t('L’équipe peut vous orienter vers une solution plus claire que la simple liste: disponibilité réelle, conditions et besoin client réunis dans la même discussion.', 'The team can guide you to a clearer solution than a simple list: real availability, conditions and customer need gathered in the same conversation.') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                            <span>{{ $t('Demander un devis', 'Request a quote') }}</span>
                        </a>
                        <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                            <i class="fa-solid fa-phone text-sm"></i>
                            <span>{{ $t('Appeler maintenant', 'Call now') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
