@props(['flight', 'searchParams', 'isBest' => false])

@php
    $stopsCount = count($flight['layovers'] ?? []);
    $totalPassengers = ($searchParams['adults'] ?? 1) + ($searchParams['children'] ?? 0) + ($searchParams['infants'] ?? 0);
    $pricePerPerson = $flight['price'] ?? 0;
    $totalPrice = $pricePerPerson * $totalPassengers;
@endphp

<div
    class="bg-white rounded-2xl shadow-xl p-6 border-2 {{ $isBest ? 'border-green-400' : 'border-purple-200' }} hover:shadow-3xl transition-all duration-300">

    {{-- Header --}}
    <div
        class="flex items-center justify-between mb-6 pb-4 border-b-2 {{ $isBest ? 'border-green-200' : 'border-gray-200' }}">
        <div class="flex items-center space-x-4">
            @if(!empty($flight['flights'][0]['airline_logo']))
                <img src="{{ $flight['flights'][0]['airline_logo'] }}" alt="{{ $flight['airline'] }}"
                    class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2 border-2 border-gray-100">
            @else
                <div
                    class="w-14 h-14 rounded-xl shadow-lg bg-gradient-to-br from-purple-100 to-amber-100 flex items-center justify-center">
                    <span class="text-2xl"></span>
                </div>
            @endif

            <div>
                <h3 class="font-black text-xl text-gray-900">{{ $flight['airline'] }}</h3>
                <div class="flex items-center gap-2 mt-1">
                    <span class="text-xs font-semibold px-2 py-1 rounded-full bg-purple-100 text-purple-700">
                        Aller Simple
                    </span>
                    @if($isBest)
                        <span
                            class="text-xs font-bold px-3 py-1 rounded-full bg-gradient-to-r from-green-400 to-green-600 text-white shadow-md">
                            Recommandé
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="text-right">
            <div class="text-xs text-gray-500 font-semibold uppercase mb-1">
                {{ $totalPassengers > 1 ? "Prix pour {$totalPassengers} passagers" : 'Prix / personne' }}
            </div>
            <div class="text-3xl font-black {{ $isBest ? 'text-green-600' : 'text-purple-700' }}">
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

    {{-- Détails du vol --}}
    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-5 border-2 border-blue-200 mb-4">
        <div class="flex items-center justify-between mb-3">
            <h4 class="text-sm font-bold text-blue-800 uppercase tracking-wide flex items-center gap-2">
                <span class="text-lg"></span> Vol
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
                    {{ $flight['flights'][0]['departure_airport']['code'] ?? $flight['flights'][0]['departure_airport']['id'] }}
                </div>
                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                    {{ $flight['flights'][0]['departure_airport']['name'] ?? '' }}
                </div>
                @if(!empty($flight['flights'][0]['departure_airport']['city']))
                    <div class="text-xs text-gray-600 truncate">
                        {{ $flight['flights'][0]['departure_airport']['city'] }}
                    </div>
                @endif
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
                            {{ $stopsCount == 0 ? 'Direct' : "{$stopsCount} escale" . ($stopsCount > 1 ? 's' : '') }}
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
                    {{ $lastFlight['arrival_airport']['code'] ?? $lastFlight['arrival_airport']['id'] }}
                </div>
                <div class="text-xs text-gray-700 font-semibold mt-1 truncate">
                    {{ $lastFlight['arrival_airport']['name'] ?? '' }}
                </div>
                @if(!empty($lastFlight['arrival_airport']['city']))
                    <div class="text-xs text-gray-600 truncate">
                        {{ $lastFlight['arrival_airport']['city'] }}
                    </div>
                @endif
            </div>
        </div>

        {{-- Escales --}}
        @if($stopsCount > 0 && !empty($flight['layovers']))
            <div class="mt-4 pt-4 border-t border-blue-300">
                <div class="flex items-center gap-2 mb-2">
                    <span class="text-xs font-bold text-amber-700">ESCALES</span>
                </div>
                @foreach($flight['layovers'] as $layover)
                    <div class="flex items-center gap-2 text-sm mb-1">
                        <span class="font-bold text-blue-800">{{ $layover['id'] }}</span>
                        <span class="text-gray-600">{{ $layover['name'] }}</span>
                        <span class="text-amber-600 font-semibold">({{ $layover['duration'] }})</span>
                        @if($layover['overnight'] ?? false)
                            <span class="text-xs bg-amber-200 text-amber-800 px-2 py-0.5 rounded-full">Nuit</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Informations supplémentaires --}}
    <div class="flex flex-wrap gap-3 pt-4 border-t border-gray-200 mb-6">
        @if(isset($flight['carbon_emissions']))
            <div class="flex items-center gap-2 bg-green-50 px-3 py-2 rounded-lg border border-green-200">
                <span class="text-sm"></span>
                <span class="text-xs font-semibold text-green-700">
                    {{ round($flight['carbon_emissions']['this_flight'] / 1000) }} kg CO₂
                </span>
            </div>
        @endif

        @if(!empty($flight['flights'][0]['travel_class']))
            <div class="flex items-center gap-2 bg-blue-50 px-3 py-2 rounded-lg border border-blue-200">
                <span class="text-sm"></span>
                <span class="text-xs font-semibold text-blue-700">
                    {{ ucfirst(str_replace('_', ' ', strtolower($flight['flights'][0]['travel_class']))) }}
                </span>
            </div>
        @endif

        @if(!empty($flight['flights'][0]['aircraft']))
            <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg border border-gray-200">
                <span class="text-sm"></span>
                <span class="text-xs font-semibold text-gray-700">
                    {{ $flight['flights'][0]['aircraft'] }}
                </span>
            </div>
        @endif
    </div>

    {{-- Bouton action --}}
    @if(isset($flight['booking_token']))
        <a href="{{ route('flights.details-one-way', [
            'booking_token' => $flight['booking_token'],
            'price' => $pricePerPerson,
            'currency' => $flight['currency'] ?? 'EUR',
            'departure_id' => $searchParams['departure_id'] ?? '',
            'arrival_id' => $searchParams['arrival_id'] ?? '',
            'outbound_date' => $searchParams['outbound_date'] ?? '',
            'adults' => $searchParams['adults'] ?? 1,
            'children' => $searchParams['children'] ?? 0,
            'infants' => $searchParams['infants'] ?? 0,
            'travel_class' => $searchParams['travel_class'] ?? 'ECONOMY'
        ]) }}"
            class="w-full inline-flex items-center justify-center bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4 rounded-xl font-bold shadow-xl hover:from-green-700 hover:to-green-800 hover:scale-105 transition-all">
            <span>Sélectionner ce vol</span>
        </a>
    @else
        <div class="w-full text-center py-4 bg-gray-100 rounded-xl text-gray-500 font-semibold">
            Informations de réservation non disponibles
        </div>
    @endif
</div>