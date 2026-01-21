@extends('layouts.app')

@section('title', __('Confirmation de réservation'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 max-w-4xl">
        <!-- Success Message -->
        <div class="bg-green-50 dark:bg-green-900/20 border-2 border-green-500 dark:border-green-700 rounded-lg p-8 mb-8 text-center">
            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                Réservation confirmée !
            </h1>
            <p class="text-gray-700 dark:text-gray-300">
                Votre vol a été réservé avec succès
            </p>
        </div>

        <!-- Booking Details -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6">Détails de la réservation</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Numéro de réservation -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Numéro de réservation</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">{{ $booking->booking_number }}</p>
                </div>

                <!-- Booking Reference Duffel -->
                @if($flight_booking && $flight_booking->duffel_booking_reference)
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Référence Duffel (PNR)</p>
                    <p class="text-lg font-bold text-blue-600 dark:text-blue-400">
                        {{ $flight_booking->duffel_booking_reference }}
                    </p>
                </div>
                @endif

                <!-- Statut -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Statut</p>
                    <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                        @if($booking->status === 'confirmed') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                        @else bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400 @endif">
                        {{ ucfirst($booking->status) }}
                    </span>
                </div>

                <!-- Montant -->
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Montant payé</p>
                    <p class="text-lg font-bold text-gray-900 dark:text-white">
                        {{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}
                    </p>
                </div>
            </div>

            <!-- Flight Details -->
            @if($flight_booking)
            <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Informations du vol</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Vol</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $flight_booking->flight_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Compagnie</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $flight_booking->airline }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Itinéraire</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $flight_booking->departure_airport }} → {{ $flight_booking->arrival_airport }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Date de départ</p>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ \Carbon\Carbon::parse($flight_booking->departure_time)->format('d/m/Y H:i') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Classe</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $flight_booking->cabin_class }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Passagers</p>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $flight_booking->passengers_count }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Duffel Order Info -->
            @if($duffel_order)
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        ✅ Votre réservation a été confirmée auprès de la compagnie aérienne via Duffel.
                        Order ID: <span class="font-mono">{{ $duffel_order['id'] ?? 'N/A' }}</span>
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Passagers -->
        @if($booking->passenger_details && count($booking->passenger_details) > 0)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Passagers</h2>
            
            <div class="space-y-3">
                @foreach($booking->passenger_details as $index => $passenger)
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">
                            {{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ ucfirst($passenger['type'] ?? 'adult') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $passenger['email'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Prochaines étapes</h2>
            
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-gray-700 dark:text-gray-300">
                        Un email de confirmation a été envoyé à votre adresse
                    </p>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-gray-700 dark:text-gray-300">
                        Conservez votre numéro de réservation pour toute correspondance
                    </p>
                </div>
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <p class="text-gray-700 dark:text-gray-300">
                        Présentez-vous à l'aéroport 2-3 heures avant le départ
                    </p>
                </div>
            </div>

            <div class="mt-6 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('flights.index') }}" 
                   class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white text-center font-medium rounded-lg transition-colors">
                    Nouvelle recherche
                </a>
                @auth
                <a href="{{ route('bookings') }}" 
                   class="flex-1 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 text-center font-medium rounded-lg transition-colors">
                    Mes réservations
                </a>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection
