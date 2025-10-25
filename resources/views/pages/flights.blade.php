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
                @if (session('error'))
                    <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-5 rounded-xl mb-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd" />
                            </svg>
                            <p class="text-red-700 dark:text-red-400 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif
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

                <form id="flight-search-form" method="POST" action="{{ route('flights.search') }}">
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
                            <input type="text" id="origin-input" name="origin" placeholder="Ex: CDG, Paris, Charles de Gaulle..."
                                class="w-full pl-4 pr-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 dark:bg-gray-700 dark:text-white text-lg font-semibold"
                                required autocomplete="off">
                            <input type="hidden" id="origin-code" name="departure_id">

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
                                placeholder="Ex: JFK, New York, John F. Kennedy..."
                                class="w-full pl-4 pr-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 dark:bg-gray-700 dark:text-white text-lg font-semibold"
                                required autocomplete="off">
                            <input type="hidden" id="destination-code" name="arrival_id">

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
                            <input type="date" id="departure-date" name="outbound_date" min="{{ date('Y-m-d') }}"
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

                    {{-- Ajout du champ 'currency' nécessaire à l'API, bien que nullable --}}
                    <input type="hidden" name="currency" value="EUR">

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

        {{-- Section Résultats --}}
        <section class="container mx-auto px-4 py-8">
            <p class="text-center text-gray-500 dark:text-gray-400">Les résultats s'afficheront sur la page dédiée après la
                soumission du formulaire.</p>
        </section>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM chargé - initialisation de l\'autocomplétion');

    // Gestion du type de voyage
    const returnDateContainer = document.getElementById('return-date-container');
    const returnDateInput = document.getElementById('return-date');
    const btnRoundtrip = document.getElementById('btn-roundtrip');
    const btnOneway = document.getElementById('btn-oneway');

    function setTripType(isRoundTrip) {
        if (isRoundTrip) {
            returnDateContainer.style.display = 'block';
            returnDateInput.setAttribute('required', 'required');
            btnRoundtrip.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl';
            btnOneway.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200';
        } else {
            returnDateContainer.style.display = 'none';
            returnDateInput.removeAttribute('required');
            returnDateInput.value = '';
            btnOneway.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl';
            btnRoundtrip.className = 'flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200';
        }
    }

    // Initialisation
    setTripType(true);

    btnRoundtrip.addEventListener('click', () => setTripType(true));
    btnOneway.addEventListener('click', () => setTripType(false));

    // Autocomplétion des aéroports
    const originInput = document.getElementById('origin-input');
    const originCode = document.getElementById('origin-code');
    const originSuggestions = document.getElementById('origin-suggestions');

    const destinationInput = document.getElementById('destination-input');
    const destinationCode = document.getElementById('destination-code');
    const destinationSuggestions = document.getElementById('destination-suggestions');

    let timeout;

    function fetchLocations(keyword, suggestionsEl, codeInputEl, type) {
        console.log(`Recherche ${type} pour: "${keyword}"`);
        
        if (keyword.length < 2) {
            console.log(`Mot-clé trop court (${keyword.length} caractères), masquage des suggestions`);
            suggestionsEl.classList.add('hidden');
            return;
        }

        suggestionsEl.innerHTML = '<div class="p-4 text-center text-gray-500">Chargement...</div>';
        suggestionsEl.classList.remove('hidden');

        const url = `/api/locations/search?q=${encodeURIComponent(keyword)}`;
        console.log(`Requête API: ${url}`);

        fetch(url)
            .then(response => {
                console.log(`Réponse reçue - Status: ${response.status}`);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log(`Données reçues: ${data.length} résultats`, data);
                suggestionsEl.innerHTML = '';

                if (data.length === 0) {
                    console.log('Aucun résultat trouvé');
                    suggestionsEl.innerHTML = `
                        <div class="p-4 text-center text-gray-500">
                            <div>Aucun aéroport trouvé</div>
                            <div class="text-xs mt-1">Essayez avec un autre nom ou code</div>
                        </div>
                    `;
                    return;
                }

                data.forEach((location, index) => {
                    console.log(`Traitement résultat ${index + 1}:`, location);
                    const div = document.createElement('div');
                    div.className = 'p-3 cursor-pointer hover:bg-purple-50 dark:hover:bg-gray-700 border-b dark:border-gray-600 transition-colors duration-200';
                    div.innerHTML = `
                        <div class="flex justify-between items-start">
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">${location.name}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">${location.municipality}, ${location.country}</div>
                            </div>
                            <div class="flex flex-col items-end">
                                <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs px-2 py-1 rounded font-mono font-bold">${location.iataCode}</span>
                                ${location.icaoCode ? `<span class="text-xs text-gray-500 mt-1">${location.icaoCode}</span>` : ''}
                            </div>
                        </div>
                    `;

                    div.addEventListener('click', () => {
                        console.log(`Sélection ${type}:`, location.name, location.iataCode);
                        if (type === 'origin') {
                            originInput.value = `${location.name} (${location.iataCode})`;
                            originCode.value = location.iataCode;
                            console.log('Origin défini:', originInput.value, 'Code:', originCode.value);
                        } else {
                            destinationInput.value = `${location.name} (${location.iataCode})`;
                            destinationCode.value = location.iataCode;
                            console.log('Destination définie:', destinationInput.value, 'Code:', destinationCode.value);
                        }
                        suggestionsEl.classList.add('hidden');
                    });

                    // Effet hover
                    div.addEventListener('mouseenter', () => {
                        div.classList.add('bg-purple-50', 'dark:bg-gray-700');
                    });
                    div.addEventListener('mouseleave', () => {
                        div.classList.remove('bg-purple-50', 'dark:bg-gray-700');
                    });

                    suggestionsEl.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                suggestionsEl.innerHTML = `
                    <div class="p-4 text-center text-red-500">
                        <div>Erreur de connexion</div>
                        <div class="text-xs mt-1">${error.message}</div>
                    </div>
                `;
            });
    }

    // Gestion des événements pour l'origine
    originInput.addEventListener('input', (e) => {
        const keyword = e.target.value.trim();
        console.log('Input origine:', keyword);
        
        clearTimeout(timeout);
        originCode.value = ''; // Reset le code IATA

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

    // Gestion des événements pour la destination
    destinationInput.addEventListener('input', (e) => {
        const keyword = e.target.value.trim();
        console.log('Input destination:', keyword);
        
        clearTimeout(timeout);
        destinationCode.value = ''; // Reset le code IATA

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

    // Cacher les suggestions en cliquant ailleurs
    document.addEventListener('click', (e) => {
        if (!originInput.contains(e.target) && !originSuggestions.contains(e.target)) {
            originSuggestions.classList.add('hidden');
        }
        if (!destinationInput.contains(e.target) && !destinationSuggestions.contains(e.target)) {
            destinationSuggestions.classList.add('hidden');
        }
    });

    // Empêcher la fermeture quand on clique dans les suggestions
    [originSuggestions, destinationSuggestions].forEach(suggestionsEl => {
        suggestionsEl.addEventListener('click', (e) => {
            e.stopPropagation();
        });
    });

    // Validation du formulaire
    const form = document.getElementById('flight-search-form');
    form.addEventListener('submit', (e) => {
        console.log('Soumission du formulaire');
        console.log('Origin code:', originCode.value);
        console.log('Destination code:', destinationCode.value);

        if (!originCode.value || !destinationCode.value) {
            e.preventDefault();
            const errorMessage = 'Veuillez sélectionner des aéroports valides dans la liste de suggestions.';
            console.error(errorMessage);
            
            // Afficher le message d'erreur dans l'interface
            const errorElement = document.getElementById('error-message');
            const errorText = document.getElementById('error-text');
            errorText.textContent = errorMessage;
            errorElement.classList.remove('hidden');
            
            // Scroll vers l'erreur
            errorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Highlight des champs problématiques
            if (!originCode.value) {
                originInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            }
            if (!destinationCode.value) {
                destinationInput.classList.add('border-red-500', 'ring-2', 'ring-red-200');
            }
        } else {
            // Cacher l'erreur si tout est bon
            document.getElementById('error-message').classList.add('hidden');
            originInput.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
            destinationInput.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
        }
    });

    // Réinitialiser les styles d'erreur quand on commence à taper
    [originInput, destinationInput].forEach(input => {
        input.addEventListener('input', () => {
            input.classList.remove('border-red-500', 'ring-2', 'ring-red-200');
        });
    });

    console.log('Autocomplétion initialisée avec succès');
});
</script>
@endsection