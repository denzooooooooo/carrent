@extends('layouts.app')

@section('title', 'Choisir votre vol retour - Carré Premium')

@section('content')
    <div class="bg-gradient-to-br from-purple-600 to-purple-700 text-white py-8">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-3xl lg:text-4xl font-black mb-2">✈️ Choisissez votre vol retour</h1>
            <p class="text-lg opacity-90">Prix aller sélectionné : {{ number_format($outboundPrice) }} {{ $currency }}</p>
        </div>
    </div>

    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-amber-50">
        <div class="container mx-auto px-4 py-6">
            @if(!empty($results['best_flights']))
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Meilleurs vols retour</h2>
                    <div class="space-y-4">
                        @foreach($results['best_flights'] as $flight)
                            <x-return-flight-card 
                                :flight="$flight" 
                                :outboundPrice="$outboundPrice" 
                                :currency="$currency" 
                                :searchParams="$searchParams" />
                        @endforeach
                    </div>
                </div>
            @endif

            @if(!empty($results['other_flights']))
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Autres vols retour</h2>
                    <div class="space-y-4">
                        @foreach($results['other_flights'] as $flight)
                            <x-return-flight-card 
                                :flight="$flight" 
                                :outboundPrice="$outboundPrice" 
                                :currency="$currency" 
                                :searchParams="$searchParams" />
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection