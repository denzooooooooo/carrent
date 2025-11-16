@extends('layouts.app')

@section('title', 'Résultats - Aller-Retour - Carré Premium')

@section('content')
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl lg:text-4xl font-black mb-2">🔄 Vols Aller-Retour</h1>
            <p class="text-lg opacity-90">
                {{ $searchParams['departure_id'] ?? '' }} ⇄ {{ $searchParams['arrival_id'] ?? '' }}
            </p>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
        <div class="container mx-auto px-4 py-6">

            {{-- FORMULAIRE DE RECHERCHE --}}
            <div class="bg-white rounded-2xl shadow-xl p-4 mb-6 border-2 border-purple-100 sticky top-0 z-40">
                <form method="POST" action="{{ route('flights.search') }}" class="flex flex-wrap items-end gap-3">
                    @csrf
                    <input type="hidden" name="type" value="1">

                    {{-- Départ --}}
                    <div class="flex-1 min-w-[150px]">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Départ</label>
                        <input type="text" name="departure_display" value="{{ $searchParams['departure_id'] ?? '' }}"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold"
                            placeholder="ABJ" readonly>
                        <input type="hidden" name="departure_id" value="{{ $searchParams['departure_id'] ?? '' }}">
                    </div>

                    {{-- Arrivée --}}
                    <div class="flex-1 min-w-[150px]">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Arrivée</label>
                        <input type="text" name="arrival_display" value="{{ $searchParams['arrival_id'] ?? '' }}"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold"
                            placeholder="CDG" readonly>
                        <input type="hidden" name="arrival_id" value="{{ $searchParams['arrival_id'] ?? '' }}">
                    </div>

                    {{-- Date départ --}}
                    <div class="flex-1 min-w-[130px]">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Départ</label>
                        <input type="date" name="outbound_date" value="{{ $searchParams['outbound_date'] ?? '' }}"
                            min="{{ date('Y-m-d') }}"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold">
                    </div>

                    {{-- Date retour --}}
                    <div class="flex-1 min-w-[130px]">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Retour</label>
                        <input type="date" name="return_date" value="{{ $searchParams['return_date'] ?? '' }}"
                            min="{{ $searchParams['outbound_date'] ?? date('Y-m-d') }}"
                            class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold">
                    </div>

                    {{-- Passagers --}}
                    <div class="flex-1 min-w-[100px]">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Adultes</label>
                        <select name="adults" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold">
                            @for($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}" {{ ($searchParams['adults'] ?? 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Classe --}}
                    <div class="flex-1 min-w-[130px]">
                        <label class="text-xs font-bold text-gray-700 mb-1 block">Classe</label>
                        <select name="travel_class" class="w-full px-3 py-2 border-2 border-gray-300 rounded-lg text-sm font-semibold">
                            <option value="ECONOMY" {{ ($searchParams['travel_class'] ?? 'ECONOMY') == 'ECONOMY' ? 'selected' : '' }}>Économique</option>
                            <option value="PREMIUM_ECONOMY" {{ ($searchParams['travel_class'] ?? '') == 'PREMIUM_ECONOMY' ? 'selected' : '' }}>Premium</option>
                            <option value="BUSINESS" {{ ($searchParams['travel_class'] ?? '') == 'BUSINESS' ? 'selected' : '' }}>Affaires</option>
                            <option value="FIRST" {{ ($searchParams['travel_class'] ?? '') == 'FIRST' ? 'selected' : '' }}>Première</option>
                        </select>
                    </div>

                    {{-- Boutons --}}
                    <div class="flex gap-2">
                        <button type="submit"
                            class="bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-2 rounded-lg font-bold hover:from-purple-700 hover:to-purple-800 transition-all shadow-lg text-sm">
                            🔍 Modifier
                        </button>
                        <a href="{{ route('flights.index') }}"
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-bold hover:bg-gray-300 transition-all text-sm">
                            ↺ Nouveau
                        </a>
                    </div>
                </form>
            </div>

            <div class="flex flex-col lg:flex-row gap-6">
                {{-- Sidebar Filtres (identique à one-way) --}}
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

                {{-- Contenu principal --}}
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
                        @if(!empty($results['best_flights']))
                            <div class="mb-8" id="bestFlights">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                    <span class="text-3xl">✅</span>
                                    Meilleurs vols aller-retour
                                </h2>
                                <div class="space-y-4">
                                    @foreach($results['best_flights'] as $flight)
                                        <x-round-trip-flight-card :flight="$flight" :searchParams="$searchParams" :isBest="true" />
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(!empty($results['other_flights']))
                            <div id="otherFlights">
                                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                    <span class="text-3xl">✈️</span>
                                    Autres vols aller-retour ({{ count($results['other_flights']) }})
                                </h2>
                                <div class="space-y-4">
                                    @foreach($results['other_flights'] as $flight)
                                        <x-round-trip-flight-card :flight="$flight" :searchParams="$searchParams" :isBest="false" />
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        @if(empty($results['best_flights']) && empty($results['other_flights']))
                            <div class="text-center py-20 bg-white rounded-3xl shadow-2xl">
                                <div class="text-6xl mb-4">😕</div>
                                <h3 class="text-2xl font-black text-gray-800 mb-4">Aucun vol trouvé</h3>
                                <a href="{{ route('flights.index') }}"
                                    class="inline-block bg-gradient-to-r from-purple-600 to-purple-700 text-white px-10 py-5 rounded-2xl font-bold shadow-2xl hover:from-purple-700 hover:to-purple-800 transition-all">
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
    @endpush
@endsection