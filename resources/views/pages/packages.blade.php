@extends('layouts.app')

@section('title', 'Packages Touristiques - Carré Premium')

@section('content')


<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 md:mb-4">Packages Touristiques</h1>
      <p class="text-base sm:text-lg md:text-xl text-white/90">Découvrez des expériences uniques avec nos packages exclusifs</p>
    </div>
  </section>

  {{-- Filters --}}
  <section class="py-6 md:py-8 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        <form method="GET" action="{{ route('packages') }}" class="bg-white rounded-xl md:rounded-2xl shadow-lg p-4 md:p-6">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4">
            <div>
              <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Type de package</label>
              <select name="type" class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:border-purple-600 focus:outline-none text-sm md:text-base">
                <option value="">Tous les packages</option>
                @foreach($packageTypes as $type)
                  <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                    {{ $type }}
                  </option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Destination</label>
              <select name="destination" class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:border-purple-600 focus:outline-none text-sm md:text-base">
                <option value="">Toutes les destinations</option>
                @foreach($destinations as $destination)
                  <option value="{{ $destination }}" {{ request('destination') == $destination ? 'selected' : '' }}>
                    {{ $destination }}
                  </option>
                @endforeach
              </select>
            </div>
            <div>
              <label class="block text-xs md:text-sm font-medium text-gray-700 mb-1 md:mb-2">Durée</label>
              <select name="duration" class="w-full px-3 md:px-4 py-2 md:py-3 border border-gray-300 rounded-lg md:rounded-xl focus:border-purple-600 focus:outline-none text-sm md:text-base">
                <option value="">Toutes les durées</option>
                <option value="1-3" {{ request('duration') == '1-3' ? 'selected' : '' }}>1-3 jours</option>
                <option value="4-7" {{ request('duration') == '4-7' ? 'selected' : '' }}>4-7 jours</option>
                <option value="1-2-weeks" {{ request('duration') == '1-2-weeks' ? 'selected' : '' }}>1-2 semaines</option>
                <option value="more-than-2-weeks" {{ request('duration') == 'more-than-2-weeks' ? 'selected' : '' }}>Plus de 2 semaines</option>
              </select>
            </div>
            <div class="flex items-end gap-2">
              <button type="submit" class="flex-1 px-4 md:px-6 py-2 md:py-3 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-lg md:rounded-xl hover:shadow-lg transition-all text-sm md:text-base">
                Rechercher
              </button>
              @if(request()->hasAny(['type', 'destination', 'duration']))
                <a href="{{ route('packages') }}" class="px-3 md:px-4 py-2 md:py-3 bg-gray-200 text-gray-700 font-medium rounded-lg md:rounded-xl hover:bg-gray-300 transition-all text-sm md:text-base">
                  ✕
                </a>
              @endif
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>

  {{-- Packages Grid --}}
  <section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        @if($packages->count() > 0)
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6 lg:gap-8">
            @foreach($packages as $package)
              <div class="bg-white rounded-2xl md:rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
                <div class="relative">
                  @php
                    $imageUrl = $package->getFirstMediaUrl('avatar', 'normal');
                    $placeholder = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=500&h=300&fit=crop';
                  @endphp
                  <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $package->title }}" class="w-full h-32 md:h-40 lg:h-48 object-cover">
                  @if($package->is_featured)
                    <div class="absolute top-2 md:top-4 left-2 md:left-4">
                      <span class="px-2 md:px-3 py-1 bg-red-500 text-white text-xs md:text-sm font-bold rounded-full">⭐ Featured</span>
                    </div>
                  @endif
                  <div class="absolute top-2 md:top-4 right-2 md:right-4">
                    <span class="px-2 md:px-3 py-1 bg-purple-600 text-white text-xs md:text-sm font-bold rounded-full">{{ $package->package_type ?? 'Tour' }}</span>
                  </div>
                </div>
                <div class="p-4 md:p-6">
                  <h3 class="text-lg md:text-xl font-black mb-2 line-clamp-2">{{ $package->title }}</h3>
                  <p class="text-gray-600 mb-3 md:mb-4 text-sm md:text-base line-clamp-3">{{ Str::limit($package->description_fr, 120) }}</p>
                  <div class="flex items-center text-gray-600 mb-2 md:mb-3">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="text-sm md:text-base">{{ $package->duration_text_fr ?? $package->duration . ' jours' }}</span>
                  </div>
                  <div class="flex items-center text-gray-600 mb-3 md:mb-4">
                    <svg class="w-4 h-4 md:w-5 md:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span class="text-sm md:text-base">{{ $package->destination }}</span>
                  </div>
                  <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                      @if($package->discount_price)
                        <span class="text-xl md:text-2xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->discount_price)) }}</span>
                        <span class="text-xs md:text-sm text-gray-500 line-through ml-1 md:ml-2">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->price)) }}</span>
                      @else
                        <span class="text-xl md:text-2xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->price)) }}</span>
                      @endif
                      <span class="text-xs md:text-sm text-gray-500 ml-1 md:ml-2">par personne</span>
                    </div>
                    <a href="{{ route('packages.show', $package->slug) }}" class="px-4 md:px-6 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-lg md:rounded-xl hover:shadow-lg transition-all text-sm md:text-base text-center">
                      Voir détails
                    </a>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
        @else
          <div class="text-center py-12">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Aucun package disponible</h3>
            <p class="text-gray-500">Revenez bientôt pour découvrir nos nouvelles offres touristiques.</p>
          </div>
        @endif
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-12 md:py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-3 md:mb-4">Un package sur mesure ?</h2>
      <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 md:mb-8">Contactez notre équipe pour créer l'expérience de vos rêves</p>
      <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-white text-purple-600 font-bold rounded-lg md:rounded-xl hover:shadow-2xl transition-all text-sm md:text-base">
          Demander un devis
        </a>
        <a href="tel:+225XXXXXXXXX" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg md:rounded-xl hover:bg-white hover:text-purple-600 transition-all text-sm md:text-base">
          Appeler Maintenant
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
