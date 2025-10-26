@extends('layouts.app')

@section('title', 'Détails du Vol - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            {{-- Bouton retour --}}
            <div class="mb-6">
                <a href="javascript:history.back()"
                    class="inline-flex items-center space-x-2 text-purple-600 hover:text-purple-700 font-semibold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <span>Retour aux résultats</span>
                </a>
            </div>

            {{-- Erreur --}}
            @if(isset($error) && $error)
                <div class="bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-xl p-6 mb-6">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                clip-rule="evenodd" />
                        </svg>
                        <div>
                            <h3 class="text-lg font-bold text-red-700 dark:text-red-400 mb-2">Erreur</h3>
                            <p class="text-red-700 dark:text-red-400">{{ $error }}</p>
                            <a href="{{ route('flights') }}"
                                class="inline-block mt-4 text-red-600 hover:text-red-700 font-semibold underline">
                                Retour à la recherche
                            </a>
                        </div>
                    </div>
                </div>
            @elseif(isset($selectedFlight))
                {{-- En-tête du vol --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-6">
                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Détails du Vol</h1>
                            <p class="text-gray-600 dark:text-gray-400">Personnalisez votre billet et réservez</p>
                        </div>
                        <div class="mt-4 lg:mt-0">
                            @if(isset($selectedFlight['price']) && $selectedFlight['price'] !== 'N/A')
                                <div class="text-4xl font-bold text-purple-600">{{ $selectedFlight['price'] }} €</div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-right">Prix total pour tous les passagers
                                </p>
                            @elseif(!empty($bookingOptions))
                                @php
                                    $minPrice = min(array_column($bookingOptions, 'price'));
                                @endphp
                                <div class="text-4xl font-bold text-purple-600">À partir de {{ $minPrice }} €</div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-right">Prix à partir de</p>
                            @elseif(isset($priceInsights['lowest_price']))
                                <div class="text-4xl font-bold text-purple-600">{{ $priceInsights['lowest_price'] }} €</div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-right">Prix estimé</p>
                            @else
                                <div class="text-2xl font-bold text-gray-600">Prix non disponible</div>
                                <p class="text-sm text-gray-500 dark:text-gray-400 text-right">Voir les options ci-dessous</p>
                            @endif
                        </div>
                    </div>

                    {{-- Informations du vol --}}
                    @if(!empty($selectedFlight['flights']))
                        <div class="space-y-6">
                            @foreach($selectedFlight['flights'] as $segmentIndex => $segment)
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-xl p-6 border-2 border-purple-100 dark:border-purple-800">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center space-x-3">
                                            @if(isset($segment['airline_logo']))
                                                <img src="{{ $segment['airline_logo'] }}" alt="{{ $segment['airline'] }}"
                                                    class="w-10 h-10 rounded">
                                            @endif
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                                    {{ $segment['airline'] ?? 'Compagnie aérienne' }}
                                                </h3>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    Vol {{ $segment['flight_number'] ?? 'N/A' }}
                                                    @if(isset($segment['airplane']))
                                                        • {{ $segment['airplane'] }}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        <span class="bg-purple-600 text-white px-4 py-2 rounded-full text-sm font-semibold">
                                            Segment {{ $segmentIndex + 1 }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        {{-- Départ --}}
                                        <div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Départ</div>
                                            <div class="font-bold text-2xl text-gray-900 dark:text-white">
                                                {{ isset($segment['departure_airport']['time']) ? \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') : 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ isset($segment['departure_airport']['time']) ? \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d/m/Y') : '' }}
                                            </div>
                                            <div class="font-semibold text-gray-900 dark:text-white mt-2">
                                                {{ $segment['departure_airport']['name'] ?? 'Aéroport de départ' }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $segment['departure_airport']['id'] ?? '' }}
                                            </div>
                                        </div>

                                        {{-- Durée --}}
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-purple-600 mb-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                            <div class="text-lg font-bold text-gray-900 dark:text-white">
                                                {{ $segment['duration'] ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Durée du vol</div>
                                        </div>

                                        {{-- Arrivée --}}
                                        <div class="text-right">
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Arrivée</div>
                                            <div class="font-bold text-2xl text-gray-900 dark:text-white">
                                                {{ isset($segment['arrival_airport']['time']) ? \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') : 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                {{ isset($segment['arrival_airport']['time']) ? \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d/m/Y') : '' }}
                                            </div>
                                            <div class="font-semibold text-gray-900 dark:text-white mt-2">
                                                {{ $segment['arrival_airport']['name'] ?? 'Aéroport d\'arrivée' }}
                                            </div>
                                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $segment['arrival_airport']['id'] ?? '' }}
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Extensions --}}
                                    @if(isset($segment['extensions']) && !empty($segment['extensions']))
                                        <div class="mt-4 flex flex-wrap gap-2">
                                            @foreach($segment['extensions'] as $extension)
                                                <span
                                                    class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full text-sm">
                                                    {{ $extension }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>

                                {{-- Escale --}}
                                @if(isset($selectedFlight['layovers'][$segmentIndex]))
                                    @php
                                        $layover = $selectedFlight['layovers'][$segmentIndex];
                                    @endphp
                                    <div class="flex items-center justify-center my-4">
                                        <div
                                            class="bg-amber-100 dark:bg-amber-900/30 border-2 border-amber-300 dark:border-amber-700 rounded-xl px-6 py-3">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                <div>
                                                    <div class="font-bold text-amber-900 dark:text-amber-200">
                                                        Escale à {{ $layover['name'] ?? 'Aéroport' }}
                                                    </div>
                                                    <div class="text-sm text-amber-700 dark:text-amber-300">
                                                        Durée: {{ $layover['duration'] ?? 'N/A' }}
                                                        @if(isset($layover['overnight']) && $layover['overnight'])
                                                            <span
                                                                class="ml-2 bg-amber-200 dark:bg-amber-800 px-2 py-0.5 rounded text-xs">Nuit</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Options de bagages --}}
                @if(!empty($baggagePrices))
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8 mb-6">
                        <div class="flex items-center space-x-3 mb-6">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Options de Bagages</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($baggagePrices as $bag)
                                <div
                                    class="bg-gradient-to-br from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-xl p-4 border border-purple-200 dark:border-purple-700">
                                    <div class="font-bold text-gray-900 dark:text-white mb-2">{{ $bag['type'] ?? 'Bagage' }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $bag['definition'] ?? '' }}</div>
                                    <div class="text-xl font-bold text-purple-600">
                                        {{ $bag['price'] ?? 'Voir sur le site' }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                            Les prix finaux des bagages seront confirmés sur le site du revendeur.
                        </p>
                    </div>
                @endif

                {{-- Options de réservation --}}
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Finaliser la Réservation</h2>
                    </div>

                    @if(!empty($bookingOptions))
                        <div class="space-y-4">
                            @foreach($bookingOptions as $option)
                                <div
                                    class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-6 border-2 border-green-200 dark:border-green-800 hover:shadow-lg transition-shadow">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="mb-4 md:mb-0">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                                                {{ $option['name'] ?? 'Revendeur' }}
                                            </h3>
                                            <div class="text-3xl font-bold text-green-600">
                                                {{ $option['price'] ?? 'N/A' }} €
                                            </div>
                                            @if(isset($option['rate']))
                                                <div class="flex items-center mt-2 space-x-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-5 h-5 {{ $i <= $option['rate'] ? 'text-yellow-400' : 'text-gray-300' }}"
                                                            fill="currentColor" viewBox="0 0 20 20">
                                                            <path
                                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                        </svg>
                                                    @endfor
                                                </div>
                                            @endif
                                        </div>
                                        <a href="{{ $option['link'] ?? '#' }}" target="_blank"
                                            class="inline-flex items-center justify-center space-x-2 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-bold text-lg px-8 py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                            <span>Réserver sur {{ $option['name'] ?? 'le site' }}</span>
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 rounded-xl p-6">
                            <div class="flex items-start space-x-3">
                                <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                                <div>
                                    <h3 class="text-lg font-bold text-yellow-800 dark:text-yellow-400 mb-1">Options non disponibles
                                    </h3>
                                    <p class="text-yellow-700 dark:text-yellow-300">
                                        Désolé, aucune option de réservation n'est disponible pour ce vol pour le moment. Veuillez
                                        réessayer plus tard.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
@endsection