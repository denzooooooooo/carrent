@extends('layouts.app')

@section('title', 'Panier - Carré Premium')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="cp-panel overflow-hidden rounded-[2.1rem] px-5 py-8 sm:px-7 sm:py-10">
                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Panier', 'Cart') }}</p>
                <h1 class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)] sm:text-4xl">{{ $t('Votre panier est vide pour le moment.', 'Your cart is empty for now.') }}</h1>
                <p class="mt-4 max-w-2xl text-sm leading-7 text-[color:var(--cp-ink-soft)] sm:text-base">
                    {{ $t('Le parcours panier n’était pas aligné avec le reste du site. Cette page garde désormais la même structure, le même ton et un point de sortie propre.', 'The cart flow was not aligned with the rest of the site. This page now keeps the same structure, tone and clean exit point.') }}
                </p>

                <div class="mt-6 flex flex-col gap-3 sm:flex-row">
                    <a href="{{ route('events') }}" class="cp-primary-button !w-full sm:!w-auto">
                        <i class="fa-solid fa-ticket text-sm"></i>
                        <span>{{ $t('Voir les événements', 'Browse events') }}</span>
                    </a>
                    <a href="{{ route('home') }}" class="cp-secondary-button !w-full sm:!w-auto">
                        <i class="fa-solid fa-house text-sm"></i>
                        <span>{{ $t("Retour à l'accueil", 'Back home') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
