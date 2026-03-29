@extends('layouts.app')

@section('title', __('Réservation de vols avec accompagnement') . ' - Carré Premium')
@section('meta_description', 'Pour réserver un vol avec Carré Premium, contactez directement notre service client. Notre équipe vous accompagne pour vos trajets privés, professionnels, familiaux ou urgents.')
@section('meta_keywords', 'réservation de vol, service client, concierge voyage, Carré Premium, Abidjan, Côte d\'Ivoire')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;

    $mobilePhoneDisplay = '+225 01 01 22 15 15';
    $mobilePhoneLink = 'tel:+2250101221515';
    $landlinePhoneDisplay = '+225 27 21 59 42 58';
    $landlinePhoneLink = 'tel:+2252721594258';
    $emailAddress = 'infos@carrepremium.com';
    $emailLink = 'mailto:' . $emailAddress . '?subject=' . rawurlencode($t('Demande de reservation de vol', 'Flight reservation request'));
    $whatsAppLink = 'https://wa.me/2250101221515?text=' . rawurlencode($t(
        'Bonjour, je souhaite reserver un vol avec Carré Premium.',
        'Hello, I would like to book a flight with Carré Premium.'
    ));

    $contactItems = [
        [
            'label' => $t('Téléphone mobile', 'Mobile phone'),
            'value' => $mobilePhoneDisplay,
            'href' => $mobilePhoneLink,
            'icon' => 'fa-solid fa-phone',
            'hint' => $t('Pour une demande rapide ou urgente', 'Best for urgent requests'),
        ],
        [
            'label' => 'WhatsApp',
            'value' => $t('Écrire au service client', 'Message customer service'),
            'href' => $whatsAppLink,
            'icon' => 'fa-brands fa-whatsapp',
            'hint' => $t('Pratique pour envoyer votre trajet et vos dates', 'Convenient for sharing route and dates'),
        ],
        [
            'label' => 'Email',
            'value' => $emailAddress,
            'href' => $emailLink,
            'icon' => 'fa-solid fa-envelope',
            'hint' => $t('Pour une demande détaillée ou multi-passagers', 'For detailed or multi-passenger requests'),
        ],
    ];

    $processSteps = [
        [
            'number' => '01',
            'title' => $t('Vous envoyez votre besoin', 'You send your request'),
            'text' => $t('Trajet, dates, nombre de passagers et niveau de confort suffisent pour démarrer.', 'Route, dates, passenger count and comfort level are enough to get started.'),
        ],
        [
            'number' => '02',
            'title' => $t('Nous vérifions les options', 'We check the options'),
            'text' => $t('Notre équipe recherche les solutions disponibles et les conditions utiles pour votre dossier.', 'Our team reviews available options and the relevant conditions for your request.'),
        ],
        [
            'number' => '03',
            'title' => $t('Un conseiller vous répond', 'An advisor replies'),
            'text' => $t('Vous recevez une proposition plus claire qu’un tunnel de réservation générique.', 'You receive a proposal that is clearer than a generic booking funnel.'),
        ],
        [
            'number' => '04',
            'title' => $t('Nous finalisons avec vous', 'We finalize with you'),
            'text' => $t('Le suivi se fait ensuite directement avec le service client jusqu’à la validation complète.', 'Follow-up then happens directly with customer service until final confirmation.'),
        ],
    ];

    $requestChecklist = [
        $t('Ville de départ et destination', 'Departure city and destination'),
        $t('Dates souhaitées ou marge de flexibilité', 'Preferred dates or flexibility window'),
        $t('Nombre de voyageurs et âge des enfants si besoin', 'Number of travelers and children ages if needed'),
        $t('Classe souhaitée: économique, affaires ou première', 'Preferred cabin: economy, business or first'),
        $t('Contraintes utiles: bagages, escales, visa, urgence', 'Useful constraints: baggage, layovers, visa, urgency'),
    ];

    $serviceReasons = [
        $t('Mieux adapté aux demandes premium ou urgentes', 'Better suited to premium or urgent requests'),
        $t('Plus simple pour les familles, groupes et besoins spécifiques', 'Simpler for families, groups and special requirements'),
        $t('Plus cohérent qu’un moteur de recherche partiel', 'More consistent than a partial search engine'),
        $t('Permet un suivi direct avec une équipe humaine', 'Allows direct follow-up with a human team'),
    ];
@endphp

