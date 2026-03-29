@extends('layouts.app')

@section('title', __('Secure Payment') . ' - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-amber-50 py-8">
  <div class="container mx-auto px-4">
    <div class="max-w-5xl mx-auto">
      
      {{-- Header with Security Badge --}}
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-600 to-amber-600 rounded-full mb-4 shadow-lg">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
        </div>
        <h1 class="text-3xl font-black text-gray-900 mb-2">{{ __('Secure Payment') }}</h1>
        <p class="text-gray-600">{{ __('Complete your booking with CinetPay') }}</p>
        <div class="flex items-center justify-center gap-2 mt-3">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            {{ __('SSL Secured') }}
          </span>
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
              <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z" />
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd" />
            </svg>
            {{ __('Instant Payment') }}
          </span>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        {{-- Payment Methods Section --}}
        <div class="lg:col-span-2">
          <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-amber-600 px-6 py-4">
              <h2 class="text-xl font-bold text-white">{{ __('Choose Your Payment Method') }}</h2>
            </div>
            
            <div class="p-6">
              <form id="payment-form" action="{{ $paymentProcessUrl ?? route('payment.cinetpay.process', $booking) }}" method="POST">
                @csrf
                
                <div class="space-y-4">
              {{-- Mobile Money --}}
              <div class="payment-method-card group">
                <input type="radio" id="mobile_money" name="payment_channel" value="MOBILE_MONEY" class="hidden peer" checked>
                <label for="mobile_money" class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-purple-300 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:shadow-md">
                  <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-orange-400 to-orange-600 rounded-lg flex items-center justify-center text-2xl shadow-md">
                    📱
                  </div>
                  <div class="ml-4 flex-1">
                    <h3 class="font-bold text-gray-900">Mobile Money</h3>
                    <p class="text-sm text-gray-600">Orange, MTN, Moov Money</p>
                  </div>
                  <div class="ml-auto">
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-purple-600 peer-checked:bg-purple-600 flex items-center justify-center">
                      <svg class="w-4 h-4 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                </label>
              </div>

              {{-- Wave --}}
              <div class="payment-method-card group">
                <input type="radio" id="wave" name="payment_channel" value="WAVE_CI" class="hidden peer">
                <label for="wave" class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-purple-300 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:shadow-md">
                  <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
                      <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                      <path d="M7 12l5-5 5 5-5 5z" opacity="0.3"/>
                    </svg>
                  </div>
                  <div class="ml-4 flex-1">
                    <h3 class="font-bold text-gray-900">Wave</h3>
                    <p class="text-sm text-gray-600">{{ __('Fast and secure') }}</p>
                  </div>
                  <div class="ml-auto">
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-purple-600 peer-checked:bg-purple-600 flex items-center justify-center">
                      <svg class="w-4 h-4 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                </label>
              </div>

              {{-- All Methods --}}
              <div class="payment-method-card group">
                <input type="radio" id="all" name="payment_channel" value="ALL" class="hidden peer">
                <label for="all" class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-purple-300 peer-checked:border-purple-600 peer-checked:bg-purple-50 peer-checked:shadow-md">
                  <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-lg flex items-center justify-center text-2xl shadow-md">
                    💰
                  </div>
                  <div class="ml-4 flex-1">
                    <h3 class="font-bold text-gray-900">{{ __('All Methods') }}</h3>
                    <p class="text-sm text-gray-600">{{ __('Choose on next page') }}</p>
                  </div>
                  <div class="ml-auto">
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-purple-600 peer-checked:bg-purple-600 flex items-center justify-center">
                      <svg class="w-4 h-4 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                </label>
              </div>

              {{-- Cybersource - Bank Card Payment (Direct Redirect) --}}
              <div class="payment-method-card group" onclick="selectCybersourceDirect()">
                <input type="radio" id="cybersource" name="payment_channel" value="CYBERSOURCE" class="hidden peer">
                <label for="cybersource" class="flex items-center p-4 border-2 border-gray-200 rounded-xl cursor-pointer transition-all hover:border-blue-300 peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md">
                  <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-lg flex items-center justify-center shadow-md">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                  </div>
                  <div class="ml-4 flex-1">
                    <h3 class="font-bold text-gray-900">Carte Bancaire</h3>
                    <p class="text-sm text-gray-600">Visa, Mastercard, American Express</p>
                  </div>
                  <div class="ml-auto flex items-center gap-2">
                    <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs font-medium rounded">Cybersource</span>
                    <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center">
                      <svg class="w-4 h-4 text-white hidden peer-checked:block" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                </label>
              </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200">
                  <button type="submit" id="pay-button" class="w-full bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold py-4 px-6 rounded-xl hover:shadow-2xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                    <span id="button-text">{{ __('Pay') }} {{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</span>
                    <svg class="w-5 h-5 animate-spin hidden" id="loading-spinner" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                  </button>
                  <p class="text-xs text-center text-gray-500 mt-3">
                    {{ __('By clicking "Pay", you agree to our terms and conditions') }}
                  </p>
                </div>
              </form>
            </div>
          </div>

          {{-- Security Info --}}
          <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
            <div class="flex items-start">
              <svg class="w-6 h-6 text-blue-600 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
              </svg>
              <div class="ml-3">
                <h4 class="text-sm font-semibold text-blue-900">{{ __('Secure Payment') }}</h4>
                <p class="text-sm text-blue-700 mt-1">
                  {{ __('Your payment is processed securely by CinetPay. Your banking information is encrypted and never stored on our servers.') }}
                </p>
              </div>
            </div>
          </div>
        </div>

        {{-- Order Summary --}}
        <div class="lg:col-span-1">
          <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden sticky top-4">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
              <h3 class="font-bold text-gray-900">{{ __('Order Summary') }}</h3>
            </div>
            
            <div class="p-6 space-y-4">
              <div>
                <p class="text-sm text-gray-600">{{ __('Booking Number') }}</p>
                <p class="font-mono font-bold text-purple-600">{{ $booking->booking_number }}</p>
              </div>

              @if($booking->booking_type === 'event')
                <div>
                  <p class="text-sm text-gray-600">{{ __('Event') }}</p>
                  <p class="font-semibold text-gray-900">{{ $booking->event->title_fr }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">{{ $booking->event_selection_type_label }}</p>
                  <p class="font-semibold text-gray-900">{{ $booking->event_selection_label }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">{{ __('Tickets') }}</p>
                  <p class="font-semibold text-gray-900">{{ $booking->number_of_passengers }}</p>
                </div>
              @elseif($booking->booking_type === 'package')
                <div>
                  <p class="text-sm text-gray-600">{{ __('Package') }}</p>
                  <p class="font-semibold text-gray-900">{{ $booking->package->title_fr }}</p>
                </div>
                <div>
                  <p class="text-sm text-gray-600">{{ __('Destination') }}</p>
                  <p class="font-semibold text-gray-900">{{ $booking->package->destination }}</p>
                </div>
              @elseif($booking->booking_type === 'location')
                <div>
                  <p class="text-sm text-gray-600">{{ __('Location') }}</p>
                  <p class="font-semibold text-gray-900">{{ $booking->location->name }}</p>
                </div>
              @endif

              <div class="pt-4 border-t border-gray-200">
                <div class="flex justify-between items-center mb-2">
                  <span class="text-gray-600">{{ __('Subtotal') }}</span>
                  <span class="font-semibold">{{ \App\Helpers\CurrencyHelper::format($booking->total_amount) }}</span>
                </div>
                @if($booking->discount_amount > 0)
                <div class="flex justify-between items-center mb-2 text-green-600">
                  <span>{{ __('Discount') }}</span>
                  <span class="font-semibold">-{{ \App\Helpers\CurrencyHelper::format($booking->discount_amount) }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center pt-3 border-t border-gray-200">
                  <span class="text-lg font-bold text-gray-900">{{ __('Total') }}</span>
                  <span class="text-2xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</span>
                </div>
              </div>
            </div>
          </div>

          {{-- Support --}}
          <div class="mt-4 bg-gradient-to-br from-purple-50 to-amber-50 rounded-xl p-4 border border-purple-100">
            <h4 class="font-semibold text-gray-900 mb-2 flex items-center">
              <svg class="w-5 h-5 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
              </svg>
              {{ __('Need Help?') }}
            </h4>
            <p class="text-sm text-gray-600 mb-2">{{ __('Our team is here to help') }}</p>
            <a href="tel:+2252721594258" class="text-sm font-medium text-purple-600 hover:text-purple-700">
              📞 +225 27 21 59 42 58
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
document.getElementById('payment-form').addEventListener('submit', function(e) {
  const button = document.getElementById('pay-button');
  const buttonText = document.getElementById('button-text');
  const spinner = document.getElementById('loading-spinner');
  
  button.disabled = true;
  buttonText.classList.add('hidden');
  spinner.classList.remove('hidden');
});

// Function to redirect directly to CyberSource when bank card is selected
function selectCybersourceDirect() {
    // Set the radio button
    document.getElementById('cybersource').checked = true;
    
    // Change button behavior for CyberSource
    const payButton = document.getElementById('pay-button');
    const buttonText = document.getElementById('button-text');
    
    // Store the original form action
    const originalForm = document.getElementById('payment-form');
    
    // Update the form to submit to CyberSource checkout directly
    payButton.onclick = function(e) {
        e.preventDefault();
        
        // Show loading
        payButton.disabled = true;
        buttonText.textContent = 'Redirection vers le paiement sécurisé...';
        payButton.querySelector('svg').classList.remove('hidden');
        
        // Redirect to CyberSource checkout
        window.location.href = "{{ route('payment.cybersource.checkout', $booking->id) }}";
    };
}
</script>
@endsection
