@extends('layouts.app')

@section('title', 'Paiement - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
      {{-- Header --}}
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Paiement de la réservation</h1>
            <p class="text-gray-600 mt-1">Référence: <span class="font-semibold">{{ $booking->booking_number }}</span></p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Montant total</p>
            <p class="text-2xl font-bold text-purple-600">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Booking Details --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Détails de la réservation</h2>

          @if($booking->booking_type === 'event')
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Événement:</span>
                <span class="font-semibold text-gray-900">{{ $booking->event->title_fr }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">{{ $booking->event_selection_type_label }}:</span>
                <span class="font-semibold text-gray-900">{{ $booking->event_selection_label }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Nombre de places:</span>
                <span class="font-semibold text-gray-900">{{ $booking->number_of_passengers }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Date:</span>
                <span class="font-semibold text-gray-900">{{ $booking->travel_date_label }}</span>
              </div>
            </div>
          @elseif($booking->booking_type === 'flight')
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Vol:</span>
                <span class="font-semibold text-gray-900">{{ $booking->flight->flight_number }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Passagers:</span>
                <span class="font-semibold text-gray-900">{{ $booking->number_of_passengers }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Classe:</span>
                <span class="font-semibold text-gray-900">{{ $booking->seat_class }}</span>
              </div>
            </div>
          @elseif($booking->booking_type === 'package')
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Package:</span>
                <span class="font-semibold text-gray-900">{{ $booking->package->title_fr }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Durée:</span>
                <span class="font-semibold text-gray-900">{{ $booking->package->duration }} jours</span>
              </div>
            </div>
          @endif
        </div>

        {{-- Payment Form --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Méthode de paiement</h2>

          <form action="{{ route('payment.process', $booking) }}" method="POST" id="payment-form">
            @csrf

            <div class="space-y-4">
              {{-- Payment Methods --}}
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Choisissez votre méthode de paiement</label>
                <div class="space-y-2">
                  <div class="flex items-center">
                    <input type="radio" id="card" name="payment_method" value="card" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300" checked>
                    <label for="card" class="ml-2 block text-sm text-gray-900">
                      <div class="flex items-center">
                        <span>Carte bancaire</span>
                        <div class="ml-2 flex space-x-1">
                          <img src="https://js.stripe.com/v3/fingerprinted/img/visa-729c05c240c4bdb47b03ac81d9945bfe.svg" alt="Visa" class="h-6 w-8">
                          <img src="https://js.stripe.com/v3/fingerprinted/img/mastercard-4d8844094130711885b5e41b28c9848f.svg" alt="Mastercard" class="h-6 w-8">
                        </div>
                      </div>
                    </label>
                  </div>

                  <div class="flex items-center">
                    <input type="radio" id="mobile_money" name="payment_method" value="mobile_money" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                    <label for="mobile_money" class="ml-2 block text-sm text-gray-900">
                      Mobile Money (Orange Money, MTN Mobile Money)
                    </label>
                  </div>

                  <div class="flex items-center">
                    <input type="radio" id="ussd" name="payment_method" value="ussd" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                    <label for="ussd" class="ml-2 block text-sm text-gray-900">
                      USSD (*126#)
                    </label>
                  </div>

                  <div class="flex items-center">
                    <input type="radio" id="bank_transfer" name="payment_method" value="bank_transfer" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                    <label for="bank_transfer" class="ml-2 block text-sm text-gray-900">
                      Virement bancaire
                    </label>
                  </div>
                </div>
              </div>

              {{-- Payment Summary --}}
              <div class="border-t pt-4">
                <div class="flex justify-between items-center">
                  <span class="text-lg font-semibold text-gray-900">Total à payer:</span>
                  <span class="text-2xl font-bold text-purple-600">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</span>
                </div>
              </div>

              {{-- Terms and Conditions --}}
              <div class="flex items-start">
                <div class="flex items-center h-5">
                  <input id="terms" name="terms" type="checkbox" class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded" required>
                </div>
                <div class="ml-3 text-sm">
                  <label for="terms" class="text-gray-700">
                    J'accepte les <a href="{{ route('terms') }}" target="_blank" class="text-purple-600 hover:text-purple-500">conditions générales</a>
                    et la <a href="{{ route('privacy') }}" target="_blank" class="text-purple-600 hover:text-purple-500">politique de confidentialité</a>
                  </label>
                </div>
              </div>

              {{-- Payment Button --}}
              <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <span id="button-text">Procéder au paiement</span>
                <div id="spinner" class="hidden inline-block ml-2">
                  <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                </div>
              </button>
            </div>
          </form>
        </div>
      </div>

      {{-- Security Notice --}}
      <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-6">
        <div class="flex items-start">
          <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
            <svg class="w-3 h-3 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-green-800 mb-1">Paiement sécurisé</h4>
            <p class="text-sm text-green-700">
              Toutes les transactions sont sécurisées et cryptées. Vos informations bancaires sont protégées par Flutterwave.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
    const button = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    button.textContent = 'Traitement en cours...';
    spinner.classList.remove('hidden');
});
</script>
@endsection
