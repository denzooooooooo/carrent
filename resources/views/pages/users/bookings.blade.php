@extends('layouts.app')

@section('title', 'Mes Réservations - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-black text-gray-900 mb-2">Mes Réservations</h1>
                        <p class="text-gray-600">Consultez et gérez toutes vos réservations</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm text-gray-500">Total des réservations</p>
                            <p class="text-2xl font-bold text-purple-600">{{ $bookings->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center space-x-2">
                        <label for="status_filter" class="text-sm font-semibold text-gray-700">Filtrer par statut:</label>
                        <select id="status_filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Tous les statuts</option>
                            <option value="confirmed">Confirmé</option>
                            <option value="pending">En attente</option>
                            <option value="cancelled">Annulé</option>
                            <option value="completed">Terminé</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-2">
                        <label for="type_filter" class="text-sm font-semibold text-gray-700">Type:</label>
                        <select id="type_filter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="">Tous les types</option>
                            <option value="event">Événements</option>
                            <option value="package">Packages</option>
                            <option value="flight">Vols</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Bookings List -->
            @if($bookings->count() > 0)
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                            <!-- Booking Header -->
                            <div class="bg-gradient-to-r from-purple-600 to-purple-700 p-6 text-white">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-xl font-bold">{{ $booking->title }}</h3>
                                        <p class="text-purple-100">Réservation #{{ $booking->id }}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($booking->total_amount)) }}</div>
                                        <div class="text-sm text-purple-100">{{ $booking->created_at->format('d/m/Y') }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Booking Details -->
                            <div class="p-6">
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <!-- Booking Info -->
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Informations de réservation</h4>
                                            <div class="space-y-2 text-sm">
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Type:</span>
                                                    <span class="font-medium capitalize">{{ $booking->type }}</span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Statut:</span>
                                                    <span class="px-2 py-1 rounded-full text-xs font-medium
                                                        @if($booking->status === 'confirmed') bg-green-100 text-green-800
                                                        @elseif($booking->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </div>
                                                <div class="flex justify-between">
                                                    <span class="text-gray-600">Date de réservation:</span>
                                                    <span class="font-medium">{{ $booking->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Event/Package Details -->
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Détails</h4>
                                            <div class="space-y-2 text-sm">
                                                @if($booking->type === 'event')
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Événement:</span>
                                                        <span class="font-medium">{{ $booking->event->title ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Date:</span>
                                                        <span class="font-medium">{{ $booking->event->start_date ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Lieu:</span>
                                                        <span class="font-medium">{{ $booking->event->location ?? 'N/A' }}</span>
                                                    </div>
                                                @elseif($booking->type === 'package')
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Package:</span>
                                                        <span class="font-medium">{{ $booking->package->title ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Durée:</span>
                                                        <span class="font-medium">{{ $booking->package->duration ?? 'N/A' }} jours</span>
                                                    </div>
                                                @elseif($booking->type === 'flight')
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Vol:</span>
                                                        <span class="font-medium">{{ $booking->flight->flight_number ?? 'N/A' }}</span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600">Départ:</span>
                                                        <span class="font-medium">{{ $booking->flight->departure_date ?? 'N/A' }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Actions -->
                                    <div class="space-y-4">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-2">Actions</h4>
                                            <div class="space-y-2">
                                                <button class="w-full px-4 py-2 bg-purple-600 text-white text-sm font-medium rounded-lg hover:bg-purple-700 transition-colors">
                                                    Voir les détails
                                                </button>
                                                @if($booking->status === 'confirmed')
                                                    <button class="w-full px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors">
                                                        Télécharger le billet
                                                    </button>
                                                @endif
                                                @if(in_array($booking->status, ['confirmed', 'pending']))
                                                    <button class="w-full px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                                                        Annuler la réservation
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-8">
                    {{ $bookings->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Aucune réservation trouvée</h3>
                    <p class="text-gray-600 mb-6">Vous n'avez pas encore effectué de réservation. Commencez votre aventure avec Carré Premium !</p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('events') }}" class="px-6 py-3 bg-purple-600 text-white font-semibold rounded-lg hover:bg-purple-700 transition-colors">
                            Découvrir les événements
                        </a>
                        <a href="{{ route('packages') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-colors">
                            Explorer les packages
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status_filter');
    const typeFilter = document.getElementById('type_filter');

    function filterBookings() {
        const statusValue = statusFilter.value.toLowerCase();
        const typeValue = typeFilter.value.toLowerCase();

        const bookings = document.querySelectorAll('[data-booking]');

        bookings.forEach(booking => {
            const bookingStatus = booking.dataset.status.toLowerCase();
            const bookingType = booking.dataset.type.toLowerCase();

            const statusMatch = !statusValue || bookingStatus === statusValue;
            const typeMatch = !typeValue || bookingType === typeValue;

            if (statusMatch && typeMatch) {
                booking.style.display = 'block';
            } else {
                booking.style.display = 'none';
            }
        });
    }

    statusFilter.addEventListener('change', filterBookings);
    typeFilter.addEventListener('change', filterBookings);
});
</script>
@endsection
