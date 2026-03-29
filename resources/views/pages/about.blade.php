@extends('layouts.app')

@section('title', 'À propos - Carré Premium')
@section('meta_description', 'Découvrez Carré Premium, conciergerie privée basée à Abidjan, spécialisée dans les événements premium, les voyages sur mesure et la mobilité haut de gamme.')
@section('meta_keywords', 'à propos carré premium, conciergerie privée abidjan, voyages premium, événements VIP, mobilité premium')
@section('og_title', 'À propos - Carré Premium')
@section('og_description', 'Carré Premium accompagne ses clients sur les événements, les packages, la mobilité et les demandes sur mesure avec un parcours plus clair.')

@push('head')
@php
    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => config('carre_premium.company.name'),
        'url' => url('/about'),
        'email' => config('carre_premium.contact.support_email'),
        'telephone' => config('carre_premium.contact.landline_display'),
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => config('carre_premium.company.address'),
            'addressLocality' => config('carre_premium.company.city'),
            'addressCountry' => config('carre_premium.company.country'),
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($organizationSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $stats = [
        ['value' => '24/7', 'label' => $t('accompagnement client', 'client support')],
        ['value' => '4', 'label' => $t('univers de service', 'service pillars')],
        ['value' => '1', 'label' => $t('parcours plus cohérent', 'clearer booking flow')],
    ];
    $pillars = [
        [
            'title' => $t('Clarté commerciale', 'Commercial clarity'),
            'description' => $t('Chaque page doit faire comprendre le service, le prix et la prochaine action sans surcharge.', 'Every page should make the service, price and next action obvious without clutter.'),
        ],
        [
            'title' => $t('Accompagnement humain', 'Human guidance'),
            'description' => $t('Le client peut toujours basculer vers un conseiller pour débloquer une situation ou demander du sur-mesure.', 'Clients can always switch to a human advisor to unblock a situation or request something bespoke.'),
        ],
        [
            'title' => $t('Exécution premium', 'Premium execution'),
            'description' => $t('Nous traitons les réservations, paiements, documents et confirmations dans le même niveau d’exigence.', 'We handle bookings, payments, documents and confirmations at the same level of quality.'),
        ],
    ];
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1.05fr)_minmax(290px,360px)]">
                    <div class="max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Carré Premium', 'Carré Premium') }}</span>
                        </div>
                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">
                            {{ $t('Une conciergerie privée pensée pour rendre les services premium compréhensibles et réservables.', 'A private concierge built to make premium services easier to understand and book.') }}
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/84 sm:text-base">
                            {{ $t('Basée à Abidjan, Carré Premium structure des parcours autour des événements, packages, locations premium et demandes accompagnées. L’objectif n’est pas seulement de montrer une offre, mais de la rendre claire jusqu’au paiement.', 'Based in Abidjan, Carré Premium structures journeys around events, packages, premium rentals and assisted requests. The goal is not just to display an offer, but to make it clear all the way to payment.') }}
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                        @foreach($stats as $stat)
                            <div class="rounded-[1.55rem] border border-white/15 bg-white/10 px-5 py-5 backdrop-blur">
                                <p class="text-3xl font-black">{{ $stat['value'] }}</p>
                                <p class="mt-2 text-sm font-semibold text-white/76">{{ $stat['label'] }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-5 py-6 sm:px-7 sm:py-8">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(0,0.9fr)]">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Ce que nous faisons', 'What we do') }}</p>
                        <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Unifier des services différents dans une même expérience client.', 'Unify different services into one customer experience.') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">
                            {{ $t('Carré Premium intervient sur quatre grandes familles: événements, packages touristiques, location premium et demandes accompagnées. Le travail n’est pas seulement commercial. Il consiste aussi à rassurer, clarifier, relier les pages et garder une logique sans rupture.', 'Carré Premium operates across four main families: events, travel packages, premium rentals and assisted requests. The work is not only commercial. It also means reassuring users, clarifying content, linking pages properly and keeping the journey coherent.') }}
                        </p>
                    </div>

                    <div class="rounded-[1.8rem] bg-[#faf6ff] p-5">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Positionnement', 'Positioning') }}</p>
                        <div class="mt-4 space-y-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            <p>{{ $t('Base opérationnelle : Abidjan, Côte d’Ivoire.', 'Operating base: Abidjan, Ivory Coast.') }}</p>
                            <p>{{ $t('Approche : premium, mobile-first, orientée conversion et compréhension.', 'Approach: premium, mobile-first, built for clarity and conversion.') }}</p>
                            <p>{{ $t('Promesse : un client doit savoir quoi réserver, combien cela coûte et qui contacter si besoin.', 'Promise: a client should know what to book, how much it costs and who to contact if needed.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-5 lg:grid-cols-3">
                @foreach($pillars as $pillar)
                    <article class="cp-panel rounded-[1.85rem] p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Pilier', 'Pillar') }}</p>
                        <h3 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $pillar['title'] }}</h3>
                        <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $pillar['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="cp-page-section-lg">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#26153a] via-[#4d2d72] to-[#d7a147] px-5 py-8 text-white shadow-[0_24px_70px_rgba(41,20,58,0.18)] sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Parler à l’équipe', 'Talk to the team') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Besoin d’un service spécifique ou d’un accompagnement direct ?', 'Need a specific service or direct support?') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/80 sm:text-base">
                            {{ $t('Le canal le plus simple reste le conseiller Carré Premium: demande, clarification et orientation vers le bon parcours.', 'The simplest route remains the Carré Premium advisor: request, clarification and guidance to the right journey.') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                            <span>{{ $t('Contacter Carré Premium', 'Contact Carré Premium') }}</span>
                        </a>
                        <a href="{{ route('events') }}" class="cp-secondary-button !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                            <i class="fa-solid fa-ticket text-sm"></i>
                            <span>{{ $t('Voir les services', 'Explore services') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
