@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8 lg:py-12">
        @if($error)
            <div class="max-w-4xl mx-auto bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md"
                role="alert">
                <p class="font-bold">Erreur de chargement</p>
                <p>{{ $error }}</p>
            </div>
        @elseif($selectedFlight)
            <div class="max-w-7xl mx-auto">
                <h1 class="text-3xl lg:text-4xl font-extrabold text-gray-900 mb-8 border-b pb-4">
                    Détails et réservation de votre vol
                </h1>

                <div class="lg:grid lg:grid-cols-3 lg:gap-8">

                    {{-- COLONNE PRINCIPALE --}}
                    <div class="lg:col-span-2">

                        {{-- CARTE DES DÉTAILS DU VOL --}}
                        <div class="bg-white rounded-xl shadow-2xl p-6 mb-8 border border-gray-100">
                            <div class="flex justify-between items-start border-b pb-4 mb-4">
                                <div>
                                    <h2 class="text-xl font-bold text-gray-800">
                                        {{ $selectedFlight['flights'][0]['airline'] ?? 'Détails du Vol' }}</h2>
                                    <p class="text-sm text-gray-500">Durée totale : <span
                                            class="font-semibold">{{ $selectedFlight['total_duration'] ?? 'N/A' }}</span></p>
                                </div>
                            </div>

                            {{-- Segments de vol --}}
                            @foreach($selectedFlight['flights'] as $index => $segment)
                                <div class="pt-4 @if(!$loop->first) border-t-2 border-dashed border-gray-200 mt-4 @endif">
                                    <div class="flex items-center justify-between space-x-4">
                                        <div class="flex-1 text-left">
                                            <p class="text-3xl font-extrabold text-gray-900">
                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                            </p>
                                            <p class="font-medium text-gray-700 mt-1">
                                                {{ $segment['departure_airport']['name'] ?? 'Ville de départ' }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d M Y') }}
                                            </p>
                                        </div>

                                        <div class="flex-shrink-0 w-32 text-center hidden sm:block">
                                            <div class="text-sm text-blue-600 font-semibold mb-1">{{ $segment['duration'] ?? '' }}
                                            </div>
                                            <div class="flex items-center justify-center">
                                                <div class="h-0.5 bg-gray-300 w-full rounded-l"></div>
                                                <svg class="w-5 h-5 text-blue-500 -mx-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path
                                                        d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                                </svg>
                                                <div class="h-0.5 bg-gray-300 w-full rounded-r"></div>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">Vol: {{ $segment['flight_number'] ?? 'N/A' }}</p>
                                        </div>

                                        <div class="flex-1 text-right">
                                            <p class="text-3xl font-extrabold text-gray-900">
                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}</p>
                                            <p class="font-medium text-gray-700 mt-1">
                                                {{ $segment['arrival_airport']['name'] ?? 'Ville d\'arrivée' }}</p>
                                            <p class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d M Y') }}
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                @if(isset($selectedFlight['layovers'][$index]))
                                    <div class="bg-gray-100 rounded-lg p-3 my-4 flex items-center justify-between text-sm">
                                        <p class="text-gray-700">
                                            <i class="fas fa-plane-arrival mr-2 text-blue-500"></i>
                                            <span class="font-bold">Escale à {{ $selectedFlight['layovers'][$index]['name'] }}</span>
                                        </p>
                                        <span class="font-semibold text-gray-600">Durée :
                                            {{ $selectedFlight['layovers'][$index]['duration'] }}</span>
                                    </div>
                                @endif
                            @endforeach
                        </div>

                        {{-- FORMULAIRE DE RÉSERVATION --}}
                        <div class="bg-white rounded-xl shadow-2xl p-6 border border-gray-100">
                            <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-3">Informations de la réservation</h2>

                            <form action="{{ route('flight.store-booking') }}" method="POST" id="bookingForm">
                                @csrf

                                {{-- Champs cachés CORRIGÉS - SANS htmlspecialchars --}}
                                <input type="hidden" name="booking_token" value="{{ request('booking_token') }}">
                                <input type="hidden" name="departure_token" value="{{ request('departure_token') }}">
                                <input type="hidden" name="departure_id" value="{{ request('departure_id') }}">
                                <input type="hidden" name="arrival_id" value="{{ request('arrival_id') }}">
                                <input type="hidden" name="outbound_date" value="{{ request('outbound_date') }}">
                                <input type="hidden" name="return_date" value="{{ request('return_date') }}">

                                {{-- CORRECTION CRITIQUE : Utiliser e() au lieu de htmlspecialchars --}}
                                <!-- <input type="hidden" name="flight_details" value="{{ e(json_encode($selectedFlight)) }}">
                                    <input type="hidden" name="booking_options" value="{{ e(json_encode($bookingOptions ?? [])) }}"> -->
                                <input type="hidden" name="flight_details" value='@json($selectedFlight)'>
                                <input type="hidden" name="booking_options" value='@json($bookingOptions)'>

                                {{-- Prix (seront mis à jour dynamiquement) --}}
                                <input type="hidden" name="base_price" id="hidden_base_price"
                                    value="{{ $selectedFlight['price'] ?? 0 }}">
                                <input type="hidden" name="taxes" id="hidden_taxes"
                                    value="{{ ($selectedFlight['price'] ?? 0) * 0.1 }}">
                                <input type="hidden" name="final_price" id="hidden_final_price"
                                    value="{{ ($selectedFlight['price'] ?? 0) * 1.1 }}">
                                <input type="hidden" name="currency" value="EUR">

                                {{-- Section Passagers et Classe --}}
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8 p-4 bg-gray-50 rounded-lg border">
                                    <div>
                                        <label for="adults" class="block text-sm font-medium text-gray-700 mb-1">Adultes
                                            (18+)</label>
                                        <input type="number" id="adults" name="adults" min="1" max="9"
                                            value="{{ request('adults', 1) }}"
                                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm"
                                            required>
                                    </div>
                                    <div>
                                        <label for="children" class="block text-sm font-medium text-gray-700 mb-1">Enfants
                                            (2-11)</label>
                                        <input type="number" id="children" name="children" min="0" max="8"
                                            value="{{ request('children', 0) }}"
                                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="infants" class="block text-sm font-medium text-gray-700 mb-1">Bébés
                                            (-2)</label>
                                        <input type="number" id="infants" name="infants" min="0" max="4"
                                            value="{{ request('infants', 0) }}"
                                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                    </div>
                                    <div>
                                        <label for="travel_class"
                                            class="block text-sm font-medium text-gray-700 mb-1">Classe</label>
                                        <select id="travel_class" name="travel_class"
                                            class="w-full border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                            <option value="ECONOMY">Économique</option>
                                            <option value="PREMIUM_ECONOMY">Économique Premium</option>
                                            <option value="BUSINESS">Affaires</option>
                                            <option value="FIRST">Première classe</option>
                                        </select>
                                    </div>
                                </div>

                                {{-- Informations passagers (dynamique) --}}
                                <div id="passengersInfo" class="mb-8">
                                    <h3 class="text-xl font-bold text-gray-800 mb-4">Détails de chaque passager</h3>
                                </div>

                                {{-- Boutons --}}
                                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t">
                                    <a href="{{ route('flights') }}"
                                        class="flex-1 text-center bg-gray-100 text-gray-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-200 transition duration-150 ease-in-out">
                                        ← Modifier la recherche
                                    </a>
                                    <button type="submit"
                                        class="flex-1 bg-blue-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-blue-700 transition duration-150 ease-in-out shadow-lg shadow-blue-500/50">
                                        Réserver maintenant
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>

                    {{-- COLONNE LATÉRALE --}}
                    <div class="lg:col-span-1 mt-8 lg:mt-0">
                        <div class="sticky top-10 bg-blue-50 rounded-xl shadow-xl p-6 border-2 border-blue-200">
                            <h2 class="text-xl font-bold text-blue-800 mb-4 pb-2 border-b border-blue-200">Résumé du Prix</h2>

                            <div class="space-y-3 text-gray-700">
                                <div class="flex justify-between">
                                    <span>Prix par personne</span>
                                    <span class="font-semibold">{{ number_format($selectedFlight['price'] ?? 0, 0, ',', ' ') }}
                                        €</span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Nombre de passagers</span>
                                    <span id="passengerCount" class="font-semibold">1</span>
                                </div>
                                <div class="flex justify-between pt-3 border-t">
                                    <span>Sous-total (Base)</span>
                                    <span id="basePrice"
                                        class="font-semibold text-gray-800">{{ number_format($selectedFlight['price'] ?? 0, 2, ',', ' ') }}
                                        €</span>
                                </div>
                                <div class="flex justify-between text-sm text-gray-600">
                                    <span>Taxes et Frais (estimation)</span>
                                    <span id="taxes">{{ number_format(($selectedFlight['price'] ?? 0) * 0.1, 2, ',', ' ') }}
                                        €</span>
                                </div>
                            </div>

                            <div class="mt-4 pt-4 border-t-2 border-blue-300">
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-blue-800">TOTAL À PAYER</span>
                                    <span id="totalPrice" class="text-3xl font-extrabold text-blue-600">
                                        {{ number_format(($selectedFlight['price'] ?? 0) * 1.1, 2, ',', ' ') }} €
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const adultsInput = document.getElementById('adults');
                    const childrenInput = document.getElementById('children');
                    const infantsInput = document.getElementById('infants');
                    const passengerContainer = document.getElementById('passengersInfo');
                    const passengerCountSpan = document.getElementById('passengerCount');

                    function updatePassengerFields() {
                        const adults = parseInt(adultsInput.value) || 0;
                        const children = parseInt(childrenInput.value) || 0;
                        const infants = parseInt(infantsInput.value) || 0;
                        const total = adults + children + infants;

                        passengerContainer.innerHTML = '';

                        for (let i = 0; i < total; i++) {
                            let type = i < adults ? 'Adulte' : (i < adults + children ? 'Enfant' : 'Bébé');
                            let placeholderName = (i === 0) ? 'Nom complet (Responsable)' : 'Nom complet';
                            let isFirstAdult = (i === 0 && type === 'Adulte');
                            let requiredAttr = isFirstAdult ? 'required' : '';
                            let emailPlaceholder = isFirstAdult ? 'Email (Obligatoire)' : 'Email (Optionnel)';
                            let phonePlaceholder = isFirstAdult ? 'Téléphone (Obligatoire)' : 'Téléphone (Optionnel)';

                            passengerContainer.innerHTML += `
                                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-4 shadow-sm">
                                        <h4 class="font-bold mb-3 text-gray-800">${type} ${i + 1} ${isFirstAdult ? '(Principal)' : ''}</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <input type="text" name="passenger_names[]" placeholder="${placeholderName}" 
                                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                            <input type="email" name="passenger_emails[]" placeholder="${emailPlaceholder}" 
                                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" ${requiredAttr}>
                                            <input type="tel" name="passenger_phones[]" placeholder="${phonePlaceholder}" 
                                                   class="border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" ${requiredAttr}>
                                        </div>
                                    </div>
                                `;
                        }

                        updatePricing(total);
                    }

                    function updatePricing(passengers) {
                        const basePricePerPax = {{ $selectedFlight['price'] ?? 0 }};
                        const totalBase = basePricePerPax * passengers;
                        const taxes = totalBase * 0.1;
                        const total = totalBase + taxes;

                        passengerCountSpan.textContent = passengers;
                        document.getElementById('basePrice').textContent = totalBase.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });
                        document.getElementById('taxes').textContent = taxes.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });
                        document.getElementById('totalPrice').textContent = total.toLocaleString('fr-FR', { style: 'currency', currency: 'EUR', minimumFractionDigits: 2 });

                        // Mise à jour des champs cachés
                        document.getElementById('hidden_base_price').value = totalBase.toFixed(2);
                        document.getElementById('hidden_taxes').value = taxes.toFixed(2);
                        document.getElementById('hidden_final_price').value = total.toFixed(2);
                    }

                    [adultsInput, childrenInput, infantsInput].forEach(input => {
                        input.addEventListener('change', updatePassengerFields);
                        input.addEventListener('keyup', updatePassengerFields);
                    });

                    updatePassengerFields();
                });
            </script>
        @endif
    </div>
@endsection