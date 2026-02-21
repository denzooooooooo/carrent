@extends('admin.layouts.app')

@section('title', 'Gestion des Vols')

@section('content')
<main class="flex-1 overflow-y-auto p-4 md:p-6 page-transition">
    @if(session('success'))
        <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 text-green-700 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-2xl mr-3"></i>
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 text-red-700 rounded-lg shadow-md animate-fade-in">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-2xl mr-3"></i>
                <p class="font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-900">Gestion des Vols</h1>
                <p class="text-gray-600 mt-2">Gérez toutes les réservations de vols et les orders Duffel</p>
            </div>
            <a href="{{ route('admin.flights.export') }}" class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg">
                <i class="fas fa-file-export"></i>
                <span class="font-medium">Exporter CSV</span>
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm font-semibold mb-1">Total Réservations</p>
                    <h3 class="text-4xl font-black">{{ $stats['total'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-plane text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Confirmés -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm font-semibold mb-1">Confirmés</p>
                    <h3 class="text-4xl font-black">{{ $stats['confirmed'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- En Attente -->
        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-amber-100 text-sm font-semibold mb-1">En Attente</p>
                    <h3 class="text-4xl font-black">{{ $stats['pending'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Revenus du Mois -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-all">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm font-semibold mb-1">Revenus Mois</p>
                    <h3 class="text-3xl font-black">{{ number_format($stats['revenue_month'], 0, ',', ' ') }}</h3>
                    <p class="text-blue-100 text-xs">XOF</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center backdrop-blur-sm">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Duffel -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Avec Order Duffel</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['with_duffel_order'] }}</h4>
                    <p class="text-xs text-gray-500 mt-1">Billets confirmés</p>
                </div>
                <i class="fas fa-ticket-alt text-3xl text-green-500"></i>
            </div>
        </div>

        <div class="glass rounded-xl shadow-sm p-6 border-l-4 border-red-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-600">Sans Order Duffel</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $stats['without_duffel_order'] }}</h4>
                    <p class="text-xs text-gray-500 mt-1">Payés mais non confirmés</p>
                </div>
                <i class="fas fa-exclamation-triangle text-3xl text-red-500"></i>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="glass rounded-2xl shadow-lg p-6 mb-8">
        <form method="GET" action="{{ route('admin.flights.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="N° réservation, PNR, vol..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>

                <!-- Statut -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Statut</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                </div>

                <!-- Paiement -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Paiement</label>
                    <select name="payment_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Tous</option>
                        <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                        <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                    </select>
                </div>

                <!-- Date -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Date</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                </div>
            </div>

            <div class="flex items-center space-x-4">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-medium">
                    <i class="fas fa-search mr-2"></i>Filtrer
                </button>
                <a href="{{ route('admin.flights.index') }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-all font-medium">
                    <i class="fas fa-redo mr-2"></i>Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des Réservations -->
    <div class="glass rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-purple-600 to-purple-700 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-bold">N° Réservation</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">PNR Duffel</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Client</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Vol</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Route</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Date</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Montant</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Statut</th>
                        <th class="px-6 py-4 text-left text-sm font-bold">Paiement</th>
                        <th class="px-6 py-4 text-center text-sm font-bold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-purple-50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold text-purple-600">{{ $booking->booking_number }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->flightBooking && $booking->flightBooking->duffel_booking_reference)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        {{ $booking->flightBooking->duffel_booking_reference }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600">
                                        <i class="fas fa-minus-circle mr-1"></i>
                                        N/A
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">{{ $booking->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->passenger_details[0]['email'] ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="font-mono text-sm font-bold">{{ $booking->flightBooking->flight_number ?? 'N/A' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2 text-sm">
                                    <span class="font-bold">{{ $booking->flightBooking->departure_airport ?? 'N/A' }}</span>
                                    <i class="fas fa-arrow-right text-gray-400"></i>
                                    <span class="font-bold">{{ $booking->flightBooking->arrival_airport ?? 'N/A' }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $booking->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->created_at->format('H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ number_format($booking->final_amount, 0, ',', ' ') }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->currency }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status == 'confirmed')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <i class="fas fa-check-circle mr-1"></i>Confirmé
                                    </span>
                                @elseif($booking->status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        <i class="fas fa-times-circle mr-1"></i>Annulé
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->payment_status == 'paid')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Payé
                                    </span>
                                @elseif($booking->payment_status == 'pending')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800">
                                        <i class="fas fa-clock mr-1"></i>En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-800">
                                        <i class="fas fa-times mr-1"></i>Échoué
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('admin.flights.show', $booking->id) }}" class="p-2 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg transition-colors" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($booking->payment_status == 'paid' && (!$booking->flightBooking || !$booking->flightBooking->duffel_order_id))
                                        <form action="{{ route('admin.flights.create-duffel-order', $booking->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-2 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg transition-colors" title="Créer Order Duffel" onclick="return confirm('Créer un order Duffel pour cette réservation?')">
                                                <i class="fas fa-plus-circle"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-plane-slash text-6xl text-gray-300 mb-4"></i>
                                    <p class="text-gray-500 text-lg font-semibold">Aucune réservation de vol trouvée</p>
                                    <p class="text-gray-400 text-sm mt-2">Les réservations apparaîtront ici</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
</main>
@endsection
