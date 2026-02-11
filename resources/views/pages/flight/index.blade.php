@extends('layouts.app')

@section('title', __('Réservation de vols') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
    <!-- Hero Section with Background Image -->
    <div class="relative bg-[#001F3F] text-white py-20 lg:py-32 overflow-hidden">
        <!-- Background Image Overlay -->
        <div class="absolute inset-0 bg-gradient-to-r from-[#001F3F] via-[#001F3F]/95 to-[#001F3F]/90 z-10"></div>
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1436491865332-7a61a109cc05?w=1920&q=80')] bg-cover bg-center z-0"></div>
        
        <div class="container mx-auto px-4 relative z-20 text-center">
            <h1 class="text-4xl lg:text-6xl font-black mb-4 tracking-tight">
                {{ __('Réservez vos vols') }}
            </h1>
            <p class="text-xl text-gray-300 mb-8 max-w-2xl mx-auto font-light">
                {{ __('Découvrez le monde avec Carré Premium. Réservations sécurisées, meilleur prix garanti.') }}
            </p>

            <!-- Search Form Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 max-w-5xl mx-auto">
                <div class="flex items-center gap-2 mb-6">
                    <svg class="w-6 h-6 text-[#001F3F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <h2 class="text-xl font-bold text-[#001F3F]">{{ __('Rechercher un vol') }}</h2>
                </div>
                
                @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('flights.search') }}" class="space-y-5">
                    @csrf
                    <input type="hidden" name="type" value="2">
                    
                    <!-- Trip Type -->
                    <div class="flex gap-2">
                        <button type="button" onclick="setTripType(1, this)" 
                            class="flex-1 py-3 px-4 rounded-lg font-semibold trip-btn active bg-[#001F3F] text-white transition-all">
                            {{ __('Aller-Retour') }}
                        </button>
                        <button type="button" onclick="setTripType(2, this)" 
                            class="flex-1 py-3 px-4 rounded-lg font-semibold trip-btn bg-gray-100 text-[#001F3F] hover:bg-gray-200 transition-all">
                            {{ __('Aller simple') }}
                        </button>
                    </div>

                    <!-- Route -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Départ') }}</label>
                            <div class="relative">
                                <input type="text" name="departure_id" id="departure_id" autocomplete="off"
                                    class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] focus:border-[#001F3F] font-bold text-lg"
                                    placeholder="Ex: Abidjan, Paris, ABJ..." required>
                                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <div id="departure-suggestions" class="absolute z-50 w-full mt-1 bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden"></div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Arrivée') }}</label>
                            <div class="relative">
                                <input type="text" name="arrival_id" id="arrival_id" autocomplete="off"
                                    class="w-full px-4 py-4 pl-12 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] focus:border-[#001F3F] font-bold text-lg"
                                    placeholder="Ex: New York, Dubai, JFK..." required>
                                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                <div id="arrival-suggestions" class="absolute z-50 w-full mt-1 bg-white border-2 border-gray-200 rounded-xl shadow-xl max-h-60 overflow-y-auto hidden"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Date de départ') }}</label>
                            <input type="date" name="outbound_date" min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] focus:border-[#001F3F] font-semibold"
                                required>
                        </div>
                        <div id="return-date-container">
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Date de retour') }}</label>
                            <input type="date" name="return_date" id="return_date" min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] focus:border-[#001F3F] font-semibold">
                        </div>
                    </div>

                    <!-- Passengers & Class -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Adultes') }}</label>
                            <select name="adults" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] font-semibold bg-white">
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Enfants') }}</label>
                            <select name="children" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] font-semibold bg-white">
                                @for($i = 0; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Bébés') }}</label>
                            <select name="infants" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] font-semibold bg-white">
                                @for($i = 0; $i <= 4; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-[#001F3F] mb-2">{{ __('Classe') }}</label>
                            <select name="travel_class" class="w-full px-4 py-4 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#001F3F] font-semibold bg-white">
                                <option value="economy">{{ __('Économique') }}</option>
                                <option value="business">{{ __('Affaires') }}</option>
                                <option value="first">{{ __('Première') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <button type="submit" 
                        class="w-full bg-[#001F3F] text-white py-4 px-6 rounded-xl font-bold text-lg hover:bg-[#003366] transition-all flex items-center justify-center gap-3 shadow-lg hover:shadow-xl">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('Rechercher') }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Popular Destinations -->
    <div class="container mx-auto px-4 py-16">
        <h2 class="text-3xl font-black text-[#001F3F] mb-2">{{ __('Destinations populaires') }}</h2>
        <p class="text-gray-600 mb-8">{{ __('Explorez nos routes les plus recherchées') }}</p>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
                $popularRoutes = [
                    [
                        'from_city' => 'Abidjan',
                        'from_code' => 'ABJ',
                        'to_city' => 'Paris',
                        'to_code' => 'CDG',
                        'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?w=400&q=80',
                        'price' => '120 000 XOF'
                    ],
                    [
                        'from_city' => 'Paris',
                        'from_code' => 'CDG',
                        'to_city' => 'New York',
                        'to_code' => 'JFK',
                        'image' => 'https://images.unsplash.com/photo-1496442226666-8d4d0e62e6e9?w=400&q=80',
                        'price' => '150 000 XOF'
                    ],
                    [
                        'from_city' => 'Paris',
                        'from_code' => 'CDG',
                        'to_city' => 'Dubai',
                        'to_code' => 'DXB',
                        'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?w=400&q=80',
                        'price' => '180 000 XOF'
                    ],
                    [
                        'from_city' => 'Londres',
                        'from_code' => 'LHR',
                        'to_city' => 'New York',
                        'to_code' => 'JFK',
                        'image' => 'https://images.unsplash.com/photo-1485738422979-f5c462d49f74?w=400&q=80',
                        'price' => '140 000 XOF'
                    ],
                ];
            @endphp
            @foreach($popularRoutes as $route)
            <button type="button" onclick="quickSearch('{{ $route['from_code'] }}', '{{ $route['to_code'] }}')"
                class="group bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300 text-left">
                <div class="relative h-48 overflow-hidden">
                    <img src="{{ $route['image'] }}" alt="{{ $route['to_city'] }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                    <div class="absolute top-4 right-4 flex gap-2">
                        <span class="bg-white/90 backdrop-blur-sm text-[#001F3F] px-3 py-1 rounded-full text-xs font-bold">
                            {{ $route['from_code'] }}
                        </span>
                        <svg class="w-4 h-4 text-white mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                        <span class="bg-white/90 backdrop-blur-sm text-[#001F3F] px-3 py-1 rounded-full text-xs font-bold">
                            {{ $route['to_code'] }}
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="text-base font-bold text-[#001F3F]">{{ $route['from_city'] }}</h3>
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                        <h3 class="text-base font-bold text-[#001F3F]">{{ $route['to_city'] }}</h3>
                    </div>
                    <p class="text-gray-500 text-xs mb-3">Vol direct disponible</p>
                    <div class="flex items-baseline gap-1">
                        <span class="text-gray-500 text-xs">À partir de</span>
                        <p class="text-[#003366] font-bold text-lg">~{{ $route['price'] }}</p>
                    </div>
                </div>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Special Offers -->
    <div class="bg-gray-50 py-16">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-3xl font-black text-[#001F3F] mb-2">{{ __('Offres spéciales') }}</h2>
                    <p class="text-gray-600">{{ __('Profitez de nos tarifs préférentiels') }}</p>
                </div>
                <a href="{{ route('flights.search') }}" class="text-[#001F3F] font-semibold hover:underline flex items-center gap-2">
                    {{ __('Voir tout') }}
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $offers = [
                        ['badge' => '-20%', 'title' => 'Paris Weekend', 'desc' => 'Vol + Hébergement', 'price' => '299 €'],
                        ['badge' => '-15%', 'title' => 'Dubai Séjour', 'desc' => 'All Inclusive', 'price' => '449 €'],
                        ['badge' => '-25%', 'title' => 'New York', 'desc' => 'Billet + Transfert', 'price' => '389 €'],
                    ];
                @endphp
                @foreach($offers as $offer)
                <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-xl transition-shadow relative overflow-hidden">
                    <div class="absolute top-4 right-4 bg-[#28A745] text-white px-3 py-1 rounded-full font-bold text-sm">
                        {{ $offer['badge'] }}
                    </div>
                    <h3 class="text-xl font-bold text-[#001F3F] mb-2">{{ $offer['title'] }}</h3>
                    <p class="text-gray-500 text-sm mb-4">{{ $offer['desc'] }}</p>
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-gray-400 text-sm">{{ __('À partir de') }}</p>
                            <p class="text-2xl font-black text-[#001F3F]">{{ $offer['price'] }}</p>
                        </div>
                        <button class="bg-[#001F3F] text-white px-4 py-2 rounded-lg font-semibold hover:bg-[#003366] transition-colors">
                            {{ __('Réserver') }}
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Why Choose Us -->
    <div class="container mx-auto px-4 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-black text-[#001F3F] mb-4">{{ __('Pourquoi choisir Carré Premium ?') }}</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">{{ __('Nous vous offrons une expérience de réservation inégalée avec des services premium à chaque étape de votre voyage.') }}</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-[#001F3F]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#001F3F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#001F3F] mb-2">{{ __('Meilleur prix garanti') }}</h3>
                <p class="text-gray-500 text-sm">{{ __('Nous comparons les prix de toutes les compagnies pour vous offrir le meilleur tarif.') }}</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-[#001F3F]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#001F3F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#001F3F] mb-2">{{ __('Paiement sécurisé') }}</h3>
                <p class="text-gray-500 text-sm">{{ __('Transactions 100% sécurisées via CinetPay avec cryptage SSL.') }}</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-[#001F3F]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#001F3F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#001F3F] mb-2">{{ __('Support 24/7') }}</h3>
                <p class="text-gray-500 text-sm">{{ __('Notre équipe est disponible à tout moment pour vous accompagner.') }}</p>
            </div>
            
            <div class="text-center">
                <div class="w-16 h-16 bg-[#001F3F]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-[#001F3F]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-[#001F3F] mb-2">{{ __('Confirmation immédiate') }}</h3>
                <p class="text-gray-500 text-sm">{{ __('Recevez vos billets électroniques instantanément par email.') }}</p>
            </div>
        </div>
    </div>
</div>

<script>
let tripType = 1;
let searchTimeout = null;

function setTripType(type, btn) {
    tripType = type;
    document.querySelectorAll('.trip-btn').forEach(b => {
        b.classList.remove('bg-[#001F3F]', 'text-white');
        b.classList.add('bg-gray-100', 'text-[#001F3F]');
    });
    btn.classList.remove('bg-gray-100', 'text-[#001F3F]');
    btn.classList.add('bg-[#001F3F]', 'text-white');

    const returnContainer = document.getElementById('return-date-container');
    const returnInput = document.getElementById('return_date');

    if (type === 2) {
        returnContainer.style.display = 'none';
        returnInput.required = false;
    } else {
        returnContainer.style.display = 'block';
        returnInput.required = true;
    }
}

function quickSearch(from, to) {
    document.getElementById('departure_id').value = from;
    document.getElementById('arrival_id').value = to;
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Airport Autocomplete
function setupAutocomplete(inputId, suggestionsId) {
    const input = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    
    input.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            suggestions.classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            fetch(`/api/flights/airports/search?keyword=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success' && data.results.length > 0) {
                        displaySuggestions(data.results, suggestions, input);
                    } else {
                        suggestions.classList.add('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error fetching airports:', error);
                    suggestions.classList.add('hidden');
                });
        }, 300);
    });
    
    // Close suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.classList.add('hidden');
        }
    });
}

function displaySuggestions(results, container, input) {
    container.innerHTML = '';
    
    results.forEach(place => {
        const div = document.createElement('div');
        div.className = 'px-4 py-3 hover:bg-gray-100 cursor-pointer border-b border-gray-100 last:border-0';
        div.innerHTML = `
            <div class="flex items-center justify-between">
                <div>
                    <div class="font-bold text-[#001F3F]">${place.name || ''}</div>
                    <div class="text-sm text-gray-500">${place.city_name || ''}</div>
                </div>
                <div class="text-lg font-bold text-[#001F3F]">${place.iata_code || ''}</div>
            </div>
        `;
        
        div.addEventListener('click', function() {
            input.value = place.iata_code || '';
            container.classList.add('hidden');
        });
        
        container.appendChild(div);
    });
    
    container.classList.remove('hidden');
}

// Initialize autocomplete on page load
document.addEventListener('DOMContentLoaded', function() {
    setupAutocomplete('departure_id', 'departure-suggestions');
    setupAutocomplete('arrival_id', 'arrival-suggestions');
});
</script>
@endsection

