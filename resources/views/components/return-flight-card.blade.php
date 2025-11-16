@props(['flight', 'outboundPrice', 'currency', 'searchParams'])

@php
    // ⭐ LE VOL RETOUR : Le prix retourné par l'API est déjà le prix TOTAL aller+retour
    // Donc on affiche juste ce prix total, pas besoin d'additionner
    $stopsCount = count($flight['layovers'] ?? []);
    $totalPriceRoundTrip = $flight['price'] ?? 0;
    
    // Si on a plusieurs passagers
    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);
    $totalPriceAllPassengers = $totalPriceRoundTrip * $totalPassengers;
@endphp

<div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-purple-200 hover:shadow-3xl transition-all">

    {{-- Header avec prix TOTAL --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b-2 border-gray-200">
        <div class="flex items-center space-x-4">
            @if(!empty($flight['flights'][0]['airline_logo']))
                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="{{ $flight['airline'] }}"
                    class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2">
            @else
                <div class="w-14 h-14 rounded-xl shadow-lg bg-gradient-to-br from-purple-100 to-amber-100 flex items-center justify-center">
                    <span class="text-2xl">✈️</span>
                </div>
            @endif

            <div>
                <h3 class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</h3>
                <span class="text-xs font-semibold px-2 py-1 rounded-full bg-green-100 text-green-700">🛬 Vol Retour</span>
            </div>
        </div>

        {{-- ⭐ PRIX TOTAL (déjà aller + retour) --}}
        <div class="text-right">
            <div class="text-xs text-gray-500 font-semibold uppercase mb-1">
                {{ $totalPassengers > 1 ? "Prix total A/R" : "Prix Total A/R" }}
            </div>
            <div class="text-3xl font-black text-purple-700">
                {{ number_format($totalPassengers > 1 ? $totalPriceAllPassengers : $totalPriceRoundTrip) }}
            </div>
            <div class="text-xs font-bold text-gray-600">{{ $currency }}</div>
            @if($totalPassengers > 1)
                <div class="text-xs text-gray-500 mt-1">
                    ({{ number_format($totalPriceRoundTrip) }} {{ $currency }}/pers.)
                </div>
            @endif
        </div>
    </div>

    {{-- 🟢 VOL RETOUR --}}
    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-5 border-2 border-green-200">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-bold text-green-800 uppercase flex items-center gap-2">
                <span class="text-lg">🛬</span> Vol Retour
            </h4>
            @if(!empty($flight['flights'][0]['departure_airport']['time']))
                <div class="text-xs font-semibold text-green-700">
                    {{ \Carbon\Carbon::parse($flight['flights'][0]['departure_airport']['time'])->format('D d M Y') }}
                </div>
            @endif
        </div>

        <div class="flex items-center justify-between">
            {{-- Départ --}}
            <div class="text-left flex-1">
                @if(!empty($flight['flights'][0]['departure_airport']['time']))
                    <div class="text-4xl font-black text-green-900">
                        {{ \Carbon\Carbon::parse($flight['flights'][0]['departure_airport']['time'])->format('H:i') }}
                    </div>
                @endif
                <div class="text-sm font-bold text-green-700 mt-2">
                    {{ $flight['flights'][0]['departure_airport']['id'] ?? '' }}
                </div>
                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                    {{ $flight['flights'][0]['departure_airport']['name'] ?? '' }}
                </div>
            </div>

            {{-- Timeline --}}
            <div class="flex-shrink-0 w-32 px-4">
                <div class="text-center">
                    <div class="text-xs font-bold text-green-700 mb-2">{{ $flight['total_duration'] }}</div>
                    <div class="relative flex items-center">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <div class="flex-1 h-1 bg-gradient-to-r from-green-500 to-green-400"></div>
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
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
            <div class="text-right flex-1">
                @php $lastFlight = $flight['flights'][count($flight['flights']) - 1]; @endphp
                @if(!empty($lastFlight['arrival_airport']['time']))
                    <div class="text-4xl font-black text-green-900">
                        {{ \Carbon\Carbon::parse($lastFlight['arrival_airport']['time'])->format('H:i') }}
                    </div>
                @endif
                <div class="text-sm font-bold text-green-700 mt-2">
                    {{ $lastFlight['arrival_airport']['id'] ?? '' }}
                </div>
                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                    {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                </div>
            </div>
        </div>

        {{-- Détails des segments retour (si plusieurs vols) --}}
        @if(count($flight['flights']) > 1)
            <div class="mt-4 pt-4 border-t border-green-300">
                <button type="button" onclick="toggleReturnSegments('{{ Str::slug($flight['booking_token'] ?? uniqid()) }}')" 
                    class="text-xs font-bold text-green-700 hover:text-green-900 flex items-center gap-2">
                    <span id="icon-return-{{ Str::slug($flight['booking_token'] ?? uniqid()) }}">▶</span>
                    Voir les {{ count($flight['flights']) }} segments
                </button>
                <div id="segments-return-{{ Str::slug($flight['booking_token'] ?? uniqid()) }}" class="hidden mt-3 space-y-2">
                    @foreach($flight['flights'] as $index => $segment)
                        <div class="bg-white rounded-lg p-3 text-sm">
                            <div class="flex items-center justify-between mb-2">
                                <div class="font-bold text-green-900">
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

    {{-- Informations supplémentaires --}}
    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 mt-4 mb-6">
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
    </div>

    {{-- 🚀 Bouton : Voir détails complets (aller + retour) --}}
    <a href="{{ route('flights.details-round-trip', array_merge([
        'booking_token' => $flight['booking_token'],
        'currency' => $currency,
    ], $searchParams ?? [])) }}" 
        class="w-full inline-flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-purple-700 hover:to-purple-800 transition-all">
        ✅ Sélectionner ce vol retour
    </a>
</div>

<script>
function toggleReturnSegments(id) {
    const segments = document.getElementById('segments-return-' + id);
    const icon = document.getElementById('icon-return-' + id);
    
    if (segments.classList.contains('hidden')) {
        segments.classList.remove('hidden');
        icon.textContent = '▼';
    } else {
        segments.classList.add('hidden');
        icon.textContent = '▶';
    }
}
</script>