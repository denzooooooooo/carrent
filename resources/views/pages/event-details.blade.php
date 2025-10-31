@extends('layouts.app')

@section('title', $event->title . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50">
  {{-- Hero Section --}}
  <section class="relative">
    <div class="h-80 sm:h-96 md:h-[500px] lg:h-[600px] relative overflow-hidden">
      @php
          $imageUrl = $event->getFirstMediaUrl('avatar', 'normal');
          $placeholder = 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1200&h=600&fit=crop';
      @endphp
      <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

      {{-- Floating Action Button --}}
      <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20">
        <button class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-all">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </button>
      </div>
    </div>

    {{-- Event Info Overlay --}}
    <div class="absolute bottom-0 left-0 right-0 z-10">
      <div class="container mx-auto px-4 pb-6 sm:pb-8">
        <div class="max-w-4xl">
          <div class="bg-white rounded-t-2xl sm:rounded-t-3xl shadow-2xl p-4 sm:p-6 md:p-8">
            {{-- Categories --}}
            <div class="flex flex-wrap gap-2 sm:gap-3 mb-3 sm:mb-4">
              <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-purple-100 text-purple-800 text-xs sm:text-sm font-semibold rounded-full">
                {{ $event->category->name_fr ?? 'Événement' }}
              </span>
              <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-amber-100 text-amber-800 text-xs sm:text-sm font-semibold rounded-full">
                {{ $event->type->name_fr ?? 'Spectacle' }}
              </span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4 leading-tight mt-4 sm:mt-0">
              {{ $event->title }}
            </h1>

            {{-- Date & Location --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="min-w-0">
                  <p class="text-xs sm:text-sm text-gray-500 font-medium">DATE & HEURE</p>
                  <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ \Carbon\Carbon::parse($event->event_date)->format('l d F Y') }}</p>
                  <p class="text-gray-600 text-sm">{{ $event->event_time }}</p>
                </div>
              </div>

              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div class="min-w-0">
                  <p class="text-xs sm:text-sm text-gray-500 font-medium">LIEU</p>
                  <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ $event->venue_name }}</p>
                  <p class="text-gray-600 text-sm">{{ $event->city }}, {{ $event->country }}</p>
                </div>
              </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
              <button class="flex-1 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold py-3 sm:py-4 px-4 sm:px-6 rounded-lg sm:rounded-xl hover:shadow-lg transition-all duration-300 text-center text-sm sm:text-base">
                Réserver maintenant
              </button>
              <button class="px-4 sm:px-6 py-3 sm:py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg sm:rounded-xl hover:border-purple-300 hover:text-purple-600 transition-all duration-300 text-sm sm:text-base">
                Partager
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Main Content --}}
  <div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
      {{-- Left Column --}}
      <div class="lg:col-span-2 space-y-6 sm:space-y-8">
        {{-- About Section --}}
        <section class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">À propos de l'événement</h2>
          <div class="prose prose-base sm:prose-lg max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($event->description_fr)) !!}
          </div>
        </section>

        {{-- Organizer Section --}}
        @if($event->organizer)
        <section class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Organisateur</h2>
          <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ $event->organizer }}</p>
              <p class="text-gray-600 text-sm">Organisateur officiel</p>
            </div>
          </div>
        </section>
        @endif

        {{-- Venue Details --}}
        <section class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Lieu de l'événement</h2>
          <div class="flex items-start space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">{{ $event->venue_name }}</h3>
              <p class="text-gray-600 mb-1 text-sm sm:text-base">{{ $event->venue_address }}</p>
              <p class="text-gray-600 text-sm sm:text-base">{{ $event->city }}, {{ $event->country }}</p>
            </div>
          </div>
        </section>
      </div>

      {{-- Right Column - Tickets --}}
      <div class="lg:col-span-1">
        <div class="sticky top-6 sm:top-8">
          <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Choisir vos places</h3>

            @if($event->seatZones->count() > 0)
              <div class="space-y-3 sm:space-y-4">
                @foreach($event->seatZones as $zone)
                  <div class="border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-4 hover:border-purple-300 hover:shadow-md transition-all duration-200">
                    <div class="flex justify-between items-start mb-2 sm:mb-3">
                      <div class="flex-1 min-w-0">
                        <h4 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ $zone->zone_name }}</h4>
                        <p class="text-xs sm:text-sm text-purple-600 font-medium">{{ $zone->zone_code }}</p>
                      </div>
                      <div class="text-right ml-2">
                        <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($zone->price)) }}</div>
                        <div class="text-xs text-gray-500">par personne</div>
                      </div>
                    </div>

                    @if($zone->description)
                      <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3 line-clamp-2">{{ $zone->description }}</p>
                    @endif

                    <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                      <span>{{ $zone->available_seats }} restantes</span>
                      <span class="text-xs">/ {{ $zone->total_seats }}</span>
                    </div>

                    @if($zone->available_seats > 0)
                      <button class="w-full bg-purple-600 text-white font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-purple-700 transition-colors text-sm sm:text-base">
                        Sélectionner
                      </button>
                    @else
                      <button class="w-full bg-gray-100 text-gray-400 font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg cursor-not-allowed text-sm sm:text-base" disabled>
                        Complet
                      </button>
                    @endif
                  </div>
                @endforeach
              </div>
            @else
              <div class="text-center py-6 sm:py-8">
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 font-medium text-sm sm:text-base">Billets bientôt disponibles</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-1">Revenez plus tard</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- CTA Section --}}
  <section class="bg-gradient-to-r from-purple-600 to-amber-600 py-12 sm:py-16">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4">Vous avez des questions ?</h2>
      <p class="text-lg sm:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
        Notre équipe est là pour vous aider à choisir les meilleures places.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="bg-white text-purple-600 font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl hover:shadow-lg transition-all duration-300 inline-block text-sm sm:text-base">
          Nous contacter
        </a>
        <a href="tel:+225XXXXXXXXX" class="border-2 border-white text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl hover:bg-white hover:text-purple-600 transition-all duration-300 inline-block text-sm sm:text-base">
          Appeler maintenant
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
