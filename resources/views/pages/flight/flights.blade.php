@extends('layouts.app')

@section('title', 'Vols - Carré Premium')

@section('content')
    <div class="min-h-screen bg-white">
        {{-- Hero Section --}}
        <section class="bg-gradient-to-r from-purple-600 to-purple-700 text-white py-12 md:py-20">
            <div class="container mx-auto px-4">
                <div class="max-w-4xl mx-auto text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black mb-4 md:mb-6 leading-tight">
                        Réservez votre <span class="text-yellow-400">Vol</span>
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl opacity-90 max-w-2xl mx-auto px-2">
                        Trouvez les meilleurs vols aux meilleurs prix
                    </p>
                </div>
            </div>
        </section>

        {{-- Formulaire de Recherche --}}
        <section class="bg-white shadow-2xl -mt-8 relative z-10">
            <div class="container mx-auto px-4 py-8 md:py-12">
                {{-- Type de voyage --}}
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mb-6">
                    <button type="button" id="btn-roundtrip"
                        class="flex-1 py-3 md:py-4 px-4 md:px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl text-sm md:text-base">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span>Aller-Retour</span>
                        </div>
                    </button>
                    <button type="button" id="btn-oneway"
                        class="flex-1 py-3 md:py-4 px-4 md:px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm md:text-base">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                            <span>Aller Simple</span>
                        </div>
                    </button>
                    <button type="button" id="btn-multicity"
                        class="flex-1 py-3 md:py-4 px-4 md:px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm md:text-base">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>Multi-Villes</span>
                        </div>
                    </button>
                </div>

                {{-- Messages d'erreur --}}
                @if (session('error'))
                    <div class="bg-red-50 border-l-4 border-red-500 p-5 rounded-xl mb-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-red-700 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
                <div id="error-message"
                    class="hidden bg-red-50 border-l-4 border-red-500 p-5 rounded-xl mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-red-700 font-semibold" id="error-text"></p>
                    </div>
                </div>

                <form id="flight-search-form" method="POST" action="{{ route('flights.search') }}">
                    @csrf
                    <input type="hidden" id="trip-type" name="type" value="1">
                    <input type="hidden" id="multi-city-data" name="multi_city_json" value="">

                    {{-- Vol Simple/Aller-Retour --}}
                    <div id="standard-flight-fields">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                            <div class="relative">
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 md:mb-3">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span>Aéroport de Départ *</span>
                                </label>
                                <input type="text" id="origin-input" placeholder="Ex: CDG, Paris..."
                                    class="w-full pl-3 md:pl-4 pr-3 md:pr-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 text-base md:text-lg font-semibold"
                                    autocomplete="off">
                                <input type="hidden" id="origin-code" name="departure_id">
                                <div id="origin-suggestions" class="hidden absolute z-50 w-full mt-2 bg-white border-2 border-purple-200 rounded-xl md:rounded-2xl shadow-2xl max-h-60 md:max-h-80 overflow-y-auto"></div>
                            </div>

                            <div class="relative">
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 md:mb-3">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span>Aéroport d'Arrivée *</span>
                                </label>
                                <input type="text" id="destination-input" placeholder="Ex: JFK, New York..."
                                    class="w-full pl-3 md:pl-4 pr-3 md:pr-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 text-base md:text-lg font-semibold"
                                    autocomplete="off">
                                <input type="hidden" id="destination-code" name="arrival_id">
                                <div id="destination-suggestions" class="hidden absolute z-50 w-full mt-2 bg-white border-2 border-amber-200 rounded-xl md:rounded-2xl shadow-2xl max-h-60 md:max-h-80 overflow-y-auto"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6">
                            <div>
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 md:mb-3">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Date de Départ *</span>
                                </label>
                                <input type="date" id="departure-date" name="outbound_date" min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 md:px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 text-base md:text-lg font-semibold">
                            </div>

                            <div id="return-date-container">
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 md:mb-3">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>Date de Retour *</span>
                                </label>
                                <input type="date" id="return-date" name="return_date" min="{{ date('Y-m-d') }}"
                                    class="w-full px-3 md:px-4 py-3 md:py-4 border-2 border-gray-300 rounded-xl md:rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 text-base md:text-lg font-semibold">
                            </div>
                        </div>
                    </div>

                    {{-- Multi-Villes --}}
                    <div id="multi-city-fields" class="hidden">
                        <div id="multi-city-flights-container">
                            <!-- Les vols multi-villes seront ajoutés dynamiquement ici -->
                        </div>
                        <button type="button" id="add-flight-btn"
                            class="flex items-center space-x-2 text-purple-600 hover:text-purple-700 font-bold py-3 px-4 rounded-xl border-2 border-purple-300 hover:border-purple-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Ajouter un vol</span>
                        </button>
                    </div>

                    {{-- Passagers et Classe --}}
                    <div class="bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl md:rounded-2xl p-4 md:p-6 mb-6 border-2 border-purple-100">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h3 class="text-base md:text-lg font-black text-gray-900">Passagers & Classe</h3>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
                            <div>
                                <label class="block text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 md:mb-2">Adultes (12+)</label>
                                <select name="adults" id="adults"
                                    class="w-full px-3 md:px-4 py-2 md:py-3 border-2 border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold text-sm md:text-base">
                                    @for ($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 md:mb-2">Enfants (2-11)</label>
                                <select name="children" id="children"
                                    class="w-full px-3 md:px-4 py-2 md:py-3 border-2 border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold text-sm md:text-base">
                                    @for ($i = 0; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 md:mb-2">Bébés (0-2)</label>
                                <select name="infants" id="infants"
                                    class="w-full px-3 md:px-4 py-2 md:py-3 border-2 border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold text-sm md:text-base">
                                    @for ($i = 0; $i <= 4; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs md:text-sm font-bold text-gray-700 dark:text-gray-300 mb-1 md:mb-2">Classe</label>
                                <select name="travel_class" id="travel-class"
                                    class="w-full px-3 md:px-4 py-2 md:py-3 border-2 border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold text-sm md:text-base">
                                    <option value="ECONOMY">Économique</option>
                                    <option value="PREMIUM_ECONOMY">Éco Premium</option>
                                    <option value="BUSINESS">Affaires</option>
                                    <option value="FIRST">Première</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="mb-6 space-y-2 md:space-y-3">
                        <label class="flex items-center space-x-2 md:space-x-3 cursor-pointer group">
                            <input type="checkbox" name="non_stop" id="non-stop"
                                class="w-5 h-5 md:w-6 md:h-6 text-purple-600 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-purple-500/50">
                            <span class="text-gray-700 font-bold text-base md:text-lg group-hover:text-purple-600 transition-colors">
                                Vols directs uniquement
                            </span>
                        </label>

                        <label class="flex items-center space-x-2 md:space-x-3 cursor-pointer group">
                            <input type="checkbox" name="deep_search" id="deep-search"
                                class="w-5 h-5 md:w-6 md:h-6 text-purple-600 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-purple-500/50">
                            <span class="text-gray-700 font-bold text-base md:text-lg group-hover:text-purple-600 transition-colors">
                                Recherche approfondie (plus lent mais plus de résultats)
                            </span>
                        </label>
                    </div>

                    {{-- Tri --}}
                    <div class="mb-6 md:mb-8">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2 md:mb-3">Trier par</label>
                        <select name="sort_by" id="sort-by"
                            class="w-full px-3 md:px-4 py-2 md:py-3 border-2 border-gray-300 rounded-lg md:rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold text-sm md:text-base">
                            <option value="1">Meilleurs vols</option>
                            <option value="2">Prix</option>
                            <option value="3">Heure de départ</option>
                            <option value="4">Heure d'arrivée</option>
                            <option value="5">Durée</option>
                            <option value="6">Émissions</option>
                        </select>
                    </div>

                    <input type="hidden" name="currency" value="EUR">

                    {{-- Bouton de recherche --}}
                    <button type="submit" id="search-btn"
                        class="w-full bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 hover:from-purple-700 hover:via-purple-800 hover:to-amber-700 text-white font-black text-lg md:text-xl py-4 md:py-6 px-6 md:px-8 rounded-xl md:rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-2xl flex items-center justify-center space-x-2 md:space-x-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>RECHERCHER DES VOLS</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- Section Résultats --}}
        <section class="container mx-auto px-4 py-12 md:py-16">
            <div class="max-w-4xl mx-auto text-center">
                <div class="bg-white rounded-2xl md:rounded-3xl p-8 md:p-12 shadow-xl border-2 border-gray-100">
                    <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-r from-purple-600 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-4 md:mb-6">
                        <svg class="w-10 h-10 md:w-12 md:h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-3 md:mb-4">Prêt à rechercher vos vols ?</h3>
                    <p class="text-gray-600 text-base md:text-lg mb-6 md:mb-8 px-2">Remplissez le formulaire ci-dessus et découvrez les meilleurs vols aux meilleurs prix.</p>
                    <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
                        <div class="flex items-center space-x-2 text-purple-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-semibold text-sm md:text-base">Prix compétitifs</span>
                        </div>
                        <div class="flex items-center space-x-2 text-purple-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-semibold text-sm md:text-base">Réservation instantanée</span>
                        </div>
                        <div class="flex items-center space-x-2 text-purple-600">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            <span class="font-semibold text-sm md:text-base">Support 24/7</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM chargé - initialisation');

    // Variables globales
    let multiCityFlights = [];
    let flightCounter = 0;

    // Éléments DOM
    const tripTypeInput = document.getElementById('trip-type');
    const multiCityDataInput = document.getElementById('multi-city-data');
    const returnDateContainer = document.getElementById('return-date-container');
    const returnDateInput = document.getElementById('return-date');
    const btnRoundtrip = document.getElementById('btn-roundtrip');
    const btnOneway = document.getElementById('btn-oneway');
    const btnMulticity = document.getElementById('btn-multicity');
    const standardFlightFields = document.getElementById('standard-flight-fields');
    const multiCityFields = document.getElementById('multi-city-fields');
    const multiCityContainer = document.getElementById('multi-city-flights-container');
    const addFlightBtn = document.getElementById('add-flight-btn');

    // Gestion du type de voyage
    function setTripType(type) {
        // Reset des classes
        [btnRoundtrip, btnOneway, btnMulticity].forEach(btn => {
            btn.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200';
        });

        if (type === 1) {
            // Aller-Retour
            tripTypeInput.value = '1';
            btnRoundtrip.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl';
            standardFlightFields.style.display = 'block';
            multiCityFields.classList.add('hidden');
            returnDateContainer.style.display = 'block';
            returnDateInput.setAttribute('required', 'required');
            document.getElementById('origin-input').setAttribute('required', 'required');
            document.getElementById('destination-input').setAttribute('required', 'required');
            document.getElementById('departure-date').setAttribute('required', 'required');
        } else if (type === 2) {
            // Aller Simple
            tripTypeInput.value = '2';
            btnOneway.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl';
            standardFlightFields.style.display = 'block';
            multiCityFields.classList.add('hidden');
            returnDateContainer.style.display = 'none';
            returnDateInput.removeAttribute('required');
            returnDateInput.value = '';
            document.getElementById('origin-input').setAttribute('required', 'required');
            document.getElementById('destination-input').setAttribute('required', 'required');
            document.getElementById('departure-date').setAttribute('required', 'required');
        } else if (type === 3) {
            // Multi-Villes
            tripTypeInput.value = '3';
            btnMulticity.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl';
            standardFlightFields.style.display = 'none';
            multiCityFields.classList.remove('hidden');
            document.getElementById('origin-input').removeAttribute('required');
            document.getElementById('destination-input').removeAttribute('required');
            document.getElementById('departure-date').removeAttribute('required');
            returnDateInput.removeAttribute('required');
            
            // Initialiser avec 2 vols si vide
            if (multiCityFlights.length === 0) {
                addMultiCityFlight();
                addMultiCityFlight();
            }
        }
    }

    // Initialisation
    setTripType(1);

    btnRoundtrip.addEventListener('click', () => setTripType(1));
    btnOneway.addEventListener('click', () => setTripType(2));
    btnMulticity.addEventListener('click', () => setTripType(3));

    // Fonction pour ajouter un vol multi-villes
    function addMultiCityFlight() {
        flightCounter++;
        const flightId = `flight-${flightCounter}`;
        
        const flightDiv = document.createElement('div');
        flightDiv.className = 'bg-white p-6 rounded-xl border-2 border-gray-200 mb-4';
        flightDiv.id = flightId;
        flightDiv.innerHTML = `
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-bold text-gray-900">Vol ${flightCounter}</h4>
                ${flightCounter > 2 ? `
                    <button type="button" class="remove-flight text-red-600 hover:text-red-700 font-bold" data-flight-id="${flightId}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                ` : ''}
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Départ *</label>
                    <input type="text" class="mc-origin-input w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500"
                           placeholder="Ex: CDG, Paris..." autocomplete="off" required>
                    <input type="hidden" class="mc-origin-code">
                    <div class="mc-origin-suggestions hidden absolute z-50 w-full mt-2 bg-white border-2 border-purple-200 rounded-xl shadow-xl max-h-60 overflow-y-auto"></div>
                </div>

                <div class="relative">
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Arrivée *</label>
                    <input type="text" class="mc-destination-input w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500"
                           placeholder="Ex: JFK, New York..." autocomplete="off" required>
                    <input type="hidden" class="mc-destination-code">
                    <div class="mc-destination-suggestions hidden absolute z-50 w-full mt-2 bg-white border-2 border-amber-200 rounded-xl shadow-xl max-h-60 overflow-y-auto"></div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Date *</label>
                    <input type="date" class="mc-date w-full px-4 py-3 border-2 border-gray-300 rounded-xl focus:ring-2 focus:ring-purple-500"
                           min="{{ date('Y-m-d') }}" required>
                </div>
            </div>
        `;

        multiCityContainer.appendChild(flightDiv);

        // Initialiser l'autocomplétion pour ce vol
        initAutocomplete(flightDiv);

        // Gérer la suppression
        const removeBtn = flightDiv.querySelector('.remove-flight');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                flightDiv.remove();
                updateMultiCityFlights();
            });
        }

        multiCityFlights.push(flightId);
        updateMultiCityFlights();
    }

    // Ajouter un vol
    addFlightBtn.addEventListener('click', addMultiCityFlight);

    // Mettre à jour les données multi-villes
    function updateMultiCityFlights() {
        const flights = [];
        const flightDivs = multiCityContainer.querySelectorAll('[id^="flight-"]');
        
        flightDivs.forEach((flightDiv) => {
            const originCode = flightDiv.querySelector('.mc-origin-code').value;
            const destinationCode = flightDiv.querySelector('.mc-destination-code').value;
            const date = flightDiv.querySelector('.mc-date').value;

            if (originCode && destinationCode && date) {
                flights.push({
                    departure_id: originCode,
                    arrival_id: destinationCode,
                    date: date
                });
            }
        });

        multiCityDataInput.value = JSON.stringify(flights);
        console.log('Multi-city data updated:', flights);
    }

    // Autocomplétion des aéroports
    const originInput = document.getElementById('origin-input');
    const originCode = document.getElementById('origin-code');
    const originSuggestions = document.getElementById('origin-suggestions');

    const destinationInput = document.getElementById('destination-input');
    const destinationCode = document.getElementById('destination-code');
    const destinationSuggestions = document.getElementById('destination-suggestions');

    let timeout;

    function fetchLocations(keyword, suggestionsEl, codeInputEl, type) {
        if (keyword.length < 2) {
            suggestionsEl.classList.add('hidden');
            return;
        }

        suggestionsEl.innerHTML = '<div class="p-4 text-center text-gray-500">Chargement...</div>';
        suggestionsEl.classList.remove('hidden');

        const url = `/api/locations/search?q=${encodeURIComponent(keyword)}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                suggestionsEl.innerHTML = '';

                if (data.length === 0) {
                    suggestionsEl.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <div>Aucun aéroport trouvé</div>
                            <div class="text-xs mt-1">Essayez avec un autre nom ou code</div>
                        </div>
                    `;
                    return;
                }

                data.forEach((location) => {
                    const div = document.createElement('div');
                    div.className = 'p-3 cursor-pointer hover:bg-purple-50 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition-colors duration-200';
                    div.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div>
                    <div class="font-semibold text-gray-900">${location.name}</div>
                    <div class="text-sm text-gray-600 mt-1">${location.municipality}, ${location.country}</div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="bg-purple-100 text-purple-800 text-xs px-2 py-1 rounded font-mono font-bold">${location.iataCode}</span>
                            </div>
                        </div>
                    `;

                    div.addEventListener('click', () => {
                        codeInputEl.value = location.iataCode;
                        const inputEl = codeInputEl.previousElementSibling;
                        inputEl.value = `${location.name} (${location.iataCode})`;
                        suggestionsEl.classList.add('hidden');
                    });

                    suggestionsEl.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Erreur:', error);
                suggestionsEl.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <div>Erreur de connexion</div>
                    </div>
                `;
            });
    }

    // Initialiser l'autocomplétion pour les champs multi-villes
    function initAutocomplete(container) {
        const originInput = container.querySelector('.mc-origin-input');
        const originCode = container.querySelector('.mc-origin-code');
        const originSuggestions = container.querySelector('.mc-origin-suggestions');

        const destinationInput = container.querySelector('.mc-destination-input');
        const destinationCode = container.querySelector('.mc-destination-code');
        const destinationSuggestions = container.querySelector('.mc-destination-suggestions');

        const dateInput = container.querySelector('.mc-date');

        // Origine
        originInput.addEventListener('input', (e) => {
            const keyword = e.target.value.trim();
            clearTimeout(timeout);
            originCode.value = '';

            timeout = setTimeout(() => {
                fetchLocations(keyword, originSuggestions, originCode, 'origin');
            }, 300);
        });

        originInput.addEventListener('focus', () => {
            const keyword = originInput.value.trim();
            if (keyword.length >= 2) {
                fetchLocations(keyword, originSuggestions, originCode, 'origin');
            }
        });

        // Destination
        destinationInput.addEventListener('input', (e) => {
            const keyword = e.target.value.trim();
            clearTimeout(timeout);
            destinationCode.value = '';

            timeout = setTimeout(() => {
                fetchLocations(keyword, destinationSuggestions, destinationCode, 'destination');
            }, 300);
        });

        destinationInput.addEventListener('focus', () => {
            const keyword = destinationInput.value.trim();
            if (keyword.length >= 2) {
                fetchLocations(keyword, destinationSuggestions, destinationCode, 'destination');
            }
        });

        // Mettre à jour les données quand on change la date
        dateInput.addEventListener('change', updateMultiCityFlights);

        // Fermer les suggestions au clic extérieur
        document.addEventListener('click', (e) => {
            if (!originInput.contains(e.target) && !originSuggestions.contains(e.target)) {
                originSuggestions.classList.add('hidden');
            }
            if (!destinationInput.contains(e.target) && !destinationSuggestions.contains(e.target)) {
                destinationSuggestions.classList.add('hidden');
            }
        });
    }

    // Autocomplétion pour les champs standard
    originInput.addEventListener('input', (e) => {
        const keyword = e.target.value.trim();
        clearTimeout(timeout);
        originCode.value = '';

        timeout = setTimeout(() => {
            fetchLocations(keyword, originSuggestions, originCode, 'origin');
        }, 300);
    });

    originInput.addEventListener('focus', () => {
        const keyword = originInput.value.trim();
        if (keyword.length >= 2) {
            fetchLocations(keyword, originSuggestions, originCode, 'origin');
        }
    });

    destinationInput.addEventListener('input', (e) => {
        const keyword = e.target.value.trim();
        clearTimeout(timeout);
        destinationCode.value = '';

        timeout = setTimeout(() => {
            fetchLocations(keyword, destinationSuggestions, destinationCode, 'destination');
        }, 300);
    });

    destinationInput.addEventListener('focus', () => {
        const keyword = destinationInput.value.trim();
        if (keyword.length >= 2) {
            fetchLocations(keyword, destinationSuggestions, destinationCode, 'destination');
        }
    });

    // Cacher les suggestions
    document.addEventListener('click', (e) => {
        if (!originInput.contains(e.target) && !originSuggestions.contains(e.target)) {
            originSuggestions.classList.add('hidden');
        }
        if (!destinationInput.contains(e.target) && !destinationSuggestions.contains(e.target)) {
            destinationSuggestions.classList.add('hidden');
        }
    });

    // Validation du formulaire
    const form = document.getElementById('flight-search-form');
    form.addEventListener('submit', (e) => {
        const tripType = parseInt(tripTypeInput.value);
        
        if (tripType === 3) {
            // Validation multi-villes
            updateMultiCityFlights();
            const multiCityData = JSON.parse(multiCityDataInput.value || '[]');
            
            if (multiCityData.length < 2) {
                e.preventDefault();
                showError('Veuillez ajouter au moins 2 vols pour une recherche multi-villes.');
                return;
            }

            // Vérifier que tous les champs sont remplis
            const allFlightDivs = multiCityContainer.querySelectorAll('[id^="flight-"]');
            let isValid = true;
            
            allFlightDivs.forEach(flightDiv => {
                const originCode = flightDiv.querySelector('.mc-origin-code').value;
                const destinationCode = flightDiv.querySelector('.mc-destination-code').value;
                const date = flightDiv.querySelector('.mc-date').value;

                if (!originCode || !destinationCode || !date) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                showError('Veuillez remplir tous les champs de vol.');
                return;
            }
        } else {
            // Validation standard
            if (!originCode.value || !destinationCode.value) {
                e.preventDefault();
                showError('Veuillez sélectionner des aéroports valides dans la liste de suggestions.');
                
                if (!originCode.value) {
                    originInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                }
                if (!destinationCode.value) {
                    destinationInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
                }
                return;
            }
        }

        // Cacher l'erreur si tout est bon
        document.getElementById('error-message').classList.add('hidden');
    });

    function showError(message) {
        const errorElement = document.getElementById('error-message');
        const errorText = document.getElementById('error-text');
        errorText.textContent = message;
        errorElement.classList.remove('hidden');
        errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }

    // Réinitialiser les styles d'erreur
    [originInput, destinationInput].forEach(input => {
        input.addEventListener('input', () => {
            input.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
        });
    });

    console.log('Initialisation terminée avec succès');
});
</script>
@endsection