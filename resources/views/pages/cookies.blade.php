@extends('layouts.app')

@section('title', 'Politique de cookies - Carré Premium')
@section('meta_description', 'Découvrez comment Carré Premium utilise les cookies pour faire fonctionner le site, mémoriser vos préférences et améliorer le parcours client.')
@section('meta_keywords', 'cookies carré premium, préférences navigateur, politique cookies')
@section('og_title', 'Politique de cookies - Carré Premium')
@section('og_description', 'Usage des cookies, stockage local et préférences techniques sur Carré Premium.')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-6 py-8 sm:px-8 sm:py-10">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Cookies', 'Cookies') }}</p>
                <h1 class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)] sm:text-4xl">{{ $t('Des cookies limités à ce qui fait fonctionner le site et améliore son usage.', 'Cookies limited to what runs the site and improves its use.') }}</h1>
                <div class="mt-5 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-[1.4rem] bg-[#faf6ff] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Cookies techniques pour la session, la langue, la devise et le thème.', 'Technical cookies for session, language, currency and theme.') }}</div>
                    <div class="rounded-[1.4rem] bg-[#faf6ff] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Éléments nécessaires au suivi de réservation et à la sécurité.', 'Elements required for booking tracking and security.') }}</div>
                    <div class="rounded-[1.4rem] bg-[#faf6ff] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Vous gardez la main via votre navigateur et vos réglages.', 'You keep control through your browser and settings.') }}</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
