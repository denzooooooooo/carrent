@extends('layouts.app')

@section('title', 'Contact - Carré Premium')
@section('meta_description', 'Contactez Carré Premium pour une réservation, une demande sur mesure, un paiement ou un accompagnement client.')
@section('meta_keywords', 'contact carré premium, support client, réservation premium, conciergerie abidjan, contact voyage luxe')
@section('og_title', 'Contact - Carré Premium')
@section('og_description', 'Écrivez à Carré Premium ou contactez directement un conseiller pour vos réservations et demandes sur mesure.')

@push('head')
@php
    $contactSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ContactPage',
        'name' => 'Contact Carré Premium',
        'url' => url('/contact'),
        'mainEntity' => [
            '@type' => 'Organization',
            'name' => config('carre_premium.company.name'),
            'email' => config('carre_premium.contact.support_email'),
            'telephone' => config('carre_premium.contact.landline_display'),
        ],
    ];
@endphp
<script type="application/ld+json">{!! json_encode($contactSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endpush

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $supportEmail = config('carre_premium.contact.support_email');
    $landlineDisplay = config('carre_premium.contact.landline_display');
    $landlineLink = config('carre_premium.contact.landline_link');
    $mobileDisplay = config('carre_premium.contact.mobile_display');
    $mobileLink = config('carre_premium.contact.mobile_link');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');
    $companyAddress = collect([
        config('carre_premium.company.address'),
        config('carre_premium.company.city'),
        config('carre_premium.company.country'),
    ])->filter()->implode(', ');
    $subject = old('subject', request('subject', 'general'));
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                <div class="grid gap-8 lg:grid-cols-[minmax(0,1.08fr)_minmax(300px,360px)]">
                    <div class="max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Contact & support', 'Contact & support') }}</span>
                        </div>
                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">
                            {{ $t('Un seul point d’entrée pour poser une question, débloquer un paiement ou demander du sur-mesure.', 'One clear entry point to ask a question, unblock a payment or request something bespoke.') }}
                        </h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/84 sm:text-base">
                            {{ $t('Le support doit être simple à trouver et simple à utiliser. Cette page regroupe les bons canaux, un formulaire utile et les contacts directs de l’équipe.', 'Support should be easy to find and easy to use. This page gathers the right channels, a useful form and the team’s direct contacts.') }}
                        </p>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-1">
                        <a href="{{ $landlineLink }}" class="rounded-[1.55rem] border border-white/15 bg-white/10 px-5 py-5 text-left backdrop-blur transition hover:bg-white/14">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Téléphone', 'Phone') }}</p>
                            <p class="mt-2 text-lg font-black">{{ $landlineDisplay }}</p>
                        </a>
                        <a href="mailto:{{ $supportEmail }}" class="rounded-[1.55rem] border border-white/15 bg-white/10 px-5 py-5 text-left backdrop-blur transition hover:bg-white/14">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Email', 'Email') }}</p>
                            <p class="mt-2 break-all text-lg font-black">{{ $supportEmail }}</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-overlap">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.08fr)_360px]">
                <div class="cp-panel rounded-[2rem] p-6 sm:p-8">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Nous écrire', 'Write to us') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Décrivez votre besoin clairement', 'Describe your need clearly') }}</h2>
                        </div>
                        <div class="cp-pill">
                            <i class="fa-solid fa-clock text-xs"></i>
                            <span>{{ $t('Réponse sous 24h ouvrées', 'Reply within 24 business hours') }}</span>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mt-5 rounded-[1.4rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="mt-6 grid gap-4">
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div>
                                <label for="name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Nom complet', 'Full name') }}</label>
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                @error('name') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="phone" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Téléphone', 'Phone') }}</label>
                                <input id="phone" name="phone" type="tel" value="{{ old('phone') }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $mobileDisplay }}">
                                @error('phone') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 sm:grid-cols-[minmax(0,1fr)_220px]">
                            <div>
                                <label for="email" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Email', 'Email') }}</label>
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                @error('email') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label for="subject" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Sujet', 'Subject') }}</label>
                                <select id="subject" name="subject" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    <option value="general" @selected($subject === 'general')>{{ $t('Question générale', 'General question') }}</option>
                                    <option value="booking" @selected($subject === 'booking')>{{ $t('Réservation', 'Booking') }}</option>
                                    <option value="payment" @selected($subject === 'payment')>{{ $t('Paiement', 'Payment') }}</option>
                                    <option value="cancellation" @selected($subject === 'cancellation')>{{ $t('Annulation', 'Cancellation') }}</option>
                                    <option value="complaint" @selected($subject === 'complaint')>{{ $t('Réclamation', 'Complaint') }}</option>
                                    <option value="partnership" @selected($subject === 'partnership')>{{ $t('Partenariat', 'Partnership') }}</option>
                                </select>
                                @error('subject') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label for="message" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Message', 'Message') }}</label>
                            <textarea id="message" name="message" rows="7" required class="w-full rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white px-4 py-4 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('Expliquez votre besoin, votre service concerné et ce qui vous bloque.', 'Describe your need, the related service and what is blocking you.') }}">{{ old('message') }}</textarea>
                            @error('message') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="cp-primary-button !flex !w-full !justify-center sm:!w-auto">
                            <i class="fa-regular fa-paper-plane text-sm"></i>
                            <span>{{ $t('Envoyer la demande', 'Send request') }}</span>
                        </button>
                    </form>
                </div>

                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Accès direct', 'Direct access') }}</p>
                        <div class="mt-5 grid gap-3">
                            <a href="{{ $mobileLink }}" class="cp-secondary-button !flex !w-full !justify-between !rounded-[1.2rem] !py-3 text-sm">
                                <span>{{ $t('Appeler un conseiller', 'Call an advisor') }}</span>
                                <i class="fa-solid fa-phone text-sm"></i>
                            </a>
                            <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="cp-secondary-button !flex !w-full !justify-between !rounded-[1.2rem] !py-3 text-sm">
                                <span>{{ $t('Ouvrir WhatsApp', 'Open WhatsApp') }}</span>
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                            </a>
                            <a href="mailto:{{ $supportEmail }}" class="cp-secondary-button !flex !w-full !justify-between !rounded-[1.2rem] !py-3 text-sm">
                                <span>{{ $t('Envoyer un email', 'Send an email') }}</span>
                                <i class="fa-regular fa-envelope text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <div class="cp-panel rounded-[2rem] p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Coordonnées', 'Contact details') }}</p>
                        <div class="mt-5 space-y-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            <div>
                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $t('Téléphone fixe', 'Landline') }}</p>
                                <p>{{ $landlineDisplay }}</p>
                            </div>
                            <div>
                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $t('Mobile / WhatsApp', 'Mobile / WhatsApp') }}</p>
                                <p>{{ $mobileDisplay }}</p>
                            </div>
                            <div>
                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $t('Adresse', 'Address') }}</p>
                                <p>{{ $companyAddress }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="cp-panel rounded-[2rem] p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('FAQ rapide', 'Quick FAQ') }}</p>
                        <div class="mt-4 space-y-3">
                            @foreach([
                                ['q' => $t('Comment suivre ma réservation ?', 'How do I track my booking?'), 'a' => $t('Depuis votre profil, rubrique Mes réservations, ou via le lien reçu après commande.', 'From your profile under My bookings, or via the link received after purchase.')],
                                ['q' => $t('Puis-je payer plus tard ?', 'Can I pay later?'), 'a' => $t('Oui si le dossier est créé, vous pouvez revenir au checkout tant que la réservation reste ouverte.', 'Yes, if the booking exists you can return to checkout while it remains open.')],
                                ['q' => $t('Puis-je demander du sur-mesure ?', 'Can I request something bespoke?'), 'a' => $t('Oui. Le plus simple est de détailler votre besoin dans le formulaire ou par téléphone.', 'Yes. The easiest way is to describe it in the form or by phone.')],
                            ] as $faq)
                                <details class="event-accordion p-0">
                                    <summary class="cursor-pointer list-none px-4 py-4 text-sm font-black text-[color:var(--cp-plum-950)]">{{ $faq['q'] }}</summary>
                                    <div class="px-4 pb-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $faq['a'] }}</div>
                                </details>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="cp-panel mt-6 rounded-[2rem] p-6 sm:p-8">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Adresse', 'Address') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Notre base à Abidjan', 'Our base in Abidjan') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $companyAddress }}</p>
                    </div>
                    <div class="cp-pill">
                        <i class="fa-solid fa-location-dot text-xs"></i>
                        <span>{{ $t('Support client et coordination', 'Client support and coordination') }}</span>
                    </div>
                </div>

                <div class="mt-6 aspect-[16/7] overflow-hidden rounded-[1.7rem] bg-[#ebe4f6]">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.2!2d-4.0!3d5.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMTgnMDAuMCJOIDTCsDAwJzAwLjAiVw!5e0!3m2!1sfr!2sci!4v1234567890&q=Abidjan+Marcory+Biétry+Boulevard+de+Marseille,+Côte+d'Ivoire"
                        width="100%"
                        height="100%"
                        style="border:0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
