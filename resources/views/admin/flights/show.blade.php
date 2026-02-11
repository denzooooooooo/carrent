@extends('admin.layouts.app')

@section('title', 'Détails Réservation Vol')

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
                <a href="{{ route('admin.flights.index') }}" class="text-purple-600 hover:text-purple-700 font-semibold mb-2 inline-flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                <h1 class="text-3xl font-black text-gray-900 mt-2">Réservation #{{ $booking->booking_number }}</h1>
                <p class="text-gray-600 mt-1">Créée le {{ $booking->created_at->format('d/m/Y à H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @if($booking->flightBooking && $booking->flightBooking->duffel_order_id)
                    <form action="{{ route('admin.flights.resend-tickets', $booking->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-medium">
                            <i class="fas fa-envelope mr-2"></i>Renvoyer Billets
                        </button>
                    </form>
                @endif
                
                @if($booking->status !== 'cancelled')
                    <form action="{{ route('admin.flights.cancel', $booking->id) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation?')">
                        @csrf
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-medium">
                            <i class="fas fa-times-circle mr-2"></i>Annuler
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne Principale -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informations Réservation -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-info-circle text-purple-600 mr-3"></i>
                    Informations Réservation
                </h2>
                
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Numéro de Réservation</p>
                        <p class="text-lg font-bold text-gray-900 font-mono">{{ $booking->booking_number }}</p>
                    </div>
                    
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Statut</p>
                        @if($booking->status == 'confirmed')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>Confirmé
                            </span>
                        @elseif($booking->status == 'pending')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-amber-100 text-amber-800">
                                <i class="fas fa-clock mr-2"></i>En attente
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i>Annulé
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Statut Paiement</p>
                        @if($booking->payment_status == 'paid')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-green-100 text-green-800">
                                <i class="fas fa-check mr-2"></i>Payé
                            </span>
                        @elseif($booking->payment_status == 'pending')
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-amber-100 text-amber-800">
                                <i class="fas fa-clock mr-2"></i>En attente
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold bg-red-100 text-red-800">
                                <i class="fas fa-times mr-2"></i>Échoué
                            </span>
                        @endif
                    </div>
                    
                    <div>
                        <p class="text-sm font-semibold text-gray-600 mb-1">Montant Total</p>
                        <p class="text-2xl font-black text-gray-900">{{ number_format($booking->final_amount, 0, ',', ' ') }} <span class="text-sm text-gray-500">{{ $booking->currency }}</span></p>
                    </div>
                </div>
            </div>

            <!-- Informations Vol -->
            @if($booking->flightBooking)
            <div class="glass rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-plane text-blue-600 mr-3"></i>
                    Informations Vol
                </h2>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-blue-50 rounded-xl">
                        <div class="flex-1">
                            <p class="text-sm text-gray-600 mb-1">Départ</p>
                            <p class="text-2xl font-black text-gray-900">{{ $booking->flightBooking->departure_airport }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $booking->flightBooking->departure_date ? \Carbon\Carbon::parse($booking->flightBooking->departure_date)->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                        <div class="px-6">
                            <i class="fas fa-plane text-4xl text-blue-600"></i>
                        </div>
                        <div class="flex-1 text-right">
                            <p class="text-sm text-gray-600 mb-1">Arrivée</p>
                            <p class="text-2xl font-black text-gray-900">{{ $booking->flightBooking->arrival_airport }}</p>
                            <p class="text-sm text-gray-600 mt-1">{{ $booking->flightBooking->arrival_date ? \Carbon\Carbon::parse($booking->flightBooking->arrival_date)->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-3 gap-4">
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Numéro de Vol</p>
                            <p class="text-lg font-bold text-gray-900 font-mono">{{ $booking->flightBooking->flight_number ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Compagnie</p>
                            <p class="text-lg font-bold text-gray-900">{{ $booking->flightBooking->airline ?? 'N/A' }}</p>
                        </div>
                        <div class="p-4 bg-gray-50 rounded-xl">
                            <p class="text-sm text-gray-600 mb-1">Passagers</p>
                            <p class="text-lg font-bold text-gray-900">{{ $booking->flightBooking->passengers_count ?? count($booking->passenger_details) }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Informations Duffel -->
            @if($booking->flightBooking)
            <div class="glass rounded-2xl shadow-lg p-6 border-2 {{ $booking->flightBooking->duffel_order_id ? 'border-green-500' : 'border-amber-500' }}">
                <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-ticket-alt {{ $booking->flightBooking->duffel_order_id ? 'text-green-600' : 'text-amber-600' }} mr-3"></i>
                    Order Duffel
                </h2>
                
                @if($booking->flightBooking->duffel_order_id)
                    <div class="space-y-4">
                        <div class="p-4 bg-green-50 rounded-xl border-2 border-green-200">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">PNR (Booking Reference)</p>
                                    <p class="text-2xl font-black text-green-800 font-mono">{{ $booking->flightBooking->duffel_booking_reference }}</p>
                                </div>
                                <i class="fas fa-check-circle text-4xl text-green-600"></i>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Order ID</p>
                                    <p class="text-sm font-mono text-gray-900 break-all">{{ $booking->flightBooking->duffel_order_id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Confirmé le</p>
                                    <p class="text-sm font-semibold text-gray-900">{{ $booking->flightBooking->duffel_confirmed_at ? \Carbon\Carbon::parse($booking->flightBooking->duffel_confirmed_at)->format('d/m/Y H:i') : 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        @if($duffelOrder)
                            <div class="p-4 bg-blue-50 rounded-xl">
                                <p class="text-sm font-semibold text-gray-700 mb-3">Statut Duffel en Temps Réel:</p>
                                <pre class="text-xs bg-white p-4 rounded-lg overflow-auto max-h-64">{{ json_encode($duffelOrder, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="p-6 bg-amber-50 rounded-xl border-2 border-amber-200 text-center">
                        <i class="fas fa-exclamation-triangle text-5xl text-amber-600 mb-4"></i>
                        <p class="text-lg font-bold text-amber-900 mb-2">Aucun Order Duffel</p>
                        <p class="text-sm text-amber-700 mb-4">Cette réservation n'a pas encore d'order Duffel confirmé</p>
                        
                        @if($booking->payment_status == 'paid')
                            <form action="{{ route('admin.flights.create-duffel-order', $booking->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg transition-all shadow-md hover:shadow-lg font-bold" onclick="return confirm('Créer un order Duffel pour cette réservation?')">
                                    <i class="fas fa-plus-circle mr-2"></i>Créer Order Duffel Maintenant
                                </button>
                            </form>
                        @else
                            <p class="text-sm text-amber-600 italic">Le paiement doit être confirmé avant de créer l'order</p>
                        @endif
                    </div>
                @endif
            </div>
            @endif

            <!-- Passagers -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-black text-gray-900 mb-6 flex items-center">
                    <i class="fas fa-users text-purple-600 mr-3"></i>
                    Passagers ({{ count($booking->passenger_details) }})
                </h2>
                
                <div class="space-y-4">
                    @foreach($booking->passenger_details as $index => $passenger)
                        <div class="p-4 bg-purple-50 rounded-xl border-2 border-purple-200">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-lg font-bold text-gray-900">
                                    Passager {{ $index + 1 }}
                                    @if($index == 0)
                                        <span class="ml-2 text-xs px-2 py-1 bg-purple-600 text-white rounded-full">Contact Principal</span>
                                    @endif
                                </h3>
                                <span class="text-sm px-3 py-1 bg-purple-200 text-purple-800 rounded-full font-semibold">
                                    {{ $passenger['type'] ?? 'adult' }}
                                </span>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Nom Complet</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $passenger['title'] ?? '' }} {{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Date de Naissance</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $passenger['date_of_birth'] ?? 'N/A' }}</p>
                                </div>
                                @if(isset($passenger['email']))
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Email</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $passenger['email'] }}</p>
                                </div>
                                @endif
                                @if(isset($passenger['phone']))
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                                    <p class="text-base font-semibold text-gray-900">{{ $passenger['phone'] }}</p>
                                </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Colonne Latérale -->
        <div class="space-y-6">
            <!-- Client -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-black text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Client
                </h2>
                
                @if($booking->user)
                    <div class="space-y-3">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Nom</p>
                            <p class="text-base font-semibold text-gray-900">{{ $booking->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Email</p>
                            <p class="text-base font-semibold text-gray-900">{{ $booking->user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                            <p class="text-base font-semibold text-gray-900">{{ $booking->user->phone ?? 'N/A' }}</p>
                        </div>
                        <a href="{{ route('admin.users.show', $booking->user->id) }}" class="block w-full text-center px-4 py-2 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors font-medium mt-4">
                            <i class="fas fa-eye mr-2"></i>Voir Profil
                        </a>
                    </div>
                @else
                    <p class="text-gray-500 italic">Réservation invité</p>
                @endif
            </div>

            <!-- Paiement -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-black text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-credit-card text-green-600 mr-2"></i>
                    Paiement
                </h2>
                
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Méthode</p>
                        <p class="text-base font-semibold text-gray-900">{{ $booking->payment_method ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Transaction ID</p>
                        <p class="text-sm font-mono text-gray-900 break-all">{{ $booking->payment_transaction_id ?? 'N/A' }}</p>
                    </div>
                    <div class="pt-3 border-t border-gray-200">
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Sous-total</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format($booking->base_amount ?? $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                        </div>
                        <div class="flex justify-between mb-2">
                            <span class="text-sm text-gray-600">Taxes</span>
                            <span class="text-sm font-semibold text-gray-900">{{ number_format(($booking->final_amount - ($booking->base_amount ?? $booking->final_amount)), 0, ',', ' ') }} {{ $booking->currency }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-gray-300">
                            <span class="text-base font-bold text-gray-900">Total</span>
                            <span class="text-xl font-black text-gray-900">{{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions Rapides -->
            <div class="glass rounded-2xl shadow-lg p-6">
                <h2 class="text-lg font-black text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-amber-600 mr-2"></i>
                    Actions Rapides
                </h2>
                
                <div class="space-y-3">
                    @if($booking->flightBooking && $booking->flightBooking->duffel_order_id)
                        <form action="{{ route('admin.flights.resend-tickets', $booking->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded-lg transition-colors font-medium text-left">
                                <i class="fas fa-envelope mr-2"></i>Renvoyer les Billets
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->payment_status == 'paid' && (!$booking->flightBooking || !$booking->flightBooking->duffel_order_id))
                        <form action="{{ route('admin.flights.create-duffel-order', $booking->id) }}" method="POST" onsubmit="return confirm('Créer un order Duffel?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg transition-colors font-medium text-left">
                                <i class="fas fa-plus-circle mr-2"></i>Créer Order Duffel
                            </button>
                        </form>
                    @endif
                    
                    @if($booking->status !== 'cancelled')
                        <form action="{{ route('admin.flights.cancel', $booking->id) }}" method="POST" onsubmit="return confirm('Annuler cette réservation?')">
                            @csrf
                            <button type="submit" class="w-full px-4 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg transition-colors font-medium text-left">
                                <i class="fas fa-times-circle mr-2"></i>Annuler la Réservation
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
