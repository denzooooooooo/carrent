@extends('layouts.app')

@section('title', 'Instructions Paiement - Carré Premium')

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
@endphp
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 py-12 px-4">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="text-center mb-12">
            <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-2xl">
                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl font-black bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent mb-4">
                Paiement VIP
            </h1>
            <p class="text-xl text-gray-600 mb-8 max-w-md mx-auto leading-relaxed">
                Réservation <strong class="text-blue-600 font-bold">{{ $booking->booking_number }}</strong><br>
                Montant: <strong class="text-2xl text-blue-600">{{ number_format($booking->final_amount, 0, ' ', ' ') }} {{ $booking->currency }}</strong>
            </p>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-2xl p-6 mb-8 shadow-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-8 w-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-lg font-semibold text-green-800">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 mb-8 shadow-lg">
                <p class="text-lg font-semibold text-red-800">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Payment Instructions Card -->
        <div class="bg-white rounded-3xl shadow-2xl border border-gray-100 overflow-hidden mb-12">
            <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-8 text-white">
                <h2 class="text-2xl md:text-3xl font-bold mb-2">Virement bancaire sécurisé</h2>
                <p class="opacity-90">Pour votre {{ $bookingTypeLabel }} {{ $booking->final_amount > 2000000 ? 'Premium' : 'Standard' }}</p>
            </div>
            <div class="p-8 md:p-12 space-y-8">
                <!-- IMPORTANT: Reference Obligatoire -->
                <div class="bg-amber-50 border-2 border-amber-200 rounded-2xl p-6 md:p-8">
                    <h3 class="text-xl font-bold text-amber-800 mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                        </svg>
                        OBLIGATOIRE: Référence exacte
                    </h3>
                    <div class="bg-white rounded-xl p-6 border-2 border-amber-100">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Référence paiement</label>
                                <code class="bg-amber-100 text-amber-800 font-mono text-xl px-4 py-3 rounded-lg font-bold block w-full">
                                    {{ $booking->booking_number }}
                                </code>
                            </div>
                            <div class="text-center">
                                <div class="w-20 h-20 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-2">
                                    <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-xs text-gray-600 uppercase font-semibold tracking-wide">À copier</p>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 mt-4">
                            <strong>IMPORTANT:</strong> Utilisez exactement cette référence. Paiement traité sous 2h après confirmation.
                        </p>
                    </div>
                </div>

                <!-- Bank Details -->
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="bg-gradient-to-b from-gray-50 to-white rounded-2xl p-6 border">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Compte Carré Premium</h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="font-semibold text-gray-700">Banque:</span>
                                <span class="block mt-1 bg-white px-3 py-2 rounded-lg border text-gray-900 font-mono">Banque Atlantique CI</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">Titulaire:</span>
                                <span class="block mt-1 bg-white px-3 py-2 rounded-lg border text-gray-900 font-mono">{{ strtoupper($companyName) }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">RIB:</span>
                                <span class="block mt-1 bg-white px-3 py-2 rounded-lg border text-gray-900 font-mono">CI23 1234 5678 9012 3456 7890 123</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">BIC/Swift:</span>
                                <span class="block mt-1 bg-white px-3 py-2 rounded-lg border text-gray-900 font-mono">BLAC CICIAXXX</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700">N° fiscal:</span>
                                <span class="block mt-1 bg-white px-3 py-2 rounded-lg border text-gray-900 font-mono">{{ $companyTaxId }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gradient-to-b from-emerald-50 to-white rounded-2xl p-6 border">
                        <h3 class="text-lg font-bold text-gray-900 mb-6">Instructions</h3>
                        <ol class="space-y-3 text-sm">
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-xs mr-3 mt-0.5 flex-shrink-0">1</span>
                                <span>Effectuez virement avec <strong>référence exacte</strong> <code class="font-mono bg-emerald-100 px-2 py-1 rounded">{{ $booking->booking_number }}</code></span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-xs mr-3 mt-0.5 flex-shrink-0">2</span>
                                <span>Envoyez votre preuve de paiement par WhatsApp ou email pour accélérer la validation.</span>
                            </li>
                            <li class="flex items-start">
                                <span class="w-6 h-6 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center font-bold text-xs mr-3 mt-0.5 flex-shrink-0">3</span>
                                <span>Confirmation + billets sous <strong>2h maximum</strong></span>
                            </li>
                        </ol>
                    </div>
                </div>

                <div class="rounded-2xl border border-gray-200 bg-gray-50 p-6">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Envoyer votre preuve de paiement</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                Déposez votre reçu ici pour que l’équipe puisse vérifier votre virement rapidement.
                            </p>
                        </div>
                        <div class="text-sm text-gray-600">
                            @if($booking->has_payment_proof)
                                <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 font-semibold text-green-700">
                                    Preuve déjà envoyée
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 font-semibold text-amber-700">
                                    En attente de preuve
                                </span>
                            @endif
                        </div>
                    </div>

                    <form action="{{ $proofUploadUrl }}" method="POST" enctype="multipart/form-data" class="mt-6 space-y-4">
                        @csrf
                        <div>
                            <label for="payment_proof" class="block text-sm font-semibold text-gray-700 mb-2">Fichier justificatif</label>
                            <input
                                id="payment_proof"
                                type="file"
                                name="payment_proof"
                                accept=".pdf,.jpg,.jpeg,.png,.webp"
                                class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700"
                            >
                            @error('payment_proof')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="payment_proof_notes" class="block text-sm font-semibold text-gray-700 mb-2">Note (optionnel)</label>
                            <textarea
                                id="payment_proof_notes"
                                name="payment_proof_notes"
                                rows="3"
                                class="block w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm text-gray-700"
                                placeholder="Exemple : virement effectué depuis le compte de M. X, ce matin à 09h15."
                            >{{ old('payment_proof_notes', $booking->payment_proof_notes) }}</textarea>
                            @error('payment_proof_notes')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-bold text-white transition hover:bg-blue-700">
                            Envoyer la preuve
                        </button>
                    </form>
                </div>

                <div class="rounded-2xl border border-blue-200 bg-blue-50 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Besoin d’aide pendant le paiement ?</h3>
                    <div class="flex flex-col gap-3 text-sm text-gray-700">
                        <a href="{{ $mobilePhoneLink }}" class="font-semibold text-blue-700 hover:text-blue-800">
                            Téléphone : {{ $mobilePhoneDisplay }}
                        </a>
                        <a href="{{ $whatsAppUrl }}" target="_blank" rel="noopener noreferrer" class="font-semibold text-blue-700 hover:text-blue-800">
                            WhatsApp : {{ config('carre_premium.contact.whatsapp_display') }}
                        </a>
                        <a href="mailto:{{ $supportEmail }}" class="font-semibold text-blue-700 hover:text-blue-800">
                            Email : {{ $supportEmail }}
                        </a>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-8 border-t">
                    <a href="{{ $backUrl }}" class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-900 font-semibold py-4 px-6 rounded-xl text-center transition-all">
                        ← Retour à la réservation
                    </a>
                    <button type="button" onclick="copyReference(this)" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl transition-all shadow-lg hover:shadow-xl">
                        Copier RIB & Référence
                    </button>
                    <a href="{{ $whatsAppUrl }}?text={{ rawurlencode('Preuve de paiement ' . $booking->booking_number . ' - ' . $booking->final_amount . ' ' . $booking->currency) }}" target="_blank" rel="noopener noreferrer" class="flex-1 bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl text-center transition-all shadow-lg hover:shadow-xl">
                        WhatsApp Preuve
                    </a>
                </div>
            </div>
        </div>

        <!-- FAQ -->
        <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-12 mb-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">FAQ Paiement VIP</h2>
            <div class="grid md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">⏱️ Délai confirmation?</h3>
                    <p class="text-gray-600 mb-6">Sous 2h après réception preuve virement. Service 24/7.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">💳 Autres options?</h3>
                    <p class="text-gray-600 mb-6">Pour packages <1.5M: Mobile Money. >1.5M: Virement obligatoire (sécurité).</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">📄 Billets?</h3>
                    <p class="text-gray-600 mb-6">PDF billets + e-tickets envoyés par email après confirmation.</p>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">❓ Problème?</h3>
                    <p class="text-gray-600 mb-6">WhatsApp {{ config('carre_premium.contact.whatsapp_display') }} ou {{ $billingEmail }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function copyReference(button) {
    const ref = '{{ $booking->booking_number }}';
    const rib = 'CI23 1234 5678 9012 3456 7890 123';
    const text = `Réservation: ${ref}\\nMontant: {{ $booking->final_amount }} {{ $booking->currency }}\\nRIB: ${rib}\\nEmail: {{ $billingEmail }}\\n\\n[Preuve virement]`;
    
    navigator.clipboard.writeText(text).then(() => {
        const original = button.textContent;
        button.textContent = '✅ Copié!';
        button.style.background = '#10b981';
        setTimeout(() => {
            button.textContent = original;
            button.style.background = '';
        }, 2000);
    });
}
</script>
@endsection
