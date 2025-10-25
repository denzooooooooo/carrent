{{-- Cette partielle attend $flights (tableau de vols) et $title (titre de la section) --}}
<h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-8 mb-4 border-b pb-2 border-gray-200 dark:border-gray-700">
    {{ $title }}
    <span class="text-lg font-normal text-purple-600 ml-2">({{ count($flights) }} options)</span>
</h2>

<div class="space-y-6">
    @foreach ($flights as $flight)
        @php
            // Extraction des données principales du vol
            $outboundItinerary = $flight['flights'][0] ?? null; // Le premier élément est l'aller
            $returnItinerary = $flight['flights'][1] ?? null; // Le second élément (si AR) est le retour
            $price = $flight['price'] ?? 'N/A';
            $airline = $flight['airline'] ?? 'Divers';
            $bookingLink = $flight['link'] ?? '#';
        @endphp

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl hover:shadow-2xl transition-all duration-300 p-6 border-l-4 border-purple-500 dark:border-purple-600">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-center">
                
                {{-- Informations sur les vols (Aller et Retour) --}}
                <div class="lg:col-span-3 space-y-4">
                    
                    {{-- Affichage de l'Aller --}}
                    @if ($outboundItinerary)
                        <x-flight-itinerary :itinerary="$outboundItinerary" type="outbound" />
                    @endif

                    {{-- Affichage du Retour (si Aller-Retour) --}}
                    @if ($returnItinerary)
                        <div class="border-t border-dashed border-gray-200 dark:border-gray-700 pt-4 mt-4">
                            <x-flight-itinerary :itinerary="$returnItinerary" type="return" />
                        </div>
                    @endif
                </div>

                {{-- Prix et Action --}}
                <div class="lg:col-span-1 text-center border-l lg:border-l-2 border-gray-100 dark:border-gray-700 pl-6 space-y-4">
                    <div class="text-4xl font-black text-purple-700 dark:text-purple-400">
                        {{ $price }} {{ $params['currency'] ?? 'USD' }}
                    </div>
                    <div class="text-sm font-semibold text-gray-600 dark:text-gray-300">
                        Compagnie(s): {{ $airline }}
                    </div>
                    <a href="{{ $bookingLink }}" target="_blank" rel="noopener noreferrer"
                       class="w-full inline-block py-3 px-6 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl shadow-lg hover:from-purple-700 hover:to-amber-700 transition duration-300 transform hover:scale-[1.02] text-lg">
                        Réserver
                    </a>
                    <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">Via Google Flights</p>
                </div>

            </div>
        </div>
    @endforeach
</div>