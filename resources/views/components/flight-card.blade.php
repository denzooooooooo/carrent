@props(['flight', 'searchParams', 'isBest' => false])

@php
    $stopsCount = count($flight['layovers'] ?? []);
    $durationMinutes = $flight['total_duration_minutes'] ?? 0;
    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);

    // Déterminer le type de vol
    $isMultiCity = isset($searchParams['type']) && $searchParams['type'] == 3;
    $isRoundTrip = !empty($searchParams['return_date']) && !$isMultiCity;

    if ($isMultiCity) {
        $flightType = 'Multi-villes';
    } elseif ($isRoundTrip) {
        $flightType = 'Aller-Retour';
    } else {
        $flightType = 'Aller Simple';
    }

    // Calcul du prix
    $pricePerPerson = $flight['price'] ?? 0;
    $totalPrice = $pricePerPerson * $totalPassengers;
@endphp

<div class="flight-card bg-white rounded-2xl shadow-xl p-6 border-2 {{ $isBest ? 'border-green-400' : 'border-purple-200' }} hover:shadow-3xl transition-all duration-300"
    data-price="{{ $pricePerPerson }}" data-duration="{{ $durationMinutes }}" data-stops="{{ $stopsCount }}"
    data-airline="{{ Str::slug($flight['airline'] ?? '') }}" data-best="{{ $isBest ? 'true' : 'false' }}">

    {{-- Header: Compagnie + Badge --}}
    <div
        class="flex items-center justify-between mb-6 pb-4 border-b-2 {{ $isBest ? 'border-green-200' : 'border-gray-200' }}">
        <div class="flex items-center space-x-4">
            @if(!empty($flight['flights'][0]['airline_logo']))
                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="{{ $flight['airline'] }}"
                    class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2 border-2 border-gray-100">
            @else
                <div
                    class="w-14 h-14 rounded-xl shadow-lg bg-gradient-to-br from-purple-100 to-amber-100 flex items-center justify-center border-2 border-gray-200">
                    <span class="text-2xl">✈️</span>
                </div>
            @endif

            <div>
                <h3 class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span
                        class="text-xs font-semibold px-2 py-1 rounded-full 
                        {{ $isMultiCity ? 'bg-orange-100 text-orange-700' : ($isRoundTrip ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700') }}">
                        {{ $flightType }}
                    </span>
                    @if($isBest)
                        <span
                            class="text-xs font-bold px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow-md">
                            ⭐ Recommandé
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Prix en haut à droite --}}
        <div class="text-right">
            <div class="text-xs text-gray-500 font-semibold uppercase mb-1">
                {{ $totalPassengers > 1 ? "Prix pour {$totalPassengers} passagers" : 'Prix / personne' }}
            </div>
            <div class="text-3xl font-black {{ $isBest ? 'text-green-600' : 'text-purple-700' }}">
                {{ number_format($totalPassengers > 1 ? $totalPrice : $pricePerPerson) }}
            </div>
            <div class="text-xs font-bold text-gray-600">
                XOF {{ $totalPassengers > 1 ? 'total' : '' }}
            </div>
            @if($totalPassengers > 1)
                <div class="text-xs text-gray-500 mt-1">
                    ({{ number_format($pricePerPerson) }} XOF/pers.)
                </div>
            @endif
        </div>
    </div>

    {{-- AFFICHAGE MULTI-VILLES --}}
    @if($isMultiCity)
        <div class="space-y-4">
            {{-- Afficher tous les segments du vol --}}
            @if(!empty($flight['flights']))
                @php
                    // Grouper les vols par itinéraire
                    $segments = [];
                    $currentSegment = [];

                    foreach ($flight['flights'] as $index => $segment) {
                        $currentSegment[] = $segment;

                        // Si c'est le dernier vol OU si le prochain vol part d'un aéroport différent
                        if (
                            $index === count($flight['flights']) - 1 ||
                            (isset($flight['flights'][$index + 1]) &&
                                $flight['flights'][$index + 1]['departure_airport']['id'] !== $segment['arrival_airport']['id'])
                        ) {
                            $segments[] = $currentSegment;
                            $currentSegment = [];
                        }
                    }
                @endphp

                @foreach($segments as $segmentIndex => $segmentFlights)
                    @php
                        $firstFlight = $segmentFlights[0];
                        $lastFlight = $segmentFlights[count($segmentFlights) - 1];
                        $segmentLayovers = array_slice($flight['layovers'] ?? [], 0, count($segmentFlights) - 1);
                    @endphp

                    <div class="bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl p-5 border-2 border-orange-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-orange-800 uppercase tracking-wide flex items-center gap-2">
                                <span class="text-lg">🛫</span> Segment {{ $segmentIndex + 1 }}
                            </h4>
                            <div class="text-xs font-semibold text-orange-700">
                                {{ \Carbon\Carbon::parse($firstFlight['departure_time'])->format('D d M Y') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            {{-- Départ --}}
                            <div class="text-left flex-1 min-w-0 pr-3">
                                <div class="text-4xl font-black text-orange-900">
                                    {{ \Carbon\Carbon::parse($firstFlight['departure_time'])->format('H:i') }}
                                </div>
                                <div class="text-sm font-bold text-orange-700 mt-2">
                                    {{ $firstFlight['departure_airport']['id'] }}
                                </div>
                                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                                    {{ $firstFlight['departure_airport']['name'] ?? '' }}
                                </div>
                            </div>

                            {{-- Timeline --}}
                            <div class="flex-shrink-0 w-32 px-4">
                                <div class="text-center">
                                    @php
                                        // Calculer la durée totale du segment
                                        $segmentDuration = 0;
                                        foreach ($segmentFlights as $sf) {
                                            $segmentDuration += (int) ($sf['duration_minutes'] ?? $sf['duration'] ?? 0);
                                        }

                                        // Ajouter les durées des escales
                                        if (!empty($segmentLayovers)) {
                                            foreach ($segmentLayovers as $layover) {
                                                $segmentDuration += (int) ($layover['duration_minutes'] ?? 0);
                                            }
                                        }
                                    @endphp
                                    <div class="text-xs font-bold text-orange-700 mb-2">
                                        {{ floor($segmentDuration / 60) }}h {{ $segmentDuration % 60 }}min
                                    </div>
                                    <div class="relative flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-orange-500 shadow-md"></div>
                                        <div class="flex-1 h-1 bg-gradient-to-r from-orange-500 to-orange-400"></div>
                                        <div class="w-3 h-3 rounded-full bg-orange-500 shadow-md"></div>
                                    </div>
                                    <div class="mt-2">
                                        @php $stops = count($segmentFlights) - 1; @endphp
                                        <span
                                            class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                                                                {{ $stops == 0 ? 'bg-green-200 text-green-800' : 'bg-amber-200 text-amber-800' }}">
                                            {{ $stops == 0 ? '✈️ Direct' : "🔄 {$stops} escale" . ($stops > 1 ? 's' : '') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            {{-- Arrivée --}}
                            <div class="text-right flex-1 min-w-0 pl-3">
                                <div class="text-4xl font-black text-orange-900">
                                    {{ \Carbon\Carbon::parse($lastFlight['arrival_time'])->format('H:i') }}
                                </div>
                                <div class="text-sm font-bold text-orange-700 mt-2">
                                    {{ $lastFlight['arrival_airport']['id'] }}
                                </div>
                                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                                    {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                                </div>
                            </div>
                        </div>

                        {{-- Escales pour ce segment --}}
                        @if(!empty($segmentLayovers))
                            <div class="mt-4 pt-4 border-t border-orange-300">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="text-xs font-bold text-amber-700">📍 ESCALES</span>
                                </div>
                                @foreach($segmentLayovers as $layover)
                                    <div class="flex items-center gap-2 text-sm mb-1">
                                        <span class="font-bold text-orange-800">{{ $layover['id'] }}</span>
                                        <span class="text-gray-600">{{ $layover['name'] }}</span>
                                        <span class="text-amber-600 font-semibold">({{ $layover['duration'] }})</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif

            {{-- Note importante --}}
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl">
                <div class="flex items-start space-x-3">
                    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                            clip-rule="evenodd" />
                    </svg>
                    <div>
                        <p class="text-sm font-bold text-blue-800">Vol multi-villes</p>
                        <p class="text-xs text-blue-700 mt-1">
                            Ce tarif couvre tous les segments de votre itinéraire multi-villes.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- AFFICHAGE ALLER-RETOUR ET ALLER SIMPLE --}}
    @else
        @if(!empty($flight['flights']))
            <div class="space-y-6">
                {{-- Section Aller --}}
                <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200">
                    <div class="flex items-center justify-between mb-3">
                        <h4 class="text-sm font-bold text-blue-800 uppercase tracking-wide flex items-center gap-2">
                            <span class="text-lg">🛫</span> Vol Aller
                        </h4>
                        <div class="text-xs font-semibold text-blue-700">
                            {{ \Carbon\Carbon::parse($flight['flights'][0]['departure_time'])->format('D d M Y') }}
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        {{-- Départ --}}
                        <div class="text-left flex-1 min-w-0 pr-3">
                            <div class="text-4xl font-black text-blue-900">
                                {{ \Carbon\Carbon::parse($flight['flights'][0]['departure_time'])->format('H:i') }}
                            </div>
                            <div class="text-sm font-bold text-blue-700 mt-2">
                                {{ $flight['flights'][0]['departure_airport']['code'] ?? $flight['flights'][0]['departure_airport']['id'] ?? '' }}
                            </div>
                            <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                                {{ $flight['flights'][0]['departure_airport']['name'] ?? '' }}
                            </div>
                            @if(!empty($flight['flights'][0]['departure_airport']['city']))
                                <div class="text-xs text-gray-600 truncate">
                                    📍 {{ $flight['flights'][0]['departure_airport']['city'] }}
                                </div>
                            @endif
                        </div>

                        {{-- Timeline du vol --}}
                        <div class="flex-shrink-0 w-32 px-4">
                            <div class="text-center">
                                <div class="text-xs font-bold text-blue-700 mb-2">
                                    {{ $flight['total_duration'] }}
                                </div>
                                <div class="relative flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-md"></div>
                                    <div class="flex-1 h-1 bg-gradient-to-r from-blue-500 to-blue-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-blue-500 shadow-md"></div>
                                </div>
                                <div class="mt-2">
                                    <span
                                        class="inline-block px-3 py-1 rounded-full text-xs font-bold
                                                                {{ $stopsCount == 0 ? 'bg-green-200 text-green-800' : 'bg-amber-200 text-amber-800' }}">
                                        {{ $stopsCount == 0 ? '✈️ Direct' : "🔄 {$stopsCount} escale" . ($stopsCount > 1 ? 's' : '') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Arrivée --}}
                        <div class="text-right flex-1 min-w-0 pl-3">
                            @php
                                $lastFlight = $flight['flights'][count($flight['flights']) - 1];
                            @endphp
                            <div class="text-4xl font-black text-blue-900">
                                {{ \Carbon\Carbon::parse($lastFlight['arrival_time'])->format('H:i') }}
                            </div>
                            <div class="text-sm font-bold text-blue-700 mt-2">
                                {{ $lastFlight['arrival_airport']['code'] ?? $lastFlight['arrival_airport']['id'] ?? '' }}
                            </div>
                            <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                                {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                            </div>
                            @if(!empty($lastFlight['arrival_airport']['city']))
                                <div class="text-xs text-gray-600 truncate">
                                    📍 {{ $lastFlight['arrival_airport']['city'] }}
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Escales Aller --}}
                    @if($stopsCount > 0 && !empty($flight['layovers']))
                        <div class="mt-4 pt-4 border-t border-blue-300">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-amber-700">📍 ESCALES</span>
                            </div>
                            @foreach($flight['layovers'] as $layover)
                                <div class="flex items-center gap-2 text-sm mb-1">
                                    <span class="font-bold text-blue-800">{{ $layover['id'] }}</span>
                                    <span class="text-gray-600">{{ $layover['name'] }}</span>
                                    <span class="text-amber-600 font-semibold">({{ $layover['duration'] }})</span>
                                    @if($layover['overnight'] ?? false)
                                        <span class="text-xs bg-amber-200 text-amber-800 px-2 py-0.5 rounded-full">🌙 Nuit</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- VOL RETOUR (si aller-retour) --}}
                @if($isRoundTrip && isset($flight['return_flights']))
                    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-5 border-2 border-purple-200">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-bold text-purple-800 uppercase tracking-wide flex items-center gap-2">
                                <span class="text-lg">🛬</span> Vol Retour
                            </h4>
                            <div class="text-xs font-semibold text-purple-700">
                                {{ \Carbon\Carbon::parse($searchParams['return_date'])->format('D d M Y') }}
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="text-left flex-1">
                                <div class="text-4xl font-black text-purple-900">--:--</div>
                                <div class="text-sm font-bold text-purple-700 mt-2">
                                    {{ $searchParams['arrival_id'] }}
                                </div>
                                <div class="text-xs text-gray-600">Vol retour disponible</div>
                            </div>

                            <div class="flex-shrink-0 w-32">
                                <div class="text-center">
                                    <div class="relative flex items-center">
                                        <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                                        <div class="flex-1 h-1 bg-gradient-to-r from-purple-500 to-purple-400"></div>
                                        <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-right flex-1">
                                <div class="text-4xl font-black text-purple-900">--:--</div>
                                <div class="text-sm font-bold text-purple-700 mt-2">
                                    {{ $searchParams['departure_id'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Informations supplémentaires --}}
                <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200">
                    @if(isset($flight['carbon_emissions']))
                        <div class="flex items-center gap-2 bg-green-50 px-3 py-2 rounded-lg border border-green-200">
                            <span class="text-sm">🌱</span>
                            <span class="text-xs font-semibold text-green-700">
                                {{ round($flight['carbon_emissions']['this_flight'] / 1000) }} kg CO₂
                            </span>
                        </div>
                    @endif

                    @if(!empty($flight['flights'][0]['travel_class']))
                        <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                            <span class="text-sm">💺</span>
                            <span class="text-xs font-semibold text-blue-700">
                                {{ ucfirst(str_replace('_', ' ', strtolower($flight['flights'][0]['travel_class']))) }}
                            </span>
                        </div>
                    @endif

                    @if(!empty($flight['flights'][0]['aircraft']))
                        <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                            <span class="text-sm">✈️</span>
                            <span class="text-xs font-semibold text-gray-700">
                                {{ $flight['flights'][0]['aircraft'] }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    @endif

    {{-- Actions --}}
    @if(isset($flight['departure_token']) && $isMultiCity)
        {{-- Pour multi-villes, utiliser le departure_token pour voir les segments suivants --}}
        <div class="flex gap-3 mt-6 pt-6 border-t-2 border-gray-200">
            <form method="POST" action="{{ route('flights.search') }}" class="flex-1">
                @csrf
                <input type="hidden" name="type" value="3">
                <input type="hidden" name="departure_token" value="{{ $flight['departure_token'] }}">
                <input type="hidden" name="multi_city_json" value="{{ $searchParams['multi_city_json'] ?? '' }}">

                <button type="submit"
                    class="w-full inline-flex items-center justify-center bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-orange-700 hover:to-orange-800 hover:scale-105 transition-all">
                    <span>Voir les segments suivants</span>
                </button>
            </form>
        </div>
    @elseif(isset($flight['booking_token']))
        {{-- Pour les autres types, lien vers les détails --}}
        @php
            $queryParams = [
                'booking_token' => $flight['booking_token'],
                'departure_id' => $searchParams['departure_id'] ?? null,
                'arrival_id' => $searchParams['arrival_id'] ?? null,
                'outbound_date' => $searchParams['outbound_date'] ?? null,
            ];
            if (!empty($searchParams['return_date'])) {
                $queryParams['return_date'] = $searchParams['return_date'];
            }
        @endphp

        <div class="flex gap-3 mt-6 pt-6 border-t-2 border-gray-200">
            <a href="{{ route('flights.details', $queryParams) }}"
                class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-purple-700 hover:to-purple-800 hover:scale-105 transition-all">
                <span>Sélectionner ce vol</span>
            </a>
        </div>
    @endif

    {{-- Actions --}}
    {{-- Actions --}}
    <!-- @if(isset($flight['departure_token']) && $isMultiCity)
        {{-- Pour multi-villes, utiliser le departure_token pour voir les segments suivants --}}
        <div class="flex gap-3 mt-6 pt-6 border-t-2 border-gray-200">
            <form method="POST" action="{{ route('flights.search') }}" class="flex-1">
                @csrf
                <input type="hidden" name="type" value="3">
                <input type="hidden" name="departure_token" value="{{ $flight['departure_token'] }}">
                <input type="hidden" name="multi_city_json" value="{{ $searchParams['multi_city_json'] ?? '' }}">

                {{-- Inclure les autres paramètres --}}
                <input type="hidden" name="adults" value="{{ $searchParams['adults'] ?? 1 }}">
                <input type="hidden" name="children" value="{{ $searchParams['children'] ?? 0 }}">
                <input type="hidden" name="infants" value="{{ $searchParams['infants'] ?? 0 }}">
                <input type="hidden" name="travel_class" value="{{ $searchParams['travel_class'] ?? '' }}">
                <input type="hidden" name="currency" value="{{ $searchParams['currency'] ?? 'EUR' }}">

                <button type="submit"
                    class="w-full inline-flex items-center justify-center bg-gradient-to-r from-orange-600 to-orange-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-orange-700 hover:to-orange-800 hover:scale-105 transition-all">
                    <span>Voir les segments suivants</span>
                </button>
            </form>
        </div>
    @elseif(isset($flight['booking_token']))
        {{-- Pour les autres types, lien vers les détails --}}
        @php
            if ($isMultiCity) {
                // POUR MULTI-VILLES: Ne pas utiliser departure_id et arrival_id
                // L'API utilise seulement booking_token pour les détails en multi-villes
                $queryParams = [
                    'booking_token' => $flight['booking_token'],
                    'currency' => $searchParams['currency'] ?? 'EUR',
                ];
            } else {
                // Pour les autres types (aller simple, aller-retour)
                $queryParams = [
                    'booking_token' => $flight['booking_token'],
                    'departure_id' => $searchParams['departure_id'] ?? null,
                    'arrival_id' => $searchParams['arrival_id'] ?? null,
                    'outbound_date' => $searchParams['outbound_date'] ?? null,
                ];
                if (!empty($searchParams['return_date'])) {
                    $queryParams['return_date'] = $searchParams['return_date'];
                }
            }

            // Filtrer les paramètres nuls
            $queryParams = array_filter($queryParams, function ($value) {
                return $value !== null;
            });
        @endphp

        <div class="flex gap-3 mt-6 pt-6 border-t-2 border-gray-200">
            <a href="{{ route('flights.details', $queryParams) }}"
                class="flex-1 inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-purple-700 hover:to-purple-800 hover:scale-105 transition-all">
                <span>Sélectionner ce vol</span>
            </a>
        </div>
    @endif -->
</div>
