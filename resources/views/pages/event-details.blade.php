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
            'description' => $t('Des offres sont disponibles et réservable immédiatement.', 'Offers are available and can be booked right away.'),
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
        'Un conseiller peut vous orienter si vous hésitez entre plusieurs packages, options ou zones.',
        'An advisor can guide you if you hesitate between packages, options or seat zones.'
    );
    $defaultName = old('name', trim((auth()->user()?->first_name ?? '') . ' ' . (auth()->user()?->last_name ?? '')));
    $defaultEmail = old('email', auth()->user()?->email ?? '');
    $defaultPhone = old('phone', auth()->user()?->phone ?? '');
    $relatedEvents = collect($relatedEvents ?? [])->filter();
    $startingPriceLabel = $event->min_price ? \App\Helpers\CurrencyHelper::format($event->min_price) : $t('Sur demande', 'On request');
    $leadText = $eventTagline ?: (\Illuminate\Support\Str::limit(strip_tags($eventDescription), 220) ?: $supportText);
    $overviewItems = collect([
        ['label' => $t('Date', 'Date'), 'value' => $dateLabel, 'meta' => $timeLabel ?: $t('Horaire à confirmer', 'Time to be confirmed')],
        ['label' => $t('Lieu', 'Venue'), 'value' => $event->venue_name ?: $t('À confirmer', 'To be confirmed'), 'meta' => $event->location_label ?: $t('Lieu à confirmer', 'Venue to be confirmed')],
        ['label' => $t('À partir de', 'Starting at'), 'value' => $startingPriceLabel, 'meta' => $t('Prix d’entrée constaté', 'Observed entry price')],
        ['label' => $t('Disponibilité', 'Availability'), 'value' => $availability['label'], 'meta' => $availability['description']],
    ]);
    $bookingSteps = [
        [
            'icon' => 'fa-list-check',
            'title' => $t('1. Comprendre l’offre', '1. Understand the offer'),
            'description' => $t('Comparez les packages, les options ou les zones sans perdre le contexte.', 'Compare packages, options or zones without losing the context.'),
        ],
        [
            'icon' => 'fa-ticket',
            'title' => $t('2. Choisir et ajuster', '2. Choose and adjust'),
            'description' => $t('Sélectionnez une offre, puis ajustez la quantité dans une fenêtre claire.', 'Select an offer, then adjust the quantity in a clear modal.'),
        ],
        [
            'icon' => 'fa-credit-card',
            'title' => $t('3. Commander', '3. Place the order'),
            'description' => $t('Renseignez vos coordonnées, puis passez au paiement adapté au montant.', 'Enter your details, then proceed to the payment mode that matches the amount.'),
        ],
    ];
    $reassuranceItems = [
        $t('Lecture claire des offres publiées', 'Clear reading of published offers'),
        $t('Quantité limitée automatiquement par le stock', 'Quantity automatically limited by available stock'),
        $t('Bascule automatique vers virement au-dessus de 1,5 M XOF', 'Automatic bank transfer above 1.5M XOF'),
    ];
    $bookingChecklist = [
        $t('Choisir une seule offre à la fois', 'Choose one offer at a time'),
        $t('Indiquer nom, email et téléphone', 'Provide name, email and phone'),
        $t('Valider puis payer ou suivre les instructions de virement', 'Validate, then pay or follow transfer instructions'),
    ];
@endphp

<div class="min-h-screen pb-24 sm:pb-28">
    <section class="pt-4 sm:pt-6">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-gradient-to-br from-[#1d112c] via-[#4b2870] to-[#d89b43] text-white shadow-[0_34px_100px_rgba(41,20,58,0.24)]">
                <div class="grid gap-7 px-5 py-7 sm:px-8 sm:py-9 xl:grid-cols-[minmax(0,1.15fr)_minmax(320px,380px)] xl:px-10 xl:py-10">
                    <div class="max-w-4xl">
                        <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold uppercase tracking-[0.18em] text-white/68">
                            <a href="{{ route('home') }}" class="transition hover:text-white">Accueil</a>
                            <span>/</span>
                            <a href="{{ route('events') }}" class="transition hover:text-white">Événements</a>
                            <span>/</span>
                            <span class="text-white/92">{{ $eventTitle }}</span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-white/90">{{ $categoryLabel }}</span>
                            <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-white/90">{{ $typeLabel }}</span>
                            @if($familyLabel)
                                <span class="rounded-full border border-white/15 bg-white/10 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-white/90">{{ $familyLabel }}</span>
                            @endif
                            @if($event->is_featured)
                                <span class="rounded-full bg-[color:var(--cp-gold-400)] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#2a163d]">{{ $t('En vedette', 'Featured') }}</span>
                            @endif
                        </div>

                        <h1 class="mt-5 text-3xl font-black leading-tight sm:text-4xl xl:text-[3.4rem]">
                            {{ $eventTitle }}
                        </h1>

                        <p class="mt-4 max-w-3xl text-sm leading-7 text-white/82 sm:text-base">
                            {{ $leadText }}
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            @foreach($overviewItems as $item)
                                <div class="rounded-[1.4rem] border border-white/14 bg-white/10 px-4 py-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/58">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-sm font-black text-white sm:text-base">{{ $item['value'] }}</p>
                                    <p class="mt-2 text-sm leading-6 text-white/70">{{ $item['meta'] }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            @if($hasInventory)
                                <a href="#event-offers" class="cp-primary-button !w-full sm:!w-auto">
                                    <i class="fa-solid fa-ticket text-sm"></i>
                                    <span>{{ $t('Choisir une offre', 'Choose an offer') }}</span>
                                </a>
                            @endif
                            <a href="#booking-journey" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/12 !text-white hover:!bg-white/16">
                                <i class="fa-solid fa-road text-sm"></i>
                                <span>{{ $t('Comprendre la réservation', 'Understand the booking flow') }}</span>
                            </a>
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/12 !text-white hover:!bg-white/16">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                            </a>
                        </div>

                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            @foreach($reassuranceItems as $item)
                                <div class="rounded-[1.25rem] border border-white/10 bg-black/10 px-4 py-4 text-sm leading-6 text-white/82">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 inline-flex h-6 w-6 items-center justify-center rounded-full bg-[color:var(--cp-gold-400)] text-[11px] font-black text-[#2a163d]">+</span>
                                        <span>{{ $item }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="overflow-hidden rounded-[2rem] border border-white/12 bg-white/10 shadow-[0_24px_60px_rgba(20,12,32,0.18)]">
                            <div class="relative">
                                <img src="{{ $imageUrl }}" alt="{{ $eventTitle }}" class="h-72 w-full object-cover sm:h-80">
                                <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/75 via-black/25 to-transparent px-5 pb-5 pt-20">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/70">{{ $t('Repère rapide', 'Quick snapshot') }}</p>
                                    <p class="mt-2 text-2xl font-black text-white">{{ $event->venue_name ?: $eventTitle }}</p>
                                    <p class="mt-2 text-sm text-white/78">{{ implode(' · ', array_filter([$dateLabel, $event->city, $event->country])) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="rounded-[1.8rem] border border-white/12 bg-white/10 p-5 backdrop-blur">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('En résumé', 'In short') }}</p>
                            <div class="mt-4 space-y-3">
                                <div class="rounded-[1.2rem] bg-white/10 px-4 py-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/58">{{ $t('Ce que vous réservez', 'What you are booking') }}</p>
                                    <p class="mt-2 text-sm leading-7 text-white/84">
                                        {{ $hasInventory ? $t('Une offre précise, avec prix, quantité et parcours de commande clairs.', 'A precise offer with clear price, quantity and checkout flow.') : $t('Une demande à suivre avec un conseiller en attendant la publication des offres.', 'A request to follow with an advisor until offers are published.') }}
                                    </p>
                                </div>
                                <div class="rounded-[1.2rem] bg-white/10 px-4 py-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/58">{{ $t('Offres visibles', 'Visible offers') }}</p>
                                    <p class="mt-2 text-2xl font-black text-white">{{ $offerCount }}</p>
                                    <p class="mt-2 text-sm text-white/72">{{ $packageCount }} {{ $t('packages', 'packages') }} · {{ $seatZoneCount }} {{ $t('zones', 'zones') }}</p>
                                </div>
                                <div class="rounded-[1.2rem] bg-white/10 px-4 py-4">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/58">{{ $t('Support humain', 'Human support') }}</p>
                                    <p class="mt-2 text-sm leading-7 text-white/84">{{ $supportText }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="pt-6">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_360px]">
                <div class="space-y-6">
                    <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <div class="grid gap-5 lg:grid-cols-[minmax(0,1.15fr)_minmax(280px,0.85fr)]">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Vue claire', 'Clear view') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Comprendre l’événement avant d’acheter', 'Understand the event before buying') }}</h2>
                                <div class="mt-5 whitespace-pre-line text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">{{ $eventDescription ?: $supportText }}</div>
                            </div>

                            <div class="rounded-[1.7rem] bg-[#faf6ff] p-5">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('À retenir', 'Keep in mind') }}</p>
                                <div class="mt-4 space-y-3">
                                    <div class="rounded-[1.2rem] bg-white px-4 py-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Type d’expérience', 'Experience type') }}</p>
                                        <p class="mt-2 text-sm font-black text-[color:var(--cp-plum-950)]">{{ $typeLabel }}</p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-white px-4 py-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Organisateur', 'Organizer') }}</p>
                                        <p class="mt-2 text-sm font-black text-[color:var(--cp-plum-950)]">{{ $event->organizer ?: 'Carré Premium' }}</p>
                                    </div>
                                    <div class="rounded-[1.2rem] bg-white px-4 py-4">
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Source', 'Source') }}</p>
                                        <p class="mt-2 text-sm font-black text-[color:var(--cp-plum-950)]">{{ $event->source_catalog ?: $t('Fiche Carré Premium', 'Carré Premium page') }}</p>
                                    </div>
                                    @if($event->series?->name)
                                        <div class="rounded-[1.2rem] bg-white px-4 py-4">
                                            <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Série', 'Series') }}</p>
                                            <p class="mt-2 text-sm font-black text-[color:var(--cp-plum-950)]">{{ $event->series->name }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="booking-journey" class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Commande simplifiée', 'Simplified checkout') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Comment la réservation se passe maintenant', 'How the booking works now') }}</h2>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                    {{ $t('Le parcours est volontairement simple : lire, choisir, confirmer, payer. Chaque étape doit répondre à une seule question à la fois.', 'The flow is intentionally simple: read, choose, confirm, pay. Each step should answer one question at a time.') }}
                                </p>
                            </div>

                            <span class="inline-flex items-center gap-2 rounded-full px-3 py-2 text-xs font-black uppercase tracking-[0.18em] {{ $availability['classes'] }}">
                                <i class="fa-solid fa-circle text-[9px]"></i>
                                <span>{{ $availability['label'] }}</span>
                            </span>
                        </div>

                        <div class="mt-6 grid gap-4 lg:grid-cols-3">
                            @foreach($bookingSteps as $step)
                                <article class="rounded-[1.7rem] border border-[color:var(--cp-border)] bg-[#fcfbff] p-5 shadow-[0_14px_34px_rgba(31,17,44,0.06)]">
                                    <span class="inline-flex h-12 w-12 items-center justify-center rounded-[1.2rem] bg-[#f3ebff] text-[color:var(--cp-plum-800)]">
                                        <i class="fa-solid {{ $step['icon'] }} text-base"></i>
                                    </span>
                                    <h3 class="mt-4 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $step['title'] }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $step['description'] }}</p>
                                </article>
                            @endforeach
                        </div>

                        <div class="mt-6 rounded-[1.6rem] border border-[color:var(--cp-border)] bg-[#fff8ea] px-5 py-4 text-sm leading-7 text-[#7a5c15]">
                            <span class="font-black text-[#61460f]">{{ $t('Paiement :', 'Payment:') }}</span>
                            {{ $t('si le total dépasse 1,5 M XOF, le site bascule automatiquement vers le virement bancaire. En dessous, le paiement standard continue.', 'if the total exceeds 1.5M XOF, the site automatically switches to bank transfer. Below that, the standard checkout continues.') }}
                        </div>
                    </section>

                    <section id="event-offers" class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Choix', 'Selection') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Choisissez l’offre qui vous convient', 'Choose the offer that fits you') }}</h2>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                    {{ $t('Chaque carte explique ce que vous réservez, à quel prix et avec quel niveau de disponibilité avant de lancer la commande.', 'Each card explains what you are booking, at what price and with what availability before starting the order.') }}
                                </p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if($packageCount)
                                    <span class="cp-pill"><i class="fa-solid fa-box-open text-xs"></i><span>{{ $packageCount }} {{ $t('packages', 'packages') }}</span></span>
                                @endif
                                @if($seatZoneCount)
                                    <span class="cp-pill"><i class="fa-solid fa-couch text-xs"></i><span>{{ $seatZoneCount }} {{ $t('zones', 'zones') }}</span></span>
                                @endif
                            </div>
                        </div>

                        @if($hasPackages)
                            <div class="mt-6 space-y-5">
                                @foreach($event->packages as $package)
                                    @php
                                        $packageDescription = app()->getLocale() === 'fr'
                                            ? ($package->description_fr ?? $package->description_en ?? '')
                                            : ($package->description_en ?? $package->description_fr ?? '');
                                        $includedSource = app()->getLocale() === 'fr'
                                            ? ($package->description_included_fr ?? $package->description_included_en ?? '')
                                            : ($package->description_included_en ?? $package->description_included_fr ?? '');
                                        $includedLines = collect(preg_split('/\r\n|\r|\n/', (string) $includedSource))
                                            ->map(fn ($line) => trim($line))
                                            ->filter()
                                            ->take(6);
                                    @endphp

                                    <article class="overflow-hidden rounded-[1.95rem] border border-[color:var(--cp-border)] bg-white shadow-[0_20px_45px_rgba(31,17,44,0.08)]">
                                        <div class="grid gap-0 lg:grid-cols-[minmax(0,1.25fr)_300px]">
                                            <div class="p-5 sm:p-6">
                                                <div class="flex flex-wrap gap-2">
                                                    @if($package->package_code)
                                                        <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $package->package_code }}</span>
                                                    @endif
                                                    <span class="rounded-full bg-[#f8fafc] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">
                                                        {{ $t('Quantité min.', 'Minimum qty') }} {{ max(1, $package->minimum_quantity ?? 1) }}
                                                    </span>
                                                    @if($package->has_options)
                                                        <span class="rounded-full bg-[#fff7e8] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#91610e]">
                                                            {{ $t('Plusieurs options', 'Multiple options') }}
                                                        </span>
                                                    @endif
                                                </div>

                                                <h3 class="mt-4 text-2xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $package->name }}</h3>
                                                @if($package->venue_details)
                                                    <p class="mt-2 text-sm font-semibold text-[color:var(--cp-plum-800)]">{{ $package->venue_details }}</p>
                                                @endif
                                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                    {{ $packageDescription ?: $t('Offre publiée sans description détaillée supplémentaire.', 'Published offer without additional detailed description.') }}
                                                </p>

                                                @if($includedLines->isNotEmpty())
                                                    <div class="mt-5 rounded-[1.55rem] bg-[#f8fafc] px-4 py-4">
                                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Ce qui est inclus', 'What is included') }}</p>
                                                        <div class="mt-3 grid gap-3 sm:grid-cols-2">
                                                            @foreach($includedLines as $line)
                                                                <div class="flex items-start gap-3">
                                                                    <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#ede4ff] text-[11px] font-black text-[color:var(--cp-plum-800)]">+</span>
                                                                    <span class="text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $line }}</span>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="border-t border-[color:var(--cp-border)] bg-[#fbf8ff] p-5 sm:p-6 lg:border-l lg:border-t-0">
                                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Repère commande', 'Booking snapshot') }}</p>
                                                <p class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($package->price) }}</p>
                                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                    {{ $package->available_quantity }} {{ $t('disponibles', 'available') }} · {{ $t('max', 'max') }} {{ max(1, $package->max_per_order ?? 1) }} {{ $t('par commande', 'per order') }}
                                                </p>

                                                @if($package->has_options)
                                                    <div class="mt-5 space-y-3">
                                                        @foreach($package->options as $option)
                                                            <div class="rounded-[1.4rem] border border-[color:var(--cp-border)] bg-white px-4 py-4">
                                                                <div class="flex flex-col gap-4">
                                                                    <div>
                                                                        <p class="text-base font-black text-[color:var(--cp-plum-950)]">{{ $option->label }}</p>
                                                                        @if($option->context)
                                                                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $option->context }}</p>
                                                                        @endif
                                                                        <div class="mt-3 flex flex-wrap gap-2 text-xs font-semibold text-[color:var(--cp-ink-muted)]">
                                                                            @if($option->option_date)
                                                                                <span class="rounded-full bg-[#faf6ff] px-3 py-1">{{ $option->option_date->translatedFormat('d M Y') }}</span>
                                                                            @endif
                                                                            <span class="rounded-full bg-[#f8fafc] px-3 py-1">{{ $option->available_quantity }} {{ $t('disponibles', 'available') }}</span>
                                                                        </div>
                                                                    </div>

                                                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                                                        <p class="text-xl font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($option->price) }}</p>
                                                                        <button
                                                                            type="button"
                                                                            class="select-package-btn cp-primary-button !w-full sm:!w-auto"
                                                                            data-package-id="{{ $package->id }}"
                                                                            data-package-option-id="{{ $option->id }}"
                                                                            data-package-name="{{ $package->name }}"
                                                                            data-selection-label="{{ $option->full_label }}"
                                                                            data-price="{{ $option->price }}"
                                                                            data-available="{{ $option->available_quantity }}"
                                                                            data-max-per-order="{{ max(1, $option->max_per_order ?? $package->max_per_order ?? 1) }}"
                                                                            data-minimum-quantity="{{ max(1, $package->minimum_quantity ?? 1) }}"
                                                                            data-type-label="{{ $t('Formule VIP', 'VIP package') }}"
                                                                            data-meta="{{ $option->full_label }}"
                                                                        >
                                                                            <span>{{ $t('Commander cette option', 'Order this option') }}</span>
                                                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="mt-5 rounded-[1.4rem] border border-[color:var(--cp-border)] bg-white px-4 py-4">
                                                        <p class="text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                            {{ $t('Formule directe : choisissez la quantité puis confirmez vos coordonnées.', 'Direct package: choose the quantity and confirm your details.') }}
                                                        </p>
                                                        <button
                                                            type="button"
                                                            class="select-package-btn cp-primary-button !mt-4 !w-full"
                                                            data-package-id="{{ $package->id }}"
                                                            data-package-option-id=""
                                                            data-package-name="{{ $package->name }}"
                                                            data-selection-label="{{ $package->name }}"
                                                            data-price="{{ $package->price }}"
                                                            data-available="{{ $package->available_quantity }}"
                                                            data-max-per-order="{{ max(1, $package->max_per_order ?? 1) }}"
                                                            data-minimum-quantity="{{ max(1, $package->minimum_quantity ?? 1) }}"
                                                            data-type-label="{{ $t('Formule VIP', 'VIP package') }}"
                                                            data-meta="{{ $package->venue_details }}"
                                                        >
                                                            <span>{{ $t('Commander cette formule', 'Order this package') }}</span>
                                                            <i class="fa-solid fa-arrow-right text-xs"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        @endif

                        @if($hasSeatZones)
                            <div class="@if($hasPackages) mt-8 border-t border-[color:var(--cp-border)] pt-8 @endif">
                                <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Billetterie directe', 'Direct ticketing') }}</p>
                                        <h3 class="mt-2 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Zones de sièges', 'Seat zones') }}</h3>
                                    </div>
                                    <p class="text-sm leading-6 text-[color:var(--cp-ink-muted)]">{{ $t('Pour aller plus vite si vous connaissez déjà votre zone.', 'Faster if you already know your preferred area.') }}</p>
                                </div>

                                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                                    @foreach($event->seatZones as $zone)
                                        <article class="rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white px-5 py-5 shadow-[0_16px_34px_rgba(31,17,44,0.06)]">
                                            <div class="flex flex-wrap gap-2">
                                                @if($zone->zone_code)
                                                    <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $zone->zone_code }}</span>
                                                @endif
                                                @if($zone->zone_type)
                                                    <span class="rounded-full bg-[#f8fafc] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ str_replace('_', ' ', $zone->zone_type) }}</span>
                                                @endif
                                            </div>

                                            <div class="mt-4 flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                                <div class="max-w-xl">
                                                    <h4 class="text-xl font-black text-[color:var(--cp-plum-950)]">{{ $zone->zone_name }}</h4>
                                                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                                        {{ $zone->description ?: $t('Accès direct sans package, avec sélection de quantité à l’étape suivante.', 'Direct access without package, with quantity selection in the next step.') }}
                                                    </p>
                                                </div>

                                                <div class="rounded-[1.25rem] bg-[#faf6ff] px-4 py-4 text-left sm:min-w-[150px] sm:text-right">
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Prix', 'Price') }}</p>
                                                    <p class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($zone->price) }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-5 flex flex-col gap-4 rounded-[1.4rem] bg-[#f8fafc] px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
                                                <div>
                                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Disponibilité', 'Availability') }}</p>
                                                    <p class="mt-2 text-sm font-semibold text-[color:var(--cp-plum-950)]">{{ $zone->available_seats }} {{ $t('places', 'seats') }}</p>
                                                </div>

                                                <button
                                                    type="button"
                                                    class="select-seat-btn cp-primary-button !w-full sm:!w-auto"
                                                    data-zone-id="{{ $zone->id }}"
                                                    data-zone-name="{{ $zone->zone_name }}"
                                                    data-selection-label="{{ $zone->zone_name }}"
                                                    data-price="{{ $zone->price }}"
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

                    <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Informations pratiques', 'Practical information') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Lieu, organisation et repères utiles', 'Venue, organization and useful references') }}</h2>

                        <div class="mt-6 grid gap-4 md:grid-cols-2">
                            <div class="rounded-[1.6rem] bg-[#faf6ff] px-5 py-5">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Lieu', 'Venue') }}</p>
                                <p class="mt-3 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $event->venue_name ?: $t('À confirmer', 'To be confirmed') }}</p>
                                @if($event->venue_address)
                                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $event->venue_address }}</p>
                                @endif
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ implode(', ', array_filter([$event->city, $event->country])) }}</p>
                            </div>

                            <div class="rounded-[1.6rem] bg-[#faf6ff] px-5 py-5">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Organisation', 'Organization') }}</p>
                                <p class="mt-3 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $event->organizer ?: 'Carré Premium' }}</p>
                                @if($event->source_catalog)
                                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Source catalogue', 'Catalog source') }} : {{ $event->source_catalog }}</p>
                                @endif
                                @if($event->series?->name)
                                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Série', 'Series') }} : {{ $event->series->name }}</p>
                                @endif
                            </div>

                            <div class="rounded-[1.6rem] bg-[#faf6ff] px-5 py-5">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Calendrier', 'Schedule') }}</p>
                                <p class="mt-3 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $dateLabel }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $timeLabel ?: $t('Heure à confirmer', 'Time to be confirmed') }}</p>
                            </div>

                            <div class="rounded-[1.6rem] bg-[#faf6ff] px-5 py-5">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Assistance', 'Assistance') }}</p>
                                <p class="mt-3 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $t('Conseiller disponible', 'Advisor available') }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $supportText }}</p>
                            </div>
                        </div>
                    </section>

                    @if($eventProgram || $eventConditions)
                        <section class="grid gap-6 lg:grid-cols-2">
                            @if($eventProgram)
                                <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Programme', 'Program') }}</p>
                                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Déroulé de l’expérience', 'Experience flow') }}</h2>
                                    <div class="mt-5 whitespace-pre-line text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">{{ $eventProgram }}</div>
                                </section>
                            @endif

                            @if($eventConditions)
                                <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Conditions', 'Conditions') }}</p>
                                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('À savoir avant de valider', 'Know this before confirming') }}</h2>
                                    <div class="mt-5 whitespace-pre-line text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">{{ $eventConditions }}</div>
                                </section>
                            @endif
                        </section>
                    @endif
                </div>

                <aside id="reservation-panel" class="xl:sticky xl:top-6 xl:self-start">
                    <div class="cp-panel rounded-[2rem] p-5 sm:p-6">
                        <div class="border-b border-[color:var(--cp-border)] pb-5">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Commande', 'Checkout') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Réserver sans se perdre', 'Book without getting lost') }}</h2>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Cette colonne résume le parcours jusqu’au paiement : offre, coordonnées, validation.', 'This panel sums up the flow to payment: offer, contact details, validation.') }}
                            </p>
                        </div>

                        <div class="mt-5 rounded-[1.5rem] bg-[#faf6ff] px-4 py-4">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Événement', 'Event') }}</p>
                            <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $eventTitle }}</p>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $dateLabel }} · {{ $event->city ?: $t('Ville à confirmer', 'City to be confirmed') }}</p>
                        </div>

                        <div class="mt-5 space-y-3">
                            @foreach($bookingChecklist as $index => $item)
                                <div class="flex items-start gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white px-4 py-4">
                                    <span class="inline-flex h-7 w-7 flex-none items-center justify-center rounded-full bg-[#f3ebff] text-[12px] font-black text-[color:var(--cp-plum-800)]">{{ $index + 1 }}</span>
                                    <span class="text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $item }}</span>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 grid gap-3">
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Prix d’entrée', 'Entry price') }}</p>
                                <p class="mt-2 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $startingPriceLabel }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Disponibilité', 'Availability') }}</p>
                                <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $availability['label'] }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $availability['description'] }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Offres publiées', 'Published offers') }}</p>
                                <p class="mt-2 text-base font-black text-[color:var(--cp-plum-950)]">{{ $offerCount }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $packageCount }} {{ $t('packages', 'packages') }} · {{ $seatZoneCount }} {{ $t('zones', 'zones') }}</p>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-3">
                            @if($hasInventory)
                                <a href="#event-offers" class="cp-primary-button !w-full !justify-center">
                                    <i class="fa-solid fa-ticket text-sm"></i>
                                    <span>{{ $t('Choisir une offre', 'Choose an offer') }}</span>
                                </a>
                            @endif
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full !justify-center">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Demander de l’aide', 'Ask for help') }}</span>
                            </a>
                        </div>

                        <div class="mt-5 rounded-[1.5rem] border border-[color:var(--cp-border)] bg-[#fff8ea] px-4 py-4 text-sm leading-7 text-[#7a5c15]">
                            <span class="font-black text-[#61460f]">{{ $t('Paiement :', 'Payment:') }}</span>
                            {{ $t('au-dessus de 1,5 M XOF, le parcours continue en virement bancaire.', 'above 1.5M XOF, the flow continues with bank transfer.') }}
                        </div>

                        <div class="mt-5 border-t border-[color:var(--cp-border)] pt-5">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Contact direct', 'Direct contact') }}</p>
                            <div class="mt-4 flex flex-col gap-3">
                                <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button !justify-center">
                                    <i class="fa-solid fa-phone text-sm"></i>
                                    <span>{{ config('carre_premium.contact.mobile_display') }}</span>
                                </a>
                                <a href="mailto:{{ config('carre_premium.contact.support_email') }}" class="cp-secondary-button !justify-center">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                    <span>{{ config('carre_premium.contact.support_email') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if($relatedEvents->isNotEmpty())
        <section class="pt-10 sm:pt-12">
            <div class="cp-shell">
                <div class="mb-5">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('À voir aussi', 'Also worth seeing') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Événements liés', 'Related events') }}</h2>
                    <p class="mt-2 max-w-2xl text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Si cette expérience vous intéresse, voici d’autres événements proches en logique ou en catégorie.', 'If this experience interests you, here are other events close in logic or category.') }}</p>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($relatedEvents as $relatedEvent)
                        <article class="overflow-hidden rounded-[2rem] border border-[color:var(--cp-border)] bg-white/95 shadow-[0_18px_55px_rgba(41,20,58,0.10)]">
                            <img src="{{ $relatedEvent->cover_image_url }}" alt="{{ $relatedEvent->title }}" class="h-56 w-full object-cover">
                            <div class="p-5">
                                <div class="flex flex-wrap gap-2">
                                    <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                        {{ $relatedEvent->short_date_label }}
                                    </span>
                                </div>
                                <h3 class="mt-4 text-xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $relatedEvent->title }}</h3>
                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit(app()->getLocale() === 'fr' ? ($relatedEvent->description_fr ?? $relatedEvent->description_en ?? '') : ($relatedEvent->description_en ?? $relatedEvent->description_fr ?? ''), 120) }}</p>
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

    <section class="pt-10 sm:pt-12">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#26153a] via-[#4d2d72] to-[#d7a147] px-5 py-8 text-white shadow-[0_24px_70px_rgba(41,20,58,0.18)] sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Accompagnement humain', 'Human support') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Besoin d’aide avant de confirmer votre choix ?', 'Need help before confirming your choice?') }}</h2>
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
            <div class="mx-auto flex max-w-3xl items-center justify-between gap-4">
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('À partir de', 'Starting at') }}</p>
                    <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $startingPriceLabel }}</p>
                </div>
                <a href="#event-offers" class="cp-primary-button !w-auto !px-5">
                    <span>{{ $t('Choisir', 'Choose') }}</span>
                </a>
            </div>
        </div>
    @endif
</div>

<div id="bookingModal" class="fixed inset-0 z-50 hidden bg-black/60 p-3 sm:p-4">
    <div class="flex min-h-full items-end justify-center sm:items-center">
        <div class="w-full max-w-3xl overflow-hidden rounded-[2rem] bg-white shadow-2xl">
            <div class="flex items-center justify-between border-b border-[color:var(--cp-border)] px-5 py-5 sm:px-6">
                <div>
                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Commande', 'Order') }}</p>
                    <h3 id="modalTitle" class="mt-1 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Confirmer la sélection', 'Confirm selection') }}</h3>
                </div>
                <button id="closeModal" type="button" class="cp-icon-button">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>

            <div class="max-h-[calc(100vh-4rem)] overflow-y-auto px-5 py-5 sm:px-6">
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
                        <div class="rounded-[1.6rem] bg-[#faf6ff] p-4">
                            <p id="selectedItemType" class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Offre', 'Offer') }}</p>
                            <p id="selectedItemName" class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]"></p>
                            <p id="selectedItemMeta" class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]"></p>
                        </div>

                        <div class="rounded-[1.6rem] border border-[color:var(--cp-border)] p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Prix unitaire', 'Unit price') }}</span>
                                <span id="unitPrice" class="text-base font-black text-[color:var(--cp-plum-950)]"></span>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Minimum requis', 'Minimum required') }}</span>
                                <span id="minRequired" class="text-base font-black text-[color:var(--cp-plum-950)]">1</span>
                            </div>
                            <div class="mt-2 flex items-center justify-between">
                                <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Maximum par commande', 'Maximum per order') }}</span>
                                <span id="maxAllowed" class="text-base font-black text-[color:var(--cp-plum-950)]">1</span>
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

                            <div class="mt-5 border-t border-[color:var(--cp-border)] pt-4">
                                <div class="flex items-center justify-between text-lg font-black">
                                    <span class="text-[color:var(--cp-plum-950)]">{{ $t('Total', 'Total') }}</span>
                                    <span id="totalPrice" class="text-[color:var(--cp-plum-800)]"></span>
                                </div>
                            </div>

                            <div id="paymentModeHint" class="mt-4 hidden rounded-[1.25rem] bg-[#fff2de] px-4 py-3 text-sm font-semibold text-[#9a5a07]">
                                {{ $t('Montant supérieur à 1,5 M XOF : la réservation passera automatiquement en virement bancaire.', 'Amount above 1.5M XOF: the booking will automatically switch to bank transfer.') }}
                            </div>
                        </div>

                        <div class="rounded-[1.6rem] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Vous confirmez ici une offre précise, puis le site vous redirige vers le mode de paiement correspondant au montant total.', 'You confirm a specific offer here, then the site redirects you to the payment mode that matches the total amount.') }}
                        </div>
                    </div>

                    <form id="bookingForm" method="POST" action="{{ route('event.book', $event) }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="zone_id" id="zoneIdInput" value="{{ old('zone_id') }}">
                        <input type="hidden" name="package_id" id="packageIdInput" value="{{ old('package_id') }}">
                        <input type="hidden" name="package_option_id" id="packageOptionIdInput" value="{{ old('package_option_id') }}">
                        <input type="hidden" name="quantity" id="quantityInput" value="{{ old('quantity', 1) }}">

                        <div>
                            <label class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Nom complet', 'Full name') }} *</label>
                            <input
                                type="text"
                                name="name"
                                required
                                value="{{ $defaultName }}"
                                class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                            >
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
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

                        <div class="rounded-[1.4rem] bg-[#f8fafc] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Après validation, la réservation est créée puis vous êtes redirigé vers le paiement ou les instructions de virement selon le total.', 'After validation, the booking is created and you are redirected to payment or transfer instructions depending on the total.') }}
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

