
@extends('layouts.app')

@section('title', 'Résultats de recherche - Carré Premium')

@section('content')
    <!-- Hero Section with Purple Gradient -->
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl lg:text-5xl font-black mb-4">Résultats de recherche</h1>
            <p class="text-xl opacity-90">Découvrez les meilleurs vols pour votre voyage</p>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6">
                <!-- Search Summary Card -->
                <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-6 border-2 border-purple-100">
                    <div class="flex items-center justify-center mb-6">
                        <div class="flex items-center space-x-3">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <h3 class="text-xl md:text-2xl font-black text-gray-900">Récapitulatif de recherche</h3>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                        <!-- Départ -->
                        <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-4 md:p-6 border border-blue-200 shadow-lg">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="bg-blue-500 rounded-full p-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                </div>
                                <span class="text-blue-700 font-bold text-sm md:text-base">Départ</span>
                            </div>
                            <p class="font-black text-gray-900 text-lg md:text-xl">{{ $searchParams['departure_id'] }}</p>
                        </div>

                        <!-- Arrivée -->
                        <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 md:p-6 border border-green-200 shadow-lg">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="bg-green-500 rounded-full p-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                </div>
                                <span class="text-green-700 font-bold text-sm md:text-base">Arrivée</span>
                            </div>
                            <p class="font-black text-gray-900 text-lg md:text-xl">{{ $searchParams['arrival_id'] }}</p>
                        </div>

                        <!-- Date départ -->
                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 md:p-6 border border-purple-200 shadow-lg">
                            <div class="flex items-center space-x-3 mb-3">
                                <div class="bg-purple-500 rounded-full p-2">
                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                </div>
                                <span class="text-purple-700 font-bold text-sm md:text-base">Date départ</span>
                            </div>
                            <p class="font-black text-gray-900 text-lg md:text-xl">
                                {{ \Carbon\Carbon::parse($searchParams['outbound_date'])->format('d/m/Y') }}
                            </p>
                        </div>

                        <!-- Date retour (si existe) -->
                        @if(!empty($searchParams['return_date']))
                            <div class="bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 md:p-6 border border-amber-200 shadow-lg">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="bg-amber-500 rounded-full p-2">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-amber-700 font-bold text-sm md:text-base">Date retour</span>
                                </div>
                                <p class="font-black text-gray-900 text-lg md:text-xl">
                                    {{ \Carbon\Carbon::parse($searchParams['return_date'])->format('d/m/Y') }}
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Price Insights Card -->
                @if(!empty($results['price_insights']))
                    <div class="bg-white rounded-2xl shadow-2xl p-6 mb-6 border border-purple-100">
                        <h3 class="text-xl font-black text-gray-900 mb-6 text-center">Analyse des prix</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="text-center bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                <span class="text-green-700 font-bold block mb-2">Prix le plus bas</span>
                                <span class="text-3xl font-black text-green-600">{{ $results['price_insights']['lowest_price'] }} €</span>
                            </div>
                            @if(!empty($results['price_insights']['typical_price_range']))
                                <div class="text-center bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-4 border border-purple-200">
                                    <span class="text-purple-700 font-bold block mb-2">Fourchette typique</span>
                                    <span class="text-lg font-black text-purple-900">{{ $results['price_insights']['typical_price_range'][0] }} - {{ $results['price_insights']['typical_price_range'][1] }} €</span>
                                </div>
                            @endif
                            @if(!empty($results['price_insights']['price_level']))
                                <div class="text-center bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl p-4 border border-amber-200">
                                    <span class="text-amber-700 font-bold block mb-2">Niveau de prix</span>
                                    <span class="text-lg font-black text-amber-900 capitalize">{{ $results['price_insights']['price_level'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Sidebar Filtres -->
                    <div class="lg:w-1/4">
                        <div class="bg-white rounded-2xl shadow-2xl p-6 sticky top-4 border border-purple-100">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-black text-gray-900">Filtres</h3>
                                <button id="resetFilters" class="text-sm bg-gradient-to-r from-purple-600 to-purple-700 text-white px-3 py-1 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200 font-bold shadow-lg">
                                    Réinitialiser
                                </button>
                            </div>

                            <!-- Compteur de résultats -->
                            <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">
                                <span class="text-sm font-black text-purple-700" id="resultsCount">
                                    {{ count($results['best_flights'] ?? []) + count($results['other_flights'] ?? []) }}
                                    vols trouvés
                                </span>
                            </div>

                            <!-- Filtre Escales -->
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-900 mb-4">Escales</h4>
                                <div class="space-y-3">
                                    <label class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-purple-50 transition-colors">
                                        <input type="checkbox" name="stops" value="0"
                                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                        <span class="text-gray-700 font-medium">Vol direct</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-purple-50 transition-colors">
                                        <input type="checkbox" name="stops" value="1"
                                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                        <span class="text-gray-700 font-medium">1 escale</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-purple-50 transition-colors">
                                        <input type="checkbox" name="stops" value="2"
                                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                        <span class="text-gray-700 font-medium">2 escales et +</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Filtre Compagnies -->
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-900 mb-4">Compagnies</h4>
                                <div class="space-y-3 max-h-40 overflow-y-auto" id="airlinesList">
                                    @php
                                        $airlines = collect();
                                        if (!empty($results['best_flights'])) {
                                            foreach ($results['best_flights'] as $flight) {
                                                $airlines = $airlines->merge(collect($flight['flights'])->pluck('airline'));
                                            }
                                        }
                                        if (!empty($results['other_flights'])) {
                                            foreach ($results['other_flights'] as $flight) {
                                                $airlines = $airlines->merge(collect($flight['flights'])->pluck('airline'));
                                            }
                                        }
                                        $uniqueAirlines = $airlines->unique()->filter()->values();
                                    @endphp

                                    @foreach($uniqueAirlines as $airline)
                                        <label class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-purple-50 transition-colors">
                                            <input type="checkbox" name="airline" value="{{ Str::slug($airline) }}"
                                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                            <span class="text-gray-700 font-medium text-sm">{{ $airline }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Filtre Prix -->
                            <div class="mb-6">
                                <h4 class="font-bold text-gray-900 mb-4">Prix max</h4>
                                <div class="space-y-4 p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">

                                    @php
                                        $minPrice = isset($results['price_insights']['lowest_price']) ? (float) $results['price_insights']['lowest_price'] : 0.0;

                                        // Récupère tous les prix valides (numériques)
                                        $bestPrices = collect($results['best_flights'] ?? [])
                                            ->pluck('price')
                                            ->filter(function ($p) {
                                                return is_numeric($p); })
                                            ->map(function ($p) {
                                                return (float) $p; })
                                            ->values()
                                            ->toArray();

                                        $otherPrices = collect($results['other_flights'] ?? [])
                                            ->pluck('price')
                                            ->filter(function ($p) {
                                                return is_numeric($p); })
                                            ->map(function ($p) {
                                                return (float) $p; })
                                            ->values()
                                            ->toArray();

                                        $allPrices = array_merge($bestPrices, $otherPrices);

                                        // Si aucun prix n'existe, on tombe sur minPrice ou 0
                                        $maxPrice = !empty($allPrices) ? max($allPrices) : max($minPrice, 0);
                                    @endphp

                                        <div class="flex justify-between text-sm font-bold text-purple-700">
                                            <span>{{ $minPrice }} €</span>
                                            <span id="priceValue">{{ $maxPrice }} €</span>
                                        </div>
                                        <input type="range" id="priceSlider" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                                               value="{{ $maxPrice }}" step="10"
                                            class="w-full h-3 bg-gradient-to-r from-purple-200 to-amber-200 rounded-lg appearance-none cursor-pointer slider-filter">
                                    </div>
                                </div>

                                <!-- Filtre Durée -->
                                <div class="mb-6">
                                    <h4 class="font-bold text-gray-900 mb-4">Durée max</h4>
                                    <div class="space-y-4 p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">
                                        @php
                                            $durations = collect();
                                            if (!empty($results['best_flights'])) {
                                                $durations = $durations->merge(
                                                    collect($results['best_flights'])
                                                        ->pluck('total_duration')
                                                        ->map(function ($d) {
                                                            return is_numeric($d) ? (int) $d : 0;
                                                        })
                                                );
                                            }
                                            if (!empty($results['other_flights'])) {
                                                $durations = $durations->merge(
                                                    collect($results['other_flights'])
                                                        ->pluck('total_duration')
                                                        ->map(function ($d) {
                                                            return is_numeric($d) ? (int) $d : 0;
                                                        })
                                                );
                                            }

                                            $minDuration = (int) ($durations->min() ?? 0);
                                            $maxDuration = (int) ($durations->max() ?? 0);
                                        @endphp

                                        <div class="flex justify-between text-sm font-bold text-purple-700">
                                            <span>{{ floor($minDuration / 60) }}h {{ $minDuration % 60 }}min</span>
                                            <span id="durationValue">{{ floor($maxDuration / 60) }}h {{ $maxDuration % 60 }}min</span>
                                        </div>
                                        <input type="range" id="durationSlider" min="{{ $minDuration }}" max="{{ $maxDuration }}"
                                               value="{{ $maxDuration }}" step="30"
                                            class="w-full h-3 bg-gradient-to-r from-purple-200 to-amber-200 rounded-lg appearance-none cursor-pointer slider-filter">
                                    </div>
                                </div>

                                <!-- Filtre Horaires -->
                                <div class="mb-6">
                                    <h4 class="font-bold text-gray-900 mb-4">Horaires de Départ</h4>
                                    <div class="grid grid-cols-2 gap-3 text-sm">
                                        <div class="space-y-3 p-3 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200">
                                            <span class="text-purple-700 font-bold block">Matin</span>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="departure_time" value="00-06"
                                                    class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                                <span class="text-gray-700 font-medium">00:00 - 06:00</span>
                                            </label>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="departure_time" value="06-12"
                                                    class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                                <span class="text-gray-700 font-medium">06:00 - 12:00</span>
                                            </label>
                                        </div>
                                        <div class="space-y-3 p-3 bg-gradient-to-br from-amber-50 to-amber-100 rounded-xl border border-amber-200">
                                            <span class="text-amber-700 font-bold block">Soir</span>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="departure_time" value="12-18"
                                                    class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                                <span class="text-gray-700 font-medium">12:00 - 18:00</span>
                                            </label>
                                            <label class="flex items-center space-x-2 cursor-pointer">
                                                <input type="checkbox" name="departure_time" value="18-24"
                                                    class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                                <span class="text-gray-700 font-medium">18:00 - 00:00</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Contenu principal -->
                        <div class="lg:w-3/4">
                            <!-- Tri des résultats -->
                            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-6 md:mb-8 border-2 border-purple-100">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 md:gap-6">
                                    <div class="flex flex-col sm:flex-row sm:items-center space-y-3 sm:space-y-0 sm:space-x-4 md:space-x-6">
                                        <div class="flex items-center space-x-2">
                                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                            </svg>
                                            <span class="text-base md:text-lg font-bold text-purple-700">Trier par:</span>
                                        </div>
                                        <select id="sortSelect"
                                            class="border-2 border-purple-200 rounded-xl px-4 md:px-6 py-2 md:py-3 bg-gradient-to-r from-purple-50 to-amber-50 text-gray-900 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 font-semibold text-sm md:text-base shadow-lg w-full sm:w-auto">
                                            <option value="best">Meilleur choix</option>
                                            <option value="price_asc">Prix croissant</option>
                                            <option value="price_desc">Prix décroissant</option>
                                            <option value="duration_asc">Durée croissante</option>
                                            <option value="duration_desc">Durée décroissante</option>
                                        </select>
                                    </div>
                                    <div class="flex items-center justify-center sm:justify-end space-x-2">
                                        <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        <span class="text-sm md:text-lg font-bold text-purple-700" id="visibleResultsCount">
                                            {{ count($results['best_flights'] ?? []) + count($results['other_flights'] ?? []) }} résultats
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Résultats de recherche -->
                            <div id="searchResults">
                                <!-- Meilleurs vols -->
                                @if(!empty($results['best_flights']))
                                    <div class="mb-8">
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Meilleurs vols
                                            ({{ count($results['best_flights']) }})</h2>
                                        <div class="space-y-4" id="bestFlights">
                                            @foreach($results['best_flights'] as $index => $flight)
                                                @php
                                                    $stopsCount = count($flight['layovers'] ?? []);
                                                    $durationMinutes = is_numeric($flight['total_duration']) ? (int) $flight['total_duration'] : 0;
                                                    $firstFlight = $flight['flights'][0] ?? [];
                                                    $departureTime = $firstFlight['departure_airport']['time'] ?? '';
                                                    $departureHour = $departureTime ? \Carbon\Carbon::parse($departureTime)->format('H') : '';
                                                @endphp
                                                <div class="flight-card bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-green-300 hover:shadow-3xl hover:scale-102 transition-all duration-300 transform"
                                                    data-price="{{ $flight['price'] }}"
                                                    data-duration="{{ $durationMinutes }}"
                                                    data-stops="{{ $stopsCount }}"
                                                    data-airline="{{ Str::slug($flight['airline'] ?? '') }}"
                                                    data-departure-time="{{ $departureHour }}"
                                                    data-best="true">
                                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-4 mb-4">
                                                                @if(isset($flight['flights'][0]['airline_logo']))
                                                                    <img src="{{ $flight['flights'][0]['airline_logo'] }}"
                                                                        alt="{{ $flight['airline'] }}" class="w-10 h-10 rounded-xl shadow-lg">
                                                                @endif
                                                                <span class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</span>
                                                                <span
                                                                    class="bg-gradient-to-r from-green-400 to-green-600 text-white text-xs px-3 py-2 rounded-full font-bold shadow-lg">Recommandé</span>
                                                            </div>

                                                            <!-- Itinéraire -->
                                                            <div class="space-y-3 mb-4">
                                                                @foreach($flight['flights'] as $segmentIndex => $segment)
                                                                    <div class="flex items-center space-x-4 text-sm">
                                                                        <div class="flex-1">
                                                                            <div class="font-semibold">
                                                                                {{ $segment['departure_airport']['name'] ?? '' }}</div>
                                                                            <div class="text-gray-600 dark:text-gray-400">
                                                                                {{ $segment['departure_airport']['time'] ?? '' }}</div>
                                                                        </div>
                                                                        <div class="flex flex-col items-center">
                                                                            <div class="w-8 h-px bg-gray-300"></div>
                                                                            <span
                                                                                class="text-xs text-gray-500">{{ $segment['duration'] }}</span>
                                                                        </div>
                                                                        <div class="flex-1 text-right">
                                                                            <div class="font-semibold">
                                                                                {{ $segment['arrival_airport']['name'] ?? '' }}</div>
                                                                            <div class="text-gray-600 dark:text-gray-400">
                                                                                {{ $segment['arrival_airport']['time'] ?? '' }}</div>
                                                                        </div>
                                                                    </div>

                                                                    @if(isset($flight['layovers'][$segmentIndex]))
                                                                        <div class="flex items-center justify-center space-x-2 my-2">
                                                                            <div class="flex-1 h-px bg-gradient-to-r from-gray-200 to-gray-300"></div>
                                                                            <div class="bg-gradient-to-r from-amber-100 to-orange-100 border border-amber-200 rounded-lg px-3 py-2 shadow-sm">
                                                                                <div class="flex items-center space-x-2">
                                                                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                                    </svg>
                                                                                    <div class="text-xs">
                                                                                        <div class="font-bold text-amber-800">{{ $flight['layovers'][$segmentIndex]['name'] }}</div>
                                                                                        <div class="text-amber-700">{{ $flight['layovers'][$segmentIndex]['duration'] ?? '' }}</div>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="flex-1 h-px bg-gradient-to-l from-gray-200 to-gray-300"></div>
                                                                        </div>
                                                                    @endif
                                                                @endforeach
                                                            </div>

                                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                                Durée totale:
                                                                {{ $flight['total_duration'] ?? '' }}
                                                                @if(isset($flight['carbon_emissions']))
                                                                    • Émissions:
                                                                    {{ round($flight['carbon_emissions']['this_flight'] / 1000) }} kg CO₂
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="mt-6 lg:mt-0 lg:ml-6 text-center lg:text-right">
                                                            <div class="text-3xl md:text-4xl font-black text-green-600 mb-4">
                                                                {{ $flight['price'] }} €
                                                            </div>
                                                            @if (isset($flight['booking_token']))
                                                                @php
                                                                    // Récupérez les paramètres de recherche initiaux passés à la vue
                                                                    $queryParams = [
                                                                        'booking_token' => $flight['booking_token'],
                                                                        'departure_id' => $searchParams['departure_id'] ?? null,
                                                                        'arrival_id' => $searchParams['arrival_id'] ?? null,
                                                                        'outbound_date' => $searchParams['outbound_date'] ?? null,
                                                                    ];

                                                                    // N'ajouter return_date que s'il existe et n'est pas vide
                                                                    if (!empty($searchParams['return_date'])) {
                                                                        $queryParams['return_date'] = $searchParams['return_date'];
                                                                    }
                                                                @endphp
                                                                <a href="{{ route('flights.details', $queryParams) }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 md:px-8 py-3 md:py-4 rounded-xl font-bold shadow-2xl hover:from-purple-700 hover:to-purple-800 hover:scale-105 transition-all duration-200 transform flex items-center justify-center space-x-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                    </svg>
                                                                    <span>Détails du Vol</span>
                                                                </a>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Autres vols -->
                                @if(!empty($results['other_flights']))
                                    <div>
                                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Autres vols</h2>
                                        <div class="space-y-4" id="otherFlights">
                                            @foreach($results['other_flights'] as $flight)
                                                @php
                                                    $stopsCount = count($flight['layovers'] ?? []);
                                                    $durationMinutes = is_numeric($flight['total_duration']) ? (int) $flight['total_duration'] : 0;
                                                    $firstFlight = $flight['flights'][0] ?? [];
                                                    $departureTime = $firstFlight['departure_airport']['time'] ?? '';
                                                    $departureHour = $departureTime ? \Carbon\Carbon::parse($departureTime)->format('H') : '';
                                                @endphp
                                                <div class="flight-card bg-white rounded-2xl shadow-2xl p-6 border border-purple-100 hover:shadow-3xl hover:scale-102 transition-all duration-300 transform"
                                                    data-price="{{ $flight['price'] }}"
                                                    data-duration="{{ $durationMinutes }}"
                                                    data-stops="{{ $stopsCount }}"
                                                    data-airline="{{ Str::slug($flight['airline'] ?? '') }}"
                                                    data-departure-time="{{ $departureHour }}"
                                                    data-best="false">
                                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                                        <div class="flex-1">
                                                            <div class="flex items-center space-x-4 mb-4">
                                                                @if(isset($flight['flights'][0]['airline_logo']))
                                                                    <img src="{{ $flight['flights'][0]['airline_logo'] }}"
                                                                        alt="{{ $flight['airline'] }}" class="w-10 h-10 rounded-xl shadow-lg">
                                                                @endif
                                                                <span class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</span>
                                                            </div>

                                                            <!-- Itinéraire simplifié -->
                                                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                                @if(!empty($flight['flights']))
                                                                    {{ $flight['flights'][0]['departure_airport']['name'] ?? '' }} →
                                                                    {{ $flight['flights'][count($flight['flights']) - 1]['arrival_airport']['name'] ?? '' }}
                                                                @endif
                                                            </div>

                                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                                Durée: {{ $flight['total_duration'] ?? '' }} •
                                                                {{ count($flight['flights']) }} segment(s)
                                                                @if(isset($flight['layovers']) && count($flight['layovers']) > 0)
                                                                    • {{ count($flight['layovers']) }} escale(s)
                                                                @endif
                                                            </div>
                                                        </div>

                                                        <div class="mt-6 lg:mt-0 lg:ml-6 text-center lg:text-right">
                                                            <div class="text-3xl font-black text-gray-900 mb-3">
                                                                {{ $flight['price'] }} €
                                                            </div>
                                                                @if (isset($flight['booking_token']))
                                                                    @php
                                                                        // Récupérez les paramètres de recherche initiaux passés à la vue
                                                                        $queryParams = [
                                                                            'booking_token' => $flight['booking_token'],
                                                                            'departure_id' => $searchParams['departure_id'] ?? null,
                                                                            'arrival_id' => $searchParams['arrival_id'] ?? null,
                                                                            'outbound_date' => $searchParams['outbound_date'] ?? null,
                                                                        ];

                                                                        // N'ajouter return_date que s'il existe et n'est pas vide
                                                                        if (!empty($searchParams['return_date'])) {
                                                                            $queryParams['return_date'] = $searchParams['return_date'];
                                                                        }
                                                                    @endphp
                                                                    <a href="{{ route('flights.details', $queryParams) }}" class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-4 rounded-xl font-black shadow-2xl hover:from-purple-700 hover:to-purple-800 hover:scale-105 transition-all duration-200 transform">
                                                                        🚀 Détails du Vol
                                                                    </a>
                                                                @endif

                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if(empty($results['best_flights']) && empty($results['other_flights']))
                                    <div class="text-center py-16 md:py-20 bg-gradient-to-br from-purple-50 via-amber-50 to-purple-100 rounded-3xl border-2 border-purple-200 shadow-2xl">
                                        <div class="mb-6 md:mb-8">
                                            <div class="mb-4">
                                                <svg class="w-16 h-16 md:w-20 md:h-20 text-purple-400 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-xl md:text-2xl font-black text-gray-800 mb-4">Aucun vol trouvé</h3>
                                            <p class="text-gray-600 text-base md:text-lg font-medium">
                                                Aucun vol ne correspond à vos critères de recherche.
                                            </p>
                                        </div>
                                        <a href="{{ route('flights') }}"
                                            class="inline-block bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 md:px-10 py-4 md:py-5 rounded-2xl font-bold shadow-2xl hover:from-purple-700 hover:to-purple-800 hover:scale-105 transition-all duration-300 transform flex items-center justify-center space-x-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                            </svg>
                                            <span>Nouvelle recherche</span>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection

@push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        class FlightFilter {
            constructor() {
                this.filters = {
                    maxPrice: {{ $maxPrice ?? 1000 }},
                    maxDuration: {{ $maxDuration ?? 1440 }},
                    stops: [],
                    airlines: [],
                    departureTimes: []
                };

                this.init();
            }

            init() {
                this.bindEvents();
                this.updateVisibleCount();
            }

            bindEvents() {
                // Sliders
                const priceSlider = document.getElementById('priceSlider');
                const durationSlider = document.getElementById('durationSlider');

                priceSlider.addEventListener('input', (e) => {
                    this.filters.maxPrice = parseInt(e.target.value);
                    document.getElementById('priceValue').textContent = `${this.filters.maxPrice} €`;
                    this.debouncedFilter();
                });

                durationSlider.addEventListener('input', (e) => {
                    this.filters.maxDuration = parseInt(e.target.value);
                    const hours = Math.floor(this.filters.maxDuration / 60);
                    const minutes = this.filters.maxDuration % 60;
                    document.getElementById('durationValue').textContent = `${hours}h ${minutes}min`;
                    this.debouncedFilter();
                });

                // Checkboxes
                document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', () => {
                        this.updateCheckboxFilters();
                        this.debouncedFilter();
                    });
                });

                // Tri
                document.getElementById('sortSelect').addEventListener('change', (e) => {
                    this.sortFlights(e.target.value);
                });

                // Réinitialisation
                document.getElementById('resetFilters').addEventListener('click', () => {
                    this.resetFilters();
                });
            }

            updateCheckboxFilters() {
                // Escales
                this.filters.stops = Array.from(document.querySelectorAll('input[name="stops"]:checked'))
                    .map(cb => parseInt(cb.value));

                // Compagnies
                this.filters.airlines = Array.from(document.querySelectorAll('input[name="airline"]:checked'))
                    .map(cb => cb.value);

                // Horaires
                this.filters.departureTimes = Array.from(document.querySelectorAll('input[name="departure_time"]:checked'))
                    .map(cb => cb.value);
            }

            filterFlights() {
                const flightCards = document.querySelectorAll('.flight-card');
                let visibleCount = 0;

                flightCards.forEach(card => {
                    const price = parseInt(card.dataset.price);
                    const duration = parseInt(card.dataset.duration);
                    const stops = parseInt(card.dataset.stops);
                    const airline = card.dataset.airline;
                    const departureTime = card.dataset.departureTime;
                    const isBest = card.dataset.best === 'true';

                    let show = true;

                    // Filtre prix
                    if (price > this.filters.maxPrice) {
                        show = false;
                    }

                    // Filtre durée
                    if (duration > this.filters.maxDuration) {
                        show = false;
                    }

                    // Filtre escales
                    if (this.filters.stops.length > 0 && !this.filters.stops.includes(stops)) {
                        show = false;
                    }

                    // Filtre compagnies
                    if (this.filters.airlines.length > 0 && !this.filters.airlines.includes(airline)) {
                        show = false;
                    }

                    // Filtre horaires de départ
                    if (this.filters.departureTimes.length > 0 && departureTime) {
                        const hour = parseInt(departureTime);
                        let timeMatch = false;

                        this.filters.departureTimes.forEach(timeRange => {
                            const [start, end] = timeRange.split('-').map(Number);
                            if (hour >= start && hour < end) {
                                timeMatch = true;
                            }
                        });

                        if (!timeMatch) {
                            show = false;
                        }
                    }

                    // Appliquer l'affichage
                    card.style.display = show ? 'block' : 'none';
                    if (show) visibleCount++;
                });

                this.updateVisibleCount(visibleCount);
                this.updateSectionHeaders();
            }

            sortFlights(sortBy) {
                const bestFlightsContainer = document.getElementById('bestFlights');
                const otherFlightsContainer = document.getElementById('otherFlights');

                const sortFunctions = {
                    price_asc: (a, b) => parseInt(a.dataset.price) - parseInt(b.dataset.price),
                    price_desc: (a, b) => parseInt(b.dataset.price) - parseInt(a.dataset.price),
                    duration_asc: (a, b) => parseInt(a.dataset.duration) - parseInt(b.dataset.duration),
                    duration_desc: (a, b) => parseInt(b.dataset.duration) - parseInt(a.dataset.duration),
                    best: (a, b) => {
                        // Meilleurs vols d'abord, puis par prix croissant
                        if (a.dataset.best !== b.dataset.best) {
                            return a.dataset.best === 'true' ? -1 : 1;
                        }
                        return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                    }
                };

                const sortFunction = sortFunctions[sortBy] || sortFunctions.best;

                // Trier les meilleurs vols
                if (bestFlightsContainer) {
                    const bestFlights = Array.from(bestFlightsContainer.querySelectorAll('.flight-card'));
                    bestFlights.sort(sortFunction);
                    bestFlights.forEach(flight => bestFlightsContainer.appendChild(flight));
                }

                // Trier les autres vols
                if (otherFlightsContainer) {
                    const otherFlights = Array.from(otherFlightsContainer.querySelectorAll('.flight-card'));
                    otherFlights.sort(sortFunction);
                    otherFlights.forEach(flight => otherFlightsContainer.appendChild(flight));
                }
            }

            updateVisibleCount(visibleCount = null) {
                if (visibleCount === null) {
                    visibleCount = document.querySelectorAll('.flight-card[style*="display: block"]').length;
                }
                document.getElementById('visibleResultsCount').textContent = `${visibleCount} résultats`;
            }

            updateSectionHeaders() {
                const bestVisible = document.querySelectorAll('#bestFlights .flight-card[style*="display: block"]').length;
                const otherVisible = document.querySelectorAll('#otherFlights .flight-card[style*="display: block"]').length;

                const bestHeader = document.querySelector('h2:contains("Meilleurs vols")');
                const otherHeader = document.querySelector('h2:contains("Autres vols")');

                if (bestHeader && bestVisible > 0) {
                    bestHeader.textContent = `Meilleurs vols (${bestVisible})`;
                }

                if (otherHeader && otherVisible > 0) {
                    otherHeader.textContent = `Autres vols (${otherVisible})`;
                }
            }

            resetFilters() {
                // Réinitialiser les sliders
                document.getElementById('priceSlider').value = {{ $maxPrice ?? 1000 }};
                document.getElementById('durationSlider').value = {{ $maxDuration ?? 1440 }};

                // Réinitialiser les checkboxes
                document.querySelectorAll('.filter-checkbox').forEach(checkbox => {
                    checkbox.checked = false;
                });

                // Réinitialiser le tri
                document.getElementById('sortSelect').value = 'best';

                // Mettre à jour les filtres
                this.filters = {
                    maxPrice: {{ $maxPrice ?? 1000 }},
                    maxDuration: {{ $maxDuration ?? 1440 }},
                    stops: [],
                    airlines: [],
                    departureTimes: []
                };

                // Mettre à jour l'interface
                document.getElementById('priceValue').textContent = `${this.filters.maxPrice} €`;
                const hours = Math.floor(this.filters.maxDuration / 60);
                const minutes = this.filters.maxDuration % 60;
                document.getElementById('durationValue').textContent = `${hours}h ${minutes}min`;

                // Réappliquer les filtres
                this.filterFlights();
                this.sortFlights('best');
            }

            debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            get debouncedFilter() {
                return this.debounce(() => this.filterFlights(), 300);
            }
        }

        // Initialiser le système de filtrage
        new FlightFilter();
    });             
    </script>
@endpush