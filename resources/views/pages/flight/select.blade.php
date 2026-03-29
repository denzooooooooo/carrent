@extends('layouts.app')

@section('title', __('Passenger Information') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50">
    <!-- Header -->
    <div class="bg-[#001F3F] text-white py-8">
        <div class="container mx-auto px-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('flights.search') }}" class="text-white/80 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-bold">{{ __('Passenger Information') }}</h1>
                    <p class="text-gray-300">{{ __('Please provide complete and accurate information') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-8">
        @if(!Session::has('selected_offer'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <svg class="w-12 h-12 text-red-500 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <h2 class="text-xl font-bold text-red-800 mb-2">{{ __('Session Expired') }}</h2>
                <p class="text-red-600 mb-4">{{ __('Your flight selection has expired. Please search again.') }}</p>
                <a href="{{ route('flights.index') }}" class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                    {{ __('New Search') }}
                </a>
            </div>
            @php return @endphp
        @endif

        <div class="lg:grid lg:grid-cols-3 lg:gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <form action="{{ route('flight.checkout') }}" method="POST" id="passengerForm">
                    @csrf

                    <!-- Flight Summary -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-gray-200">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            {{ __('Flight Summary') }}
                        </h2>
                        <div class="flex items-center gap-4 p-4 bg-blue-50 rounded-lg">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <span class="text-xl font-bold text-blue-700">
                                    {{ substr($offer['slices'][0]['segments'][0]['marketing_carrier']['iata_code'] ?? 'XX', 0, 2) }}
                                </span>
                            </div>
                            <div class="flex-1">
                                <p class="font-bold text-gray-900">
                                    {{ $offer['slices'][0]['segments'][0]['marketing_carrier']['name'] ?? 'Airline' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $offer['slices'][0]['origin']['iata_code'] ?? '' }} → 
                                    {{ end($offer['slices'])['destination']['iata_code'] ?? '' }}
                                    | {{ ucfirst($offer['cabin_class'] ?? 'economy') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ number_format(($offer['total_amount'] ?? 0) * $exchangeRate, 0, ',', ' ') }} XOF
                                </p>
                                <p class="text-xs text-gray-500">{{ __('per person') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Passenger Forms -->
                    <div id="passengers-container" class="space-y-6">
                        @for($i = 1; $i <= $total_passengers; $i++)
                            @php
                                $search = $search ?? [];
                                $adults = $search['adults'] ?? 1;
                                $children = $search['children'] ?? 0;
                                $infants = $search['infants'] ?? 0;
                                
                                if ($i <= $adults) {
                                    $type = 'adult';
                                    $label = __('Adult');
                                } elseif ($i <= $adults + $children) {
                                    $type = 'child';
                                    $label = __('Child');
                                } else {
                                    $type = 'infant';
                                    $label = __('Infant');
                                }
                                
                                $isFirstAdult = ($i === 1 && $type === 'adult');
                            @endphp
                            
                            <div class="bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                                    <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                                        <span class="font-bold text-purple-700">{{ $i }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900">{{ $label }} {{ $i }}</h3>
                                        @if($isFirstAdult)
                                            <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded">{{ __('Main Contact') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Title -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Title') }} *</label>
                                        <select name="passengers[{{ $i }}][title]" 
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                            <option value="">{{ __('Select') }}</option>
                                            <option value="mr">{{ __('Mr') }}</option>
                                            <option value="mrs">{{ __('Mrs') }}</option>
                                            <option value="miss">{{ __('Miss') }}</option>
                                            <option value="dr">{{ __('Dr') }}</option>
                                        </select>
                                    </div>

                                    <!-- Date of Birth (REQUIRED by Duffel) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Date of Birth') }} *</label>
                                        <input type="date" 
                                               name="passengers[{{ $i }}][born_on]"
                                               max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               required>
                                        <p class="text-xs text-gray-500 mt-1">{{ __('Required for all passengers') }}</p>
                                    </div>

                                    <!-- First Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('First Name') }} *</label>
                                        <input type="text" 
                                               name="passengers[{{ $i }}][first_name]"
                                               placeholder="{{ __('As on travel document') }}"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               required>
                                    </div>

                                    <!-- Last Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Last Name') }} *</label>
                                        <input type="text" 
                                               name="passengers[{{ $i }}][last_name]"
                                               placeholder="{{ __('As on travel document') }}"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               required>
                                    </div>

                                    <!-- Nationality (REQUIRED by Duffel) -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Nationality') }} *</label>
                                        <select name="passengers[{{ $i }}][nationality]"
                                                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                required>
                                            <option value="">{{ __('Select Country') }}</option>
                                            <option value="CI">{{ __('Côte d\'Ivoire') }}</option>
                                            <option value="SN">{{ __('Sénégal') }}</option>
                                            <option value="ML">{{ __('Mali') }}</option>
                                            <option value="BJ">{{ __('Bénin') }}</option>
                                            <option value="TG">{{ __('Togo') }}</option>
                                            <option value="GH">{{ __('Ghana') }}</option>
                                            <option value="NG">{{ __('Nigeria') }}</option>
                                            <option value="FR">{{ __('France') }}</option>
                                            <option value="US">{{ __('United States') }}</option>
                                            <option value="GB">{{ __('United Kingdom') }}</option>
                                            <option value="AE">{{ __('UAE') }}</option>
                                            <option value="OTHER">{{ __('Other') }}</option>
                                        </select>
                                    </div>

                                    <!-- Email -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }} {{ $isFirstAdult ? '*' : '' }}</label>
                                        <input type="email" 
                                               name="passengers[{{ $i }}][email]"
                                               placeholder="email@example.com"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               {{ $isFirstAdult ? 'required' : '' }}>
                                    </div>

                                    <!-- Phone -->
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone Number') }} {{ $isFirstAdult ? '*' : '' }}</label>
                                        <input type="tel" 
                                               name="passengers[{{ $i }}][phone]"
                                               placeholder="+225 01 01 22 15 15"
                                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                               {{ $isFirstAdult ? 'required' : '' }}>
                                    </div>
                                </div>

                                <!-- Identity Document Section -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <h4 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                                        </svg>
                                        {{ __('Travel Document') }}
                                    </h4>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Document Type -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Document Type') }} *</label>
                                            <select name="passengers[{{ $i }}][identity_document_type]"
                                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    required>
                                                <option value="">{{ __('Select Type') }}</option>
                                                <option value="passport">{{ __('Passport') }}</option>
                                                <option value="visa">{{ __('Visa') }}</option>
                                                <option value="national_id">{{ __('National ID') }}</option>
                                            </select>
                                        </div>

                                        <!-- Document Number -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Document Number') }} *</label>
                                            <input type="text" 
                                                   name="passengers[{{ $i }}][identity_document_number]"
                                                   placeholder="AB1234567"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   required>
                                        </div>

                                        <!-- Expiry Date -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Expiry Date') }} *</label>
                                            <input type="date" 
                                                   name="passengers[{{ $i }}][identity_document_expiry]"
                                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                   required>
                                        </div>

                                        <!-- Issuing Country -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Issuing Country') }} *</label>
                                            <select name="passengers[{{ $i }}][identity_document_issuing_country]"
                                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                                    required>
                                                <option value="">{{ __('Select Country') }}</option>
                                                <option value="CI">{{ __('Côte d\'Ivoire') }}</option>
                                                <option value="SN">{{ __('Sénégal') }}</option>
                                                <option value="ML">{{ __('Mali') }}</option>
                                                <option value="BJ">{{ __('Bénin') }}</option>
                                                <option value="TG">{{ __('Togo') }}</option>
                                                <option value="GH">{{ __('Ghana') }}</option>
                                                <option value="FR">{{ __('France') }}</option>
                                                <option value="US">{{ __('United States') }}</option>
                                                <option value="GB">{{ __('United Kingdom') }}</option>
                                                <option value="OTHER">{{ __('Other') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="passengers[{{ $i }}][type]" value="{{ $type }}">
                            </div>
                        @endfor
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('flights.search') }}" 
                           class="flex-1 text-center bg-gray-100 text-gray-700 py-4 px-6 rounded-xl font-bold hover:bg-gray-200 transition-colors">
                            ← {{ __('Back to Search') }}
                        </a>
                        <button type="submit" 
                                id="submitBtn"
                                class="flex-1 bg-[#001F3F] text-white py-4 px-6 rounded-xl font-bold hover:bg-[#003366] transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            {{ __('Continue to Payment') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 mt-8 lg:mt-0">
                <div class="sticky top-6 bg-white rounded-xl shadow-lg p-6 border border-gray-200">
                    <h3 class="font-bold text-gray-900 mb-4">{{ __('Booking Summary') }}</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Passengers') }}</span>
                            <span class="font-medium">{{ $total_passengers }}</span>
                        </div>
                        
                        @php
                            $price = $offer['total_amount'] ?? 0;
                            $total = $price * $total_passengers;
                            $currency = $offer['total_currency'] ?? 'EUR';
                        @endphp

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ __('Price per person') }}</span>
                            <span class="font-medium">{{ number_format($price * $exchangeRate, 0, ',', ' ') }} XOF</span>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-900">{{ __('Total') }}</span>
                                <span class="font-bold text-xl text-[#001F3F]">
                                    {{ number_format($total * $exchangeRate, 0, ',', ' ') }} XOF
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notice -->
                    <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">{{ __('Important') }}</p>
                                <p class="text-xs text-amber-700 mt-1">
                                    {{ __('All information must match your travel document exactly. Incorrect information may result in boarding denial.') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('passengerForm').addEventListener('submit', function(e) {
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg><span>{{ __('Processing...') }}</span>';
});
</script>
@endsection
