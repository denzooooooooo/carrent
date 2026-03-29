@extends('layouts.app')

@section('title', 'Paiement sécurisé - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
@php
    $supportPhone = config('carre_premium.contact.mobile_display');
    $supportPhoneLink = config('carre_premium.contact.mobile_link');
    $supportEmail = config('carre_premium.contact.support_email');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');

    $channelThemes = [
        'ORANGE_MONEY' => ['accent' => 'from-orange-500 to-amber-500', 'surface' => 'bg-orange-50 border-orange-200 text-orange-800', 'badge' => 'Orange Money'],
        'MTN_MONEY' => ['accent' => 'from-yellow-400 to-amber-500', 'surface' => 'bg-amber-50 border-amber-200 text-amber-800', 'badge' => 'MTN Money'],
        'MOOV_MONEY' => ['accent' => 'from-sky-500 to-blue-600', 'surface' => 'bg-sky-50 border-sky-200 text-sky-800', 'badge' => 'Moov Money'],
        'WAVE' => ['accent' => 'from-cyan-500 to-indigo-600', 'surface' => 'bg-cyan-50 border-cyan-200 text-cyan-800', 'badge' => 'Wave'],
        'CREDIT_CARD' => ['accent' => 'from-[#4b2870] to-[#d9a441]', 'surface' => 'bg-violet-50 border-violet-200 text-violet-800', 'badge' => 'Carte bancaire'],
        'ALL' => ['accent' => 'from-slate-600 to-slate-800', 'surface' => 'bg-slate-50 border-slate-200 text-slate-800', 'badge' => 'Toutes les options'],
    ];
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-gradient-to-br from-[#1d102a] via-[#4b2870] to-[#d9a441] px-5 py-8 text-white shadow-[0_28px_90px_rgba(34,18,52,0.24)] sm:px-7 sm:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-end">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-[color:var(--cp-gold-300)] backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-current"></span>
                            Étape paiement
                        </span>
                        <h1 class="mt-4 text-3xl font-black sm:text-4xl">Choisissez votre moyen de paiement et confirmez votre réservation.</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                            Votre réservation est prête. Sélectionnez simplement le canal qui vous convient pour régler le montant indiqué.
                        </p>

                        <div class="mt-6 grid gap-3 sm:grid-cols-3">
                            <div class="rounded-[1.4rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">1. Dossier</p>
                                <p class="mt-2 text-sm font-bold">Référence {{ $booking->booking_number }}</p>
                            </div>
                            <div class="rounded-[1.4rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">2. Canal</p>
                                <p class="mt-2 text-sm font-bold">Mobile Money, Wave ou carte</p>
                            </div>
                            <div class="rounded-[1.4rem] border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                                <p class="text-[11px] font-black uppercase tracking-[0.18em] text-white/60">3. Validation</p>
                                <p class="mt-2 text-sm font-bold">Confirmation puis documents</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/60">Montant à régler</p>
                        <p class="mt-3 text-3xl font-black">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
                        <p class="mt-2 text-sm text-white/78">Paiement sécurisé opéré par CinetPay et validé dans votre dossier Carré Premium.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            @if(session('error'))
                <div class="mb-6 rounded-[1.6rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-6 rounded-[1.6rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_360px]">
                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">Moyens disponibles</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">Choisissez le moyen de règlement disponible maintenant.</h2>
                            </div>
                            <a href="javascript:history.back()" class="cp-secondary-button !px-4 !py-3">
                                <i class="fa-solid fa-arrow-left text-sm"></i>
                                <span>Retour</span>
                            </a>
                        </div>

                        <form id="paymentForm" action="{{ $paymentProcessUrl ?? route('payment.cinetpay.process', $booking) }}" method="POST" class="mt-6 space-y-5">
                            @csrf

                            <div class="grid gap-4 md:grid-cols-2">
                                @foreach($channels as $channelKey => $channel)
                                    @php
                                        $theme = $channelThemes[$channelKey] ?? $channelThemes['ALL'];
                                    @endphp
                                    <label class="group cursor-pointer">
                                        <input type="radio" name="payment_channel" value="{{ $channelKey }}" class="peer sr-only">
                                        <span class="block rounded-[1.8rem] border border-[color:var(--cp-border)] bg-white/88 p-5 shadow-[0_16px_35px_rgba(31,17,44,0.06)] transition duration-200 hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)] peer-checked:border-[rgba(75,40,112,0.34)] peer-checked:bg-[rgba(244,237,255,0.88)] peer-checked:shadow-[0_22px_45px_rgba(75,40,112,0.14)]">
                                            <span class="flex items-start justify-between gap-4">
                                                <span class="flex items-center gap-4">
                                                    <span class="flex h-13 w-13 items-center justify-center rounded-[1.2rem] bg-gradient-to-br {{ $theme['accent'] }} text-lg font-black text-white shadow-lg">
                                                        {{ $channel['icon'] }}
                                                    </span>
                                                    <span>
                                                        <span class="block text-base font-black text-[color:var(--cp-plum-950)]">{{ $channel['label'] }}</span>
                                                        <span class="mt-1 block text-sm leading-6 text-[color:var(--cp-ink-soft)]">{{ $channel['description'] }}</span>
                                                    </span>
                                                </span>
                                                <span class="mt-1 flex h-6 w-6 items-center justify-center rounded-full border border-[color:var(--cp-border)] bg-white text-transparent transition peer-checked:border-[rgba(75,40,112,0.34)] peer-checked:bg-[color:var(--cp-plum-900)] peer-checked:text-white">
                                                    <i class="fa-solid fa-check text-[11px]"></i>
                                                </span>
                                            </span>
                                            <span class="mt-4 inline-flex rounded-full border px-3 py-1 text-[11px] font-black uppercase tracking-[0.18em] {{ $theme['surface'] }}">
                                                {{ $theme['badge'] }}
                                            </span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>

                            <div class="rounded-[1.6rem] border border-[rgba(75,40,112,0.12)] bg-[rgba(255,249,242,0.8)] px-5 py-5">
                                <div class="grid gap-4 md:grid-cols-3">
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Validation</p>
                                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">Le bouton devient actif dès qu’un canal est choisi.</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Sécurité</p>
                                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">Le règlement se fait sur une page sécurisée, sans stockage bancaire côté Carré Premium.</p>
                                    </div>
                                    <div>
                                        <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Support</p>
                                        <p class="mt-2 text-sm leading-7 text-[color:var(--cp-ink-soft)]">Si un canal ne passe pas, l’équipe peut vous proposer une autre solution rapidement.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3 border-t border-[color:var(--cp-border)] pt-5 sm:flex-row sm:items-center sm:justify-between">
                                <p id="selectionLabel" class="text-sm font-semibold text-[color:var(--cp-ink-soft)]">Choisissez un moyen de paiement pour continuer.</p>
                                <button type="submit" id="submitBtn" class="cp-primary-button !w-full sm:!w-auto !justify-center opacity-60" disabled>
                                    <i class="fa-solid fa-lock text-sm"></i>
                                    <span id="submitText">Confirmer et payer</span>
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="grid gap-4 md:grid-cols-3">
                        <div class="cp-panel rounded-[1.7rem] p-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Paiement sécurisé</p>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">Transaction chiffrée et contrôlée avant validation du dossier.</p>
                        </div>
                        <div class="cp-panel rounded-[1.7rem] p-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Pas de perte de dossier</p>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">La référence de paiement reste attachée à votre réservation jusqu’à la confirmation finale.</p>
                        </div>
                        <div class="cp-panel rounded-[1.7rem] p-5">
                            <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Assistance directe</p>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">Téléphone, WhatsApp et email restent disponibles pendant tout le paiement.</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="xl:sticky xl:top-28 space-y-6">
                        @include('pages.payment._booking-summary', ['booking' => $booking, 'heading' => 'Réservation liée'])

                        <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Assistance paiement</p>
                            <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">Un doute pendant le règlement ?</h2>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">L’équipe peut vous orienter vers le canal le plus adapté ou reprendre le dossier si nécessaire.</p>

                            <div class="mt-5 space-y-3">
                                <a href="{{ $supportPhoneLink }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                    <span class="flex items-center gap-3"><i class="fa-solid fa-phone text-[color:var(--cp-plum-800)]"></i> {{ $supportPhone }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </a>
                                <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                    <span class="flex items-center gap-3"><i class="fa-brands fa-whatsapp text-[color:var(--cp-success)]"></i> WhatsApp</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </a>
                                <a href="mailto:{{ $supportEmail }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                    <span class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[color:var(--cp-plum-800)]"></i> {{ $supportEmail }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('paymentForm');
    const radios = Array.from(document.querySelectorAll('input[name="payment_channel"]'));
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const selectionLabel = document.getElementById('selectionLabel');

    const labels = {
        ORANGE_MONEY: 'Orange Money',
        MTN_MONEY: 'MTN Money',
        MOOV_MONEY: 'Moov Money',
        WAVE: 'Wave',
        CREDIT_CARD: 'Carte bancaire',
        ALL: 'Toutes les options',
    };

    function updateButton() {
        const selected = radios.find((radio) => radio.checked);
        if (!selected) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-60');
            submitText.textContent = 'Confirmer et payer';
            selectionLabel.textContent = 'Choisissez un moyen de paiement pour continuer.';
            return;
        }

        const label = labels[selected.value] || selected.value;
        submitBtn.disabled = false;
        submitBtn.classList.remove('opacity-60');
        submitText.textContent = `Payer avec ${label}`;
        selectionLabel.textContent = `${label} sélectionné. Vous allez être redirigé vers le paiement sécurisé.`;
    }

    radios.forEach((radio) => {
        radio.addEventListener('change', updateButton);
    });

    form.addEventListener('submit', function () {
        submitBtn.disabled = true;
        submitBtn.classList.add('opacity-80');
        submitText.textContent = 'Redirection sécurisée...';
    });
});
</script>
@endpush
