@extends('layouts.app')

@section('title', __('Réservation de vols') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Hero Header -->
    <div class="bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white py-12 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-64 h-64 bg-white rounded-full filter blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500 rounded-full filter blur-3xl"></div>
        </div>
        
        <div class="container mx-auto px-4 text-center relative z-10">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-white/10 rounded-full mb-4">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                </svg>
            </div>
            <h1 class="text-3xl md:text-4xl font-bold mb-2">{{ __('Réservez vos vols') }}</h1>
            <p class="text-gray-300 text-lg">{{ __('Recherchez et réservez vos billets au meilleur prix') }}</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8 -mt-6 relative z-20">
        <!-- Search Form Card -->
        <div class="bg-white rounded-xl shadow-xl border border-gray-200 p-6 md:p-8 max-w-4xl mx-auto">
            <div class="flex items-center gap-2 mb-4">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">{{ __('Formulaire de recherche') }}</h2>
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
                        class="flex-1 py-3 px-4 rounded-lg font-medium trip-btn active bg-gray-900 text-white transition-all">
                        {{ __('Aller-Retour') }}
                    </button>
                    <button type="button" onclick="setTripType(2, this)" 
                        class="flex-1 py-3 px-4 rounded-lg font-medium trip-btn bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all">
                        {{ __('Aller simple') }}
                    </button>
                </div>

                <!-- Route -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Départ') }}</label>
                        <div class="relative">
                            <input type="text" name="departure_id" id="departure_id"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent uppercase font-semibold"
                                placeholder="CDG" required maxlength="3" pattern="[A-Z]{3}">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Code IATA 3 lettres (ex: CDG, JFK, ABJ)</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Arrivée') }}</label>
                        <div class="relative">
                            <input type="text" name="arrival_id" id="arrival_id"
                                class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent uppercase font-semibold"
                                placeholder="JFK" required maxlength="3" pattern="[A-Z]{3}">
                            <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            </svg>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">Code IATA 3 lettres</p>
                    </div>
                </div>

                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date de départ') }}</label>
                        <input type="date" name="outbound_date" min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent"
                            required>
                    </div>
                    <div id="return-date-container">
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Date de retour') }}</label>
                        <input type="date" name="return_date" id="return_date" min="{{ date('Y-m-d') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent">
                    </div>
                </div>

                <!-- Passengers & Class -->
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Adultes') }}</label>
                        <div class="relative">
                            <select name="adults" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 appearance-none bg-white">
                                @for($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Classe') }}</label>
                        <div class="relative">
                            <select name="travel_class" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 appearance-none bg-white">
                                <option value="economy">{{ __('Économique') }}</option>
                                <option value="business">{{ __('Affaires') }}</option>
                                <option value="first">{{ __('Première') }}</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">{{ __('Devise') }}</label>
                        <div class="relative">
                            <select name="currency" class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 appearance-none bg-white">
                                <option value="XOF">XOF (CFA)</option>
                                <option value="EUR">EUR (Euro)</option>
                                <option value="USD">USD (Dollar)</option>
                            </select>
                            <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Search Button -->
                <button type="submit" 
                    class="w-full bg-gray-900 text-white py-4 px-6 rounded-lg font-semibold text-lg hover:bg-gray-800 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    {{ __('Rechercher') }}
                </button>
            </form>
        </div>

        <!-- Popular Routes -->
        <div class="mt-12 max-w-4xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                {{ __('Destinations populaires') }}
            </h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @php
                    $routes = [
                        ['from' => 'CDG', 'to' => 'JFK', 'price' => '~150 000 XOF', 'city_from' => 'Paris', 'city_to' => 'New York'],
                        ['from' => 'CDG', 'to' => 'DXB', 'price' => '~180 000 XOF', 'city_from' => 'Paris', 'city_to' => 'Dubai'],
                        ['from' => 'ABJ', 'to' => 'CDG', 'price' => '~120 000 XOF', 'city_from' => 'Abidjan', 'city_to' => 'Paris'],
                        ['from' => 'LHR', 'to' => 'JFK', 'price' => '~140 000 XOF', 'city_from' => 'London', 'city_to' => 'New York'],
                    ];
                @endphp
                @foreach($routes as $route)
                <button type="button" onclick="quickSearch('{{ $route['from'] }}', '{{ $route['to'] }}')"
                    class="bg-white rounded-xl border border-gray-200 p-4 hover:shadow-lg hover:border-gray-300 transition-all text-left group">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <span class="bg-gray-100 px-2 py-1 rounded font-bold text-gray-900">{{ $route['from'] }}</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                            <span class="bg-gray-100 px-2 py-1 rounded font-bold text-gray-900">{{ $route['to'] }}</span>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500">{{ $route['city_from'] }} → {{ $route['city_to'] }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $route['price'] }}</p>
                </button>
                @endforeach
            </div>
        </div>

        <!-- Features -->
        <div class="mt-12 max-w-4xl mx-auto">
            <h2 class="text-xl font-semibold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                {{ __('Pourquoi nous choisir') }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Meilleur prix garanti') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Nous comparons les prix pour vous offrir le meilleur tarif') }}</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Paiement sécurisé') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Transactions sécurisées via CinetPay') }}</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Support 24/7') }}</h3>
                    <p class="text-sm text-gray-500">{{ __('Notre équipe est disponible à tout moment') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let tripType = 1;

function setTripType(type, btn) {
    tripType = type;
    document.querySelectorAll('.trip-btn').forEach(b => {
        b.classList.remove('bg-gray-900', 'text-white');
        b.classList.add('bg-gray-100', 'text-gray-700');
    });
    btn.classList.remove('bg-gray-100', 'text-gray-700');
    btn.classList.add('bg-gray-900', 'text-white');

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

// Auto-uppercase pour les codes aéroport
document.getElementById('departure_id').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase();
});
document.getElementById('arrival_id').addEventListener('input', function(e) {
    this.value = this.value.toUpperCase();
});
</script>
@endsection

