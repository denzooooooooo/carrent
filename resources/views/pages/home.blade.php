@extends('layouts.app')

@section('title', 'Accueil - Carré Premium')
@section('meta_description', 'Carré Premium simplifie l’accès aux événements VIP, packages signature, location premium et demandes de vols accompagnées avec un parcours clair et un support humain visible.')
@section('meta_keywords', 'conciergerie premium, événements VIP, packages luxe, location premium, vols accompagnés, Carré Premium, Abidjan')
@section('og_title', 'Accueil - Carré Premium')
@section('og_description', 'Événements VIP, packages signature, mobilité premium et accompagnement humain dans un parcours plus clair, plus cohérent et plus rassurant.')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $supportPhone = config('carre_premium.contact.mobile_display', '+225 01 01 22 15 15');
    $supportPhoneLink = config('carre_premium.contact.mobile_link', 'tel:+2250101221515');
    $supportEmail = config('carre_premium.contact.support_email', 'infos@carrepremium.com');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url', 'https://wa.me/2250101221515');
    $events = $events ?? collect();
    $featuredPackages = $featuredPackages ?? collect();
    $featuredLocations = $featuredLocations ?? collect();
    $stats = $stats ?? [];

    $heroStats = [
        [
            'label' => $t('Événements actifs', 'Active events'),
            'value' => number_format((int) ($stats['events'] ?? $events->count()), 0, ',', ' '),
        ],
        [
            'label' => $t('Packages signature', 'Signature packages'),
            'value' => number_format((int) ($stats['packages'] ?? $featuredPackages->count()), 0, ',', ' '),
        ],
        [
            'label' => $t('Solutions mobilité', 'Mobility solutions'),
            'value' => number_format((int) ($stats['locations'] ?? $featuredLocations->count()), 0, ',', ' '),
        ],
    ];

    $serviceCards = [
        [
            'title' => $t('Événements VIP', 'VIP events'),
            'description' => $t('Matchs, concerts et expériences premium avec une lecture plus simple des offres, du prix et du parcours de réservation.', 'Matches, concerts and premium experiences with a clearer reading of offers, pricing and booking flow.'),
            'route' => route('events'),
            'cta' => $t('Voir les événements', 'See events'),
            'icon' => 'fa-ticket',
        ],
        [
            'title' => $t('Packages signature', 'Signature packages'),
            'description' => $t('Séjours, circuits et expériences exclusives présentés avec un vrai niveau de comparaison et un support sur mesure.', 'Trips, circuits and exclusive experiences presented with true comparison and tailored support.'),
            'route' => route('packages'),
            'cta' => $t('Explorer les packages', 'Explore packages'),
            'icon' => 'fa-suitcase-rolling',
        ],
        [
            'title' => $t('Location premium', 'Premium rental'),
            'description' => $t('Véhicules, transferts et solutions de mobilité premium avec repères clairs sur la capacité, le prix et le service.', 'Vehicles, transfers and premium mobility solutions with clear guidance on capacity, pricing and service.'),
            'route' => route('location'),
            'cta' => $t('Voir la flotte', 'See the fleet'),
            'icon' => 'fa-car-side',
        ],
        [
            'title' => $t('Vols accompagnés', 'Supported flights'),
            'description' => $t('Demandes de vols traitées avec accompagnement humain pour sécuriser la recherche, la validation et le paiement.', 'Flight requests handled with human guidance to secure research, validation and payment.'),
            'route' => route('flights.index'),
            'cta' => $t('Parler à un conseiller', 'Talk to an advisor'),
            'icon' => 'fa-plane-departure',
        ],
    ];

    $trustSignals = [
        $t('Support humain visible', 'Visible human support'),
        $t('Parcours mobile-first', 'Mobile-first journey'),
        $t('Paiement et accompagnement clarifiés', 'Payment and guidance clarified'),
    ];

    $journeySteps = [
        [
            'step' => '01',
            'title' => $t('Choisir un service', 'Choose a service'),
            'description' => $t('Événement, package, location ou demande de vol: chaque entrée doit orienter immédiatement.', 'Event, package, rental or flight request: each entry should orient instantly.'),
        ],
        [
            'step' => '02',
            'title' => $t('Comprendre l’offre', 'Understand the offer'),
            'description' => $t('Prix, disponibilité, format de réservation et canal de support sont visibles sans effort.', 'Price, availability, booking format and support channel are visible without effort.'),
        ],
        [
            'step' => '03',
            'title' => $t('Réserver sans friction', 'Book without friction'),
            'description' => $t('Le client avance avec un parcours clair, cohérent et rassurant jusqu’au paiement ou à la demande accompagnée.', 'Clients move forward with a clear, coherent and reassuring flow until payment or assisted request.'),
        ],
    ];

    $featuredEvent = $events->first();
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-gradient-to-br from-[#211130] via-[#4b2870] to-[#d89b43] text-white shadow-[0_28px_90px_rgba(41,20,58,0.26)]">
                <div class="grid gap-8 px-5 py-8 sm:px-8 sm:py-10 xl:grid-cols-[minmax(0,1.15fr)_minmax(360px,430px)] xl:px-10 xl:py-12">
                    <div class="cp-fade-up max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Carré Premium', 'Carré Premium') }}</span>
                        </div>

                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl xl:text-[3.65rem] xl:leading-[1.04]">
                            {{ $t('Une conciergerie premium plus claire à comprendre, plus simple à réserver et plus solide sur mobile.', 'A premium concierge experience that is clearer to understand, easier to book and more reliable on mobile.') }}
                        </h1>

                        <p class="mt-5 max-w-2xl text-sm leading-7 text-white/84 sm:text-base">
                            {{ $t('Événements VIP, packages signature, location premium et vols accompagnés dans un parcours cohérent, avec des points de contact visibles et une vraie logique commerciale.', 'VIP events, signature packages, premium rental and supported flights in one coherent journey, with visible contact points and real commercial logic.') }}
                        </p>

                        <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="{{ route('events') }}" class="cp-primary-button !w-full sm:!w-auto !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e4ae54]">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                                <span>{{ $t('Commencer par les événements', 'Start with events') }}</span>
                            </a>
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/10 !text-white hover:!bg-white/16">
                                <i class="fa-regular fa-envelope text-sm"></i>
                                <span>{{ $t('Demander un accompagnement', 'Request guidance') }}</span>
                            </a>
                        </div>

                        <div class="mt-7 flex flex-wrap gap-2.5">
                            @foreach($trustSignals as $signal)
                                <span class="event-step-pill !bg-white/8 !text-white/88">{{ $signal }}</span>
                            @endforeach
                        </div>

                        <div class="mt-8 grid gap-3 sm:grid-cols-3">
                            @foreach($heroStats as $item)
                                <div class="rounded-[1.55rem] border border-white/12 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/58">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-2xl font-black sm:text-[1.75rem]">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="cp-fade-up grid gap-4 [animation-delay:120ms]">
                        <div class="overflow-hidden rounded-[2rem] border border-white/14 bg-black/16">
                            <div class="relative aspect-[4/3]">
                                <img
                                    src="{{ $featuredEvent?->getFirstMediaUrl('avatar', 'normal') ?: 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1200&h=900&fit=crop' }}"
                                    alt="{{ $featuredEvent?->title_fr ?: 'Carré Premium' }}"
                                    class="h-full w-full object-cover"
                                >
                                <div class="absolute inset-0 bg-gradient-to-t from-[#120a1d]/92 via-[#120a1d]/32 to-transparent"></div>

                                <div class="absolute inset-x-0 bottom-0 p-5">
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-gold-300)]">
                                        {{ $featuredEvent ? $t('Événement mis en avant', 'Featured event') : $t('Parcours premium', 'Premium journey') }}
                                    </p>
                                    <h2 class="mt-2 text-2xl font-black leading-tight text-white">
                                        {{ $featuredEvent?->title_fr ?: $t('Une expérience unifiée du premier clic au paiement.', 'One unified experience from first click to payment.') }}
                                    </h2>
                                    <p class="mt-3 text-sm leading-6 text-white/78">
                                        @if($featuredEvent)
                                            {{ $featuredEvent->venue_name ?: $featuredEvent->city }}
                                            @if($featuredEvent->event_date)
                                                · {{ $featuredEvent->event_date->format('d/m/Y') }}
                                            @endif
                                        @else
                                            {{ $t('Clarté de l’offre, support visible et meilleure cohérence entre pages publiques et tunnel de conversion.', 'Offer clarity, visible support and stronger consistency across public pages and the conversion funnel.') }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="rounded-[1.7rem] border border-white/12 bg-white/10 p-5 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/58">{{ $t('Canal recommandé', 'Recommended channel') }}</p>
                                <p class="mt-3 text-lg font-black text-white">{{ $t('Conseiller + WhatsApp', 'Advisor + WhatsApp') }}</p>
                                <p class="mt-2 text-sm leading-6 text-white/74">{{ $t('Pour un besoin urgent, un montant élevé ou une demande sur mesure.', 'For an urgent need, a high amount or a bespoke request.') }}</p>
                            </div>

                            <div class="rounded-[1.7rem] border border-white/12 bg-white/10 p-5 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/58">{{ $t('Contact direct', 'Direct contact') }}</p>
                                <p class="mt-3 text-lg font-black text-white">{{ $supportPhone }}</p>
                                <p class="mt-2 text-sm leading-6 text-white/74">{{ $supportEmail }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-4 py-5 sm:px-6 sm:py-6">
                <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Entrées principales', 'Main entries') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Les 4 parcours utiles du site', 'The 4 useful journeys on the site') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('La home doit orienter vite. Chaque carte ci-dessous annonce clairement le service, le bénéfice et l’action suivante.', 'The home page must orient quickly. Each card below clearly announces the service, the benefit and the next action.') }}
                        </p>
                    </div>

                    <a href="{{ route('contact') }}" class="cp-secondary-button !self-start lg:!self-auto">
                        <i class="fa-solid fa-headset text-sm"></i>
                        <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                    </a>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($serviceCards as $card)
                        <a href="{{ $card['route'] }}" class="group rounded-[1.7rem] border border-[color:var(--cp-border)] bg-white p-5 shadow-[0_16px_36px_rgba(41,20,58,0.08)] transition hover:-translate-y-1 hover:border-[color:var(--cp-border-strong)] hover:shadow-[0_22px_48px_rgba(41,20,58,0.12)]">
                            <span class="inline-flex h-12 w-12 items-center justify-center rounded-2xl bg-[linear-gradient(135deg,var(--cp-plum-900),var(--cp-plum-700))] text-white shadow-lg">
                                <i class="fa-solid {{ $card['icon'] }}"></i>
                            </span>
                            <h3 class="mt-4 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $card['title'] }}</h3>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $card['description'] }}</p>
                            <span class="mt-5 inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-plum-900)]">
                                <span>{{ $card['cta'] }}</span>
                                <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="mb-5 flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-3xl">
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Événements en vedette', 'Featured events') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Des événements mieux présentés et plus rapides à réserver', 'Events presented more clearly and faster to book') }}</h2>
                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                        {{ $t('La home doit montrer immédiatement le niveau de service attendu: visuel fort, lieu, date, prix d’entrée et accès au détail.', 'The home page should immediately show the expected level of service: strong visual, venue, date, entry price and access to detail.') }}
                    </p>
                </div>

                <a href="{{ route('events') }}" class="cp-primary-button !w-full sm:!w-auto">
                    <span>{{ $t('Voir tous les événements', 'See all events') }}</span>
                    <i class="fa-solid fa-arrow-right text-xs"></i>
                </a>
            </div>

            @if($events->isNotEmpty())
                <div class="grid gap-5 lg:grid-cols-[minmax(0,1.2fr)_minmax(320px,420px)]">
                    <div class="grid gap-5 sm:grid-cols-2">
                        @foreach($events->take(4) as $event)
                            @php
                                $eventTitle = app()->getLocale() === 'fr'
                                    ? ($event->title_fr ?? $event->title_en ?? 'Événement')
                                    : ($event->title_en ?? $event->title_fr ?? 'Event');
                                $eventImage = $event->getFirstMediaUrl('avatar', 'normal')
                                    ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=900&h=700&fit=crop';
                                $eventCategory = app()->getLocale() === 'fr'
                                    ? ($event->category?->name_fr ?? 'Événement VIP')
                                    : ($event->category?->name_en ?? $event->category?->name_fr ?? 'VIP event');
                            @endphp

                            <a href="{{ route('events.show', $event->slug ?? $event->id) }}" class="group overflow-hidden rounded-[1.9rem] border border-[color:var(--cp-border)] bg-white shadow-[0_18px_48px_rgba(41,20,58,0.09)] transition hover:-translate-y-1 hover:shadow-[0_24px_60px_rgba(41,20,58,0.14)]">
                                <div class="relative aspect-[4/3] overflow-hidden">
                                    <img src="{{ $eventImage }}" alt="{{ $eventTitle }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#120a1d]/84 via-[#120a1d]/10 to-transparent"></div>
                                    <div class="absolute left-4 top-4 flex flex-wrap gap-2">
                                        <span class="rounded-full bg-white/90 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $eventCategory }}</span>
                                        @if($event->is_featured)
                                            <span class="rounded-full bg-[color:var(--cp-gold-400)] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#2a163d]">{{ $t('À la une', 'Featured') }}</span>
                                        @endif
                                    </div>
                                    @if($event->event_date)
                                        <div class="absolute right-4 top-4 rounded-[1rem] bg-white/92 px-3 py-2 text-center shadow-lg">
                                            <p class="text-[10px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $event->event_date->translatedFormat('M') }}</p>
                                            <p class="text-lg font-black text-[color:var(--cp-plum-950)]">{{ $event->event_date->format('d') }}</p>
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5">
                                    <h3 class="text-xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $eventTitle }}</h3>
                                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $event->venue_name ?: $event->city }}</p>
                                    <div class="mt-5 flex items-center justify-between gap-3 border-t border-[color:var(--cp-border)] pt-4">
                                        <div>
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('À partir de', 'From') }}</p>
                                            <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">
                                                {{ $event->min_price ? \App\Helpers\CurrencyHelper::format($event->min_price) : $t('Sur demande', 'On request') }}
                                            </p>
                                        </div>
                                        <span class="inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-plum-900)]">
                                            <span>{{ $t('Voir le détail', 'View details') }}</span>
                                            <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="event-sticky-rail rounded-[2rem] border border-[color:var(--cp-border)] p-6 shadow-[0_20px_54px_rgba(41,20,58,0.10)]">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Pourquoi ça marche mieux', 'Why it works better') }}</p>
                        <h3 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Une home qui oriente vraiment le client', 'A home page that truly orients the client') }}</h3>

                        <div class="mt-5 space-y-4">
                            @foreach([
                                $t('Les parcours principaux sont visibles dès le haut de page.', 'Main journeys are visible from the top of the page.'),
                                $t('Les événements en vedette montrent immédiatement le niveau d’offre.', 'Featured events immediately show the level of offer.'),
                                $t('Le support reste joignable sans sortir du parcours.', 'Support remains reachable without leaving the journey.'),
                            ] as $message)
                                <div class="rounded-[1.35rem] bg-white/80 px-4 py-4">
                                    <p class="text-sm font-semibold leading-7 text-[color:var(--cp-ink-soft)]">{{ $message }}</p>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6 flex flex-col gap-3">
                            <a href="{{ route('contact') }}" class="cp-primary-button !w-full">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Être rappelé par un conseiller', 'Get a callback from an advisor') }}</span>
                            </a>
                            <a href="{{ $supportPhoneLink }}" class="cp-secondary-button !w-full">
                                <i class="fa-solid fa-phone text-sm"></i>
                                <span>{{ $supportPhone }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @else
                <div class="rounded-[2rem] border border-dashed border-[color:var(--cp-border-strong)] bg-white/70 px-6 py-14 text-center">
                    <p class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Les événements arrivent bientôt.', 'Events are coming soon.') }}</p>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('La page d’accueil reste prête à orienter vers les autres services et à capter les demandes accompagnées.', 'The home page remains ready to orient users toward the other services and capture assisted requests.') }}</p>
                </div>
            @endif
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-2">
                <article class="cp-panel rounded-[2rem] p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Packages signature', 'Signature packages') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Des séjours plus lisibles', 'Trips made easier to read') }}</h2>
                        </div>
                        <a href="{{ route('packages') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">{{ $t('Tout voir', 'View all') }}</a>
                    </div>

                    <div class="mt-5 space-y-4">
                        @forelse($featuredPackages->take(3) as $package)
                            @php
                                $packageTitle = app()->getLocale() === 'fr'
                                    ? ($package->title_fr ?? $package->title_en ?? $package->slug)
                                    : ($package->title_en ?? $package->title_fr ?? $package->slug);
                                $packageDescription = app()->getLocale() === 'fr'
                                    ? ($package->description_fr ?? $package->description_en ?? '')
                                    : ($package->description_en ?? $package->description_fr ?? '');
                                $packageImage = $package->getFirstMediaUrl('avatar', 'normal')
                                    ?: 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=900&h=700&fit=crop';
                            @endphp

                            <a href="{{ route('packages.show', $package->slug) }}" class="group grid gap-4 overflow-hidden rounded-[1.6rem] border border-[color:var(--cp-border)] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md sm:grid-cols-[132px_minmax(0,1fr)]">
                                <div class="relative h-40 overflow-hidden sm:h-full">
                                    <img src="{{ $packageImage }}" alt="{{ $packageTitle }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($package->destination)
                                            <span class="rounded-full bg-[#f4edff] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $package->destination }}</span>
                                        @endif
                                        @if($package->duration)
                                            <span class="rounded-full bg-[#fff5e6] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[#a86308]">{{ $package->duration }} {{ $t('jours', 'days') }}</span>
                                        @endif
                                    </div>
                                    <h3 class="mt-3 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $packageTitle }}</h3>
                                    <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit($packageDescription, 120) }}</p>
                                    <div class="mt-4 flex items-center justify-between gap-3">
                                        <p class="text-sm font-black text-[color:var(--cp-plum-950)]">
                                            {{ ($package->discount_price ?? $package->price) ? \App\Helpers\CurrencyHelper::format($package->discount_price ?? $package->price) : $t('Sur demande', 'On request') }}
                                        </p>
                                        <span class="inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-plum-900)]">
                                            <span>{{ $t('Voir le détail', 'View details') }}</span>
                                            <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                                        </span>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-[1.6rem] border border-dashed border-[color:var(--cp-border-strong)] bg-[#fffaf4] px-5 py-8 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Les packages mis en avant apparaîtront ici pour aider le client à entrer rapidement dans un séjour adapté.', 'Featured packages will appear here to help clients quickly enter the right kind of trip.') }}
                            </div>
                        @endforelse
                    </div>
                </article>

                <article class="cp-panel rounded-[2rem] p-6 sm:p-7">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Location premium', 'Premium rental') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Mobilité premium sans ambiguïté', 'Premium mobility without ambiguity') }}</h2>
                        </div>
                        <a href="{{ route('location') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">{{ $t('Voir la flotte', 'See the fleet') }}</a>
                    </div>

                    <div class="mt-5 grid gap-4">
                        @forelse($featuredLocations->take(3) as $location)
                            @php
                                $locationName = app()->getLocale() === 'fr'
                                    ? ($location->name_fr ?? $location->name_en ?? $t('Véhicule premium', 'Premium vehicle'))
                                    : ($location->name_en ?? $location->name_fr ?? $t('Véhicule premium', 'Premium vehicle'));
                                $locationDescription = app()->getLocale() === 'fr'
                                    ? ($location->description_fr ?? $location->description_en ?? '')
                                    : ($location->description_en ?? $location->description_fr ?? '');
                                $locationImage = $location->image_url ?: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=900&h=700&fit=crop';
                            @endphp

                            <a href="{{ route('location.show', $location->id) }}" class="group overflow-hidden rounded-[1.6rem] border border-[color:var(--cp-border)] bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                                <div class="grid gap-0 sm:grid-cols-[minmax(160px,200px)_minmax(0,1fr)]">
                                    <div class="relative h-44 overflow-hidden sm:h-full">
                                        <img src="{{ $locationImage }}" alt="{{ $locationName }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                                    </div>
                                    <div class="p-4">
                                        <div class="flex flex-wrap gap-2">
                                            @if($location->category)
                                                <span class="rounded-full bg-[#f4edff] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ ucfirst($location->category) }}</span>
                                            @endif
                                            @if($location->capacity)
                                                <span class="rounded-full bg-[#eefaf7] px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-[#0f766e]">{{ $location->capacity }} {{ $t('places', 'seats') }}</span>
                                            @endif
                                        </div>
                                        <h3 class="mt-3 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $locationName }}</h3>
                                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit($locationDescription, 120) }}</p>
                                        <div class="mt-4 flex items-center justify-between gap-3">
                                            <p class="text-sm font-black text-[color:var(--cp-plum-950)]">
                                                {{ $location->price_per_day ? \App\Helpers\CurrencyHelper::format($location->price_per_day) : $t('Sur demande', 'On request') }}
                                            </p>
                                            <span class="inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-plum-900)]">
                                                <span>{{ $t('Réserver', 'Book') }}</span>
                                                <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="rounded-[1.6rem] border border-dashed border-[color:var(--cp-border-strong)] bg-[#fffaf4] px-5 py-8 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('La flotte premium apparaît ici avec la capacité, le niveau de service et le tarif journalier mieux mis en avant.', 'The premium fleet appears here with capacity, service level and daily pricing made clearer.') }}
                            </div>
                        @endforelse
                    </div>
                </article>
            </div>
        </div>
    </section>

    <section class="cp-page-section-lg">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#26153a] via-[#4d2d72] to-[#d7a147] px-5 py-8 text-white shadow-[0_24px_70px_rgba(41,20,58,0.18)] sm:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Cheminement client', 'Client journey') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Le site doit guider, pas fatiguer', 'The site should guide, not tire users') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/82 sm:text-base">
                            {{ $t('Cette refonte de la home doit installer une logique simple: comprendre l’offre, choisir son parcours, réserver ou demander un accompagnement sans se perdre.', 'This home page refactor is meant to establish one simple logic: understand the offer, choose a journey, book or request guidance without getting lost.') }}
                        </p>
                    </div>

                    <div class="grid gap-4 lg:max-w-[34rem] lg:grid-cols-3">
                        @foreach($journeySteps as $step)
                            <div class="rounded-[1.5rem] border border-white/12 bg-white/10 p-4 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-gold-300)]">{{ $step['step'] }}</p>
                                <h3 class="mt-2 text-lg font-black text-white">{{ $step['title'] }}</h3>
                                <p class="mt-2 text-sm leading-6 text-white/76">{{ $step['description'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                        <i class="fa-regular fa-envelope text-sm"></i>
                        <span>{{ $t('Demander un devis', 'Request a quote') }}</span>
                    </a>
                    <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>{{ $t('WhatsApp direct', 'Direct WhatsApp') }}</span>
                    </a>
                    <a href="{{ route('flights.index') }}" class="cp-secondary-button !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                        <i class="fa-solid fa-plane-departure text-sm"></i>
                        <span>{{ $t('Demande de vol', 'Flight request') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>

<a
    href="{{ $whatsAppUrl }}"
    target="_blank"
    rel="noopener noreferrer"
    class="fixed bottom-5 right-5 z-40 inline-flex h-14 w-14 items-center justify-center rounded-full bg-[#1fbf5b] text-white shadow-[0_18px_40px_rgba(31,191,91,0.34)] transition hover:scale-105 hover:bg-[#17a44b] sm:bottom-6 sm:right-6 sm:h-16 sm:w-16"
    aria-label="Contact WhatsApp"
>
    <i class="fa-brands fa-whatsapp text-2xl sm:text-[1.7rem]"></i>
</a>
@endsection
