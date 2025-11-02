@extends('layouts.app')

@section('title', 'Détails du vol - Carré Premium')

@section('content')
    <!-- Hero Section with Purple Gradient -->
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl lg:text-5xl font-black mb-4">Détails et réservation de votre vol</h1>
            <p class="text-xl opacity-90">Confirmez les détails de votre voyage et réservez en toute sérénité</p>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
        <div class="container mx-auto px-4 py-8 lg:py-12">
            @if($error)
                <div class="max-w-4xl mx-auto bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-2xl shadow-2xl mb-8"
                    role="alert">
                    <div class="flex items-center space-x-3 mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <h3 class="text-lg font-bold">Erreur de chargement</h3>
                    </div>
                    <p class="text-red-700">{{ $error }}</p>
                </div>
            @elseif($selectedFlight)
                <div class="max-w-7xl mx-auto">

                <div class="lg:grid lg:grid-cols-3 lg:gap-8">

                    {{-- COLONNE PRINCIPALE --}}
                    <div class="lg:col-span-2">

                        {{-- CARTE DES DÉTAILS DU VOL --}}
                        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-8 border-2 border-purple-100">
                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start border-b-2 border-purple-100 pb-6 mb-6">
                                <div class="mb-4 sm:mb-0">
                                    <div class="flex items-center space-x-4 mb-3">
                                        @if(isset($selectedFlight['flights'][0]['airline_logo']))
                                            <img src="{{ $selectedFlight['flights'][0]['airline_logo'] }}"
                                                alt="{{ $selectedFlight['flights'][0]['airline'] }}" class="w-12 h-12 rounded-xl shadow-lg">
                                        @endif
                                        <div>
                                            <h2 class="text-2xl md:text-3xl font-black text-gray-900">
                                                {{ $selectedFlight['flights'][0]['airline'] ?? 'Détails du Vol' }}</h2>
                                            <div class="flex items-center space-x-4 text-sm text-gray-600 mt-1">
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ $selectedFlight['total_duration'] ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex items-center space-x-1">
                                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="font-semibold">{{ count($selectedFlight['flights']) }} segment(s)</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200 shadow-sm">
                                    <div class="text-center">
                                        <div class="text-2xl md:text-3xl font-black text-green-600 mb-1">
                                            {{ number_format($selectedFlight['price'] ?? 0, 0, ',', ' ') }} €
                                        </div>
                                        <div class="text-xs text-green-700 font-semibold">Prix par personne</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Segments de vol --}}
                            @foreach($selectedFlight['flights'] as $index => $segment)
                                <div class="bg-gradient-to-r from-gray-50 to-purple-50 rounded-2xl p-6 mb-6 border-2 border-purple-100 shadow-lg @if(!$loop->first) mt-6 @endif">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                        <!-- Départ -->
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-4">
                                                <div class="bg-blue-500 rounded-full p-3 shadow-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-2xl md:text-3xl font-black text-gray-900">
                                                        {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                                    </p>
                                                    <p class="font-bold text-gray-800 text-lg">
                                                        {{ $segment['departure_airport']['name'] ?? 'Ville de départ' }}</p>
                                                    <p class="text-sm text-gray-600 font-medium">
                                                        {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d M Y') }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Centre - Durée et avion -->
                                        <div class="flex-shrink-0 text-center lg:w-64">
                                            <div class="bg-white rounded-xl p-4 shadow-md border border-purple-200">
                                                <div class="text-sm text-purple-700 font-bold mb-2">{{ $segment['duration'] ?? '' }}</div>
                                                <div class="flex items-center justify-center mb-2">
                                                    <div class="h-0.5 bg-gradient-to-r from-purple-300 to-amber-300 w-full rounded-l"></div>
                                                    <div class="bg-gradient-to-r from-purple-500 to-amber-500 rounded-full p-2 -mx-1">
                                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                        </svg>
                                                    </div>
                                                    <div class="h-0.5 bg-gradient-to-l from-purple-300 to-amber-300 w-full rounded-r"></div>
                                                </div>
                                                <p class="text-xs text-gray-600 font-semibold">Vol: {{ $segment['flight_number'] ?? 'N/A' }}</p>
                                            </div>
                                        </div>

                                        <!-- Arrivée -->
                                        <div class="flex-1 text-right">
                                            <div class="flex items-center justify-end space-x-4">
                                                <div>
                                                    <p class="text-2xl md:text-3xl font-black text-gray-900">
                                                        {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</p>
                                                    <p class="font-bold text-gray-800 text-lg">
                                                        {{ $segment['arrival_airport']['name'] ?? 'Ville d\'arrivée' }}</p>
                                                    <p class="text-sm text-gray-600 font-medium">
                                                        {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d M Y') }}
                                                    </p>
                                                </div>
                                                <div class="bg-green-500 rounded-full p-3 shadow-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($selectedFlight['layovers'][$index]))
                                    <div class="flex items-center justify-center space-x-4 my-6">
                                        <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-gray-300"></div>
                                        <div class="bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-200 rounded-xl p-4 shadow-lg">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div class="text-center">
                                                    <div class="font-black text-amber-800 text-lg">{{ $selectedFlight['layovers'][$index]['name'] }}</div>
                                                    <div class="text-amber-700 font-bold">{{ $selectedFlight['layovers'][$index]['duration'] }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 h-px bg-gradient-to-l from-gray-200 to-gray-300"></div>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- FORMULAIRE DE RÉSERVATION --}}
                        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-purple-100">
                            <div class="flex items-center space-x-3 mb-6 border-b-2 border-purple-100 pb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h2 class="text-2xl md:text-3xl font-black text-gray-900">Informations de la réservation</h2>
                            </div>

                            <form action="{{ route('flight.store-booking') }}" method="POST" id="bookingForm">
                                @csrf

                                {{-- Champs cachés CORRIGÉS - SANS htmlspecialchars --}}
                                <input type="hidden" name="booking_token" value="{{ request('booking_token') }}">
                                <input type="hidden" name="departure_token" value="{{ request('departure_token') }}">
                                <input type="hidden" name="departure_id" value="{{ request('departure_id') }}">
                                <input type="hidden" name="arrival_id" value="{{ request('arrival_id') }}">
                                <input type="hidden" name="outbound_date" value="{{ request('outbound_date') }}">
                                <input type="hidden" name="return_date" value="{{ request('return_date') }}">

                                {{-- CORRECTION CRITIQUE : Utiliser e() au lieu de htmlspecialchars --}}
                                <!-- <input type="hidden" name="flight_details" value="{{ e(json_encode($selectedFlight)) }}">
                                    <input type="hidden" name="booking_options" value="{{ e(json_encode($bookingOptions ?? [])) }}"> -->
                                <input type="hidden" name="flight_details" value='@json($selectedFlight)'>
                                <input type="hidden" name="booking_options" value='@json($bookingOptions)'>

                                {{-- Prix (seront mis à jour dynamiquement) --}}
                                <input type="hidden" name="base_price" id="hidden_base_price"
                                    value="{{ $selectedFlight['price'] ?? 0 }}">
                                <input type="hidden" name="taxes" id="hidden_taxes"
                                    value="{{ ($selectedFlight['price'] ?? 0) * 0.1 }}">
                                <input type="hidden" name="final_price" id="hidden_final_price"
                                    value="{{ ($selectedFlight['price'] ?? 0) * 1.1 }}">
                                <input type="hidden" name="currency" value="EUR">

                                {{-- Section Passagers et Classe --}}
                                <div class="mb-8">
                                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <span>Configuration des passagers</span>
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-gradient-to-r from-purple-50 to-amber-50 rounded-2xl border-2 border-purple-200 shadow-lg">
                                        <div class="bg-white rounded-xl p-4 border border-purple-200 shadow-sm">
                                            <div class="flex items-center space-x-2 mb-3">
                                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <label for="adults" class="block text-sm font-bold text-gray-800">Adultes (18+)</label>
                                            </div>
                                            <input type="number" id="adults" name="adults" min="1" max="9"
                                                value="{{ request('adults', 1) }}"
                                                class="w-full border-2 border-purple-200 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm px-3 py-2 font-semibold"
                                                required>
                                        </div>
                                        <div class="bg-white rounded-xl p-4 border border-purple-200 shadow-sm">
                                            <div class="flex items-center space-x-2 mb-3">
                                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                                </svg>
                                                <label for="children" class="block text-sm font-bold text-gray-800">Enfants (2-11)</label>
                                            </div>
                                            <input type="number" id="children" name="children" min="0" max="8"
                                                value="{{ request('children', 0) }}"
                                                class="w-full border-2 border-purple-200 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm px-3 py-2 font-semibold">
                                        </div>
                                        <div class="bg-white rounded-xl p-4 border border-purple-200 shadow-sm">
                                            <div class="flex items-center space-x-2 mb-3">
                                                <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                                </svg>
                                                <label for="infants" class="block text-sm font-bold text-gray-800">Bébés (-2)</label>
                                            </div>
                                            <input type="number" id="infants" name="infants" min="0" max="4"
                                                value="{{ request('infants', 0) }}"
                                                class="w-full border-2 border-purple-200 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm px-3 py-2 font-semibold">
                                        </div>
                                        <div class="bg-white rounded-xl p-4 border border-purple-200 shadow-sm">
                                            <div class="flex items-center space-x-2 mb-3">
                                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                                </svg>
                                                <label for="travel_class" class="block text-sm font-bold text-gray-800">Classe</label>
                                            </div>
                                            <select id="travel_class" name="travel_class"
                                                class="w-full border-2 border-purple-200 focus:border-purple-500 focus:ring-purple-500 rounded-lg shadow-sm px-3 py-2 font-semibold">
                                                <option value="ECONOMY">Économique</option>
                                                <option value="PREMIUM_ECONOMY">Économique Premium</option>
                                                <option value="BUSINESS">Affaires</option>
                                                <option value="FIRST">Première classe</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{-- Informations passagers (dynamique) --}}
                                <div id="passengersInfo" class="mb-8">
                                    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center space-x-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                        </svg>
                                        <span>Détails de chaque passager</span>
                                    </h3>
                                </div>

                                {{-- Boutons --}}
                                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-purple-200">
                                    <a href="{{ route('flights') }}"
                                        class="flex-1 text-center bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 px-6 py-4 rounded-xl font-bold hover:from-gray-200 hover:to-gray-300 transition-all duration-200 shadow-lg flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <span>Modifier la recherche</span>
                                    </a>
                                    <button type="submit"
                                        class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-black hover:from-purple-700 hover:to-purple-800 transition-all duration-200 shadow-2xl border-2 border-purple-300 flex items-center justify-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span>Réserver maintenant</span>
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    {{-- COLONNE LATÉRALE --}}
                    <div class="lg:col-span-1 mt-8 lg:mt-0">
                        <div class="sticky top-10 bg-gradient-to-br from-purple-50 to-amber-50 rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-purple-200">
                            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-purple-200">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <h2 class="text-xl md:text-2xl font-black text-purple-800">Résumé du Prix</h2>
                            </div>

                            <div class="space-y-4 text-gray-700">
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Prix par personne</span>
                                        <span class="font-bold text-purple-700">{{ number_format($selectedFlight['price'] ?? 0, 0, ',', ' ') }} €</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">Nombre de passagers</span>
                                        <span id="passengerCount" class="font-bold text-purple-700">1</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                                    <div class="flex justify-between items-center pt-2 border-t border-purple-200">
                                        <span class="font-semibold">Sous-total (Base)</span>
                                        <span id="basePrice" class="font-bold text-gray-800">{{ number_format($selectedFlight['price'] ?? 0, 2, ',', ' ') }} €</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 shadow-sm border border-purple-100">
                                    <div class="flex justify-between items-center text-sm text-gray-600">
                                        <span>Taxes et Frais (estimation)</span>
                                        <span id="taxes" class="font-semibold">{{ number_format(($selectedFlight['price'] ?? 0) * 0.1, 2, ',', ' ') }} €</span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 pt-6 border-t-2 border-purple-300">
                                <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-4 text-white">
                                    <div class="flex justify-between items-center">
                                        <span class="text-lg font-bold">TOTAL À PAYER</span>
                                        <span id="totalPrice" class="text-2xl md:text-3xl font-black">
                                            {{ number_format(($selectedFlight['price'] ?? 0) * 1.1, 2, ',', ' ') }} €
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const adultsInput = document.getElementById('adults');
                    const childrenInput = document.getElementById('children');
                    const infantsInput = document.getElementById('infants');
                    const passengerContainer = document.getElementById('passengersInfo');
                    const passengerCountSpan = document.getElementById('passengerCount');

                    function updatePassengerFields() {
                        const adults = parseInt(adultsInput.value) || 0;
                        const children = parseInt(childrenInput.value) || 0;
                        const infants = parseInt(infantsInput.value) || 0;
                        const total = adults + children + infants;

                        passengerContainer.innerHTML = '';

                        for (let i = 0; i < total; i++) {
                            let type = i < adults ? 'Adulte' : (i < adults + children ? 'Enfant' : 'Bébé');
                            let placeholderName = (i === 0) ? 'Nom complet (Responsable)' : 'Nom complet';
                            let isFirstAdult = (i === 0 && type === 'Adulte');
                            let requiredAttr = isFirstAdult ? 'required' : '';
                            let emailPlaceholder = isFirstAdult ? 'Email (Obligatoire)' : 'Email (Optionnel)';
                            let phonePlaceholder = isFirstAdult ? 'Téléphone (Obligatoire)' : 'Téléphone (Optionnel)';

                            passengerContainer.innerHTML += `
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
                                        <h4 class="font-bold mb-3 text-gray-800">${type} ${i + 1} ${isFirstAdult ? '(Principal)' : ''}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <input type="text" name="passenger_names[]" placeholder="${placeholderName}" 
                                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <input type="email" name="passenger_emails[]" placeholder="${emailPlaceholder}" 
                                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" ${requiredAttr}>
                                            <input type="tel" name="passenger_phones[]" placeholder="${phonePlaceholder}" 
                                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" ${requiredAttr}>
                                        </div>
                                    </div>
                                `;
                        }

                        updatePricing(total);
                    }

                    function updatePricing(passengers) {
                        const basePricePerPax = {{ $selectedFlight['price'] ?? 0 }};
                        const totalBase = basePricePerPax * passengers;
                        const taxes = totalBase * 0.1;
                        const total = totalBase + taxes;

                        passengerCountSpan.textContent = passengers;
                        document.getElementById('basePrice').textContent = totalBase.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });
                        document.getElementById('taxes').textContent = taxes.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });
                        document.getElementById('totalPrice').textContent = total.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });

                        // Mise à jour des champs cachés
                        document.getElementById('hidden_base_price').value = totalBase.toFixed(2);
                        document.getElementById('hidden_taxes').value = taxes.toFixed(2);
                        document.getElementById('hidden_final_price').value = total.toFixed(2);
                    }

                    [adultsInput, childrenInput, infantsInput].forEach(input => {
                        input.addEventListener('change', updatePassengerFields);
                        input.addEventListener('keyup', updatePassengerFields);
                    });

                    updatePassengerFields();
                });
            </script>
        @endif
    </div>
@endsection