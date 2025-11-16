@props(['flight', 'searchParams', 'isBest' => false])

@php
    // ⭐ IMPORTANT : Dans les résultats A/R, l'API retourne le prix TOTAL (aller + retour)
    // Mais on ne montre QUE le vol aller ici, le retour sera choisi après
    $stopsCount = count($flight['layovers'] ?? []);
    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);
    
    // Le prix affiché est le prix TOTAL aller-retour
    $totalPriceRoundTrip = $flight['price'] ?? 0;
    $totalPriceAllPassengers = $totalPriceRoundTrip * $totalPassengers;
@endphp

<div class="bg-white rounded-2xl shadow-xl p-6 border-2 {{ $isBest ? 'border-green-400' : 'border-purple-200' }} hover:shadow-3xl transition-all duration-300">

    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b-2 {{ $isBest ? 'border-green-200' : 'border-gray-200' }}">
        <div class="flex items-center space-x-4">
            @if(!empty($flight['flights'][0]['airline_logo']))
                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="{{ $flight['airline'] }}"
                    class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2 border-2 border-gray-100">
            @else
                <div class="w-14 h-14 rounded-xl shadow-lg bg-gradient-to-br from-purple-100 to-amber-100 flex items-center justify-center">
                    <span class="text-2xl">✈️</span>
                </div>
            @endif

            <div>
                <h3 class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-700">
                        🛫 Vol Aller
                    </span>
                    @if($isBest)
                        <span class="text-xs font-bold px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow-md">
                            ⭐ Recommandé
                        </span>
                    @endif
                </div>
            </div>
        </div>

        {{-- Prix TOTAL Aller-Retour --}}
        <div class="text-right">
            <div class="text-xs text-gray-500 font-semibold uppercase mb-1">
                {{ $totalPassengers > 1 ? "Prix total A/R" : 'Prix A/R' }}
            </div>
            <div class="text-3xl font-black {{ $isBest ? 'text-green-600' : 'text-purple-700' }}">
                {{ number_format($totalPassengers > 1 ? $totalPriceAllPassengers : $totalPriceRoundTrip) }}
            </div>
            <div class="text-xs font-bold text-gray-600">
                {{ $flight['currency'] ?? 'XOF' }}
            </div>
            @if($totalPassengers > 1)
                <div class="text-xs text-gray-500 mt-1">
                    ({{ number_format($totalPriceRoundTrip) }} {{ $flight['currency'] ?? 'XOF' }}/pers.)
                </div>
            @endif
        </div>
    </div>

    {{-- 🔵 VOL ALLER UNIQUEMENT --}}
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-bold text-blue-800 uppercase tracking-wide flex items-center gap-2">
                <span class="text-lg">🛫</span> Vol Aller
            </h4>
            @if(!empty($flight['flights'][0]['departure_airport']['time']))
                <div class="text-xs font-semibold text-blue-700">
                    {{ \Carbon\Carbon::parse($flight['flights'][0]['departure_airport']['time'])->format('D d M Y') }}
                </div>
            @endif
        </div>

        <div class="flex items-center justify-between">
            {{-- Départ --}}
            <div class="text-left flex-1 min-w-0 pr-3">
                @if(!empty($flight['flights'][0]['departure_airport']['time']))
                    <div class="text-4xl font-black text-blue-900">
                        {{ \Carbon\Carbon::parse($flight['flights'][0]['departure_airport']['time'])->format('H:i') }}
                    </div>
                @endif
                <div class="text-sm font-bold text-blue-700 mt-2">
                    {{ $flight['flights'][0]['departure_airport']['id'] ?? '' }}
                </div>
                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                    {{ $flight['flights'][0]['departure_airport']['name'] ?? '' }}
                </div>
            </div>

            {{-- Timeline --}}
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
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold
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
                @if(!empty($lastFlight['arrival_airport']['time']))
                    <div class="text-4xl font-black text-blue-900">
                        {{ \Carbon\Carbon::parse($lastFlight['arrival_airport']['time'])->format('H:i') }}
                    </div>
                @endif
                <div class="text-sm font-bold text-blue-700 mt-2">
                    {{ $lastFlight['arrival_airport']['id'] ?? '' }}
                </div>
                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                    {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                </div>
            </div>
        </div>

        {{-- Détails des segments aller (si plusieurs vols) --}}
        @if(count($flight['flights']) > 1)
            <div class="mt-4 pt-4 border-t border-blue-300">
                <button type="button" onclick="toggleSegments('{{ Str::slug($flight['departure_token'] ?? uniqid()) }}')" 
                    class="text-xs font-bold text-blue-700 hover:text-blue-900 flex items-center gap-2">
                    <span id="icon-{{ Str::slug($flight['departure_token'] ?? uniqid()) }}">▶</span>
                    Voir les {{ count($flight['flights']) }} segments
                </button>
                <div id="segments-{{ Str::slug($flight['departure_token'] ?? uniqid()) }}" class="hidden mt-3 space-y-2">
                    @foreach($flight['flights'] as $index => $segment)
                        <div class="bg-white rounded-lg p-3 text-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-blue-900">
                                    {{ $segment['airline'] }} {{ $segment['flight_number'] }}
                                </div>
                                <div class="text-xs text-gray-600">
                                    @php
                                        $duration = $segment['duration_minutes'] ?? 0;
                                        $h = floor($duration / 60);
                                        $m = $duration % 60;
                                    @endphp
                                    {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                                </div>
                            </div>
                            <div class="flex justify-between text-xs text-gray-700">
                                <span>{{ $segment['departure_airport']['id'] }} {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}</span>
                                <span>→</span>
                                <span>{{ $segment['arrival_airport']['id'] }} {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</span>
                            </div>
                        </div>

                        {{-- Escale entre les segments --}}
                        @if(!empty($flight['layovers'][$index]))
                            <div class="flex items-center justify-center">
                                <div class="bg-amber-100 border border-amber-200 rounded px-3 py-1">
                                    <span class="text-xs font-semibold text-amber-800">
                                        ⏱️ Escale {{ $flight['layovers'][$index]['id'] }} 
                                        @php
                                            $layoverDuration = $flight['layovers'][$index]['duration_minutes'] ?? 0;
                                            $lh = floor($layoverDuration / 60);
                                            $lm = $layoverDuration % 60;
                                        @endphp
                                        ({{ $lh > 0 ? "{$lh}h " : "" }}{{ $lm }}min)
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    {{-- 📋 Note importante sur le retour --}}
    <div class="bg-gradient-to-r from-purple-50 to-blue-50 border-2 border-purple-300 rounded-xl p-4 mb-6">
        <div class="flex items-start gap-3">
            <span class="text-2xl">ℹ️</span>
            <div>
                <p class="text-sm font-bold text-purple-900 mb-1">Prix aller-retour complet</p>
                <p class="text-xs text-purple-700">
                    Le prix affiché ({{ number_format($totalPriceRoundTrip) }} {{ $flight['currency'] ?? 'XOF' }}) 
                    inclut l'aller <strong>ET</strong> le retour. 
                    Après avoir sélectionné ce vol aller, vous pourrez choisir parmi plusieurs options de vols retour.
                </p>
            </div>
        </div>
    </div>

    {{-- Informations supplémentaires --}}
    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 mb-6">
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

    {{-- 🚀 Bouton action : Choisir le vol RETOUR --}}
    @if(isset($flight['departure_token']))
        <form action="{{ route('flights.return') }}" method="GET" class="w-full">
            {{-- Token pour récupérer les vols retour --}}
            <input type="hidden" name="departure_token" value="{{ $flight['departure_token'] }}">
            <input type="hidden" name="price" value="{{ $totalPriceRoundTrip }}">
            <input type="hidden" name="currency" value="{{ $flight['currency'] ?? 'XOF' }}">

            {{-- Paramètres de recherche nécessaires --}}
            <input type="hidden" name="departure_id" value="{{ $searchParams['departure_id'] }}">
            <input type="hidden" name="arrival_id" value="{{ $searchParams['arrival_id'] }}">
            <input type="hidden" name="outbound_date" value="{{ $searchParams['outbound_date'] }}">
            <input type="hidden" name="return_date" value="{{ $searchParams['return_date'] }}">
            <input type="hidden" name="adults" value="{{ $searchParams['adults'] ?? 1 }}">
            @if(!empty($searchParams['children']))
                <input type="hidden" name="children" value="{{ $searchParams['children'] }}">
            @endif
            @if(!empty($searchParams['infants']))
                <input type="hidden" name="infants" value="{{ $searchParams['infants'] }}">
            @endif
            @if(!empty($searchParams['travel_class']))
                <input type="hidden" name="travel_class" value="{{ $searchParams['travel_class'] }}">
            @endif

            <button type="submit"
                class="w-full inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-purple-700 hover:to-purple-800 hover:scale-105 transition-all">
                <span>Choisir le vol retour →</span>
            </button>
        </form>
    @endif
</div>

<script>
function toggleSegments(id) {
    const segments = document.getElementById('segments-' + id);
    const icon = document.getElementById('icon-' + id);
    
    if (segments.classList.contains('hidden')) {
        segments.classList.remove('hidden');
        icon.textContent = '▼';
    } else {
        segments.classList.add('hidden');
        icon.textContent = '▶';
    }
}
</script>   