@extends('layouts.app')

@section('title', $event->title . ' - Carré Premium')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $eventTitle = $event->title;
    $eventTagline = $event->tagline;
    $eventDescription = app()->getLocale() === 'fr'
        ? ($event->description_fr ?? $event->description_en ?? '')
        : ($event->description_en ?? $event->description_fr ?? '');
    $eventProgram = $event->program;
    $eventConditions = $event->conditions;
    $imageUrl = $event->cover_image_url;
    $hasPackages = $event->relationLoaded('packages') && $event->packages->isNotEmpty();
    $hasSeatZones = $event->seatZones->isNotEmpty();
    $hasInventory = $hasPackages || $hasSeatZones;
    $packageCount = $hasPackages ? $event->packages->count() : 0;
    $seatZoneCount = $hasSeatZones ? $event->seatZones->count() : 0;
    $offerCount = $packageCount + $seatZoneCount;
    $defaultOfferTab = $hasPackages ? 'packages' : ($hasSeatZones ? 'zones' : 'packages');
    $dateLabel = $event->date_range_label ?? $event->short_date_label ?? $t('Date à confirmer', 'Date to be confirmed');
    $timeLabel = trim(collect([
        $event->event_time,
        ($event->end_time && $event->end_date && $event->end_date->isSameDay($event->event_date)) ? $event->end_time : null,
    ])->filter()->implode(' - '));
    $categoryLabel = app()->getLocale() === 'fr'
        ? ($event->category->name_fr ?? $event->category->name_en ?? $t('Événement', 'Event'))
        : ($event->category->name_en ?? $event->category->name_fr ?? $t('Événement', 'Event'));
    $typeLabel = app()->getLocale() === 'fr'
        ? ($event->type->name_fr ?? $event->type->name_en ?? $t('Expérience', 'Experience'))
        : ($event->type->name_en ?? $event->type->name_fr ?? $t('Expérience', 'Experience'));
    $familyLabels = [
        'sportif' => $t('Sport', 'Sports'),
        'culturel' => $t('Culture', 'Culture'),
    ];
    $familyLabel = $familyLabels[$event->family] ?? null;
    $availabilityMap = [
        'available' => [
            'label' => $t('Réservation ouverte', 'Booking open'),
            'description' => $t('Des offres sont disponibles immédiatement.', 'Offers are immediately available.'),
            'classes' => 'bg-[#e8f7ec] text-[#1f6a35]',
        ],
        'limited' => [
            'label' => $t('Disponibilité limitée', 'Limited availability'),
            'description' => $t('Certaines offres approchent de la rupture.', 'Some offers are running low.'),
            'classes' => 'bg-[#fff2de] text-[#9a5a07]',
        ],
        'sold_out' => [
            'label' => $t('Complet', 'Sold out'),
            'description' => $t('Plus de stock immédiat sur les offres publiées.', 'No immediate stock remains on published offers.'),
            'classes' => 'bg-[#ffe7e5] text-[#b42318]',
        ],
    ];
    $availability = $availabilityMap[$event->availability_state] ?? $availabilityMap['available'];
    $supportText = $t(
        'Un conseiller peut vous orienter avant la commande si vous hésitez entre plusieurs formules ou zones.',
        'An advisor can guide you before checkout if you hesitate between several packages or seat zones.'
    );
    $defaultName = old('name', trim((auth()->user()?->first_name ?? '') . ' ' . (auth()->user()?->last_name ?? '')));
    $defaultEmail = old('email', auth()->user()?->email ?? '');
    $defaultPhone = old('phone', auth()->user()?->phone ?? '');
    $relatedEvents = collect($relatedEvents ?? [])->filter();
    $startingPriceLabel = $event->min_price ? \App\Helpers\CurrencyHelper::format($event->min_price) : $t('Sur demande', 'On request');
    $leadText = $eventTagline ?: (\Illuminate\Support\Str::limit(strip_tags($eventDescription), 200) ?: $supportText);
    $locationSummary = implode(' · ', array_filter([$event->venue_name, $event->city, $event->country])) ?: $t('Lieu à confirmer', 'Venue to be confirmed');
    $heroFacts = collect([
        ['label' => $t('Date', 'Date'), 'value' => $dateLabel, 'meta' => $timeLabel ?: $t('Horaire à confirmer', 'Time to be confirmed')],
        ['label' => $t('Lieu', 'Venue'), 'value' => $event->venue_name ?: $t('À confirmer', 'To be confirmed'), 'meta' => $locationSummary],
        ['label' => $t('À partir de', 'Starting at'), 'value' => $startingPriceLabel, 'meta' => $t('Prix d’entrée observé', 'Observed entry price')],
        ['label' => $t('Disponibilité', 'Availability'), 'value' => $availability['label'], 'meta' => $availability['description']],
    ]);
    $insightCards = collect([
        ['label' => $t('Type', 'Type'), 'value' => $typeLabel, 'meta' => $categoryLabel],
        ['label' => $t('Organisateur', 'Organizer'), 'value' => $event->organizer ?: 'Carré Premium', 'meta' => $event->source_catalog ?: $t('Fiche Carré Premium', 'Carré Premium listing')],
        ['label' => $t('Support', 'Support'), 'value' => $t('Conseiller disponible', 'Advisor available'), 'meta' => $supportText],
        ['label' => $t('Offres publiées', 'Published offers'), 'value' => (string) $offerCount, 'meta' => $packageCount . ' ' . $t('packages', 'packages') . ' · ' . $seatZoneCount . ' ' . $t('zones', 'zones')],
    ]);
    $bookingSteps = [
        [
            'number' => '01',
            'title' => $t('Choisir une offre', 'Choose an offer'),
            'description' => $t('Comparez les formules disponibles et retenez celle qui correspond à votre besoin.', 'Compare the available offers and keep the one that fits your need.'),
        ],
        [
            'number' => '02',
            'title' => $t('Confirmer la quantité', 'Confirm quantity'),
            'description' => $t('Le stock et les limites par commande sont contrôlés automatiquement.', 'Stock and per-order limits are controlled automatically.'),
        ],
        [
            'number' => '03',
            'title' => $t('Finaliser le paiement', 'Complete payment'),
            'description' => $t('Le site envoie vers le bon mode de paiement selon le total.', 'The site sends you to the right payment mode according to the total.'),
        ],
    ];
    $heroSignals = [
        $t('Boutons visibles', 'Visible buttons'),
        $t('Réservation guidée', 'Guided booking'),
        $t('Paiement adapté', 'Adaptive payment'),
    ];
    $initialOfferTab = old('zone_id')
        ? 'zones'
        : ((old('package_id') || old('package_option_id')) ? 'packages' : $defaultOfferTab);
@endphp

