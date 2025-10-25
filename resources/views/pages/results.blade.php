@extends('layouts.app')

@section('title', 'Résultats de recherche - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">Résultats de recherche</h1>

                <!-- Résumé de la recherche -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">De</span>
                            <p class="font-semibold">{{ $searchParams['departure_id'] }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Vers</span>
                            <p class="font-semibold">{{ $searchParams['arrival_id'] }}</p>
                        </div>
                        <div>
                            <span class="text-gray-500 dark:text-gray-400">Date départ</span>
                            <p class="font-semibold">
                                {{ \Carbon\Carbon::parse($searchParams['outbound_date'])->format('d/m/Y') }}</p>
                        </div>
                        @if(!empty($searchParams['return_date']))
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Date retour</span>
                                <p class="font-semibold">
                                    {{ \Carbon\Carbon::parse($searchParams['return_date'])->format('d/m/Y') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Statistiques de prix -->
                @if(!empty($results['price_insights']))
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                        <h3 class="text-lg font-semibold mb-4">Analyse des prix</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="text-center">
                                <span class="text-gray-500 dark:text-gray-400 block">Prix le plus bas</span>
                                <span class="text-2xl font-bold text-green-600">{{ $results['price_insights']['lowest_price'] }}
                                    €</span>
                            </div>
                            @if(!empty($results['price_insights']['typical_price_range']))
                                <div class="text-center">
                                    <span class="text-gray-500 dark:text-gray-400 block">Fourchette typique</span>
                                    <span class="text-lg font-semibold">{{ $results['price_insights']['typical_price_range'][0] }} -
                                        {{ $results['price_insights']['typical_price_range'][1] }} €</span>
                                </div>
                            @endif
                            @if(!empty($results['price_insights']['price_level']))
                                <div class="text-center">
                                    <span class="text-gray-500 dark:text-gray-400 block">Niveau de prix</span>
                                    <span
                                        class="text-lg font-semibold capitalize">{{ $results['price_insights']['price_level'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- Sidebar Filtres -->
                    <div class="lg:w-1/4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 sticky top-4">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Filtres</h3>
                                <button id="resetFilters" class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                                    Réinitialiser
                                </button>
                            </div>

                            <!-- Compteur de résultats -->
                            <div class="mb-6 p-3 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                                <span class="text-sm font-semibold text-purple-700 dark:text-purple-300" id="resultsCount">
                                    {{ count($results['best_flights'] ?? []) + count($results['other_flights'] ?? []) }}
                                    vols trouvés
                                </span>
                            </div>

                            <!-- Filtre Escales -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Escales</h4>
                                <div class="space-y-2">
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="stops" value="0"
                                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                        <span class="text-gray-600 dark:text-gray-400">Vol direct</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="stops" value="1"
                                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                        <span class="text-gray-600 dark:text-gray-400">1 escale</span>
                                    </label>
                                    <label class="flex items-center space-x-3 cursor-pointer">
                                        <input type="checkbox" name="stops" value="2"
                                            class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                        <span class="text-gray-600 dark:text-gray-400">2 escales et +</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Filtre Compagnies -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Compagnies</h4>
                                <div class="space-y-2 max-h-40 overflow-y-auto" id="airlinesList">
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
                                        <label class="flex items-center space-x-3 cursor-pointer">
                                            <input type="checkbox" name="airline" value="{{ Str::slug($airline) }}"
                                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                            <span class="text-gray-600 dark:text-gray-400 text-sm">{{ $airline }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Filtre Prix -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Prix max</h4>
                                <div class="space-y-4">
                                    @php
                                        $minPrice = $results['price_insights']['lowest_price'] ?? 0;
                                        $maxPrice = max(array_merge(
                                            collect($results['best_flights'] ?? [])->pluck('price')->toArray(),
                                            collect($results['other_flights'] ?? [])->pluck('price')->toArray()
                                        ));
                                    @endphp
                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ $minPrice }} €</span>
                                        <span id="priceValue">{{ $maxPrice }} €</span>
                                    </div>
                                    <input type="range" id="priceSlider" min="{{ $minPrice }}" max="{{ $maxPrice }}" 
                                           value="{{ $maxPrice }}" step="10"
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 slider-filter">
                                </div>
                            </div>

                            <!-- Filtre Durée -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Durée max</h4>
                                <div class="space-y-4">
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

                                    <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400">
                                        <span>{{ floor($minDuration / 60) }}h {{ $minDuration % 60 }}min</span>
                                        <span id="durationValue">{{ floor($maxDuration / 60) }}h {{ $maxDuration % 60 }}min</span>
                                    </div>
                                    <input type="range" id="durationSlider" min="{{ $minDuration }}" max="{{ $maxDuration }}" 
                                           value="{{ $maxDuration }}" step="30"
                                        class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700 slider-filter">
                                </div>
                            </div>

                            <!-- Filtre Horaires -->
                            <div class="mb-6">
                                <h4 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Horaires de Départ</h4>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="space-y-2">
                                        <span class="text-gray-500 dark:text-gray-400 block">Matin</span>
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="departure_time" value="00-06"
                                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                            <span class="text-gray-600 dark:text-gray-400">00:00 - 06:00</span>
                                        </label>
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="departure_time" value="06-12"
                                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                            <span class="text-gray-600 dark:text-gray-400">06:00 - 12:00</span>
                                        </label>
                                    </div>
                                    <div class="space-y-2">
                                        <span class="text-gray-500 dark:text-gray-400 block">Soir</span>
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="departure_time" value="12-18"
                                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                            <span class="text-gray-600 dark:text-gray-400">12:00 - 18:00</span>
                                        </label>
                                        <label class="flex items-center space-x-2 cursor-pointer">
                                            <input type="checkbox" name="departure_time" value="18-24"
                                                class="w-4 h-4 text-purple-600 rounded focus:ring-purple-500 filter-checkbox">
                                            <span class="text-gray-600 dark:text-gray-400">18:00 - 00:00</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contenu principal -->
                    <div class="lg:w-3/4">
                        <!-- Tri des résultats -->
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-4 mb-6">
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Trier par:</span>
                                    <select id="sortSelect"
                                        class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                        <option value="best">Meilleur choix</option>
                                        <option value="price_asc">Prix (croissant)</option>
                                        <option value="price_desc">Prix (décroissant)</option>
                                        <option value="duration_asc">Durée (croissante)</option>
                                        <option value="duration_desc">Durée (décroissante)</option>
                                    </select>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="text-sm text-gray-600 dark:text-gray-400" id="visibleResultsCount">
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
                                                $durationMinutes = is_numeric($flight['total_duration']) ? (int)$flight['total_duration'] : 0;
                                                $firstFlight = $flight['flights'][0] ?? [];
                                                $departureTime = $firstFlight['departure_airport']['time'] ?? '';
                                                $departureHour = $departureTime ? \Carbon\Carbon::parse($departureTime)->format('H') : '';
                                            @endphp
                                            <div class="flight-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-2 border-green-200 dark:border-green-800"
                                                data-price="{{ $flight['price'] }}"
                                                data-duration="{{ $durationMinutes }}"
                                                data-stops="{{ $stopsCount }}"
                                                data-airline="{{ Str::slug($flight['airline'] ?? '') }}"
                                                data-departure-time="{{ $departureHour }}"
                                                data-best="true">
                                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-4 mb-3">
                                                            @if(isset($flight['flights'][0]['airline_logo']))
                                                                <img src="{{ $flight['flights'][0]['airline_logo'] }}"
                                                                    alt="{{ $flight['airline'] }}" class="w-8 h-8 rounded">
                                                            @endif
                                                            <span class="font-semibold text-lg">{{ $flight['airline'] }}</span>
                                                            <span
                                                                class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Recommandé</span>
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
                                                                    <div class="text-center">
                                                                        <span
                                                                            class="bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 text-xs px-2 py-1 rounded">
                                                                            Escale à {{ $flight['layovers'][$segmentIndex]['name'] }}:
                                                                            {{ $flight['layovers'][$segmentIndex]['duration'] ?? '' }}
                                                                        </span>
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

                                                    <div class="mt-4 lg:mt-0 lg:ml-6 text-center lg:text-right">
                                                        <div class="text-2xl font-bold text-green-600 mb-2">
                                                            {{ $flight['price'] }} €
                                                        </div>
                                                        @if(isset($flight['departure_token']))
                                                            <form action="{{ route('flights.booking') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="departure_token"
                                                                    value="{{ $flight['departure_token'] }}">
                                                                <input type="hidden" name="booking_token"
                                                                    value="{{ $flight['booking_token'] ?? '' }}">
                                                                <button type="submit"
                                                                    class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                                                    Sélectionner
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="https://www.google.com/travel/flights" target="_blank"
                                                                class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                                                Voir sur Google Flights
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
                                                $durationMinutes = is_numeric($flight['total_duration']) ? (int)$flight['total_duration'] : 0;
                                                $firstFlight = $flight['flights'][0] ?? [];
                                                $departureTime = $firstFlight['departure_airport']['time'] ?? '';
                                                $departureHour = $departureTime ? \Carbon\Carbon::parse($departureTime)->format('H') : '';
                                            @endphp
                                            <div class="flight-card bg-white dark:bg-gray-800 rounded-lg shadow-md p-6"
                                                data-price="{{ $flight['price'] }}"
                                                data-duration="{{ $durationMinutes }}"
                                                data-stops="{{ $stopsCount }}"
                                                data-airline="{{ Str::slug($flight['airline'] ?? '') }}"
                                                data-departure-time="{{ $departureHour }}"
                                                data-best="false">
                                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                                    <div class="flex-1">
                                                        <div class="flex items-center space-x-4 mb-3">
                                                            @if(isset($flight['flights'][0]['airline_logo']))
                                                                <img src="{{ $flight['flights'][0]['airline_logo'] }}"
                                                                    alt="{{ $flight['airline'] }}" class="w-8 h-8 rounded">
                                                            @endif
                                                            <span class="font-semibold">{{ $flight['airline'] }}</span>
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

                                                    <div class="mt-4 lg:mt-0 lg:ml-6 text-center lg:text-right">
                                                        <div class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                            {{ $flight['price'] }} €
                                                        </div>
                                                        @if(isset($flight['departure_token']))
                                                            <form action="{{ route('flights.booking') }}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="departure_token"
                                                                    value="{{ $flight['departure_token'] }}">
                                                                <input type="hidden" name="booking_token"
                                                                    value="{{ $flight['booking_token'] ?? '' }}">
                                                                <button type="submit"
                                                                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                                                    Voir détails
                                                                </button>
                                                            </form>
                                                        @else
                                                            <a href="https://www.google.com/travel/flights" target="_blank"
                                                                class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors">
                                                                Voir sur Google Flights
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
                                <div class="text-center py-12">
                                    <div class="text-gray-500 dark:text-gray-400 text-lg mb-4">
                                        Aucun vol trouvé pour ces critères de recherche.
                                    </div>
                                    <a href="{{ route('flights') }}"
                                        class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                                        Nouvelle recherche
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