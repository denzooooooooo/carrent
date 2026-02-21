@extends('admin.layouts.app')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-extrabold text-gray-800">Tableau de Bord des Réservations</h2>
    </div>

    ---

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <div class="bg-white rounded-xl shadow-lg border-l-4 border-blue-500 hover:shadow-xl transition duration-300 transform hover:scale-[1.01]">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-blue-500 uppercase mb-1">Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    </div>
                    <div class="text-blue-200">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-yellow-500 hover:shadow-xl transition duration-300 transform hover:scale-[1.01]">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-yellow-500 uppercase mb-1">En Attente</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</div>
                    </div>
                    <div class="text-yellow-200">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-green-500 hover:shadow-xl transition duration-300 transform hover:scale-[1.01]">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-green-500 uppercase mb-1">Confirmées</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['confirmed'] }}</div>
                    </div>
                    <div class="text-green-200">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg border-l-4 border-indigo-500 hover:shadow-xl transition duration-300 transform hover:scale-[1.01]">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-indigo-500 uppercase mb-1">Revenu Total</div>
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['total_revenue'], 0, ',', ' ') }} <small class="text-sm font-normal text-gray-500">FCFA</small>
                        </div>
                    </div>
                    <div class="text-indigo-200">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    ---

    <div class="bg-white rounded-xl shadow-lg mb-8">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700">
                <i class="fas fa-filter mr-2"></i>Filtres de recherche
            </h3>
        </div>
        <div class="p-4">
            <form method="GET" action="{{ route('admin.bookings.index') }}" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-12 gap-4 items-end">
                    
                    <div class="lg:col-span-3">
                        <label for="search" class="block text-xs font-bold text-gray-500 uppercase mb-1">Recherche</label>
                        <input type="text" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="N°, client, email...">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="booking_type" class="block text-xs font-bold text-gray-500 uppercase mb-1">Type</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" id="booking_type" name="booking_type">
                            <option value="">Tous</option>
                            <option value="flight" {{ request('booking_type') == 'flight' ? 'selected' : '' }}>Vol</option>
                            <option value="event" {{ request('booking_type') == 'event' ? 'selected' : '' }}>Événement</option>
                            <option value="package" {{ request('booking_type') == 'package' ? 'selected' : '' }}>Package</option>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status" class="block text-xs font-bold text-gray-500 uppercase mb-1">Statut</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Attente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="payment_status" class="block text-xs font-bold text-gray-500 uppercase mb-1">Paiement</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" id="payment_status" name="payment_status">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Attente</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Payé</option>
                            <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Échoué</option>
                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                            <option value="partially_paid" {{ request('payment_status') == 'partially_paid' ? 'selected' : '' }}>Partiel</option>
                        </select>
                    </div>

                    <div class="lg:col-span-1">
                        <label for="date_from" class="block text-xs font-bold text-gray-500 uppercase mb-1">Du</label>
                        <input type="date" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="lg:col-span-1">
                        <label for="date_to" class="block text-xs font-bold text-gray-500 uppercase mb-1">Au</label>
                        <input type="date" 
                               class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="lg:col-span-1 flex justify-end space-x-2">
                        <button type="submit" class="p-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-150" title="Rechercher">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="p-2 text-sm font-medium text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-100 focus:outline-none transition duration-150" title="Réinitialiser">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    ---

    @if(session('success'))
        <div class="relative px-4 py-3 mb-6 leading-normal text-green-700 bg-green-100 rounded-lg shadow-md" role="alert">
            <p class="font-semibold"><i class="fas fa-check-circle mr-2"></i> Succès :</p>
            <p>{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="relative px-4 py-3 mb-6 leading-normal text-red-700 bg-red-100 rounded-lg shadow-md" role="alert">
            <p class="font-semibold"><i class="fas fa-exclamation-circle mr-2"></i> Erreur :</p>
            <p>{{ session('error') }}</p>
        </div>
    @endif

    ---

    <div class="bg-white rounded-xl shadow-lg mb-8">
        <div class="p-4 border-b border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700">
                <i class="fas fa-list-ul mr-2"></i>Liste des réservations
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">N° Réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Réservation</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date Voyage</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paiement</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($bookings as $booking)
                        <tr class="hover:bg-gray-50 transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">{{ $booking->booking_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->user->email ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @switch($booking->booking_type)
                                    @case('flight')
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-plane mr-1"></i> Vol
                                        </span>
                                        @break
                                    @case('event')
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                            <i class="fas fa-calendar-alt mr-1"></i> Événement
                                        </span>
                                        @break
                                    @case('package')
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-suitcase mr-1"></i> Package
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $booking->booking_type }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $booking->booking_date->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800">{{ $booking->travel_date ? $booking->travel_date->format('d/m/Y') : 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-right">
                                <span class="font-bold text-gray-900">
                                    {{ number_format($booking->final_amount, 0, ',', ' ') }}
                                </span> 
                                <small class="text-gray-500">{{ $booking->currency }}</small>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @switch($booking->status)
                                    @case('pending')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
                                        @break
                                    @case('confirmed')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Confirmé</span>
                                        @break
                                    @case('cancelled')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Annulé</span>
                                        @break
                                    @case('completed')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">Terminé</span>
                                        @break
                                    @default
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $booking->status }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @switch($booking->payment_status)
                                    @case('pending')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium border border-yellow-500 text-yellow-700">En attente</span>
                                        @break
                                    @case('paid')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium border border-green-500 text-green-700">Payé</span>
                                        @break
                                    @case('failed')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium border border-red-500 text-red-700">Échoué</span>
                                        @break
                                    @case('refunded')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium border border-indigo-500 text-indigo-700">Remboursé</span>
                                        @break
                                    @case('partially_paid')
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium border border-gray-500 text-gray-700">Partiel</span>
                                        @break
                                    @default
                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium border border-gray-500 text-gray-700">{{ $booking->payment_status }}</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('admin.bookings.show', $booking->id) }}" 
                                       class="text-indigo-600 hover:text-indigo-900 p-2 rounded-full hover:bg-gray-100"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($booking->status === 'pending')
                                        <a href="#" 
                                           class="text-green-600 hover:text-green-900 p-2 rounded-full hover:bg-gray-100"
                                           title="Confirmer la réservation">
                                            <i class="fas fa-check"></i>
                                        </a>
                                    @endif

                                    <form action="{{ route('admin.bookings.destroy', $booking->id) }}" 
                                          method="POST" 
                                          class="inline-block"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 p-2 rounded-full hover:bg-gray-100"
                                                title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <i class="fas fa-inbox fa-3x text-gray-200 mb-3 block"></i>
                                <p>Aucune réservation trouvée pour les filtres actuels.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-4 px-6 py-4 border-t border-gray-100">
            <div class="text-sm text-gray-600">
                Affichage de **{{ $bookings->firstItem() ?? 0 }}** à **{{ $bookings->lastItem() ?? 0 }}** sur **{{ $bookings->total() }}** réservations
            </div>
            <div>
                {{ $bookings->links('pagination::tailwind') }} 
                </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@endpush