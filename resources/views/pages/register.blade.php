@extends('layouts.app')

@section('title', 'Créer un compte - Carré Premium')
@section('meta_description', 'Créez votre compte Carré Premium pour suivre vos réservations, gérer vos documents et réserver plus rapidement vos services premium.')
@section('robots', 'noindex, nofollow')

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/css/intlTelInput.css">
<style>
    .iti {
        width: 100%;
    }

    .iti__selected-country-primary {
        gap: 0.45rem;
    }

    .iti--separate-dial-code .iti__selected-country {
        border-radius: 1rem 0 0 1rem;
    }

    .iti input#phone {
        padding-left: 7rem !important;
    }
</style>
@endpush

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.05fr)_minmax(380px,470px)]">
                <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4e2974] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('Création de compte', 'Account creation') }}</span>
                    </div>

                    <h1 class="mt-4 max-w-xl text-3xl font-black leading-tight sm:text-4xl">
                        {{ $t('Un compte clair pour réserver, payer et retrouver vos documents sans friction.', 'A clear account to book, pay and retrieve documents without friction.') }}
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                        {{ $t('Créez votre espace pour réserver plus vite, conserver vos préférences et retrouver vos billets, factures et confirmations.', 'Create your account to book faster, keep your preferences and retrieve your tickets, invoices and confirmations.') }}
                    </p>

                    <div class="mt-7 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Réservation', 'Booking') }}</p>
                            <p class="mt-2 text-base font-black">{{ $t('Plus rapide', 'Faster') }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $t('vos coordonnées sont déjà prêtes', 'your details stay ready') }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Suivi', 'Tracking') }}</p>
                            <p class="mt-2 text-base font-black">{{ $t('Centralisé', 'Centralized') }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $t('tous vos dossiers au même endroit', 'all your bookings in one place') }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Vérification', 'Verification') }}</p>
                            <p class="mt-2 text-base font-black">{{ $t('Immédiate', 'Immediate') }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $t('email ou SMS selon le cas', 'email or SMS depending on the flow') }}</p>
                        </div>
                    </div>
                </div>

                <div class="cp-panel rounded-[2.15rem] p-6 sm:p-8">
                    <div class="max-w-md">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Nouveau client', 'New client') }}</p>
                        <h2 class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Créer votre espace', 'Create your account') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Renseignez uniquement les informations utiles pour démarrer.', 'Only the information needed to get started.') }}
                        </p>
                    </div>

                    @if($errors->any())
                        <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p class="font-black text-red-900">{{ $t('Le compte n’a pas pu être créé pour le moment.', 'The account could not be created right now.') }}</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.post') }}" class="mt-6 space-y-4">
                        @csrf

                        <div class="grid gap-4 sm:grid-cols-2">
                            <div class="sm:col-span-2">
                                <label for="civility" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">
                                    {{ $t('Civilité', 'Title') }}
                                </label>
                                <select id="civility" name="civility" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    <option value="">{{ $t('Sélectionnez une civilité', 'Select a title') }}</option>
                                    <option value="Monsieur" @selected(old('civility') === 'Monsieur')>{{ $t('Monsieur', 'Mr.') }}</option>
                                    <option value="Madame" @selected(old('civility') === 'Madame')>{{ $t('Madame', 'Mrs.') }}</option>
                                    <option value="Mademoiselle" @selected(old('civility') === 'Mademoiselle')>{{ $t('Mademoiselle', 'Miss') }}</option>
                                </select>
                            </div>

                            <div>
                                <label for="first_name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Prénom', 'First name') }}</label>
                                <input id="first_name" name="first_name" type="text" value="{{ old('first_name') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('Votre prénom', 'Your first name') }}">
                            </div>

                            <div>
                                <label for="last_name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Nom', 'Last name') }}</label>
                                <input id="last_name" name="last_name" type="text" value="{{ old('last_name') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('Votre nom', 'Your last name') }}">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="email" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Email', 'Email') }}</label>
                                <input id="email" name="email" type="email" autocomplete="email" value="{{ old('email') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('vous@exemple.com', 'you@example.com') }}">
                            </div>

                            <div class="sm:col-span-2">
                                <label for="phone" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Téléphone', 'Phone') }}</label>
                                <input id="phone" name="phone" type="tel" autocomplete="tel" value="{{ old('phone') }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="+225 01 02 03 04 05">
                                <input type="hidden" name="phone_country_code" id="phone_country_code" value="+225">
                            </div>

                            <div>
                                <label for="password" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Mot de passe', 'Password') }}</label>
                                <input id="password" name="password" type="password" autocomplete="new-password" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('Minimum 8 caractères', 'At least 8 characters') }}">
                            </div>

                            <div>
                                <label for="password_confirmation" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Confirmation', 'Confirmation') }}</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]" placeholder="{{ $t('Confirmez le mot de passe', 'Confirm password') }}">
                            </div>
                        </div>

                        <label class="flex items-start gap-3 rounded-[1.25rem] border border-[color:var(--cp-border)] bg-[#faf6ff] px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)]">
                            <input id="terms" name="terms" type="checkbox" required class="mt-0.5 h-4 w-4 rounded border-[color:var(--cp-border-strong)] text-[color:var(--cp-plum-800)] focus:ring-[color:var(--cp-plum-700)]">
                            <span>
                                {{ $t('J’accepte les', 'I accept the') }}
                                <a href="{{ route('terms') }}" class="font-bold text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">{{ $t('conditions générales', 'terms and conditions') }}</a>
                                {{ $t('et la', 'and the') }}
                                <a href="{{ route('privacy') }}" class="font-bold text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">{{ $t('politique de confidentialité', 'privacy policy') }}</a>.
                            </span>
                        </label>

                        <button type="submit" class="cp-primary-button !flex !w-full !justify-center">
                            <i class="fa-solid fa-user-plus text-sm"></i>
                            <span>{{ $t('Créer mon compte', 'Create my account') }}</span>
                        </button>
                    </form>

                    <div class="mt-6 grid gap-3">
                        <a href="{{ route('auth.google') }}" class="cp-secondary-button !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                            <i class="fa-brands fa-google text-sm"></i>
                            <span>{{ $t('S’inscrire avec Google', 'Sign up with Google') }}</span>
                        </a>
                        <a href="{{ route('auth.facebook') }}" class="cp-secondary-button !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                            <i class="fa-brands fa-facebook-f text-sm"></i>
                            <span>{{ $t('S’inscrire avec Facebook', 'Sign up with Facebook') }}</span>
                        </a>
                    </div>

                    <div class="mt-6 rounded-[1.35rem] bg-[#f7f1ff] px-4 py-4 text-sm text-[color:var(--cp-ink-soft)]">
                        <p class="font-bold text-[color:var(--cp-plum-950)]">{{ $t('Déjà client ?', 'Already a client?') }}</p>
                        <p class="mt-1 leading-6">{{ $t('Connectez-vous pour retrouver vos réservations, vos documents et vos paiements en attente.', 'Sign in to retrieve your bookings, documents and pending payments.') }}</p>
                        <a href="{{ route('login') }}" class="mt-3 inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">
                            <span>{{ $t('Me connecter', 'Sign in') }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/intlTelInput.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const phoneInput = document.querySelector('#phone');
    const phoneCountryCodeInput = document.querySelector('#phone_country_code');

    if (!phoneInput || !window.intlTelInput) {
        return;
    }

    const iti = window.intlTelInput(phoneInput, {
        initialCountry: 'ci',
        preferredCountries: ['ci', 'fr', 'sn', 'ml', 'bf', 'ne', 'tg', 'bj', 'gn'],
        separateDialCode: true,
        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js',
        autoPlaceholder: 'aggressive',
        formatOnDisplay: true,
        nationalMode: false,
        customPlaceholder: function (selectedCountryPlaceholder) {
            return 'ex: ' + selectedCountryPlaceholder;
        }
    });

    const updateDialCode = function () {
        const countryData = iti.getSelectedCountryData();
        phoneCountryCodeInput.value = '+' + countryData.dialCode;
    };

    phoneInput.addEventListener('countrychange', updateDialCode);
    updateDialCode();

    const form = phoneInput.closest('form');

    form.addEventListener('submit', function (event) {
        if (!phoneInput.value.trim()) {
            return;
        }

        if (!iti.isValidNumber()) {
            event.preventDefault();
            alert(@json($t('Veuillez entrer un numéro de téléphone valide.', 'Please enter a valid phone number.')));
            phoneInput.focus();
            return;
        }

        phoneInput.value = iti.getNumber();
    });
});
</script>
@endpush
