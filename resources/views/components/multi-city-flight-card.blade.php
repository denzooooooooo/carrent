@props(['flight', 'searchParams', 'isBest' => false])

@php
    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);
    $pricePerPerson = $flight['price'] ?? 0;
    $totalPrice = $pricePerPerson * $totalPassengers;
@endphp

<div
    class="bg-white rounded-2xl shadow-xl p-6 border-2 {{ $isBest ? 'border-green-400' : 'border-orange-200' }} hover:shadow-3xl transition-all duration-300">

    {{-- Header --}}
    <div
        class="flex items-center justify-between mb-6 pb-4 border-2 {{ $isBest ? 'border-green-200' : 'border-gray-200' }}">
        <div class="flex items-center space-x-4">
            @if(!empty($flight['flights'][0]['airline_logo']))
                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="{{ $flight['airline'] }}"
                    class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2 border-2 border-gray-100">
            @else
                <div
                    class="w-14 h-14 rounded-xl shadow-lg bg-gradient-to-br from-orange-100 to-amber-100 flex items-center justify-center">
                    <span class="text-2xl">✈️</span>
                </div>
            @endif

            <div>
                <h3 class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-orange-100 text-orange-700">
                        Multi-villes
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

        <div class="text-right">
            <div class="text-xs text-gray-500 font-semibold uppercase mb-1">
                {{ $totalPassengers > 1 ? "Prix pour {$totalPassengers} passagers" : 'Prix total' }}
            </div>
            <div class="text-3xl font-black {{ $isBest ? 'text-green-600' : 'text-orange-700' }}">
                {{ number_format($totalPassengers > 1 ? $totalPrice : $pricePerPerson) }}
            </div>
            <div class="text-xs font-bold text-gray-600">
                {{ $flight['currency'] ?? 'XOF' }} {{ $totalPassengers > 1 ? 'total' : '' }}
            </div>
            @if($totalPassengers > 1)
                <div class="text-xs text-gray-500 mt-1">
                    ({{ number_format($pricePerPerson) }} {{ $flight['currency'] ?? 'XOF' }}/pers.)
                </div>
            @endif
        </div>
    </div>

    {{-- Segments de vol --}}
    <div class="space-y-4 mb-6">
        @if(!empty($flight['flights']))
            @php
                // Grouper les vols par segment
                $segments = [];
                $currentSegment = [];

                foreach ($flight['flights'] as $index => $segment) {
                    $currentSegment[] = $segment;

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

                    // Calculer durée
                    $segmentDuration = 0;
                    foreach ($segmentFlights as $sf) {
                        $segmentDuration += (int) ($sf['duration_minutes'] ?? $sf['duration'] ?? 0);
                    }
                    if (!empty($segmentLayovers)) {
                        foreach ($segmentLayovers as $layover) {
                            $segmentDuration += (int) ($layover['duration_minutes'] ?? 0);
                        }
                    }

                    $stops = count($segmentFlights) - 1;
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
                                <div class="text-xs font-bold text-orange-700 mb-2">
                                    {{ floor($segmentDuration / 60) }}h {{ $segmentDuration % 60 }}min
                                </div>
                                <div class="relative flex items-center">
                                    <div class="w-3 h-3 rounded-full bg-orange-500 shadow-md"></div>
                                    <div class="flex-1 h-1 bg-gradient-to-r from-orange-500 to-orange-400"></div>
                                    <div class="w-3 h-3 rounded-full bg-orange-500 shadow-md"></div>
                                </div>
                                <div class="mt-2">
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

                    {{-- Escales --}}
                    @if(!empty($segmentLayovers))
                        <div class="mt-4 pt-4 border-t border-orange-300">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="text-xs font-bold text-amber-700">🔍 ESCALES</span>
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
    </div>

    {{-- Note --}}
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-xl mb-6">
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

    {{-- ⭐ BOUTON ACTION CORRIGÉ : METHOD="POST" --}}
    @if(isset($flight['departure_token']))
        <form method="POST" action="{{ route('flights.multi-city.next-segment') }}" class="w-full">
            @csrf
            <input type="hidden" name="departure_token" value="{{ $flight['departure_token'] }}">
            <input type="hidden" name="multi_city_json" value="{{ $searchParams['multi_city_json'] ?? '' }}">
            <input type="hidden" name="current_segment" value="0">
            <input type="hidden" name="total_price" value="{{ $flight['price'] }}">
            <input type="hidden" name="currency" value="{{ $flight['currency'] ?? 'EUR' }}">
            <input type="hidden" name="adults" value="{{ $searchParams['adults'] ?? 1 }}">
            <input type="hidden" name="children" value="{{ $searchParams['children'] ?? 0 }}">
            <input type="hidden" name="infants" value="{{ $searchParams['infants'] ?? 0 }}">
            <input type="hidden" name="travel_class" value="{{ $searchParams['travel_class'] ?? '' }}">

            <button type="submit"
                class="w-full inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-green-700 hover:to-green-800 hover:scale-105 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Commencer la sélection</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    @elseif(isset($flight['booking_token']))
        <a href="{{ route('flights.details-multi-city', [
            'booking_token' => $flight['booking_token'],
            'total_price' => $flight['price'],
            'currency' => $flight['currency'] ?? 'EUR',
            'multi_city_json' => $searchParams['multi_city_json'] ?? '',
            'adults' => $searchParams['adults'] ?? 1,
            'children' => $searchParams['children'] ?? 0,
            'infants' => $searchParams['infants'] ?? 0,
            'travel_class' => $searchParams['travel_class'] ?? '',
        ]) }}"
            class="w-full inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-green-700 hover:to-green-800 hover:scale-105 transition-all">
            <span>Voir les détails et réserver</span>
        </a>
    @endif
</div>