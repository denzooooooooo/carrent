@extends('layouts.app')

@section('title', 'Vols - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        {{-- Hero Section --}}
        <section class="bg-gradient-to-r from-purple-600 to-purple-700 text-white py-16">
            <div class="container mx-auto px-4">
                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    Réservez votre <span class="text-yellow-400">Vol</span>
                </h1>
                <p class="text-xl opacity-90">
                    Trouvez les meilleurs vols aux meilleurs prix
                </p>
            </div>
        </section>

        {{-- Formulaire de Recherche --}}
        <section class="bg-white dark:bg-gray-800 shadow-2xl -mt-8 relative z-10">
            <div class="container mx-auto px-4 py-8">
                {{-- Type de voyage --}}
                <div class="flex space-x-4 mb-6">
                    <button type="button" id="btn-roundtrip"
                        class="flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span>Aller-Retour</span>
                        </div>
                    </button>
                    <button type="button" id="btn-oneway"
                        class="flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                            <span>Aller Simple</span>
                        </div>
                    </button>
                </div>

                {{-- Message d'erreur --}}
                <div id="error-message"
                    class="hidden bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-5 rounded-xl mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <p class="text-red-700 dark:text-red-400 font-semibold" id="error-text"></p>
                    </div>
                </div>

                <form id="flight-search-form">
                    @csrf
                    {{-- Aéroports --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        {{-- Départ --}}
                        <div class="relative">
                            <label
                                class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                </svg>
                                <span>Aéroport de Départ *</span>
                            </label>
                            <input type="text" id="origin-input" name="origin" placeholder="Ex: Paris, ABJ, Abidjan..."
                                class="w-full pl-12 pr-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:bg-gray-700 dark:text-white text-lg font-semibold"
                                required autocomplete="off">
                            <input type="hidden" id="origin-code" name="origin_code">

                            {{-- Suggestions origine --}}
                            <div id="origin-suggestions"
                                class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-700 rounded-2xl shadow-2xl max-h-80 overflow-y-auto">
                            </div>
                        </div>

                        {{-- Arrivée --}}
                        <div class="relative">
                            <label
                                class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                <span>Aéroport d'Arrivée *</span>
                            </label>
                            <input type="text" id="destination-input" name="destination"
                                placeholder="Ex: Paris, CDG, New York..."
                                class="w-full pl-12 pr-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 dark:bg-gray-700 dark:text-white text-lg font-semibold"
                                required autocomplete="off">
                            <input type="hidden" id="destination-code" name="destination_code">

                            {{-- Suggestions destination --}}
                            <div id="destination-suggestions"
                                class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-amber-200 dark:border-amber-700 rounded-2xl shadow-2xl max-h-80 overflow-y-auto">
                            </div>
                        </div>
                    </div>

                    {{-- Dates --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label
                                class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Date de Départ *</span>
                            </label>
                            <input type="date" id="departure-date" name="departure_date" min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:bg-gray-700 dark:text-white text-lg font-semibold"
                                required>
                        </div>

                        <div id="return-date-container">
                            <label
                                class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span>Date de Retour *</span>
                            </label>
                            <input type="date" id="return-date" name="return_date" min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 dark:bg-gray-700 dark:text-white text-lg font-semibold">
                        </div>
                    </div>

                    {{-- Passagers et Classe --}}
                    <div
                        class="bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-2xl p-6 mb-6 border-2 border-purple-100 dark:border-purple-800">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">Passagers & Classe</h3>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Adultes
                                    (12+)</label>
                                <select name="adults" id="adults"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold">
                                    @for ($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Enfants
                                    (2-11)</label>
                                <select name="children" id="children"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold">
                                    @for ($i = 0; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Bébés
                                    (0-2)</label>
                                <select name="infants" id="infants"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold">
                                    @for ($i = 0; $i <= 4; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Classe</label>
                                <select name="travel_class" id="travel-class"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white font-semibold">
                                    <option value="ECONOMY">Économique</option>
                                    <option value="PREMIUM_ECONOMY">Éco Premium</option>
                                    <option value="BUSINESS">Affaires</option>
                                    <option value="FIRST">Première</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="mb-8">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" name="non_stop" id="non-stop"
                                class="w-6 h-6 text-purple-600 border-2 border-gray-300 rounded-lg focus:ring-4 focus:ring-purple-500/50">
                            <span
                                class="text-gray-700 dark:text-gray-300 font-bold text-lg group-hover:text-purple-600 transition-colors">
                                Vols directs uniquement (sans escale)
                            </span>
                        </label>
                    </div>

                    {{-- Bouton de recherche --}}
                    <button type="submit" id="search-btn"
                        class="w-full bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 hover:from-purple-700 hover:via-purple-800 hover:to-amber-700 text-white font-black text-xl py-6 px-8 rounded-2xl transition-all duration-300 transform hover:scale-105 shadow-2xl flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>RECHERCHER DES VOLS</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- Résultats --}}
        <section class="container mx-auto px-4 py-8">
            <div id="loading" class="hidden text-center py-20">
                <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-purple-600 mx-auto"></div>
                <p class="mt-4 text-xl text-gray-600">Recherche en cours...</p>
            </div>

            <div id="no-results" class="hidden text-center py-20 bg-white rounded-2xl shadow-lg">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
                <h3 class="text-2xl font-bold text-gray-700 mb-2">Aucun vol trouvé</h3>
                <p class="text-gray-500">Essayez de modifier vos critères de recherche</p>
            </div>

            <div id="results" class="space-y-6"></div>
        </section>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let tripType = 'roundtrip';
                let selectedOrigin = null;
                let selectedDestination = null;
                let searchTimeout = null;

                // Gestion du type de voyage
                const btnRoundtrip = document.getElementById('btn-roundtrip');
                const btnOneway = document.getElementById('btn-oneway');
                const returnDateContainer = document.getElementById('return-date-container');
                const returnDateInput = document.getElementById('return-date');

                btnRoundtrip.addEventListener('click', function () {
                    tripType = 'roundtrip';
                    btnRoundtrip.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-amber-600', 'text-white', 'shadow-xl');
                    btnRoundtrip.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700');
                    btnOneway.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-amber-600', 'text-white', 'shadow-xl');
                    btnOneway.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700');
                    returnDateContainer.classList.remove('hidden');
                    returnDateInput.required = true;
                });

                btnOneway.addEventListener('click', function () {
                    tripType = 'oneway';
                    btnOneway.classList.add('bg-gradient-to-r', 'from-purple-600', 'to-amber-600', 'text-white', 'shadow-xl');
                    btnOneway.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700');
                    btnRoundtrip.classList.remove('bg-gradient-to-r', 'from-purple-600', 'to-amber-600', 'text-white', 'shadow-xl');
                    btnRoundtrip.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700');
                    returnDateContainer.classList.add('hidden');
                    returnDateInput.required = false;
                    returnDateInput.value = '';
                });

                // Recherche d'aéroports
                // Recherche d'aéroports
                async function searchAirports(keyword, type) {
                    if (keyword.length < 3) {
                        document.getElementById(`${type}-suggestions`).classList.add('hidden');
                        return;
                    }

                    try {
                        // Requête GET correcte pour l'API Laravel
                        const response = await fetch(`/api/flights/airports/search?keyword=${encodeURIComponent(keyword)}`);
                        const data = await response.json();

                        // ✅ CORRECTION DE LA GESTION DE LA RÉPONSE JSON :
                        // Le contrôleur renvoie probablement le tableau des aéroports directement.
                        // On vérifie si la réponse a un champ 'data', sinon on utilise l'objet complet.
                        const airports = data.data || data;

                        // Le code original vérifiait 'data.success' (qui n'existe pas) et 'data.data.length'.
                        // On vérifie maintenant que la requête HTTP est OK et que 'airports' est un tableau non vide.
                        if (response.ok && Array.isArray(airports) && airports.length > 0) {
                            displaySuggestions(airports, type);
                        } else {
                            document.getElementById(`${type}-suggestions`).classList.add('hidden');
                        }
                    } catch (error) {
                        console.error('Erreur recherche aéroports:', error);
                        // Masquer les suggestions en cas d'erreur de réseau ou de parsing
                        document.getElementById(`${type}-suggestions`).classList.add('hidden');
                    }
                }

                function displaySuggestions(airports, type) {
                    const container = document.getElementById(`${type}-suggestions`);
                    container.innerHTML = airports.map(airport => `
                    <div class="airport-suggestion px-5 py-4 hover:bg-${type === 'origin' ? 'purple' : 'amber'}-50 dark:hover:bg-${type === 'origin' ? 'purple' : 'amber'}-900/30 cursor-pointer border-b border-gray-100 dark:border-gray-700 last:border-b-0 transition-colors"
                        data-code="${airport.iataCode}"
                        data-name="${airport.name}"
                        data-city="${airport.address?.cityName || ''}"
                        data-type="${type}">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-${type === 'origin' ? 'purple' : 'amber'}-600 to-${type === 'origin' ? 'purple' : 'amber'}-700 rounded-xl flex items-center justify-center flex-shrink-0">
                                <span class="text-white font-black text-sm">${airport.iataCode}</span>
                            </div>
                            <div class="flex-1">
                                <div class="font-bold text-gray-900 dark:text-white text-lg">${airport.name}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-semibold">
                                    ${airport.address?.cityName || ''}, ${airport.address?.countryName || ''}
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
                    container.classList.remove('hidden');

                    // Événements de clic sur les suggestions
                    container.querySelectorAll('.airport-suggestion').forEach(el => {
                        el.addEventListener('click', function () {
                            selectAirport({
                                iataCode: this.dataset.code,
                                name: this.dataset.name,
                                city: this.dataset.city
                            }, this.dataset.type);
                        });
                    });
                }

                function selectAirport(airport, type) {
                    const displayText = `${airport.name} (${airport.iataCode})`;
                    document.getElementById(`${type}-input`).value = displayText;
                    document.getElementById(`${type}-code`).value = airport.iataCode;
                    document.getElementById(`${type}-suggestions`).classList.add('hidden');

                    if (type === 'origin') {
                        selectedOrigin = airport;
                    } else {
                        selectedDestination = airport;
                    }
                }

                // Événements de saisie
                document.getElementById('origin-input').addEventListener('input', function (e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => searchAirports(e.target.value, 'origin'), 300);
                });

                document.getElementById('destination-input').addEventListener('input', function (e) {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(() => searchAirports(e.target.value, 'destination'), 300);
                });

                // Fermer les suggestions au clic extérieur
                document.addEventListener('click', function (e) {
                    if (!e.target.closest('#origin-input') && !e.target.closest('#origin-suggestions')) {
                        document.getElementById('origin-suggestions').classList.add('hidden');
                    }
                    if (!e.target.closest('#destination-input') && !e.target.closest('#destination-suggestions')) {
                        document.getElementById('destination-suggestions').classList.add('hidden');
                    }
                });

                // Soumission du formulaire
                document.getElementById('flight-search-form').addEventListener('submit', async function (e) {
                    e.preventDefault();

                    const errorMessage = document.getElementById('error-message');
                    const errorText = document.getElementById('error-text');
                    errorMessage.classList.add('hidden');

                    // Validation
                    if (!selectedOrigin || !selectedDestination) {
                        errorText.textContent = 'Veuillez sélectionner un aéroport de départ et d\'arrivée valide dans la liste.';
                        errorMessage.classList.remove('hidden');
                        return;
                    }

                    const formData = new FormData(this);
                    const payload = {
                        origin: selectedOrigin.iataCode,
                        destination: selectedDestination.iataCode,
                        departureDate: formData.get('departure_date'),
                        adults: parseInt(formData.get('adults')),
                        children: parseInt(formData.get('children') || 0),
                        infants: parseInt(formData.get('infants') || 0),
                        travelClass: formData.get('travel_class'),
                        nonStop: formData.get('non_stop') === 'on',
                        currencyCode: 'XOF' // À adapter selon votre configuration
                    };

                    if (tripType === 'roundtrip') {
                        payload.returnDate = formData.get('return_date');
                    }

                    // Afficher le loader
                    document.getElementById('loading').classList.remove('hidden');
                    document.getElementById('results').innerHTML = '';
                    document.getElementById('no-results').classList.add('hidden');

                    try {
                        const response = await fetch('/api/flights/search', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();

                        document.getElementById('loading').classList.add('hidden');

                        if (data.success && data.data.length > 0) {
                            displayResults(data.data);
                        } else {
                            document.getElementById('no-results').classList.remove('hidden');
                        }
                    } catch (error) {
                        console.error('Erreur:', error);
                        document.getElementById('loading').classList.add('hidden');
                        errorText.textContent = 'Erreur lors de la recherche de vols. Veuillez réessayer.';
                        errorMessage.classList.remove('hidden');
                    }
                });

                function displayResults(flights) {
                    const resultsContainer = document.getElementById('results');
                    resultsContainer.innerHTML = flights.map(flight => {
                        const itinerary = flight.itineraries[0];
                        const firstSegment = itinerary.segments[0];
                        const lastSegment = itinerary.segments[itinerary.segments.length - 1];

                        return `
                        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 p-6">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <div class="flex items-center gap-8">
                                        <div class="text-center">
                                            <div class="text-3xl font-bold text-gray-800">
                                                ${new Date(firstSegment.departure.at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                                            </div>
                                            <div class="text-lg font-semibold text-gray-600 mt-1">${firstSegment.departure.iataCode}</div>
                                        </div>

                                        <div class="flex-1 px-4">
                                            <div class="border-t-2 border-gray-300 relative">
                                                <svg class="absolute -top-3 left-1/2 transform -translate-x-1/2 w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                            </div>
                                            <div class="text-center mt-2">
                                                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                                    ${itinerary.segments.length === 1 ? 'Direct' : `${itinerary.segments.length - 1} escale(s)`}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <div class="text-3xl font-bold text-gray-800">
                                                ${new Date(lastSegment.arrival.at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })}
                                            </div>
                                            <div class="text-lg font-semibold text-gray-600 mt-1">${lastSegment.arrival.iataCode}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="text-right ml-8 pl-8 border-l-2 border-gray-200">
                                    <div class="text-4xl font-bold text-purple-600 mb-1">
                                        ${Math.round(parseFloat(flight.price.total)).toLocaleString()}
                                    </div>
                                    <div class="text-lg text-gray-600 mb-3">${flight.price.currency}</div>
                                    <a href="/flights/${flight.id}" class="block w-full bg-gradient-to-r from-purple-600 to-purple-800 hover:from-purple-700 hover:to-purple-900 text-white font-bold px-8 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                                        Sélectionner
                                    </a>
                                </div>
                            </div>
                        </div>
                    `;
                    }).join('');
                }
            });
        </script>
    @endpush
@endsection