@extends('layouts.app')

@section('title', 'Détails Vol Aller Simple - Carré Premium')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-12">
    <div class="container mx-auto px-4 text-center">
        <h1 class="text-4xl lg:text-5xl font-black mb-3">✈️ Détails de votre vol aller simple</h1>
        <p class="text-xl opacity-90">Vérifiez les informations et complétez votre réservation</p>
    </div>
</div>

<div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
    <div class="container mx-auto px-4 py-8">

        @if($error)
            <div class="max-w-4xl mx-auto bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-2xl shadow-2xl mb-8">
                <div class="flex items-center space-x-3 mb-4">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-bold">Erreur</h3>
                </div>
                <p>{{ $error }}</p>
                <a href="{{ route('flights.index') }}"
                    class="inline-block mt-4 bg-red-600 text-white px-6 py-3 rounded-lg font-bold hover:bg-red-700">
                    🔍 Nouvelle recherche
                </a>
            </div>
        @elseif($selectedFlight)
            <div class="max-w-7xl mx-auto">
                <div class="lg:grid lg:grid-cols-3 lg:gap-8">

                    {{-- COLONNE PRINCIPALE --}}
                    <div class="lg:col-span-2">

                        {{-- INFORMATIONS DU VOL --}}
                        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 mb-8 border-2 border-purple-100">

                            {{-- Header du vol --}}
                            <div
                                class="flex flex-col sm:flex-row sm:justify-between sm:items-start border-b-2 border-purple-100 pb-6 mb-6">
                                <div class="mb-4 sm:mb-0">
                                    <div class="flex items-center space-x-4 mb-3">
                                        @if(isset($selectedFlight['flights'][0]['airline_logo']))
                                            <img src="{{ $selectedFlight['flights'][0]['airline_logo'] }}"
                                                alt="{{ $selectedFlight['flights'][0]['airline'] }}"
                                                class="w-14 h-14 rounded-xl shadow-lg object-contain bg-white p-2 border border-gray-200">
                                        @endif
                                        <div>
                                            <h2 class="text-2xl md:text-3xl font-black text-gray-900">
                                                {{ $selectedFlight['flights'][0]['airline'] ?? 'Vol aller simple' }}
                                            </h2>
                                            <div class="flex items-center space-x-3 text-sm text-gray-600 mt-2">
                                                <span
                                                    class="flex items-center gap-1 bg-purple-100 px-3 py-1 rounded-full font-semibold text-purple-700">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $selectedFlight['total_duration'] ?? 'N/A' }}
                                                </span>
                                                <span
                                                    class="flex items-center gap-1 bg-blue-100 px-3 py-1 rounded-full font-semibold text-blue-700">
                                                    ✈️
                                                    {{ count($selectedFlight['layovers'] ?? []) == 0 ? 'Direct' : count($selectedFlight['layovers']) . ' escale(s)' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border-2 border-green-200 shadow-lg">
                                    <div class="text-center">
                                        <div class="text-3xl font-black text-green-600">
                                            {{ number_format($selectedFlight['price'] ?? 0, 0, ',', ' ') }}
                                        </div>
                                        <div class="text-sm font-bold text-green-700">
                                            {{ $searchParams['currency'] ?? 'EUR' }} / personne
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Segments de vol --}}
                            @foreach($selectedFlight['flights'] as $index => $segment)
                                <div
                                    class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-2xl p-6 mb-6 border-2 border-blue-200 shadow-lg">
                                    <div
                                        class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-6 lg:space-y-0">

                                        {{-- Départ --}}
                                        <div class="flex-1">
                                            <div class="flex items-start space-x-4">
                                                <div class="bg-blue-500 rounded-full p-3 shadow-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <p class="text-3xl font-black text-blue-900">
                                                        {{ \Carbon\Carbon::parse($segment['departure_time'])->format('H:i') }}
                                                    </p>
                                                    <p class="font-bold text-blue-800 text-lg mt-1">
                                                        {{ $segment['departure_airport']['code'] ?? $segment['departure_airport']['id'] }}
                                                    </p>
                                                    <p class="text-sm text-gray-700 font-semibold">
                                                        {{ $segment['departure_airport']['name'] ?? '' }}
                                                    </p>
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        📅
                                                        {{ \Carbon\Carbon::parse($segment['departure_time'])->format('d M Y') }}
                                                    </p>
                                                    @if(!empty($segment['departure_airport']['city']))
                                                        <p class="text-xs text-gray-600">
                                                            📍 {{ $segment['departure_airport']['city'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Durée et infos --}}
                                        <div class="flex-shrink-0 text-center lg:w-72 lg:px-6">
                                            <div class="bg-white rounded-xl p-4 shadow-md border border-blue-200">
                                                <div class="text-sm text-blue-700 font-bold mb-2">
                                                    {{ $segment['duration'] ?? 'N/A' }}
                                                </div>
                                                <div class="flex items-center justify-center mb-3">
                                                    <div
                                                        class="h-1 bg-gradient-to-r from-blue-400 to-blue-500 w-full rounded-l">
                                                    </div>
                                                    <div
                                                        class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-full p-2 -mx-2 shadow-lg">
                                                        <svg class="w-5 h-5 text-white rotate-90" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                        </svg>
                                                    </div>
                                                    <div
                                                        class="h-1 bg-gradient-to-l from-blue-400 to-blue-500 w-full rounded-r">
                                                    </div>
                                                </div>
                                                <div class="space-y-1">
                                                    <p class="text-xs text-gray-700 font-semibold">
                                                        🛫 Vol {{ $segment['flight_number'] ?? 'N/A' }}
                                                    </p>
                                                    @if(!empty($segment['aircraft']))
                                                        <p class="text-xs text-gray-600">
                                                            ✈️ {{ $segment['aircraft'] }}
                                                        </p>
                                                    @endif
                                                    @if(!empty($segment['travel_class']))
                                                        <p class="text-xs text-purple-700 font-semibold">
                                                            💺
                                                            {{ ucfirst(str_replace('_', ' ', strtolower($segment['travel_class']))) }}
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- Arrivée --}}
                                        <div class="flex-1 text-right">
                                            <div class="flex items-start justify-end space-x-4">
                                                <div>
                                                    <p class="text-3xl font-black text-blue-900">
                                                        {{ \Carbon\Carbon::parse($segment['arrival_time'])->format('H:i') }}
                                                    </p>
                                                    <p class="font-bold text-blue-800 text-lg mt-1">
                                                        {{ $segment['arrival_airport']['code'] ?? $segment['arrival_airport']['id'] }}
                                                    </p>
                                                    <p class="text-sm text-gray-700 font-semibold">
                                                        {{ $segment['arrival_airport']['name'] ?? '' }}
                                                    </p>
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        📅
                                                        {{ \Carbon\Carbon::parse($segment['arrival_time'])->format('d M Y') }}
                                                    </p>
                                                    @if(!empty($segment['arrival_airport']['city']))
                                                        <p class="text-xs text-gray-600">
                                                            📍 {{ $segment['arrival_airport']['city'] }}
                                                        </p>
                                                    @endif
                                                </div>
                                                <div class="bg-green-500 rounded-full p-3 shadow-lg">
                                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                        </path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Services et équipements --}}
                                    @if(!empty($segment['extensions']))
                                        <div class="mt-6 pt-6 border-t border-blue-300">
                                            <h4 class="text-sm font-bold text-blue-800 mb-3">📋 Services inclus</h4>
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($segment['extensions'] as $extension)
                                                    <span
                                                        class="text-xs bg-white px-3 py-1.5 rounded-full text-gray-700 border border-blue-200 font-medium">
                                                        {{ $extension }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Escale --}}
                                @if(isset($selectedFlight['layovers'][$index]))
                                    <div class="flex items-center justify-center space-x-4 my-6">
                                        <div class="flex-1 h-px bg-gradient-to-r from-transparent via-amber-300 to-transparent">
                                        </div>
                                        <div
                                            class="bg-gradient-to-r from-amber-100 to-orange-100 border-2 border-amber-300 rounded-xl p-4 shadow-lg">
                                            <div class="flex items-center space-x-3">
                                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <div>
                                                    <div class="font-black text-amber-800 text-base">
                                                        Escale à {{ $selectedFlight['layovers'][$index]['name'] }}
                                                    </div>
                                                    <div class="text-amber-700 font-bold text-sm">
                                                        ⏱️ {{ $selectedFlight['layovers'][$index]['duration'] }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex-1 h-px bg-gradient-to-l from-transparent via-amber-300 to-transparent">
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            {{-- Émissions carbone --}}
                            @if(isset($selectedFlight['carbon_emissions']))
                                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="bg-green-500 rounded-full p-2">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                                                </path>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-green-800">Émissions de CO₂</p>
                                            <p class="text-sm text-green-700">
                                                {{ round($selectedFlight['carbon_emissions']['this_flight'] / 1000) }} kg par
                                                passager
                                                @if(isset($selectedFlight['carbon_emissions']['difference_percent']))
                                                    <span class="ml-2 text-xs">
                                                        ({{ $selectedFlight['carbon_emissions']['difference_percent'] > 0 ? '+' : '' }}{{ $selectedFlight['carbon_emissions']['difference_percent'] }}%
                                                        vs moyenne)
                                                    </span>
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- FORMULAIRE DE RÉSERVATION --}}
                        <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8 border-2 border-purple-100">
                            <div class="flex items-center space-x-3 mb-6 border-b-2 border-purple-100 pb-4">
                                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <h2 class="text-2xl md:text-3xl font-black text-gray-900">Informations passagers</h2>
                            </div>

                            @if ($errors->any())
                                <div
                                    class="max-w-4xl mx-auto bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-2xl shadow-2xl mb-8">
                                    <div class="flex items-center space-x-3 mb-4">
                                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        <h3 class="text-lg font-bold">❌ Erreur de validation</h3>
                                    </div>
                                    <ul class="list-disc list-inside space-y-2">
                                        @foreach ($errors->all() as $error)
                                            <li class="font-semibold">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if (session('error'))
                                <div
                                    class="max-w-4xl mx-auto bg-red-50 border-l-4 border-red-500 text-red-700 p-6 rounded-2xl shadow-2xl mb-8">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z">
                                            </path>
                                        </svg>
                                        <p class="font-bold">{{ session('error') }}</p>
                                    </div>
                                </div>
                            @endif

                            @if (session('success'))
                                <div
                                    class="max-w-4xl mx-auto bg-green-50 border-l-4 border-green-500 text-green-700 p-6 rounded-2xl shadow-2xl mb-8">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <p class="font-bold">{{ session('success') }}</p>
                                    </div>
                                </div>
                            @endif

                            <form action="{{ route('flights.booking.store') }}" method="POST" id="bookingForm">
                                @csrf

                                {{-- Champs cachés --}}
                                <input type="hidden" name="booking_token" value="{{ request('booking_token') }}">
                                <input type="hidden" name="departure_id" value="{{ $searchParams['departure_id'] ?? '' }}">
                                <input type="hidden" name="arrival_id" value="{{ $searchParams['arrival_id'] ?? '' }}">
                                <input type="hidden" name="outbound_date"
                                    value="{{ $searchParams['outbound_date'] ?? '' }}">
                                <input type="hidden" name="travel_class"
                                    value="{{ $selectedFlight['flights'][0]['travel_class'] ?? 'ECONOMY' }}">
                                <input type="hidden" name="flight_details" value='@json($selectedFlight)'>
                                <input type="hidden" name="booking_options" value='@json($bookingOptions ?? [])'>
                                <input type="hidden" name="base_price" id="hidden_base_price"
                                    value="{{ $selectedFlight['price'] ?? 0 }}">
                                <input type="hidden" name="taxes" id="hidden_taxes"
                                    value="{{ ($selectedFlight['price'] ?? 0) * 0.1 }}">
                                <input type="hidden" name="final_price" id="hidden_final_price"
                                    value="{{ ($selectedFlight['price'] ?? 0) * 1.1 }}">
                                <input type="hidden" name="currency" value="{{ $searchParams['currency'] ?? 'EUR' }}">
                                <input type="hidden" name="trip_type" value="one_way">

                                {{-- Configuration passagers --}}
                                <div class="mb-8">
                                    <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                                            </path>
                                        </svg>
                                        <span>Nombre de passagers</span>
                                    </h3>
                                    <div
                                        class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 bg-gradient-to-r from-purple-50 to-amber-50 rounded-xl border border-purple-200">
                                        <div class="bg-white rounded-lg p-4 border border-purple-200">
                                            <label for="adults" class="block text-sm font-bold text-gray-800 mb-2">👤
                                                Adultes (18+)</label>
                                            <input type="number" id="adults" name="adults" min="1" max="9"
                                                value="{{ $searchParams['adults'] ?? 1 }}"
                                                class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold"
                                                required>
                                        </div>
                                        <div class="bg-white rounded-lg p-4 border border-purple-200">
                                            <label for="children" class="block text-sm font-bold text-gray-800 mb-2">👶
                                                Enfants (2-11)</label>
                                            <input type="number" id="children" name="children" min="0" max="8"
                                                value="{{ $searchParams['children'] ?? 0 }}"
                                                class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold">
                                        </div>
                                        <div class="bg-white rounded-lg p-4 border border-purple-200">
                                            <label for="infants" class="block text-sm font-bold text-gray-800 mb-2">🍼 Bébés
                                                (-2)</label>
                                            <input type="number" id="infants" name="infants" min="0" max="4"
                                                value="{{ $searchParams['infants'] ?? 0 }}"
                                                class="w-full border-2 border-purple-200 rounded-lg px-3 py-2 font-semibold">
                                        </div>
                                    </div>
                                </div>

                                {{-- Détails passagers (dynamique) --}}
                                <div id="passengersInfo" class="mb-8"></div>

                                {{-- Boutons --}}
                                {{-- Boutons --}}
                                <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t-2 border-purple-200">
                                    <button type="button" onclick="window.history.back()"
                                        class="flex-1 text-center bg-gray-200 text-gray-700 px-6 py-4 rounded-xl font-bold hover:bg-gray-300 transition-all flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 19l-7-7 7-7"></path>
                                        </svg>
                                        <span>Retour</span>
                                    </button>
                                    <button type="submit"
                                        class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-6 py-4 rounded-xl font-black hover:from-purple-700 hover:to-purple-800 transition-all shadow-2xl flex items-center justify-center gap-2">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span>Confirmer la réservation</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- SIDEBAR RÉCAPITULATIF PRIX --}}
                    <div class="lg:col-span-1 mt-8 lg:mt-0">
                        <div class="sticky top-10 bg-white rounded-2xl shadow-2xl p-6 border-2 border-purple-200">
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b-2 border-purple-200">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                    </path>
                                </svg>
                                <h2 class="text-xl font-black text-purple-800">Récapitulatif</h2>
                            </div>

                            <div class="space-y-3 mb-6">
                                <div
                                    class="bg-gradient-to-r from-purple-50 to-amber-50 rounded-lg p-3 border border-purple-200">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-700">Prix/personne</span>
                                        <span
                                            class="font-bold text-purple-700">{{ number_format($selectedFlight['price'] ?? 0, 0, ',', ' ') }}
                                            {{ $searchParams['currency'] ?? 'EUR' }}</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium text-gray-700">Passagers</span>
                                        <span id="passengerCount" class="font-bold text-purple-700">1</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="flex justify-between items-center">
                                        <span class="font-semibold text-gray-800">Sous-total</span>
                                        <span id="basePrice"
                                            class="font-bold text-gray-800">{{ number_format($selectedFlight['price'] ?? 0, 2, ',', ' ') }}
                                            {{ $searchParams['currency'] ?? 'EUR' }}</span>
                                    </div>
                                </div>
                                <div class="bg-white rounded-lg p-3 border border-purple-200">
                                    <div class="flex justify-between items-center text-sm">
                                        <span class="text-gray-600">Taxes et frais</span>
                                        <span id="taxes"
                                            class="font-semibold text-gray-700">{{ number_format(($selectedFlight['price'] ?? 0) * 0.1, 2, ',', ' ') }}
                                            {{ $searchParams['currency'] ?? 'EUR' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-xl p-4 text-white">
                                <div class="flex justify-between items-center">
                                    <span class="text-lg font-bold">TOTAL</span>
                                    <span id="totalPrice" class="text-2xl font-black">
                                        {{ number_format(($selectedFlight['price'] ?? 0) * 1.1, 2, ',', ' ') }}
                                        {{ $searchParams['currency'] ?? 'EUR' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        @endif
    </div>
</div>


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const adultsInput = document.getElementById('adults');
            const childrenInput = document.getElementById('children');
            const infantsInput = document.getElementById('infants');
            const passengersContainer = document.getElementById('passengersInfo');
            const passengerCountSpan = document.getElementById('passengerCount');
            const bookingForm = document.getElementById('bookingForm');
            const submitButton = bookingForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;

            // ========================================
            // GESTION DES PASSAGERS
            // ========================================
            function updatePassengerFields() {
                const adults = parseInt(adultsInput.value) || 0;
                const children = parseInt(childrenInput.value) || 0;
                const infants = parseInt(infantsInput.value) || 0;
                const total = adults + children + infants;

                passengersContainer.innerHTML = '';

                if (total > 0) {
                    passengersContainer.innerHTML = '<h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2"><svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg><span>Informations de chaque passager</span></h3>';
                }

                for (let i = 0; i < total; i++) {
                    let type = i < adults ? 'Adulte' : (i < adults + children ? 'Enfant' : 'Bébé');
                    let isFirst = (i === 0);
                    let placeholder = isFirst ? 'Nom complet (Passager principal - obligatoire)' : 'Nom complet';
                    let emailRequired = isFirst ? 'required' : '';
                    let phoneRequired = isFirst ? 'required' : '';

                    passengersContainer.innerHTML += `
                    <div class="bg-gradient-to-r from-gray-50 to-purple-50 border-2 border-purple-200 rounded-xl p-5 mb-4 shadow-lg">
                        <h4 class="font-bold mb-4 text-gray-900 text-lg flex items-center gap-2">
                            ${type === 'Adulte' ? '👤' : type === 'Enfant' ? '👶' : '🍼'} 
                            ${type} ${i + 1} 
                            ${isFirst ? '<span class="text-xs bg-purple-600 text-white px-2 py-1 rounded-full ml-2">Principal</span>' : ''}
                        </h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <input type="text" name="passenger_names[]" placeholder="${placeholder}" 
                                   class="border-2 border-purple-200 rounded-lg px-3 py-2 focus:border-purple-500 focus:ring-purple-500 font-semibold" required>
                            <input type="email" name="passenger_emails[]" placeholder="${isFirst ? 'Email (Obligatoire)' : 'Email (Optionnel)'}" 
                                   class="border-2 border-purple-200 rounded-lg px-3 py-2 focus:border-purple-500 focus:ring-purple-500 font-semibold" ${emailRequired}>
                            <input type="tel" name="passenger_phones[]" placeholder="${isFirst ? 'Téléphone (Obligatoire)' : 'Téléphone (Optionnel)'}" 
                                   class="border-2 border-purple-200 rounded-lg px-3 py-2 focus:border-purple-500 focus:ring-purple-500 font-semibold" ${phoneRequired}>
                        </div>
                    </div>
                `;
                }

                updatePricing(total);
            }

            // ========================================
            // CALCUL DES PRIX
            // ========================================
            function updatePricing(passengers) {
                const basePricePerPax = {{ $selectedFlight['price'] ?? 0 }};
                const currency = '{{ $searchParams['currency'] ?? 'EUR' }}';
                const totalBase = basePricePerPax * passengers;
                const taxes = totalBase * 0.1;
                const total = totalBase + taxes;

                passengerCountSpan.textContent = passengers;
                document.getElementById('basePrice').textContent = totalBase.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' ' + currency;
                document.getElementById('taxes').textContent = taxes.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' ' + currency;
                document.getElementById('totalPrice').textContent = total.toLocaleString('fr-FR', { minimumFractionDigits: 2 }) + ' ' + currency;

                document.getElementById('hidden_base_price').value = totalBase.toFixed(2);
                document.getElementById('hidden_taxes').value = taxes.toFixed(2);
                document.getElementById('hidden_final_price').value = total.toFixed(2);
            }

            // ========================================
            // ÉVÉNEMENTS CHANGEMENT PASSAGERS
            // ========================================
            [adultsInput, childrenInput, infantsInput].forEach(input => {
                input.addEventListener('change', updatePassengerFields);
                input.addEventListener('keyup', updatePassengerFields);
            });

            updatePassengerFields();

            // ========================================
            // SOUMISSION FORMULAIRE AVEC LOGS
            // ========================================
            bookingForm.addEventListener('submit', function (e) {
                console.log('========== SOUMISSION FORMULAIRE ==========');
                console.log('📤 Action:', bookingForm.action);
                console.log('📤 Method:', bookingForm.method);

                // Vérifier tous les champs requis
                const requiredFields = {
                    booking_token: bookingForm.querySelector('[name="booking_token"]')?.value,
                    departure_id: bookingForm.querySelector('[name="departure_id"]')?.value,
                    arrival_id: bookingForm.querySelector('[name="arrival_id"]')?.value,
                    outbound_date: bookingForm.querySelector('[name="outbound_date"]')?.value,
                    base_price: bookingForm.querySelector('[name="base_price"]')?.value,
                    taxes: bookingForm.querySelector('[name="taxes"]')?.value,
                    final_price: bookingForm.querySelector('[name="final_price"]')?.value,
                    currency: bookingForm.querySelector('[name="currency"]')?.value,
                    adults: bookingForm.querySelector('[name="adults"]')?.value,
                    travel_class: bookingForm.querySelector('[name="travel_class"]')?.value,
                };

                console.log('📋 Champs requis:', requiredFields);

                const passengerNames = bookingForm.querySelectorAll('[name="passenger_names[]"]');
                const passengerEmails = bookingForm.querySelectorAll('[name="passenger_emails[]"]');
                const passengerPhones = bookingForm.querySelectorAll('[name="passenger_phones[]"]');

                console.log('👥 Passagers:', {
                    names: passengerNames.length,
                    emails: passengerEmails.length,
                    phones: passengerPhones.length,
                    first_name: passengerNames[0]?.value,
                    first_email: passengerEmails[0]?.value,
                    first_phone: passengerPhones[0]?.value,
                });

                // Vérification basique
                let hasError = false;
                Object.entries(requiredFields).forEach(([key, value]) => {
                    if (!value || value === '') {
                        console.error('❌ Champ manquant:', key);
                        hasError = true;
                    }
                });

                if (passengerNames.length === 0 || !passengerNames[0]?.value) {
                    console.error('❌ Aucun nom de passager');
                    hasError = true;
                }

                if (hasError) {
                    e.preventDefault();
                    alert('⚠️ Veuillez remplir tous les champs obligatoires. Vérifiez la console (F12)');
                    return false;
                }

                // Désactiver le bouton
                submitButton.disabled = true;
                submitButton.innerHTML = `
                <svg class="animate-spin h-5 w-5 inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="ml-2">⏳ Traitement en cours...</span>
            `;

                console.log('✅ Formulaire validé, envoi en cours...');

                // Réactiver après 15 secondes au cas où
                setTimeout(() => {
                    if (submitButton.disabled) {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                        console.warn('⚠️ Timeout : bouton réactivé');
                    }
                }, 15000);
            });
        });
    </script>
@endpush
@endsection