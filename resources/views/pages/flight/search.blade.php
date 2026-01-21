@extends('layouts.app')

@section('title', __('Résultats de recherche avancés') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header -->
    <div class="bg-[#001F3F] text-white py-6">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">{{ __('Résultats de recherche') }}</h1>
                    <p class="text-gray-300 mt-1">
                        {{ $search['departure_id'] ?? '' }} → {{ $search['arrival_id'] ?? '' }}
                        @if(isset($search['outbound_date']))
                            | {{\Carbon\Carbon::parse($search['outbound_date'])->locale(app()->getLocale())->isoFormat('D MMM YYYY')}}
                        @endif
                        @if(isset($search['return_date']) && $search['return_date'])
                            - {{\Carbon\Carbon::parse($search['return_date'])->locale(app()->getLocale())->isoFormat('D MMM YYYY')}}
                        @endif
                    </p>
                </div>
                <a href="{{ route('flights') }}" class="bg-white/10 hover:bg-white/20 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                    ← {{ __('Nouvelle recherche') }}
                </a>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6">
        <!-- API Status Notices -->
        @if(isset($api_error) && $api_error)
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                    <div>
                        <p class="text-amber-800 font-medium">{{ __('Erreur API Duffel') }}</p>
                        <p class="text-amber-700 text-sm mt-1">{{ $error_message ?? __('L\'API Duffel a rencontré une erreur. Les données affichées sont des données de démonstration.') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($search_info['mock_data']) && $search_info['mock_data'])
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-blue-800 font-medium">{{ __('Mode démonstration') }}</p>
                        <p class="text-blue-700 text-sm mt-1">{{ $search_info['message'] ?? __('Les vols affichés sont des données de démonstration pour illustrer les fonctionnalités.') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Search Summary & Controls -->
        @if(!empty($flights))
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <!-- Results Summary -->
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-semibold text-gray-900">{{ count($flights) }} {{ __('vols trouvés') }}</span>
                        </div>

                        @if(isset($commission_rate))
                            <div class="flex items-center gap-2">
                                <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Commission {{ $commission_rate }}% incluse
                                </span>
                            </div>
                        @endif

                        @if(isset($api_version) && $api_version === 'v2')
                            <div class="flex items-center gap-2">
                                <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-xs font-medium">
                                    Duffel API v2
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Sort & View Controls -->
                    <div class="flex items-center gap-3">
                        <select id="sort-select" class="px-3 py-2 border border-gray-300 rounded-lg text-sm bg-white">
                            <option value="price">{{ __('Prix croissant') }}</option>
                            <option value="duration">{{ __('Durée') }}</option>
                            <option value="departure">{{ __('Heure de départ') }}</option>
                            <option value="arrival">{{ __('Heure d\'arrivée') }}</option>
                        </select>

                        <div class="flex items-center gap-2">
                            <button id="list-view" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                            </button>
                            <button id="grid-view" class="p-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="lg:grid lg:grid-cols-4 lg:gap-6">
                <!-- Advanced Filters Sidebar -->
                <div class="lg:col-span-1 mb-6 lg:mb-0">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 sticky top-6">
                        <div class="flex items-center gap-2 mb-6">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <h3 class="font-semibold text-gray-900">{{ __('Filtres avancés') }}</h3>
                        </div>

                        <!-- Airlines Filter -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                {{ __('Compagnies aériennes') }}
                            </h4>
                            <div class="space-y-2 max-h-40 overflow-y-auto">
                                @php
                                    $airlines = array_unique(array_column($flights, 'airline_name'));
                                    sort($airlines);
                                @endphp
                                @foreach($airlines as $airline)
                                    <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                        <input type="checkbox" class="airline-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="{{ $airline }}">
                                        <span class="ml-2 text-sm text-gray-700">{{ $airline }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <!-- Stops Filter -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                {{ __('Nombre d\'escales') }}
                            </h4>
                            <div class="space-y-2">
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="stops-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="0" checked>
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Vols directs') }}</span>
                                </label>
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="stops-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="1">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('1 escale') }}</span>
                                </label>
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="stops-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="2">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('2+ escales') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Class Filter -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                </svg>
                                {{ __('Classe') }}
                            </h4>
                            <div class="space-y-2">
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="class-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="economy" checked>
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Économique') }}</span>
                                </label>
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="class-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="business">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Affaires') }}</span>
                                </label>
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="class-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="first">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Première') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Price Range Filter -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                {{ __('Prix par personne') }}
                            </h4>
                            @php
                                $prices = array_column($flights, 'price_with_commission');
                                $minPrice = min($prices);
                                $maxPrice = max($prices);
                            @endphp
                            <div class="px-2">
                                <div class="flex justify-between text-xs text-gray-500 mb-2">
                                    <span>{{ number_format($minPrice, 0, ',', ' ') }} XOF</span>
                                    <span>{{ number_format($maxPrice, 0, ',', ' ') }} XOF</span>
                                </div>
                                <input type="range" id="price-range" min="{{ $minPrice }}" max="{{ $maxPrice }}" value="{{ $maxPrice }}"
                                       class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer slider">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span id="price-min">{{ number_format($minPrice, 0, ',', ' ') }}</span>
                                    <span id="price-max">{{ number_format($maxPrice, 0, ',', ' ') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Time Filters -->
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-3 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('Heures de départ') }}
                            </h4>
                            <div class="space-y-2">
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="time-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="morning">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Matin (06h-12h)') }}</span>
                                </label>
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="time-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="afternoon">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Après-midi (12h-18h)') }}</span>
                                </label>
                                <label class="flex items-center hover:bg-gray-50 px-2 py-1 rounded cursor-pointer">
                                    <input type="checkbox" class="time-filter rounded border-gray-300 text-blue-600 focus:ring-blue-500" value="evening">
                                    <span class="ml-2 text-sm text-gray-700">{{ __('Soir (18h-24h)') }}</span>
                                </label>
                            </div>
                        </div>

                        <!-- Clear Filters Button -->
                        <button type="button" id="clear-filters"
                                class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            {{ __('Effacer les filtres') }}
                        </button>
                    </div>
                </div>

                <!-- Results -->
                <div class="lg:col-span-3">
                    <!-- Flights List -->
                    <div id="flights-container" class="space-y-4">
                @foreach($flights as $index => $flight)
                    @php
                        $offerId = $flight['offer_id'] ?? $flight['duffel_offer_id'] ?? '';
                        $totalPrice = $flight['price_with_commission'] ?? $flight['total_price'] ?? 0;
                        $basePrice = $flight['base_price'] ?? $totalPrice;
                        $commissionAmount = $flight['commission_amount'] ?? 0;
                        $isDirect = ($flight['stops'] ?? 0) == 0;
                        $cabinClass = strtolower($flight['cabin_class'] ?? 'economy');
                        $cabinClassLabel = [
                            'economy' => 'Économique',
                            'business' => 'Affaires',
                            'first' => 'Première'
                        ][$cabinClass] ?? 'Économique';

                        // Time categorization for filtering
                        $departureHour = isset($flight['departure']['time']) ?
                            (int)date('H', strtotime($flight['departure']['time'])) : 12;
                        $timeCategory = $departureHour >= 6 && $departureHour < 12 ? 'morning' :
                                       $departureHour >= 12 && $departureHour < 18 ? 'afternoon' : 'evening';
                    @endphp
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden hover:shadow-lg transition-all duration-300 flight-card"
                         data-airline="{{ $flight['airline_name'] ?? '' }}"
                         data-stops="{{ $flight['stops'] ?? 0 }}"
                         data-class="{{ $cabinClass }}"
                         data-price="{{ $totalPrice }}"
                         data-time="{{ $timeCategory }}"
                         data-duration="{{ $flight['duration_minutes'] ?? 0 }}"
                         data-departure="{{ $departureHour }}">
                        <div class="flex flex-col lg:flex-row">
                            <!-- Flight Info -->
                            <div class="flex-1 p-6">
                                <div class="flex items-start gap-4">
                                    <!-- Airline Logo & Info -->
                                    <div class="w-14 h-14 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-blue-200">
                                        <span class="text-2xl">{{ $flight['airline_logo'] ?? '✈️' }}</span>
                                    </div>

                                    <div class="flex-1">
                                        <!-- Airline & Flight Number -->
                                        <div class="flex items-center gap-3 mb-4">
                                            <span class="font-bold text-gray-900 text-lg">{{ $flight['airline_name'] ?? 'Airline' }}</span>
                                            <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $flight['flight_number'] ?? 'N/A' }}</span>
                                            @if(isset($flight['offer_expires_at']))
                                                <span class="text-xs text-amber-600 bg-amber-50 px-2 py-1 rounded flex items-center gap-1">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    Expire bientôt
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Route & Times -->
                                        <div class="grid grid-cols-3 gap-4 mb-4">
                                            <!-- Departure -->
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-gray-900">{{ $flight['departure']['formatted_time'] ?? 'N/A' }}</div>
                                                <div class="text-sm font-medium text-gray-700">{{ $flight['departure']['airport'] ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $flight['departure']['formatted_date'] ?? '' }}</div>
                                            </div>

                                            <!-- Duration & Stops -->
                                            <div class="text-center flex flex-col items-center justify-center">
                                                <div class="text-sm text-gray-600 mb-1">{{ $flight['duration_formatted'] ?? $flight['duration'] ?? 'N/A' }}</div>
                                                <div class="relative h-px bg-gray-300 w-full mb-2">
                                                    <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-white px-2">
                                                        <span class="text-xs text-gray-500 whitespace-nowrap">
                                                            @if($isDirect)
                                                                ✈️ {{ __('Direct') }}
                                                            @else
                                                                {{ $flight['stops'] }} {{ __('escale(s)') }}
                                                            @endif
                                                        </span>
                                                    </div>
                                                </div>
                                                @if(!$isDirect && isset($flight['layovers']) && !empty($flight['layovers']))
                                                    <div class="text-xs text-gray-500">
                                                        @foreach($flight['layovers'] as $layover)
                                                            {{ $layover['name'] }} ({{ $layover['duration_formatted'] }})
                                                            @if(!$loop->last), @endif
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Arrival -->
                                            <div class="text-center">
                                                <div class="text-2xl font-bold text-gray-900">{{ $flight['arrival']['formatted_time'] ?? 'N/A' }}</div>
                                                <div class="text-sm font-medium text-gray-700">{{ $flight['arrival']['airport'] ?? 'N/A' }}</div>
                                                <div class="text-xs text-gray-500">{{ $flight['arrival']['formatted_date'] ?? '' }}</div>
                                            </div>
                                        </div>

                                        <!-- Aircraft & Class Info -->
                                        <div class="flex items-center gap-4 mb-4">
                                            @if(isset($flight['aircraft']))
                                                <div class="flex items-center gap-2 text-sm text-gray-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ $flight['aircraft'] }}
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                                                </svg>
                                                {{ $cabinClassLabel }}
                                            </div>
                                        </div>

                                        <!-- Conditions & Features -->
                                        <div class="flex flex-wrap gap-2">
                                            {{-- Duffel v2 Conditions Badges --}}
                                            @if(isset($flight['conditions']))
                                                {{-- Changeable Badge --}}
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                    {{ ($flight['conditions']['changeable'] ?? false) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600' }}">
                                                    @if(($flight['conditions']['changeable'] ?? false))
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                    @endif
                                                    {{ ($flight['conditions']['changeable'] ?? false) ? __('Modifiable') : __('Non modifiable') }}
                                                </span>

                                                {{-- Refundable Badge --}}
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                    {{ ($flight['conditions']['refundable'] ?? false) ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-600' }}">
                                                    @if(($flight['conditions']['refundable'] ?? false))
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                        </svg>
                                                    @endif
                                                    {{ ($flight['conditions']['refundable'] ?? false) ? __('Remboursable') : __('Non remboursable') }}
                                                </span>

                                                {{-- Airline Initiated Change Supported Badge --}}
                                                @if(($flight['conditions']['airline_initiated_change_supported'] ?? false))
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700" title="{{ __('Les changements initiés par la compagnie aérienne sont pris en charge') }}">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                                        </svg>
                                                        {{ __('Protection vols') }}
                                                    </span>
                                                @endif
                                            @endif

                                            {{-- Seats Available --}}
                                            @if(isset($flight['seats_available']))
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                                    {{ $flight['seats_available'] <= 3 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                                    </svg>
                                                    {{ $flight['seats_available'] }} {{ __('places') }}
                                                </span>
                                            @endif

                                            {{-- API Version Badge --}}
                                            @if(isset($api_version) && $api_version === 'v2')
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-700">
                                                    Duffel v2
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Price & Action -->
                            <div class="lg:w-80 bg-gradient-to-br from-gray-50 to-blue-50 p-6 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-gray-100">
                                <div class="text-center mb-4">
                                    <div class="text-3xl font-bold text-gray-900 mb-1">
                                        {{ number_format($totalPrice, 0, ',', ' ') }}
                                        <span class="text-lg font-normal text-gray-600">XOF</span>
                                    </div>
                                    <div class="text-sm text-gray-500">{{ __('par personne') }}</div>

                                    @if($commissionAmount > 0)
                                        <div class="mt-2 text-sm text-green-600 font-medium flex items-center justify-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                            </svg>
                                            -{{ number_format($commissionAmount, 0, ',', ' ') }} XOF commission
                                        </div>
                                    @endif
                                </div>

                                <div class="space-y-3">
                                    <!-- Quick Actions -->
                                    <div class="flex gap-2">
                                        <button class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-3 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors flex items-center justify-center gap-1"
                                                onclick="showFlightDetails({{ $index }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ __('Détails') }}
                                        </button>
                                        <button class="flex-1 bg-blue-50 border border-blue-200 text-blue-700 py-2 px-3 rounded-lg text-sm font-medium hover:bg-blue-100 transition-colors flex items-center justify-center gap-1"
                                                onclick="shareFlight({{ $index }})">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"/>
                                            </svg>
                                            {{ __('Partager') }}
                                        </button>
                                    </div>

                                    <!-- Select Button -->
                                    <form action="{{ route('flights.select') }}" method="POST" class="w-full">
                                        @csrf
                                        <input type="hidden" name="offer_id" value="{{ $offerId }}">
                                        <input type="hidden" name="flight_number" value="{{ $flight['flight_number'] ?? '' }}">
                                        <input type="hidden" name="airline" value="{{ $flight['airline_name'] ?? '' }}">
                                        <input type="hidden" name="departure_airport" value="{{ $flight['departure']['airport'] ?? '' }}">
                                        <input type="hidden" name="arrival_airport" value="{{ $flight['arrival']['airport'] ?? '' }}">
                                        <input type="hidden" name="departure_time" value="{{ $flight['departure']['time'] ?? '' }}">
                                        <input type="hidden" name="arrival_time" value="{{ $flight['arrival']['time'] ?? '' }}">
                                        <input type="hidden" name="departure_date" value="{{ $search['outbound_date'] ?? '' }}">
                                        <input type="hidden" name="cabin_class" value="{{ $flight['cabin_class'] ?? 'economy' }}">
                                        <input type="hidden" name="total_price" value="{{ $totalPrice }}">
                                        <input type="hidden" name="base_price" value="{{ $basePrice }}">
                                        <input type="hidden" name="commission_amount" value="{{ $commissionAmount }}">
                                        <input type="hidden" name="duration" value="{{ $flight['duration_formatted'] ?? '' }}">
                                        <input type="hidden" name="stops" value="{{ $flight['stops'] ?? 0 }}">

                                        <button type="submit"
                                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg text-sm font-semibold hover:bg-blue-700 transition-colors shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                            {{ __('Sélectionner ce vol') }}
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                    </div>

                    <!-- Load More Button -->
                    @if(count($flights) >= 20)
                        <div class="text-center mt-8">
                            <button id="load-more" class="bg-white border border-gray-300 text-gray-700 py-3 px-6 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                                {{ __('Charger plus de vols') }}
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Price Information -->
            <div class="mt-8 bg-blue-50 rounded-xl p-6 border border-blue-200">
                <div class="flex items-start gap-4">
                    <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-lg font-semibold text-blue-900 mb-2">{{ __('Informations sur les prix') }}</h3>
                        <div class="text-blue-800 text-sm space-y-1">
                            <p>• {{ __('Les prix affichés incluent notre commission de service de') }} <strong>{{ $commission_rate ?? 15 }}%</strong></p>
                            <p>• {{ __('Le tarif de base correspond au prix de la compagnie aérienne') }}</p>
                            <p>• {{ __('Les prix sont garantis jusqu\'à la confirmation de réservation') }}</p>
                            <p>• {{ __('Taxes et frais d\'aéroport inclus dans le prix final') }}</p>
                        </div>
                    </div>
                </div>
            </div>

        @else
            <!-- No Results -->
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                <div class="text-6xl mb-6">🔍</div>
                <h3 class="text-2xl font-bold text-gray-900 mb-4">{{ __('Aucun vol trouvé') }}</h3>
                <p class="text-gray-600 mb-8 max-w-md mx-auto">{{ __('Essayez d\'autres dates, aéroports ou modifiez vos critères de recherche.') }}</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('flights') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('Nouvelle recherche') }}
                    </a>
                    <button onclick="showAdvancedSearch()" class="inline-flex items-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                        </svg>
                        {{ __('Recherche avancée') }}
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Flight Details Modal -->
<div id="flight-details-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold text-gray-900">{{ __('Détails du vol') }}</h3>
                    <button onclick="closeFlightDetails()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="flight-details-content" class="p-6">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<script>
// Enhanced filtering and sorting system
document.addEventListener('DOMContentLoaded', function() {
    const flightsContainer = document.getElementById('flights-container');
    const flightCards = flightsContainer.querySelectorAll('.flight-card');
    const sortSelect = document.getElementById('sort-select');
    const priceRange = document.getElementById('price-range');
    const priceMin = document.getElementById('price-min');
    const priceMax = document.getElementById('price-max');
    const clearFiltersBtn = document.getElementById('clear-filters');

    let currentSort = 'price';
    let visibleCards = Array.from(flightCards);

    // Enhanced filtering function
    function filterFlights() {
        const selectedAirlines = Array.from(document.querySelectorAll('.airline-filter:checked')).map(cb => cb.value);
        const selectedStops = Array.from(document.querySelectorAll('.stops-filter:checked')).map(cb => parseInt(cb.value));
        const selectedClasses = Array.from(document.querySelectorAll('.class-filter:checked')).map(cb => cb.value);
        const selectedTimes = Array.from(document.querySelectorAll('.time-filter:checked')).map(cb => cb.value);
        const maxPrice = parseInt(priceRange.value);

        visibleCards = Array.from(flightCards).filter(card => {
            const airline = card.dataset.airline;
            const stops = parseInt(card.dataset.stops);
            const flightClass = card.dataset.class;
            const price = parseInt(card.dataset.price);
            const time = card.dataset.time;

            const airlineMatch = selectedAirlines.length === 0 || selectedAirlines.includes(airline);
            const stopsMatch = selectedStops.length === 0 || selectedStops.includes(stops);
            const classMatch = selectedClasses.length === 0 || selectedClasses.includes(flightClass);
            const timeMatch = selectedTimes.length === 0 || selectedTimes.includes(time);
            const priceMatch = price <= maxPrice;

            return airlineMatch && stopsMatch && classMatch && timeMatch && priceMatch;
        });

        updateDisplay();
        updateResultsCount();
    }

    // Sorting function
    function sortFlights(sortBy) {
        currentSort = sortBy;

        visibleCards.sort((a, b) => {
            let aVal, bVal;

            switch(sortBy) {
                case 'price':
                    aVal = parseInt(a.dataset.price);
                    bVal = parseInt(b.dataset.price);
                    break;
                case 'duration':
                    aVal = parseInt(a.dataset.duration);
                    bVal = parseInt(b.dataset.duration);
                    break;
                case 'departure':
                    aVal = parseInt(a.dataset.departure);
                    bVal = parseInt(b.dataset.departure);
                    break;
                case 'arrival':
                    // This would need additional data attributes
                    aVal = 0;
                    bVal = 0;
                    break;
                default:
                    return 0;
            }

            return aVal - bVal;
        });

        updateDisplay();
    }

    // Update display after filtering/sorting
    function updateDisplay() {
        // Hide all cards first
        flightCards.forEach(card => card.style.display = 'none');

        // Show only visible cards in sorted order
        visibleCards.forEach(card => {
            card.style.display = 'block';
            flightsContainer.appendChild(card);
        });
    }

    // Update results count
    function updateResultsCount() {
        const count = visibleCards.length;
        const resultsText = document.querySelector('.text-gray-900');
        if (resultsText) {
            resultsText.textContent = `${count} vols trouvés`;
        }
    }

    // Price range slider
    if (priceRange) {
        priceRange.addEventListener('input', function() {
            const value = parseInt(this.value);
            priceMax.textContent = value.toLocaleString('fr-FR') + ' XOF';
            filterFlights();
        });
    }

    // Sort select
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortFlights(this.value);
        });
    }

    // Filter checkboxes
    document.querySelectorAll('.airline-filter, .stops-filter, .class-filter, .time-filter').forEach(cb => {
        cb.addEventListener('change', filterFlights);
    });

    // Clear filters
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            document.querySelectorAll('.airline-filter, .stops-filter, .class-filter, .time-filter').forEach(cb => {
                cb.checked = cb.value === '0' || cb.value === 'economy'; // Keep some defaults
            });

            if (priceRange) {
                priceRange.value = priceRange.max;
                priceMax.textContent = parseInt(priceRange.max).toLocaleString('fr-FR') + ' XOF';
            }

            filterFlights();
        });
    }

    // View toggle (list/grid)
    document.getElementById('list-view')?.addEventListener('click', function() {
        this.classList.add('bg-gray-200');
        document.getElementById('grid-view')?.classList.remove('bg-gray-200');
        // Implement list view logic
    });

    document.getElementById('grid-view')?.addEventListener('click', function() {
        this.classList.add('bg-gray-200');
        document.getElementById('list-view')?.classList.remove('bg-gray-200');
        // Implement grid view logic
    });

    // Load more functionality
    document.getElementById('load-more')?.addEventListener('click', function() {
        // Implement load more logic
        this.textContent = 'Chargement...';
        setTimeout(() => {
            this.textContent = 'Charger plus de vols';
        }, 2000);
    });
});

// Flight details modal functions
function showFlightDetails(flightIndex) {
    const modal = document.getElementById('flight-details-modal');
    const content = document.getElementById('flight-details-content');

    // Load flight details (this would be an AJAX call in production)
    content.innerHTML = `
        <div class="text-center py-8">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Chargement des détails...</p>
        </div>
    `;

    modal.classList.remove('hidden');

    // Simulate loading
    setTimeout(() => {
        content.innerHTML = `
            <div class="space-y-6">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-900 mb-2">Informations générales</h4>
                    <p class="text-blue-800 text-sm">Détails complets du vol #${flightIndex + 1}</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h5 class="font-medium mb-2">Conditions de modification</h5>
                        <p class="text-sm text-gray-600">Modifications autorisées jusqu'à 24h avant le départ</p>
                    </div>
                    <div>
                        <h5 class="font-medium mb-2">Conditions d'annulation</h5>
                        <p class="text-sm text-gray-600">Remboursement partiel selon les conditions</p>
                    </div>
                </div>
            </div>
        `;
    }, 1000);
}

function closeFlightDetails() {
    document.getElementById('flight-details-modal').classList.add('hidden');
}

function shareFlight(flightIndex) {
    // Implement share functionality
    if (navigator.share) {
        navigator.share({
            title: 'Vol trouvé sur Carré Premium',
            text: 'Découvrez ce vol incroyable!',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        // Show success message
    }
}

function showAdvancedSearch() {
    // Implement advanced search modal or redirect
    window.location.href = '{{ route("flights") }}';
}

// Close modal when clicking outside
document.getElementById('flight-details-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFlightDetails();
    }
});
</script>

<style>
.slider::-webkit-slider-thumb {
    appearance: none;
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #2563eb;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 0 2px rgba(0,0,0,0.3);
}

.slider::-moz-range-thumb {
    height: 20px;
    width: 20px;
    border-radius: 50%;
    background: #2563eb;
    cursor: pointer;
    border: 2px solid #ffffff;
    box-shadow: 0 0 2px rgba(0,0,0,0.3);
}
</style>
@endsection
