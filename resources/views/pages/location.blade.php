@extends('layouts.app')

@section('title', __('Location de véhicules - Quads, voitures et plus') . ' - Carré Premium')
@section('meta_description', 'Découvrez nos locations de véhicules en Côte d\'Ivoire et Afrique. Quads, voitures, bateaux et véhicules premium avec Carré Premium.')
@section('meta_keywords', 'location véhicules, quads, voitures luxe, bateaux, Côte d\'Ivoire, Afrique, véhicules premium, Carré Premium')
@section('og_title', __('Location de véhicules - Quads, voitures et plus') . ' - Carré Premium')
@section('og_description', 'Réservez vos véhicules en Côte d\'Ivoire. Quads, voitures, bateaux et véhicules exclusifs avec notre service de conciergerie privée.')

@section('content')

<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-blue-600 to-cyan-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 md:mb-4">{{ __('Vehicule de location') }}</h1>
      <p class="text-base sm:text-lg md:text-xl text-white/90">{{ __('Decouvrez nos offres de vehicule premium') }}</p>
    </div>
  </section>

  {{-- Filters --}}
  <section class="py-6 md:py-8 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        <form method="GET" action="{{ route('location') }}" class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
            <div>
              <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('Categories') }}</label>
              <select name="category" class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:border-blue-600 focus:outline-none text-sm md:text-base">
                <option value="">{{ __('toutes les categories') }}</option>
                <option value="terrestre" {{ request('category') == 'terrestre' ? 'selected' : '' }}>{{ __('Terrestrial') }}</option>
              </select>
            </div>
            <div>
              <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">{{ __('Vehicle Type') }}</label>
              <select name="type" class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:border-blue-600 focus:outline-none text-sm md:text-base">
                <option value="">{{ __('Tout type') }}</option>
                <option value="voiture" {{ request('type') == 'voiture' ? 'selected' : '' }}>{{ __('Car') }}</option>
              </select>
            </div>
            <div class="flex items-end gap-2">
              <button type="submit" class="flex-1 px-4 md:px-6 py-2 md:py-3 bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold rounded-lg md:rounded-xl hover:shadow-lg transition-all text-sm md:text-base">
                {{ __('Search') }}
              </button>
              @if(request()->hasAny(['category', 'type']))
                <a href="{{ route('location') }}" class="px-3 md:px-4 py-2 md:py-3 bg-gray-200 text-gray-700 font-medium rounded-lg md:rounded-xl hover:bg-gray-300 transition-all text-sm md:text-base">
                  ✕
                </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  {{-- Locations Grid --}}
  <section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        @if($locations->count() > 0)
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
            @foreach($locations as $location)
              <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
                <div class="relative">
                  @php
                    $imageUrl = $location->image_url;
                    $placeholder = 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=500&h=300&fit=crop';
                  @endphp
                  <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $location->name }}" class="w-full h-32 md:h-40 lg:h-48 object-cover">
                  <div class="absolute top-2 md:top-4 left-2 md:left-4">
                    <span class="px-2 md:px-3 py-1 bg-green-500 text-white text-xs md:text-sm font-bold rounded-full">{{ ucfirst($location->category) }}</span>
                  </div>
                  <div class="absolute top-2 md:top-4 right-2 md:right-4">
                    <span class="px-2 md:px-3 py-1 bg-teal-600 text-white text-xs md:text-sm font-bold rounded-full">{{ ucfirst($location->type) }}</span>
                  </div>
                </div>
                <div class="p-4 md:p-6">
                  <h3 class="text-lg md:text-xl font-black mb-2 line-clamp-2">{{ $location->name }}</h3>
                  <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base line-clamp-3">{{ Str::limit($location->description, 120) }}</p>
                  <div class="flex items-center text-gray-600 mb-2 md:mb-3">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm md:text-base">{{ $location->capacity }} {{ __('passengers') }}</span>
                  </div>
                  <div class="flex items-center text-gray-600 mb-3 md:mb-4">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                    <span class="text-sm md:text-base">{{ \App\Helpers\CurrencyHelper::format($location->price_per_day) }} / {{ __('day') }}</span>
                  </div>
                  @if($location->features && count($location->features) > 0)
                    <div class="flex flex-wrap gap-1 mb-3 md:mb-4">
                      @foreach(array_slice($location->features, 0, 3) as $feature)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">{{ $feature }}</span>
                      @endforeach
                      @if(count($location->features) > 3)
                        <span class="px-2 py-1 bg-gray-100 text-gray-700 text-xs rounded-full">+{{ count($location->features) - 3 }}</span>
                      @endif
                    </div>
                  @endif
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                      <span class="text-xl md:text-2xl font-black text-green-600">{{ \App\Helpers\CurrencyHelper::format($location->price_per_day) }}</span>
                      <span class="text-xs md:text-sm text-gray-500 ml-1 md:ml-2">par jour</span>
                    </div>
                    <a href="{{ route('location.show', $location) }}" class="px-4 md:px-6 py-2 bg-gradient-to-r from-green-600 to-teal-600 text-white font-bold rounded-lg md:rounded-xl hover:shadow-lg transition-all text-sm md:text-base text-center">
                      {{ __('Voir les détails') }}
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ __('No vehicles available') }}</h3>
            <p class="text-gray-500">{{ __('Come back soon to discover our new premium vehicles.') }}</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-12 md:py-16 bg-gradient-to-r from-green-600 to-teal-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-3 md:mb-4">{{ __('Besoin de vehicule personnalisé ?') }}</h2>
      <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 md:mb-8">{{ __('Contactez notre équipe pour plus de details') }}</p>
      <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-white text-green-600 font-bold rounded-lg md:rounded-xl hover:shadow-2xl transition-all text-sm md:text-base">
          {{ __('Request a quote') }}
        </a>
        <a href="tel:+225XXXXXXXXX" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg md:rounded-xl hover:bg-white hover:text-green-600 transition-all text-sm md:text-base">
          {{ __('Call Now') }}
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
