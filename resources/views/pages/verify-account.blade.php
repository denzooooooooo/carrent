@extends('layouts.app')

@section('title', 'Vérification du compte - Carré Premium')
@section('meta_description', 'Validez votre compte Carré Premium avec le code reçu par email ou SMS.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_minmax(340px,440px)]">
                <div class="overflow-hidden rounded-[2.3rem] bg-gradient-to-br from-[#22112f] via-[#4d2973] to-[#d8a44a] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                    <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                        <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                        <span>{{ $t('Vérification', 'Verification') }}</span>
                    </div>

                    <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl">
                        {{ $t('Dernière étape avant d’activer votre espace client.', 'Final step before activating your client area.') }}
                    </h1>

                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                        {{ $t('Le code reçu sécurise votre compte et évite les erreurs sur vos futures réservations.', 'The received code secures your account and avoids issues on future bookings.') }}
                    </p>

                    <div class="mt-7 grid gap-3 sm:grid-cols-2">
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Email envoyé à', 'Email sent to') }}</p>
                            <p class="mt-2 break-all text-base font-black">{{ $email }}</p>
                        </div>
                        <div class="rounded-[1.5rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('SMS possible', 'SMS available') }}</p>
                            <p class="mt-2 text-base font-black">{{ $phone ?: $t('Numéro non renseigné', 'Phone not provided') }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="cp-panel rounded-[2.15rem] p-6 sm:p-8">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Code de sécurité', 'Security code') }}</p>
                        <h2 class="mt-3 text-3xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Valider mon compte', 'Verify my account') }}</h2>
                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                            {{ $t('Entrez le code à 6 chiffres reçu. Si nécessaire, vous pouvez demander un nouvel envoi.', 'Enter the 6-digit code you received. You can request a new one if needed.') }}
                        </p>

                        @foreach(['success' => 'emerald', 'warning' => 'amber', 'info' => 'blue'] as $flash => $tone)
                            @if(session($flash))
                                <div class="mt-5 rounded-[1.4rem] border px-4 py-3 text-sm font-semibold
                                    @if($tone === 'emerald') border-emerald-200 bg-emerald-50 text-emerald-800
                                    @elseif($tone === 'amber') border-amber-200 bg-amber-50 text-amber-800
                                    @else border-blue-200 bg-blue-50 text-blue-800 @endif">
                                    {{ session($flash) }}
                                </div>
                            @endif
                        @endforeach

                        @if($errors->any())
                            <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                                <ul class="list-disc space-y-1 pl-5">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('verify.code') }}" class="mt-6 space-y-4">
                            @csrf
                            <div>
                                <label for="code" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">
                                    {{ $t('Code à 6 chiffres', '6-digit code') }}
                                </label>
                                <input
                                    id="code"
                                    name="code"
                                    type="text"
                                    inputmode="numeric"
                                    pattern="[0-9]{6}"
                                    maxlength="6"
                                    required
                                    autofocus
                                    value="{{ old('code') }}"
                                    class="w-full rounded-[1.4rem] border border-[color:var(--cp-border)] bg-white px-4 py-4 text-center text-3xl font-black tracking-[0.35em] text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]"
                                    placeholder="000000"
                                >
                            </div>

                            <button type="submit" class="cp-primary-button !flex !w-full !justify-center">
                                <i class="fa-solid fa-shield-check text-sm"></i>
                                <span>{{ $t('Vérifier mon compte', 'Verify my account') }}</span>
                            </button>
                        </form>
                    </div>

                    <div class="cp-panel rounded-[2rem] p-5 sm:p-6">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Besoin d’un nouvel envoi ?', 'Need another delivery?') }}</p>

                        <div class="mt-4 grid gap-3">
                            <form action="{{ route('verify.resend') }}" method="POST">
                                @csrf
                                <button type="submit" class="cp-secondary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                    <i class="fa-solid fa-rotate-right text-sm"></i>
                                    <span>{{ $t('Renvoyer le code', 'Resend code') }}</span>
                                </button>
                            </form>

                            <form action="{{ route('verify.change-method') }}" method="POST">
                                @csrf
                                <input type="hidden" name="method" value="sms">
                                <button type="submit" class="cp-secondary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                    <i class="fa-solid fa-mobile-screen text-sm"></i>
                                    <span>{{ $t('Recevoir le code par SMS', 'Receive the code by SMS') }}</span>
                                </button>
                            </form>
                        </div>

                        <div class="mt-5 rounded-[1.25rem] bg-[#faf6ff] px-4 py-4 text-sm text-[color:var(--cp-ink-soft)]">
                            <p class="font-bold text-[color:var(--cp-plum-950)]">{{ $t('Un problème ?', 'Any issue?') }}</p>
                            <p class="mt-1 leading-6">
                                {{ $t('Si le code n’arrive pas, vérifiez vos spams puis contactez le support.', 'If the code does not arrive, check spam and contact support.') }}
                            </p>
                            <div class="mt-3 flex flex-col gap-2 sm:flex-row">
                                <a href="mailto:{{ config('carre_premium.contact.support_email') }}" class="cp-secondary-button !w-full sm:!w-auto">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                    <span>{{ $t('Envoyer un email', 'Send an email') }}</span>
                                </a>
                                <a href="{{ config('carre_premium.contact.landline_link') }}" class="cp-secondary-button !w-full sm:!w-auto">
                                    <i class="fa-solid fa-phone text-sm"></i>
                                    <span>{{ $t('Appeler', 'Call') }}</span>
                                </a>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST" class="mt-4">
                            @csrf
                            <button type="submit" class="text-sm font-bold text-[color:var(--cp-ink-soft)] hover:text-[color:var(--cp-plum-900)]">
                                {{ $t('Me déconnecter', 'Sign out') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
