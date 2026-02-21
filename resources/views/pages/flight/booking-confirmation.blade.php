@extends('layouts.app')

@section('title', __('Booking Confirmed') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50">
    <!-- Success Header -->
    <div class="bg-[#001F3F] text-white py-12">
        <div class="container mx-auto px-4 text-center">
            <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-4">{{ __('Booking Confirmed!') }}</h1>
            <p class="text-xl text-gray-300">{{ __('Your flight reservation has been successfully processed') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if(!$booking || !$flight_booking)
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <p class="text-red-800">{{ __('Booking not found') }}</p>
            </div>
        @else
        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Duffel Order Confirmation -->
                @if($flight_booking->duffel_order_id)
                    <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('Duffel Booking Confirmed') }}</h2>
                                <p class="text-gray-600 mb-4">{{ __('Your reservation has been registered with Duffel and will appear in your Duffel dashboard.') }}</p>
                                
                                <!-- Duffel References -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-50 rounded-lg">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('Duffel Order ID') }}</p>
                                        <p class="font-mono font-bold text-gray-900">{{ $flight_booking->duffel_order_id }}</p>
                                    </div>
                                    @if($flight_booking->duffel_booking_reference)
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('Booking Reference') }}</p>
                                        <p class="font-mono font-bold text-gray-900">{{ $flight_booking->duffel_booking_reference }}</p>
                                    </div>
                                    @endif
                                </div>
                                
                                @if($duffel_order)
                                <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>{{ __('Status') }}:</strong> {{ ucfirst($duffel_order['status'] ?? 'confirmed') }}
                                    </p>
                                    @if(isset($duffel_order['created_at']))
                                    <p class="text-sm text-blue-700 mt-1">
                                        {{ __('Confirmed on') }}: {{ \Carbon\Carbon::parse($duffel_order['created_at'])->locale(app()->getLocale())->format('d/m/Y H:i') }}
                                    </p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Flight Details -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        {{ __('Flight Details') }}
                    </h2>

                    <!-- Flight Route -->
                    <div class="flex flex-col lg:flex-row lg:items-center gap-6 p-6 bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl mb-6">
                        <!-- Departure -->
                        <div class="flex-1 text-center lg:text-left">
                            <p class="text-3xl font-black text-gray-900">
                                {{ isset($flight_booking->departure_time) ? \Carbon\Carbon::parse($flight_booking->departure_time)->format('H:i') : '--:--' }}
                            </p>
                            <p class="text-xl font-bold text-gray-800">{{ $flight_booking->departure_airport }}</p>
                            <p class="text-gray-600">
                                {{ isset($flight_booking->departure_date) ? \Carbon\Carbon::parse($flight_booking->departure_date)->locale(app()->getLocale())->format('D d M Y') : '' }}
                            </p>
                        </div>

                        <!-- Duration & Stops -->
                        <div class="flex-shrink-0 text-center">
                            <div class="flex items-center gap-2 text-gray-600 mb-2">
                                <span class="text-sm">{{ $flight_booking->duration ?? 'Duration' }}</span>
                            </div>
                            <div class="flex items-center">
                                <div class="h-0.5 bg-gray-300 w-16"></div>
                                <div class="bg-blue-500 rounded-full p-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </div>
                                <div class="h-0.5 bg-gray-300 w-16"></div>
                            </div>
                            <p class="text-sm text-gray-500 mt-2">
                                {{ ($flight_booking->stops ?? 0) == 0 ? __('Direct') : ($flight_booking->stops . ' ' . __('stop(s)')) }}
                            </p>
                        </div>

                        <!-- Arrival -->
                        <div class="flex-1 text-center lg:text-right">
                            <p class="text-3xl font-black text-gray-900">
                                {{ isset($flight_booking->arrival_time) ? \Carbon\Carbon::parse($flight_booking->arrival_time)->format('H:i') : '--:--' }}
                            </p>
                            <p class="text-xl font-bold text-gray-800">{{ $flight_booking->arrival_airport }}</p>
                        </div>
                    </div>

                    <!-- Flight Info -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">{{ __('Flight') }}</p>
                            <p class="font-bold text-gray-900">{{ $flight_booking->flight_number }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">{{ __('Airline') }}</p>
                            <p class="font-bold text-gray-900">{{ $flight_booking->airline }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">{{ __('Class') }}</p>
                            <p class="font-bold text-gray-900">{{ $flight_booking->cabin_class }}</p>
                        </div>
                        <div class="p-3 bg-gray-50 rounded-lg">
                            <p class="text-xs text-gray-500">{{ __('Passengers') }}</p>
                            <p class="font-bold text-gray-900">{{ $flight_booking->passengers_count }}</p>
                        </div>
                    </div>
                </div>

                <!-- Passengers -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        {{ __('Passengers') }}
                    </h2>

                    <div class="space-y-4">
                        @foreach($booking->passenger_details ?? [] as $index => $passenger)
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

                <!-- Price Details -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('Price Summary') }}
                    </h2>

                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('Base fare') }} ({{ $flight_booking->passengers_count }} {{ __('passengers') }})</span>
                            <span>{{ number_format($booking->total_amount, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>{{ __('Taxes & fees') }}</span>
                            <span>{{ number_format($booking->final_amount - $booking->total_amount, 0, ',', ' ') }} XOF</span>
                        </div>
                        <div class="border-t pt-3 flex justify-between">
                            <span class="font-bold text-gray-900">{{ __('Total Paid') }}</span>
                            <span class="font-bold text-xl text-green-600">{{ number_format($booking->final_amount, 0, ',', ' ') }} XOF</span>
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-green-50 rounded-lg">
                        <p class="text-sm text-green-800 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('Payment confirmed') }} - {{ __('Transaction ID') }}: {{ $booking->payment_transaction_id ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky top-6 space-y-6">
                    <!-- Booking Reference -->
                    <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                        <p class="text-sm text-gray-500 mb-2">{{ __('Booking Number') }}</p>
                        <p class="text-2xl font-black text-[#001F3F]">{{ $booking->booking_number }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="font-bold text-gray-900 mb-4">{{ __('Actions') }}</h3>
                        <div class="space-y-3">
                            <a href="{{ route('users.bookings') }}" 
                               class="w-full flex items-center justify-center gap-2 bg-blue-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-blue-700 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ __('View My Bookings') }}
                            </a>
                            <button onclick="window.print()" 
                                    class="w-full flex items-center justify-center gap-2 bg-gray-100 text-gray-700 py-3 px-4 rounded-lg font-medium hover:bg-gray-200 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                {{ __('Print Ticket') }}
                            </button>
                        </div>
                    </div>

                    <!-- Help -->
                    <div class="bg-amber-50 rounded-xl p-6 border border-amber-200">
                        <h3 class="font-bold text-amber-800 mb-2">{{ __('Need Help?') }}</h3>
                        <p class="text-sm text-amber-700 mb-4">
                            {{ __('Contact our support team for any questions about your booking.') }}
                        </p>
                        <a href="{{ route('contact') }}" 
                           class="text-sm text-amber-800 font-medium hover:underline">
                            {{ __('Contact Support') }} →
                        </a>
                    </div>

                    <!-- Duffel Dashboard Link -->
                    @if($flight_booking->duffel_order_id)
                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-blue-800">{{ __('Verify in Duffel Dashboard') }}</p>
                                <p class="text-xs text-blue-600 mt-1">
                                    {{ __('Your booking is registered in the Duffel system. Order ID:') }} 
                                    <code class="bg-white px-1 rounded">{{ substr($flight_booking->duffel_order_id, 0, 8) }}...</code>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
@media print {
    .sticky, header, footer { display: none !important; }
    .min-h-screen { min-height: auto !important; }
    body { background: white !important; }
}
</style>
@endsection

