@props(['flight', 'searchParams', 'currentSegment', 'selectedSegments', 'multiCityData', 'isBest' => false])

@php
    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);
    $segmentPrice = $flight['price'] ?? 0;
    $cumulativePrice = ($searchParams['total_price'] ?? 0) + $segmentPrice;
@endphp

<div class="bg-white rounded-2xl shadow-xl p-6 border-2 {{ $isBest ? 'border-green-400' : 'border-purple-200' }} hover:shadow-3xl transition-all duration-300">
    
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6 pb-4 border-b-2 {{ $isBest ? 'border-green-200' : 'border-gray-200' }}">
        <div class="flex items-center space-x-4">
            @if(!empty($flight['flights'][0]['airline_logo']))
                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="{{ $flight['airline'] }}" class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2 border-2 border-gray-100">
            @else
                <div class="w-14 h-14 rounded-xl shadow-lg bg-gradient-to-br from-purple-100 to-amber-100 flex items-center justify-center">
                    <span class="text-2xl">✈️</span>
                </div>
            @endif

            <div>
                <h3 class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</h3>
                @if($isBest)
                    <span class="text-xs font-bold px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow-md">
                        ⭐ Recommandé
                    </span>
                @endif
            </div>
        </div>

        <div class="text-right">
            <div class="text-xs text-gray-500 font-semibold uppercase mb-1">Prix segment</div>
            <div class="text-3xl font-black {{ $isBest ? 'text-green-600' : 'text-purple-700' }}">
                {{ number_format($segmentPrice) }}
            </div>
            <div class="text-xs font-bold text-gray-600">{{ $flight['currency'] ?? 'XOF' }}</div>
            <div class="text-xs text-gray-500 mt-2">
                Total cumulé : <span class="font-bold">{{ number_format($cumulativePrice) }} {{ $flight['currency'] ?? 'XOF' }}</span>
            </div>
        </div>
    </div>

    {{-- Détails du vol --}}
    <div class="bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl p-5 mb-6 border-2 border-purple-200">
        @if(!empty($flight['flights']))
            @php
                $firstFlight = $flight['flights'][0];
                $lastFlight = end($flight['flights']);
                $stops = count($flight['flights']) - 1;
            @endphp

            <div class="flex items-center justify-between">
                {{-- Départ --}}
                <div class="text-left flex-1 min-w-0 pr-3">
                    <div class="text-4xl font-black text-purple-900">
                        {{ \Carbon\Carbon::parse($firstFlight['departure_time'])->format('H:i') }}
                    </div>
                    <div class="text-sm font-bold text-purple-700 mt-2">
                        {{ $firstFlight['departure_airport']['id'] }}
                    </div>
                    <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                        {{ $firstFlight['departure_airport']['name'] ?? '' }}
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="flex-shrink-0 w-32 px-4">
                    <div class="text-center">
                        <div class="text-xs font-bold text-purple-700 mb-2">
                            {{ $flight['total_duration'] }}
                        </div>
                        <div class="relative flex items-center">
                            <div class="w-3 h-3 rounded-full bg-purple-500 shadow-md"></div>
                            <div class="flex-1 h-1 bg-gradient-to-r from-purple-500 to-purple-400"></div>
                            <div class="w-3 h-3 rounded-full bg-purple-500 shadow-md"></div>
                        </div>
                        <div class="mt-2">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $stops == 0 ? 'bg-green-200 text-green-800' : 'bg-amber-200 text-amber-800' }}">
                                {{ $stops == 0 ? '✈️ Direct' : "🔄 {$stops} escale" . ($stops > 1 ? 's' : '') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Arrivée --}}
                <div class="text-right flex-1 min-w-0 pl-3">
                    <div class="text-4xl font-black text-purple-900">
                        {{ \Carbon\Carbon::parse($lastFlight['arrival_time'])->format('H:i') }}
                    </div>
                    <div class="text-sm font-bold text-purple-700 mt-2">
                        {{ $lastFlight['arrival_airport']['id'] }}
                    </div>
                    <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                        {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                    </div>
                </div>
            </div>

            {{-- Escales --}}
            @if(!empty($flight['layovers']))
                <div class="mt-4 pt-4 border-t border-purple-300">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-xs font-bold text-amber-700">🔍 ESCALES</span>
                    </div>
                    @foreach($flight['layovers'] as $layover)
                        <div class="flex items-center gap-2 text-sm mb-1">
                            <span class="font-bold text-purple-800">{{ $layover['id'] }}</span>
                            <span class="text-gray-600">{{ $layover['name'] }}</span>
                            <span class="text-amber-600 font-semibold">({{ $layover['duration'] }})</span>
                        </div>
                    @endforeach
                </div>
            @endif
        @endif
    </div>

    {{-- ⭐ BOUTON DE SÉLECTION CORRIGÉ : METHOD="POST" --}}
    @if(isset($flight['departure_token']))
        <form method="POST" action="{{ route('flights.multi-city.next-segment') }}" class="w-full">
            @csrf
            <input type="hidden" name="departure_token" value="{{ $flight['departure_token'] }}">
            <input type="hidden" name="multi_city_json" value="{{ $searchParams['multi_city_json'] }}">
            <input type="hidden" name="current_segment" value="{{ $currentSegment }}">
            <input type="hidden" name="total_price" value="{{ $cumulativePrice }}">
            <input type="hidden" name="currency" value="{{ $searchParams['currency'] }}">
            <input type="hidden" name="adults" value="{{ $searchParams['adults'] ?? 1 }}">
            <input type="hidden" name="children" value="{{ $searchParams['children'] ?? 0 }}">
            <input type="hidden" name="infants" value="{{ $searchParams['infants'] ?? 0 }}">
            <input type="hidden" name="travel_class" value="{{ $searchParams['travel_class'] ?? '' }}">
            
            @php
                $updatedSegments = $selectedSegments;
                $updatedSegments[] = [
                    'segment_number' => $currentSegment + 1,
                    'departure' => $multiCityData[$currentSegment]['departure_id'],
                    'arrival' => $multiCityData[$currentSegment]['arrival_id'],
                    'date' => $multiCityData[$currentSegment]['date'],
                    'price' => $segmentPrice,
                    'airline' => $flight['airline'],
                    'departure_token' => $flight['departure_token'],
                ];
            @endphp
            <input type="hidden" name="selected_segments" value="{{ json_encode($updatedSegments) }}">

            <button type="submit" class="w-full inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-green-700 hover:to-green-800 hover:scale-105 transition-all">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>Sélectionner ce vol</span>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </button>
        </form>
    @endif
</div>