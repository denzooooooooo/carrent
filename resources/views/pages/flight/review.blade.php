@extends('layouts.app')

@section('title', __('Révision de la réservation'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Progress -->
        <div class="mb-8">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-blue-600">Étape 3/4</span>
                <span class="text-sm text-gray-600 dark:text-gray-400">Révision et paiement</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
                <div class="bg-blue-600 h-2 rounded-full" style="width: 75%"></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Détails du vol et passagers -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Détails du vol -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        Détails du vol
                    </h2>

                    @if(isset($offer['slices']))
                        @foreach($offer['slices'] as $index => $slice)
                        <div class="mb-6 pb-6 @if(!$loop->last) border-b border-gray-200 dark:border-gray-700 @endif">
                            <h3 class="font-semibold text-gray-900 dark:text-white mb-4">
                                @if($index == 0)
                                    Vol aller
                                @else
                                    Vol retour
                                @endif
                            </h3>

                            @foreach($slice['segments'] as $segment)
                            <div class="flex items-center justify-between mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <div class="flex items-center gap-4">
                                    @if(isset($segment['marketing_carrier']['logo_symbol_url']))
                                    <img src="{{ $segment['marketing_carrier']['logo_symbol_url'] }}" 
                                         alt="{{ $segment['marketing_carrier']['name'] }}" 
                                         class="w-12 h-12 object-contain">
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white">
                                            {{ $segment['marketing_carrier']['name'] ?? 'Compagnie' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $segment['marketing_carrier']['iata_code'] ?? '' }}{{ $segment['marketing_carrier_flight_number'] ?? '' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $segment['origin']['iata_code'] ?? '' }} → {{ $segment['destination']['iata_code'] ?? '' }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        {{ \Carbon\Carbon::parse($segment['departing_at'])->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endforeach
                    @endif
                </div>

                <!-- Passagers -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        Passagers ({{ count($passengers) }})
                    </h2>

                    @foreach($passengers as $index => $passenger)
                    <div class="mb-4 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ ucfirst($passenger['title'] ?? '') }} {{ $passenger['first_name'] ?? '' }} {{ $passenger['last_name'] ?? '' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ ucfirst($passenger['type'] ?? 'adult') }} • 
                                    Né(e) le {{ \Carbon\Carbon::parse($passenger['born_on'])->format('d/m/Y') }}
                                </p>
                            </div>
                            <div class="text-right text-sm text-gray-600 dark:text-gray-400">
                                <p>{{ $passenger['email'] ?? '' }}</p>
                                <p>{{ $passenger['phone'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Récapitulatif des prix -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4">
                        Récapitulatif
                    </h2>

                    <div class="space-y-3 mb-6">
                        @if(isset($offer['slices']) && count($offer['slices']) > 1)
                            <!-- Vol Aller-Retour -->
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Vol aller</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($price_eur / 2, 2) }} EUR
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-400">Vol retour</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ number_format($price_eur / 2, 2) }} EUR
                                </span>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-2 mt-2"></div>
                        @endif
                        
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Prix par personne</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ number_format($price_eur / count($passengers), 2) }} EUR
                            </span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Nombre de passagers</span>
                            <span class="font-medium text-gray-900 dark:text-white">{{ count($passengers) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Sous-total vols</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ number_format($price_eur, 2) }} EUR
                            </span>
                        </div>
                        @if(isset($offer['tax_amount']) && $offer['tax_amount'] > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600 dark:text-gray-400">Taxes et frais</span>
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ number_format($offer['tax_amount'], 2) }} EUR
                            </span>
                        </div>
                        @endif
                    </div>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-700 mb-6">
                        <div class="flex justify-between items-baseline mb-2">
                            <span class="text-lg font-bold text-gray-900 dark:text-white">TOTAL</span>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-blue-600">
                                    {{ number_format($price_xof, 0, ',', ' ') }} XOF
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ number_format($price_eur, 2) }} EUR
                                </p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment.process') }}" method="POST">
                        @csrf
                        
                        <!-- Données de l'offre -->
                        <input type="hidden" name="offer_id" value="{{ $offer['duffel_offer_id'] ?? '' }}">
                        <input type="hidden" name="total_price" value="{{ $price_xof }}">
                        <input type="hidden" name="base_price" value="{{ $price_eur }}">
                        <input type="hidden" name="currency" value="XOF">
                        
                        <!-- Données du vol -->
                        @if(isset($offer['slices'][0]['segments'][0]))
                            @php
                                $firstSegment = $offer['slices'][0]['segments'][0];
                                $lastSlice = end($offer['slices']);
                                $lastSegment = end($lastSlice['segments']);
                            @endphp
                            <input type="hidden" name="flight_number" value="{{ $firstSegment['marketing_carrier']['iata_code'] ?? '' }}{{ $firstSegment['marketing_carrier_flight_number'] ?? '' }}">
                            <input type="hidden" name="airline" value="{{ $firstSegment['marketing_carrier']['name'] ?? '' }}">
                            <input type="hidden" name="departure_airport" value="{{ $firstSegment['origin']['iata_code'] ?? '' }}">
                            <input type="hidden" name="arrival_airport" value="{{ $lastSegment['destination']['iata_code'] ?? '' }}">
                            <input type="hidden" name="departure_time" value="{{ $firstSegment['departing_at'] ?? '' }}">
                            <input type="hidden" name="arrival_time" value="{{ $lastSegment['arriving_at'] ?? '' }}">
                            <input type="hidden" name="departure_date" value="{{ \Carbon\Carbon::parse($firstSegment['departing_at'])->format('Y-m-d') }}">
                        @endif
                        
                        <input type="hidden" name="cabin_class" value="{{ $offer['cabin_class'] ?? 'ECONOMY' }}">
                        <input type="hidden" name="duration" value="{{ $offer['duration_formatted'] ?? '' }}">
                        <input type="hidden" name="stops" value="{{ $offer['stops'] ?? 0 }}">
                        
                        <!-- Passagers -->
                        @foreach($passengers as $index => $passenger)
                            <input type="hidden" name="passengers[{{ $index }}][first_name]" value="{{ $passenger['first_name'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][last_name]" value="{{ $passenger['last_name'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][type]" value="{{ $passenger['type'] ?? 'adult' }}">
                            <input type="hidden" name="passengers[{{ $index }}][title]" value="{{ $passenger['title'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][born_on]" value="{{ $passenger['born_on'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][email]" value="{{ $passenger['email'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][phone]" value="{{ $passenger['phone'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][gender]" value="{{ $passenger['gender'] ?? '' }}">
                            <input type="hidden" name="passengers[{{ $index }}][nationality]" value="{{ $passenger['nationality'] ?? '' }}">
                            @if(isset($passenger['identity_document_type']))
                                <input type="hidden" name="passengers[{{ $index }}][identity_document_type]" value="{{ $passenger['identity_document_type'] }}">
                                <input type="hidden" name="passengers[{{ $index }}][identity_document_number]" value="{{ $passenger['identity_document_number'] ?? '' }}">
                                <input type="hidden" name="passengers[{{ $index }}][identity_document_expiry]" value="{{ $passenger['identity_document_expiry'] ?? '' }}">
                                <input type="hidden" name="passengers[{{ $index }}][identity_document_issuing_country]" value="{{ $passenger['identity_document_issuing_country'] ?? '' }}">
                            @endif
                        @endforeach
                        
                        <!-- Compteurs passagers -->
                        @php
                            $adults = collect($passengers)->where('type', 'adult')->count();
                            $children = collect($passengers)->where('type', 'child')->count();
                            $infants = collect($passengers)->where('type', 'infant')->count();
                        @endphp
                        <input type="hidden" name="adults" value="{{ $adults }}">
                        <input type="hidden" name="children" value="{{ $children }}">
                        <input type="hidden" name="infants" value="{{ $infants }}">
                        
                        <button type="submit" 
                                class="w-full px-6 py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg transition-colors shadow-lg">
                            Procéder au paiement →
                        </button>
                    </form>

                    <div class="mt-4 text-center">
                        <a href="{{ route('flights.passengers', Session::get('selected_offer.offer_id')) }}" 
                           class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                            ← Modifier les informations
                        </a>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 mb-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Paiement sécurisé
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 mb-2">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Confirmation instantanée
                        </div>
                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Billets électroniques par email
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
