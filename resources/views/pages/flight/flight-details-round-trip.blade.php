@extends('layouts.app')

@section('title', 'Détails Aller-Retour - Carré Premium')

@section('content')
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-16">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl lg:text-5xl font-black mb-4">🔄 Détails de votre vol Aller-Retour</h1>
            <p class="text-xl opacity-90">Confirmez les détails et réservez en toute sérénité</p>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
        <div class="container mx-auto px-4 py-8 lg:py-12">
            @if($error)
                <div class="max-w-4xl mx-auto bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-2xl shadow-2xl mb-8">
                    <div class="flex items-center space-x-3 mb-4">
                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                        <h3 class="text-lg font-bold">Erreur de chargement</h3>
                    </div>
                    <p class="text-red-700">{{ $error }}</p>
                    <a href="{{ route('flights.index') }}" class="mt-4 inline-block bg-red-600 text-white px-6 py-2 rounded-lg">
                        ← Nouvelle recherche
                    </a>
                </div>
            @elseif($roundTripData)
                <div class="max-w-7xl mx-auto">
                    <div class="lg:grid lg:grid-cols-3 lg:gap-8">

                        {{-- COLONNE PRINCIPALE --}}
                        <div class="lg:col-span-2">

                            {{-- CARTE DES DÉTAILS --}}
                            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-8 border-2 border-purple-100">

                                {{-- Header --}}
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start border-b-2 border-purple-100 pb-6 mb-6">
                                    <div class="mb-4 sm:mb-0">
                                        <div class="flex items-center space-x-4 mb-3">
                                            @if(!empty($roundTripData['outbound_flights'][0]['airline_logo']))
                                                <img src="{{ $roundTripData['outbound_flights'][0]['airline_logo'] }}"
                                                    alt="{{ $roundTripData['airline'] }}"
                                                    class="w-12 h-12 rounded-xl shadow-lg">
                                            @endif
                                            <div>
                                                <h2 class="text-2xl md:text-3xl font-black text-gray-900">
                                                    {{ $roundTripData['airline'] }}
                                                </h2>
                                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 mt-2">
                                                    🔄 Aller-Retour
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-200">
                                        <div class="text-center">
                                            <div class="text-sm text-gray-600 mb-1">Prix Total A/R</div>
                                            <div class="text-3xl font-black text-green-600 mb-1">
                                                {{ number_format($roundTripData['total_price'] ?? 0, 0, ',', ' ') }}
                                                {{ $searchParams['currency'] }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- VOL ALLER --}}
                                @if(!empty($roundTripData['outbound_flights']))
                                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-6 border-2 border-blue-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-xl font-bold text-blue-800 flex items-center gap-2">
                                                <span class="text-2xl">🛫</span> Vol Aller
                                            </h3>
                                            {{-- Date du vol aller --}}
                                            @if(!empty($roundTripData['outbound_flights'][0]['departure_airport']['time']))
                                                <div class="bg-blue-200 text-blue-900 px-4 py-2 rounded-lg font-bold text-sm">
                                                    📅 {{ \Carbon\Carbon::parse($roundTripData['outbound_flights'][0]['departure_airport']['time'])->format('D d M Y') }}
                                                </div>
                                            @endif
                                        </div>

                                        @foreach($roundTripData['outbound_flights'] as $index => $segment)
                                            <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                                    {{-- Départ --}}
                                                    <div class="flex-1">
                                                        @if(!empty($segment['departure_airport']['time']))
                                                            <div class="text-3xl font-black text-blue-900">
                                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 font-semibold mt-1">
                                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="font-bold text-blue-700 mt-2">
                                                            {{ $segment['departure_airport']['id'] ?? '' }}
                                                        </div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ $segment['departure_airport']['name'] ?? '' }}
                                                        </div>
                                                    </div>

                                                    {{-- Durée --}}
                                                    <div class="flex-shrink-0 text-center px-4">
                                                        <div class="text-sm text-blue-700 font-bold mb-2">
                                                            @php
                                                                $duration = $segment['duration'] ?? 0;
                                                                $h = floor($duration / 60);
                                                                $m = $duration % 60;
                                                            @endphp
                                                            {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                                                        </div>
                                                        <div class="flex items-center">
                                                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                            <div class="flex-1 h-0.5 bg-blue-400 mx-2"></div>
                                                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                        </div>
                                                        <div class="text-xs text-gray-600 mt-2">
                                                            {{ $segment['airline'] ?? '' }} {{ $segment['flight_number'] ?? '' }}
                                                        </div>
                                                    </div>

                                                    {{-- Arrivée --}}
                                                    <div class="flex-1 text-right">
                                                        @if(!empty($segment['arrival_airport']['time']))
                                                            <div class="text-3xl font-black text-blue-900">
                                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 font-semibold mt-1">
                                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="font-bold text-blue-700 mt-2">
                                                            {{ $segment['arrival_airport']['id'] ?? '' }}
                                                        </div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ $segment['arrival_airport']['name'] ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Escale --}}
                                            @if(!empty($roundTripData['outbound_layovers'][$index]))
                                                <div class="flex items-center justify-center my-4">
                                                    <div class="bg-amber-100 border border-amber-200 rounded-lg px-4 py-2">
                                                        <div class="text-sm font-bold text-amber-800">
                                                            ⏱️ Escale {{ $roundTripData['outbound_layovers'][$index]['name'] ?? $roundTripData['outbound_layovers'][$index]['id'] ?? '' }}
                                                            @php
                                                                $layoverDuration = $roundTripData['outbound_layovers'][$index]['duration'] ?? 0;
                                                                $lh = floor($layoverDuration / 60);
                                                                $lm = $layoverDuration % 60;
                                                            @endphp
                                                            ({{ $lh > 0 ? "{$lh}h " : "" }}{{ $lm }}min)
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif

                                {{-- VOL RETOUR --}}
                                @if(!empty($roundTripData['return_flights']))
                                    <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-2xl p-6 mb-6 border-2 border-green-200">
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-xl font-bold text-green-800 flex items-center gap-2">
                                                <span class="text-2xl">🛬</span> Vol Retour
                                            </h3>
                                            {{-- Date du vol retour --}}
                                            @if(!empty($roundTripData['return_flights'][0]['departure_airport']['time']))
                                                <div class="bg-green-200 text-green-900 px-4 py-2 rounded-lg font-bold text-sm">
                                                    📅 {{ \Carbon\Carbon::parse($roundTripData['return_flights'][0]['departure_airport']['time'])->format('D d M Y') }}
                                                </div>
                                            @endif
                                        </div>

                                        @foreach($roundTripData['return_flights'] as $index => $segment)
                                            <div class="bg-white rounded-xl p-4 mb-4 shadow-sm">
                                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0">
                                                    {{-- Départ --}}
                                                    <div class="flex-1">
                                                        @if(!empty($segment['departure_airport']['time']))
                                                            <div class="text-3xl font-black text-green-900">
                                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 font-semibold mt-1">
                                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="font-bold text-green-700 mt-2">
                                                            {{ $segment['departure_airport']['id'] ?? '' }}
                                                        </div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ $segment['departure_airport']['name'] ?? '' }}
                                                        </div>
                                                    </div>

                                                    {{-- Durée --}}
                                                    <div class="flex-shrink-0 text-center px-4">
                                                        <div class="text-sm text-green-700 font-bold mb-2">
                                                            @php
                                                                $duration = $segment['duration'] ?? 0;
                                                                $h = floor($duration / 60);
                                                                $m = $duration % 60;
                                                            @endphp
                                                            {{ $h > 0 ? "{$h}h " : "" }}{{ $m }}min
                                                        </div>
                                                        <div class="flex items-center">
                                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                            <div class="flex-1 h-0.5 bg-green-400 mx-2"></div>
                                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                        </div>
                                                        <div class="text-xs text-gray-600 mt-2">
                                                            {{ $segment['airline'] ?? '' }} {{ $segment['flight_number'] ?? '' }}
                                                        </div>
                                                    </div>

                                                    {{-- Arrivée --}}
                                                    <div class="flex-1 text-right">
                                                        @if(!empty($segment['arrival_airport']['time']))
                                                            <div class="text-3xl font-black text-green-900">
                                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                                                            </div>
                                                            <div class="text-xs text-gray-500 font-semibold mt-1">
                                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d/m/Y') }}
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="font-bold text-green-700 mt-2">
                                                            {{ $segment['arrival_airport']['id'] ?? '' }}
                                                        </div>
                                                        <div class="text-sm text-gray-600">
                                                            {{ $segment['arrival_airport']['name'] ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Escale --}}
                                            @if(!empty($roundTripData['return_layovers'][$index]))
                                                <div class="flex items-center justify-center my-4">
                                                    <div class="bg-amber-100 border border-amber-200 rounded-lg px-4 py-2">
                                                        <div class="text-sm font-bold text-amber-800">
                                                            ⏱️ Escale {{ $roundTripData['return_layovers'][$index]['name'] ?? $roundTripData['return_layovers'][$index]['id'] ?? '' }}
                                                            @php
                                                                $layoverDuration = $roundTripData['return_layovers'][$index]['duration'] ?? 0;
                                                                $lh = floor($layoverDuration / 60);
                                                                $lm = $layoverDuration % 60;
                                                            @endphp
                                                            ({{ $lh > 0 ? "{$lh}h " : "" }}{{ $lm }}min)
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            {{-- FORMULAIRE DE RÉSERVATION --}}
                            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-purple-100">
                                <h2 class="text-2xl font-black text-gray-900 mb-6">📋 Informations de réservation</h2>

                                <form action="{{ route('flights.booking.store') }}" method="POST" id="bookingFormRoundTrip">
                                    @csrf

                                    {{-- Champs cachés --}}
                                    <input type="hidden" name="booking_token" value="{{ $searchParams['booking_token'] }}">
                                    <input type="hidden" name="departure_id" value="{{ $searchParams['departure_id'] }}">
                                    <input type="hidden" name="arrival_id" value="{{ $searchParams['arrival_id'] }}">
                                    <input type="hidden" name="outbound_date" value="{{ $searchParams['outbound_date'] }}">
                                    <input type="hidden" name="return_date" value="{{ $searchParams['return_date'] }}">
                                    <input type="hidden" name="trip_type" value="round_trip">

                                    <input type="hidden" name="flight_details" value='@json($roundTripData)'>
                                    <input type="hidden" name="booking_options" value='@json([])'>

                                    <input type="hidden" name="base_price" id="hidden_base_price_rt"
                                        value="{{ $roundTripData['total_price'] ?? 0 }}">
                                    <input type="hidden" name="taxes" id="hidden_taxes_rt"
                                        value="{{ ($roundTripData['total_price'] ?? 0) * 0.1 }}">
                                    <input type="hidden" name="final_price" id="hidden_final_price_rt"
                                        value="{{ ($roundTripData['total_price'] ?? 0) * 1.1 }}">
                                    <input type="hidden" name="currency" value="{{ $searchParams['currency'] }}">

                                    {{-- Section Passagers --}}
                                    <div class="mb-8">
                                        <h3 class="text-xl font-bold text-gray-900 mb-6">👥 Configuration des passagers</h3>
                                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 p-6 bg-gradient-to-r from-purple-50 to-amber-50 rounded-2xl">
                                            <div class="bg-white rounded-xl p-4 border border-purple-200">
                                                <label for="adults_rt" class="block text-sm font-bold text-gray-800 mb-2">Adultes (18+)</label>
                                                <input type="number" id="adults_rt" name="adults" min="1" max="9"
                                                    value="{{ $searchParams['adults'] ?? 1 }}"
                                                    class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold" required>
                                            </div>
                                            <div class="bg-white rounded-xl p-4 border border-purple-200">
                                                <label for="children_rt" class="block text-sm font-bold text-gray-800 mb-2">Enfants (2-11)</label>
                                                <input type="number" id="children_rt" name="children" min="0" max="8"
                                                    value="{{ $searchParams['children'] ?? 0 }}"
                                                    class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold">
                                            </div>
                                            <div class="bg-white rounded-xl p-4 border border-purple-200">
                                                <label for="infants_rt" class="block text-sm font-bold text-gray-800 mb-2">Bébés (-2)</label>
                                                <input type="number" id="infants_rt" name="infants" min="0" max="4"
                                                    value="{{ $searchParams['infants'] ?? 0 }}"
                                                    class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold">
                                            </div>
                                            <div class="bg-white rounded-xl p-4 border border-purple-200">
                                                <label for="travel_class_rt" class="block text-sm font-bold text-gray-800 mb-2">Classe</label>
                                                <select id="travel_class_rt" name="travel_class"
                                                    class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold">
                                                    <option value="ECONOMY">Économique</option>
                                                    <option value="PREMIUM_ECONOMY">Premium</option>
                                                    <option value="BUSINESS">Affaires</option>
                                                    <option value="FIRST">Première</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Informations passagers dynamiques --}}
                                    <div id="passengersInfoRoundTrip" class="mb-8">
                                        <h3 class="text-xl font-bold text-gray-900 mb-6">📝 Détails de chaque passager</h3>
                                    </div>

                                    {{-- Boutons --}}
                                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-purple-200">
                                        <a href="{{ route('flights.index') }}"
                                            class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-4 rounded-xl font-bold hover:bg-gray-300 transition-all">
                                            ← Modifier la recherche
                                        </a>
                                        <button type="submit"
                                            class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-black hover:from-purple-700 hover:to-purple-800 transition-all shadow-2xl">
                                            ✅ Réserver maintenant
                                        </button>
                                    </div>
                                </form>
                            </div>

                        </div>

                        {{-- COLONNE LATÉRALE - RÉSUMÉ PRIX --}}
                        <div class="lg:col-span-1 mt-8 lg:mt-0">
                            <div class="sticky top-10 bg-gradient-to-br from-purple-50 to-amber-50 rounded-2xl shadow-2xl p-6 border-2 border-purple-200">
                                <h2 class="text-xl font-black text-purple-800 mb-6">💰 Résumé du Prix</h2>

                                <div class="space-y-4">
                                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Prix par personne</span>
                                            <span class="font-bold text-purple-700">{{ number_format($roundTripData['total_price'] ?? 0, 0, ',', ' ') }}
                                                {{ $searchParams['currency'] }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                                        <div class="flex justify-between">
                                            <span class="font-medium">Nombre de passagers</span>
                                            <span id="passengerCountRT" class="font-bold text-purple-700">1</span>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                                        <div class="flex justify-between">
                                            <span class="font-semibold">Sous-total</span>
                                            <span id="basePriceRT" class="font-bold">{{ number_format($roundTripData['total_price'] ?? 0, 0, ',', ' ') }}
                                                {{ $searchParams['currency'] }}</span>
                                        </div>
                                    </div>
                                    <div class="bg-white rounded-lg p-3 border border-purple-100">
                                        <div class="flex justify-between text-sm">
                                            <span>Taxes et Frais</span>
                                            <span id="taxesRT">{{ number_format(($roundTripData['total_price'] ?? 0) * 0.1, 0, ',', ' ') }}
                                                {{ $searchParams['currency'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-6 pt-6 border-t-2 border-purple-300">
                                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-4 text-white">
                                        <div class="flex justify-between items-center">
                                            <span class="text-lg font-bold">TOTAL À PAYER</span>
                                            <span id="totalPriceRT" class="text-3xl font-black">
                                                {{ number_format(($roundTripData['total_price'] ?? 0) * 1.1, 0, ',', ' ') }}
                                                {{ $searchParams['currency'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const adultsInput = document.getElementById('adults_rt');
                        const childrenInput = document.getElementById('children_rt');
                        const infantsInput = document.getElementById('infants_rt');
                        const passengerContainer = document.getElementById('passengersInfoRoundTrip');

                        function updatePassengerFieldsRT() {
                            const adults = parseInt(adultsInput.value) || 0;
                            const children = parseInt(childrenInput.value) || 0;
                            const infants = parseInt(infantsInput.value) || 0;
                            const total = adults + children + infants;

                            passengerContainer.innerHTML = '';

                            for (let i = 0; i < total; i++) {
                                let type = i < adults ? 'Adulte' : (i < adults + children ? 'Enfant' : 'Bébé');
                                let isFirstAdult = (i === 0 && type === 'Adulte');
                                let requiredAttr = isFirstAdult ? 'required' : '';

                                passengerContainer.innerHTML += `
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4">
                                        <h4 class="font-bold mb-3 text-gray-800">${type} ${i + 1} ${isFirstAdult ? '(Principal)' : ''}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <input type="text" name="passenger_names[]" placeholder="Nom complet" 
                                                   class="border-gray-300 rounded-md" required>
                                            <input type="email" name="passenger_emails[]" placeholder="Email ${isFirstAdult ? '(Obligatoire)' : '(Optionnel)'}" 
                                                   class="border-gray-300 rounded-md" ${requiredAttr}>
                                            <input type="tel" name="passenger_phones[]" placeholder="Téléphone ${isFirstAdult ? '(Obligatoire)' : '(Optionnel)'}" 
                                                   class="border-gray-300 rounded-md" ${requiredAttr}>
                                        </div>
                                    </div>
                                `;
                            }

                            updatePricingRT(total);
                        }

                        function updatePricingRT(passengers) {
                            const basePricePerPax = {{ $roundTripData['total_price'] ?? 0 }};
                            const totalBase = basePricePerPax * passengers;
                            const taxes = totalBase * 0.1;
                            const total = totalBase + taxes;

                            document.getElementById('passengerCountRT').textContent = passengers;
                            document.getElementById('basePriceRT').textContent = totalBase.toLocaleString() + ' {{ $searchParams["currency"] }}';
                            document.getElementById('taxesRT').textContent = taxes.toLocaleString() + ' {{ $searchParams["currency"] }}';
                            document.getElementById('totalPriceRT').textContent = total.toLocaleString() + ' {{ $searchParams["currency"] }}';

                            document.getElementById('hidden_base_price_rt').value = totalBase.toFixed(2);
                            document.getElementById('hidden_taxes_rt').value = taxes.toFixed(2);
                            document.getElementById('hidden_final_price_rt').value = total.toFixed(2);
                        }

                        [adultsInput, childrenInput, infantsInput].forEach(input => {
                            input.addEventListener('change', updatePassengerFieldsRT);
                        });

                        updatePassengerFieldsRT();
                    });
                </script>
            @endif
        </div>
    </div>
@endsection