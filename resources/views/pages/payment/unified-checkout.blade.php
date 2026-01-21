@extends('layouts.app')

@section('title', 'Paiement - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Progression</span>
                <span class="text-sm font-medium text-blue-600">Étape 2/2</span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 h-2.5 rounded-full" style="width: 100%"></div>
            </div>
            <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
                <span>Sélection</span>
                <span>Paiement</span>
                <span>Confirmation</span>
            </div>
        </div>

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full mb-4">
                <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Paiement sécurisé
            </h1>
            <p class="text-gray-600 dark:text-gray-400">
                Montant à payer: <span class="font-bold text-blue-600">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
            </p>
        </div>

        <!-- Error Messages -->
        @if(session('error'))
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-red-700 dark:text-red-300">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Payment Methods -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Payment Method Selection -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Choisir votre moyen de paiement
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                        Cliquez sur votre moyen de paiement pour être redirigé directement vers la page de paiement.
                    </p>

                    <form id="paymentForm" action="{{ route('payment.cinetpay.process', $booking) }}" method="POST">
                        @csrf
                        
                        <!-- Mobile Money Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                                Mobile Money
                            </h3>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <!-- Orange Money - CLICK TO PAY -->
                                <label class="payment-option cursor-pointer group" data-channel="ORANGE_MONEY">
                                    <input type="radio" name="payment_channel" value="ORANGE_MONEY" class="hidden peer">
                                    <div class="relative border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-orange-500 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/20 transition-all duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-orange-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-lg">OM</span>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block font-semibold text-gray-900 dark:text-white">Orange Money</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">Paiement immédiat via mobile</span>
                                            </div>
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-orange-500 peer-checked:bg-orange-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Badge Cliquer pour payer -->
                                        <div class="absolute -top-2 -right-2 bg-orange-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            Cliquer pour payer
                                        </div>
                                    </div>
                                </label>

                                <!-- MTN Money - CLICK TO PAY -->
                                <label class="payment-option cursor-pointer group" data-channel="MTN_MONEY">
                                    <input type="radio" name="payment_channel" value="MTN_MONEY" class="hidden peer">
                                    <div class="relative border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-yellow-500 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 transition-all duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-yellow-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-lg">MTN</span>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block font-semibold text-gray-900 dark:text-white">MTN Money</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">Paiement immédiat via mobile</span>
                                            </div>
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-yellow-500 peer-checked:bg-yellow-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="absolute -top-2 -right-2 bg-yellow-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            Cliquer pour payer
                                        </div>
                                    </div>
                                </label>

                                <!-- Moov Money - CLICK TO PAY -->
                                <label class="payment-option cursor-pointer group" data-channel="MOOV_MONEY">
                                    <input type="radio" name="payment_channel" value="MOOV_MONEY" class="hidden peer">
                                    <div class="relative border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-blue-500 peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 transition-all duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-blue-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-lg">MV</span>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block font-semibold text-gray-900 dark:text-white">Moov Money</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">Paiement immédiat via mobile</span>
                                            </div>
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-500 peer-checked:bg-blue-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="absolute -top-2 -right-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            Cliquer pour payer
                                        </div>
                                    </div>
                                </label>

                                <!-- Wave - CLICK TO PAY (Auto-submit) -->
                                <label class="payment-option cursor-pointer group" data-channel="WAVE">
                                    <input type="radio" name="payment_channel" value="WAVE" class="hidden peer">
                                    <div class="relative border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-pink-500 peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-pink-900/20 transition-all duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-14 h-14 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                                <span class="text-white font-bold text-lg">W</span>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block font-semibold text-gray-900 dark:text-white">Wave</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">Paiement immédiat</span>
                                            </div>
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-pink-500 peer-checked:bg-pink-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="absolute -top-2 -right-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            Cliquer pour payer
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Credit Card Section -->
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">
                                Carte Bancaire
                            </h3>
                            
                            <div class="grid grid-cols-1 gap-4">
                                <!-- Visa/Mastercard - CLICK TO PAY -->
                                <label class="payment-option cursor-pointer group" data-channel="CREDIT_CARD">
                                    <input type="radio" name="payment_channel" value="ALL" class="hidden peer">
                                    <div class="relative border-2 border-gray-200 dark:border-gray-700 rounded-xl p-4 hover:border-indigo-500 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 dark:peer-checked:bg-indigo-900/20 transition-all duration-200">
                                        <div class="flex items-center space-x-4">
                                            <div class="flex space-x-2 flex-shrink-0">
                                                <div class="w-12 h-8 bg-blue-600 rounded flex items-center justify-center">
                                                    <span class="text-white font-bold text-xs">VISA</span>
                                                </div>
                                                <div class="w-12 h-8 bg-red-600 rounded flex items-center justify-center">
                                                    <span class="text-white font-bold text-xs">MC</span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <span class="block font-semibold text-gray-900 dark:text-white">Carte Visa / Mastercard</span>
                                                <span class="block text-xs text-gray-500 dark:text-gray-400">Paiement par carte bancaire sécurisée</span>
                                            </div>
                                            <div class="w-6 h-6 rounded-full border-2 border-gray-300 dark:border-gray-600 peer-checked:border-indigo-500 peer-checked:bg-indigo-500 flex items-center justify-center">
                                                <svg class="w-4 h-4 text-white opacity-0 peer-checked:opacity-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="absolute -top-2 -right-2 bg-indigo-500 text-white text-xs font-bold px-2 py-1 rounded-full shadow-lg">
                                            Cliquer pour payer
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button (visible when selection is made) -->
                        <div id="submitSection" class="hidden">
                            <button type="submit" id="submitBtn" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span id="submitText">Confirmer et payer</span>
                            </button>
                            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-3">
                                Vous allez être redirigé vers la page de paiement sécurisée
                            </p>
                        </div>
                    </form>
                </div>

                <!-- Security Badges -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6">
                    <div class="flex flex-wrap items-center justify-center gap-6 text-gray-500 dark:text-gray-400">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">Paiement 100% sécurisé</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">Cryptage SSL/TLS</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm9.707 5.707a1 1 0 00-1.414-1.414L9 12.586l-1.293-1.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">Données protégées</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium">Support 24/7</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Booking Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">Récapitulatif</h2>
                    
                    <!-- Booking Details -->
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Numéro de réservation</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $booking->booking_number }}</span>
                        </div>
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Type</span>
                            <span class="font-semibold text-gray-900 dark:text-white capitalize">
                                @switch($booking->booking_type)
                                    @case('flight') Vol @break
                                    @case('package') Package @break
                                    @case('event') Événement @break
                                    @case('location') Location @break
                                    @default {{ $booking->booking_type }}
                                @endswitch
                            </span>
                        </div>

                        @if($booking->travel_date)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Date</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ \Carbon\Carbon::parse($booking->travel_date)->format('d/m/Y') }}</span>
                        </div>
                        @endif

                        @if($booking->number_of_passengers)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Passagers</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ $booking->number_of_passengers }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Divider -->
                    <div class="border-t border-gray-200 dark:border-gray-700 my-6"></div>

                    <!-- Price Breakdown -->
                    <div class="space-y-3 mb-6">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Sous-total</span>
                            <span class="text-gray-900 dark:text-white">{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                        </div>
                        
                        @if($booking->total_amount != $booking->final_amount)
                        <div class="flex justify-between text-sm text-green-600">
                            <span>Réduction</span>
                            <span>-{{ number_format($booking->total_amount - $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                        </div>
                        @endif
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">Total</span>
                            <span class="text-2xl font-bold text-blue-600">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    @if(isset($booking->passenger_details[0]))
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Informations client</h3>
                        <div class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                            @if(isset($booking->passenger_details[0]['first_name']))
                            <p>{{ $booking->passenger_details[0]['first_name'] }} {{ $booking->passenger_details[0]['last_name'] ?? '' }}</p>
                            @elseif(isset($booking->passenger_details[0]['name']))
                            <p>{{ $booking->passenger_details[0]['name'] }}</p>
                            @endif
                            @if(isset($booking->passenger_details[0]['email']))
                            <p>{{ $booking->passenger_details[0]['email'] }}</p>
                            @endif
                            @if(isset($booking->passenger_details[0]['phone']))
                            <p>{{ $booking->passenger_details[0]['phone'] }}</p>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitSection = document.getElementById('submitSection');
    const submitText = document.getElementById('submitText');
    const paymentOptions = document.querySelectorAll('.payment-option');
    const selectedAmount = {{ $booking->final_amount }};
    const currency = '{{ $booking->currency }}';

    // Payment method names
    const paymentNames = {
        'ORANGE_MONEY': 'Orange Money',
        'MTN_MONEY': 'MTN Money',
        'MOOV_MONEY': 'Moov Money',
        'WAVE': 'Wave',
        'ALL': 'Carte Bancaire',
        'CREDIT_CARD': 'Carte Bancaire'
    };

    // Format amount
    const formatAmount = (amount) => {
        return new Intl.NumberFormat('fr-FR').format(amount) + ' ' + currency;
    };

    // Show submit button when payment method is selected
    paymentOptions.forEach(option => {
        const radio = option.querySelector('input[type="radio"]');
        
        radio.addEventListener('change', function() {
            if (this.checked) {
                // Show submit section
                submitSection.classList.remove('hidden');
                
                // Update submit text with selected payment method
                const methodName = paymentNames[this.value] || this.value;
                submitText.textContent = 'Payer avec ' + methodName + ' - ' + formatAmount(selectedAmount);
                
                // Scroll to submit button on mobile
                if (window.innerWidth < 1024) {
                    submitSection.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }

                // Auto-submit for Wave (immediate redirect)
                if (this.value === 'WAVE') {
                    showLoading(this.value);
                    form.submit();
                }
            }
        });
    });

    // Show loading state
    function showLoading(paymentMethod) {
        submitBtn.disabled = true;
        const methodName = paymentNames[paymentMethod] || paymentMethod;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Redirection vers ${methodName}...</span>
        `;
        submitBtn.classList.add('opacity-75');
    }

    // Form submission handler
    form.addEventListener('submit', function(e) {
        const selectedPayment = document.querySelector('input[name="payment_channel"]:checked');
        
        if (!selectedPayment) {
            e.preventDefault();
            showError('Veuillez sélectionner un moyen de paiement pour continuer.');
            return false;
        }

        // Don't prevent default for Wave (already handled)
        if (selectedPayment.value === 'WAVE') {
            return true;
        }

        // Show loading state
        showLoading(selectedPayment.value);
    });

    // Show error message
    function showError(message) {
        // Remove existing error if any
        const existingError = document.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }

        // Create error element
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-xl p-4 animate-pulse';
        errorDiv.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="text-red-700 dark:text-red-300 font-medium">${message}</p>
            </div>
        `;

        // Insert after header
        const header = document.querySelector('.text-center');
        header.after(errorDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            errorDiv.remove();
        }, 5000);
    }

    // Click on payment option should also trigger selection visual
    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            const radio = this.querySelector('input[type="radio"]');
            if (!radio.checked) {
                radio.checked = true;
                radio.dispatchEvent(new Event('change'));
            }
        });
    });
});
</script>
@endpush
@endsection
