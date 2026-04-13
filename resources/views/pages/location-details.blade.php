@extends('layouts.app')

@section('title', (($location->name ?? __('Vehicle')) . ' - Carré Premium'))

@section('content')
@php
    $t = fn (string $fr, string $en) => app()->getLocale() === 'fr' ? $fr : $en;
    $locationName = $location->name;
    $locationDescription = $location->description;
    $locationImage = $location->image_url ?: 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=1200&h=800&fit=crop';
    $features = collect($location->features ?? [])->filter()->values();
    $categoryLabel = $location->category ? ucfirst((string) $location->category) : $t('Sur demande', 'On request');
    $typeLabel = $location->type ? ucfirst((string) $location->type) : $t('Sur demande', 'On request');
    $pricePerDay = (float) ($location->price_per_day ?? 0);
@endphp

<div class="min-h-screen pb-24 sm:pb-28">
    <section class="pt-4 sm:pt-6">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.25rem] bg-gradient-to-br from-[#1d2239] via-[#234c7b] to-[#d49a46] text-white shadow-[0_30px_90px_rgba(20,34,59,0.24)]">
                <div class="grid gap-6 px-5 py-8 sm:px-8 sm:py-10 lg:grid-cols-[minmax(0,1.12fr)_minmax(320px,420px)] lg:px-10 lg:py-12">
                    <div class="max-w-3xl">
                        <div class="cp-kicker !text-[color:var(--cp-gold-300)]">
                            <span class="cp-eyebrow-dot !bg-[color:var(--cp-gold-300)]"></span>
                            <span>{{ $t('Location detail', 'Rental detail') }}</span>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2">
                            <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-white/90">
                                {{ $categoryLabel }}
                            </span>
                            <span class="rounded-full border border-white/20 bg-white/10 px-3 py-1 text-[11px] font-bold uppercase tracking-[0.18em] text-white/90">
                                {{ $typeLabel }}
                            </span>
                            <span class="rounded-full bg-[color:var(--cp-gold-400)] px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] text-[#17304a]">
                                {{ $location->capacity }} {{ $t('passagers', 'passengers') }}
                            </span>
                        </div>

                        <h1 class="mt-4 text-3xl font-black leading-tight sm:text-4xl lg:text-5xl">
                            {{ $locationName }}
                        </h1>

                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/85 sm:text-base">
                            {{ \Illuminate\Support\Str::limit($locationDescription, 260) }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-3">
                            <div class="rounded-[1.1rem] border border-white/15 bg-white/10 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Tarif indicatif', 'Indicative fare') }}</p>
                                <p class="mt-1 text-sm font-bold text-white">{{ \App\Helpers\CurrencyHelper::format($pricePerDay) }} / {{ $t('jour', 'day') }}</p>
                            </div>
                            <div class="rounded-[1.1rem] border border-white/15 bg-white/10 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Capacite', 'Capacity') }}</p>
                                <p class="mt-1 text-sm font-bold text-white">{{ $location->capacity }} {{ $t('passagers', 'passengers') }}</p>
                            </div>
                            <div class="rounded-[1.1rem] border border-white/15 bg-white/10 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Reservation', 'Booking') }}</p>
                                <p class="mt-1 text-sm font-bold text-white">{{ $t('Paiement securise apres recapitulatif', 'Secure payment after summary') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                            <a href="#booking-form" class="cp-primary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-calendar-check text-sm"></i>
                                <span>{{ $t('Reserver ce vehicule', 'Book this vehicle') }}</span>
                            </a>
                            <a href="{{ route('contact') }}" class="cp-secondary-button !w-full sm:!w-auto !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                                <i class="fa-solid fa-headset text-sm"></i>
                                <span>{{ $t("Parler a l'equipe", 'Talk to the team') }}</span>
                            </a>
                        </div>
                    </div>

                    <div class="rounded-[1.9rem] border border-white/15 bg-white/10 p-4 backdrop-blur sm:p-5">
                        <div class="overflow-hidden rounded-[1.5rem]">
                            <img src="{{ $locationImage }}" alt="{{ $locationName }}" class="h-64 w-full object-cover sm:h-72">
                        </div>

                        <div class="mt-4 grid grid-cols-2 gap-3">
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Categorie', 'Category') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $categoryLabel }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Type', 'Type') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $typeLabel }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Capacite', 'Capacity') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $location->capacity }} {{ $t('places', 'seats') }}</p>
                            </div>
                            <div class="rounded-[1.2rem] bg-white/12 px-4 py-3">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">{{ $t('Support', 'Support') }}</p>
                                <p class="mt-2 text-sm font-bold text-white">{{ $t('Equipe humaine disponible', 'Human team available') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="-mt-6 pt-0">
        <div class="cp-shell">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1.25fr)_minmax(320px,420px)]">
                <div class="space-y-6">
                    <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <div class="flex flex-col gap-4 border-b border-[color:var(--cp-border)] pb-5 sm:flex-row sm:items-end sm:justify-between">
                            <div class="max-w-3xl">
                                <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Lecture rapide', 'Quick reading') }}</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Ce que le client doit savoir avant de reserver', 'What the client should know before booking') }}</h2>
                            </div>
                            <div class="cp-pill">
                                <i class="fa-solid fa-wallet text-xs"></i>
                                <span>{{ \App\Helpers\CurrencyHelper::format($pricePerDay) }} / {{ $t('jour', 'day') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-[1.35rem] bg-[#eef7ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#5b7393]">{{ $t('Categorie', 'Category') }}</p>
                                <p class="mt-2 text-sm font-bold text-[#10233e]">{{ $categoryLabel }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#eef7ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#5b7393]">{{ $t('Type', 'Type') }}</p>
                                <p class="mt-2 text-sm font-bold text-[#10233e]">{{ $typeLabel }}</p>
                            </div>
                            <div class="rounded-[1.35rem] bg-[#eef7ff] px-4 py-4">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[#5b7393]">{{ $t('Capacite', 'Capacity') }}</p>
                                <p class="mt-2 text-sm font-bold text-[#10233e]">{{ $location->capacity }} {{ $t('passagers', 'passengers') }}</p>
                            </div>
                        </div>

                        <div class="mt-6 prose max-w-none text-[color:var(--cp-ink-soft)]">
                            {!! nl2br(e($locationDescription)) !!}
                        </div>
                    </section>

                    @if($features->isNotEmpty())
                        <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Equipements', 'Features') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Ce vehicule inclut', 'What this vehicle includes') }}</h2>

                            <div class="mt-6 grid gap-3 sm:grid-cols-2">
                                @foreach($features as $feature)
                                    <div class="flex items-start gap-3 rounded-[1.35rem] bg-[#f3f8fc] px-4 py-4">
                                        <span class="mt-1 inline-flex h-5 w-5 items-center justify-center rounded-full bg-[#d7ebfb] text-[11px] font-black text-[#1e507b]">+</span>
                                        <span class="text-sm leading-6 text-[#274765]">{{ $feature }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endif

                    <section class="cp-panel rounded-[2rem] p-5 sm:p-6 md:p-8">
                        <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Processus', 'Process') }}</p>
                        <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)] sm:text-3xl">{{ $t('Reservation plus claire', 'Clearer booking flow') }}</h2>

                        <div class="mt-6 grid gap-4 md:grid-cols-3">
                            <div class="rounded-[1.4rem] bg-[#f8fafc] px-4 py-4">
                                <p class="text-sm font-black text-[color:var(--cp-plum-950)]">1. {{ $t('Choisir les dates', 'Choose the dates') }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Le client saisit simplement le debut et la fin de location.', 'The client simply enters rental start and end dates.') }}</p>
                            </div>
                            <div class="rounded-[1.4rem] bg-[#f8fafc] px-4 py-4">
                                <p class="text-sm font-black text-[color:var(--cp-plum-950)]">2. {{ $t('Voir le montant estime', 'See the estimated amount') }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('Le recapitulatif calcule les jours et le total sans ambiguite.', 'The summary calculates days and total without ambiguity.') }}</p>
                            </div>
                            <div class="rounded-[1.4rem] bg-[#f8fafc] px-4 py-4">
                                <p class="text-sm font-black text-[color:var(--cp-plum-950)]">3. {{ $t('Payer puis confirmer', 'Pay then confirm') }}</p>
                                <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">{{ $t('La reservation passe ensuite vers le paiement securise et la confirmation.', 'The booking then proceeds to secure payment and confirmation.') }}</p>
                            </div>
                        </div>
                    </section>
                </div>

                <aside id="booking-form" class="lg:sticky lg:top-6 lg:self-start">
                    <div class="cp-panel rounded-[2rem] p-5 sm:p-6">
                        <div class="border-b border-[color:var(--cp-border)] pb-5">
                            <p class="text-xs font-black uppercase tracking-[0.24em] text-[color:var(--cp-plum-800)]">{{ $t('Reservation', 'Booking') }}</p>
                            <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">{{ $t('Demande de location', 'Rental request') }}</h2>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('On garde le tunnel simple: coordonnees, dates, recapitulatif, puis paiement.', 'The funnel stays simple: contact details, dates, summary, then payment.') }}
                            </p>
                        </div>

                        @if($errors->any())
                            <div class="mt-5 rounded-[1.4rem] border border-red-200 bg-red-50 px-4 py-4 text-sm text-red-700">
                                <p class="font-bold">{{ $t('Le formulaire contient des erreurs.', 'The form contains errors.') }}</p>
                                <ul class="mt-2 space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('location.book', $location) }}" method="POST" class="mt-5 space-y-5">
                            @csrf

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="first_name" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Prenoms', 'First name') }} *</label>
                                    <input
                                        type="text"
                                        id="first_name"
                                        name="first_name"
                                        required
                                        value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    >
                                </div>
                                <div>
                                    <label for="last_name" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Nom', 'Last name') }} *</label>
                                    <input
                                        type="text"
                                        id="last_name"
                                        name="last_name"
                                        required
                                        value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    >
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="email" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">Email *</label>
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        required
                                        value="{{ old('email', auth()->user()->email ?? '') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    >
                                </div>
                                <div>
                                    <label for="phone" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Telephone', 'Phone') }} *</label>
                                    <input
                                        type="tel"
                                        id="phone"
                                        name="phone"
                                        required
                                        value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                        placeholder="{{ config('carre_premium.contact.mobile_display') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    >
                                </div>
                            </div>

                            <div class="grid gap-4 sm:grid-cols-2">
                                <div>
                                    <label for="start_date" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Date de debut', 'Start date') }} *</label>
                                    <input
                                        type="date"
                                        id="start_date"
                                        name="start_date"
                                        required
                                        min="{{ now()->addDay()->format('Y-m-d') }}"
                                        value="{{ old('start_date') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    >
                                </div>
                                <div>
                                    <label for="end_date" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Date de fin', 'End date') }} *</label>
                                    <input
                                        type="date"
                                        id="end_date"
                                        name="end_date"
                                        required
                                        min="{{ now()->addDays(2)->format('Y-m-d') }}"
                                        value="{{ old('end_date') }}"
                                        class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    >
                                </div>
                            </div>

                            <div>
                                <label for="special_requests" class="mb-2 block text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $t('Demandes speciales', 'Special requests') }}</label>
                                <textarea
                                    id="special_requests"
                                    name="special_requests"
                                    rows="4"
                                    class="w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3 text-sm text-[color:var(--cp-plum-950)] outline-none transition focus:border-[#1e507b]"
                                    placeholder="{{ $t('Chauffeur, point de prise en charge, attente, accessibilite, etc.', 'Driver, pick-up point, waiting time, accessibility, etc.') }}"
                                >{{ old('special_requests') }}</textarea>
                            </div>

                            <div class="rounded-[1.5rem] bg-[#eef7ff] px-4 py-4 sm:px-5">
                                <div class="flex items-center justify-between gap-4">
                                    <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Prix par jour', 'Price per day') }}</span>
                                    <span class="text-base font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($pricePerDay) }}</span>
                                </div>
                                <div class="mt-3 flex items-center justify-between gap-4">
                                    <span class="text-sm text-[color:var(--cp-ink-soft)]">{{ $t('Nombre de jours', 'Number of days') }}</span>
                                    <span id="days-count" class="text-base font-black text-[color:var(--cp-plum-950)]">0</span>
                                </div>
                                <div class="mt-4 border-t border-[color:var(--cp-border)] pt-4">
                                    <div class="flex items-center justify-between gap-4 text-lg font-black">
                                        <span class="text-[color:var(--cp-plum-950)]">{{ $t('Total estime', 'Estimated total') }}</span>
                                        <span id="total-price" class="text-[#1e507b]">0 XAF</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="cp-primary-button !w-full !justify-center !bg-[#1e507b] hover:!bg-[#194668]">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span>{{ $t('Continuer vers le paiement', 'Continue to payment') }}</span>
                            </button>
                        </form>

                        <div class="mt-5 border-t border-[color:var(--cp-border)] pt-5">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">{{ $t('Support', 'Support') }}</p>
                            <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                {{ $t('Si le client hesite sur les dates ou le vehicule exact, l equipe peut clarifier avant validation.', 'If the client is unsure about dates or the exact vehicle, the team can clarify before validation.') }}
                            </p>
                            <div class="mt-4 flex flex-col gap-3">
                                <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button !justify-center">
                                    <i class="fa-solid fa-phone text-sm"></i>
                                    <span>{{ config('carre_premium.contact.mobile_display') }}</span>
                                </a>
                                <a href="mailto:{{ config('carre_premium.contact.support_email') }}" class="cp-secondary-button !justify-center">
                                    <i class="fa-regular fa-envelope text-sm"></i>
                                    <span>{{ config('carre_premium.contact.support_email') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <section class="pt-10 sm:pt-12">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.1rem] bg-gradient-to-r from-[#19304a] via-[#226695] to-[#d49a46] px-5 py-8 text-white shadow-[0_24px_70px_rgba(24,37,67,0.18)] sm:px-8">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-3xl">
                        <p class="text-xs font-black uppercase tracking-[0.22em] text-white/60">{{ $t('Besoin specifique', 'Specific need') }}</p>
                        <h2 class="mt-3 text-2xl font-black sm:text-3xl">{{ $t('Besoin d un autre modele, d un chauffeur ou d une solution sur mesure ?', 'Need another model, a driver or a bespoke solution?') }}</h2>
                        <p class="mt-3 text-sm leading-7 text-white/80 sm:text-base">
                            {{ $t('La page aide a comprendre l offre, mais l equipe peut encore ajuster le vehicule, le timing et le mode d accompagnement.', 'The page helps users understand the offer, but the team can still adjust the vehicle, timing and support mode.') }}
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('contact') }}" class="cp-primary-button !bg-[#f0bb61] !text-[#17304a] hover:!bg-[#e2aa54]">
                            <i class="fa-regular fa-envelope text-sm"></i>
                            <span>{{ $t('Demander un devis', 'Request a quote') }}</span>
                        </a>
                        <a href="{{ config('carre_premium.contact.mobile_link') }}" class="cp-secondary-button !border-white/25 !bg-white/10 !text-white hover:!bg-white/15">
                            <i class="fa-solid fa-phone text-sm"></i>
                            <span>{{ $t('Appeler maintenant', 'Call now') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="fixed inset-x-0 bottom-0 z-30 border-t border-[color:var(--cp-border)] bg-white/95 px-4 py-3 shadow-[0_-16px_40px_rgba(24,37,67,0.12)] backdrop-blur lg:hidden">
        <div class="mx-auto flex max-w-3xl items-center justify-between gap-4">
            <div>
                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">{{ $t('A partir de', 'Starting at') }}</p>
                <p class="mt-1 text-lg font-black text-[color:var(--cp-plum-950)]">{{ \App\Helpers\CurrencyHelper::format($pricePerDay) }}</p>
            </div>
            <a href="#booking-form" class="cp-primary-button !w-auto !px-5 !bg-[#1e507b] hover:!bg-[#194668]">
                <span>{{ $t('Reserver', 'Book now') }}</span>
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const daysCountSpan = document.getElementById('days-count');
    const totalPriceSpan = document.getElementById('total-price');

    if (!startDateInput || !endDateInput || !daysCountSpan || !totalPriceSpan) {
        return;
    }

    const pricePerDay = {{ json_encode($pricePerDay) }};

    function formatCurrency(amount) {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'XAF',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(amount);
    }

    function updateMinEndDate() {
        if (!startDateInput.value) {
            return;
        }

        const startDate = new Date(startDateInput.value);
        startDate.setDate(startDate.getDate() + 1);
        endDateInput.min = startDate.toISOString().split('T')[0];

        if (endDateInput.value && endDateInput.value < endDateInput.min) {
            endDateInput.value = endDateInput.min;
        }
    }

    function updatePrice() {
        updateMinEndDate();

        if (!startDateInput.value || !endDateInput.value) {
            daysCountSpan.textContent = '0';
            totalPriceSpan.textContent = '0 XAF';
            return;
        }

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime()) || endDate < startDate) {
            daysCountSpan.textContent = '0';
            totalPriceSpan.textContent = '0 XAF';
            return;
        }

        const diffTime = Math.abs(endDate - startDate);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        const total = diffDays * pricePerDay;

        daysCountSpan.textContent = diffDays;
        totalPriceSpan.textContent = formatCurrency(total);
    }

    startDateInput.addEventListener('change', updatePrice);
    endDateInput.addEventListener('change', updatePrice);
    updatePrice();
});
</script>
@endpush
@endsection
