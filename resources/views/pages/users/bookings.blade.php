@extends('layouts.app')

@section('title', 'Mes Réservations - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-50 via-white to-amber-50 py-12">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto">
            <!-- Header -->
            <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 border border-purple-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-200 to-transparent rounded-full -mr-16 -mt-16"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h1 class="text-4xl font-black text-gray-900 mb-2 bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">Mes Réservations</h1>
                        <p class="text-gray-600 text-lg">Consultez et gérez toutes vos réservations</p>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-center bg-gradient-to-r from-purple-100 to-amber-100 rounded-2xl p-4 border border-purple-200">
                            <p class="text-sm text-gray-600 font-medium">Total des réservations</p>
                            <p class="text-3xl font-black text-purple-600">{{ $bookings->count() }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-600 to-amber-600 rounded-full flex items-center justify-center shadow-lg">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-3xl shadow-xl p-6 mb-8 border border-purple-100">
                <div class="flex flex-wrap items-center gap-6">
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            <label for="status_filter" class="text-sm font-bold text-gray-700">Filtrer par statut:</label>
                        </div>
                        <select id="status_filter" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white hover:border-purple-400">
                            <option value="">Tous les statuts</option>
                            <option value="confirmed">Confirmé</option>
                            <option value="pending">En attente</option>
                            <option value="cancelled">Annulé</option>
                            <option value="completed">Terminé</option>
                        </select>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <label for="type_filter" class="text-sm font-bold text-gray-700">Type:</label>
                        </div>
                        <select id="type_filter" class="px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 bg-white hover:border-purple-400">
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
                        <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-purple-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1" data-booking data-status="{{ $booking->status }}" data-type="{{ $booking->type }}">
                            <!-- Booking Header -->
                            <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 p-6 text-white relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-10 rounded-full -mr-12 -mt-12"></div>
                                <div class="relative z-10 flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-white bg-opacity-20 rounded-xl flex items-center justify-center">
                                            @if($booking->type === 'event')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                            @elseif($booking->type === 'package')
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                            @else
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold">{{ $booking->title }}</h3>
                                            <p class="text-purple-100">Réservation #{{ $booking->id }}</p>
                                        </div>
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
                                                <div class="flex justify-between items-center">
                                                    <span class="text-gray-600 flex items-center">
                                                        <svg class="w-4 h-4 mr-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Statut:
                                                    </span>
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold flex items-center space-x-1
                                                        @if($booking->status === 'confirmed') bg-gradient-to-r from-green-100 to-green-200 text-green-800 border border-green-300
                                                        @elseif($booking->status === 'pending') bg-gradient-to-r from-yellow-100 to-yellow-200 text-yellow-800 border border-yellow-300
                                                        @elseif($booking->status === 'cancelled') bg-gradient-to-r from-red-100 to-red-200 text-red-800 border border-red-300
                                                        @else bg-gradient-to-r from-gray-100 to-gray-200 text-gray-800 border border-gray-300 @endif">
                                                        @if($booking->status === 'confirmed')
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @elseif($booking->status === 'pending')
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @elseif($booking->status === 'cancelled')
                                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                            </svg>
                                                        @endif
                                                        <span>{{ ucfirst($booking->status) }}</span>
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
                                        <div class="space-y-3">
                                                <button class="w-full px-4 py-3 bg-gradient-to-r from-purple-600 to-purple-700 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                    <span>Voir les détails</span>
                                                </button>
                                                @if($booking->status === 'confirmed')
                                                    <button class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                        </svg>
                                                        <span>Télécharger le billet</span>
                                                    </button>
                                                @endif
                                                @if(in_array($booking->status, ['confirmed', 'pending']))
                                                    <button class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white text-sm font-bold rounded-xl hover:shadow-lg hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                        <span>Annuler la réservation</span>
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
                <div class="bg-white rounded-3xl shadow-xl p-16 text-center border border-purple-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-purple-200 to-transparent rounded-full -mr-16 -mt-16"></div>
                    <div class="relative z-10">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-100 to-amber-100 rounded-full flex items-center justify-center mx-auto mb-8">
                            <svg class="w-16 h-16 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                        </div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Aucune réservation trouvée</h3>
                        <p class="text-gray-600 text-lg mb-8 max-w-md mx-auto">Vous n'avez pas encore effectué de réservation. Découvrez nos offres exceptionnelles et commencez votre aventure !</p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a href="{{ route('events') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-purple-700 text-white font-bold rounded-2xl hover:shadow-xl hover:scale-105 transition-all duration-300 space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <span>Découvrir les événements</span>
                            </a>
                            <a href="{{ route('packages') }}" class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-amber-600 to-orange-600 text-black font-bold rounded-2xl hover:shadow-xl hover:scale-105 transition-all duration-300 space-x-2 border-2 border-amber-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                                <span>Explorer les packages</span>
                            </a>
                        </div>
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
