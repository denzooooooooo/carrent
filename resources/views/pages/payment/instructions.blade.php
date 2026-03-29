@extends('layouts.app')

@section('title', 'Virement bancaire - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
@php
    $bookingTypeLabel = match ($booking->booking_type) {
        'event' => 'événement',
        'package' => 'package',
        'location' => 'location',
        'flight' => 'vol',
        default => 'réservation',
    };
    $supportEmail = config('carre_premium.contact.support_email');
    $billingEmail = config('carre_premium.contact.billing_email');
    $mobilePhoneDisplay = config('carre_premium.contact.mobile_display');
    $mobilePhoneLink = config('carre_premium.contact.mobile_link');
    $whatsAppUrl = config('carre_premium.contact.whatsapp_url');
    $companyName = config('carre_premium.company.legal_name');
    $companyTaxId = config('carre_premium.company.tax_id');
    $bankReference = $booking->booking_number;
    $bankRib = 'CI23 1234 5678 9012 3456 7890 123';
    $bankSwift = 'BLAC CICIAXXX';
@endphp

<div class="cp-page">
    <section class="cp-page-hero">
        <div class="cp-shell">
            <div class="overflow-hidden rounded-[2.4rem] bg-gradient-to-br from-[#1d102a] via-[#4b2870] to-[#d9a441] px-5 py-8 text-white shadow-[0_28px_90px_rgba(34,18,52,0.24)] sm:px-7 sm:py-10">
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px] lg:items-end">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[11px] font-black uppercase tracking-[0.22em] text-[color:var(--cp-gold-300)] backdrop-blur">
                            <span class="h-2.5 w-2.5 rounded-full bg-current"></span>
                            Paiement assisté
                        </span>
                        <h1 class="mt-4 text-3xl font-black sm:text-4xl">Le virement peut être effectué dès maintenant avec la référence ci-dessous.</h1>
                        <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                            Retrouvez ici les coordonnées bancaires, le libellé exact à reprendre et l’envoi de preuve de paiement.
                        </p>
                    </div>

                    <div class="space-y-3">
                        <div class="rounded-[1.7rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/60">Référence</p>
                            <p class="mt-3 text-2xl font-black">{{ $booking->booking_number }}</p>
                            <p class="mt-2 text-sm text-white/78">À reporter exactement dans le libellé du virement.</p>
                        </div>
                        <div class="rounded-[1.7rem] border border-white/15 bg-white/10 p-5 backdrop-blur">
                            <p class="text-[11px] font-black uppercase tracking-[0.22em] text-white/60">Montant</p>
                            <p class="mt-3 text-2xl font-black">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
                            <p class="mt-2 text-sm text-white/78">Dossier {{ $bookingTypeLabel }} suivi par l’équipe Carré Premium.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="cp-page-section">
        <div class="cp-shell">
            @if(session('success'))
                <div class="mb-6 rounded-[1.6rem] border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-semibold text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-[1.6rem] border border-red-200 bg-red-50 px-5 py-4 text-sm font-semibold text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <div class="grid gap-6 xl:grid-cols-[minmax(0,1.15fr)_360px]">
                <div class="space-y-6">
                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_280px]">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">Référence obligatoire</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">Utilisez exactement cette référence pour le virement.</h2>
                                <div class="mt-5 rounded-[1.6rem] border border-amber-200 bg-amber-50 px-5 py-5">
                                    <p class="text-xs font-black uppercase tracking-[0.18em] text-amber-700">Libellé de virement</p>
                                    <code class="mt-3 block text-2xl font-black text-amber-900">{{ $bankReference }}</code>
                                    <p class="mt-3 text-sm leading-7 text-amber-800">Cette référence permet d’associer le règlement à votre réservation sans délai inutile.</p>
                                </div>
                            </div>
                            <div class="rounded-[1.6rem] border border-[color:var(--cp-border)] bg-[rgba(255,249,242,0.88)] p-5">
                                <p class="text-xs font-black uppercase tracking-[0.18em] text-[color:var(--cp-ink-muted)]">Traitement</p>
                                <div class="mt-4 space-y-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                    <p><strong class="text-[color:var(--cp-plum-950)]">1.</strong> Effectuez le virement avec la référence ci-contre.</p>
                                    <p><strong class="text-[color:var(--cp-plum-950)]">2.</strong> Envoyez la preuve de paiement depuis ce formulaire.</p>
                                    <p><strong class="text-[color:var(--cp-plum-950)]">3.</strong> L’équipe vérifie puis confirme le dossier et les documents.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-2">
                        <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                            <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">Coordonnées bancaires</p>
                            <div class="mt-5 space-y-4">
                                <div class="rounded-[1.3rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[color:var(--cp-ink-muted)]">Banque</p>
                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">Banque Atlantique CI</p>
                                </div>
                                <div class="rounded-[1.3rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[color:var(--cp-ink-muted)]">Titulaire</p>
                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ strtoupper($companyName) }}</p>
                                </div>
                                <div class="rounded-[1.3rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[color:var(--cp-ink-muted)]">RIB</p>
                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $bankRib }}</p>
                                </div>
                                <div class="rounded-[1.3rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[color:var(--cp-ink-muted)]">BIC / Swift</p>
                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $bankSwift }}</p>
                                </div>
                                <div class="rounded-[1.3rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4">
                                    <p class="text-xs font-black uppercase tracking-[0.16em] text-[color:var(--cp-ink-muted)]">N° fiscal</p>
                                    <p class="mt-2 text-sm font-bold text-[color:var(--cp-plum-950)]">{{ $companyTaxId }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">Preuve de paiement</p>
                                    <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">Déposez votre reçu directement ici.</h2>
                                </div>
                                <span class="inline-flex rounded-full px-3 py-1 text-xs font-black uppercase tracking-[0.18em] {{ $booking->has_payment_proof ? 'bg-emerald-100 text-emerald-800 border border-emerald-200' : 'bg-amber-100 text-amber-800 border border-amber-200' }}">
                                    {{ $booking->has_payment_proof ? 'Preuve reçue' : 'En attente' }}
                                </span>
                            </div>

                            <form action="{{ $proofUploadUrl }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-5">
                                @csrf
                                <div>
                                    <label for="payment_proof" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Fichier justificatif</label>
                                    <input id="payment_proof" type="file" name="payment_proof" accept=".pdf,.jpg,.jpeg,.png,.webp" class="block w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm text-[color:var(--cp-plum-950)]">
                                    @error('payment_proof')
                                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="payment_proof_notes" class="mb-2 block text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Note interne</label>
                                    <textarea id="payment_proof_notes" name="payment_proof_notes" rows="4" class="block w-full rounded-[1.25rem] border border-[color:var(--cp-border)] bg-white px-4 py-3.5 text-sm text-[color:var(--cp-plum-950)]" placeholder="Exemple : virement effectué depuis le compte société ce matin à 09h15.">{{ old('payment_proof_notes', $booking->payment_proof_notes) }}</textarea>
                                    @error('payment_proof_notes')
                                        <p class="mt-2 text-sm font-semibold text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" class="cp-primary-button !w-full sm:!w-auto">
                                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                    <span>Envoyer la preuve</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs font-black uppercase tracking-[0.22em] text-[color:var(--cp-ink-muted)]">Actions rapides</p>
                                <h2 class="mt-2 text-2xl font-black text-[color:var(--cp-plum-950)]">Finalisez le règlement puis transmettez la preuve.</h2>
                            </div>
                            <button type="button" onclick="copyTransferDetails(this)" class="cp-secondary-button !px-4 !py-3">
                                <i class="fa-solid fa-copy text-sm"></i>
                                <span>Copier RIB + référence</span>
                            </button>
                        </div>
                        <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                            <a href="{{ $backUrl }}" class="cp-secondary-button !w-full sm:!w-auto">
                                <i class="fa-solid fa-arrow-left text-sm"></i>
                                <span>Retour au dossier</span>
                            </a>
                            <a href="{{ $whatsAppUrl }}?text={{ rawurlencode('Preuve de paiement ' . $booking->booking_number . ' - ' . $booking->final_amount . ' ' . $booking->currency) }}" target="_blank" rel="noopener noreferrer" class="cp-primary-button !w-full sm:!w-auto !bg-[#0f766e] hover:!bg-[#0c665f]">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>Prévenir sur WhatsApp</span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="xl:sticky xl:top-28 space-y-6">
                        @include('pages.payment._booking-summary', ['booking' => $booking, 'heading' => 'Réservation liée'])

                        <div class="cp-panel rounded-[2rem] p-6 sm:p-7">
                            <p class="text-xs font-black uppercase tracking-[0.2em] text-[color:var(--cp-ink-muted)]">Aide immédiate</p>
                            <h2 class="mt-3 text-2xl font-black text-[color:var(--cp-plum-950)]">Un doute sur le virement ou la preuve ?</h2>
                            <p class="mt-3 text-sm leading-7 text-[color:var(--cp-ink-soft)]">Choisissez le canal le plus direct pour confirmer le virement ou poser une question sur la facturation.</p>

                            <div class="mt-5 space-y-3">
                                <a href="{{ $mobilePhoneLink }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                    <span class="flex items-center gap-3"><i class="fa-solid fa-phone text-[color:var(--cp-plum-800)]"></i> {{ $mobilePhoneDisplay }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </a>
                                <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                    <span class="flex items-center gap-3"><i class="fa-brands fa-whatsapp text-[color:var(--cp-success)]"></i> {{ config('carre_premium.contact.whatsapp_display') }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </a>
                                <a href="mailto:{{ $supportEmail }}" class="flex items-center justify-between gap-3 rounded-[1.35rem] border border-[color:var(--cp-border)] bg-white/85 px-4 py-4 text-sm font-semibold text-[color:var(--cp-plum-950)] transition hover:-translate-y-0.5 hover:border-[color:var(--cp-border-strong)]">
                                    <span class="flex items-center gap-3"><i class="fa-solid fa-envelope text-[color:var(--cp-plum-800)]"></i> {{ $supportEmail }}</span>
                                    <i class="fa-solid fa-arrow-up-right-from-square text-xs text-[color:var(--cp-ink-muted)]"></i>
                                </a>
                            </div>

                            <div class="mt-6 rounded-[1.5rem] border border-[rgba(75,40,112,0.12)] bg-[rgba(255,249,242,0.9)] px-4 py-4 text-sm leading-7 text-[color:var(--cp-ink-soft)]">
                                Documents envoyés après validation du paiement et contrôle du dossier. Contact facturation: <a href="mailto:{{ $billingEmail }}" class="font-bold text-[color:var(--cp-plum-800)]">{{ $billingEmail }}</a>.
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
function copyTransferDetails(button) {
    const text = `Réservation: {{ $booking->booking_number }}\nMontant: {{ $booking->final_amount }} {{ $booking->currency }}\nRIB: {{ $bankRib }}\nSwift: {{ $bankSwift }}\nEmail: {{ $billingEmail }}`;

    navigator.clipboard.writeText(text).then(() => {
        const original = button.innerHTML;
        button.innerHTML = '<i class="fa-solid fa-check text-sm"></i><span>Copié</span>';
        setTimeout(() => {
            button.innerHTML = original;
        }, 1800);
    });
}
</script>
@endpush
