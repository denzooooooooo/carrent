@extends('layouts.app')

@section('title', 'Politique de confidentialité - Carré Premium')
@section('meta_description', 'Consultez la politique de confidentialité Carré Premium et la manière dont vos données sont utilisées, protégées et conservées.')
@section('meta_keywords', 'confidentialité carré premium, données personnelles, protection données, politique confidentialité')
@section('og_title', 'Politique de confidentialité - Carré Premium')
@section('og_description', 'Comprenez comment Carré Premium collecte, utilise et protège vos données personnelles.')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $updatedAt = '29 mars 2026';
    $sections = [
        [
            'title' => $t('1. Données collectées', '1. Data collected'),
            'body' => $t('Nous collectons les données nécessaires au fonctionnement du service: identité, coordonnées, informations de réservation, paiement, navigation et préférences utiles au suivi client.', 'We collect the data required to operate the service: identity, contact details, booking details, payment, browsing information and preferences useful for client follow-up.'),
        ],
        [
            'title' => $t('2. Finalités', '2. Purposes'),
            'body' => $t('Ces données servent à créer un dossier, encaisser un paiement, générer des documents, communiquer sur votre réservation, sécuriser la plateforme et améliorer le parcours client.', 'This data is used to create a booking record, process payment, generate documents, communicate about your booking, secure the platform and improve the customer journey.'),
        ],
        [
            'title' => $t('3. Partage', '3. Sharing'),
            'body' => $t('Les données ne sont partagées qu’avec les partenaires ou prestataires strictement nécessaires au service: paiement, émission de documents, exécution d’une réservation ou obligations légales.', 'Data is only shared with partners or providers strictly necessary for the service: payment, document issuance, booking execution or legal obligations.'),
        ],
        [
            'title' => $t('4. Conservation', '4. Retention'),
            'body' => $t('Les informations sont conservées aussi longtemps que nécessaire au traitement du service, à la relation commerciale et au respect des obligations comptables ou réglementaires.', 'Information is retained as long as needed for service delivery, customer relationship management and compliance with accounting or regulatory obligations.'),
        ],
        [
            'title' => $t('5. Vos droits', '5. Your rights'),
            'body' => $t('Vous pouvez demander l’accès, la rectification, la limitation ou la suppression de certaines données, sous réserve des obligations légales qui s’imposent au service.', 'You may request access, correction, restriction or deletion of certain data, subject to the legal obligations that apply to the service.'),
        ],
    ];
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                <div class="max-w-3xl">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('Confidentialité', 'Privacy') }}</span>
                    </div>
                    <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">{{ $t('Vos données doivent être compréhensibles, protégées et limitées à ce qui sert réellement.', 'Your data should be understandable, protected and limited to what actually serves the service.') }}</h1>
                    <p class="mt-4 text-sm leading-7 text-white/84 sm:text-base">{{ $t('Dernière mise à jour :', 'Last updated:') }} {{ $updatedAt }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="cp-panel rounded-[2rem] px-5 py-6 sm:px-7 sm:py-8">
                <div class="grid gap-6 lg:grid-cols-[260px_minmax(0,1fr)]">
                    <aside class="rounded-[1.8rem] bg-[#faf6ff] p-5">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Résumé', 'Summary') }}</p>
                        <ul class="mt-4 space-y-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            <li>{{ $t('Données limitées au service rendu.', 'Data limited to what the service needs.') }}</li>
                            <li>{{ $t('Protection des paiements et des dossiers clients.', 'Protection of payments and booking records.') }}</li>
                            <li>{{ $t('Possibilité de contacter l’équipe pour exercer vos droits.', 'You can contact the team to exercise your rights.') }}</li>
                        </ul>
                    </aside>

                    <div class="space-y-5">
                        @foreach($sections as $section)
                            <article class="rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white px-5 py-5">
                                <h2 class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $section['title'] }}</h2>
                                <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $section['body'] }}</p>
                            </article>
                        @endforeach

                        <article class="rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white px-5 py-5">
                            <h2 class="text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('6. Contact et demandes', '6. Contact and requests') }}</h2>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Pour toute demande relative à vos données, contactez le support Carré Premium. Nous traiterons la demande selon sa nature et les obligations applicables.', 'For any request related to your data, contact Carré Premium support. We will process it according to its nature and the obligations that apply.') }}
                            </p>
                            <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                                <a href="{{ route('contact') }}" class="cp-primary-button">{{ $t('Contacter le support', 'Contact support') }}</a>
                                <a href="mailto:{{ config('carre_premium.contact.support_email') }}" class="cp-secondary-button">{{ config('carre_premium.contact.support_email') }}</a>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
