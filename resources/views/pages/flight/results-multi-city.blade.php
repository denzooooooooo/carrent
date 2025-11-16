@extends('layouts.app')

@section('title', 'Résultats - Multi-villes - Carré Premium')

@section('content')
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl lg:text-4xl font-black mb-2">🌍 Vols Multi-villes</h1>
            <p class="text-lg opacity-90">Itinéraire personnalisé</p>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
        <div class="container mx-auto px-4 py-6">

            {{-- FORMULAIRE COMPACT --}}
            <div class="bg-white rounded-xl shadow-lg p-4 mb-6 border border-purple-100 sticky top-0 z-40">
                @if(!empty($searchParams['multi_city_json']))
                    @php
                        $multiCityFlights = json_decode($searchParams['multi_city_json'], true);
                    @endphp

                    <div class="flex items-center justify-between gap-4">
                        {{-- Résumé compact --}}
                        <div class="flex-1 flex items-center gap-2 overflow-x-auto">
                            @foreach($multiCityFlights as $index => $cityFlight)
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="px-3 py-1 bg-purple-100 text-purple-800 rounded-lg font-bold text-sm">
                                        {{ $cityFlight['departure_id'] }} → {{ $cityFlight['arrival_id'] }}
                                    </span>
                                    @if(!$loop->last)
                                        <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                        </svg>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        {{-- Infos passagers --}}
                        <div class="flex items-center gap-3 text-sm font-semibold text-gray-700 flex-shrink-0">
                            <span>👤 {{ $searchParams['adults'] ?? 1 }}</span>
                            <span class="text-gray-400">•</span>
                            <span>{{ $searchParams['travel_class'] ?? 'ECONOMY' }}</span>
                        </div>

                        {{-- Bouton modifier --}}
                        <button type="button" onclick="toggleEditForm()" 
                            class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-purple-700 transition-all text-sm flex-shrink-0">
                            ✏️ Modifier
                        </button>
                    </div>

                    {{-- Formulaire d'édition (masqué par défaut) --}}
                    <div id="editForm" class="hidden mt-4 pt-4 border-t border-purple-200">
                        <form method="POST" action="{{ route('flights.search') }}" id="multiCityForm">
                            @csrf
                            <input type="hidden" name="type" value="3">
                            
                            <div id="segmentsContainer" class="space-y-2 mb-3">
                                @foreach($multiCityFlights as $index => $cityFlight)
                                    <div class="segment-item flex items-center gap-2">
                                        <span class="segment-number w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        
                                        <input type="text" 
                                            name="segments[{{ $index }}][departure]" 
                                            value="{{ $cityFlight['departure_id'] }}"
                                            class="w-20 px-2 py-1 border border-purple-300 rounded text-sm font-semibold text-center"
                                            placeholder="ABJ" required>
                                        
                                        <span class="text-purple-400">→</span>
                                        
                                        <input type="text" 
                                            name="segments[{{ $index }}][arrival]" 
                                            value="{{ $cityFlight['arrival_id'] }}"
                                            class="w-20 px-2 py-1 border border-purple-300 rounded text-sm font-semibold text-center"
                                            placeholder="FCO" required>
                                        
                                        <input type="date" 
                                            name="segments[{{ $index }}][date]" 
                                            value="{{ $cityFlight['date'] }}"
                                            min="{{ date('Y-m-d') }}"
                                            class="px-2 py-1 border border-purple-300 rounded text-sm font-semibold"
                                            required>

                                        @if($index > 1)
                                            <button type="button" onclick="removeSegment(this)" 
                                                class="w-6 h-6 rounded-full bg-red-500 text-white hover:bg-red-600 transition-all flex items-center justify-center text-xs flex-shrink-0">
                                                ✕
                                            </button>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="flex items-center gap-2 mb-3">
                                <button type="button" onclick="addSegment()" 
                                    class="bg-purple-500 text-white px-3 py-1 rounded text-xs font-bold hover:bg-purple-600 transition-all">
                                    ➕ Ajouter
                                </button>
                                
                                <select name="adults" class="px-2 py-1 border border-gray-300 rounded text-xs font-semibold">
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ ($searchParams['adults'] ?? 1) == $i ? 'selected' : '' }}>{{ $i }} adulte{{ $i > 1 ? 's' : '' }}</option>
                                    @endfor
                                </select>

                                <select name="travel_class" class="px-2 py-1 border border-gray-300 rounded text-xs font-semibold">
                                    <option value="ECONOMY" {{ ($searchParams['travel_class'] ?? 'ECONOMY') == 'ECONOMY' ? 'selected' : '' }}>Économique</option>
                                    <option value="PREMIUM_ECONOMY" {{ ($searchParams['travel_class'] ?? '') == 'PREMIUM_ECONOMY' ? 'selected' : '' }}>Premium</option>
                                    <option value="BUSINESS" {{ ($searchParams['travel_class'] ?? '') == 'BUSINESS' ? 'selected' : '' }}>Affaires</option>
                                    <option value="FIRST" {{ ($searchParams['travel_class'] ?? '') == 'FIRST' ? 'selected' : '' }}>Première</option>
                                </select>
                            </div>

                            <div class="flex gap-2">
                                <button type="submit"
                                    class="bg-purple-600 text-white px-4 py-2 rounded-lg font-bold hover:bg-purple-700 transition-all text-sm">
                                    🔍 Rechercher
                                </button>
                                <button type="button" onclick="toggleEditForm()"
                                    class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg font-bold hover:bg-gray-300 transition-all text-sm">
                                    Annuler
                                </button>
                            </div>
                        </form>
                    </div>
                @endif
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Sidebar Filtres --}}
                <div class="lg:w-1/4">
                    <div class="bg-white rounded-2xl shadow-2xl p-6 sticky top-24 border border-purple-100">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black text-gray-900">🎯 Filtres</h3>
                            <button id="resetFilters"
                                class="text-sm bg-gradient-to-r from-purple-600 to-purple-700 text-white px-3 py-1 rounded-lg hover:from-purple-700 hover:to-purple-800 transition-all font-bold shadow-lg">
                                Réinitialiser
                            </button>
                        </div>

                        <div class="mb-6 p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">
                            <span class="text-sm font-black text-purple-700" id="resultsCount">
                                {{ count($results['best_flights'] ?? []) + count($results['other_flights'] ?? []) }} vols trouvés
                            </span>
                        </div>

                        {{-- Filtre Escales --}}
                        <div class="mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">✈️ Escales</h4>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-2 cursor-pointer p-2 rounded-lg hover:bg-purple-50">
                                    <input type="checkbox" value="0" class="filter-stops w-4 h-4 text-purple-600 rounded">
                                    <span class="text-gray-700 font-medium text-sm">Vol direct</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-2 rounded-lg hover:bg-purple-50">
                                    <input type="checkbox" value="1" class="filter-stops w-4 h-4 text-purple-600 rounded">
                                    <span class="text-gray-700 font-medium text-sm">1 escale</span>
                                </label>
                                <label class="flex items-center space-x-2 cursor-pointer p-2 rounded-lg hover:bg-purple-50">
                                    <input type="checkbox" value="2+" class="filter-stops w-4 h-4 text-purple-600 rounded">
                                    <span class="text-gray-700 font-medium text-sm">2+ escales</span>
                                </label>
                            </div>
                        </div>

                        {{-- Filtre Compagnies --}}
                        <div class="mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">🛫 Compagnies</h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto" id="airlinesList">
                                @php
                                    $airlines = collect();
                                    if (!empty($results['best_flights'])) {
                                        foreach ($results['best_flights'] as $flight) {
                                            $airlines->push($flight['airline']);
                                        }
                                    }
                                    if (!empty($results['other_flights'])) {
                                        foreach ($results['other_flights'] as $flight) {
                                            $airlines->push($flight['airline']);
                                        }
                                    }
                                    $uniqueAirlines = $airlines->unique()->filter()->sort()->values();
                                @endphp

                                @foreach($uniqueAirlines as $airline)
                                    <label class="flex items-center space-x-2 cursor-pointer p-2 rounded-lg hover:bg-purple-50">
                                        <input type="checkbox" value="{{ Str::slug($airline) }}"
                                            class="filter-airline w-4 h-4 text-purple-600 rounded">
                                        <span class="text-gray-700 font-medium text-sm">{{ $airline }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Filtre Prix --}}
                        <div class="mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">💰 Prix maximum</h4>
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">
                                @php
                                    $allPrices = collect($results['best_flights'] ?? [])->pluck('price')
                                        ->merge(collect($results['other_flights'] ?? [])->pluck('price'))
                                        ->filter(fn($p) => is_numeric($p))
                                        ->map(fn($p) => (float) $p);

                                    $minPrice = $allPrices->min() ?? 0;
                                    $maxPrice = $allPrices->max() ?? 1000000;
                                @endphp

                                <div class="flex justify-between text-sm font-bold text-purple-700 mb-2">
                                    <span>{{ number_format($minPrice) }} XOF</span>
                                    <span id="priceValue">{{ number_format($maxPrice) }} XOF</span>
                                </div>
                                <input type="range" id="priceSlider" min="{{ $minPrice }}" max="{{ $maxPrice }}"
                                    value="{{ $maxPrice }}" step="1000"
                                    class="w-full h-3 bg-gradient-to-r from-purple-200 to-amber-200 rounded-lg appearance-none cursor-pointer">
                            </div>
                        </div>

                        {{-- Filtre Durée --}}
                        <div class="mb-6">
                            <h4 class="font-bold text-gray-900 mb-3">⏱️ Durée maximum</h4>
                            <div class="p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">
                                @php
                                    $durations = collect($results['best_flights'] ?? [])->pluck('total_duration_minutes')
                                        ->merge(collect($results['other_flights'] ?? [])->pluck('total_duration_minutes'))
                                        ->filter(fn($d) => is_numeric($d))
                                        ->map(fn($d) => (int) $d);

                                    $minDuration = $durations->min() ?? 0;
                                    $maxDuration = $durations->max() ?? 1440;
                                @endphp

                                <div class="flex justify-between text-sm font-bold text-purple-700 mb-2">
                                    <span>{{ floor($minDuration / 60) }}h {{ $minDuration % 60 }}min</span>
                                    <span id="durationValue">{{ floor($maxDuration / 60) }}h {{ $maxDuration % 60 }}min</span>
                                </div>
                                <input type="range" id="durationSlider" min="{{ $minDuration }}" max="{{ $maxDuration }}"
                                    value="{{ $maxDuration }}" step="30"
                                    class="w-full h-3 bg-gradient-to-r from-purple-200 to-amber-200 rounded-lg appearance-none cursor-pointer">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTENU PRINCIPAL --}}
                <div class="lg:w-3/4">
                    {{-- Tri --}}
                    <div class="bg-white rounded-2xl shadow-2xl p-4 mb-6 border-2 border-purple-100">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <span class="text-base font-bold text-purple-700">Trier par:</span>
                                <select id="sortSelect"
                                    class="border-2 border-purple-200 rounded-xl px-4 py-2 bg-gradient-to-r from-purple-50 to-amber-50 text-gray-900 focus:ring-2 focus:ring-purple-500 font-semibold text-sm shadow-lg">
                                    <option value="best">Meilleur choix</option>
                                    <option value="price_asc">Prix croissant</option>
                                    <option value="price_desc">Prix décroissant</option>
                                    <option value="duration_asc">Durée croissante</option>
                                    <option value="duration_desc">Durée décroissante</option>
                                </select>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-bold text-purple-700" id="visibleResultsCount">
                                    {{ count($results['best_flights'] ?? []) + count($results['other_flights'] ?? []) }} résultats
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Résultats --}}
                    <div id="searchResults">
                        
                        {{-- 1. BILLETS SÉPARÉS --}}
                        @if(isset($separateTickets))
                            <div class="mb-8">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                    <span class="text-3xl">💰</span>
                                    Option Économique
                                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-orange-100 text-orange-700">
                                        MOINS CHER
                                    </span>
                                </h2>

                                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl shadow-2xl p-6 border-2 border-orange-300">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center shadow-lg">
                                                <span class="text-2xl">💰</span>
                                            </div>
                                            <div>
                                                <h3 class="text-2xl font-black text-orange-900">Billets séparés</h3>
                                                <p class="text-sm text-orange-700 font-semibold">Non protégés - Risque élevé</p>
                                            </div>
                                        </div>

                                        @if(isset($savings))
                                            <div class="text-right">
                                                <div class="text-sm font-bold text-orange-700 mb-1">ÉCONOMIE</div>
                                                <div class="text-3xl font-black text-green-600">
                                                    -{{ number_format($savings['amount']) }} {{ $savings['currency'] }}
                                                </div>
                                                <div class="text-sm font-bold text-green-600">
                                                    ({{ $savings['percentage'] }}%)
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="bg-white rounded-xl p-4 mb-4 border-2 border-orange-200">
                                        <div class="flex items-center justify-between">
                                            <span class="text-lg font-bold text-gray-700">Prix Total</span>
                                            <div>
                                                <div class="text-4xl font-black text-orange-600">
                                                    {{ $separateTickets['formatted_total_price'] }}
                                                </div>
                                                <div class="text-xs text-gray-600 text-right mt-1">
                                                    Prix total le plus bas
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="space-y-3 mb-4">
                                        @foreach($separateTickets['legs'] as $leg)
                                            <div class="bg-white rounded-xl p-4 border-2 border-orange-200">
                                                <div class="flex items-center justify-between mb-2">
                                                    <div class="flex items-center gap-3">
                                                        <span class="w-8 h-8 rounded-full bg-orange-500 text-white flex items-center justify-center font-bold text-sm">
                                                            {{ $leg['leg_number'] }}
                                                        </span>
                                                        <div>
                                                            <div class="font-bold text-gray-900">
                                                                {{ \Carbon\Carbon::parse($leg['date'])->translatedFormat('l d F') }}
                                                            </div>
                                                            <div class="text-xs text-gray-600">
                                                                {{ $leg['airline'] }} • {{ $leg['duration'] }}min
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="text-right">
                                                        <div class="text-xl font-black text-orange-600">
                                                            {{ $leg['formatted_price'] }}
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <div class="text-2xl font-black text-gray-900">
                                                            {{ \Carbon\Carbon::parse($leg['flights'][0]['departure_airport']['time'])->format('H:i') }}
                                                        </div>
                                                        <div class="text-sm font-bold text-gray-700">{{ $leg['departure'] }}</div>
                                                    </div>

                                                    <div class="flex-1 px-4 text-center">
                                                        <div class="relative flex items-center">
                                                            <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                                                            <div class="flex-1 h-1 bg-orange-300"></div>
                                                            <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                                                        </div>
                                                        @if(!empty($leg['layovers']))
                                                            <div class="text-xs text-gray-600 mt-1">
                                                                {{ count($leg['layovers']) }} arrêt{{ count($leg['layovers']) > 1 ? 's' : '' }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <div class="text-right">
                                                        <div class="text-2xl font-black text-gray-900">
                                                            {{ \Carbon\Carbon::parse(end($leg['flights'])['arrival_airport']['time'])->format('H:i') }}
                                                        </div>
                                                        <div class="text-sm font-bold text-gray-700">{{ $leg['arrival'] }}</div>
                                                    </div>
                                                </div>

                                                @if(!empty($leg['layovers']))
                                                    <div class="mt-3 pt-3 border-t border-orange-200 text-sm text-gray-600">
                                                        @foreach($leg['layovers'] as $layover)
                                                            <span class="font-semibold">{{ $layover['duration'] }}min</span> {{ $layover['name'] }}{{ !$loop->last ? ' • ' : '' }}
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="bg-red-50 border-2 border-red-300 rounded-xl p-4 mb-4">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <div>
                                                <h4 class="font-black text-red-900 mb-2 text-lg">⚠️ RISQUES IMPORTANTS</h4>
                                                <ul class="space-y-2 text-sm text-red-800">
                                                    @foreach($separateTickets['warnings'] as $warning)
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-red-600 font-bold">•</span>
                                                            <span class="font-semibold">{{ $warning }}</span>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-4">
                                        <div class="flex items-start gap-3">
                                            <span class="text-2xl">⚠️</span>
                                            <div>
                                                <h4 class="font-black text-yellow-900 mb-1">Billets séparés</h4>
                                                <p class="text-sm text-yellow-800 font-semibold">
                                                    Les {{ count($separateTickets['legs']) }} billets doivent être réservés individuellement.
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Recommandation --}}
                        @if(isset($recommendation))
                            <div class="bg-gradient-to-r from-purple-100 to-blue-100 border-2 border-purple-300 rounded-xl p-6 mb-8">
                                <div class="flex items-start gap-4">
                                    <span class="text-4xl">💡</span>
                                    <div class="flex-1">
                                        <h4 class="font-black text-purple-900 mb-2 text-xl">Notre Recommandation</h4>
                                        <p class="text-base text-purple-800 font-semibold leading-relaxed">{{ $recommendation }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- 2. BILLETS PROTÉGÉS --}}
                        @if(!empty($results['best_flights']))
                            <div class="mb-8" id="bestFlights">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                    <span class="text-3xl">✅</span>
                                    Billets Protégés 
                                    <span class="text-sm font-semibold px-3 py-1 rounded-full bg-green-100 text-green-700">
                                        RECOMMANDÉ
                                    </span>
                                </h2>
                                <div class="space-y-4">
                                    @foreach($results['best_flights'] as $flight)
                                        <x-multi-city-flight-card :flight="$flight" :searchParams="$searchParams" :isBest="true" />
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- 3. AUTRES VOLS --}}
                        @if(!empty($results['other_flights']))
                            <div id="otherFlights">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                    <span class="text-3xl">✈️</span>
                                    Autres vols ({{ count($results['other_flights']) }})
                                </h2>
                                <div class="space-y-4">
                                    @foreach($results['other_flights'] as $flight)
                                        <x-multi-city-flight-card :flight="$flight" :searchParams="$searchParams" :isBest="false" />
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(empty($results['best_flights']) && empty($results['other_flights']) && !isset($separateTickets))
                            <div class="text-center py-20 bg-white rounded-3xl shadow-2xl">
                                <div class="text-6xl mb-4">✈️</div>
                                <h3 class="text-2xl font-black text-gray-800 mb-4">Aucun vol trouvé</h3>
                                <a href="{{ route('flights.index') }}"
                                    class="inline-block bg-purple-600 text-white px-10 py-5 rounded-2xl font-bold shadow-2xl hover:bg-purple-700 transition-all">
                                    🔍 Nouvelle recherche
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="{{ asset('js/flight-filters.js') }}"></script>
        <script>
            let segmentCount = {{ count($multiCityFlights ?? []) }};

            function toggleEditForm() {
                const form = document.getElementById('editForm');
                form.classList.toggle('hidden');
            }

            function addSegment() {
                const container = document.getElementById('segmentsContainer');
                const lastSegment = container.querySelector('.segment-item:last-child');
                const lastDate = lastSegment.querySelector('input[type="date"]').value;
                
                const newSegment = `
                    <div class="segment-item flex items-center gap-2">
                        <span class="segment-number w-6 h-6 rounded-full bg-purple-600 text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                            ${segmentCount + 1}
                        </span>
                        <input type="text" name="segments[${segmentCount}][departure]" class="w-20 px-2 py-1 border border-purple-300 rounded text-sm font-semibold text-center" placeholder="ABJ" required>
                        <span class="text-purple-400">→</span>
                        <input type="text" name="segments[${segmentCount}][arrival]" class="w-20 px-2 py-1 border border-purple-300 rounded text-sm font-semibold text-center" placeholder="FCO" required>
                        <input type="date" name="segments[${segmentCount}][date]" value="${lastDate}" min="{{ date('Y-m-d') }}" class="px-2 py-1 border border-purple-300 rounded text-sm font-semibold" required>
                        <button type="button" onclick="removeSegment(this)" class="w-6 h-6 rounded-full bg-red-500 text-white hover:bg-red-600 transition-all flex items-center justify-center text-xs flex-shrink-0">✕</button>
                    </div>
                `;
                
                container.insertAdjacentHTML('beforeend', newSegment);
                segmentCount++;
                updateSegmentNumbers();
            }

            function removeSegment(button) {
                if (document.querySelectorAll('.segment-item').length > 2) {
                    button.closest('.segment-item').remove();
                    updateSegmentNumbers();
                } else {
                    alert('Minimum 2 vols requis');
                }
            }

            function updateSegmentNumbers() {
                document.querySelectorAll('.segment-item').forEach((row, index) => {
                    row.querySelector('.segment-number').textContent = index + 1;
                    row.querySelectorAll('input').forEach(input => {
                        const name = input.getAttribute('name');
                        if (name) {
                            input.setAttribute('name', name.replace(/segments\[\d+\]/, `segments[${index}]`));
                        }
                    });
                });
            }

            document.getElementById('multiCityForm').addEventListener('submit', function(e) {
                const segments = [];
                document.querySelectorAll('.segment-item').forEach((row) => {
                    const inputs = row.querySelectorAll('input');
                    segments.push({
                        departure_id: inputs[0].value.toUpperCase(),
                        arrival_id: inputs[1].value.toUpperCase(),
                        date: inputs[2].value
                    });
                });

                const jsonInput = document.createElement('input');
                jsonInput.type = 'hidden';
                jsonInput.name = 'multi_city_json';
                jsonInput.value = JSON.stringify(segments);
                this.appendChild(jsonInput);
            });
        </script>
    @endpush
@endsection