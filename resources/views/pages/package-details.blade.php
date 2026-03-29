@extends('layouts.app')

@section('title', (($package->title_fr ?? $package->title_en ?? $package->title ?? __('Package')) . ' - Carré Premium'))

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $packageTitle = app()->getLocale() === 'fr'
        ? ($package->title_fr ?? $package->title_en ?? $package->title ?? $package->slug)
        : ($package->title_en ?? $package->title_fr ?? $package->title ?? $package->slug);
    $packageDescription = app()->getLocale() === 'fr'
        ? ($package->description_fr ?? $package->description_en ?? '')
        : ($package->description_en ?? $package->description_fr ?? '');
    $imageUrl = $package->getFirstMediaUrl('avatar', 'normal');
    $placeholder = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200&h=800&fit=crop';
    $unitPrice = (float) ($package->discount_price ?? $package->price ?? 0);
    $basePrice = (float) ($package->price ?? 0);
    $discountPercent = $package->discount_price && $basePrice > 0
        ? max(0, (int) round((1 - ((float) $package->discount_price / $basePrice)) * 100))
        : null;
    $packageTypeMap = [
        'helicopter' => $t('Helicoptere', 'Helicopter'),
        'helicoptere' => $t('Helicoptere', 'Helicopter'),
        'helicoptère' => $t('Helicoptere', 'Helicopter'),
        'private_jet' => $t('Jet prive', 'Private jet'),
        'jet' => $t('Jet prive', 'Private jet'),
        'cruise' => $t('Croisiere', 'Cruise'),
        'safari' => 'Safari',
        'city_tour' => $t('City tour', 'City tour'),
    ];
    $packageTypeLabel = $packageTypeMap[$package->package_type] ?? ucfirst(str_replace('_', ' ', (string) $package->package_type));
    $durationLabel = app()->getLocale() === 'fr'
        ? ($package->duration_text_fr ?: ($package->duration ? $package->duration . ' jours' : $t('Duree sur demande', 'Duration on request')))
        : ($package->duration_text_en ?? $package->duration_text_fr ?? ($package->duration ? $package->duration . ' days' : $t('Duree sur demande', 'Duration on request')));
    $includedServices = collect(app()->getLocale() === 'fr' ? ($package->included_services_fr ?? []) : ($package->included_services_en ?? $package->included_services_fr ?? []))
        ->filter()
        ->values();
    $excludedServices = collect(app()->getLocale() === 'fr' ? ($package->excluded_services_fr ?? []) : ($package->excluded_services_en ?? $package->excluded_services_fr ?? []))
        ->filter()
        ->values();
    $itinerary = collect(app()->getLocale() === 'fr' ? ($package->itinerary_fr ?? []) : ($package->itinerary_en ?? $package->itinerary_fr ?? []))
        ->filter(fn ($day) => filled($day['title'] ?? null) || filled($day['description'] ?? null))
        ->values();
    $gallery = collect($package->gallery ?? [])->filter()->values();
    $participantsMin = max(1, (int) ($package->min_participants ?? 1));
    $participantsMax = max($participantsMin, (int) ($package->max_participants ?? $participantsMin));
    $defaultParticipants = (int) old('participants', $participantsMin);
    $fixedDeparture = $package->event_date_start ? $package->event_date_start->translatedFormat('d F Y') : null;
    $fixedReturn = $package->event_date_end && !$package->event_date_end->isSameDay($package->event_date_start)
        ? $package->event_date_end->translatedFormat('d F Y')
        : null;
    $travelDateLabel = $fixedDeparture
        ? trim($fixedDeparture . ($fixedReturn ? ' -> ' . $fixedReturn : ''))
        : $t('Date flexible selon disponibilite', 'Flexible date depending on availability');
    $startingTotal = $unitPrice * $defaultParticipants;
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#241233] via-[#4c2872] to-[#d89b43] text-white shadow-[0_30px_90px_rgba(41,20,58,0.24)]">
                <div class="grid gap-6 px-5 py-8 sm:px-8 sm:py-10 lg:grid-cols-[minmax(0,1.15fr)_minmax(320px,420px)] lg:px-10 lg:py-12">
                    <div class="max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Package detail', 'Package detail') }}</span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            @if($package->category?->name_fr || $package->category?->name_en)
                                <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-white/90">
                                    {{ app()->getLocale() === 'fr' ? ($package->category->name_fr ?? $package->category->name_en) : ($package->category->name_en ?? $package->category->name_fr) }}
                                </span>
                            @endif
                            @if($packageTypeLabel)
                                <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-white/90">
                                    {{ $packageTypeLabel }}
                                </span>
                            @endif
                            @if($package->is_featured)
                                <span class="rounded-full bg-[color:var(--cp-gold-400)] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#2a163d]">
                                    {{ $t('Selection', 'Featured') }}
                                </span>
                            @endif
                        </div>

                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">
                            {{ $packageTitle }}
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">
                            {{ \Illuminate\Support\Str::limit($packageDescription, 260) }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="rounded-[1.1rem] border border-white/15 bg-white/10 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Destination', 'Destination') }}</p>
                                <p class="mt-1 text-sm font-bold text-white">{{ $package->destination ?: $t('Sur demande', 'On request') }}</p>
                            </div>
                            <div class="rounded-[1.1rem] border border-white/15 bg-white/10 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Duree', 'Duration') }}</p>
                                <p class="mt-1 text-sm font-bold text-white">{{ $durationLabel }}</p>
                            </div>
                            <div class="rounded-[1.1rem] border border-white/15 bg-white/10 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Participants', 'Participants') }}</p>
                                <p class="mt-1 text-sm font-bold text-white">{{ $participantsMin }}-{{ $participantsMax }} {{ $t('pers.', 'pax') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="#booking-form" class="cp-primary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                                <span>{{ $t('Reserver ce package', 'Book this package') }}</span>
                            </a>
                            <a href="{{ route('contact') }}?subject={{ urlencode($t('Demande sur mesure package', 'Custom package request')) }}%20{{ urlencode($packageTitle) }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                                <i class="fa-regular fa-envelope text-sm"></i>
                                <span>{{ $t('Demander du sur-mesure', 'Request something bespoke') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[1.9rem] border border-white/15 bg-white/10 p-4 backdrop-blur sm:p-5">
                        <div class="overflow-hidden rounded-[1.5rem]">
                            <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $packageTitle }}" class="h-64 w-full object-cover sm:h-72">
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Date repere', 'Date cue') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $package->event_date_start ? $package->event_date_start->format('d/m/Y') : $t('Flexible', 'Flexible') }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Depart', 'Departure') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $package->departure_city ?: $t('A confirmer', 'To be confirmed') }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Tarif', 'Price') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ \App\Helpers\CurrencyHelper::format($unitPrice) }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Accompagnement', 'Support') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $t('Equipe concierge', 'Concierge team') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.3fr)_minmax(320px,420px)]">
                <div class="space-y-6">
                    <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Vue d ensemble', 'Overview') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Ce que le client doit comprendre tout de suite', 'What the client should understand immediately') }}</h2>
                            </div>
                            <div class="cp-pill">
                                <i class="fa-solid fa-wallet text-xs"></i>
                                <span>{{ $t('A partir de', 'Starting at') }} {{ \App\Helpers\CurrencyHelper::format($unitPrice) }}</span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Destination', 'Destination') }}</p>
                                <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $package->destination ?: $t('Sur demande', 'On request') }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Duree', 'Duration') }}</p>
                                <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $durationLabel }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#faf6ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Date', 'Date') }}</p>
                                <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $travelDateLabel }}</p>
                            </div>
                        </div>

                        <div class="mt-6 prose max-w-none text-[color:var(--cp-ink-soft)]">
                            {!! nl2br(e($packageDescription)) !!}
                        </div>
                    </section>

                    @if($includedServices->isNotEmpty() || $excludedServices->isNotEmpty())
                        <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Contenu', 'Contents') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Inclus et non inclus', 'Included and excluded') }}</h2>

                            <div class="mt-6 grid gap-5 lg:grid-cols-2">
                                @if($includedServices->isNotEmpty())
                                    <div class="rounded-[1.6rem] bg-[#f7fbf7] p-5">
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[#3f6b44]">{{ $t('Inclus', 'Included') }}</p>
                                        <div class="mt-4 space-y-3">
                                            @foreach($includedServices as $service)
                                                <div class="flex items-start gap-3">
                                                    <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#d9f0db] text-[11px] font-black text-[#275b2d]">+</span>
                                                    <span class="text-sm leading-6 text-[#29402c]">{{ $service }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if($excludedServices->isNotEmpty())
                                    <div class="rounded-[1.6rem] bg-[#fff8f1] p-5">
                                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[#9b5b13]">{{ $t('Non inclus', 'Not included') }}</p>
                                        <div class="mt-4 space-y-3">
                                            @foreach($excludedServices as $service)
                                                <div class="flex items-start gap-3">
                                                    <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#f7d9b6] text-[11px] font-black text-[#8a4904]">-</span>
                                                    <span class="text-sm leading-6 text-[#5f4324]">{{ $service }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </section>
                    @endif

                    @if($itinerary->isNotEmpty())
                        <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Itineraire', 'Itinerary') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Deroule du sejour', 'Journey breakdown') }}</h2>

                            <div class="mt-6 space-y-4">
                                @foreach($itinerary as $index => $day)
                                    <div class="flex gap-4 rounded-[1.5rem] bg-[#faf6ff] px-4 py-4 sm:px-5">
                                        <div class="inline-flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-full bg-[color:var(--cp-plum-800)] text-sm font-black text-white">
                                            {{ $index + 1 }}
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-black text-[color:var(--cp-plum-950)]">
                                                {{ $day['title'] ?? ($t('Jour', 'Day') . ' ' . ($index + 1)) }}
                                            </h3>
                                            @if(!empty($day['description']))
                                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $day['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    @if($gallery->isNotEmpty())
                        <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Galerie', 'Gallery') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Visuels du package', 'Package visuals') }}</h2>

                            <div class="mt-6 grid grid-cols-2 gap-3 sm:grid-cols-3">
                                @foreach($gallery as $galleryImage)
                                    <div class="overflow-hidden rounded-[1.35rem]">
                                        <img src="{{ $galleryImage }}" alt="{{ $packageTitle }}" class="aspect-[4/4] w-full object-cover transition duration-500 hover:scale-105">
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif
                </div>

                <aside id="booking-form" class="lg:sticky lg:top-6 lg:self-start">
                    <div class="cp-panel rounded-[2rem] p-5 sm:p-6">
                        <div class="border-b border-[color:var(--cp-border)] pb-5">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Reservation', 'Booking') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Confirmer ce sejour', 'Confirm this trip') }}</h2>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Le formulaire reste simple: qui part, quand, combien de participants, puis estimation du montant avant paiement.', 'The form stays simple: who travels, when, how many participants, then an estimated total before payment.') }}
                            </p>
                        </div>

                        <div class="mt-5 grid gap-3 sm:grid-cols-2">
                            <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Depart', 'Departure') }}</p>
                                <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $package->departure_city ?: $t('A confirmer', 'To be confirmed') }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-[#faf6ff] px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Participants', 'Participants') }}</p>
                                <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $participantsMin }}-{{ $participantsMax }}</p>
                            </div>
                        </div>

                        @if($errors->any())
                            <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                                <p class="font-bold">{{ $t('Le formulaire contient des erreurs.', 'The form contains errors.') }}</p>
                                <ul class="mt-2 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('packages.book', $package) }}" method="POST" class="mt-5 space-y-5">
                            @csrf

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="first_name" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Prenoms', 'First name') }} *</label>
                                    <input
                                        type="text"
                                        id="first_name"
                                        name="first_name"
                                        required
                                        value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                                <div>
                                    <label for="last_name" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Nom', 'Last name') }} *</label>
                                    <input
                                        type="text"
                                        id="last_name"
                                        name="last_name"
                                        required
                                        value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">Email *</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        required
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Telephone', 'Phone') }} *</label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        required
                                        value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                        placeholder="{{ config('carre_premium.contact.mobile_display') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    @if($package->event_date_start)
                                        <label class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Date de depart', 'Departure date') }}</label>
                                        <div class="rounded-[1.25rem] border border-[color:var(--cp-border)] bg-[#faf6ff] px-4 py-3 text-sm font-semibold text-[color:var(--cp-plum-950)]">
                                            {{ $travelDateLabel }}
                                        </div>
                                        <input type="hidden" name="departure_date" value="{{ $package->event_date_start->format('Y-m-d') }}">
                                    @else
                                        <label for="departure_date" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Date de depart', 'Departure date') }} *</label>
                                        <input
                                            type="date"
                                            id="departure_date"
                                            name="departure_date"
                                            required
                                            min="{{ now()->addDay()->format('Y-m-d') }}"
                                            value="{{ old('departure_date') }}"
                                            class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                        >
                                    @endif
                                </div>

                                <div>
                                    <label for="participants" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Nombre de participants', 'Number of participants') }} *</label>
                                    <select
                                        id="participants"
                                        name="participants"
                                        required
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    >
                                        @for($i = $participantsMin; $i <= $participantsMax; $i++)
                                            <option value="{{ $i }}" {{ $defaultParticipants === $i ? 'selected' : '' }}>
                                                {{ $i }} {{ $i > 1 ? $t('personnes', 'people') : $t('personne', 'person') }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label for="special_requests" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Demandes speciales', 'Special requests') }}</label>
                                <textarea
                                    id="special_requests"
                                    name="special_requests"
                                    rows="4"
                                    class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-plum-700)]"
                                    placeholder="{{ $t('Regime alimentaire, accessibilite, preference de confort, etc.', 'Dietary preference, accessibility, comfort preference, etc.') }}"
                                >{{ old('special_requests') }}</textarea>
                            </div>

                            <div class="rounded-[1.5rem] bg-[#faf6ff] px-4 py-4 sm:px-5">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Prix unitaire', 'Unit price') }}</span>
                                    <span class="text-base font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($unitPrice) }}</span>
                                </div>
                                @if($discountPercent)
                                    <div class="mt-2 flex items-center justify-between gap-4">
                                        <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Tarif catalogue', 'Original price') }}</span>
                                        <span class="text-sm font-semibold text-[color:var(--cp-ink-muted)] line-through">{{ \App\Helpers\CurrencyHelper::format($basePrice) }}</span>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between gap-4">
                                        <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Reduction', 'Discount') }}</span>
                                        <span class="text-sm font-bold text-[#2a7a41]">-{{ $discountPercent }}%</span>
                                    </div>
                                @endif
                                <div class="mt-3 flex items-center justify-between gap-4">
                                    <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Participants', 'Participants') }}</span>
                                    <span id="participant-count" class="text-base font-black text-[color:var(--cp-plum-950)]">{{ $defaultParticipants }}</span>
                                </div>
                                <div class="mt-4 border-t border-[color:var(--cp-border)] pt-4">
                                    <div class="flex items-center justify-between gap-4 text-lg font-black">
                                        <span class="text-[color:var(--cp-plum-950)]">{{ $t('Total estime', 'Estimated total') }}</span>
                                        <span id="total-price" class="text-[color:var(--cp-plum-800)]">{{ \App\Helpers\CurrencyHelper::format($startingTotal) }}</span>
                                    </div>
                                </div>
                            </div>

                            <label class="flex items-start gap-3 rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-4 text-sm text-[color:var(--cp-ink-soft)]">
                                <input type="checkbox" name="terms" required class="mt-1 h-4 w-4 rounded border-[color:var(--cp-border-strong)] text-[color:var(--cp-plum-800)] focus:ring-[color:var(--cp-plum-700)]">
                                <span>
                                    {{ $t("J'accepte les conditions avant la creation de la reservation.", 'I accept the terms before the booking is created.') }}
                                    <a href="{{ route('terms') }}" class="font-bold text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">{{ $t('Voir les conditions', 'View terms') }}</a>
                                </span>
                            </label>

                            <button type="submit" class="cp-primary-button !w-full !justify-center">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span>{{ $t('Continuer vers le paiement', 'Continue to payment') }}</span>
                            </button>
                        </form>

                        <div class="mt-5 border-t border-[color:var(--cp-border)] pt-5">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Besoin d aide', 'Need help') }}</p>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Un conseiller peut verifier la disponibilite, le depart et les ajustements avant paiement.', 'An advisor can verify availability, departure details and adjustments before payment.') }}
                            </p>
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

    @if($similarPackages->count() > 0)
        <section class="cp-page-section-lg">
            <div class="cp-shell">
                <div class="mb-5">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('A considerer aussi', 'Also worth considering') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Packages similaires', 'Similar packages') }}</h2>
                </div>

                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach($similarPackages as $similarPackage)
                        @php
                            $similarTitle = app()->getLocale() === 'fr'
                                ? ($similarPackage->title_fr ?? $similarPackage->title_en ?? $similarPackage->title ?? $similarPackage->slug)
                                : ($similarPackage->title_en ?? $similarPackage->title_fr ?? $similarPackage->title ?? $similarPackage->slug);
                            $similarDescription = app()->getLocale() === 'fr'
                                ? ($similarPackage->description_fr ?? $similarPackage->description_en ?? '')
                                : ($similarPackage->description_en ?? $similarPackage->description_fr ?? '');
                            $similarImageUrl = $similarPackage->getFirstMediaUrl('avatar', 'small');
                            $similarPrice = $similarPackage->discount_price ?? $similarPackage->price;
                        @endphp

                        <article class="overflow-hidden rounded-[2rem] border border-[color:var(--cp-border)] bg-white/95 shadow-[0_18px_55px_rgba(41,20,58,0.10)]">
                            <img src="{{ $similarImageUrl ?: $placeholder }}" alt="{{ $similarTitle }}" class="h-56 w-full object-cover">
                            <div class="p-5">
                                <div class="flex flex-wrap gap-2">
                                    @if($similarPackage->destination)
                                        <span class="rounded-full bg-[#faf6ff] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $similarPackage->destination }}</span>
                                    @endif
                                </div>
                                <h3 class="mt-4 text-xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $similarTitle }}</h3>
                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit($similarDescription, 110) }}</p>
                                <div class="mt-5 flex items-center justify-between gap-4">
                                    <span class="text-lg font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($similarPrice) }}</span>
                                    <a href="{{ route('packages.show', $similarPackage->slug) }}" class="cp-primary-button !w-auto !px-4">
                                        <span>{{ $t('Voir le detail', 'View details') }}</span>
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
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Vous voulez ajuster le voyage avant de payer ?', 'Need to adjust the trip before paying?') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/80 sm:text-base">
                            {{ $t('Nous pouvons confirmer le depart, les participants, les prestations incluses et toute demande speciale avant validation finale.', 'We can confirm departure, participants, included services and special requests before final validation.') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                            <span>{{ $t('Parler a un conseiller', 'Talk to an advisor') }}</span>
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

    <div class="fixed inset-x-0 bottom-0 z-30 border-t border-[color:var(--cp-border)] bg-white/95 px-4 py-3 shadow-[0_-16px_40px_rgba(41,20,58,0.12)] backdrop-blur lg:hidden">
        <div class="mx-auto flex max-w-3xl items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('A partir de', 'Starting at') }}</p>
                <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($unitPrice) }}</p>
            </div>
            <a href="#booking-form" class="cp-primary-button !w-auto !px-5">
                <span>{{ $t('Reserver', 'Book now') }}</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const participantsSelect = document.getElementById('participants');
    const participantCount = document.getElementById('participant-count');
    const totalPrice = document.getElementById('total-price');

    if (!participantsSelect || !participantCount || !totalPrice) {
        return;
    }

    const unitPrice = {{ json_encode($unitPrice) }};
    const currency = '{{ \App\Helpers\CurrencyHelper::current() }}';

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency,
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function updatePrice() {
        const participants = parseInt(participantsSelect.value || '1', 10);
        const total = participants * unitPrice;

        participantCount.textContent = participants;
        totalPrice.textContent = formatCurrency(total);
    }

    participantsSelect.addEventListener('change', updatePrice);
    updatePrice();
});
</script>
@endpush
@endsection
