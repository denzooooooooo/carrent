@extends('layouts.app')

@section('title', __('Booking Partners') . ' - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                {{-- Header --}}
                <div class="text-center mb-8">
                    <div class="w-16 h-16 md:w-20 md:h-20 bg-gradient-to-r from-blue-600 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-8 h-8 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black text-gray-900 mb-4">
                        {{ __('Choose Your Booking Partner') }}
                    </h1>
                    <p class="text-gray-600 text-lg">
                        {{ __('Select a trusted partner to complete your flight booking') }}
                    </p>
                </div>

                {{-- Flight Summary --}}
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-900 mb-4">{{ __('Flight Summary') }}</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">{{ __('From') }}</p>
                            <p class="font-semibold text-lg">{{ $searchParams['departure_id'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('To') }}</p>
                            <p class="font-semibold text-lg">{{ $searchParams['arrival_id'] }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Departure') }}</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($searchParams['outbound_date'])->format('d M Y') }}</p>
                        </div>
                        @if(isset($searchParams['return_date']))
                        <div>
                            <p class="text-sm text-gray-600">{{ __('Return') }}</p>
                            <p class="font-semibold">{{ \Carbon\Carbon::parse($searchParams['return_date'])->format('d M Y') }}</p>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Booking Partners --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($bookingUrls as $partner)
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold text-gray-900">{{ $partner['name'] }}</h3>
                                <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                    </svg>
                                </div>
                            </div>

                            <p class="text-gray-600 mb-6">{{ $partner['description'] }}</p>

                            <form method="POST" action="{{ route('flight.store-booking') }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="departure_id" value="{{ $searchParams['departure_id'] }}">
                                <input type="hidden" name="arrival_id" value="{{ $searchParams['arrival_id'] }}">
                                <input type="hidden" name="outbound_date" value="{{ $searchParams['outbound_date'] }}">
                                @if(isset($searchParams['return_date']))
                                <input type="hidden" name="return_date" value="{{ $searchParams['return_date'] }}">
                                @endif
                                <input type="hidden" name="partner_name" value="{{ $partner['name'] }}">
                                <input type="hidden" name="partner_url" value="{{ $partner['url'] }}">
                                @if(isset($searchParams['flight_number']))
                                <input type="hidden" name="flight_number" value="{{ $searchParams['flight_number'] }}">
                                @endif

                                {{-- Passenger Details --}}
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Adults (12+)') }}</label>
                                        <input type="number" name="adults" value="1" min="1" max="9" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Children (2-11)') }}</label>
                                        <input type="number" name="children" value="0" min="0" max="8" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Infants (0-2)') }}</label>
                                        <input type="number" name="infants" value="0" min="0" max="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Estimated Price (€)') }}</label>
                                        <input type="number" name="estimated_price" placeholder="Optional" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <input type="hidden" name="currency" value="EUR">
                                    </div>
                                </div>

                                {{-- Passenger Names --}}
                                <div id="passenger-names" class="space-y-3">
                                    <div class="passenger-input">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Passenger 1 Name') }} *</label>
                                        <input type="text" name="passenger_names[]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <input type="email" name="passenger_emails[]" placeholder="Email" class="w-full px-3 py-2 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <input type="tel" name="passenger_phones[]" placeholder="Phone" class="w-full px-3 py-2 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                                    </div>
                                </div>

                                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg">
                                    {{ __('Book with') }} {{ $partner['name'] }}
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Note --}}
                <div class="mt-8 bg-blue-50 border-l-4 border-blue-500 p-6 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <svg class="w-6 h-6 text-blue-500 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">{{ __('Important Note') }}</h3>
                            <p class="text-blue-800">
                                {{ __('You will be redirected to our partner\'s website to complete your booking and payment. We will track your reservation for follow-up.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Dynamically add passenger input fields based on number of passengers
        document.querySelectorAll('input[name="adults"], input[name="children"], input[name="infants"]').forEach(input => {
            input.addEventListener('input', updatePassengerFields);
        });

        function updatePassengerFields() {
            const adults = parseInt(document.querySelector('input[name="adults"]').value) || 0;
            const children = parseInt(document.querySelector('input[name="children"]').value) || 0;
            const infants = parseInt(document.querySelector('input[name="infants"]').value) || 0;
            const totalPassengers = adults + children + infants;

            const container = document.getElementById('passenger-names');
            container.innerHTML = '';

            for (let i = 1; i <= totalPassengers; i++) {
                const passengerType = i <= adults ? 'Adult' : (i <= adults + children ? 'Child' : 'Infant');
                const div = document.createElement('div');
                div.className = 'passenger-input';
                div.innerHTML = `
                    <label class="block text-sm font-medium text-gray-700 mb-1">Passenger ${i} Name (${passengerType}) *</label>
                    <input type="text" name="passenger_names[]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="email" name="passenger_emails[]" placeholder="Email" class="w-full px-3 py-2 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                    <input type="tel" name="passenger_phones[]" placeholder="Phone" class="w-full px-3 py-2 mt-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                `;
                container.appendChild(div);
            }
        }

        // Initialize with default values
        updatePassengerFields();
    </script>
@endsection

