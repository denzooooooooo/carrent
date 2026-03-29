@extends('layouts.app')

@section('title', 'Panier - Carré Premium')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.2rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-5 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-7 sm:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-end">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-gold-300)]">{{ $t('Panier', 'Cart') }}</p>
                        <h1 class="mt-3 text-3xl font-black sm:text-4xl">{{ $t('Votre panier est vide pour le moment.', 'Your cart is empty for now.') }}</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                            {{ $t('Le site ne mélange plus les parcours. Vous ajoutez une réservation depuis une fiche service, puis vous revenez ici seulement si un vrai panier est alimenté.', 'The site no longer mixes journeys. You add a booking from a service page, then come back here only when a real cart is populated.') }}
                        </p>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('events') }}" class="cp-primary-button !w-full sm:!w-auto !bg-[#f0bb61] !text-[#2a163d] hover:!bg-[#e2aa54]">
                                <i class="fa-solid fa-ticket text-sm"></i>
                                <span>{{ $t('Voir les événements', 'Browse events') }}</span>
                            </a>
                            <a href="{{ route('packages') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/10 !text-white hover:!bg-white/16">
                                <i class="fa-solid fa-route text-sm"></i>
                                <span>{{ $t('Explorer les packages', 'Explore packages') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
                        <div class="rounded-[1.45rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Événements', 'Events') }}</p>
                            <p class="mt-2 text-sm font-bold">{{ $t('Ajout direct depuis la fiche', 'Added directly from detail pages') }}</p>
                        </div>
                        <div class="rounded-[1.45rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Packages', 'Packages') }}</p>
                            <p class="mt-2 text-sm font-bold">{{ $t('Offres et demandes sur mesure', 'Offers and bespoke requests') }}</p>
                        </div>
                        <div class="rounded-[1.45rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Location', 'Rental') }}</p>
                            <p class="mt-2 text-sm font-bold">{{ $t('Demande claire avant paiement', 'Clear request before payment') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-5 md:grid-cols-3">
                <div class="cp-panel rounded-[1.8rem] p-6">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Étape 1', 'Step 1') }}</p>
                    <h2 class="mt-3 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Choisir un service', 'Choose a service') }}</h2>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Commencez depuis la page événement, package ou location qui vous intéresse.', 'Start from the event, package or rental page that fits your need.') }}</p>
                </div>
                <div class="cp-panel rounded-[1.8rem] p-6">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Étape 2', 'Step 2') }}</p>
                    <h2 class="mt-3 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Créer le dossier', 'Create the booking') }}</h2>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Renseignez les informations utiles pour générer votre dossier ou votre réservation.', 'Fill in the useful details to generate your booking record.') }}</p>
                </div>
                <div class="cp-panel rounded-[1.8rem] p-6">
                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Étape 3', 'Step 3') }}</p>
                    <h2 class="mt-3 text-xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Payer ou finaliser', 'Pay or finalize') }}</h2>
                    <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Une fois le dossier créé, le paiement et les documents se retrouvent dans votre espace client.', 'Once the booking exists, payment and documents move into your client area.') }}</p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