<div data-event-details data-initial-offer-tab="{{ $initialOfferTab }}" class="cp-page">
    @if($errors->any())
        <section class="cp-page-hero">
            <div class="cp-shell">
                <div class="event-page-alert rounded-[1.8rem] border border-red-200 bg-red-50/90 px-5 py-5 text-red-800 shadow-[0_18px_50px_rgba(127,29,29,0.08)]">
                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-red-700">{{ $t('Vérification requise', 'Review required') }}</p>
                            <p class="mt-2 text-lg font-black text-red-900">{{ $t('La réservation doit être corrigée avant validation.', 'The booking needs corrections before it can continue.') }}</p>
                        </div>
                        <button type="button" data-open-booking-errors class="cp-secondary-button !w-full !justify-center !border-red-200 !bg-white !text-red-800 sm:!w-auto">
                            <i class="fa-solid fa-pen-to-square text-sm"></i>
                            <span>{{ $t('Reprendre la commande', 'Resume checkout') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="grid gap-5 xl:grid-cols-[minmax(0,1.05fr)_400px]">
                <div class="event-surface cp-fade-up overflow-hidden rounded-[2.45rem] p-5 sm:p-7 xl:p-9">
                    <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">
                        <a href="{{ route('home') }}" class="transition hover:text-[color:var(--cp-plum-900)]">Accueil</a>
                        <span>/</span>
                        <a href="{{ route('events') }}" class="transition hover:text-[color:var(--cp-plum-900)]">Événements</a>
                        <span>/</span>
                        <span class="text-[color:var(--cp-plum-900)]">{{ $eventTitle }}</span>
                    </div>

                    <div class="mt-5 flex flex-wrap gap-2">
                        <span class="rounded-full bg-[#f4edff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $categoryLabel }}</span>
                        <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $typeLabel }}</span>
                        @if($familyLabel)
                            <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $familyLabel }}</span>
                        @endif
                        @if($event->is_featured)
                            <span class="rounded-full bg-[color:var(--cp-gold-400)] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#2a163d]">{{ $t('En vedette', 'Featured') }}</span>
                        @endif
                    </div>

                    <h1 class="mt-5 max-w-4xl text-3xl font-black leading-tight text-[color:var(--cp-plum-950)] sm:text-4xl xl:text-[3.3rem]">
                        {{ $eventTitle }}
                    </h1>

                    <p class="mt-4 max-w-3xl text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">
                        {{ $leadText }}
                    </p>

                    <div class="event-hero-actions mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        @if($hasInventory)
                            <a href="#event-offers" class="cp-primary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-ticket text-sm"></i>
                                <span>{{ $t('Voir les offres', 'See offers') }}</span>
                            </a>
                        @else
                            <a href="{{ route('contact') }}" class="cp-primary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                            </a>
                        @endif
                        <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto">
                            <i class="fa-solid fa-envelope text-sm"></i>
                            <span>{{ $t('Être accompagné', 'Get assistance') }}</span>
                        </a>
                    </div>

                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach($heroSignals as $signal)
                            <span class="event-step-pill !border-[color:var(--cp-border)] !bg-white !text-[color:var(--cp-plum-900)]">{{ $signal }}</span>
                        @endforeach
                    </div>

                    <div class="mt-8 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        @foreach($heroFacts as $item)
                            <div class="event-summary-card rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $item['label'] }}</p>
                                <p class="mt-2 text-sm font-black text-[color:var(--cp-plum-950)] sm:text-base">{{ $item['value'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $item['meta'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <aside class="space-y-4">
                    <div class="cp-fade-up relative overflow-hidden rounded-[2.3rem] bg-[#1d112c] shadow-[0_30px_90px_rgba(41,20,58,0.22)]" style="animation-delay: 0.08s">
                        <img src="{{ $imageUrl }}" alt="{{ $eventTitle }}" class="h-[280px] w-full object-cover sm:h-[340px] xl:h-[420px]">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#12091b]/90 via-[#2b163f]/34 to-transparent"></div>
                        <div class="absolute left-4 top-4">
                            <span class="rounded-full px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] {{ $availability['classes'] }}">
                                {{ $availability['label'] }}
                            </span>
                        </div>
                        <div class="absolute inset-x-0 bottom-0 px-5 pb-5 sm:px-6 sm:pb-6">
                            <div class="rounded-[1.5rem] border border-white/12 bg-white/12 p-4 text-white backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/62">{{ $t('Repère rapide', 'Quick snapshot') }}</p>
                                <p class="mt-2 text-lg font-black">{{ $dateLabel }}</p>
                                <p class="mt-2 text-sm leading-6 text-white/80">{{ $locationSummary }}</p>
                                <div class="mt-4 flex items-end justify-between gap-4">
                                    <div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/62">{{ $t('À partir de', 'Starting at') }}</p>
                                        <p class="mt-1 text-2xl font-black">{{ $startingPriceLabel }}</p>
                                    </div>
                                    <span class="rounded-full border border-white/12 bg-white/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-white/84">
                                        {{ $offerCount }} {{ $t('offres', 'offers') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="event-surface cp-fade-up rounded-[2rem] p-5 sm:p-6" style="animation-delay: 0.12s">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Réservation', 'Booking') }}</p>
                        <h2 class="mt-3 text-2xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $t('Un parcours simple jusqu’à la commande', 'A simple path to checkout') }}</h2>
                        <div class="mt-4 grid gap-2">
                            @foreach($bookingSteps as $step)
                                <div class="flex items-center gap-3 rounded-[1.15rem] bg-[#faf6ff] px-4 py-3">
                                    <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-[#ede4ff] text-xs font-black text-[color:var(--cp-plum-800)]">{{ $step['number'] }}</span>
                                    <p class="text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $step['title'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <a href="{{ route('contact') }}" class="cp-primary-button !w-full !justify-center">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                            </a>
                            <a href="{{ config('carre_premium.contact.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !w-full !justify-center">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <section class="event-surface rounded-[2.1rem] p-5 sm:p-6 md:p-8">
                        <div class="grid gap-6 lg:grid-cols-[minmax(0,1.08fr)_minmax(280px,0.92fr)]">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('À propos', 'About') }}</p>
                                <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('L’essentiel de l’événement', 'What matters about this event') }}</h2>
                                <div class="mt-5 whitespace-pre-line text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">
                                    {{ $eventDescription ?: $supportText }}
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                @foreach($insightCards as $item)
                                    <article class="rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white px-4 py-4 event-card-hover">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $item['label'] }}</p>
                                        <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $item['value'] }}</p>
                                        <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $item['meta'] }}</p>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </section>

                    <section id="event-offers" class="event-surface event-section-anchor rounded-[2.1rem] p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Offres', 'Offers') }}</p>
                                <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Choisissez votre formule', 'Choose your offer') }}</h2>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                    {{ $t('Prix, disponibilité, détail utile, puis bouton de commande. Rien de caché, rien de confus.', 'Price, availability, useful detail, then a booking button. Nothing hidden, nothing confusing.') }}
                                </p>
                            </div>

                            @if($hasPackages && $hasSeatZones)
                                <div class="flex flex-wrap gap-2" data-offer-tab-controls>
                                    <button type="button" data-offer-tab-button="packages" @class([
                                        'event-tab',
                                        'is-active' => $initialOfferTab === 'packages',
                                    ])>
                                        <i class="fa-solid fa-box-open text-xs"></i>
                                        <span>{{ $packageCount }} {{ $t('packages', 'packages') }}</span>
                                    </button>
                                    <button type="button" data-offer-tab-button="zones" @class([
                                        'event-tab',
                                        'is-active' => $initialOfferTab === 'zones',
                                    ])>
                                        <i class="fa-solid fa-couch text-xs"></i>
                                        <span>{{ $seatZoneCount }} {{ $t('zones', 'zones') }}</span>
                                    </button>
                                </div>
                            @else
                                <div class="flex flex-wrap gap-2">
                                    @if($packageCount)
                                        <span class="cp-pill"><i class="fa-solid fa-box-open text-xs"></i><span>{{ $packageCount }} {{ $t('packages', 'packages') }}</span></span>
                                    @endif
                                    @if($seatZoneCount)
                                        <span class="cp-pill"><i class="fa-solid fa-couch text-xs"></i><span>{{ $seatZoneCount }} {{ $t('zones', 'zones') }}</span></span>
                                    @endif
                                </div>
                            @endif
                        </div>

                        @if($hasPackages)
                            <div
                                data-offer-tab-panel="packages"
                                @class([
                                    'mt-6 space-y-5',
                                    'hidden' => $hasSeatZones && $initialOfferTab !== 'packages',
                                ])
                            >
                                @foreach($event->packages as $package)
                                    @php
                                        $packageDescription = app()->getLocale() === 'fr'
                                            ? ($package->description_fr ?? $package->description_en ?? '')
                                            : ($package->description_en ?? $package->description_fr ?? '');
                                        $packageSummary = \Illuminate\Support\Str::limit(trim(strip_tags($packageDescription)), 210);
                                        $includedSource = app()->getLocale() === 'fr'
                                            ? ($package->description_included_fr ?? $package->description_included_en ?? '')
                                            : ($package->description_included_en ?? $package->description_included_fr ?? '');
                                        $includedLines = collect(preg_split('/\r\n|\r|\n/', (string) $includedSource))
                                            ->map(fn ($line) => trim($line))
                                            ->filter()
                                            ->take(4);
                                    @endphp

                                    <article class="event-offer-card event-card-hover overflow-hidden rounded-[2rem] border border-[color:var(--cp-border)] bg-white p-5 sm:p-6">
                                        <div class="flex flex-wrap gap-2">
                                            @if($package->package_code)
                                                <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $package->package_code }}</span>
                                            @endif
                                            <span class="rounded-full bg-[#f8fafc] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">
                                                {{ $t('Min.', 'Min.') }} {{ max(1, $package->minimum_quantity ?? 1) }}
                                            </span>
                                            @if($package->has_options)
                                                <span class="rounded-full bg-[#fff7e8] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#91610e]">
                                                    {{ $t('Plusieurs options', 'Multiple options') }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mt-4 grid gap-5 lg:grid-cols-[minmax(0,1fr)_280px]">
                                            <div>
                                                <h3 class="text-2xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $package->name }}</h3>
                                                @if($package->venue_details)
                                                    <p class="mt-2 text-sm font-semibold text-[color:var(--cp-plum-800)]">{{ $package->venue_details }}</p>
                                                @endif
                                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                    {{ $packageSummary ?: $t('Offre publiée sans description longue supplémentaire.', 'Published offer without additional long description.') }}
                                                </p>

                                                @if($includedLines->isNotEmpty())
                                                    <div class="mt-5 grid gap-2 sm:grid-cols-2">
                                                        @foreach($includedLines as $line)
                                                            <div class="flex items-start gap-3 rounded-[1.2rem] bg-[#faf8ff] px-4 py-3">
                                                                <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#ede4ff] text-[11px] font-black text-[color:var(--cp-plum-800)]">+</span>
                                                                <span class="text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $line }}</span>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            @unless($package->has_options)
                                                <div data-offer-card class="rounded-[1.7rem] border border-[color:var(--cp-border)] bg-[#fbf8ff] p-5">
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Repère commande', 'Booking snapshot') }}</p>
                                                    <p class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($package->price) }}</p>
                                                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                        {{ $package->available_quantity }} {{ $t('disponibles', 'available') }} · {{ $t('max', 'max') }} {{ max(1, $package->max_per_order ?? 1) }} {{ $t('par commande', 'per order') }}
                                                    </p>
                                                    <button
                                                        type="button"
                                                        class="select-package-btn cp-primary-button !mt-5 !w-full !justify-center"
                                                        data-package-id="{{ $package->id }}"
                                                        data-package-option-id=""
                                                        data-package-name="{{ $package->name }}"
                                                        data-selection-label="{{ $package->name }}"
                                                        data-parent-label=""
                                                        data-price="{{ \App\Helpers\CurrencyHelper::convert($package->price) }}"
                                                        data-base-price="{{ $package->price }}"
                                                        data-available="{{ $package->available_quantity }}"
                                                        data-max-per-order="{{ max(1, $package->max_per_order ?? 1) }}"
                                                        data-minimum-quantity="{{ max(1, $package->minimum_quantity ?? 1) }}"
                                                        data-type-label="{{ $t('Formule VIP', 'VIP package') }}"
                                                        data-meta="{{ $package->venue_details ?: $packageSummary }}"
                                                    >
                                                        <span>{{ $t('Commander cette formule', 'Order this package') }}</span>
                                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                                    </button>
                                                </div>
                                            @endunless
                                        </div>

                                        @if($package->has_options)
                                            <div class="mt-6 grid gap-4 xl:grid-cols-2">
                                                @foreach($package->options as $option)
                                                    <article data-offer-card class="rounded-[1.6rem] border border-[color:var(--cp-border)] bg-[#fbf8ff] p-4 event-card-hover">
                                                        <div class="flex items-start justify-between gap-4">
                                                            <div class="min-w-0">
                                                                <p class="text-base font-black text-[color:var(--cp-plum-950)]">{{ $option->label }}</p>
                                                                @if($option->context)
                                                                    <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit(trim($option->context), 110) }}</p>
                                                                @endif
                                                            </div>
                                                            <p class="text-lg font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($option->price) }}</p>
                                                        </div>

                                                        <div class="mt-4 flex flex-wrap gap-2 text-xs font-semibold text-[color:var(--cp-ink-muted)]">
                                                            @if($option->option_date)
                                                                <span class="rounded-full bg-white px-3 py-1">{{ $option->option_date->translatedFormat('d M Y') }}</span>
                                                            @endif
                                                            <span class="rounded-full bg-white px-3 py-1">{{ $option->available_quantity }} {{ $t('disponibles', 'available') }}</span>
                                                            <span class="rounded-full bg-white px-3 py-1">{{ $t('max', 'max') }} {{ max(1, $option->max_per_order ?? $package->max_per_order ?? 1) }}</span>
                                                        </div>

                                                        <button
                                                            type="button"
                                                            class="select-package-btn cp-primary-button !mt-5 !w-full !justify-center"
                                                            data-package-id="{{ $package->id }}"
                                                            data-package-option-id="{{ $option->id }}"
                                                            data-package-name="{{ $package->name }}"
                                                            data-selection-label="{{ $option->full_label }}"
                                                            data-parent-label="{{ $package->name }}"
                                                            data-price="{{ \App\Helpers\CurrencyHelper::convert($option->price) }}"
                                                            data-base-price="{{ $option->price }}"
                                                            data-available="{{ $option->available_quantity }}"
                                                            data-max-per-order="{{ max(1, $option->max_per_order ?? $package->max_per_order ?? 1) }}"
                                                            data-minimum-quantity="{{ max(1, $package->minimum_quantity ?? 1) }}"
                                                            data-type-label="{{ $t('Formule VIP', 'VIP package') }}"
                                                            data-meta="{{ $option->full_label }}"
                                                        >
                                                            <span>{{ $t('Commander cette option', 'Order this option') }}</span>
                                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                                        </button>
                                                    </article>
                                                @endforeach
                                            </div>
                                        @endif
                                    </article>
                                @endforeach
                            </div>
                        @endif

                        @if($hasSeatZones)
                            <div
                                data-offer-tab-panel="zones"
                                @class([
                                    'mt-6',
                                    'hidden' => $hasPackages && $initialOfferTab !== 'zones',
                                ])
                            >
                                <div class="grid gap-4 lg:grid-cols-2">
                                    @foreach($event->seatZones as $zone)
                                        <article data-offer-card class="event-offer-card event-card-hover rounded-[1.9rem] border border-[color:var(--cp-border)] bg-white p-5">
                                            <div class="flex flex-wrap gap-2">
                                                @if($zone->zone_code)
                                                    <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $zone->zone_code }}</span>
                                                @endif
                                                @if($zone->zone_type)
                                                    <span class="rounded-full bg-[#f8fafc] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ str_replace('_', ' ', $zone->zone_type) }}</span>
                                                @endif
                                            </div>

                                            <div class="mt-4">
                                                <h3 class="text-xl font-black text-[color:var(--cp-plum-950)]">{{ $zone->zone_name }}</h3>
                                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                    {{ \Illuminate\Support\Str::limit(trim($zone->description ?: $t('Accès direct sans package, avec sélection de quantité ensuite.', 'Direct access without package, with quantity selection next.')), 180) }}
                                                </p>
                                            </div>

                                            <div class="mt-5 grid gap-4 sm:grid-cols-[minmax(0,1fr)_auto] sm:items-end">
                                                <div class="grid gap-3 sm:grid-cols-2">
                                                    <div class="rounded-[1.2rem] bg-[#faf8ff] px-4 py-4">
                                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Disponibilité', 'Availability') }}</p>
                                                        <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $zone->available_seats }} {{ $t('places', 'seats') }}</p>
                                                    </div>
                                                    <div class="rounded-[1.2rem] bg-[#faf8ff] px-4 py-4">
                                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Prix', 'Price') }}</p>
                                                        <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($zone->price) }}</p>
                                                    </div>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="select-seat-btn cp-primary-button !w-full sm:!w-auto"
                                                    data-zone-id="{{ $zone->id }}"
                                                    data-zone-name="{{ $zone->zone_name }}"
                                                    data-selection-label="{{ $zone->zone_name }}"
                                                    data-parent-label=""
                                                    data-price="{{ \App\Helpers\CurrencyHelper::convert($zone->price) }}"
                                                    data-base-price="{{ $zone->price }}"
                                                    data-available="{{ $zone->available_seats }}"
                                                    data-max-per-order="{{ max(1, $zone->available_seats) }}"
                                                    data-minimum-quantity="1"
                                                    data-type-label="{{ $t('Zone de sièges', 'Seat zone') }}"
                                                    data-meta="{{ $zone->description }}"
                                                >
                                                    <span>{{ $t('Commander cette zone', 'Order this zone') }}</span>
                                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                                </button>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @unless($hasInventory)
                            <div class="mt-6 rounded-[1.9rem] border border-dashed border-[color:var(--cp-border-strong)] bg-[#faf6ff] px-6 py-10 text-center">
                                <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-plum-800)]">{{ $t('Publication en attente', 'Pending publication') }}</p>
                                <p class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Les offres ne sont pas encore visibles.', 'Offers are not visible yet.') }}</p>
                                <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $supportText }}</p>
                                <div class="mt-6 flex flex-col items-center justify-center gap-3 sm:flex-row">
                                    <a href="{{ route('contact') }}" class="cp-primary-button">{{ $t('Contacter un conseiller', 'Contact an advisor') }}</a>
                                    <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button">{{ $t('Appeler maintenant', 'Call now') }}</a>
                                </div>
                            </div>
                        @endunless
                    </section>

                    <section class="event-surface rounded-[2.1rem] p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Informations utiles', 'Useful information') }}</p>
                                <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Les détails restent accessibles', 'Important details stay accessible') }}</h2>
                            </div>
                            <p class="text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Le principal reste devant, le complément reste juste en dessous.', 'The main decision stays upfront, the extra detail stays just below.') }}</p>
                        </div>

                        <div class="mt-6 space-y-3">
                            <details class="event-accordion" open>
                                <summary class="flex items-center justify-between gap-4 px-5 py-4">
                                    <div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Pratique', 'Practical') }}</p>
                                        <p class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $t('Lieu, calendrier et organisation', 'Venue, schedule and organization') }}</p>
                                    </div>
                                    <i class="fa-solid fa-chevron-down text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </summary>
                                <div class="border-t border-[color:var(--cp-border)] px-5 py-5">
                                    <div class="grid gap-4 md:grid-cols-2">
                                        <div class="rounded-[1.35rem] bg-[#faf8ff] px-4 py-4">
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Lieu', 'Venue') }}</p>
                                            <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $event->venue_name ?: $t('À confirmer', 'To be confirmed') }}</p>
                                            @if($event->venue_address)
                                                <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $event->venue_address }}</p>
                                            @endif
                                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ implode(', ', array_filter([$event->city, $event->country])) }}</p>
                                        </div>

                                        <div class="rounded-[1.35rem] bg-[#faf8ff] px-4 py-4">
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Calendrier', 'Schedule') }}</p>
                                            <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $dateLabel }}</p>
                                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $timeLabel ?: $t('Horaire à confirmer', 'Time to be confirmed') }}</p>
                                        </div>

                                        <div class="rounded-[1.35rem] bg-[#faf8ff] px-4 py-4">
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Organisation', 'Organization') }}</p>
                                            <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $event->organizer ?: 'Carré Premium' }}</p>
                                            @if($event->source_catalog)
                                                <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $t('Source catalogue', 'Catalog source') }} : {{ $event->source_catalog }}</p>
                                            @endif
                                            @if($event->series?->name)
                                                <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $t('Série', 'Series') }} : {{ $event->series->name }}</p>
                                            @endif
                                        </div>

                                        <div class="rounded-[1.35rem] bg-[#faf8ff] px-4 py-4">
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Assistance', 'Assistance') }}</p>
                                            <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $t('Conseiller disponible', 'Advisor available') }}</p>
                                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $supportText }}</p>
                                        </div>
                                    </div>
                                </div>
                            </details>

                            @if($eventProgram)
                                <details class="event-accordion">
                                    <summary class="flex items-center justify-between gap-4 px-5 py-4">
                                        <div>
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Programme', 'Program') }}</p>
                                            <p class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $t('Déroulé de l’expérience', 'Experience flow') }}</p>
                                        </div>
                                        <i class="fa-solid fa-chevron-down text-xs text-[color:var(--cp-ink-muted)]"></i>
                                    </summary>
                                    <div class="border-t border-[color:var(--cp-border)] px-5 py-5 whitespace-pre-line text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">{{ $eventProgram }}</div>
                                </details>
                            @endif

                            @if($eventConditions)
                                <details class="event-accordion">
                                    <summary class="flex items-center justify-between gap-4 px-5 py-4">
                                        <div>
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Conditions', 'Conditions') }}</p>
                                            <p class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $t('À savoir avant de valider', 'Know this before validating') }}</p>
                                        </div>
                                        <i class="fa-solid fa-chevron-down text-xs text-[color:var(--cp-ink-muted)]"></i>
                                    </summary>
                                    <div class="border-t border-[color:var(--cp-border)] px-5 py-5 whitespace-pre-line text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">{{ $eventConditions }}</div>
                                </details>
                            @endif
                        </div>
                    </section>
                </div>

                <aside id="reservation-panel" class="xl:sticky xl:top-[calc(var(--cp-header-height,5rem)+1.2rem)] xl:self-start">
                    <div class="event-surface event-sticky-rail rounded-[2.1rem] p-5 sm:p-6">
                        <div class="border-b border-[color:var(--cp-border)] pb-5">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Commande', 'Checkout') }}</p>
                            <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Votre réservation', 'Your booking') }}</h2>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @foreach($bookingSteps as $step)
                                    <span class="rounded-full bg-[#f4edff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                        {{ $step['number'] }} · {{ $step['title'] }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-5 rounded-[1.55rem] bg-[#faf6ff] px-4 py-4">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Événement', 'Event') }}</p>
                            <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $eventTitle }}</p>
                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $dateLabel }}</p>
                            <p class="mt-1 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $locationSummary }}</p>
                        </div>

                        <div id="railSelectionEmpty" class="event-selection-card mt-5 px-4 py-4">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Étape active', 'Active step') }}</p>
                            <p class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $t('Choisissez une offre', 'Choose an offer') }}</p>
                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $t('Le rail se remplit automatiquement dès qu’une formule est sélectionnée.', 'The rail fills automatically as soon as an offer is selected.') }}</p>
                        </div>

                        <div id="railSelectionFilled" class="event-selection-card mt-5 hidden px-4 py-4">
                            <p id="railSelectionType" class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]"></p>
                            <p id="railSelectionName" class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]"></p>
                            <p id="railSelectionMeta" class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]"></p>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2 xl:grid-cols-2">
                                <div class="rounded-[1.2rem] bg-white px-4 py-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Quantité', 'Quantity') }}</p>
                                    <p id="railSelectionQuantity" class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">1</p>
                                </div>
                                <div class="rounded-[1.2rem] bg-white px-4 py-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Total', 'Total') }}</p>
                                    <p id="railSelectionTotal" class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]"></p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2 xl:grid-cols-1">
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('À partir de', 'Starting at') }}</p>
                                <p class="mt-2 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $startingPriceLabel }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Disponibilité', 'Availability') }}</p>
                                <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $availability['label'] }}</p>
                                <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $availability['description'] }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-3">
                            @if($hasInventory)
                                <button id="railChooseButton" type="button" class="cp-primary-button !w-full !justify-center">
                                    <i class="fa-solid fa-ticket text-sm"></i>
                                    <span>{{ $t('Voir les offres', 'See offers') }}</span>
                                </button>
                                <button id="railContinueButton" type="button" class="cp-primary-button hidden !w-full !justify-center">
                                    <i class="fa-solid fa-lock text-sm"></i>
                                    <span>{{ $t('Ouvrir la commande', 'Open checkout') }}</span>
                                </button>
                            @endif
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full !justify-center">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                            </a>
                        </div>

                        <div class="mt-5 rounded-[1.45rem] border border-[color:var(--cp-border)] bg-[#fff8ea] px-4 py-4 text-sm leading-7 text-[#7a5c15]">
                            <span class="font-black text-[#61460f]">{{ $t('Paiement :', 'Payment:') }}</span>
                            {{ $t('au-dessus de 1,5 M XOF, la réservation continue automatiquement en virement bancaire.', 'above 1.5M XOF, the booking automatically continues in bank transfer mode.') }}
                        </div>

                        <div class="mt-5 border-t border-[color:var(--cp-border)] pt-5">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Aide rapide', 'Quick support') }}</p>
                            <div class="mt-4 grid grid-cols-2 gap-3">
                                <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button !justify-center">
                                    <i class="fa-solid fa-phone text-sm"></i>
                                    <span>{{ $t('Appeler', 'Call') }}</span>
                                </a>
                                <a href="{{ config('carre_premium.contact.whatsapp_url') }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !justify-center">
                                    <i class="fa-brands fa-whatsapp text-sm"></i>
                                    <span>WhatsApp</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if($relatedEvents->isNotEmpty())
        <section class="cp-page-section-lg">
            <div class="cp-shell">
                <div class="mb-5">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('À voir aussi', 'Also worth seeing') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Événements liés', 'Related events') }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('D’autres événements proches restent accessibles si vous voulez comparer rapidement.', 'Other nearby events stay available if you want a quick comparison.') }}</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($relatedEvents as $relatedEvent)
                        <article class="event-surface event-card-hover overflow-hidden rounded-[2rem] border border-[color:var(--cp-border)]">
                            <div class="relative">
                                <img src="{{ $relatedEvent->cover_image_url }}" alt="{{ $relatedEvent->title }}" class="h-56 w-full object-cover">
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 to-transparent px-5 pb-4 pt-20">
                                    <span class="rounded-full bg-white/15 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-white">{{ $relatedEvent->short_date_label }}</span>
                                </div>
                            </div>
                            <div class="p-5">
                                <h3 class="text-xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $relatedEvent->title }}</h3>
                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit(app()->getLocale() === 'fr' ? ($relatedEvent->description_fr ?? $relatedEvent->description_en ?? '') : ($relatedEvent->description_en ?? $relatedEvent->description_fr ?? ''), 125) }}</p>
                                <div class="mt-5 flex items-center justify-between gap-4">
                                    <span class="text-sm font-semibold text-[color:var(--cp-ink-soft)]">{{ $relatedEvent->location_label }}</span>
                                    <a href="{{ route('events.show', $relatedEvent->slug) }}" class="cp-primary-button !w-auto !px-4">
                                        <span>{{ $t('Voir', 'View') }}</span>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="cp-page-section-lg">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#26153a] via-[#4d2d72] to-[#d7a147] px-5 py-8 text-white shadow-[0_24px_70px_rgba(41,20,58,0.18)] sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Accompagnement humain', 'Human support') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Un doute avant de commander ?', 'Need help before ordering?') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/80 sm:text-base">{{ $supportText }}</p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                            <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
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

    @if($hasInventory)
        <div class="fixed inset-x-0 bottom-0 z-30 border-t border-[color:var(--cp-border)] bg-white/95 px-4 py-3 shadow-[0_-16px_40px_rgba(41,20,58,0.12)] backdrop-blur lg:hidden">
            <div class="mx-auto flex max-w-3xl items-center gap-4">
                <div class="min-w-0 flex-1">
                    <div id="mobileBookingDefault">
                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('À partir de', 'Starting at') }}</p>
                        <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $startingPriceLabel }}</p>
                    </div>
                    <div id="mobileBookingSelected" class="hidden min-w-0">
                        <p id="mobileSelectedName" class="truncate text-sm font-black text-[color:var(--cp-plum-950)]"></p>
                        <p id="mobileSelectedTotal" class="mt-1 text-sm text-[color:var(--cp-ink-soft)]"></p>
                    </div>
                </div>
                <button id="mobileBookingAction" type="button" class="cp-primary-button !w-auto !px-5">
                    <span id="mobileBookingActionLabel">{{ $t('Offres', 'Offers') }}</span>
                </button>
            </div>
        </div>
    @endif
</div>

@if($hasInventory)
    <div id="bookingModal" class="fixed inset-0 z-50 hidden bg-[#1c112b]/70 p-0 sm:p-4">
        <div class="flex min-h-full items-end justify-center sm:items-center">
            <div class="event-modal-panel w-full max-w-5xl overflow-hidden rounded-t-[2rem] sm:rounded-[2rem]">
                <div class="flex items-center justify-between border-b border-[color:var(--cp-border)] px-5 py-5 sm:px-6">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Commande', 'Order') }}</p>
                        <h3 id="modalTitle" class="mt-1 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Confirmer la sélection', 'Confirm selection') }}</h3>
                    </div>
                    <button id="closeModal" type="button" class="cp-icon-button">
                        <i class="fa-solid fa-xmark text-base"></i>
                    </button>
                </div>

                <div class="max-h-[calc(100vh-1.25rem)] overflow-y-auto px-4 py-5 sm:px-6 sm:py-6">
                    @if($errors->any())
                        <div class="rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                            <p class="font-bold">{{ $t('Le formulaire contient des erreurs.', 'The form contains errors.') }}</p>
                            <ul class="mt-2 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-0 grid gap-5 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
                        <div class="space-y-4">
                            <div class="rounded-[1.7rem] bg-[#faf6ff] p-4">
                                <p id="selectedItemType" class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Offre', 'Offer') }}</p>
                                <p id="selectedItemName" class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]"></p>
                                <p id="selectedItemMeta" class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]"></p>
                            </div>

                            <div class="rounded-[1.7rem] border border-[color:var(--cp-border)] bg-white p-4">
                                <div class="grid gap-3 sm:grid-cols-2">
                                    <div class="rounded-[1.2rem] bg-[#faf8ff] px-4 py-4">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Prix unitaire', 'Unit price') }}</p>
                                        <p id="unitPrice" class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]"></p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-[#faf8ff] px-4 py-4">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Total', 'Total') }}</p>
                                        <p id="totalPrice" class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]"></p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-[#faf8ff] px-4 py-4">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Minimum requis', 'Minimum required') }}</p>
                                        <p id="minRequired" class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">1</p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-[#faf8ff] px-4 py-4">
                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Maximum par commande', 'Maximum per order') }}</p>
                                        <p id="maxAllowed" class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">1</p>
                                    </div>
                                </div>

                                <div class="mt-5 flex items-center gap-3">
                                    <button id="decreaseQty" type="button" class="cp-icon-button !h-12 !w-12">
                                        <i class="fa-solid fa-minus text-xs"></i>
                                    </button>
                                    <div class="min-w-[96px] text-center">
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Quantité', 'Quantity') }}</p>
                                        <p id="quantity" class="mt-1 text-2xl font-black text-[color:var(--cp-plum-950)]">1</p>
                                    </div>
                                    <button id="increaseQty" type="button" class="cp-icon-button !h-12 !w-12">
                                        <i class="fa-solid fa-plus text-xs"></i>
                                    </button>
                                </div>

                                <div id="paymentModeHint" class="mt-5 hidden rounded-[1.25rem] bg-[#fff2de] px-4 py-3 text-sm font-semibold text-[#9a5a07]">
                                    {{ $t('Montant supérieur à 1,5 M XOF : la réservation passera automatiquement en virement bancaire.', 'Amount above 1.5M XOF: the booking will automatically switch to bank transfer.') }}
                                </div>
                            </div>

                            <div class="rounded-[1.6rem] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Vous confirmez une offre précise, avec quantité et total visibles avant le paiement.', 'You confirm a specific offer, with quantity and total visible before payment.') }}
                            </div>
                        </div>

                        <form id="bookingForm" method="POST" action="{{ route('event.book', $event) }}" class="space-y-4">
                            @csrf
                            <input type="hidden" name="zone_id" id="zoneIdInput" value="{{ old('zone_id') }}">
                            <input type="hidden" name="package_id" id="packageIdInput" value="{{ old('package_id') }}">
                            <input type="hidden" name="package_option_id" id="packageOptionIdInput" value="{{ old('package_option_id') }}">
                            <input type="hidden" name="quantity" id="quantityInput" value="{{ old('quantity', 1) }}">

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div class="sm:col-span-2">
                                    <label class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Nom complet', 'Full name') }} *</label>
                                    <input
                                        type="text"
                                        name="name"
                                        required
                                        value="{{ $defaultName }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">Email *</label>
                                    <input
                                        type="email"
                                        name="email"
                                        required
                                        value="{{ $defaultEmail }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Téléphone', 'Phone') }} *</label>
                                    <input
                                        type="tel"
                                        name="phone"
                                        required
                                        value="{{ $defaultPhone }}"
                                        placeholder="{{ config('carre_premium.contact.mobile_display') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                            </div>

                            <div class="rounded-[1.45rem] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Après validation, la réservation est créée puis redirigée vers le bon mode de paiement.', 'After validation, the booking is created and redirected to the correct payment mode.') }}
                            </div>

                            <button type="submit" class="cp-primary-button !w-full !justify-center">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span>{{ $t('Confirmer la réservation', 'Confirm booking') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@if($hasInventory)
    @push('scripts')
    <script>
    window.currentCurrency = @json(\App\Helpers\CurrencyHelper::current());

    document.addEventListener('DOMContentLoaded', function () {
        const eventPage = document.querySelector('[data-event-details]');
        const modal = document.getElementById('bookingModal');
        const closeModal = document.getElementById('closeModal');
        const modalTitleEl = document.getElementById('modalTitle');
        const quantitySpan = document.getElementById('quantity');
        const quantityInput = document.getElementById('quantityInput');
        const zoneIdInput = document.getElementById('zoneIdInput');
        const packageIdInput = document.getElementById('packageIdInput');
        const packageOptionIdInput = document.getElementById('packageOptionIdInput');
        const unitPriceEl = document.getElementById('unitPrice');
        const totalPriceEl = document.getElementById('totalPrice');
        const selectedItemTypeEl = document.getElementById('selectedItemType');
        const selectedItemNameEl = document.getElementById('selectedItemName');
        const selectedItemMetaEl = document.getElementById('selectedItemMeta');
        const minRequiredEl = document.getElementById('minRequired');
        const maxAllowedEl = document.getElementById('maxAllowed');
        const paymentModeHint = document.getElementById('paymentModeHint');
        const decreaseBtn = document.getElementById('decreaseQty');
        const increaseBtn = document.getElementById('increaseQty');
        const railSelectionEmpty = document.getElementById('railSelectionEmpty');
        const railSelectionFilled = document.getElementById('railSelectionFilled');
        const railSelectionType = document.getElementById('railSelectionType');
        const railSelectionName = document.getElementById('railSelectionName');
        const railSelectionMeta = document.getElementById('railSelectionMeta');
        const railSelectionQuantity = document.getElementById('railSelectionQuantity');
        const railSelectionTotal = document.getElementById('railSelectionTotal');
        const railChooseButton = document.getElementById('railChooseButton');
        const railContinueButton = document.getElementById('railContinueButton');
        const mobileBookingDefault = document.getElementById('mobileBookingDefault');
        const mobileBookingSelected = document.getElementById('mobileBookingSelected');
        const mobileSelectedName = document.getElementById('mobileSelectedName');
        const mobileSelectedTotal = document.getElementById('mobileSelectedTotal');
        const mobileBookingAction = document.getElementById('mobileBookingAction');
        const mobileBookingActionLabel = document.getElementById('mobileBookingActionLabel');
        const tabButtons = Array.from(document.querySelectorAll('[data-offer-tab-button]'));
        const tabPanels = Array.from(document.querySelectorAll('[data-offer-tab-panel]'));
        const openBookingErrorsButton = document.querySelector('[data-open-booking-errors]');

        let currentItem = null;
        let currentQuantity = 1;
        let minRequired = 1;
        let maxAllowed = 1;
        let currentSelectionCard = null;
        let currentOfferTab = eventPage?.dataset.initialOfferTab || @json($initialOfferTab);

        const previousSelection = {
            zoneId: @json(old('zone_id')),
            packageId: @json(old('package_id')),
            packageOptionId: @json(old('package_option_id')),
            quantity: Number(@json(old('quantity', 1))) || 1,
            hasErrors: {{ $errors->any() ? 'true' : 'false' }},
        };

        const setOfferTab = (tabId) => {
            if (!tabId) {
                return;
            }

            currentOfferTab = tabId;

            tabButtons.forEach((button) => {
                const isActive = button.dataset.offerTabButton === tabId;
                button.classList.toggle('is-active', isActive);
                button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
            });

            tabPanels.forEach((panel) => {
                panel.classList.toggle('hidden', panel.dataset.offerTabPanel !== tabId);
            });
        };

        const normalizeButtonData = (button, type) => ({
            type,
            id: type === 'seat' ? button.dataset.zoneId : button.dataset.packageId,
            packageId: type === 'package' ? button.dataset.packageId : '',
            packageOptionId: type === 'package' ? (button.dataset.packageOptionId || '') : '',
            name: button.dataset.selectionLabel || (type === 'seat' ? button.dataset.zoneName : button.dataset.packageName),
            meta: [button.dataset.parentLabel || '', button.dataset.meta || ''].filter(Boolean).join(' · '),
            price: parseFloat(button.dataset.price),
            basePrice: parseFloat(button.dataset.basePrice || button.dataset.price),
            available: parseInt(button.dataset.available, 10),
            maxPerOrder: parseInt(button.dataset.maxPerOrder, 10),
            minimumQuantity: parseInt(button.dataset.minimumQuantity, 10),
            typeLabel: button.dataset.typeLabel,
        });

        const formatPrice = (price) => new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: window.currentCurrency,
            minimumFractionDigits: 0,
        }).format(price);

        const scrollToOffers = () => {
            const target = document.getElementById('event-offers');

            if (!target) {
                return;
            }

            const headerOffset = parseFloat(getComputedStyle(document.documentElement).getPropertyValue('--cp-header-height')) || 0;
            const top = target.getBoundingClientRect().top + window.scrollY - headerOffset - 18;

            window.scrollTo({ top, behavior: 'smooth' });
        };

        const showModalWindow = () => {
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeModalWindow = () => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        const highlightSelection = (trigger) => {
            currentSelectionCard?.classList.remove('is-selected');
            currentSelectionCard = trigger?.closest('[data-offer-card]') || null;
            currentSelectionCard?.classList.add('is-selected');
        };

        const syncSelectionPreview = () => {
            if (!currentItem) {
                railSelectionEmpty?.classList.remove('hidden');
                railSelectionFilled?.classList.add('hidden');
                railChooseButton?.classList.remove('hidden');
                railContinueButton?.classList.add('hidden');
                mobileBookingDefault?.classList.remove('hidden');
                mobileBookingSelected?.classList.add('hidden');
                if (mobileBookingActionLabel) {
                    mobileBookingActionLabel.textContent = @json($t('Offres', 'Offers'));
                }
                return;
            }

            const total = currentItem.price * currentQuantity;

            railSelectionEmpty?.classList.add('hidden');
            railSelectionFilled?.classList.remove('hidden');
            railSelectionType.textContent = currentItem.typeLabel;
            railSelectionName.textContent = currentItem.name;
            railSelectionMeta.textContent = currentItem.meta || @json($t('Offre prête à être confirmée.', 'Offer ready to be confirmed.'));
            railSelectionQuantity.textContent = currentQuantity;
            railSelectionTotal.textContent = formatPrice(total);
            railChooseButton?.classList.add('hidden');
            railContinueButton?.classList.remove('hidden');

            mobileBookingDefault?.classList.add('hidden');
            mobileBookingSelected?.classList.remove('hidden');
            mobileSelectedName.textContent = currentItem.name;
            mobileSelectedTotal.textContent = formatPrice(total);
            if (mobileBookingActionLabel) {
                mobileBookingActionLabel.textContent = @json($t('Commander', 'Book'));
            }
        };

        const updateQuantityButtons = () => {
            decreaseBtn.disabled = currentQuantity <= minRequired;
            increaseBtn.disabled = currentQuantity >= maxAllowed;
            decreaseBtn.classList.toggle('opacity-40', decreaseBtn.disabled);
            increaseBtn.classList.toggle('opacity-40', increaseBtn.disabled);
        };

        const updateTotal = () => {
            if (!currentItem) {
                return;
            }

            const total = currentItem.price * currentQuantity;
            totalPriceEl.textContent = formatPrice(total);
            paymentModeHint.classList.toggle('hidden', (currentItem.basePrice * currentQuantity) <= 1500000);
            syncSelectionPreview();
        };

        const syncQuantity = () => {
            quantitySpan.textContent = currentQuantity;
            quantityInput.value = currentQuantity;
            updateQuantityButtons();
            updateTotal();
        };

        const openModalForSelection = (item, trigger = null) => {
            minRequired = item.minimumQuantity;
            maxAllowed = Math.min(item.available, item.maxPerOrder);

            if (maxAllowed < minRequired) {
                alert(@json($t('Cette offre n’est plus disponible pour la quantité minimale requise.', 'This offer is no longer available for the required minimum quantity.')));
                return;
            }

            currentItem = item;
            currentQuantity = minRequired;
            setOfferTab(item.type === 'seat' ? 'zones' : 'packages');

            modalTitleEl.textContent = item.type === 'seat'
                ? @json($t('Commander cette zone', 'Order this seat zone'))
                : @json($t('Commander cette formule', 'Order this package'));
            selectedItemTypeEl.textContent = item.typeLabel;
            selectedItemNameEl.textContent = item.name;
            selectedItemMetaEl.textContent = item.meta || '';
            selectedItemMetaEl.classList.toggle('hidden', !item.meta);
            unitPriceEl.textContent = formatPrice(item.price);
            minRequiredEl.textContent = minRequired;
            maxAllowedEl.textContent = maxAllowed;

            zoneIdInput.value = item.type === 'seat' ? item.id : '';
            packageIdInput.value = item.type === 'package' ? item.packageId : '';
            packageOptionIdInput.value = item.type === 'package' ? (item.packageOptionId || '') : '';

            highlightSelection(trigger);
            syncQuantity();
            showModalWindow();
        };

        document.querySelectorAll('.select-seat-btn').forEach((button) => {
            button.addEventListener('click', function () {
                openModalForSelection(normalizeButtonData(this, 'seat'), this);
            });
        });

        document.querySelectorAll('.select-package-btn').forEach((button) => {
            button.addEventListener('click', function () {
                openModalForSelection(normalizeButtonData(this, 'package'), this);
            });
        });

        tabButtons.forEach((button) => {
            button.addEventListener('click', function () {
                setOfferTab(this.dataset.offerTabButton);
            });
        });

        railChooseButton?.addEventListener('click', scrollToOffers);
        railContinueButton?.addEventListener('click', showModalWindow);
        mobileBookingAction?.addEventListener('click', function () {
            if (currentItem) {
                showModalWindow();
                return;
            }

            scrollToOffers();
        });

        closeModal.addEventListener('click', closeModalWindow);

        modal.addEventListener('click', function (event) {
            if (event.target === modal) {
                closeModalWindow();
            }
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModalWindow();
            }
        });

        decreaseBtn.addEventListener('click', function () {
            if (currentQuantity > minRequired) {
                currentQuantity--;
                syncQuantity();
            }
        });

        increaseBtn.addEventListener('click', function () {
            if (currentQuantity < maxAllowed) {
                currentQuantity++;
                syncQuantity();
            }
        });

        openBookingErrorsButton?.addEventListener('click', function () {
            if (currentItem) {
                showModalWindow();
                return;
            }

            scrollToOffers();
        });

        if (previousSelection.hasErrors) {
            let previousButton = null;

            if (previousSelection.packageOptionId) {
                previousButton = document.querySelector('.select-package-btn[data-package-option-id="' + previousSelection.packageOptionId + '"]');
            } else if (previousSelection.packageId) {
                previousButton = document.querySelector('.select-package-btn[data-package-id="' + previousSelection.packageId + '"][data-package-option-id=""]')
                    || document.querySelector('.select-package-btn[data-package-id="' + previousSelection.packageId + '"]');
            } else if (previousSelection.zoneId) {
                previousButton = document.querySelector('.select-seat-btn[data-zone-id="' + previousSelection.zoneId + '"]');
            }

            if (previousButton) {
                const item = previousButton.classList.contains('select-seat-btn')
                    ? normalizeButtonData(previousButton, 'seat')
                    : normalizeButtonData(previousButton, 'package');

                openModalForSelection(item, previousButton);
                currentQuantity = Math.max(minRequired, Math.min(previousSelection.quantity, maxAllowed));
                syncQuantity();
            } else {
                updateQuantityButtons();
                syncSelectionPreview();
            }
        } else {
            updateQuantityButtons();
            syncSelectionPreview();
        }

        setOfferTab(currentOfferTab);
    });
    </script>
    @endpush
@endif
@endsection