@push('scripts')
<script>
window.currentCurrency = 'XOF';

document.addEventListener('DOMContentLoaded', function () {
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

    let currentItem = null;
    let currentQuantity = 1;
    let minRequired = 1;
    let maxAllowed = 1;

    const previousSelection = {
        zoneId: @json(old('zone_id')),
        packageId: @json(old('package_id')),
        packageOptionId: @json(old('package_option_id')),
        quantity: Number(@json(old('quantity', 1))) || 1,
        hasErrors: {{ $errors->any() ? 'true' : 'false' }},
    };

    const normalizeButtonData = (button, type) => ({
        type,
        id: type === 'seat' ? button.dataset.zoneId : button.dataset.packageId,
        packageId: type === 'package' ? button.dataset.packageId : '',
        packageOptionId: type === 'package' ? (button.dataset.packageOptionId || '') : '',
        name: type === 'seat' ? button.dataset.zoneName : button.dataset.packageName,
        meta: button.dataset.meta || button.dataset.selectionLabel || '',
        price: parseFloat(button.dataset.price),
        available: parseInt(button.dataset.available, 10),
        maxPerOrder: parseInt(button.dataset.maxPerOrder, 10),
        minimumQuantity: parseInt(button.dataset.minimumQuantity, 10),
        typeLabel: button.dataset.typeLabel,
    });

    const updateQuantityButtons = () => {
        decreaseBtn.disabled = currentQuantity <= minRequired;
        increaseBtn.disabled = currentQuantity >= maxAllowed;
        decreaseBtn.classList.toggle('opacity-40', decreaseBtn.disabled);
        increaseBtn.classList.toggle('opacity-40', increaseBtn.disabled);
    };

    const openModalForSelection = (item) => {
        minRequired = item.minimumQuantity;
        maxAllowed = Math.min(item.available, item.maxPerOrder);

        if (maxAllowed < minRequired) {
            alert(@json($t('Cette offre n’est plus disponible pour la quantité minimale requise.', 'This offer is no longer available for the required minimum quantity.')));
            return;
        }

        currentItem = item;
        currentQuantity = minRequired;

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

        syncQuantity();
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    };

    document.querySelectorAll('.select-seat-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openModalForSelection(normalizeButtonData(this, 'seat'));
        });
    });

    document.querySelectorAll('.select-package-btn').forEach((button) => {
        button.addEventListener('click', function () {
            openModalForSelection(normalizeButtonData(this, 'package'));
        });
    });

    const closeModalWindow = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    };

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

    function syncQuantity() {
        quantitySpan.textContent = currentQuantity;
        quantityInput.value = currentQuantity;
        updateQuantityButtons();
        updateTotal();
    }

    function updateTotal() {
        if (!currentItem) {
            return;
        }

        const total = currentItem.price * currentQuantity;
        totalPriceEl.textContent = formatPrice(total);

        if (total > 1500000) {
            paymentModeHint.classList.remove('hidden');
        } else {
            paymentModeHint.classList.add('hidden');
        }
    }

    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: window.currentCurrency,
            minimumFractionDigits: 0,
        }).format(price);
    }

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

            openModalForSelection(item);
            currentQuantity = Math.max(minRequired, Math.min(previousSelection.quantity, maxAllowed));
            syncQuantity();
        }
    } else {
        updateQuantityButtons();
    }
});
</script>
@endpush
@endsection
