@extends('layouts.app')

@section('title', __('Packages touristiques - Circuits et séjours VIP') . ' - Carré Premium')
@section('meta_description', 'Découvrez nos packages touristiques exclusifs en Côte d\'Ivoire et Afrique. Circuits VIP, séjours de luxe, expériences uniques avec Carré Premium.')
@section('meta_keywords', 'packages touristiques, circuits VIP, séjours luxe, Côte d\'Ivoire, Afrique, voyages exclusifs, Carré Premium')
@section('og_title', __('Packages touristiques - Circuits et séjours VIP') . ' - Carré Premium')
@section('og_description', 'Réservez vos packages touristiques de luxe en Côte d\'Ivoire. Circuits exclusifs, hébergements premium et expériences uniques avec notre service de conciergerie privée.')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $packages = $packages ?? new \Illuminate\Pagination\LengthAwarePaginator(
        collect(),
        0,
        12,
        request()->integer('page', 1),
        ['path' => route('packages'), 'query' => request()->query()]
    );
    $packageTypes = isset($packageTypes) ? collect($packageTypes)->filter()->values() : collect();
    $destinations = isset($destinations) ? collect($destinations)->filter()->values() : collect();
    $selectedSort = isset($selectedSort) && $selectedSort !== '' ? (string) $selectedSort : (string) request('sort', 'featured');
    $sortOptions = $sortOptions ?? [
        'featured' => $t('Sélection Carré Premium', 'Carré Premium selection'),
        'price_low' => $t('Prix croissant', 'Price low to high'),
        'price_high' => $t('Prix décroissant', 'Price high to low'),
        'duration_short' => $t('Durée courte', 'Shortest duration'),
        'duration_long' => $t('Durée longue', 'Longest duration'),
        'newest' => $t('Nouveautés', 'Newest'),
    ];
    $totalPackagesCount = isset($totalPackagesCount) ? (int) $totalPackagesCount : (int) $packages->total();
    $featuredPackagesCount = isset($featuredPackagesCount) ? (int) $featuredPackagesCount : 0;
    $startingPrice = $startingPrice ?? null;
    $searchTerm = trim((string) request('q'));
    $selectedType = (string) request('type');
    $selectedDestination = (string) request('destination');
    $selectedDuration = (string) request('duration');

    $typeLabels = [
        'helicopter' => $t('Hélicoptère', 'Helicopter'),
        'helicoptère' => $t('Hélicoptère', 'Helicopter'),
        'private_jet' => $t('Jet privé', 'Private jet'),
        'jet' => $t('Jet privé', 'Private jet'),
        'cruise' => $t('Croisière', 'Cruise'),
        'safari' => 'Safari',
        'city_tour' => $t('City tour', 'City tour'),
    ];

    $durationLabels = [
        '1-3' => $t('1 à 3 jours', '1 to 3 days'),
        '4-7' => $t('4 à 7 jours', '4 to 7 days'),
        '1-2-weeks' => $t('1 à 2 semaines', '1 to 2 weeks'),
        'more-than-2-weeks' => $t('Plus de 2 semaines', 'More than 2 weeks'),
    ];

    $activeFilters = collect([
        $searchTerm !== '' ? ['label' => $t('Recherche', 'Search'), 'value' => $searchTerm] : null,
        $selectedType !== '' ? ['label' => $t('Type', 'Type'), 'value' => $typeLabels[$selectedType] ?? ucfirst(str_replace('_', ' ', $selectedType))] : null,
        $selectedDestination !== '' ? ['label' => $t('Destination', 'Destination'), 'value' => $selectedDestination] : null,
        $selectedDuration !== '' ? ['label' => $t('Durée', 'Duration'), 'value' => $durationLabels[$selectedDuration] ?? $selectedDuration] : null,
        request()->boolean('featured') ? ['label' => $t('Sélection', 'Selection'), 'value' => $t('À la une', 'Featured')] : null,
        $selectedSort !== 'featured' ? ['label' => $t('Tri', 'Sort'), 'value' => $sortOptions[$selectedSort] ?? $selectedSort] : null,
    ])->filter()->values();

    $resetUrl = route('packages');
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#241233] via-[#4c2872] to-[#d89b43] text-white shadow-[0_28px_90px_rgba(41,20,58,0.24)]">
                <div class="grid gap-8 px-5 py-8 sm:px-8 sm:py-10 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,420px)] lg:px-10 lg:py-12">
                    <div class="max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Packages signature', 'Signature packages') }}</span>
                        </div>

                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">
                            {{ $t('Des séjours signature choisis pour leur destination, leur rythme et leur intensité.', 'Signature escapes chosen for their destination, rhythm and sense of occasion.') }}
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">
                            {{ $t('Week-ends exclusifs, circuits premium, city breaks et expériences rares à réserver selon votre envie d’évasion, votre durée de séjour et votre budget.', 'Exclusive weekends, premium circuits, city breaks and rare experiences to book according to your appetite for escape, travel length and budget.') }}
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="#packages-filters" class="cp-primary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-sliders text-sm"></i>
                                <span>{{ $t('Filtrer les offres', 'Filter offers') }}</span>
                            </a>
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                                <i class="fa-regular fa-envelope text-sm"></i>
                                <span>{{ $t('Demander du sur-mesure', 'Request something bespoke') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Résultats', 'Results') }}</p>
                            <p class="mt-3 text-3xl font-black">{{ number_format($packages->total(), 0, ',', ' ') }}</p>
                            <p class="mt-2 text-sm text-white/78">{{ $t('packages visibles avec vos critères', 'packages visible with your current criteria') }}</p>
                        </div>

                        <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Sélection premium', 'Premium selection') }}</p>
                            <p class="mt-3 text-3xl font-black">{{ number_format($featuredPackagesCount, 0, ',', ' ') }}</p>
                            <p class="mt-2 text-sm text-white/78">{{ $t('offres mises en avant', 'featured offers') }}</p>
                        </div>

                        <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('À partir de', 'Starting at') }}</p>
                            <p class="mt-3 text-2xl font-black">
                                {{ $startingPrice ? \App\Helpers\CurrencyHelper::format($startingPrice) : $t('Sur demande', 'On request') }}
                            </p>
                            <p class="mt-2 text-sm text-white/78">{{ $t('prix constaté sur les offres actives', 'price observed on active offers') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="packages-filters" class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-4 py-5 sm:px-6 sm:py-6">
                <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Affiner la collection', 'Refine the collection') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Destination, durée et style de séjour', 'Destination, duration and travel style') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Affinez la sélection par destination, type d’expérience ou durée pour trouver le séjour qui correspond à votre prochaine escapade.', 'Filter by destination, experience type or duration to find the escape that fits your next journey.') }}
                        </p>
                    </div>

                    @if($activeFilters->isNotEmpty())
                        <a href="{{ $resetUrl }}" class="cp-secondary-button !self-start lg:!self-auto">
                            <i class="fa-solid fa-rotate-left text-sm"></i>
                            <span>{{ $t('Réinitialiser', 'Reset') }}</span>
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('packages') }}" class="mt-5 grid gap-4 xl:grid-cols-[minmax(0,1.5fr)_repeat(4,minmax(0,1fr))]">
                    <div class="xl:col-span-2">
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Recherche', 'Search') }}</label>
                        <div class="flex items-center gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 shadow-sm">
                            <i class="fa-solid fa-magnifying-glass text-sm text-[color:var(--cp-ink-muted)]"></i>
                            <input
                                type="search"
                                name="q"
                                value="{{ request('q') }}"
                                placeholder="{{ $t('Destination, thème, type de package…', 'Destination, theme, package type…') }}"
                                class="w-full border-0 bg-transparent p-0 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none placeholder:text-[color:var(--cp-ink-muted)]"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Type', 'Type') }}</label>
                        <select name="type" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            <option value="">{{ $t('Tous les types', 'All types') }}</option>
                            @foreach($packageTypes as $type)
                                <option value="{{ $type }}" {{ $selectedType === $type ? 'selected' : '' }}>
                                    {{ $typeLabels[$type] ?? ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Destination', 'Destination') }}</label>
                        <select name="destination" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            <option value="">{{ $t('Toutes les destinations', 'All destinations') }}</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination }}" {{ $selectedDestination === $destination ? 'selected' : '' }}>
                                    {{ $destination }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Durée', 'Duration') }}</label>
                        <select name="duration" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            <option value="">{{ $t('Toutes les durées', 'All durations') }}</option>
                            @foreach($durationLabels as $value => $label)
                                <option value="{{ $value }}" {{ $selectedDuration === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Tri', 'Sort') }}</label>
                        <select name="sort" class="w-full rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none">
                            @foreach($sortOptions as $value => $label)
                                <option value="{{ $value }}" {{ ($selectedSort ?? 'featured') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="xl:col-span-2">
                        <label class="flex items-center gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)] shadow-sm">
                            <input type="checkbox" name="featured" value="1" {{ request()->boolean('featured') ? 'checked' : '' }} class="h-4 w-4 rounded border-[color:var(--cp-border-strong)] text-[color:var(--cp-plum-800)] focus:ring-[color:var(--cp-plum-700)]">
                            <span>{{ $t('Limiter à la sélection mise en avant', 'Only show featured offers') }}</span>
                        </label>
                    </div>

                    <div class="xl:col-span-3 flex flex-col gap-3 sm:flex-row sm:justify-end">
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
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Résultats', 'Results') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Packages disponibles', 'Available packages') }}</h2>
                    <p class="mt-2 text-sm text-[color:var(--cp-ink-soft)]">
                        {{ number_format($packages->total(), 0, ',', ' ') }}
                        {{ $packages->total() > 1 ? $t('packages correspondent à vos critères', 'packages match your criteria') : $t('package correspond à vos critères', 'package matches your criteria') }}
                    </p>
                </div>

                <div class="cp-pill">
                    <i class="fa-solid fa-compass text-xs"></i>
                    <span>{{ number_format(count($destinations), 0, ',', ' ') }} {{ $t('destinations actives', 'active destinations') }}</span>
                </div>
            </div>

            @if($packages->count() > 0)
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($packages as $package)
                        @php
                            $packageTitle = app()->getLocale() === 'fr'
                                ? ($package->title_fr ?? $package->title_en ?? $package->slug)
                                : ($package->title_en ?? $package->title_fr ?? $package->slug);
                            $packageDescription = app()->getLocale() === 'fr'
                                ? ($package->description_fr ?? $package->description_en ?? '')
                                : ($package->description_en ?? $package->description_fr ?? '');
                            $packageImage = $package->getFirstMediaUrl('avatar', 'normal');
                            $packageFallback = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=900&h=700&fit=crop';
                            $displayPrice = $package->discount_price ?? $package->price;
                            $displayPriceLabel = $displayPrice ? \App\Helpers\CurrencyHelper::format($displayPrice) : $t('Sur demande', 'On request');
                            $durationLabel = app()->getLocale() === 'fr'
                                ? ($package->duration_text_fr ?: ($package->duration ? $package->duration . ' jours' : $t('Durée sur demande', 'Duration on request')))
                                : ($package->duration_text_en ?? $package->duration_text_fr ?? ($package->duration ? $package->duration . ' days' : $t('Durée sur demande', 'Duration on request')));
                            $packageTypeLabel = $typeLabels[$package->package_type] ?? ucfirst(str_replace('_', ' ', $package->package_type ?? ''));
                            $packageDescription = trim($packageDescription) !== ''
                                ? $packageDescription
                                : $t('Programme premium construit autour du confort, de la logistique et d’une expérience plus fluide.', 'Premium itinerary built around comfort, logistics and a smoother experience.');
                            $participantLabel = match (true) {
                                filled($package->min_participants) && filled($package->max_participants) => $package->min_participants . '-' . $package->max_participants . ' ' . $t('pers.', 'pax'),
                                filled($package->max_participants) => $t('Jusqu’à', 'Up to') . ' ' . $package->max_participants . ' ' . $t('pers.', 'pax'),
                                default => null,
                            };
                        @endphp

                        <article class="group overflow-hidden rounded-[2rem] border border-[color:var(--cp-border)] bg-white/95 shadow-[0_18px_55px_rgba(41,20,58,0.10)] transition hover:-translate-y-1 hover:shadow-[0_28px_75px_rgba(41,20,58,0.16)]">
                            <div class="relative overflow-hidden">
                                <img src="{{ $packageImage ?: $packageFallback }}" alt="{{ $packageTitle }}" class="h-64 w-full object-cover transition duration-700 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/68 via-black/10 to-transparent"></div>

                                <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                                    @if($package->is_featured)
                                        <span class="rounded-full bg-[color:var(--cp-gold-400)] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#2a163d]">
                                            {{ $t('Sélection', 'Featured') }}
                                        </span>
                                    @endif
                                    @if($packageTypeLabel)
                                        <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                            {{ $packageTypeLabel }}
                                        </span>
                                    @endif
                                </div>

                                <div class="absolute bottom-4 left-4 right-4 flex items-end justify-between gap-3">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-white/70">{{ $t('Destination', 'Destination') }}</p>
                                        <p class="mt-1 text-lg font-black text-white">{{ $package->destination ?: $t('Sur demande', 'On request') }}</p>
                                    </div>

                                    <div class="rounded-[1.1rem] bg-white/92 px-4 py-3 text-right shadow-lg">
                                        <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('À partir de', 'From') }}</p>
                                        <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $displayPriceLabel }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="p-5 sm:p-6">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-full bg-[#f4edff] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                        {{ $durationLabel }}
                                    </span>
                                    @if($participantLabel)
                                        <span class="rounded-full bg-[#fff6e8] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[#a86308]">
                                            {{ $participantLabel }}
                                        </span>
                                    @endif
                                </div>

                                <h3 class="mt-4 text-2xl font-black leading-tight text-[color:var(--cp-plum-950)]">
                                    {{ $packageTitle }}
                                </h3>

                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                    {{ \Illuminate\Support\Str::limit($packageDescription, 150) }}
                                </p>

                                <div class="mt-5 grid grid-cols-2 gap-3">
                                    <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Départ', 'Departure') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $package->departure_city ?: $t('Départ à confirmer', 'Departure to confirm') }}</p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Date repère', 'Date cue') }}</p>
                                        <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">
                                            {{ $package->event_date_start ? $package->event_date_start->format('d/m/Y') : $t('Dates flexibles', 'Flexible dates') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        @if($package->discount_price)
                                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Tarif remisé', 'Discounted fare') }}</p>
                                            <div class="mt-1 flex items-center gap-2">
                                                <span class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($package->discount_price) }}</span>
                                                @if($package->price)
                                                    <span class="text-sm font-semibold text-[color:var(--cp-ink-muted)] line-through">{{ \App\Helpers\CurrencyHelper::format($package->price) }}</span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="text-xs font-bold uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Tarif estimatif', 'Estimated fare') }}</p>
                                            <span class="mt-1 block text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $package->price ? \App\Helpers\CurrencyHelper::format($package->price) : $t('Sur demande', 'On request') }}</span>
                                        @endif
                                    </div>

                                    <a href="{{ route('packages.show', $package->slug) }}" class="cp-primary-button !w-full sm:!w-auto">
                                        <span>{{ $t('Voir le détail', 'View details') }}</span>
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                @if($packages->hasPages())
                    <div class="mt-8">
                        {{ $packages->links() }}
                    </div>
                @endif
            @else
                <div class="rounded-[2rem] border border-dashed border-[color:var(--cp-border-strong)] bg-white/70 px-6 py-14 text-center">
                    <p class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Aucun package ne correspond à votre recherche.', 'No package matches your search.') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                        {{ $t('Essayez une destination plus large, retirez un filtre ou contactez l’équipe pour une proposition sur mesure.', 'Try a broader destination, remove one filter, or contact the team for a bespoke suggestion.') }}
                    </p>
                    <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                        <a href="{{ $resetUrl }}" class="cp-secondary-button">{{ $t('Voir toutes les offres', 'See all offers') }}</a>
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
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Demande sur mesure', 'Tailor-made request') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Besoin d’un séjour totalement sur mesure ?', 'Need a fully bespoke journey?') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/80 sm:text-base">
                            {{ $t('Notre équipe peut composer un séjour complet avec transport, hébergement, activités et accompagnement humain du devis jusqu’au départ.', 'Our team can build a full journey with transport, accommodation, activities and human guidance from quote to departure.') }}
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
