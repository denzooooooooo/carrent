@extends('layouts.app')

@section('title', 'Détails du vol - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            {{-- Bouton retour --}}
            <div class="mb-6">
                <button onclick="window.history.back()"
                    class="flex items-center text-purple-600 hover:text-purple-700 font-medium">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Retour aux résultats
                </button>
            </div>

            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Détails du vol</h1>

            {{-- Informations sur les vols sélectionnés --}}
            @if(!empty($selectedFlights))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Itinéraire sélectionné</h2>

                    @foreach($selectedFlights as $index => $flightGroup)
                        <div class="mb-6 @if($index > 0) pt-6 border-t border-gray-200 dark:border-gray-700 @endif">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $index == 0 ? 'Vol aller' : 'Vol retour' }}
                                </h3>
                                <div class="text-sm text-gray-500 dark:text-gray-400">
                                    Durée totale: {{ $flightGroup['total_duration'] ?? '' }}
                                </div>
                            </div>

                            @foreach($flightGroup['flights'] as $segmentIndex => $segment)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-3">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="flex items-center space-x-3">
                                            @if(isset($segment['airline_logo']))
                                                <img src="{{ $segment['airline_logo'] }}"
                                                    alt="{{ $segment['airline'] }}" class="w-10 h-10 rounded">
                                            @endif
                                            <div>
                                                <div class="font-semibold">{{ $segment['airline'] }}</div>
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    Vol {{ $segment['flight_number'] }}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $segment['travel_class'] ?? '' }}
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-3 gap-4 items-center">
                                        <div>
                                            <div class="text-2xl font-bold">
                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('H:i') }}
                                            </div>
                                            <div class="text-sm font-semibold">
                                                {{ $segment['departure_airport']['name'] }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($segment['departure_airport']['time'])->format('d M Y') }}
                                            </div>
                                        </div>

                                        <div class="text-center">
                                            <div class="flex flex-col items-center">
                                                <div class="text-xs text-gray-500 mb-1">{{ $segment['duration'] ?? '' }}</div>
                                                <div class="w-full h-0.5 bg-gray-300 relative">
                                                    <div class="absolute right-0 top-1/2 transform -translate-y-1/2">
                                                        <svg class="w-4 h-4 text-gray-400" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path
                                                                d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z" />
                                                        </svg>
                                                    </div>
                                                </div>
                                                @if(isset($segment['airplane']))
                                                    <div class="text-xs text-gray-500 mt-1">{{ $segment['airplane'] }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-2xl font-bold">
                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('H:i') }}
                                            </div>
                                            <div class="text-sm font-semibold">
                                                {{ $segment['arrival_airport']['name'] }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($segment['arrival_airport']['time'])->format('d M Y') }}
                                            </div>
                                        </div>
                                    </div>

                                    @if(isset($segment['extensions']) && !empty($segment['extensions']))
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($segment['extensions'] as $extension)
                                                    <span
                                                        class="text-xs bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 px-2 py-1 rounded">
                                                        {{ $extension }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Escale --}}
                                @if(isset($flightGroup['layovers'][$segmentIndex]))
                                    <div class="flex items-center justify-center py-3">
                                        <div
                                            class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 px-4 py-2 rounded-lg text-sm">
                                            <span class="font-semibold">Escale à
                                                {{ $flightGroup['layovers'][$segmentIndex]['name'] }}</span>
                                            <span class="mx-2">•</span>
                                            <span>{{ $flightGroup['layovers'][$segmentIndex]['duration'] }}</span>
                                            @if($flightGroup['layovers'][$segmentIndex]['overnight'] ?? false)
                                                <span class="mx-2">•</span>
                                                <span class="font-semibold">Nuit sur place</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Émissions carbone --}}
                            @if(isset($flightGroup['carbon_emissions']))
                                <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                    <div class="flex items-center text-sm">
                                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-gray-700 dark:text-gray-300">
                                            Émissions CO₂:
                                            {{ round($flightGroup['carbon_emissions']['this_flight'] / 1000) }} kg
                                            @if(isset($flightGroup['carbon_emissions']['difference_percent']))
                                                <span
                                                    class="ml-2 {{ $flightGroup['carbon_emissions']['difference_percent'] > 0 ? 'text-red-600' : 'text-green-600' }}">
                                                    ({{ $flightGroup['carbon_emissions']['difference_percent'] > 0 ? '+' : '' }}{{ $flightGroup['carbon_emissions']['difference_percent'] }}%
                                                    par rapport à la moyenne)
                                                </span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Informations sur les bagages --}}
            @if(!empty($baggagePrices))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Politique de bagages</h2>

                    @if(isset($baggagePrices['together']))
                        <div class="space-y-2">
                            @foreach($baggagePrices['together'] as $baggage)
                                <div class="flex items-center text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 mr-3 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    {{ $baggage }}
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if(isset($baggagePrices['departing']) || isset($baggagePrices['returning']))
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            @if(isset($baggagePrices['departing']))
                                <div>
                                    <h3 class="font-semibold mb-2">Vol aller</h3>
                                    <div class="space-y-2">
                                        @foreach($baggagePrices['departing'] as $baggage)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $baggage }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(isset($baggagePrices['returning']))
                                <div>
                                    <h3 class="font-semibold mb-2">Vol retour</h3>
                                    <div class="space-y-2">
                                        @foreach($baggagePrices['returning'] as $baggage)
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-2 text-purple-600" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $baggage }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endif

            {{-- Options de réservation --}}
            @if(!empty($bookingOptions))
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Options de réservation</h2>

                    <div class="space-y-4">
                        @foreach($bookingOptions as $option)
                            @if(isset($option['together']))
                                <div
                                    class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:border-purple-500 transition-colors">
                                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center space-x-3 mb-2">
                                                @if(isset($option['together']['airline_logos']))
                                                    @foreach($option['together']['airline_logos'] as $logo)
                                                        <img src="{{ $logo }}" alt="Logo" class="w-8 h-8 rounded">
                                                    @endforeach
                                                @endif
                                                <span
                                                    class="font-semibold text-lg">{{ $option['together']['book_with'] }}</span>
                                            </div>

                                            @if(isset($option['together']['option_title']))
                                                <div class="text-purple-600 font-medium mb-2">
                                                    {{ $option['together']['option_title'] }}
                                                </div>
                                            @endif

                                            @if(isset($option['together']['extensions']))
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    @foreach($option['together']['extensions'] as $extension)
                                                        <span
                                                            class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 px-2 py-1 rounded">
                                                            {{ $extension }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            @endif

                                            @if(isset($option['together']['baggage_prices']))
                                                <div class="text-sm text-gray-600 dark:text-gray-400">
                                                    <span class="font-medium">Bagages:</span>
                                                    {{ implode(' • ', $option['together']['baggage_prices']) }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-4 lg:mt-0 lg:ml-6 text-center lg:text-right">
                                            <div class="text-3xl font-bold text-purple-600 mb-2">
                                                {{ $option['together']['price'] }} €
                                            </div>

                                            @if(isset($option['together']['booking_request']))
                                                <form action="{{ route('flights.booking') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="booking_url"
                                                        value="{{ $option['together']['booking_request']['url'] }}?{{ http_build_query(['post_data' => $option['together']['booking_request']['post_data']]) }}">
                                                    <input type="hidden" name="price"
                                                        value="{{ $option['together']['price'] }}">
                                                    <input type="hidden" name="booking_provider"
                                                        value="{{ $option['together']['book_with'] }}">
                                                    <button type="submit"
                                                        class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors w-full lg:w-auto">
                                                        Réserver maintenant
                                                    </button>
                                                </form>
                                            @elseif(isset($option['together']['booking_phone']))
                                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                    Réservation par téléphone
                                                </div>
                                                <a href="tel:{{ $option['together']['booking_phone'] }}"
                                                    class="inline-block bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                                                    {{ $option['together']['booking_phone'] }}
                                                </a>
                                                @if(isset($option['together']['estimated_phone_service_fee']))
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Frais de service: {{ $option['together']['estimated_phone_service_fee'] }}
                                                        €
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection