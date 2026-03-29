@extends('layouts.app')

@section('title', 'Accueil - Carré Premium')
@section('meta_description', 'Carré Premium donne accès à des événements VIP, packages signature, mobilité premium et accompagnement humain dans une vitrine visuelle, premium et pensée mobile.')
@section('meta_keywords', 'conciergerie premium, événements VIP, packages luxe, location premium, vols accompagnés, Carré Premium, Abidjan')
@section('og_title', 'Accueil - Carré Premium')
@section('og_description', 'Une home premium, visuelle et mobile-first pour découvrir les événements VIP, packages signature, mobilité premium et vols accompagnés.')

@push('styles')
<style>
    .cp-home-glow {
        position: relative;
        overflow: hidden;
    }

    .cp-home-glow::before,
    .cp-home-glow::after {
        content: '';
        position: absolute;
        border-radius: 999px;
        filter: blur(20px);
        pointer-events: none;
        opacity: 0.42;
    }

    .cp-home-glow::before {
        top: -6rem;
        right: -4rem;
        width: 16rem;
        height: 16rem;
        background: radial-gradient(circle, rgba(240, 187, 97, 0.42), transparent 68%);
    }

    .cp-home-glow::after {
        bottom: -7rem;
        left: -5rem;
        width: 18rem;
        height: 18rem;
        background: radial-gradient(circle, rgba(110, 67, 168, 0.32), transparent 68%);
    }

    .cp-home-track {
        scrollbar-width: none;
        -ms-overflow-style: none;
        scroll-snap-type: x mandatory;
    }

    .cp-home-track::-webkit-scrollbar {
        display: none;
    }

    .cp-home-card {
        scroll-snap-align: start;
    }

    .cp-home-hero-slide {
        transition: opacity 0.45s ease, transform 0.45s ease;
    }

    .cp-home-dot {
        transition: width 0.25s ease, opacity 0.25s ease, background-color 0.25s ease;
    }

    @keyframes cp-home-float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }

    .cp-home-float {
        animation: cp-home-float 5.4s ease-in-out infinite;
    }
</style>
@endpush

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

    $eventCards = $events->map(function ($event) use ($t) {
        $title = app()->getLocale() === 'fr'
            ? ($event->title_fr ?? $event->title_en ?? 'Événement VIP')
            : ($event->title_en ?? $event->title_fr ?? 'VIP event');

        return [
            'type' => $t('Événement', 'Event'),
            'title' => $title,
            'subtitle' => collect([$event->venue_name, $event->city])->filter()->join(' · '),
            'image' => $event->getFirstMediaUrl('avatar', 'normal')
                ?: 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1400&h=900&fit=crop',
            'url' => route('events.show', $event->slug ?? $event->id),
            'meta' => $event->event_date?->format('d/m/Y'),
            'price' => $event->min_price ? \App\Helpers\CurrencyHelper::format($event->min_price) : $t('Sur demande', 'On request'),
        ];
    })->values();

    $packageCards = $featuredPackages->map(function ($package) use ($t) {
        $title = app()->getLocale() === 'fr'
            ? ($package->title_fr ?? $package->title_en ?? 'Package signature')
            : ($package->title_en ?? $package->title_fr ?? 'Signature package');

        return [
            'type' => $t('Package', 'Package'),
            'title' => $title,
            'subtitle' => $package->destination ?: $t('Voyage signature', 'Signature escape'),
            'image' => $package->getFirstMediaUrl('avatar', 'normal')
                ?: 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1400&h=900&fit=crop',
            'url' => route('packages.show', $package->slug),
            'meta' => $package->duration_text_fr ?: $package->duration . ' ' . $t('jours', 'days'),
            'price' => \App\Helpers\CurrencyHelper::format($package->discount_price ?: $package->price),
        ];
    })->values();

    $locationCards = $featuredLocations->map(function ($location) use ($t) {
        return [
            'type' => $t('Mobilité', 'Mobility'),
            'title' => $location->name,
            'subtitle' => ucfirst($location->category ?: $t('premium', 'premium')),
            'image' => $location->image_url
                ?: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1400&h=900&fit=crop',
            'url' => route('location.show', $location->id),
            'meta' => $location->capacity ? $location->capacity . ' ' . $t('pers.', 'guests') : null,
            'price' => \App\Helpers\CurrencyHelper::format($location->price_per_day) . '/' . $t('jour', 'day'),
        ];
    })->values();

    $heroSlides = collect()
        ->merge($eventCards->take(3))
        ->merge($packageCards->take(2))
        ->merge($locationCards->take(2))
        ->take(7)
        ->values();

    if ($heroSlides->isEmpty()) {
        $heroSlides = collect([
            [
                'type' => $t('Carré Premium', 'Carré Premium'),
                'title' => $t('Des expériences premium pensées comme une vraie vitrine.', 'Premium experiences staged as a true visual showcase.'),
                'subtitle' => $t('Événements VIP, packages signature, mobilité premium et accompagnement humain.', 'VIP events, signature packages, premium mobility and human guidance.'),
                'image' => 'https://images.unsplash.com/photo-1511578314322-379afb476865?w=1400&h=900&fit=crop',
                'url' => route('contact'),
                'meta' => $t('Conseiller dédié', 'Dedicated advisor'),
                'price' => $t('Sur demande', 'On request'),
            ],
        ]);
    }

    $serviceCards = [
        [
            'title' => $t('Événements VIP', 'VIP events'),
            'description' => $t('Billetterie premium, accès exclusifs et expériences fortes.', 'Premium ticketing, exclusive access and high-impact experiences.'),
            'route' => route('events'),
            'cta' => $t('Voir les événements', 'See events'),
            'icon' => 'fa-ticket',
            'image' => $eventCards->first()['image'] ?? 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=1400&h=900&fit=crop',
        ],
        [
            'title' => $t('Packages signature', 'Signature packages'),
            'description' => $t('Séjours, circuits et expériences luxe présentés comme des collections.', 'Trips, circuits and luxury experiences presented like curated collections.'),
            'route' => route('packages'),
            'cta' => $t('Explorer les packages', 'Explore packages'),
            'icon' => 'fa-suitcase-rolling',
            'image' => $packageCards->first()['image'] ?? 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=1400&h=900&fit=crop',
        ],
        [
            'title' => $t('Mobilité premium', 'Premium mobility'),
            'description' => $t('Véhicules, transferts et solutions de déplacement haut de gamme.', 'Vehicles, transfers and high-end mobility solutions.'),
            'route' => route('location'),
            'cta' => $t('Voir la flotte', 'See the fleet'),
            'icon' => 'fa-car-side',
            'image' => $locationCards->first()['image'] ?? 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1400&h=900&fit=crop',
        ],
        [
            'title' => $t('Vols accompagnés', 'Supported flights'),
            'description' => $t('Demandes gérées avec un conseiller pour sécuriser chaque étape.', 'Requests handled with an advisor to secure each step.'),
            'route' => route('flights.index'),
            'cta' => $t('Parler à un conseiller', 'Talk to an advisor'),
            'icon' => 'fa-plane-departure',
            'image' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1400&h=900&fit=crop',
        ],
    ];

    $statsCards = [
        ['label' => $t('Événements actifs', 'Active events'), 'value' => number_format((int) ($stats['events'] ?? $events->count()), 0, ',', ' ')],
        ['label' => $t('Packages signature', 'Signature packages'), 'value' => number_format((int) ($stats['packages'] ?? $featuredPackages->count()), 0, ',', ' ')],
        ['label' => $t('Solutions mobilité', 'Mobility options'), 'value' => number_format((int) ($stats['locations'] ?? $featuredLocations->count()), 0, ',', ' ')],
        ['label' => $t('Point de départ package', 'Package entry point'), 'value' => !empty($stats['starting_package_price']) ? \App\Helpers\CurrencyHelper::format($stats['starting_package_price']) : $t('Sur demande', 'On request')],
    ];

    $mixedMoments = collect()
        ->merge($eventCards)
        ->merge($packageCards)
        ->merge($locationCards)
        ->take(10)
        ->values();
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="cp-home-glow overflow-hidden rounded-[2.6rem] bg-[linear-gradient(135deg,#170d23_0%,#3f225f_42%,#d9a441_100%)] px-5 py-8 text-white shadow-[0_28px_90px_rgba(34,18,52,0.28)] sm:px-8 sm:py-10 xl:px-10 xl:py-12">
                <div class="grid gap-8 xl:grid-cols-[minmax(0,1.04fr)_minmax(360px,470px)] xl:items-center">
                    <div class="max-w-3xl">
                        <div class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-[color:var(--cp-gold-300)] backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-current"></span>
                            {{ $t('Carré Premium', 'Carré Premium') }}
                        </div>

                        <h1 class="mt-5 text-3xl font-black leading-tight sm:text-4xl xl:text-[4rem] xl:leading-[1.02]">
                            {{ $t('Une vitrine premium qui donne envie avant même le premier clic.', 'A premium storefront that creates desire before the first click.') }}
                        </h1>

                        <p class="mt-5 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                            {{ $t('Événements VIP, packages signature, mobilité premium et vols accompagnés dans une home plus visuelle, plus désirable et plus simple à parcourir sur mobile.', 'VIP events, signature packages, premium mobility and supported flights in a homepage that is more visual, more desirable and easier to navigate on mobile.') }}
                        </p>

                        <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="{{ route('events') }}" class="cp-primary-button !w-full sm:!w-auto !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e4ae54]">
                                <i class="fa-solid fa-ticket text-sm"></i>
                                <span>{{ $t('Explorer les collections', 'Explore the collections') }}</span>
                            </a>
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/10 !text-white hover:!bg-white/16">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t('Parler à un conseiller', 'Talk to an advisor') }}</span>
                            </a>
                        </div>

                        <div class="mt-7 grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                            @foreach($statsCards as $item)
                                <div class="rounded-[1.55rem] border border-white/12 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/56">{{ $item['label'] }}</p>
                                    <p class="mt-2 text-2xl font-black sm:text-[1.75rem]">{{ $item['value'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="relative" data-home-hero>
                        <div class="absolute -left-5 top-6 hidden h-24 w-24 rounded-full bg-white/12 blur-3xl xl:block"></div>
                        <div class="relative overflow-hidden rounded-[2rem] border border-white/14 bg-black/15 shadow-[0_20px_60px_rgba(17,10,29,0.32)]">
                            <div class="relative aspect-[4/5] sm:aspect-[4/4]">
                                @foreach($heroSlides as $index => $slide)
                                    <a
                                        href="{{ $slide['url'] }}"
                                        class="cp-home-hero-slide absolute inset-0 {{ $index === 0 ? 'opacity-100 translate-x-0' : 'pointer-events-none translate-x-6 opacity-0' }}"
                                        data-home-hero-slide
                                    >
                                        <img src="{{ $slide['image'] }}" alt="{{ $slide['title'] }}" class="h-full w-full object-cover">
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#110a1b]/96 via-[#110a1b]/18 to-transparent"></div>

                                        <div class="absolute inset-x-0 bottom-0 p-5 sm:p-6">
                                            <div class="inline-flex items-center gap-2 rounded-full bg-white/90 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)] shadow">
                                                <span>{{ $slide['type'] }}</span>
                                            </div>
                                            <h2 class="mt-4 text-2xl font-black leading-tight text-white sm:text-[2rem]">{{ $slide['title'] }}</h2>
                                            @if(!empty($slide['subtitle']))
                                                <p class="mt-3 text-sm leading-7 text-white/80">{{ $slide['subtitle'] }}</p>
                                            @endif

                                            <div class="mt-5 flex items-end justify-between gap-3">
                                                <div>
                                                    @if(!empty($slide['meta']))
                                                        <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/56">{{ $slide['meta'] }}</p>
                                                    @endif
                                                    @if(!empty($slide['price']))
                                                        <p class="mt-2 text-xl font-black text-[color:var(--cp-gold-300)]">{{ $slide['price'] }}</p>
                                                    @endif
                                                </div>
                                                <span class="inline-flex h-12 w-12 items-center justify-center rounded-full bg-white/12 text-white backdrop-blur">
                                                    <i class="fa-solid fa-arrow-right text-sm"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>

                            <div class="absolute inset-x-0 top-0 flex items-center justify-between p-4">
                                <div class="rounded-full border border-white/14 bg-white/10 px-3 py-2 text-[11px] font-black uppercase tracking-[0.18em] text-white/78 backdrop-blur">
                                    {{ $t('Sélection en mouvement', 'Curated motion') }}
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" data-home-prev class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/14 bg-white/10 text-white backdrop-blur transition hover:bg-white/18">
                                        <i class="fa-solid fa-arrow-left text-xs"></i>
                                    </button>
                                    <button type="button" data-home-next class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/14 bg-white/10 text-white backdrop-blur transition hover:bg-white/18">
                                        <i class="fa-solid fa-arrow-right text-xs"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        @if($heroSlides->count() > 1)
                            <div class="mt-4 flex items-center justify-center gap-2">
                                @foreach($heroSlides as $index => $slide)
                                    <button type="button" data-home-dot="{{ $index }}" class="cp-home-dot h-2.5 w-2.5 rounded-full bg-white/32 {{ $index === 0 ? '!w-9 !bg-[color:var(--cp-gold-300)]' : '' }}"></button>
                                @endforeach
                            </div>
                        @endif

                        <div class="cp-home-float absolute -bottom-3 -left-3 hidden rounded-[1.5rem] border border-white/14 bg-white/12 px-4 py-4 text-white shadow-xl backdrop-blur md:block">
                            <p class="text-[11px] font-black uppercase tracking-[0.2em] text-white/56">{{ $t('Support direct', 'Direct support') }}</p>
                            <p class="mt-2 text-lg font-black">{{ $supportPhone }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $supportEmail }}</p>
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
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Univers', 'Universes') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Quatre entrées, quatre ambiances, un seul niveau de service.', 'Four entry points, four moods, one level of service.') }}</h2>
                    </div>
                    <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !self-start lg:!self-auto">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                        <span>{{ $t('WhatsApp direct', 'Direct WhatsApp') }}</span>
                    </a>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    @foreach($serviceCards as $card)
                        <a href="{{ $card['route'] }}" class="group relative overflow-hidden rounded-[1.75rem] border border-[color:var(--cp-border)] bg-[color:var(--cp-plum-950)] text-white shadow-[0_18px_44px_rgba(32,20,47,0.14)] transition hover:-translate-y-1 hover:shadow-[0_28px_64px_rgba(32,20,47,0.18)]">
                            <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="absolute inset-0 h-full w-full object-cover opacity-32 transition duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#130b1d] via-[#130b1d]/76 to-transparent"></div>
                            <div class="relative flex min-h-[18rem] flex-col justify-end p-5">
                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-full bg-white/12 backdrop-blur">
                                    <i class="fa-solid {{ $card['icon'] }}"></i>
                                </span>
                                <h3 class="mt-4 text-2xl font-black">{{ $card['title'] }}</h3>
                                <p class="mt-3 text-sm leading-7 text-white/76">{{ $card['description'] }}</p>
                                <span class="mt-5 inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-gold-300)]">
                                    <span>{{ $card['cta'] }}</span>
                                    <i class="fa-solid fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                                </span>
                            </div>
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
                    <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Sélections signature', 'Signature selections') }}</p>
                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Un flux visuel pour sentir l’offre sans lire un catalogue entier.', 'A visual flow to feel the offer without reading an entire catalog.') }}</h2>
                </div>
                <div class="flex gap-2">
                    <button type="button" class="cp-icon-button" data-carousel-prev="moments">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                    </button>
                    <button type="button" class="cp-icon-button" data-carousel-next="moments">
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </div>
            </div>

            <div class="cp-home-track flex gap-4 overflow-x-auto pb-2" data-carousel-track="moments">
                @foreach($mixedMoments as $card)
                    <a href="{{ $card['url'] }}" class="cp-home-card group min-w-[19rem] max-w-[19rem] overflow-hidden rounded-[1.9rem] border border-[color:var(--cp-border)] bg-white shadow-[0_16px_40px_rgba(41,20,58,0.08)] transition hover:-translate-y-1 hover:shadow-[0_22px_54px_rgba(41,20,58,0.12)] sm:min-w-[22rem] sm:max-w-[22rem]">
                        <div class="relative aspect-[4/3] overflow-hidden">
                            <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#120a1d]/86 via-[#120a1d]/18 to-transparent"></div>
                            <div class="absolute left-4 top-4 rounded-full bg-white/92 px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">
                                {{ $card['type'] }}
                            </div>
                        </div>
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div>
                                    <h3 class="text-xl font-black leading-tight text-[color:var(--cp-plum-950)]">{{ $card['title'] }}</h3>
                                    @if(!empty($card['subtitle']))
                                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit($card['subtitle'], 86) }}</p>
                                    @endif
                                </div>
                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-full bg-[rgba(75,40,112,0.08)] text-[color:var(--cp-plum-800)]">
                                    <i class="fa-solid fa-arrow-right text-xs"></i>
                                </span>
                            </div>
                            <div class="mt-5 flex items-center justify-between gap-3 border-t border-[color:var(--cp-border)] pt-4">
                                <span class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $card['meta'] ?: $t('Collection premium', 'Premium selection') }}</span>
                                <span class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $card['price'] }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div class="space-y-6">
                    <div class="mb-2">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('En ce moment', 'Right now') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Deux collections qui doivent donner envie immédiatement.', 'Two collections that should create desire instantly.') }}</h2>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <article class="cp-panel rounded-[2rem] p-5 sm:p-6">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Packages', 'Packages') }}</p>
                                    <h3 class="mt-2 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Évasion signature', 'Signature escapes') }}</h3>
                                </div>
                                <a href="{{ route('packages') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">{{ $t('Tout voir', 'View all') }}</a>
                            </div>
                            <div class="mt-5 space-y-4">
                                @foreach($packageCards->take(3) as $card)
                                    <a href="{{ $card['url'] }}" class="group flex gap-4 rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white p-3 transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                        <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="h-24 w-24 rounded-[1.2rem] object-cover sm:h-28 sm:w-28">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $card['type'] }}</p>
                                            <h4 class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $card['title'] }}</h4>
                                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit($card['subtitle'], 52) }}</p>
                                            <div class="mt-3 flex items-center justify-between gap-3">
                                                <span class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $card['price'] }}</span>
                                                <span class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $card['meta'] }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </article>

                        <article class="cp-panel rounded-[2rem] p-5 sm:p-6">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Mobilité', 'Mobility') }}</p>
                                    <h3 class="mt-2 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Arrivées premium', 'Premium arrivals') }}</h3>
                                </div>
                                <a href="{{ route('location') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">{{ $t('Tout voir', 'View all') }}</a>
                            </div>
                            <div class="mt-5 space-y-4">
                                @foreach($locationCards->take(3) as $card)
                                    <a href="{{ $card['url'] }}" class="group flex gap-4 rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white p-3 transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                        <img src="{{ $card['image'] }}" alt="{{ $card['title'] }}" class="h-24 w-24 rounded-[1.2rem] object-cover sm:h-28 sm:w-28">
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-plum-800)]">{{ $card['type'] }}</p>
                                            <h4 class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $card['title'] }}</h4>
                                            <p class="mt-2 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ \Illuminate\Support\Str::limit($card['subtitle'], 52) }}</p>
                                            <div class="mt-3 flex items-center justify-between gap-3">
                                                <span class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $card['price'] }}</span>
                                                <span class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $card['meta'] ?: $t('Service premium', 'Premium service') }}</span>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </article>
                    </div>
                </div>

                <aside class="cp-panel rounded-[2rem] p-6 sm:p-7 xl:sticky xl:top-28">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Accompagnement', 'Guidance') }}</p>
                    <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Quand un client hésite, il doit pouvoir agir immédiatement.', 'When a client hesitates, action should stay immediate.') }}</h2>
                    <p class="mt-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                        {{ $t('Un conseiller visible, un canal WhatsApp direct et un lien simple vers les demandes accompagnées. C’est ce qui rassure vraiment sur mobile.', 'A visible advisor, direct WhatsApp and a simple path to assisted requests. That is what truly reassures on mobile.') }}
                    </p>

                    <div class="mt-6 space-y-3">
                        <a href="{{ $supportPhoneLink }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-phone text-[color:var(--cp-plum-800)]"></i> {{ $supportPhone }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                        </a>
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                            <span class="flex items-center gap-3"><i class="fa-brands fa-whatsapp text-[color:var(--cp-success)]"></i> {{ $t('WhatsApp direct', 'Direct WhatsApp') }}</span>
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                        </a>
                        <a href="{{ route('flights.index') }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                            <span class="flex items-center gap-3"><i class="fa-solid fa-plane-departure text-[color:var(--cp-plum-800)]"></i> {{ $t('Demande de vol accompagnée', 'Assisted flight request') }}</span>
                            <i class="fa-solid fa-arrow-right text-xs text-[color:var(--cp-ink-muted)]"></i>
                        </a>
                    </div>

                    <div class="mt-6 rounded-[1.6rem] bg-[linear-gradient(135deg,rgba(75,40,112,0.08),rgba(217,164,65,0.14))] p-5">
                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('Email support', 'Support email') }}</p>
                        <p class="mt-2 text-lg font-black text-[color:var(--cp-plum-950)]">{{ $supportEmail }}</p>
                        <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Pour les besoins corporate, demandes groupe ou paiements assistés.', 'For corporate requests, group demands or assisted payments.') }}</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-[linear-gradient(135deg,#231332_0%,#4b2870_54%,#d9a441_100%)] px-5 py-8 text-white shadow-[0_24px_74px_rgba(34,18,52,0.22)] sm:px-8 sm:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-center">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-gold-300)]">{{ $t('Dernier écran', 'Final call') }}</p>
                        <h2 class="mt-3 text-3xl font-black sm:text-4xl">{{ $t('Donner envie, orienter vite, puis faire convertir sans bruit.', 'Create desire, orient quickly, then convert without noise.') }}</h2>
                        <p class="mt-4 text-sm leading-7 text-white/82 sm:text-base">{{ $t('La nouvelle home doit lancer le bon parcours et laisser le détail aux pages produit. C’est la logique retenue ici.', 'The new homepage should launch the right journey and leave details to product pages. That is the direction used here.') }}</p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row lg:flex-col">
                        <a href="{{ route('events') }}" class="cp-primary-button !w-full sm:!w-auto !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e4ae54]">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                            <span>{{ $t('Entrer sur le site', 'Enter the site') }}</span>
                        </a>
                        <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/18 !bg-white/10 !text-white hover:!bg-white/14">
                            <i class="fa-solid fa-headset text-sm"></i>
                            <span>{{ $t('Être accompagné', 'Get guidance') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const hero = document.querySelector('[data-home-hero]');

    if (hero) {
        const slides = Array.from(hero.querySelectorAll('[data-home-hero-slide]'));
        const dots = Array.from(document.querySelectorAll('[data-home-dot]'));
        const prevButton = hero.querySelector('[data-home-prev]');
        const nextButton = hero.querySelector('[data-home-next]');
        let active = 0;
        let timer = null;

        const renderHero = (index) => {
            active = (index + slides.length) % slides.length;

            slides.forEach((slide, slideIndex) => {
                const isActive = slideIndex === active;
                slide.classList.toggle('opacity-100', isActive);
                slide.classList.toggle('translate-x-0', isActive);
                slide.classList.toggle('pointer-events-none', !isActive);
                slide.classList.toggle('opacity-0', !isActive);
                slide.classList.toggle('translate-x-6', !isActive);
            });

            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('!w-9', dotIndex === active);
                dot.classList.toggle('!bg-[color:var(--cp-gold-300)]', dotIndex === active);
                dot.classList.toggle('bg-white/32', dotIndex !== active);
            });
        };

        const startHero = () => {
            if (slides.length < 2) return;
            stopHero();
            timer = window.setInterval(() => renderHero(active + 1), 5200);
        };

        const stopHero = () => {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        };

        prevButton?.addEventListener('click', () => renderHero(active - 1));
        nextButton?.addEventListener('click', () => renderHero(active + 1));
        hero.addEventListener('mouseenter', stopHero);
        hero.addEventListener('mouseleave', startHero);

        dots.forEach((dot) => {
            dot.addEventListener('click', () => renderHero(Number(dot.dataset.homeDot || 0)));
        });

        renderHero(0);
        startHero();
    }

    document.querySelectorAll('[data-carousel-track]').forEach((track) => {
        const key = track.dataset.carouselTrack;
        const prev = document.querySelector(`[data-carousel-prev="${key}"]`);
        const next = document.querySelector(`[data-carousel-next="${key}"]`);
        const amount = () => Math.min(track.clientWidth * 0.88, 380);

        prev?.addEventListener('click', () => {
            track.scrollBy({ left: -amount(), behavior: 'smooth' });
        });

        next?.addEventListener('click', () => {
            track.scrollBy({ left: amount(), behavior: 'smooth' });
        });
    });
});
</script>
@endpush