<div class="min-h-screen bg-white">
    <section class="bg-purple-600">
        <div class="container mx-auto px-4 py-14 md:py-20">
            <div class="max-w-4xl text-white">
                <span class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-4 py-2 text-xs font-bold uppercase tracking-[0.22em] text-white/90">
                    <span class="h-2 w-2 rounded-full bg-white"></span>
                    {{ $t('Service client vols', 'Flight customer service') }}
                </span>

                <h1 class="mt-5 text-3xl font-black leading-tight sm:text-4xl md:text-5xl">
                    {{ $t('La réservation de vols passe maintenant directement par notre équipe.', 'Flight bookings now go directly through our team.') }}
                </h1>

                <p class="mt-4 max-w-3xl text-sm leading-7 text-white/90 sm:text-base md:text-lg">
                    {{ $t('Nous avons retiré le parcours autonome. Cette page sert désormais à vous mettre rapidement en relation avec le service client pour une demande plus propre, plus fiable et mieux suivie.', 'We removed the self-service flow. This page now exists to connect you quickly with customer service for a cleaner, more reliable and better tracked request.') }}
                </p>

                <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                    <a href="{{ $mobilePhoneLink }}" class="inline-flex items-center justify-center gap-3 rounded-xl bg-white px-6 py-3 text-sm font-black text-purple-600 transition hover:bg-purple-50">
                        <i class="fa-solid fa-phone"></i>
                        <span>{{ $t('Appeler un conseiller', 'Call an advisor') }}</span>
                    </a>
                    <a href="{{ $whatsAppLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center justify-center gap-3 rounded-xl border border-white/25 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                        <i class="fa-brands fa-whatsapp"></i>
                        <span>{{ $t('Contacter sur WhatsApp', 'Contact on WhatsApp') }}</span>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center justify-center gap-3 rounded-xl border border-white/25 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">
                        <i class="fa-regular fa-envelope"></i>
                        <span>{{ $t('Ouvrir la page contact', 'Open contact page') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <section class="relative -mt-8 bg-gray-50 pb-10 md:-mt-10 md:pb-14">
        <div class="container mx-auto px-4">
            <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-[360px_minmax(0,1fr)]">
                <aside class="rounded-3xl bg-white p-6 shadow-xl shadow-purple-100/40 ring-1 ring-gray-100">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-purple-600">{{ $t('Contacts rapides', 'Quick contacts') }}</p>
                    <h2 class="mt-3 text-2xl font-black text-gray-900">{{ $t('Choisissez le canal le plus simple pour vous.', 'Choose the easiest contact channel for you.') }}</h2>

                    <div class="mt-6 space-y-3">
                        @foreach($contactItems as $item)
                            <a href="{{ $item['href'] }}" @if(str_starts_with($item['href'], 'http')) target="_blank" rel="noopener noreferrer" @endif class="flex items-start gap-4 rounded-2xl border border-gray-200 px-4 py-4 transition hover:border-purple-200 hover:bg-purple-50/40">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-100 text-purple-600">
                                    <i class="{{ $item['icon'] }}"></i>
                                </span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-xs font-bold uppercase tracking-[0.18em] text-gray-400">{{ $item['label'] }}</span>
                                    <span class="mt-1 block text-base font-black text-gray-900 break-words">{{ $item['value'] }}</span>
                                    <span class="mt-1 block text-sm text-gray-500">{{ $item['hint'] }}</span>
                                </span>
                            </a>
                        @endforeach

                        <a href="{{ $landlinePhoneLink }}" class="flex items-start gap-4 rounded-2xl border border-gray-200 px-4 py-4 transition hover:border-purple-200 hover:bg-purple-50/40">
                            <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-100 text-purple-600">
                                <i class="fa-solid fa-headset"></i>
                            </span>
                            <span class="min-w-0 flex-1">
                                <span class="block text-xs font-bold uppercase tracking-[0.18em] text-gray-400">{{ $t('Ligne fixe', 'Landline') }}</span>
                                <span class="mt-1 block text-base font-black text-gray-900">{{ $landlinePhoneDisplay }}</span>
                                <span class="mt-1 block text-sm text-gray-500">{{ $t('Accueil Carré Premium', 'Carré Premium front desk') }}</span>
                            </span>
                        </a>
                    </div>

                    <div class="mt-6 rounded-2xl bg-purple-50 px-5 py-4">
                        <p class="text-sm font-semibold text-purple-700">
                            {{ $t('La réservation directe n’est plus affichée ici. La page vol sert à lancer une demande encadrée par le service client.', 'Direct booking is no longer shown here. The flight page now starts a request handled by customer service.') }}
                        </p>
                    </div>
                </aside>

                <div class="rounded-3xl bg-white p-6 shadow-xl shadow-gray-100 ring-1 ring-gray-100 md:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-purple-600">{{ $t('Demande de vol', 'Flight request') }}</p>
                    <h2 class="mt-3 text-2xl font-black text-gray-900 md:text-3xl">{{ $t('Envoyez votre besoin directement depuis cette page.', 'Send your request directly from this page.') }}</h2>
                    <p class="mt-3 max-w-3xl text-sm leading-7 text-gray-600 md:text-base">
                        {{ $t('Si vous préférez ne pas appeler tout de suite, laissez votre demande ici. Notre équipe reviendra vers vous avec les options adaptées.', 'If you prefer not to call right away, leave your request here. Our team will come back to you with suitable options.') }}
                    </p>

                    @if(session('success'))
                        <div class="mt-6 rounded-2xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('info'))
                        <div class="mt-6 rounded-2xl border border-purple-200 bg-purple-50 px-4 py-3 text-sm text-purple-700">
                            {{ session('info') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            {{ $t('Merci de corriger les champs du formulaire avant de renvoyer votre demande.', 'Please correct the form fields before resubmitting your request.') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="mt-6 space-y-5">
                        @csrf
                        <input type="hidden" name="subject" value="{{ $t('Demande de reservation de vol', 'Flight reservation request') }}">

                        <div class="grid gap-4 md:grid-cols-2">
                            <div>
                                <label class="mb-2 block text-sm font-bold text-gray-700">{{ $t('Nom complet', 'Full name') }} *</label>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                    placeholder="{{ $t('Votre nom', 'Your name') }}"
                                    required
                                >
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="mb-2 block text-sm font-bold text-gray-700">{{ $t('Email', 'Email') }} *</label>
                                <input
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                    placeholder="infos@carrepremium.com"
                                    required
                                >
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="grid gap-4 md:grid-cols-[minmax(0,1fr)_220px]">
                            <div>
                                <label class="mb-2 block text-sm font-bold text-gray-700">{{ $t('Téléphone', 'Phone') }}</label>
                                <input
                                    type="tel"
                                    name="phone"
                                    value="{{ old('phone') }}"
                                    class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                    placeholder="+225 01 01 22 15 15"
                                >
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="rounded-2xl bg-gray-50 px-4 py-4">
                                <p class="text-xs font-bold uppercase tracking-[0.18em] text-gray-400">{{ $t('Conseil', 'Tip') }}</p>
                                <p class="mt-2 text-sm text-gray-600">{{ $t('Indiquez aussi la ville de départ, la destination et vos dates dans le message.', 'Also include departure city, destination and dates in the message.') }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-bold text-gray-700">{{ $t('Votre demande', 'Your request') }} *</label>
                            <textarea
                                name="message"
                                rows="7"
                                class="w-full rounded-2xl border border-gray-300 px-4 py-3 text-sm text-gray-700 outline-none transition focus:border-purple-500 focus:ring-4 focus:ring-purple-100"
                                placeholder="{{ $t('Exemple: Abidjan vers Paris, aller-retour, 2 adultes, départ autour du 15 avril, retour flexible, classe affaires.', 'Example: Abidjan to Paris, round trip, 2 adults, departure around April 15, flexible return, business class.') }}"
                                required
                            >{{ old('message') }}</textarea>
                            @error('message')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <p class="text-sm text-gray-500">
                                {{ $t('Réponse par téléphone ou email selon votre préférence.', 'Reply by phone or email depending on your preference.') }}
                            </p>
                            <button type="submit" class="inline-flex items-center justify-center gap-2 rounded-2xl bg-purple-600 px-6 py-3 text-sm font-bold text-white transition hover:bg-purple-700">
                                <span>{{ $t('Envoyer la demande', 'Send request') }}</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10 md:py-14">
        <div class="container mx-auto px-4">
            <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-[minmax(0,1.05fr)_360px]">
                <div class="rounded-3xl bg-white p-6 shadow-lg shadow-gray-100 ring-1 ring-gray-100 md:p-8">
                    <p class="text-xs font-bold uppercase tracking-[0.22em] text-purple-600">{{ $t('Comment ça marche', 'How it works') }}</p>
                    <h2 class="mt-3 text-2xl font-black text-gray-900 md:text-3xl">{{ $t('Un parcours plus net et plus crédible.', 'A cleaner and more credible flow.') }}</h2>

                    <div class="mt-8 grid gap-4 md:grid-cols-2">
                        @foreach($processSteps as $step)
                            <article class="rounded-3xl border border-gray-200 p-5">
                                <div class="flex items-center gap-3">
                                    <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl bg-purple-100 text-sm font-black text-purple-600">{{ $step['number'] }}</span>
                                    <h3 class="text-lg font-black text-gray-900">{{ $step['title'] }}</h3>
                                </div>
                                <p class="mt-4 text-sm leading-7 text-gray-600">{{ $step['text'] }}</p>
                            </article>
                        @endforeach
                    </div>
                </div>

                <aside class="space-y-4">
                    <div class="rounded-3xl bg-white p-6 shadow-lg shadow-gray-100 ring-1 ring-gray-100">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-purple-600">{{ $t('À préparer', 'Prepare this') }}</p>
                        <ul class="mt-5 space-y-4">
                            @foreach($requestChecklist as $item)
                                <li class="flex items-start gap-3">
                                    <span class="mt-0.5 flex h-7 w-7 items-center justify-center rounded-full bg-purple-100 text-sm text-purple-600">
                                        <i class="fa-solid fa-check"></i>
                                    </span>
                                    <span class="text-sm font-medium leading-7 text-gray-700">{{ $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="rounded-3xl border border-purple-200 bg-purple-50 p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.22em] text-purple-600">{{ $t('Pourquoi ce format', 'Why this format') }}</p>
                        <ul class="mt-4 space-y-3">
                            @foreach($serviceReasons as $reason)
                                <li class="flex items-start gap-3 text-sm leading-7 text-gray-700">
                                    <i class="fa-solid fa-circle text-[8px] text-purple-600 mt-2"></i>
                                    <span>{{ $reason }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>
</div>
@endsection
