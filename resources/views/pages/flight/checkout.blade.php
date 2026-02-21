@extends('layouts.app')

@section('title', __('Review & Payment') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header -->
    <div class="bg-[#001F3F] text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl font-bold mb-2">{{ __('Review Your Booking') }}</h1>
            <p class="text-gray-300">{{ __('Please review passenger information before payment') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @php
            $totalPassengers = $total_passengers ?? count($passengers ?? [1]);
            $price = $price ?? ($offer['total_amount'] ?? 0);
            $currency = $currency ?? ($offer['total_currency'] ?? 'EUR');
            $exchangeRate = $exchange_rate ?? 655.957;
            $totalXof = $price * $totalPassengers * $exchangeRate;
        @endphp

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Flight Summary -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        {{ __('Flight Details') }}
                    </h2>

                    <div class="flex flex-col lg:flex-row items-center gap-6 p-4 bg-blue-50 rounded-lg">
                        <div class="text-center lg:text-left">
                            <p class="text-2xl font-black text-gray-900">
                                {{ isset($flight_details['departure_time']) ? \Carbon\Carbon::parse($flight_details['departure_time'])->format('H:i') : '--:--' }}
                            </p>
                            <p class="font-bold text-gray-800">{{ $flight_details['departure_airport'] ?? '---' }}</p>
                        </div>
                        <div class="flex-1 text-center">
                            <p class="text-sm text-gray-600">{{ $flight_details['duration_formatted'] ?? 'Duration' }}</p>
                            <div class="flex items-center justify-center gap-2">
                                <div class="h-0.5 bg-gray-300 w-full"></div>
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <div class="h-0.5 bg-gray-300 w-full"></div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                {{ ($flight_details['stops'] ?? 0) == 0 ? __('Direct') : ($flight_details['stops'] . ' ' . __('stop(s)')) }}
                            </p>
                        </div>
                        <div class="text-center lg:text-right">
                            <p class="text-2xl font-black text-gray-900">
                                {{ isset($flight_details['arrival_time']) ? \Carbon\Carbon::parse($flight_details['arrival_time'])->format('H:i') : '--:--' }}
                            </p>
                            <p class="font-bold text-gray-800">{{ $flight_details['arrival_airport'] ?? '---' }}</p>
                        </div>
                    </div>

                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div><span class="text-gray-500">{{ __('Flight') }}:</span> <span class="font-medium">{{ $flight_details['flight_number'] ?? '---' }}</span></div>
                        <div><span class="text-gray-500">{{ __('Airline') }}:</span> <span class="font-medium">{{ $flight_details['airline'] ?? '---' }}</span></div>
                        <div><span class="text-gray-500">{{ __('Class') }}:</span> <span class="font-medium">{{ $flight_details['cabin_class'] ?? 'ECONOMY' }}</span></div>
                        <div><span class="text-gray-500">{{ __('Date') }}:</span> <span class="font-medium">{{ isset($search['outbound_date']) ? \Carbon\Carbon::parse($search['outbound_date'])->format('d/m/Y') : '---' }}</span></div>
                    </div>
                </div>

                <!-- Passengers Summary -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('Passengers') }}
                    </h2>

                    <div class="space-y-4">
                        @foreach($passengers ?? [] as $index => $passenger)
                            <div class="p-4 bg-gray-50 rounded-lg flex items-center gap-4">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="font-bold text-blue-700">{{ $index + 1 }}</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-gray-900">
                                        {{ strtoupper($passenger['last_name'] ?? '') }} {{ ucfirst($passenger['first_name'] ?? '') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        {{ ucfirst($passenger['type'] ?? 'Adult') }}
                                        @if(!empty($passenger['identity_document_number']))
                                            | {{ $passenger['identity_document_type'] ?? 'Passport' }}: {{ $passenger['identity_document_number'] }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Payment Form -->
                <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        {{ __('Payment Method') }}
                    </h2>

                    <form action="{{ route('payment.process') }}" method="POST" id="paymentForm">
                        @csrf
                        
                        <!-- Hidden fields for flight data -->
                        <input type="hidden" name="offer_id" value="{{ $offer_id ?? '' }}">
                        <input type="hidden" name="flight_number" value="{{ $flight_details['flight_number'] ?? '' }}">
                        <input type="hidden" name="airline" value="{{ $flight_details['airline'] ?? '' }}">
                        <input type="hidden" name="departure_airport" value="{{ $flight_details['departure_airport'] ?? '' }}">
                        <input type="hidden" name="arrival_airport" value="{{ $flight_details['arrival_airport'] ?? '' }}">
                        <input type="hidden" name="departure_time" value="{{ $flight_details['departure_time'] ?? '' }}">
                        <input type="hidden" name="arrival_time" value="{{ $flight_details['arrival_time'] ?? '' }}">
                        <input type="hidden" name="departure_date" value="{{ $search['outbound_date'] ?? '' }}">
                        <input type="hidden" name="cabin_class" value="{{ $flight_details['cabin_class'] ?? 'ECONOMY' }}">
                        <input type="hidden" name="duration" value="{{ $flight_details['duration_formatted'] ?? '' }}">
                        <input type="hidden" name="stops" value="{{ $flight_details['stops'] ?? 0 }}">
                        <input type="hidden" name="total_price" value="{{ $totalXof }}">
                        <input type="hidden" name="base_price" value="{{ $totalXof }}">
                        <input type="hidden" name="adults" value="{{ $search['adults'] ?? 1 }}">
                        <input type="hidden" name="children" value="{{ $search['children'] ?? 0 }}">
                        <input type="hidden" name="infants" value="{{ $search['infants'] ?? 0 }}">
                        
                        @foreach($passengers ?? [] as $index => $passenger)
                            @foreach($passenger as $key => $value)
                                <input type="hidden" name="passengers[{{ $index + 1 }}][{{ $key }}]" value="{{ $value }}">
                            @endforeach
                        @endforeach

                        <!-- Payment Gateway Selection -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_gateway" value="cinetpay" class="peer sr-only" checked>
                                <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">💳</div>
                                        <div>
                                            <p class="font-bold text-gray-900">CinetPay</p>
                                            <p class="text-xs text-gray-500">Mobile Money & Cards</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                            
                            <label class="cursor-pointer">
                                <input type="radio" name="payment_gateway" value="cybersource" class="peer sr-only">
                                <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">🔐</div>
                                        <div>
                                            <p class="font-bold text-gray-900">CyberSource</p>
                                            <p class="text-xs text-gray-500">Credit Cards</p>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('flights.select') }}" 
                               class="flex-1 text-center bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                                ← {{ __('Back') }}
                            </a>
                            <button type="submit" 
                                    id="payBtn"
                                    class="flex-1 bg-[#001F3F] text-white py-4 px-6 rounded-xl font-bold hover:bg-[#003366] transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                {{ __('Pay') }} {{ number_format($totalXof, 0, ',', ' ') }} XOF
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky top-6 bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('Price Summary') }}</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Price per person') }}</span>
                            <span class="font-medium">{{ number_format($price * $exchangeRate, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Passengers') }}</span>
                            <span class="font-medium">{{ $totalPassengers }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">{{ __('Cabin') }}</span>
                            <span class="font-medium">{{ $flight_details['cabin_class'] ?? 'ECONOMY' }}</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-bold text-gray-900">{{ __('Total') }}</span>
                            <span class="font-bold text-xl text-[#001F3F]">{{ number_format($totalXof, 0, ',', ' ') }} XOF</span>
                        </div>
                    </div>

                    <div class="mt-6 p-4 bg-green-50 rounded-lg border border-green-200">
                        <div class="flex items-center gap-2 text-green-700 text-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            <span class="font-medium">{{ __('Secure Payment') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>{{ __('Processing...') }}</span>';
});
</script>
@endsection

