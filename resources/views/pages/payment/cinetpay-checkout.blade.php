@extends('layouts.app')

@section('title', 'CinetPay - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
@php
    $supportPhone = config('carre_premium.contact.mobile_display');
    $supportPhoneLink = config('carre_premium.contact.mobile_link');
    $supportEmail = config('carre_premium.contact.support_email');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-gradient-to-br from-[#1d102a] via-[#4b2870] to-[#d9a441] px-5 py-8 text-white shadow-[0_28px_90px_rgba(34,18,52,0.24)] sm:px-7 sm:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_300px] lg:items-end">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-[color:var(--cp-gold-300)] backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-current"></span>
                            CinetPay
                        </span>
                        <h1 class="mt-4 text-3xl font-black sm:text-4xl">Finalisez votre règlement sur le canal sécurisé CinetPay.</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                            Vous pouvez régler ce montant immédiatement via Mobile Money ou laisser CinetPay afficher l’ensemble des moyens compatibles.
                        </p>
                    </div>

                    <div class="rounded-[1.8rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                        <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/60">Montant</p>
                        <p class="mt-3 text-3xl font-black">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
                        <p class="mt-2 text-sm text-white/78">Référence {{ $booking->booking_number }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.05fr)_360px]">
                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <div class="flex flex-wrap items-center justify-between gap-3">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">Canal express</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">Choisissez votre mode de règlement, puis laissez CinetPay prendre le relais.</h2>
                            </div>
                            <a href="{{ route('payment.checkout', $booking) }}" class="cp-secondary-button !px-4 !py-3">
                                <i class="fa-solid fa-arrow-left text-sm"></i>
                                <span>Voir toutes les options</span>
                            </a>
                        </div>

                        <form id="payment-form" action="{{ $paymentProcessUrl ?? route('payment.cinetpay.process', $booking) }}" method="POST" class="mt-6 space-y-4">
                            @csrf
                            <label class="block rounded-[1.6rem] border border-[color:var(--cp-border)] bg-white/90 p-5 transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                <input type="radio" name="payment_channel" value="MOBILE_MONEY" class="sr-only peer" checked>
                                <span class="flex items-center justify-between gap-4">
                                    <span class="flex items-center gap-4">
                                        <span class="flex h-13 w-13 items-center justify-center rounded-[1.2rem] bg-gradient-to-br from-orange-500 to-amber-500 text-white shadow-lg">📱</span>
                                        <span>
                                            <span class="block text-base font-black text-[color:var(--cp-plum-950)]">Mobile Money</span>
                                            <span class="mt-1 block text-sm leading-6 text-[color:var(--cp-ink-soft)]">Orange Money, MTN, Moov et autres portefeuilles mobiles.</span>
                                        </span>
                                    </span>
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border border-[color:var(--cp-border)] bg-white text-transparent peer-checked:bg-[color:var(--cp-plum-900)] peer-checked:text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                </span>
                            </label>

                            <label class="block rounded-[1.6rem] border border-[color:var(--cp-border)] bg-white/90 p-5 transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                <input type="radio" name="payment_channel" value="ALL" class="sr-only peer">
                                <span class="flex items-center justify-between gap-4">
                                    <span class="flex items-center gap-4">
                                        <span class="flex h-13 w-13 items-center justify-center rounded-[1.2rem] bg-gradient-to-br from-[#4b2870] to-[#d9a441] text-white shadow-lg">💳</span>
                                        <span>
                                            <span class="block text-base font-black text-[color:var(--cp-plum-950)]">Tous les moyens</span>
                                            <span class="mt-1 block text-sm leading-6 text-[color:var(--cp-ink-soft)]">Laisser CinetPay afficher la liste complète des moyens compatibles.</span>
                                        </span>
                                    </span>
                                    <span class="flex h-6 w-6 items-center justify-center rounded-full border border-[color:var(--cp-border)] bg-white text-transparent peer-checked:bg-[color:var(--cp-plum-900)] peer-checked:text-white">
                                        <i class="fa-solid fa-check text-[11px]"></i>
                                    </span>
                                </span>
                            </label>

                            <button type="submit" id="pay-button" class="cp-primary-button !mt-2 !w-full !justify-center">
                                <i class="fa-solid fa-lock text-sm"></i>
                                <span id="button-text">Continuer vers CinetPay</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="space-y-6 xl:sticky xl:top-28">
                    @include('pages.payment._booking-summary', ['booking' => $booking, 'heading' => 'Dossier lié'])

                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Support</p>
                        <div class="mt-5 space-y-3">
                            <a href="{{ $supportPhoneLink }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)]">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-phone text-[color:var(--cp-plum-800)]"></i> {{ $supportPhone }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </a>
                            <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)]">
                                <span class="flex items-center gap-3"><i class="fa-brands fa-whatsapp text-[color:var(--cp-success)]"></i> WhatsApp</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </a>
                            <a href="mailto:{{ $supportEmail }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)]">
                                <span class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[color:var(--cp-plum-800)]"></i> {{ $supportEmail }}</span>
                                <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                            </a>
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
document.getElementById('payment-form')?.addEventListener('submit', function () {
    const button = document.getElementById('pay-button');
    const buttonText = document.getElementById('button-text');
    button.disabled = true;
    button.classList.add('opacity-80');
    buttonText.textContent = 'Redirection sécurisée...';
});
</script>
@endpush
