@props(['itinerary', 'type'])

@php
    // S'assurer que les données sont présentes pour éviter des erreurs
    if (!isset($itinerary['segments']) || empty($itinerary['segments'])) {
        return;
    }
    
    $segments = $itinerary['segments'];
    $firstSegment = $segments[0];
    $lastSegment = $segments[count($segments) - 1];
    
    $departureTime = date('H:i', strtotime($firstSegment['departure']['at']));
    $departureCode = $firstSegment['departure']['iataCode'];
    
    $arrivalTime = date('H:i', strtotime($lastSegment['arrival']['at']));
    $arrivalCode = $lastSegment['arrival']['iataCode'];

    $durationInMinutes = $itinerary['duration'] ?? 0;
    $hours = floor($durationInMinutes / 60);
    $minutes = $durationInMinutes % 60;
    $durationFormatted = ($hours > 0 ? "{$hours}h" : '') . ($minutes > 0 ? "{$minutes}m" : '');

    $stopCount = count($segments) - 1;
    $stopsText = $stopCount === 0 ? 'Direct' : ($stopCount === 1 ? '1 escale' : "{$stopCount} escales");
    $stopsColor = $stopCount === 0 ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';
@endphp

<div class="flex items-center space-x-4">
    <div class="w-16 text-center flex-shrink-0">
        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">{{ $type === 'outbound' ? 'Aller' : 'Retour' }}</span>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $departureTime }}</div>
        <div class="text-lg font-bold text-purple-600">{{ $departureCode }}</div>
    </div>

    {{-- Ligne de Vol avec Infos --}}
    <div class="flex-1">
        <div class="flex items-center justify-between">
            <div class="w-2 h-2 rounded-full {{ $type === 'outbound' ? 'bg-purple-600' : 'bg-amber-600' }}"></div>
            <div class="flex-1 h-0.5 border-t border-dashed border-gray-300 dark:border-gray-600 relative">
                <span class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 {{ $stopsColor }} text-xs font-semibold px-3 py-1 rounded-full whitespace-nowrap shadow-sm">
                    {{ $stopsText }}
                </span>
                <svg class="absolute -top-2 right-0 w-6 h-6 text-gray-500 transform -translate-y-1/2 rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                </svg>
            </div>
            <div class="w-2 h-2 rounded-full {{ $type === 'outbound' ? 'bg-amber-600' : 'bg-purple-600' }}"></div>
        </div>
        <div class="text-sm font-semibold text-center text-gray-600 dark:text-gray-300 mt-2">
            Durée: **{{ $durationFormatted }}**
        </div>
    </div>

    <div class="w-16 text-center flex-shrink-0">
        <span class="text-xs font-semibold uppercase text-gray-500 dark:text-gray-400">Arrivée</span>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $arrivalTime }}</div>
        <div class="text-lg font-bold text-amber-600">{{ $arrivalCode }}</div>
    </div>
</div>