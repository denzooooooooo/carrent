@extends('layouts.app')

@section('title', __('Détails du vol'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="container mx-auto px-4 max-w-5xl">
        <div class="mb-6">
            <a href="{{ route('flights.results') }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour aux résultats
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 md:p-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">Détails du vol</h1>

            <!-- Prix -->
            <div class="mb-8 p-6 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Prix total</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">
                            {{ number_format($offer['total_amount'] * $exchange_rate, 0, ',', ' ') }} XOF
                        </p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            {{ number_format($offer['total_amount'], 2) }} EUR
                        </p>
                    </div>
                    <a href="{{ route('flights.passengers', $offer_id) }}" 
                       class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Réserver
                    </a>
                </div>
            </div>

            <!-- Itinéraire -->
            @foreach($offer['slices'] as $sliceIndex => $slice)
            <div class="mb-8">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                    {{ $sliceIndex == 0 ? 'Vol aller' : 'Vol retour' }}
                </h2>

                <div class="space-y-4">
                    @foreach($slice['segments'] as $segmentIndex => $segment)
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                        <!-- Compagnie -->
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded flex items-center justify-center">
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-400">
                                    {{ $segment['marketing_carrier']['iata_code'] ?? '' }}
                                </span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">
                                    {{ $segment['marketing_carrier']['name'] ?? '' }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Vol {{ $segment['marketing_carrier']['iata_code'] ?? '' }}{{ $segment['marketing_carrier_flight_number'] ?? '' }}
                                </p>
                            </div>
                        </div>

                        <!-- Timeline -->
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0 w-24 text-right">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($segment['departing_at'])->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($segment['departing_at'])->format('d M') }}
                                </p>
                            </div>

                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $segment['origin']['iata_code'] ?? '' }} - {{ $segment['origin']['name'] ?? '' }}
                                    </p>
                                </div>
                                
                                <div class="ml-1.5 border-l-2 border-gray-300 dark:border-gray-600 pl-4 py-2">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Durée: {{ $segment['duration'] ?? '' }} • {{ $segment['aircraft']['name'] ?? '' }}
                                    </p>
                                </div>

                                <div class="flex items-center gap-2">
                                    <div class="w-3 h-3 rounded-full bg-blue-600"></div>
                                    <p class="font-medium text-gray-900 dark:text-white">
                                        {{ $segment['destination']['iata_code'] ?? '' }} - {{ $segment['destination']['name'] ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex-shrink-0 w-24">
                                <p class="text-lg font-bold text-gray-900 dark:text-white">
                                    {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('H:i') }}
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($segment['arriving_at'])->format('d M') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    @if(!$loop->last)
                    <div class="flex items-center justify-center py-2">
                        <div class="px-4 py-2 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg text-sm text-yellow-800 dark:text-yellow-200">
                            Escale
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            @endforeach

            <!-- Informations supplémentaires -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Classe de cabine</h3>
                    <p class="text-gray-700 dark:text-gray-300">{{ $offer['cabin_class'] }}</p>
                </div>

                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Conditions</h3>
                    <div class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                        @if(isset($offer['conditions']['change_before_departure']))
                        <p>✓ Modification possible</p>
                        @endif
                        @if(isset($offer['conditions']['refund_before_departure']))
                        <p>✓ Remboursement possible</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action -->
            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                <a href="{{ route('flights.passengers', $offer_id) }}" 
                   class="px-8 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Continuer la réservation →
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
