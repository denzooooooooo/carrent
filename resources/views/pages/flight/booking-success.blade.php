@extends('layouts.app')

@section('title', 'Réservation confirmée - Carré Premium')

@section('content')
    @php
        // Récupérer le type de voyage
        $tripType = $booking->flightBooking->trip_type ?? 'one_way';
        
        // Récupérer tous les segments de vol
        $flightSegments = $booking->flightBooking->flight_segments ?? [];
        
        // Fonction pour séparer les vols aller et retour
        function separateFlightSegments($segments, $returnDate) {
            if (empty($segments)) {
                return ['outbound' => [], 'return' => []];
            }
            
            $outbound = [];
            $return = [];
            
            // Si on a une date de retour, séparer les segments
            if ($returnDate) {
                foreach ($segments as $segment) {
                    $segmentDate = \Carbon\Carbon::parse($segment['departure_airport']['time'] ?? '')->format('Y-m-d');
                    $returnDateFormatted = \Carbon\Carbon::parse($returnDate)->format('Y-m-d');
                    
                    if ($segmentDate < $returnDateFormatted) {
                        $outbound[] = $segment;
                    } else {
                        $return[] = $segment;
                    }
                }
            } else {
                // Pas de vol retour, tous les segments sont pour l'aller
                $outbound = $segments;
            }
            
            return ['outbound' => $outbound, 'return' => $return];
        }
        
        $separatedFlights = separateFlightSegments($flightSegments, $booking->flightBooking->return_date);
        $outboundSegments = $separatedFlights['outbound'];
        $returnSegments = $separatedFlights['return'];
        
        // Premier et dernier segment du vol aller
        $firstOutboundSegment = $outboundSegments[0] ?? null;
        $lastOutboundSegment = $outboundSegments[count($outboundSegments) - 1] ?? null;
        
        // Premier et dernier segment du vol retour (si existe)
        $firstReturnSegment = $returnSegments[0] ?? null;
        $lastReturnSegment = $returnSegments[count($returnSegments) - 1] ?? null;
        
        // Informations de départ (vol aller)
        $departureCode = $firstOutboundSegment['departure_airport']['code'] ?? $booking->flightBooking->departure_id;
        $departureCity = $firstOutboundSegment['departure_airport']['city'] ?? $firstOutboundSegment['departure_airport']['name'] ?? $departureCode;
        $departureTime = $firstOutboundSegment['departure_airport']['time'] ?? null;
        
        // Informations d'arrivée (vol aller)
        $arrivalCode = $lastOutboundSegment['arrival_airport']['code'] ?? $booking->flightBooking->arrival_id;
        $arrivalCity = $lastOutboundSegment['arrival_airport']['city'] ?? $lastOutboundSegment['arrival_airport']['name'] ?? $arrivalCode;
        $arrivalTime = $lastOutboundSegment['arrival_airport']['time'] ?? null;
        
        // Labels selon le type de vol
        $tripTypeLabel = match($tripType) {
            'one_way' => 'Aller simple',
            'round_trip' => 'Aller-retour',
            'multi_city' => 'Multi-destinations',
            default => 'Vol'
        };
        
        // Fonction pour formater la durée
        function formatDuration($duration) {
            if (is_numeric($duration)) {
                $hours = floor($duration / 60);
                $minutes = $duration % 60;
                return "{$hours}h {$minutes}min";
            }
            return 'Durée non spécifiée';
        }
        
        // Fonction pour compter les escales
        function countLayovers($segments) {
            return max(0, count($segments) - 1);
        }
    @endphp

    <div class="min-h-screen bg-gradient-to-br from-green-50 to-blue-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto">

                {{-- Icône de succès animée --}}
                <div class="text-center mb-8">
                    <div class="inline-block bg-green-100 rounded-full p-6 mb-4 animate-bounce">
                        <svg class="w-24 h-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h1 class="text-4xl md:text-5xl font-black text-gray-900 mb-4">
                        Demande de réservation enregistrée !
                    </h1>
                    <p class="text-xl text-gray-600">
                        Votre demande a été transmise avec succès
                    </p>
                </div>

                {{-- Carte principale --}}
                <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6 border-2 border-green-200">
                    <div class="text-center mb-8">
                        <div class="inline-block bg-gradient-to-r from-purple-100 to-blue-100 rounded-xl px-6 py-4 mb-6">
                            <p class="text-sm text-gray-600 mb-2">Votre numéro de réservation</p>
                            <p class="text-3xl font-black text-purple-700">
                                {{ $booking->booking_number }}
                            </p>
                        </div>
                    </div>

                    {{-- Informations du vol --}}
                    <div class="bg-gradient-to-r from-blue-50 to-purple-50 rounded-xl p-6 mb-6 border border-blue-200">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Détails de votre {{ $tripTypeLabel }}
                        </h3>

                        {{-- ========================================== --}}
                        {{-- VOL ALLER --}}
                        {{-- ========================================== --}}
                        @if(!empty($outboundSegments))
                            <div class="bg-white rounded-lg p-4 mb-4 border border-blue-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xl">🛫</span>
                                    <h4 class="font-bold text-blue-800">
                                        {{ $tripType === 'round_trip' ? 'Vol Aller' : 'Trajet' }}
                                    </h4>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Départ</p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $departureCity }}</p>
                                        <p class="text-sm text-gray-600">{{ $departureCode }}</p>
                                        @if($departureTime)
                                            <p class="text-sm font-semibold text-blue-600 mt-2">
                                                {{ \Carbon\Carbon::parse($departureTime)->format('H:i') }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($departureTime)->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Arrivée</p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $arrivalCity }}</p>
                                        <p class="text-sm text-gray-600">{{ $arrivalCode }}</p>
                                        @if($arrivalTime)
                                            <p class="text-sm font-semibold text-green-600 mt-2">
                                                {{ \Carbon\Carbon::parse($arrivalTime)->format('H:i') }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($arrivalTime)->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Afficher les escales si présentes (vol aller) --}}
                                @if(countLayovers($outboundSegments) > 0)
                                    <div class="mt-4 pt-4 border-t border-blue-200">
                                        <p class="text-xs text-gray-600 font-semibold mb-2">
                                            {{ countLayovers($outboundSegments) }} escale(s)
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($outboundSegments as $index => $segment)
                                                @if($index > 0)
                                                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full font-medium">
                                                        {{ $segment['departure_airport']['code'] ?? '' }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- ========================================== --}}
                        {{-- VOL RETOUR (si aller-retour) --}}
                        {{-- ========================================== --}}
                        @if($tripType === 'round_trip' && !empty($returnSegments))
                            <div class="bg-white rounded-lg p-4 mb-4 border border-green-200">
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-xl">🛬</span>
                                    <h4 class="font-bold text-green-800">Vol Retour</h4>
                                </div>
                                
                                @php
                                    $returnDepartureCode = $firstReturnSegment['departure_airport']['code'] ?? '';
                                    $returnDepartureCity = $firstReturnSegment['departure_airport']['city'] ?? $firstReturnSegment['departure_airport']['name'] ?? $returnDepartureCode;
                                    $returnDepartureTime = $firstReturnSegment['departure_airport']['time'] ?? null;
                                    
                                    $returnArrivalCode = $lastReturnSegment['arrival_airport']['code'] ?? '';
                                    $returnArrivalCity = $lastReturnSegment['arrival_airport']['city'] ?? $lastReturnSegment['arrival_airport']['name'] ?? $returnArrivalCode;
                                    $returnArrivalTime = $lastReturnSegment['arrival_airport']['time'] ?? null;
                                @endphp
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Départ</p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $returnDepartureCity }}</p>
                                        <p class="text-sm text-gray-600">{{ $returnDepartureCode }}</p>
                                        @if($returnDepartureTime)
                                            <p class="text-sm font-semibold text-blue-600 mt-2">
                                                {{ \Carbon\Carbon::parse($returnDepartureTime)->format('H:i') }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($returnDepartureTime)->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="text-xs text-gray-500 uppercase font-semibold mb-1">Arrivée</p>
                                        <p class="font-bold text-gray-900 text-lg">{{ $returnArrivalCity }}</p>
                                        <p class="text-sm text-gray-600">{{ $returnArrivalCode }}</p>
                                        @if($returnArrivalTime)
                                            <p class="text-sm font-semibold text-green-600 mt-2">
                                                {{ \Carbon\Carbon::parse($returnArrivalTime)->format('H:i') }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-1">
                                                {{ \Carbon\Carbon::parse($returnArrivalTime)->format('d/m/Y') }}
                                            </p>
                                        @endif
                                    </div>
                                </div>

                                {{-- Afficher les escales si présentes (vol retour) --}}
                                @if(countLayovers($returnSegments) > 0)
                                    <div class="mt-4 pt-4 border-t border-green-200">
                                        <p class="text-xs text-gray-600 font-semibold mb-2">
                                            {{ countLayovers($returnSegments) }} escale(s)
                                        </p>
                                        <div class="flex flex-wrap gap-2">
                                            @foreach($returnSegments as $index => $segment)
                                                @if($index > 0)
                                                    <span class="text-xs bg-amber-100 text-amber-700 px-2 py-1 rounded-full font-medium">
                                                        {{ $segment['departure_airport']['code'] ?? '' }}
                                                    </span>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- Résumé --}}
                        <div class="grid grid-cols-2 gap-4 mt-4">
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <p class="text-xs text-gray-500 mb-1">Passagers</p>
                                <p class="font-bold text-gray-900">{{ $booking->number_of_passengers }}</p>
                            </div>
                            <div class="bg-white rounded-lg p-3 border border-blue-200">
                                <p class="text-xs text-gray-500 mb-1">Classe</p>
                                <p class="font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->seat_class)) }}</p>
                            </div>
                        </div>

                        {{-- Prix --}}
                        <div class="mt-4 pt-4 border-t border-blue-200">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 font-semibold">Montant total</span>
                                <span class="text-2xl font-black text-green-600">
                                    {{ number_format($booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Détails complets des vols (tous les segments) --}}
                    @if(count($flightSegments) > 0)
                        <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                            <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Itinéraire détaillé
                            </h4>
                            
                            {{-- VOL ALLER DÉTAILLÉ --}}
                            @if(!empty($outboundSegments))
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-sm font-bold text-blue-700">🛫 Vol Aller</span>
                                    </div>
                                    @foreach($outboundSegments as $index => $segment)
                                        <div class="bg-white rounded-lg p-4 mb-3 border border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-bold text-gray-900">
                                                    Segment {{ $index + 1 }}
                                                </span>
                                                <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-semibold">
                                                    {{ $segment['airline'] ?? 'Compagnie aérienne' }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <p><strong>Vol :</strong> {{ $segment['flight_number'] ?? 'N/A' }}</p>
                                                <p><strong>Départ :</strong> {{ $segment['departure_airport']['name'] ?? $segment['departure_airport']['code'] }} 
                                                    à {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}</p>
                                                <p><strong>Arrivée :</strong> {{ $segment['arrival_airport']['name'] ?? $segment['arrival_airport']['code'] }} 
                                                    à {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</p>
                                                <p><strong>Durée :</strong> {{ formatDuration($segment['duration'] ?? null) }}</p>
                                                @if(isset($segment['aircraft']))
                                                    <p><strong>Appareil :</strong> {{ $segment['aircraft'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            
                            {{-- VOL RETOUR DÉTAILLÉ --}}
                            @if(!empty($returnSegments))
                                <div class="mb-4">
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="text-sm font-bold text-green-700">🛬 Vol Retour</span>
                                    </div>
                                    @foreach($returnSegments as $index => $segment)
                                        <div class="bg-white rounded-lg p-4 mb-3 border border-gray-200">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-bold text-gray-900">
                                                    Segment {{ $index + 1 }}
                                                </span>
                                                <span class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">
                                                    {{ $segment['airline'] ?? 'Compagnie aérienne' }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-600 space-y-1">
                                                <p><strong>Vol :</strong> {{ $segment['flight_number'] ?? 'N/A' }}</p>
                                                <p><strong>Départ :</strong> {{ $segment['departure_airport']['name'] ?? $segment['departure_airport']['code'] }} 
                                                    à {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}</p>
                                                <p><strong>Arrivée :</strong> {{ $segment['arrival_airport']['name'] ?? $segment['arrival_airport']['code'] }} 
                                                    à {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</p>
                                                <p><strong>Durée :</strong> {{ formatDuration($segment['duration'] ?? null) }}</p>
                                                @if(isset($segment['aircraft']))
                                                    <p><strong>Appareil :</strong> {{ $segment['aircraft'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Prochaines étapes --}}
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-6 rounded-lg mb-6">
                        <h3 class="font-bold text-amber-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Prochaines étapes
                        </h3>
                        <ul class="space-y-2 text-gray-700">
                            <li class="flex items-start gap-2">
                                <span class="text-green-500 font-bold">✓</span>
                                <span>Un email de confirmation vous a été envoyé</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-blue-500 font-bold">→</span>
                                <span>Notre équipe va vérifier la disponibilité de vos vols</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <span class="text-purple-500 font-bold">📞</span>
                                <span><strong>Vous serez contacté(e) dans les 24 heures</strong> pour finaliser votre réservation</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Informations importantes --}}
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-6 rounded-lg">
                        <h3 class="font-bold text-blue-900 mb-3">Informations importantes</h3>
                        <ul class="text-sm text-gray-700 space-y-2">
                            <li>• Conservez précieusement votre <strong>numéro de réservation : {{ $booking->booking_number }}</strong></li>
                            <li>• Vérifiez votre boîte email (y compris les spams)</li>
                            <li>• Préparez vos documents de voyage (passeport, visa si nécessaire)</li>
                            <li>• Notre service client est disponible pour toute question</li>
                        </ul>
                    </div>
                </div>

                {{-- Boutons d'action --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('flights.index') }}"
                        class="flex-1 text-center bg-purple-600 text-white px-6 py-4 rounded-xl font-bold hover:bg-purple-700 transition-all shadow-lg flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Nouvelle recherche
                    </a>
                    <a href="{{ route('home') }}"
                        class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-4 rounded-xl font-bold hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection