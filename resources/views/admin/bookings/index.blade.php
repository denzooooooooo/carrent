@extends('admin.layouts.app')

@section('title', 'Gestion des Réservations')

@section('content')
<div class="mx-auto max-w-7xl space-y-8">

    <section class="admin-page-header">
        <div>
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-purple-600">Operations</p>
            <h2 class="mt-2 text-3xl font-extrabold text-gray-900">Tableau de bord des réservations</h2>
            <p class="mt-3 max-w-2xl text-gray-600">Suivi des volumes, état des paiements et contrôle rapide des réservations de la plateforme.</p>
        </div>
        <div class="admin-btn-ghost px-5 py-3 text-sm">
            <i class="fas fa-calendar-day"></i>
            {{ now()->format('d/m/Y') }}
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <div class="admin-kpi p-5 border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-purple-600 uppercase mb-1 tracking-[0.16em]">Total</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</div>
                    </div>
                    <div class="text-purple-200">
                        <i class="fas fa-calendar-alt fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-kpi admin-kpi-accent p-5 border-l-4 border-amber-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-amber-700 uppercase mb-1 tracking-[0.16em]">En Attente</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['pending'] }}</div>
                    </div>
                    <div class="text-amber-200">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-kpi p-5 border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-green-600 uppercase mb-1 tracking-[0.16em]">Confirmées</div>
                        <div class="text-2xl font-bold text-gray-900">{{ $stats['confirmed'] }}</div>
                    </div>
                    <div class="text-green-200">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="admin-kpi p-5 border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center justify-between">
                    <div class="flex-grow">
                        <div class="text-sm font-bold text-purple-600 uppercase mb-1 tracking-[0.16em]">Revenu Total</div>
                        <div class="text-2xl font-bold text-gray-900">
                            {{ number_format($stats['total_revenue'], 0, ',', ' ') }} <small class="text-sm font-normal text-gray-500">FCFA</small>
                        </div>
                    </div>
                    <div class="text-purple-200">
                        <i class="fas fa-dollar-sign fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-panel overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-filter mr-2"></i>Filtres de recherche
            </h3>
            <span class="rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-700">Analyse rapide</span>
        </div>
        <div class="p-6">
            <form method="GET" action="{{ route('admin.bookings.index') }}" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-12 gap-4 items-end">
                    
                    <div class="lg:col-span-3">
                        <label for="search" class="block text-xs font-bold text-gray-500 uppercase mb-1">Recherche</label>
                        <input type="text" 
                               class="w-full px-3 py-3 text-sm border border-gray-300 rounded-2xl" 
                               id="search" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="N°, client, email...">
                    </div>

                    <div class="lg:col-span-2">
                        <label for="booking_type" class="block text-xs font-bold text-gray-500 uppercase mb-1">Type</label>
                        <select class="w-full px-3 py-3 text-sm border border-gray-300 rounded-2xl" id="booking_type" name="booking_type">
                            <option value="">Tous</option>
                            <option value="flight" {{ request('booking_type') == 'flight' ? 'selected' : '' }}>Vol</option>
                            <option value="event" {{ request('booking_type') == 'event' ? 'selected' : '' }}>Événement</option>
                            <option value="package" {{ request('booking_type') == 'package' ? 'selected' : '' }}>Package</option>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="status" class="block text-xs font-bold text-gray-500 uppercase mb-1">Statut</label>
                        <select class="w-full px-3 py-3 text-sm border border-gray-300 rounded-2xl" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Attente</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <label for="payment_status" class="block text-xs font-bold text-gray-500 uppercase mb-1">Paiement</label>
                        <select class="w-full px-3 py-3 text-sm border border-gray-300 rounded-2xl" id="payment_status" name="payment_status">
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
                               class="w-full px-3 py-3 text-sm border border-gray-300 rounded-2xl" 
                               id="date_from" 
                               name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>

                    <div class="lg:col-span-1">
                        <label for="date_to" class="block text-xs font-bold text-gray-500 uppercase mb-1">Au</label>
                        <input type="date" 
                               class="w-full px-3 py-3 text-sm border border-gray-300 rounded-2xl" 
                               id="date_to" 
                               name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>

                    <div class="lg:col-span-1 flex justify-end space-x-2">
                        <button type="submit" class="admin-btn-primary h-12 w-12 text-sm" title="Rechercher">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="{{ route('admin.bookings.index') }}" class="admin-btn-ghost h-12 w-12 text-sm" title="Réinitialiser">
                            <i class="fas fa-redo"></i>
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="admin-panel overflow-hidden">
        <div class="flex items-center justify-between border-b border-gray-100 p-6">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-list-ul mr-2"></i>Liste des réservations
            </h3>
            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">{{ $bookings->total() }} entrée(s)</span>
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
                        <tr class="transition duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-purple-700">{{ $booking->booking_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $booking->customer_name }}</div>
                                <div class="text-xs text-gray-500">{{ $booking->customer_email ?? 'N/A' }}</div>
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
                                       class="text-purple-700 hover:text-purple-900 p-2 rounded-full hover:bg-gray-100"
                                       title="Voir les détails">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    @if($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit"
                                                class="text-green-600 hover:text-green-900 p-2 rounded-full hover:bg-gray-100"
                                                title="Confirmer la réservation">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
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

        <div class="flex flex-col gap-3 border-t border-gray-100 px-6 py-4 text-sm text-gray-600 md:flex-row md:items-center md:justify-between">
            <div class="text-sm text-gray-600">
                Affichage de <strong>{{ $bookings->firstItem() ?? 0 }}</strong> à <strong>{{ $bookings->lastItem() ?? 0 }}</strong> sur <strong>{{ $bookings->total() }}</strong> réservations
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
