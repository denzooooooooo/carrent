@extends('layouts.app')

@section('title', __('Résultats de recherche') . ' - Vols')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Header -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 py-4">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $search['departure_id'] }} → {{ $search['arrival_id'] }}
                    </h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        {{ \Carbon\Carbon::parse($search['outbound_date'])->format('d M Y') }}
                        @if($search['return_date'])
                            - {{ \Carbon\Carbon::parse($search['return_date'])->format('d M Y') }}
                        @endif
                        • {{ $search['adults'] }} {{ $search['adults'] > 1 ? 'adultes' : 'adulte' }}
                        @if($search['children'] > 0)
                            , {{ $search['children'] }} {{ $search['children'] > 1 ? 'enfants' : 'enfant' }}
                        @endif
                    </p>
                </div>
                <a href="{{ route('flights.index') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Nouvelle recherche
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <div class="flex flex-col lg:flex-row gap-6">
            <!-- Filtres (Sidebar) -->
            <div class="lg:w-64 flex-shrink-0">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-4 sticky top-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Filtres</h3>
                    
                    <!-- Prix -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Prix maximum</label>
                        <input type="range" id="priceFilter" min="0" max="1000000" step="10000" value="1000000" 
                            class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer dark:bg-gray-700">
                        <div class="flex justify-between text-xs text-gray-600 dark:text-gray-400 mt-1">
                            <span>0 XOF</span>
                            <span id="priceValue">1 000 000 XOF</span>
                        </div>
                    </div>

                    <!-- Escales -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Escales</label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" class="stops-filter rounded text-blue-600" value="0" checked>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Direct</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="stops-filter rounded text-blue-600" value="1" checked>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">1 escale</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" class="stops-filter rounded text-blue-600" value="2" checked>
                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">2+ escales</span>
                            </label>
                        </div>
                    </div>

                    <!-- Tri -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Trier par</label>
                        <select id="sortBy" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="price">Prix (croissant)</option>
                            <option value="duration">Durée (croissant)</option>
                            <option value="departure">Heure de départ</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Liste des vols -->
            <div class="flex-1">
                <div class="mb-4 flex items-center justify-between">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <span id="resultsCount">{{ count($flights) }}</span> vols trouvés
                    </p>
                </div>

                <div id="flightsList" class="space-y-4">
                    @forelse($flights as $flight)
                    <div class="flight-card bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow"
                         data-price="{{ $flight['price'] * $exchange_rate }}"
                         data-stops="{{ $flight['stops'] }}"
                         data-duration="{{ $flight['duration'] }}"
                         data-departure="{{ $flight['departure']['time'] }}">
                        
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <!-- Compagnie -->
                                <div class="flex items-center gap-3">
                                    @if($flight['airline_logo'])
                                    <img src="{{ $flight['airline_logo'] }}" alt="{{ $flight['airline_name'] }}" class="w-10 h-10 object-contain">
                                    @else
                                    <div class="w-10 h-10 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-600 dark:text-gray-400">{{ $flight['airline_code'] }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">{{ $flight['airline_name'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $flight['flight_number'] }}</p>
                                    </div>
                                </div>

                                <!-- Prix -->
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                        {{ number_format($flight['price'] * $exchange_rate, 0, ',', ' ') }} <span class="text-sm">XOF</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ number_format($flight['price'], 2) }} EUR
                                    </p>
                                </div>
                            </div>

                            <!-- Itinéraire -->
                            <div class="flex items-center justify-between mb-4">
                                <!-- Départ -->
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $flight['departure']['formatted_time'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $flight['departure']['airport'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $flight['departure']['formatted_date'] }}</p>
                                </div>

                                <!-- Durée et escales -->
                                <div class="flex-1 px-4">
                                    <div class="relative">
                                        <div class="h-0.5 bg-gray-300 dark:bg-gray-600"></div>
                                        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white dark:bg-gray-800 px-2">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="text-center mt-2">
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $flight['duration_formatted'] }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">
                                            @if($flight['stops'] == 0)
                                                Direct
                                            @elseif($flight['stops'] == 1)
                                                1 escale
                                            @else
                                                {{ $flight['stops'] }} escales
                                            @endif
                                        </p>
                                    </div>
                                </div>

                                <!-- Arrivée -->
                                <div class="text-center">
                                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $flight['arrival']['formatted_time'] }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $flight['arrival']['airport'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $flight['arrival']['formatted_date'] }}</p>
                                </div>
                            </div>

                            <!-- Détails et actions -->
                            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-4 text-xs text-gray-600 dark:text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $flight['cabin_class'] }}
                                    </span>
                                    @if(isset($flight['baggage']['checked']) && count($flight['baggage']['checked']) > 0)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        Bagage inclus
                                    </span>
                                    @endif
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('flights.details', $flight['duffel_offer_id']) }}" 
                                       class="px-4 py-2 text-sm text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-colors">
                                        Détails
                                    </a>
                                    <a href="{{ route('flights.passengers', $flight['duffel_offer_id']) }}" 
                                       class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Sélectionner
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Aucun vol trouvé</h3>
                        <p class="text-gray-600 dark:text-gray-400 mb-4">Essayez de modifier vos critères de recherche</p>
                        <a href="{{ route('flights.index') }}" class="inline-block px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                            Nouvelle recherche
                        </a>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const flightCards = document.querySelectorAll('.flight-card');
    const priceFilter = document.getElementById('priceFilter');
    const priceValue = document.getElementById('priceValue');
    const stopsFilters = document.querySelectorAll('.stops-filter');
    const sortBy = document.getElementById('sortBy');
    const resultsCount = document.getElementById('resultsCount');

    // Filtrage par prix
    priceFilter.addEventListener('input', function() {
        const maxPrice = parseInt(this.value);
        priceValue.textContent = maxPrice.toLocaleString('fr-FR') + ' XOF';
        filterFlights();
    });

    // Filtrage par escales
    stopsFilters.forEach(filter => {
        filter.addEventListener('change', filterFlights);
    });

    // Tri
    sortBy.addEventListener('change', sortFlights);

    function filterFlights() {
        const maxPrice = parseInt(priceFilter.value);
        const selectedStops = Array.from(stopsFilters)
            .filter(f => f.checked)
            .map(f => parseInt(f.value));

        let visibleCount = 0;

        flightCards.forEach(card => {
            const price = parseInt(card.dataset.price);
            const stops = parseInt(card.dataset.stops);

            const matchesPrice = price <= maxPrice;
            const matchesStops = selectedStops.includes(stops) || (stops >= 2 && selectedStops.includes(2));

            if (matchesPrice && matchesStops) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        resultsCount.textContent = visibleCount;
    }

    function sortFlights() {
        const sortValue = sortBy.value;
        const container = document.getElementById('flightsList');
        const cards = Array.from(flightCards);

        cards.sort((a, b) => {
            if (sortValue === 'price') {
                return parseInt(a.dataset.price) - parseInt(b.dataset.price);
            } else if (sortValue === 'duration') {
                return parseInt(a.dataset.duration) - parseInt(b.dataset.duration);
            } else if (sortValue === 'departure') {
                return a.dataset.departure.localeCompare(b.dataset.departure);
            }
            return 0;
        });

        cards.forEach(card => container.appendChild(card));
    }
});
</script>
@endsection
