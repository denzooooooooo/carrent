@extends('layouts.app')

@section('title', 'Connexion client - Carré Premium')
@section('meta_description', 'Connectez-vous à votre espace Carré Premium pour suivre vos réservations, télécharger vos documents et gérer votre profil.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $supportEmail = config('carre_premium.contact.support_email');
    $mobileLink = config('carre_premium.contact.mobile_link');
    $mobileDisplay = config('carre_premium.contact.mobile_display');
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.08fr)_minmax(360px,460px)]">
                <div class="overflow-hidden rounded-[2.35rem] bg-gradient-to-br from-[#22112f] via-[#4c2872] to-[#d6a04a] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('Espace client', 'Client access') }}</span>
                    </div>

                    <h1 class="mt-4 max-w-xl text-3xl font-black leading-tight sm:text-4xl">
                        {{ $t('Retrouvez vos réservations, paiements et documents au même endroit.', 'Keep your bookings, payments and documents in one clear place.') }}
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                        {{ $t('La connexion doit être immédiate à comprendre: récupérer un dossier, payer ce qui reste, puis télécharger les justificatifs sans se perdre.', 'Signing in should feel immediate: recover a booking, complete payment, then download documents without friction.') }}
                    </p>

                    <div class="mt-7 grid gap-3 sm:grid-cols-3">
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Suivi', 'Tracking') }}</p>
                            <p class="mt-2 text-base font-black">{{ $t('Réservations', 'Bookings') }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $t('voir le statut en direct', 'check status in real time') }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Documents', 'Documents') }}</p>
                            <p class="mt-2 text-base font-black">{{ $t('Factures & reçus', 'Invoices & receipts') }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $t('téléchargement rapide', 'fast downloads') }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Support', 'Support') }}</p>
                            <p class="mt-2 text-base font-black">{{ $t('Conseiller humain', 'Human advisor') }}</p>
                            <p class="mt-1 text-sm text-white/76">{{ $t('si un dossier bloque', 'if a booking gets stuck') }}</p>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/10 !text-white hover:!bg-white/16">
                            <i class="fa-regular fa-user text-sm"></i>
                            <span>{{ $t('Créer un compte', 'Create an account') }}</span>
                        </a>
                        <a href="{{ $mobileLink }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/20 !bg-white/10 !text-white hover:!bg-white/16">
                            <i class="fa-solid fa-phone text-sm"></i>
                            <span>{{ $mobileDisplay }}</span>
                        </a>
                    </div>
                </div>

                <div class="cp-panel rounded-[2.15rem] p-6 sm:p-8">
                    <div class="max-w-md">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Connexion', 'Sign in') }}</p>
                        <h2 class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Accéder à votre espace', 'Access your account') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Connectez-vous pour retrouver votre historique, vos paiements et vos téléchargements.', 'Sign in to retrieve your history, payments and downloads.') }}
                        </p>
                    </div>

                    @if(session('success'))
                        <div class="mt-5 rounded-[1.4rem] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                            <p class="font-black text-red-900">{{ $t('Connexion impossible pour le moment.', 'Unable to sign in right now.') }}</p>
                            <ul class="mt-2 list-disc space-y-1 pl-5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login.post') }}" class="mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">
                                {{ $t('Email', 'Email') }}
                            </label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                autocomplete="email"
                                required
                                value="{{ old('email') }}"
                                class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]"
                                placeholder="{{ $t('vous@exemple.com', 'you@example.com') }}"
                            >
                        </div>

                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3">
                                <label for="password" class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">
                                    {{ $t('Mot de passe', 'Password') }}
                                </label>
                                <a href="{{ route('contact') }}" class="text-xs font-bold text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">
                                    {{ $t('Besoin d’aide ?', 'Need help?') }}
                                </a>
                            </div>

                            <input
                                id="password"
                                name="password"
                                type="password"
                                autocomplete="current-password"
                                required
                                class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]"
                                placeholder="{{ $t('Votre mot de passe', 'Your password') }}"
                            >
                        </div>

                        <label class="flex items-center gap-3 rounded-[1.25rem] border border-[color:var(--cp-border)] bg-[#faf6ff] px-4 py-3 text-sm font-semibold text-[color:var(--cp-ink-soft)]">
                            <input type="checkbox" name="remember" class="h-4 w-4 rounded border-[color:var(--cp-border-strong)] text-[color:var(--cp-plum-800)] focus:ring-[color:var(--cp-plum-700)]">
                            <span>{{ $t('Garder ma session active sur cet appareil', 'Keep me signed in on this device') }}</span>
                        </label>

                        <button type="submit" class="cp-primary-button !mt-2 !flex !w-full !justify-center">
                            <i class="fa-solid fa-arrow-right-to-bracket text-sm"></i>
                            <span>{{ $t('Se connecter', 'Sign in') }}</span>
                        </button>
                    </form>

                    <div class="mt-6 grid gap-3">
                        <a href="{{ route('auth.google') }}" class="cp-secondary-button !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                            <i class="fa-brands fa-google text-sm"></i>
                            <span>{{ $t('Continuer avec Google', 'Continue with Google') }}</span>
                        </a>
                        <a href="{{ route('auth.facebook') }}" class="cp-secondary-button !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                            <i class="fa-brands fa-facebook-f text-sm"></i>
                            <span>{{ $t('Continuer avec Facebook', 'Continue with Facebook') }}</span>
                        </a>
                    </div>

                    <div class="mt-6 rounded-[1.35rem] bg-[#f7f1ff] px-4 py-4 text-sm text-[color:var(--cp-ink-soft)]">
                        <p class="font-bold text-[color:var(--cp-plum-950)]">{{ $t('Pas encore de compte ?', 'No account yet?') }}</p>
                        <p class="mt-1 leading-6">{{ $t('Créez votre espace client pour suivre vos demandes, réservations et paiements sur mobile comme sur desktop.', 'Create your account to track requests, bookings and payments on mobile and desktop.') }}</p>
                        <a href="{{ route('register') }}" class="mt-3 inline-flex items-center gap-2 text-sm font-black text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">
                            <span>{{ $t('Créer mon compte', 'Create my account') }}</span>
                            <i class="fa-solid fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <p class="mt-4 text-xs leading-6 text-[color:var(--cp-ink-muted)]">
                        {{ $t('Support client :', 'Customer support:') }}
                        <a href="mailto:{{ $supportEmail }}" class="font-bold text-[color:var(--cp-plum-800)] hover:text-[color:var(--cp-plum-700)]">{{ $supportEmail }}</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
