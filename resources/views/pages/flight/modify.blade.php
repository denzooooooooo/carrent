@extends('layouts.app')

@section('title', __('Modify Flight Booking - Duffel v2') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-black mb-2">{{ __('Modify Your Flight Booking') }}</h1>
            <p class="text-lg opacity-90">
                {{ __('Change your flight details with Duffel API v2') }}
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if(isset($quote) && $quote)
            {{-- Change Quote Details --}}
            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-6 border-2 border-purple-100">
                <div class="flex items-center gap-3 mb-6 border-b-2 border-purple-100 pb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <h2 class="text-2xl font-black text-gray-900">{{ __('Change Quote Details') }}</h2>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">{{ __('Duffel v2') }}</span>
                </div>

                {{-- Current vs New Flight --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    {{-- Current Flight --}}
                    <div class="bg-red-50 rounded-xl p-4 border-2 border-red-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            <h3 class="font-bold text-red-800">{{ __('Current Flight') }}</h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">{{ __('Route') }}:</span> {{ $current_flight['departure'] ?? 'N/A' }} → {{ $current_flight['arrival'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">{{ __('Date') }}:</span> {{ isset($current_flight['date']) ? \Carbon\Carbon::parse($current_flight['date'])->format('d M Y') : 'N/A' }}</p>
                            <p><span class="font-medium">{{ __('Airline') }}:</span> {{ $current_flight['airline'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">{{ __('Flight') }}:</span> {{ $current_flight['flight_number'] ?? 'N/A' }}</p>
                        </div>
                    </div>

                    {{-- New Flight --}}
                    <div class="bg-green-50 rounded-xl p-4 border-2 border-green-200">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <h3 class="font-bold text-green-800">{{ __('New Flight') }}</h3>
                        </div>
                        <div class="space-y-2 text-sm">
                            <p><span class="font-medium">{{ __('Route') }}:</span> {{ $new_slices[0]['origin'] ?? 'N/A' }} → {{ $new_slices[0]['destination'] ?? 'N/A' }}</p>
                            <p><span class="font-medium">{{ __('Date') }}:</span> {{ isset($new_slices[0]['departure_date']) ? \Carbon\Carbon::parse($new_slices[0]['departure_date'])->format('d M Y') : 'N/A' }}</p>
                            <p><span class="font-medium">{{ __('Airline') }}:</span> {{ __('To be confirmed') }}</p>
                            <p><span class="font-medium">{{ __('Flight') }}:</span> {{ __('To be assigned') }}</p>
                        </div>
                    </div>
                </div>

                {{-- Price Comparison --}}
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 border-2 border-blue-200">
                    <div class="flex items-center gap-3 mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        <h3 class="text-xl font-black text-blue-800">{{ __('Price Comparison') }}</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-gray-500">{{ __('Original Price') }}</p>
                            <p class="text-xl font-black text-gray-900">
                                {{ number_format($original_price ?? 0, 2) }} {{ strtoupper($quote['total_currency'] ?? 'EUR') }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-gray-500">{{ __('New Price') }}</p>
                            <p class="text-xl font-black text-gray-900">
                                {{ number_format($quote['total_amount'] ?? 0, 2) }} {{ strtoupper($quote['total_currency'] ?? 'EUR') }}
                            </p>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-blue-200">
                            <p class="text-sm text-gray-500">{{ __('Difference') }}</p>
                            @php
                                $difference = ($quote['total_amount'] ?? 0) - ($original_price ?? 0);
                                $isPositive = $difference > 0;
                            @endphp
                            <p class="text-xl font-black {{ $isPositive ? 'text-red-600' : 'text-green-600' }}">
                                {{ $isPositive ? '+' : '' }}{{ number_format($difference, 2) }} {{ strtoupper($quote['total_currency'] ?? 'EUR') }}
                            </p>
                        </div>
                    </div>

                    {{-- Additional Costs --}}
                    @if(isset($quote['additional_payment_amount']) && $quote['additional_payment_amount'] > 0)
                        <div class="mt-4 p-4 bg-amber-50 rounded-lg border border-amber-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                                <span class="font-bold text-amber-800">{{ __('Additional Payment Required') }}</span>
                            </div>
                            <p class="text-amber-700">
                                {{ __('You will need to pay an additional') }}
                                <strong>{{ number_format($quote['additional_payment_amount'], 2) }} {{ strtoupper($quote['additional_payment_currency'] ?? 'EUR') }}</strong>
                                {{ __('for this change.') }}
                            </p>
                        </div>
                    @endif

                    @if(isset($quote['refund_amount']) && $quote['refund_amount'] > 0)
                        <div class="mt-4 p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center gap-2 mb-2">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="font-bold text-green-800">{{ __('Refund Available') }}</span>
                            </div>
                            <p class="text-green-700">
                                {{ __('You will receive a refund of') }}
                                <strong>{{ number_format($quote['refund_amount'], 2) }} {{ strtoupper($quote['refund_currency'] ?? 'EUR') }}</strong>
                                {{ __('for this change.') }}
                            </p>
                        </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-purple-200">
                    <a href="{{ route('flights.orders') }}"
                        class="flex-1 text-center bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-6 py-4 rounded-xl font-bold hover:from-gray-200 hover:to-gray-300 transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span>{{ __('Cancel & Return') }}</span>
                    </a>

                    <form action="{{ route('flights.confirm-modification') }}" method="POST" id="confirmChangeForm">
                        @csrf
                        <input type="hidden" name="order_id" value="{{ $order_id ?? '' }}">
                        <input type="hidden" name="change_quote_id" value="{{ $quote['id'] ?? '' }}">

                        <button type="submit" id="confirmBtn"
                            class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-black hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-2xl border-2 border-purple-300 flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>{{ __('Confirm Change') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        @else
            {{-- Change Request Form --}}
            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-6 border-2 border-purple-100">
                <div class="flex items-center gap-3 mb-6 border-b-2 border-purple-100 pb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <h2 class="text-2xl font-black text-gray-900">{{ __('Request Flight Change') }}</h2>
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">{{ __('Duffel v2') }}</span>
                </div>

                {{-- Current Booking Info --}}
                @if(isset($current_booking))
                    <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-purple-200">
                        <h3 class="font-bold text-gray-900 mb-3">{{ __('Current Booking') }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">{{ __('Order ID') }}</p>
                                <p class="font-bold text-gray-900">{{ $current_booking['duffel_order_id'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('Booking Reference') }}</p>
                                <p class="font-bold text-gray-900">{{ $current_booking['duffel_booking_reference'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('Route') }}</p>
                                <p class="font-bold text-gray-900">{{ $current_booking['departure_airport'] ?? 'N/A' }} → {{ $current_booking['arrival_airport'] ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">{{ __('Date') }}</p>
                                <p class="font-bold text-gray-900">
                                    {{ isset($current_booking['departure_date']) ? \Carbon\Carbon::parse($current_booking['departure_date'])->format('d M Y') : 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Change Request Form --}}
                <form action="{{ route('flights.modify', $order_id ?? '') }}" method="POST" id="changeRequestForm">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- New Departure Airport --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('New Departure Airport') }} *</label>
                            <input type="text"
                                   name="new_origin"
                                   placeholder="e.g., CDG, JFK, ABJ"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-semibold uppercase"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Enter airport code (e.g., CDG for Paris)') }}</p>
                        </div>

                        {{-- New Arrival Airport --}}
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('New Arrival Airport') }} *</label>
                            <input type="text"
                                   name="new_destination"
                                   placeholder="e.g., JFK, CDG, ABJ"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-semibold uppercase"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Enter airport code (e.g., JFK for New York)') }}</p>
                        </div>

                        {{-- New Departure Date --}}
                        <div class="md:col-span-2">
                            <label class="block text-sm font-bold text-gray-700 mb-2">{{ __('New Departure Date') }} *</label>
                            <input type="date"
                                   name="new_departure_date"
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-semibold"
                                   required>
                            <p class="text-xs text-gray-500 mt-1">{{ __('Select a date at least 1 day from now') }}</p>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-purple-200 mt-6">
                        <a href="{{ route('flights.orders') }}"
                            class="flex-1 text-center bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-6 py-4 rounded-xl font-bold hover:from-gray-200 hover:to-gray-300 transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                            <span>{{ __('Back to Bookings') }}</span>
                        </a>

                        <button type="submit" id="requestChangeBtn"
                            class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-black hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-2xl border-2 border-purple-300 flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ __('Request Change Quote') }}</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif

        {{-- Duffel API Info --}}
        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-blue-700">{{ __('Powered by Duffel API v2') }}</span>
            </div>
            <p class="text-sm text-blue-600">
                {{ __('Flight modifications are handled through Duffel\'s advanced change management system with real-time pricing and automatic fare calculations.') }}
            </p>
        </div>
    </div>
</div>

<script>
document.getElementById('changeRequestForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('requestChangeBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>{{ __('Requesting Quote...') }}</span>';
});

document.getElementById('confirmChangeForm')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('confirmBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>{{ __('Confirming Change...') }}</span>';
});
</script>
@endsection
