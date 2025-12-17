@extends('layouts.app')

@section('title', 'Détails du vol Multi-villes - Carré Premium')

@section('content')
    {{-- Header avec gradient --}}
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4">
            {{-- Breadcrumb --}}
            <nav class="text-sm mb-4">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('flights.index') }}" class="hover:underline">Recherche</a></li>
                    <li><span class="text-purple-300">/</span></li>
                    <li><a href="javascript:history.back()" class="hover:underline">Résultats</a></li>
                    <li><span class="text-purple-300">/</span></li>
                    <li class="font-bold">Détails du vol</li>
                </ol>
            </nav>

            {{-- Progression des segments --}}
            <div class="max-w-5xl mx-auto mb-6">
                <div class="flex items-center justify-between">
                    @foreach($multiCityData as $index => $segment)
                        <div class="flex items-center {{ $index > 0 ? 'flex-1' : '' }}">
                            @if($index > 0)
                                <div class="flex-1 h-1 bg-green-400"></div>
                            @endif
                            
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center font-bold text-lg shadow-lg">
                                    ✓
                                </div>
                                <div class="absolute top-14 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-center">
                                    <div class="text-xs font-bold">{{ $segment['departure_id'] }} → {{ $segment['arrival_id'] }}</div>
                                    <div class="text-xs opacity-75">{{ \Carbon\Carbon::parse($segment['date'])->format('d/m') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <h1 class="text-3xl lg:text-4xl font-black mb-2">
                    🌍 Détails de votre itinéraire Multi-villes
                </h1>
                <p class="text-lg opacity-90">
                    {{ count($multiCityData) }} destinations • Itinéraire complet
                </p>
            </div>
        </div>
    </div>

    {{-- Message d'erreur --}}
    @if(isset($error) && $error)
        <div class="container mx-auto px-4 py-6">
            <div class="bg-red-50 border-2 border-red-300 rounded-2xl p-6 text-center">
                <div class="text-5xl mb-4">⚠️</div>
                <h2 class="text-2xl font-black text-red-900 mb-3">Une erreur est survenue</h2>
                <p class="text-red-800 font-semibold mb-6">{{ $error }}</p>
                <a href="{{ route('flights.index') }}"
                    class="inline-block bg-gradient-to-r from-red-600 to-red-700 text-white px-8 py-4 rounded-xl font-bold shadow-xl hover:from-red-700 hover:to-red-800 transition-all">
                    🔍 Nouvelle recherche
                </a>
            </div>
        </div>
    @endif

    {{-- Contenu principal --}}
    @if(!isset($error) && $selectedFlight)
        <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50 py-8">
            <div class="container mx-auto px-4">
                <div class="flex flex-col lg:flex-row gap-6">
                    
                    {{-- ========================================
                         COLONNE PRINCIPALE (Détails du vol)
                        ======================================== --}}
                    <div class="lg:w-2/3">
                        {{-- Carte principale du vol --}}
                        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-6 border-2 border-purple-200">
                            {{-- Header de la carte --}}
                            <div class="flex items-center justify-between mb-6 pb-6 border-b-2 border-purple-200">
                                <div class="flex items-center space-x-4">
                                    @if(!empty($selectedFlight['airline_logo']))
                                        <img src="{{ $selectedFlight['airline_logo'] }}" 
                                             alt="{{ $selectedFlight['airline'] ?? 'Compagnie' }}"
                                             class="w-16 h-16 rounded-xl shadow-lg object-contain bg-white p-2 border-2 border-gray-100">
                                    @else
                                        <div class="w-16 h-16 rounded-xl shadow-lg bg-gradient-to-br from-purple-100 to-amber-100 flex items-center justify-center">
                                            <span class="text-3xl">✈️</span>
                                        </div>
                                    @endif

                                    <div>
                                        <h2 class="font-black text-2xl text-gray-900">
                                            {{ $selectedFlight['airline'] ?? 'Compagnies multiples' }}
                                        </h2>
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-purple-100 text-purple-700">
                                                Multi-villes
                                            </span>
                                            <span class="text-xs font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                                ✅ Protégé
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- SEGMENTS DE VOL --}}
                            <div class="space-y-6">
                                @php
                                    $currentSegmentIndex = 0;
                                    $allFlights = $selectedFlight['flights'] ?? [];
                                    $allLayovers = $selectedFlight['layovers'] ?? [];
                                @endphp

                                @foreach($multiCityData as $segmentIndex => $segmentInfo)
                                    @php
                                        // Identifier les vols de ce segment
                                        $segmentFlights = [];
                                        $segmentLayovers = [];
                                        
                                        foreach ($allFlights as $flightIndex => $flight) {
                                            if ($flight['departure_airport']['id'] == $segmentInfo['departure_id'] || 
                                                $flight['arrival_airport']['id'] == $segmentInfo['arrival_id']) {
                                                $segmentFlights[] = $flight;
                                            }
                                        }
                                        
                                        if (empty($segmentFlights) && isset($selectedSegments[$segmentIndex])) {
                                            // Utiliser les données des segments sélectionnés si disponibles
                                            $segmentFlights = $selectedSegments[$segmentIndex]['flights'] ?? [];
                                            $segmentLayovers = $selectedSegments[$segmentIndex]['layovers'] ?? [];
                                        }

                                        if (empty($segmentFlights)) {
                                            continue;
                                        }

                                        $firstFlight = $segmentFlights[0];
                                        $lastFlight = end($segmentFlights);
                                        
                                        // Calculer la durée du segment
                                        $segmentDuration = 0;
                                        foreach ($segmentFlights as $sf) {
                                            $segmentDuration += (int) ($sf['duration_minutes'] ?? $sf['duration'] ?? 0);
                                        }
                                        foreach ($segmentLayovers as $layover) {
                                            $segmentDuration += (int) ($layover['duration_minutes'] ?? 0);
                                        }

                                        $stops = count($segmentFlights) - 1;
                                    @endphp

                                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-6 border-2 border-purple-300">
                                        {{-- En-tête du segment --}}
                                        <div class="flex items-center justify-between mb-4 pb-3 border-b-2 border-purple-300">
                                            <h3 class="text-lg font-black text-purple-900 flex items-center gap-3">
                                                <span class="w-8 h-8 rounded-full bg-purple-600 text-white flex items-center justify-center text-sm">
                                                    {{ $segmentIndex + 1 }}
                                                </span>
                                                Segment {{ $segmentIndex + 1 }}
                                            </h3>
                                            <div class="text-sm font-bold text-purple-700">
                                                {{ \Carbon\Carbon::parse($segmentInfo['date'])->translatedFormat('D d M Y') }}
                                            </div>
                                        </div>

                                        {{-- Vue d'ensemble du segment --}}
                                        <div class="flex items-center justify-between mb-6">
                                            {{-- Départ --}}
                                            <div class="text-left flex-1 min-w-0 pr-3">
                                                <div class="text-5xl font-black text-purple-900">
                                                    {{ \Carbon\Carbon::parse($firstFlight['departure_time'])->format('H:i') }}
                                                </div>
                                                <div class="text-base font-bold text-purple-700 mt-2">
                                                    {{ $firstFlight['departure_airport']['id'] }}
                                                </div>
                                                <div class="text-sm text-gray-700 font-semibold mt-1 truncate">
                                                    {{ $firstFlight['departure_airport']['name'] ?? '' }}
                                                </div>
                                                @if(!empty($firstFlight['departure_airport']['city']))
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        {{ $firstFlight['departure_airport']['city'] }}
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- Timeline --}}
                                            <div class="flex-shrink-0 w-40 px-4">
                                                <div class="text-center">
                                                    <div class="text-sm font-bold text-purple-700 mb-3">
                                                        {{ floor($segmentDuration / 60) }}h {{ $segmentDuration % 60 }}min
                                                    </div>
                                                    <div class="relative flex items-center">
                                                        <div class="w-4 h-4 rounded-full bg-purple-600 shadow-lg"></div>
                                                        <div class="flex-1 h-2 bg-gradient-to-r from-purple-600 to-purple-400"></div>
                                                        <div class="w-4 h-4 rounded-full bg-purple-600 shadow-lg"></div>
                                                    </div>
                                                    <div class="mt-3">
                                                        <span class="inline-block px-4 py-1 rounded-full text-sm font-bold shadow-md
                                                            {{ $stops == 0 ? 'bg-green-200 text-green-800' : 'bg-amber-200 text-amber-800' }}">
                                                            {{ $stops == 0 ? '✈️ Direct' : "🔄 {$stops} escale" . ($stops > 1 ? 's' : '') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Arrivée --}}
                                            <div class="text-right flex-1 min-w-0 pl-3">
                                                <div class="text-5xl font-black text-purple-900">
                                                    {{ \Carbon\Carbon::parse($lastFlight['arrival_time'])->format('H:i') }}
                                                </div>
                                                <div class="text-base font-bold text-purple-700 mt-2">
                                                    {{ $lastFlight['arrival_airport']['id'] }}
                                                </div>
                                                <div class="text-sm text-gray-700 font-semibold mt-1 truncate">
                                                    {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                                                </div>
                                                @if(!empty($lastFlight['arrival_airport']['city']))
                                                    <div class="text-xs text-gray-600 mt-1">
                                                        {{ $lastFlight['arrival_airport']['city'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        {{-- Détails des vols du segment --}}
                                        @if(count($segmentFlights) > 1)
                                            <details class="mt-4">
                                                <summary class="cursor-pointer text-sm font-bold text-purple-700 hover:text-purple-900 flex items-center gap-2">
                                                    <span>📋 Voir le détail des {{ count($segmentFlights) }} vols</span>
                                                </summary>
                                                <div class="mt-3 space-y-3">
                                                    @foreach($segmentFlights as $flightIndex => $flight)
                                                        <div class="bg-white rounded-lg p-4 border border-purple-200">
                                                            <div class="flex items-center justify-between mb-2">
                                                                <div class="flex items-center gap-3">
                                                                    @if(!empty($flight['airline_logo']))
                                                                        <img src="{{ $flight['airline_logo'] }}" alt="{{ $flight['airline'] }}" class="w-10 h-10 rounded-lg">
                                                                    @endif
                                                                    <div>
                                                                        <div class="font-bold text-gray-900">{{ $flight['airline'] }}</div>
                                                                        <div class="text-xs text-gray-600">{{ $flight['flight_number'] }}</div>
                                                                    </div>
                                                                </div>
                                                                <div class="text-xs font-semibold text-gray-600">
                                                                    {{ floor(($flight['duration_minutes'] ?? $flight['duration']) / 60) }}h 
                                                                    {{ ($flight['duration_minutes'] ?? $flight['duration']) % 60 }}min
                                                                </div>
                                                            </div>

                                                            <div class="flex items-center justify-between text-sm">
                                                                <div>
                                                                    <span class="font-bold">{{ \Carbon\Carbon::parse($flight['departure_time'])->format('H:i') }}</span>
                                                                    <span class="text-gray-600 ml-2">{{ $flight['departure_airport']['id'] }}</span>
                                                                </div>
                                                                <div class="flex-1 mx-4">
                                                                    <div class="h-px bg-purple-300"></div>
                                                                </div>
                                                                <div>
                                                                    <span class="font-bold">{{ \Carbon\Carbon::parse($flight['arrival_time'])->format('H:i') }}</span>
                                                                    <span class="text-gray-600 ml-2">{{ $flight['arrival_airport']['id'] }}</span>
                                                                </div>
                                                            </div>

                                                            @if(!empty($flight['aircraft']))
                                                                <div class="mt-2 text-xs text-gray-600">
                                                                    ✈️ {{ $flight['aircraft'] }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                        {{-- Escale entre les vols --}}
                                                        @if(!$loop->last && isset($segmentLayovers[$flightIndex]))
                                                            <div class="flex items-center justify-center py-2">
                                                                <div class="bg-amber-100 text-amber-800 px-4 py-2 rounded-lg text-sm font-bold">
                                                                    ⏱️ Escale {{ $segmentLayovers[$flightIndex]['duration'] }} à {{ $segmentLayovers[$flightIndex]['name'] }}
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </details>
                                        @else
                                            {{-- Vol direct - afficher les détails complets --}}
                                            @php $flight = $segmentFlights[0]; @endphp
                                            <div class="bg-white rounded-lg p-4 border border-purple-200">
                                                <div class="flex items-center gap-4 mb-3">
                                                    @if(!empty($flight['airline_logo']))
                                                        <img src="{{ $flight['airline_logo'] }}" alt="{{ $flight['airline'] }}" class="w-12 h-12 rounded-lg">
                                                    @endif
                                                    <div>
                                                        <div class="font-bold text-gray-900 text-lg">{{ $flight['airline'] }}</div>
                                                        <div class="text-sm text-gray-600">Vol {{ $flight['flight_number'] }}</div>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-2 gap-4 text-sm">
                                                    @if(!empty($flight['aircraft']))
                                                        <div>
                                                            <span class="text-gray-600">Appareil :</span>
                                                            <span class="font-semibold ml-1">{{ $flight['aircraft'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(!empty($flight['travel_class']))
                                                        <div>
                                                            <span class="text-gray-600">Classe :</span>
                                                            <span class="font-semibold ml-1">{{ $flight['travel_class'] }}</span>
                                                        </div>
                                                    @endif
                                                    @if(!empty($flight['legroom']))
                                                        <div>
                                                            <span class="text-gray-600">Espace jambes :</span>
                                                            <span class="font-semibold ml-1">{{ $flight['legroom'] }}</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                @if(!empty($flight['extensions']))
                                                    <div class="mt-3 pt-3 border-t border-gray-200">
                                                        <div class="flex flex-wrap gap-2">
                                                            @foreach($flight['extensions'] as $extension)
                                                                <span class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full">
                                                                    {{ $extension }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            {{-- Émissions carbone --}}
                            @if(!empty($selectedFlight['carbon_emissions']))
                                <div class="mt-6 bg-green-50 border-2 border-green-200 rounded-xl p-4">
                                    <div class="flex items-center gap-3">
                                        <span class="text-3xl">🌱</span>
                                        <div class="flex-1">
                                            <h4 class="font-bold text-green-900 mb-1">Émissions de CO₂</h4>
                                            <div class="flex items-center gap-4 text-sm">
                                                <div>
                                                    <span class="text-gray-700">Ce vol :</span>
                                                    <span class="font-bold text-green-700 ml-1">
                                                        {{ number_format($selectedFlight['carbon_emissions']['this_flight'] / 1000, 1) }} kg
                                                    </span>
                                                </div>
                                                @if(isset($selectedFlight['carbon_emissions']['difference_percent']))
                                                    @php $diff = $selectedFlight['carbon_emissions']['difference_percent']; @endphp
                                                    <div>
                                                        <span class="font-bold {{ $diff < 0 ? 'text-green-600' : 'text-orange-600' }}">
                                                            {{ $diff > 0 ? '+' : '' }}{{ $diff }}%
                                                        </span>
                                                        <span class="text-gray-600 ml-1">vs moyenne</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Informations importantes --}}
                        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded-xl mb-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-lg font-black text-blue-900 mb-2">✅ Vol protégé</h4>
                                    <ul class="space-y-2 text-sm text-blue-800">
                                        <li class="flex items-start gap-2">
                                            <span class="text-blue-600 font-bold">•</span>
                                            <span class="font-semibold">Tous vos segments sont sur le même billet</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-blue-600 font-bold">•</span>
                                            <span class="font-semibold">En cas de retard, la compagnie vous reprotège automatiquement</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-blue-600 font-bold">•</span>
                                            <span class="font-semibold">Vos bagages sont enregistrés jusqu'à la destination finale</span>
                                        </li>
                                        <li class="flex items-start gap-2">
                                            <span class="text-blue-600 font-bold">•</span>
                                            <span class="font-semibold">Une seule réservation pour tout votre itinéraire</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ========================================
                         SIDEBAR (Prix & Réservation)
                        ======================================== --}}
                    <div class="lg:w-1/3">
                        <div class="bg-white rounded-2xl shadow-2xl p-6 sticky top-24 border-2 border-purple-200">
                            {{-- Prix --}}
                            <div class="bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl p-6 mb-6 border-2 border-purple-300">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">💰 Récapitulatif des prix</h3>
                                
                                {{-- Prix par segment --}}
                                @if(!empty($selectedSegments))
                                    <div class="space-y-2 mb-4 pb-4 border-b-2 border-purple-200">
                                        @foreach($selectedSegments as $segment)
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-gray-700">
                                                    Segment {{ $loop->iteration }} ({{ $segment['departure'] }} → {{ $segment['arrival'] }})
                                                </span>
                                                <span class="font-bold text-gray-900">
                                                    {{ number_format($segment['price']) }} {{ $searchParams['currency'] }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                {{-- Prix total --}}
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-base font-semibold text-gray-700">Prix total</span>
                                    <span class="text-3xl font-black text-purple-700">
                                        {{ number_format($selectedFlight['price'] ?? $searchParams['total_price']) }}
                                    </span>
                                </div>
                                <div class="text-right text-sm font-bold text-gray-600 mb-4">
                                    {{ $selectedFlight['currency'] ?? $searchParams['currency'] ?? 'XOF' }}
                                </div>

                                {{-- Prix par passager --}}
                                @php
                                    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);
                                    $totalPrice = $selectedFlight['price'] ?? $searchParams['total_price'];
                                @endphp

                                <div class="bg-white rounded-lg p-4 space-y-2 text-sm">
                                    @if(($searchParams['adults'] ?? 1) > 0)
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-700">{{ $searchParams['adults'] ?? 1 }} Adulte(s)</span>
                                            <span class="font-bold">{{ number_format($totalPrice) }} {{ $searchParams['currency'] }}</span>
                                        </div>
                                    @endif
                                    @if(($searchParams['children'] ?? 0) > 0)
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-700">{{ $searchParams['children'] }} Enfant(s)</span>
                                            <span class="font-bold">Inclus</span>
                                        </div>
                                    @endif
                                    @if(($searchParams['infants'] ?? 0) > 0)
                                        <div class="flex items-center justify-between">
                                            <span class="text-gray-700">{{ $searchParams['infants'] }} Bébé(s)</span>
                                            <span class="font-bold">Inclus</span>
                                        </div>
                                    @endif
                                </div>

                                @if($totalPassengers > 1)
                                    <div class="mt-3 pt-3 border-t border-purple-200 text-xs text-gray-600">
                                        Prix / personne : {{ number_format($totalPrice / $totalPassengers) }} {{ $searchParams['currency'] }}
                                    </div>
                                @endif
                            </div>

                            {{-- Formulaire de réservation --}}
                            <form method="POST" action="{{ route('flights.booking.store') }}" id="bookingForm">
                                @csrf

                                {{-- Champs cachés pour les données de vol --}}
                                <input type="hidden" name="booking_token" value="{{ $searchParams['booking_token'] ?? '' }}">
                                <input type="hidden" name="departure_id" value="{{ $multiCityData[0]['departure_id'] ?? '' }}">
                                <input type="hidden" name="arrival_id" value="{{ $multiCityData[count($multiCityData) - 1]['arrival_id'] ?? '' }}">
                                <input type="hidden" name="outbound_date" value="{{ $multiCityData[0]['date'] ?? '' }}">
                                <input type="hidden" name="return_date" value="">
                                <input type="hidden" name="trip_type" value="multi_city">
                                
                                {{-- Prix --}}
                                <input type="hidden" name="base_price" value="{{ $totalPrice }}">
                                <input type="hidden" name="taxes" value="0">
                                <input type="hidden" name="final_price" value="{{ $totalPrice }}">
                                <input type="hidden" name="currency" value="{{ $searchParams['currency'] ?? 'XOF' }}">

                                {{-- Passagers --}}
                                <input type="hidden" name="adults" value="{{ $searchParams['adults'] ?? 1 }}">
                                <input type="hidden" name="children" value="{{ $searchParams['children'] ?? 0 }}">
                                <input type="hidden" name="infants" value="{{ $searchParams['infants'] ?? 0 }}">
                                <input type="hidden" name="travel_class" value="{{ $searchParams['travel_class'] ?? 'ECONOMY' }}">

                                {{-- Détails du vol en JSON --}}
                                <input type="hidden" name="flight_details" value="{{ json_encode($selectedFlight) }}">
                                <input type="hidden" name="booking_options" value="{{ json_encode($bookingOptions ?? []) }}">

                                {{-- Multi-city spécifique --}}
                                <input type="hidden" name="multi_city_json" value="{{ $searchParams['multi_city_json'] ?? '' }}">
                                <input type="hidden" name="selected_segments" value="{{ $searchParams['selected_segments'] ?? '' }}">

                                {{-- Informations passagers --}}
                                <div class="space-y-4 mb-6">
                                    <h4 class="font-bold text-gray-900 flex items-center gap-2">
                                        <span class="text-xl">👤</span>
                                        Informations passagers
                                    </h4>

                                    @for($i = 0; $i < $totalPassengers; $i++)
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                                            <h5 class="font-bold text-sm text-gray-900 mb-3">
                                                @if($i < ($searchParams['adults'] ?? 1))
                                                    👨 Adulte {{ $i + 1 }}
                                                @elseif($i < (($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0)))
                                                    👶 Enfant {{ $i - ($searchParams['adults'] ?? 1) + 1 }}
                                                @else
                                                    🍼 Bébé {{ $i - (($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0)) + 1 }}
                                                @endif
                                            </h5>

                                            <div class="space-y-3">
                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                                        Nom complet *
                                                    </label>
                                                    <input type="text" 
                                                           name="passenger_names[]" 
                                                           required
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                                           placeholder="Ex: DUPONT Jean">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                                        Email {{ $i === 0 ? '*' : '(optionnel)' }}
                                                    </label>
                                                    <input type="email" 
                                                           name="passenger_emails[]" 
                                                           {{ $i === 0 ? 'required' : '' }}
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                                           placeholder="email@exemple.com">
                                                </div>

                                                <div>
                                                    <label class="block text-xs font-semibold text-gray-700 mb-1">
                                                        Téléphone {{ $i === 0 ? '*' : '(optionnel)' }}
                                                    </label>
                                                    <input type="tel" 
                                                           name="passenger_phones[]" 
                                                           {{ $i === 0 ? 'required' : '' }}
                                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                                           placeholder="+225 XX XX XX XX XX">
                                                </div>
                                            </div>
                                        </div>
                                    @endfor
                                </div>

                                {{-- Conditions --}}
                                <div class="mb-6">
                                    <label class="flex items-start space-x-3 cursor-pointer p-3 rounded-lg hover:bg-purple-50 border border-gray-300">
                                        <input type="checkbox" required class="mt-1 w-5 h-5 text-purple-600 rounded focus:ring-purple-500">
                                        <span class="text-sm text-gray-700">
                                            J'accepte les <a href="{{ route('terms') }}" target="_blank" class="text-purple-600 font-semibold hover:underline">conditions générales</a> 
                                            et la <a href="{{ route('privacy') }}" target="_blank" class="text-purple-600 font-semibold hover:underline">politique de confidentialité</a>
                                        </span>
                                    </label>
                                </div>

                                {{-- Bouton de soumission --}}
                                <button type="submit" 
                                        class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-5 rounded-xl font-black text-lg shadow-2xl hover:from-green-700 hover:to-green-800 hover:scale-105 transition-all flex items-center justify-center gap-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span>Confirmer la réservation</span>
                                </button>

                                <p class="text-xs text-center text-gray-600 mt-4">
                                    🔒 Paiement sécurisé • Confirmation immédiate
                                </p>
                            </form>

                            {{-- Note de sécurité --}}
                            <div class="mt-6 bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-start gap-2">
                                    <span class="text-xl">✅</span>
                                    <div class="text-xs text-green-800">
                                        <p class="font-bold mb-1">Réservation sécurisée</p>
                                        <p>Vol protégé avec tous les segments sur un seul billet. Aucune surprise en cas de retard.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('scripts')
    <script>
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            const button = this.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = `
                <svg class="animate-spin h-6 w-6 mr-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>Traitement en cours...</span>
            `;
        });
    </script>
    @endpush
@endsection