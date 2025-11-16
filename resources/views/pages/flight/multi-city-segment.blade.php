@extends('layouts.app')

@section('title', "Segment {{ $currentSegment + 1 }} - Multi-villes - Carré Premium")

@section('content')
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4">
            {{-- Progression --}}
            <div class="max-w-4xl mx-auto mb-6">
                <div class="flex items-center justify-between">
                    @foreach($multiCityData as $index => $segment)
                        <div class="flex items-center {{ $index > 0 ? 'flex-1' : '' }}">
                            @if($index > 0)
                                <div class="flex-1 h-1 {{ $index <= $currentSegment ? 'bg-green-400' : 'bg-purple-400' }}"></div>
                            @endif
                            
                            <div class="relative">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-lg
                                    {{ $index < $currentSegment ? 'bg-green-500' : ($index == $currentSegment ? 'bg-white text-purple-700' : 'bg-purple-400') }}">
                                    {{ $index < $currentSegment ? '✓' : $index + 1 }}
                                </div>
                                <div class="absolute top-14 left-1/2 transform -translate-x-1/2 whitespace-nowrap text-center">
                                    <div class="text-xs font-bold">{{ $segment['departure_id'] }} → {{ $segment['arrival_id'] }}</div>
                                    <div class="text-xs opacity-75">{{ \Carbon\Carbon::parse($segment['date'])->format('d/m') }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="text-center">
                <h1 class="text-3xl lg:text-4xl font-black mb-2">
                    ✈️ Segment {{ $currentSegment + 1 }} / {{ count($multiCityData) }}
                </h1>
                <p class="text-lg opacity-90">
                    {{ $multiCityData[$currentSegment]['departure_id'] }} → {{ $multiCityData[$currentSegment]['arrival_id'] }}
                    le {{ \Carbon\Carbon::parse($multiCityData[$currentSegment]['date'])->translatedFormat('D d M Y') }}
                </p>
            </div>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50 py-8">
        <div class="container mx-auto px-4">
            {{-- Résumé des segments précédents --}}
            @if(!empty($selectedSegments))
                <div class="bg-white rounded-2xl shadow-xl p-6 mb-6 border-2 border-green-300">
                    <h3 class="text-xl font-black text-gray-900 mb-4 flex items-center gap-2">
                        <span class="text-2xl">✅</span> Segments déjà sélectionnés
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($selectedSegments as $segment)
                            <div class="bg-gradient-to-r from-green-50 to-green-100 rounded-xl p-4 border border-green-300">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-sm font-bold text-green-800">Segment {{ $loop->iteration }}</span>
                                    <span class="text-lg font-black text-green-700">{{ number_format($segment['price']) }} {{ $searchParams['currency'] }}</span>
                                </div>
                                <div class="text-sm text-gray-700">
                                    <div class="font-bold">{{ $segment['departure'] }} → {{ $segment['arrival'] }}</div>
                                    <div class="text-xs">{{ $segment['airline'] ?? 'N/A' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-4 pt-4 border-t border-green-300 flex items-center justify-between">
                        <span class="text-lg font-bold text-gray-700">Total partiel</span>
                        <span class="text-3xl font-black text-green-700">{{ number_format($totalPrice) }} {{ $searchParams['currency'] }}</span>
                    </div>
                </div>
            @endif

            {{-- Options pour le segment actuel --}}
            <div class="bg-white rounded-2xl shadow-xl p-6 border-2 border-purple-200">
                <h3 class="text-2xl font-black text-gray-900 mb-6">
                    Choisissez votre vol pour ce segment
                </h3>

                {{-- Best flights --}}
                @if(!empty($results['best_flights']))
                    <div class="mb-8">
                        <h4 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="text-2xl">⭐</span> Meilleurs choix
                        </h4>
                        <div class="space-y-4">
                            @foreach($results['best_flights'] as $flight)
                                <x-multi-city-segment-card 
                                    :flight="$flight" 
                                    :searchParams="$searchParams"
                                    :currentSegment="$currentSegment"
                                    :selectedSegments="$selectedSegments"
                                    :multiCityData="$multiCityData"
                                    :isBest="true"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Other flights --}}
                @if(!empty($results['other_flights']))
                    <div>
                        <h4 class="text-lg font-bold text-gray-900 mb-4">
                            Autres options ({{ count($results['other_flights']) }})
                        </h4>
                        <div class="space-y-4">
                            @foreach($results['other_flights'] as $flight)
                                <x-multi-city-segment-card 
                                    :flight="$flight" 
                                    :searchParams="$searchParams"
                                    :currentSegment="$currentSegment"
                                    :selectedSegments="$selectedSegments"
                                    :multiCityData="$multiCityData"
                                    :isBest="false"
                                />
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection