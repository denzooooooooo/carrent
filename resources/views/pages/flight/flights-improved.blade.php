@extends('layouts.app')

@section('title', __('Flights') . ' - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
        {{-- Hero Section --}}
        <section class="relative bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-800 text-white py-16 md:py-24 overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 left-0 w-96 h-96 bg-white rounded-full filter blur-3xl"></div>
                <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-400 rounded-full filter blur-3xl"></div>
            </div>
            
            <div class="container mx-auto px-4 relative z-10">
                <div class="max-w-5xl mx-auto text-center">
                    {{-- Icon --}}
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-white/20 backdrop-blur-sm rounded-2xl mb-6">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-black mb-6 leading-tight">
                        {{ __('Find Your Perfect') }} <span class="text-amber-400">{{ __('Flight') }}</span>
                    </h1>
                    <p class="text-xl md:text-2xl opacity-95 max-w-3xl mx-auto mb-8">
                        {{ __('Compare prices from hundreds of airlines and travel agencies') }}
                    </p>
                    
                    {{-- Trust Badges --}}
                    <div class="flex flex-wrap justify-center gap-6 md:gap-8">
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">{{ __('Best Price Guarantee') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">{{ __('Instant Confirmation') }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <svg class="w-6 h-6 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="font-semibold">{{ __('24/7 Support') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- Search Form Section --}}
        <section class="container mx-auto px-4 -mt-12 relative z-20 mb-16">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl border-2 border-purple-100 dark:border-purple-900 p-6 md:p-10">
                {{-- Trip Type Selector --}}
                <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4 mb-8">
                    <button type="button" id="btn-roundtrip"
                        class="flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gradient-to-r from-purple-600 to-amber-600 text-white shadow-xl">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                            </svg>
                            <span>{{ __('Round Trip') }}</span>
                        </div>
                    </button>
                    <button type="button" id="btn-oneway"
                        class="flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                            <span>{{ __('One Way') }}</span>
                        </div>
                    </button>
                    <button type="button" id="btn-multicity"
                        class="flex-1 py-4 px-6 rounded-2xl font-bold transition-all duration-300 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600">
                        <div class="flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                            </svg>
                            <span>{{ __('Multi-City') }}</span>
                        </div>
                    </button>
                </div>

                {{-- Error Messages --}}
                @if (session('error'))
                    <div class="bg-red-50 dark:bg-red-900/30 border-l-4 border-red-500 p-5 rounded-xl mb-6">
                        <div class="flex items-start space-x-3">
                            <svg class="w-6 h-6 text-red-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                            <p class="text-red-700 dark:text-red-300 font-semibold">{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                <form id="flight-search-form" method="POST" action="{{ route('flights.search') }}">
                    @csrf
                    <input type="hidden" id="trip-type" name="type" value="1">
                    <input type="hidden" id="multi-city-data" name="multi_city_json" value="">

                    {{-- Standard Flight Fields --}}
                    <div id="standard-flight-fields">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            {{-- Origin --}}
                            <div class="relative">
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                    </svg>
                                    <span>{{ __('Departure Airport') }} *</span>
                                </label>
                                <input type="text" id="origin-input" placeholder="{{ __('Ex: CDG, Paris...') }}"
                                    class="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 text-lg font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    autocomplete="off">
                                <input type="hidden" id="origin-code" name="departure_id">
                                <div id="origin-suggestions" class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-700 rounded-2xl shadow-2xl max-h-80 overflow-y-auto"></div>
                            </div>

                            {{-- Destination --}}
                            <div class="relative">
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    </svg>
                                    <span>{{ __('Arrival Airport') }} *</span>
                                </label>
                                <input type="text" id="destination-input" placeholder="{{ __('Ex: JFK, New York...') }}"
                                    class="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 text-lg font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white"
                                    autocomplete="off">
                                <input type="hidden" id="destination-code" name="arrival_id">
                                <div id="destination-suggestions" class="hidden absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-amber-200 dark:border-amber-700 rounded-2xl shadow-2xl max-h-80 overflow-y-auto"></div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                            {{-- Departure Date --}}
                            <div>
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('Departure Date') }} *</span>
                                </label>
                                <input type="date" id="departure-date" name="outbound_date" min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-purple-500/50 focus:border-purple-500 text-lg font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>

                            {{-- Return Date --}}
                            <div id="return-date-container">
                                <label class="flex items-center space-x-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span>{{ __('Return Date') }} *</span>
                                </label>
                                <input type="date" id="return-date" name="return_date" min="{{ date('Y-m-d') }}"
                                    class="w-full px-4 py-4 border-2 border-gray-300 dark:border-gray-600 rounded-2xl focus:ring-4 focus:ring-amber-500/50 focus:border-amber-500 text-lg font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>
                        </div>
                    </div>

                    {{-- Multi-City Fields --}}
                    <div id="multi-city-fields" class="hidden">
                        <div id="multi-city-flights-container"></div>
                        <button type="button" id="add-flight-btn"
                            class="flex items-center space-x-2 text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 font-bold py-3 px-4 rounded-xl border-2 border-purple-300 dark:border-purple-700 hover:border-purple-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>{{ __('Add a flight') }}</span>
                        </button>
                    </div>

                    {{-- Passengers & Class --}}
                    <div class="bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-2xl p-6 mb-6 border-2 border-purple-200 dark:border-purple-800">
                        <div class="flex items-center space-x-2 mb-4">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <h3 class="text-lg font-black text-gray-900 dark:text-white">{{ __('Passengers & Class') }}</h3>
                        </div>

                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Adults (12+)') }}</label>
                                <select name="adults" id="adults"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @for ($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Children (2-11)') }}</label>
                                <select name="children" id="children"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @for ($i = 0; $i <= 8; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Infants (0-2)') }}</label>
                                <select name="infants" id="infants"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    @for ($i = 0; $i <= 4; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Class') }}</label>
                                <select name="travel_class" id="travel-class"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                                    <option value="ECONOMY">{{ __('Economy') }}</option>
                                    <option value="PREMIUM_ECONOMY">{{ __('Premium Economy') }}</option>
                                    <option value="BUSINESS">{{ __('Business') }}</option>
                                    <option value="FIRST">{{ __('First') }}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Options --}}
                    <div class="mb-6 space-y-3">
                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" name="non_stop" id="non-stop"
                                class="w-6 h-6 text-purple-600 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-4 focus:ring-purple-500/50">
                            <span class="text-gray-700 dark:text-gray-300 font-bold text-lg group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                {{ __('Direct flights only') }}
                            </span>
                        </label>

                        <label class="flex items-center space-x-3 cursor-pointer group">
                            <input type="checkbox" name="deep_search" id="deep-search"
                                class="w-6 h-6 text-purple-600 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-4 focus:ring-purple-500/50">
                            <span class="text-gray-700 dark:text-gray-300 font-bold text-lg group-hover:text-purple-600 dark:group-hover:text-purple-400 transition-colors">
                                {{ __('Deep search (slower but more results)') }}
                            </span>
                        </label>
                    </div>

                    {{-- Sort By --}}
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">{{ __('Sort by') }}</label>
                        <select name="sort_by" id="sort-by"
                            class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-purple-500 font-semibold bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            <option value="1">{{ __('Best flights') }}</option>
                            <option value="2">{{ __('Price') }}</option>
                            <option value="3">{{ __('Departure time') }}</option>
                            <option value="4">{{ __('Arrival time') }}</option>
                            <option value="5">{{ __('Duration') }}</option>
                            <option value="6">{{ __('Emissions') }}</option>
                        </select>
                    </div>

                    <input type="hidden" name="currency" value="EUR">

                    {{-- Search Button --}}
                    <button type="submit" id="search-btn"
                        class="w-full bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 hover:from-purple-700 hover:via-purple-800 hover:to-amber-700 text-white font-black text-xl py-6 px-8 rounded-2xl transition-all duration-300 transform hover:scale-[1.02] shadow-2xl flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span>{{ __('SEARCH FLIGHTS') }}</span>
                    </button>
                </form>
            </div>
        </section>

        {{-- Travel Insurance Section --}}
        <section class="container mx-auto px-4 mb-16">
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-3xl p-8 md:p-12 border-2 border-blue-200 dark:border-blue-800">
                <div class="flex items-center space-x-3 mb-6">
                    <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white">{{ __('Travel Insurance') }}</h2>
                </div>

                <p class="text-lg text-gray-700 dark:text-gray-300 mb-8">
                    {{ __('Protect your trip with comprehensive travel insurance. Get covered for cancellations, medical emergencies, and more.') }}
                </p>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    {{-- Basic Plan --}}
                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border-2 border-gray-200 dark:border-gray-700 hover:border-blue-500 dark:hover:border-blue-500 transition-all">
                        <div class="text-center mb-4">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ __('Basic') }}</h3>
                            <div class="text-3xl font-black text-blue-600 dark:text-blue-400">€29</div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">{{ __('per person') }}</div>
                        </div>
                        <ul class="space-y-3">
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Trip cancellation') }}</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">{{ __('Baggage loss') }}</span>
                            </li>
                            <li class="flex items-start space-x-2">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20
