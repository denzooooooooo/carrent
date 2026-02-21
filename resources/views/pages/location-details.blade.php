@extends('layouts.app')

@section('title', __('Book this vehicle') . ' - ' . $location->name . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white mb-2">
                {{ __('Book this vehicle') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400">{{ $location->name }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Formulaire de réservation -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sm:p-8">
                    <form action="{{ route('location.book', $location) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Informations personnelles -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                {{ __('Personal Information') }}
                            </h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Prénom -->
                                <div>
                                    <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('First Name') }} *
                                    </label>
                                    <input type="text" 
                                           id="first_name" 
                                           name="first_name" 
                                           required
                                           value="{{ old('first_name', auth()->user()->first_name ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white"
                                           placeholder="{{ __('First Name') }}">
                                    @error('first_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Nom -->
                                <div>
                                    <label for="last_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Last Name') }} *
                                    </label>
                                    <input type="text" 
                                           id="last_name" 
                                           name="last_name" 
                                           required
                                           value="{{ old('last_name', auth()->user()->last_name ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white"
                                           placeholder="{{ __('Last Name') }}">
                                    @error('last_name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Coordonnées -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Contact Information') }}
                            </h2>
                            
                            <div class="space-y-4">
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Email') }} *
                                    </label>
                                    <input type="email" 
                                           id="email" 
                                           name="email" 
                                           required
                                           value="{{ old('email', auth()->user()->email ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white"
                                           placeholder="email@example.com">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Téléphone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Phone') }} *
                                    </label>
                                    <input type="tel" 
                                           id="phone" 
                                           name="phone" 
                                           required
                                           value="{{ old('phone', auth()->user()->phone ?? '') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white"
                                           placeholder="+225 XX XX XX XX XX">
                                    @error('phone')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Dates de location -->
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                {{ __('Rental Dates') }}
                            </h2>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <!-- Date de début -->
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('Start Date') }} *
                                    </label>
                                    <input type="date" 
                                           id="start_date" 
                                           name="start_date" 
                                           required
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                           value="{{ old('start_date') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white">
                                    @error('start_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <!-- Date de fin -->
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ __('End Date') }} *
                                    </label>
                                    <input type="date" 
                                           id="end_date" 
                                           name="end_date" 
                                           required
                                           min="{{ date('Y-m-d', strtotime('+2 days')) }}"
                                           value="{{ old('end_date') }}"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white">
                                    @error('end_date')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Demandes spéciales -->
                        <div>
                            <label for="special_requests" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                {{ __('Special Requests') }}
                            </label>
                            <textarea id="special_requests" 
                                      name="special_requests" 
                                      rows="3"
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-600 focus:outline-none dark:bg-gray-700 dark:text-white"
                                      placeholder="{{ __('Any special requests or requirements?') }}">{{ old('special_requests') }}</textarea>
                        </div>

                        <!-- Bouton de soumission -->
                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold py-4 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-200 flex items-center justify-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span>{{ __('Continue to Payment') }}</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Récapitulatif -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-6">{{ __('Summary') }}</h2>
                    
                    <!-- Image et nom -->
                    <div class="mb-6">
                        <img src="{{ $location->image_url ?: 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=400&h=250&fit=crop' }}" 
                             alt="{{ $location->name }}"
                             class="w-full h-32 object-cover rounded-xl mb-4">
                        <h3 class="font-semibold text-gray-900 dark:text-white">{{ $location->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ ucfirst($location->category) }} - {{ ucfirst($location->type) }}</p>
                    </div>

                    <!-- Caractéristiques -->
                    <div class="space-y-3 mb-6">
                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-sm">{{ $location->capacity }} {{ __('passengers') }}</span>
                        </div>
                        @if($location->features && count($location->features) > 0)
                            <div class="flex flex-wrap gap-1">
                                @foreach(array_slice($location->features, 0, 3) as $feature)
                                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-full">{{ $feature }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Prix -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Price per day') }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white">{{ \App\Helpers\CurrencyHelper::format($location->price_per_day) }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ __('Number of days') }}</span>
                            <span class="font-semibold text-gray-900 dark:text-white" id="days-count">0</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Total') }}</span>
                            <span class="text-xl font-bold text-green-600" id="total-price">0 XAF</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const daysCountSpan = document.getElementById('days-count');
    const totalPriceSpan = document.getElementById('total-price');
    const pricePerDay = {{ $location->price_per_day }};
    
    function updatePrice() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDateInput.value && endDateInput.value && endDate >= startDate) {
            const diffTime = Math.abs(endDate - startDate);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            const total = diffDays * pricePerDay;
            
            daysCountSpan.textContent = diffDays;
            totalPriceSpan.textContent = new Intl.NumberFormat('fr-FR').format(total) + ' XAF';
        } else {
            daysCountSpan.textContent = '0';
            totalPriceSpan.textContent = '0 XAF';
        }
    }
    
    startDateInput.addEventListener('change', updatePrice);
    endDateInput.addEventListener('change', updatePrice);
});
</script>
@endpush
@endsection

