@extends('layouts.app')

@section('title', __('My Flight Bookings - Duffel v2') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
    {{-- Hero Section --}}
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h1 class="text-3xl lg:text-4xl font-black mb-2">{{ __('My Flight Bookings') }}</h1>
            <p class="text-lg opacity-90">
                {{ __('Manage your flight reservations and booking history') }}
            </p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @auth
            @if(isset($bookings) && $bookings->count() > 0)
                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-gray-900">{{ $bookings->count() }}</p>
                                <p class="text-sm text-gray-600">{{ __('Total Bookings') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-gray-900">
                                    {{ $bookings->where('status', 'confirmed')->count() }}
                                </p>
                                <p class="text-sm text-gray-600">{{ __('Confirmed') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-gray-900">
                                    {{ $bookings->where('status', 'pending')->count() }}
                                </p>
                                <p class="text-sm text-gray-600">{{ __('Pending') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-200">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-2xl font-black text-gray-900">
                                    {{ $bookings->where('status', 'cancelled')->count() }}
                                </p>
                                <p class="text-sm text-gray-600">{{ __('Cancelled') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bookings List --}}
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        @php
                            $flightBooking = $booking->flightBooking;
                            $statusColors = [
                                'confirmed' => 'bg-green-100 text-green-700 border-green-200',
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'cancelled' => 'bg-red-100 text-red-700 border-red-200',
                                'failed' => 'bg-red-100 text-red-700 border-red-200',
                            ];
                            $statusLabels = [
                                'confirmed' => __('Confirmed'),
                                'pending' => __('Pending'),
                                'cancelled' => __('Cancelled'),
                                'failed' => __('Failed'),
                            ];
                        @endphp

                        <div class="bg-white rounded-2xl shadow-xl border-2 border-purple-100 overflow-hidden">
                            {{-- Header --}}
                            <div class="bg-gradient-to-r from-purple-600 to-purple-700 text-white p-6">
                                <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
                                    <div>
                                        <h3 class="text-xl font-black">
                                            {{ $flightBooking->departure_airport ?? 'N/A' }} → {{ $flightBooking->arrival_airport ?? 'N/A' }}
                                        </h3>
                                        <p class="text-purple-100">
                                            {{ $flightBooking->airline ?? 'N/A' }} {{ $flightBooking->flight_number ?? '' }}
                                            • {{ isset($flightBooking->departure_date) ? \Carbon\Carbon::parse($flightBooking->departure_date)->format('l, d F Y') : 'N/A' }}
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full text-sm font-bold {{ $statusColors[$booking->status] ?? 'bg-gray-100 text-gray-700 border-gray-200' }}">
                                            {{ $statusLabels[$booking->status] ?? ucfirst($booking->status) }}
                                        </span>
                                        <span class="bg-white/20 text-white px-3 py-1 rounded-full text-sm font-bold">
                                            {{ __('Booking') }} #{{ $booking->id }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="p-6">
                                {{-- Airline Initiated Change Alert (Duffel v2) --}}
                                @php
                                    $hasAirlineChange = false;
                                    $changeRequestStatus = null;
                                    
                                    // Check for airline initiated change flags
                                    if($flightBooking) {
                                        // Check metadata or additional fields for change indicators
                                        $metadata = $flightBooking->metadata ?? [];
                                        $hasAirlineChange = isset($metadata['airline_initiated_change']) && $metadata['airline_initiated_change'];
                                        $changeRequestStatus = $metadata['change_request_status'] ?? null;
                                    }
                                @endphp
                                
                                @if($hasAirlineChange || $changeRequestStatus === 'pending')
                                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-bold text-amber-800">{{ __('Changement compagnie aérienne') }}</h4>
                                                <p class="text-sm text-amber-700 mt-1">
                                                    {{ __('La compagnie aérienne a initié un changement sur votre réservation.') }}
                                                </p>
                                                @if($changeRequestStatus === 'pending')
                                                    <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                                        <svg class="w-3 h-3 mr-1 animate-spin" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        {{ __('En attente de confirmation') }}
                                                    </div>
                                                @endif
                                                <a href="{{ route('flights.modify', $flightBooking->duffel_order_id ?? $booking->id) }}" 
                                                   class="inline-block mt-3 text-sm font-semibold text-amber-800 hover:text-amber-900 underline">
                                                    {{ __('Voir les options de modification') }} →
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                {{-- Order Change Request Status --}}
                                @if($flightBooking && ($flightBooking->pending_change_request_id || $changeRequestStatus))
                                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-6">
                                        <div class="flex items-start gap-3">
                                            <div class="flex-shrink-0 mt-0.5">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-bold text-blue-800">{{ __('Demande de modification en cours') }}</h4>
                                                <p class="text-sm text-blue-700 mt-1">
                                                    {{ __('Votre demande de modification est en cours de traitement.') }}
                                                </p>
                                                @if($flightBooking->pending_change_request_id)
                                                    <p class="text-xs text-blue-600 mt-2 font-mono">
                                                        ID: {{ $flightBooking->pending_change_request_id }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                    {{-- Flight Details --}}
                                    <div class="lg:col-span-2">
                                        <div class="bg-gray-50 rounded-xl p-4 border border-purple-200">
                                            <div class="flex items-center gap-4 mb-4">
                                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                                    <span class="text-lg font-bold text-purple-700">
                                                        {{ substr($flightBooking->airline ?? 'XX', 0, 2) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-gray-900">{{ $flightBooking->airline ?? 'Airline' }}</h4>
                                                    <p class="text-sm text-gray-600">{{ __('Flight') }} {{ $flightBooking->flight_number ?? 'N/A' }}</p>
                                                </div>
                                            </div>

                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                                                <div>
                                                    <p class="text-gray-500">{{ __('From') }}</p>
                                                    <p class="font-bold text-gray-900">{{ $flightBooking->departure_airport ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">{{ __('To') }}</p>
                                                    <p class="font-bold text-gray-900">{{ $flightBooking->arrival_airport ?? 'N/A' }}</p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">{{ __('Date') }}</p>
                                                    <p class="font-bold text-gray-900">
                                                        {{ isset($flightBooking->departure_date) ? \Carbon\Carbon::parse($flightBooking->departure_date)->format('d/m/Y') : 'N/A' }}
                                                    </p>
                                                </div>
                                                <div>
                                                    <p class="text-gray-500">{{ __('Passengers') }}</p>
                                                    <p class="font-bold text-gray-900">{{ $booking->passenger_details ? count($booking->passenger_details) : 1 }}</p>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Duffel Order Info --}}
                                        @if($flightBooking && ($flightBooking->duffel_order_id || $flightBooking->duffel_booking_reference))
                                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 mt-4">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="font-bold text-blue-700">{{ __('Duffel API v2') }}</span>
                                                </div>
                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    @if($flightBooking->duffel_order_id)
                                                        <div>
                                                            <p class="text-blue-600">{{ __('Order ID') }}</p>
                                                            <p class="font-bold text-blue-900">{{ $flightBooking->duffel_order_id }}</p>
                                                        </div>
                                                    @endif
                                                    @if($flightBooking->duffel_booking_reference)
                                                        <div>
                                                            <p class="text-blue-600">{{ __('Booking Ref') }}</p>
                                                            <p class="font-bold text-blue-900">{{ $flightBooking->duffel_booking_reference }}</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Actions & Price --}}
                                    <div>
                                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200 mb-4">
                                            <div class="text-center">
                                                <p class="text-sm text-green-600">{{ __('Total Paid') }}</p>
                                                <p class="text-2xl font-black text-green-700">
                                                    {{ number_format($booking->total_amount ?? 0, 0, ',', ' ') }} XOF
                                                </p>
                                                @if($booking->commission_amount > 0)
                                                    <p class="text-xs text-green-600 mt-1">
                                                        {{ __('Commission') }}: {{ number_format($booking->commission_amount, 0, ',', ' ') }} XOF
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Actions --}}
                                        <div class="space-y-2">
                                            <a href="{{ route('flights.confirmation', $booking->id) }}"
                                                class="w-full text-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg flex items-center justify-center gap-2">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                <span>{{ __('View Details') }}</span>
                                            </a>

                                            @if($booking->status === 'confirmed')
                                                <form action="{{ route('flights.modify', $flightBooking->duffel_order_id ?? $booking->id) }}" method="GET" class="inline-block w-full">
                                                    <button type="submit"
                                                        class="w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 text-white px-4 py-3 rounded-xl font-bold hover:from-blue-700 hover:to-blue-800 transition-all duration-200 shadow-lg flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                        <span>{{ __('Modify') }}</span>
                                                    </button>
                                                </form>

                                                <form action="{{ route('flights.cancel', $flightBooking->duffel_order_id ?? $booking->id) }}" method="POST" class="inline-block w-full"
                                                      onsubmit="return confirm('{{ __('Are you sure you want to cancel this booking?') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="w-full text-center bg-gradient-to-r from-red-600 to-red-700 text-white px-4 py-3 rounded-xl font-bold hover:from-red-700 hover:to-red-800 transition-all duration-200 shadow-lg flex items-center justify-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                        </svg>
                                                        <span>{{ __('Cancel') }}</span>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($bookings->hasPages())
                    <div class="mt-8">
                        {{ $bookings->links() }}
                    </div>
                @endif

            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-purple-100">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-black text-gray-900 mb-2">{{ __('No Flight Bookings Yet') }}</h2>
                        <p class="text-gray-600 mb-6">{{ __('You haven\'t made any flight bookings yet. Start exploring amazing destinations!') }}</p>
                        <a href="{{ route('flights.search') }}"
                            class="inline-block bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg">
                            {{ __('Search Flights') }}
                        </a>
                    </div>
                </div>
            @endif
        @else
            {{-- Not Authenticated --}}
            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-red-100">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="text-2xl font-black text-gray-900 mb-2">{{ __('Authentication Required') }}</h2>
                    <p class="text-gray-600 mb-6">{{ __('Please log in to view your flight bookings.') }}</p>
                    <a href="{{ route('login') }}"
                        class="inline-block bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-3 rounded-xl font-bold hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-lg">
                        {{ __('Log In') }}
                    </a>
                </div>
            </div>
        @endauth

        {{-- Duffel API Info --}}
        <div class="bg-blue-50 rounded-xl p-4 border border-blue-200 mt-8">
            <div class="flex items-center gap-2 mb-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-bold text-blue-700">{{ __('Powered by Duffel API v2') }}</span>
            </div>
            <p class="text-sm text-blue-600">
                {{ __('All flight bookings are managed through Duffel\'s advanced API with real-time updates, commission tracking, and webhook integration.') }}
            </p>
        </div>
    </div>
</div>
@endsection
