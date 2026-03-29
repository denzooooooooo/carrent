@extends('layouts.app')

@section('title', 'Mon profil - Carré Premium')
@section('meta_description', 'Gérez vos informations personnelles, vos préférences et votre mot de passe depuis votre espace Carré Premium.')
@section('robots', 'noindex, nofollow')

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $user = $user ?? auth()->user();
    $recentPaidBookings = $user->recent_bookings->where('payment_status', 'paid')->take(3);
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[280px_minmax(0,1fr)]">
                @include('pages.users._account-nav', ['accountUser' => $user, 'activePage' => 'profile'])

                <div class="space-y-6">
                    <div class="overflow-hidden rounded-[2.3rem] bg-gradient-to-br from-[#22112f] via-[#4c2872] to-[#d9a64d] px-6 py-8 text-white shadow-[0_28px_90px_rgba(41,20,58,0.22)] sm:px-8 sm:py-10">
                        <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                            <div class="max-w-3xl">
                                <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                                    <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                                    <span>{{ $t('Profil client', 'Client profile') }}</span>
                                </div>
                                <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl">{{ $t('Un espace plus propre pour gérer vos informations et vos préférences.', 'A cleaner area to manage your details and preferences.') }}</h1>
                                <p class="mt-4 text-sm leading-7 text-white/82 sm:text-base">
                                    {{ $t('Les informations utiles au parcours sont regroupées ici: identité, coordonnées, préférences et sécurité du compte.', 'Useful account details now live in one place: identity, contact details, preferences and account security.') }}
                                </p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-[1.4rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Email', 'Email') }}</p>
                                    <p class="mt-2 text-sm font-bold">{{ $user->email }}</p>
                                </div>
                                <div class="rounded-[1.4rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                    <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Vérification', 'Verification') }}</p>
                                    <p class="mt-2 text-sm font-bold">{{ $user->is_verified ? $t('Compte vérifié', 'Verified account') : $t('Vérification en attente', 'Verification pending') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="rounded-[1.6rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-800">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="rounded-[1.6rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-800">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="grid gap-6 xl:grid-cols-[minmax(0,1.12fr)_minmax(340px,0.88fr)]">
                        <div class="cp-panel rounded-[2rem] p-6 sm:p-8">
                            <div class="flex items-center justify-between gap-4">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Informations personnelles', 'Personal details') }}</p>
                                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Mettre à jour votre profil', 'Update your profile') }}</h2>
                                </div>
                                @if(!$user->is_verified)
                                    <a href="{{ route('verify.show') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">
                                        <i class="fa-solid fa-shield-check text-sm"></i>
                                        <span>{{ $t('Vérifier', 'Verify') }}</span>
                                    </a>
                                @endif
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-5">
                                @csrf
                                @method('PUT')

                                <div class="flex flex-col gap-4 rounded-[1.6rem] bg-[#faf6ff] p-4 sm:flex-row sm:items-center">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->first_name }}" class="h-20 w-20 rounded-[1.45rem] object-cover shadow-lg">
                                    @else
                                        <span class="inline-flex h-20 w-20 items-center justify-center rounded-[1.45rem] bg-[color:var(--cp-plum-900)] text-2xl font-black text-white shadow-lg">
                                            {{ strtoupper(mb_substr($user->first_name ?? 'C', 0, 1)) }}
                                        </span>
                                    @endif

                                    <div class="min-w-0 flex-1">
                                        <p class="text-sm font-black text-[color:var(--cp-plum-950)]">{{ $t('Photo de profil', 'Profile picture') }}</p>
                                        <p class="mt-1 text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $t('Optionnelle, mais utile pour retrouver plus vite votre espace.', 'Optional, but useful to spot your account faster.') }}</p>
                                    </div>

                                    <div class="sm:max-w-[240px] sm:flex-1">
                                        <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-[color:var(--cp-ink-soft)] file:mr-3 file:rounded-full file:border-0 file:bg-[color:var(--cp-plum-900)] file:px-4 file:py-2.5 file:text-sm file:font-bold file:text-white">
                                    </div>
                                </div>

                                <div class="grid gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="first_name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Prénom', 'First name') }}</label>
                                        <input id="first_name" name="first_name" type="text" value="{{ old('first_name', $user->first_name) }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('first_name') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="last_name" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Nom', 'Last name') }}</label>
                                        <input id="last_name" name="last_name" type="text" value="{{ old('last_name', $user->last_name) }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('last_name') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Email', 'Email') }}</label>
                                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('email') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Téléphone', 'Phone') }}</label>
                                        <input id="phone" name="phone" type="tel" value="{{ old('phone', $user->phone) }}" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('phone') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="date_of_birth" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Date de naissance', 'Date of birth') }}</label>
                                        <input id="date_of_birth" name="date_of_birth" type="date" value="{{ old('date_of_birth', optional($user->date_of_birth)->format('Y-m-d')) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('date_of_birth') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="gender" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Genre', 'Gender') }}</label>
                                        <select id="gender" name="gender" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                            <option value="">{{ $t('Non renseigné', 'Not specified') }}</option>
                                            <option value="male" @selected(old('gender', $user->gender) === 'male')>{{ $t('Homme', 'Male') }}</option>
                                            <option value="female" @selected(old('gender', $user->gender) === 'female')>{{ $t('Femme', 'Female') }}</option>
                                            <option value="other" @selected(old('gender', $user->gender) === 'other')>{{ $t('Autre', 'Other') }}</option>
                                        </select>
                                        @error('gender') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="nationality" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Nationalité', 'Nationality') }}</label>
                                        <input id="nationality" name="nationality" type="text" value="{{ old('nationality', $user->nationality) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('nationality') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="passport_number" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Passeport', 'Passport') }}</label>
                                        <input id="passport_number" name="passport_number" type="text" value="{{ old('passport_number', $user->passport_number) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('passport_number') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="address" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Adresse', 'Address') }}</label>
                                        <input id="address" name="address" type="text" value="{{ old('address', $user->address) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('address') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="city" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Ville', 'City') }}</label>
                                        <input id="city" name="city" type="text" value="{{ old('city', $user->city) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('city') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="country" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Pays', 'Country') }}</label>
                                        <input id="country" name="country" type="text" value="{{ old('country', $user->country) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('country') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="postal_code" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Code postal', 'Postal code') }}</label>
                                        <input id="postal_code" name="postal_code" type="text" value="{{ old('postal_code', $user->postal_code) }}" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('postal_code') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="preferred_language" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Langue', 'Language') }}</label>
                                        <select id="preferred_language" name="preferred_language" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                            <option value="fr" @selected(old('preferred_language', $user->preferred_language) === 'fr')>Français</option>
                                            <option value="en" @selected(old('preferred_language', $user->preferred_language) === 'en')>English</option>
                                        </select>
                                        @error('preferred_language') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                    <div>
                                        <label for="preferred_currency" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Devise', 'Currency') }}</label>
                                        <select id="preferred_currency" name="preferred_currency" class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                            @foreach(['XOF', 'EUR', 'USD', 'GBP'] as $currency)
                                                <option value="{{ $currency }}" @selected(old('preferred_currency', $user->preferred_currency) === $currency)>{{ $currency }}</option>
                                            @endforeach
                                        </select>
                                        @error('preferred_currency') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>
                                </div>

                                <button type="submit" class="cp-primary-button !flex !w-full sm:!w-auto">
                                    <i class="fa-solid fa-floppy-disk text-sm"></i>
                                    <span>{{ $t('Enregistrer les modifications', 'Save changes') }}</span>
                                </button>
                            </form>
                        </div>

                        <div class="space-y-6">
                            <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Sécurité', 'Security') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Changer le mot de passe', 'Change password') }}</h2>

                                <form method="POST" action="{{ route('password.update') }}" class="mt-5 space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <div>
                                        <label for="current_password" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Mot de passe actuel', 'Current password') }}</label>
                                        <input id="current_password" name="current_password" type="password" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('current_password') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="password" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Nouveau mot de passe', 'New password') }}</label>
                                        <input id="password" name="password" type="password" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                        @error('password') <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p> @enderror
                                    </div>

                                    <div>
                                        <label for="password_confirmation" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">{{ $t('Confirmation', 'Confirmation') }}</label>
                                        <input id="password_confirmation" name="password_confirmation" type="password" required class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm font-medium text-[color:var(--cp-plum-950)] outline-none transition focus:border-[color:var(--cp-border-strong)] focus:ring-2 focus:ring-[rgba(75,40,112,0.12)]">
                                    </div>

                                    <button type="submit" class="cp-secondary-button !flex !w-full !justify-center !rounded-[1.2rem] !py-3 text-sm">
                                        <i class="fa-solid fa-key text-sm"></i>
                                        <span>{{ $t('Mettre à jour le mot de passe', 'Update password') }}</span>
                                    </button>
                                </form>
                            </div>

                            <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-plum-800)]">{{ $t('Documents récents', 'Recent documents') }}</p>
                                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Factures et reçus', 'Invoices and receipts') }}</h2>
                                    </div>
                                    <a href="{{ route('bookings') }}" class="cp-secondary-button !px-4 !py-2.5 text-sm">
                                        <span>{{ $t('Tout voir', 'View all') }}</span>
                                    </a>
                                </div>

                                @if($recentPaidBookings->isEmpty())
                                    <div class="mt-5 rounded-[1.45rem] bg-[#faf6ff] px-5 py-6 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                        {{ $t('Aucun document récent pour le moment. Vos prochaines factures apparaîtront ici après paiement.', 'No recent document yet. Your next invoices will appear here after payment.') }}
                                    </div>
                                @else
                                    <div class="mt-5 space-y-3">
                                        @foreach($recentPaidBookings as $booking)
                                            <div class="rounded-[1.5rem] border border-[color:var(--cp-border)] bg-white px-5 py-4">
                                                <p class="font-black text-[color:var(--cp-plum-950)]">{{ $booking->title }}</p>
                                                <p class="mt-1 text-sm text-[color:var(--cp-ink-soft)]">{{ $booking->booking_number }} · {{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</p>
                                                <div class="mt-4 flex flex-col gap-2 sm:flex-row">
                                                    <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'invoice']) }}" class="cp-secondary-button !w-full sm:!w-auto">
                                                        <i class="fa-regular fa-file-lines text-sm"></i>
                                                        <span>{{ $t('Facture', 'Invoice') }}</span>
                                                    </a>
                                                    <a href="{{ route('user.booking.documents.download', ['booking' => $booking, 'documentType' => 'receipt']) }}" class="cp-secondary-button !w-full sm:!w-auto">
                                                        <i class="fa-regular fa-file-invoice text-sm"></i>
                                                        <span>{{ $t('Reçu', 'Receipt') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
