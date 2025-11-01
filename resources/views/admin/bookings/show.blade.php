@extends('admin.layouts.app')

@section('title', 'Détails de la réservation #' . $booking->booking_number)

@section('content')
<div class="container mx-auto px-4 py-8">

    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h2 class="text-3xl font-extrabold text-gray-800 mb-1">Réservation #{{ $booking->booking_number }}</h2>
            <nav aria-label="breadcrumb">
                <ol class="flex text-sm text-gray-500 space-x-2">
                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a></li>
                    <li class="text-gray-400">/</li>
                    <li><a href="{{ route('admin.bookings.index') }}" class="hover:text-blue-600">Réservations</a></li>
                    <li class="text-gray-400">/</li>
                    <li class="text-gray-700 font-semibold">{{ $booking->booking_number }}</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="mt-4 sm:mt-0 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-lg hover:bg-gray-200 transition duration-150">
            <i class="fas fa-arrow-left mr-2"></i> Retour
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm" role="alert">
            <i class="fas fa-check-circle mr-2"></i> **Succès :** {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm" role="alert">
            <i class="fas fa-exclamation-circle mr-2"></i> **Erreur :** {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <div class="lg:col-span-8 space-y-6">
            
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-5 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-blue-600">Informations générales</h3>
                    @switch($booking->booking_type)
                        @case('flight')
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-plane mr-2"></i> Vol
                            </span>
                            @break
                        @case('event')
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-calendar-alt mr-2"></i> Événement
                            </span>
                            @break
                        @case('package')
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-suitcase mr-2"></i> Package
                            </span>
                            @break
                    @endswitch
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Numéro de réservation</span>
                            <span class="font-bold text-gray-900">{{ $booking->booking_number }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Date de réservation</span>
                            <span class="font-bold text-gray-900">{{ $booking->booking_date->format('d/m/Y à H:i') }}</span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Date de voyage</span>
                            <span class="font-bold text-gray-900">
                                {{ $booking->travel_date ? $booking->travel_date->format('d/m/Y') : 'N/A' }}
                            </span>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-xs font-semibold text-gray-500 uppercase">Nombre de passagers</span>
                            <span class="font-bold text-gray-900">{{ $booking->number_of_passengers }}</span>
                        </div>
                        @if($booking->seat_class)
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-gray-500 uppercase">Classe</span>
                                <span class="font-bold text-gray-900 capitalize">{{ $booking->seat_class }}</span>
                            </div>
                        @endif
                        @if($booking->seat_numbers)
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-gray-500 uppercase">Numéros de siège</span>
                                <span class="font-bold text-gray-900">{{ $booking->seat_numbers }}</span>
                            </div>
                        @endif
                    </div>

                    @if($booking->special_requests)
                        <div class="mt-5 p-4 bg-blue-50 border-l-4 border-blue-400 rounded-lg">
                            <span class="text-xs font-semibold text-gray-500 uppercase block mb-1">Demandes spéciales</span>
                            <p class="text-blue-800">{{ $booking->special_requests }}</p>
                        </div>
                    @endif

                    @if($booking->cancellation_reason)
                        <div class="mt-5 p-4 bg-red-50 border-l-4 border-red-400 rounded-lg">
                            <span class="text-xs font-semibold text-gray-500 uppercase block mb-1">Raison d'annulation</span>
                            <p class="text-red-800">{{ $booking->cancellation_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            @if($booking->booking_type === 'flight' && isset($additionalData['flight_details']))
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-blue-600">Détails du vol</h3>
                    </div>
                    <div class="p-5">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                            @if(isset($additionalData['flight_details']['pnr']))
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-gray-500 uppercase">PNR</span>
                                    <span class="font-bold text-gray-900">{{ $additionalData['flight_details']['pnr'] }}</span>
                                </div>
                            @endif
                            @if(isset($additionalData['flight_details']['eticket_number']))
                                <div class="flex flex-col">
                                    <span class="text-xs font-semibold text-gray-500 uppercase">Numéro e-ticket</span>
                                    <span class="font-bold text-gray-900">{{ $additionalData['flight_details']['eticket_number'] }}</span>
                                </div>
                            @endif
                            <div class="flex flex-col">
                                <span class="text-xs font-semibold text-gray-500 uppercase">Statut du ticket</span>
                                <div>
                                    @switch($additionalData['flight_details']['ticket_status'])
                                        @case('issued')
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Émis</span>
                                            @break
                                        @case('pending')
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Annulé</span>
                                            @break
                                        @default
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $additionalData['flight_details']['ticket_status'] }}</span>
                                    @endswitch
                                </div>
                            </div>
                        </div>

                        @if(isset($additionalData['flight_details']['flight_segments']) && count($additionalData['flight_details']['flight_segments']) > 0)
                            <hr class="my-5">
                            <h4 class="text-lg font-semibold mb-4 text-gray-700">Segments de vol</h4>
                            @foreach($additionalData['flight_details']['flight_segments'] as $index => $segment)
                                <div class="p-4 bg-gray-50 rounded-lg border-l-4 border-blue-500 mb-4 shadow-sm">
                                    <div class="flex justify-between items-center mb-3">
                                        <strong class="text-base text-gray-800">Segment {{ $index + 1 }}</strong>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-500 text-white">
                                            {{ $segment['airline'] ?? 'N/A' }} {{ $segment['flight_number'] ?? '' }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-5 items-center">
                                        <div class="md:col-span-2 space-y-1">
                                            <div class="text-xs text-gray-500 uppercase">Départ</div>
                                            <div class="font-bold text-gray-900">{{ $segment['departure_airport']['name'] ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-600">{{ $segment['departure_airport']['code'] ?? '' }}</div>
                                            <div class="text-blue-600 font-medium">{{ $segment['departure_airport']['time'] ?? '' }}</div>
                                        </div>
                                        <div class="md:col-span-1 text-center py-3 md:py-0">
                                            <i class="fas fa-arrow-right text-2xl text-gray-400"></i>
                                            <div class="text-xs text-gray-500 mt-1 font-semibold">{{ $segment['duration'] ?? 0 }} min</div>
                                        </div>
                                        <div class="md:col-span-2 space-y-1">
                                            <div class="text-xs text-gray-500 uppercase">Arrivée</div>
                                            <div class="font-bold text-gray-900">{{ $segment['arrival_airport']['name'] ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-600">{{ $segment['arrival_airport']['code'] ?? '' }}</div>
                                            <div class="text-blue-600 font-medium">{{ $segment['arrival_airport']['time'] ?? '' }}</div>
                                        </div>
                                    </div>
                                    @if(isset($segment['aircraft']))
                                        <div class="mt-3 text-sm text-gray-600">
                                            <i class="fas fa-plane mr-2"></i> Appareil : {{ $segment['aircraft'] }}
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif

            @if($booking->passenger_details && count($booking->passenger_details) > 0)
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-blue-600">Passagers</h3>
                    </div>
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Téléphone</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($booking->passenger_details as $passenger)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                @switch($passenger['type'])
                                                    @case('adult')
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Adulte</span>
                                                        @break
                                                    @case('child')
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">Enfant</span>
                                                        @break
                                                    @case('infant')
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Bébé</span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $passenger['name'] ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $passenger['email'] ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $passenger['phone'] ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            @if($booking->payments && count($booking->payments) > 0)
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="p-5 border-b border-gray-100">
                        <h3 class="text-lg font-bold text-blue-600">Historique des paiements</h3>
                    </div>
                    <div class="p-5">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Référence</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($booking->payments as $payment)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ number_format($payment->amount, 0, ',', ' ') }} {{ $payment->currency }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $payment->paymentMethod->name ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @switch($payment->status)
                                                    @case('completed')
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Complété</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">En attente</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Échoué</span>
                                                        @break
                                                    @default
                                                        <span class="inline-flex px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">{{ $payment->status }}</span>
                                                @endswitch
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-400">{{ $payment->transaction_id ?? 'N/A' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-blue-600">Client</h3>
                </div>
                <div class="p-5">
                    <div class="text-center mb-4">
                        <div class="w-20 h-20 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full mx-auto mb-3 flex items-center justify-center text-white text-3xl font-bold">
                            {{ strtoupper(substr($booking->user->name ?? 'U', 0, 2)) }}
                        </div>
                        <h5 class="text-xl font-semibold text-gray-900 mb-0">{{ $booking->user->name ?? 'N/A' }}</h5>
                        <p class="text-sm text-gray-500">{{ $booking->user->email ?? 'N/A' }}</p>
                    </div>
                    @if($booking->user)
                        <div class="space-y-2">
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-phone text-gray-400 w-5 mr-3"></i>
                                <span>{{ $booking->user->phone ?? 'Non renseigné' }}</span>
                            </div>
                            <div class="flex items-center text-sm text-gray-700">
                                <i class="fas fa-calendar-alt text-gray-400 w-5 mr-3"></i>
                                <span>Client depuis {{ $booking->user->created_at->format('M Y') }}</span>
                            </div>
                            <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium mt-2 block">
                                Voir le profil complet
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-blue-600">Statuts</h3>
                </div>
                <div class="p-5">
                    <div class="mb-5">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Statut de réservation</label>
                        <form action="{{ route('admin.bookings.update-status', $booking->id) }}" method="POST" id="statusForm">
                            @csrf
                            @method('PUT')
                            <select name="status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 mb-3" onchange="toggleCancellationReason(this.value)">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmé</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            <div id="cancellationReasonDiv" class="mb-3" style="display: {{ $booking->status == 'cancelled' ? 'block' : 'none' }};">
                                <textarea name="reason" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500" rows="3" placeholder="Raison de l'annulation (requis)">{{ $booking->cancellation_reason }}</textarea>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition duration-150">
                                <i class="fas fa-save mr-2"></i> Mettre à jour
                            </button>
                        </form>
                    </div>

                    <hr class="my-5">

                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-gray-500 uppercase mb-2">Statut de paiement</label>
                        <form action="{{ route('admin.bookings.update-payment-status', $booking->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <select name="payment_status" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:ring-green-500 focus:border-green-500 mb-3">
                                <option value="pending" {{ $booking->payment_status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Payé</option>
                                <option value="failed" {{ $booking->payment_status == 'failed' ? 'selected' : '' }}>Échoué</option>
                                <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Remboursé</option>
                                <option value="partially_paid" {{ $booking->payment_status == 'partially_paid' ? 'selected' : '' }}>Partiellement payé</option>
                            </select>
                            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 transition duration-150">
                                <i class="fas fa-check mr-2"></i> Mettre à jour
                            </button>
                        </form>
                    </div>

                    @if($booking->confirmed_at)
                        <div class="text-xs text-gray-500 mt-4 border-l-2 border-green-500 pl-2">
                            <i class="fas fa-check-circle text-green-500 mr-1"></i> 
                            Confirmé le {{ $booking->confirmed_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                    @if($booking->cancelled_at)
                        <div class="text-xs text-gray-500 mt-2 border-l-2 border-red-500 pl-2">
                            <i class="fas fa-times-circle text-red-500 mr-1"></i> 
                            Annulé le {{ $booking->cancelled_at->format('d/m/Y à H:i') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-blue-600">Détails financiers</h3>
                </div>
                <div class="p-5 space-y-2">
                    <div class="flex justify-between text-sm text-gray-600">
                        <span class="font-medium">Montant de base</span>
                        <strong class="font-semibold">{{ number_format($booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong>
                    </div>
                    @if($booking->discount_amount > 0)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span class="font-medium">Réduction</span>
                            <strong class="text-red-500 font-semibold">-{{ number_format($booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong>
                        </div>
                    @endif
                    @if($booking->tax_amount > 0)
                        <div class="flex justify-between text-sm text-gray-600">
                            <span class="font-medium">Taxes</span>
                            <strong class="font-semibold">{{ number_format($booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong>
                        </div>
                    @endif
                    <hr class="pt-2">
                    <div class="flex justify-between items-center pt-2">
                        <span class="text-lg font-bold text-gray-800">Montant total</span>
                        <strong class="text-green-600 text-2xl font-extrabold">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</strong>
                    </div>
                </div>
            </div>

            <!-- <div class="bg-white rounded-xl shadow-lg">
                <div class="p-5 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-blue-600">Actions</h3>
                </div>
                <div class="p-5 space-y-3">
                    <a href="#" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition duration-150">
                        <i class="fas fa-print mr-2"></i> Imprimer la réservation
                    </a>
                    <a href="#" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 rounded-lg hover:bg-blue-600 transition duration-150">
                        <i class="fas fa-envelope mr-2"></i> Envoyer par email
                    </a>
                    <a href="#" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-yellow-400 rounded-lg hover:bg-yellow-500 transition duration-150">
                        <i class="fas fa-file-pdf mr-2"></i> Télécharger PDF
                    </a>
                    @if($booking->status !== 'confirmed' && $booking->status !== 'completed')
                        <form action="{{ route('admin.bookings.destroy', $booking->id) }}" 
                              method="POST" 
                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition duration-150">
                                <i class="fas fa-trash mr-2"></i> Supprimer
                            </button>
                        </form>
                    @endif
                </div>
            </div> -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function toggleCancellationReason(status) {
        const reasonDiv = document.getElementById('cancellationReasonDiv');
        if (status === 'cancelled') {
            reasonDiv.style.display = 'block';
            // Optionnel : Ajouter/retirer la classe 'required' si vous utilisez la validation front-end
            // document.querySelector('#cancellationReasonDiv textarea').setAttribute('required', 'required');
        } else {
            reasonDiv.style.display = 'none';
            // document.querySelector('#cancellationReasonDiv textarea').removeAttribute('required');
        }
    }
</script>
@endpush