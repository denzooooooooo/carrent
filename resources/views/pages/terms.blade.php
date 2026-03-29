@extends('layouts.app')

@section('title', 'Conditions générales - Carré Premium')
@section('meta_description', 'Consultez les conditions générales d’utilisation et de réservation de Carré Premium.')
@section('meta_keywords', 'conditions générales carré premium, conditions réservation, CGU, CGV')
@section('og_title', 'Conditions générales - Carré Premium')
@section('og_description', 'Conditions générales d’utilisation et de réservation Carré Premium.')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $updatedAt = '29 mars 2026';
    $sections = [
        ['title' => $t('1. Objet', '1. Scope'), 'body' => $t('Les présentes conditions encadrent l’utilisation du site, la consultation des offres et les réservations proposées par Carré Premium.', 'These terms govern the use of the site, the browsing of offers and the bookings offered by Carré Premium.')],
        ['title' => $t('2. Réservations', '2. Bookings'), 'body' => $t('Toute réservation reste soumise à disponibilité, validation des informations transmises et confirmation effective du paiement ou du mode de règlement retenu.', 'Any booking remains subject to availability, validation of the provided information and effective confirmation of payment or the chosen payment method.')],
        ['title' => $t('3. Tarifs et paiements', '3. Pricing and payments'), 'body' => $t('Les prix affichés peuvent dépendre du service, de la devise et du mode de paiement. La réservation n’est considérée comme finalisée qu’après validation du règlement.', 'Displayed prices may depend on the service, currency and payment method. A booking is only considered finalized after payment validation.')],
        ['title' => $t('4. Modifications et annulations', '4. Changes and cancellations'), 'body' => $t('Les possibilités de modification ou d’annulation varient selon le type de service, le fournisseur concerné et l’état d’avancement du dossier.', 'Change and cancellation options vary depending on the service type, the relevant provider and the stage of the booking.')],
        ['title' => $t('5. Responsabilités', '5. Liability'), 'body' => $t('Carré Premium organise la réservation, mais certaines prestations dépendent de partenaires, fournisseurs ou organisateurs externes.', 'Carré Premium organizes the booking, but some services depend on external partners, providers or organizers.')],
        ['title' => $t('6. Données et sécurité', '6. Data and security'), 'body' => $t('L’usage du service implique le traitement de certaines données personnelles et le respect des règles de sécurité décrites dans notre politique de confidentialité.', 'Using the service implies the processing of personal data and compliance with the security rules described in our privacy policy.')],
    ];
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                <div class="max-w-3xl">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('Conditions générales', 'Terms') }}</span>
                    </div>
                    <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">{{ $t('Les conditions applicables à vos réservations Carré Premium.', 'The terms that apply to your Carré Premium bookings.') }}</h1>
                    <p class="mt-4 text-sm leading-7 text-white/84 sm:text-base">{{ $t('Dernière mise à jour :', 'Last updated:') }} {{ $updatedAt }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-5 py-6 sm:px-7 sm:py-8">
                <div class="space-y-5">
                    @foreach($sections as $section)
                        <article class="rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white px-5 py-5">
                            <h2 class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $section['title'] }}</h2>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $section['body'] }}</p>
                        </article>
                    @endforeach

                    <article class="rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white px-5 py-5">
                        <h2 class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('7. Contact', '7. Contact') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Avant toute réservation sensible ou si un point n’est pas clair, contactez l’équipe Carré Premium pour obtenir une confirmation écrite du cadre applicable.', 'Before any sensitive booking, or if anything is unclear, contact the Carré Premium team to obtain written confirmation of the applicable framework.') }}
                        </p>
                        <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ route('contact') }}" class="cp-primary-button">{{ $t('Poser une question', 'Ask a question') }}</a>
                            <a href="{{ route('privacy') }}" class="cp-secondary-button">{{ $t('Voir la confidentialité', 'View privacy policy') }}</a>
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
