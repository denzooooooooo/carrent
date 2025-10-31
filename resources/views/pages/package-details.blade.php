@extends('layouts.app')

@section('title', $package->title . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50">
  {{-- Hero Section --}}
  <section class="relative">
    <div class="h-80 sm:h-96 md:h-[500px] lg:h-[600px] relative overflow-hidden">
      @php
          $imageUrl = $package->getFirstMediaUrl('avatar', 'normal');
          $placeholder = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1200&h=600&fit=crop';
      @endphp
      <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
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

    {{-- Package Info Overlay --}}
    <div class="absolute bottom-0 left-0 right-0 z-10">
      <div class="container mx-auto px-4 pb-6 sm:pb-8">
        <div class="max-w-4xl">
          <div class="bg-white rounded-t-2xl sm:rounded-t-3xl shadow-2xl p-4 sm:p-6 md:p-8">
            {{-- Categories --}}
            <div class="flex flex-wrap gap-2 sm:gap-3 mb-3 sm:mb-4">
              <span class="px-3 py-1 sm:px-4 sm:py-2 bg-purple-100 text-purple-800 text-xs sm:text-sm font-semibold rounded-full">
                {{ $package->category->name_fr ?? 'Package' }}
              </span>
              <span class="px-3 py-1 sm:px-4 sm:py-2 bg-amber-100 text-amber-800 text-xs sm:text-sm font-semibold rounded-full">
                {{ $package->package_type ?? 'Touristique' }}
              </span>
              @if($package->is_featured)
                <span class="px-3 py-1 sm:px-4 sm:py-2 bg-red-100 text-red-800 text-xs sm:text-sm font-semibold rounded-full">
                  ⭐ Featured
                </span>
              @endif
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4 leading-tight mt-2 sm:mt-0">
              {{ $package->title }}
            </h1>

            {{-- Duration & Destination --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs sm:text-sm text-gray-500 font-medium">DURÉE</p>
                  <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ $package->duration_text_fr ?? $package->duration . ' jours' }}</p>
                </div>
              </div>

              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div>
                  <p class="text-xs sm:text-sm text-gray-500 font-medium">DESTINATION</p>
                  <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ $package->destination }}</p>
                </div>
              </div>
            </div>

            {{-- Price & Action Buttons --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
              <div class="flex flex-col sm:flex-row sm:items-center gap-2 sm:gap-4">
                @if($package->discount_price)
                  <div>
                    <span class="text-2xl sm:text-3xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->discount_price)) }}</span>
                    <span class="text-sm sm:text-base text-gray-500 line-through ml-2">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->price)) }}</span>
                    <span class="text-xs sm:text-sm text-green-600 font-semibold ml-2">(-{{ round((1 - $package->discount_price / $package->price) * 100) }}%)</span>
                  </div>
                @else
                  <div>
                    <span class="text-2xl sm:text-3xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->price)) }}</span>
                    <span class="text-xs sm:text-sm text-gray-500 ml-1">par personne</span>
                  </div>
                @endif
              </div>
              <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                <button class="flex-1 sm:flex-none bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold py-3 px-4 sm:py-4 sm:px-6 rounded-xl hover:shadow-lg transition-all text-sm sm:text-base text-center">
                  Réserver maintenant
                </button>
                <button class="px-4 sm:px-6 py-3 sm:py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:border-purple-300 hover:text-purple-600 transition-all text-sm sm:text-base">
                  Demander un devis
                </button>
              </div>
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
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">À propos du package</h2>
          <div class="prose prose-lg max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($package->description_fr)) !!}
          </div>
        </section>

        {{-- Itinerary Section --}}
        @if($package->itinerary_fr && count($package->itinerary_fr) > 0)
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Itinéraire détaillé</h2>
          <div class="space-y-4 sm:space-y-6">
            @foreach($package->itinerary_fr as $index => $day)
              <div class="flex gap-3 sm:gap-4">
                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center">
                  <span class="text-sm sm:text-base font-bold text-purple-600">{{ $index + 1 }}</span>
                </div>
                <div class="flex-1">
                  <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">{{ $day['title'] ?? 'Jour ' . ($index + 1) }}</h3>
                  <p class="text-gray-600 text-sm sm:text-base leading-relaxed">{{ $day['description'] ?? '' }}</p>
                </div>
              </div>
            @endforeach
          </div>
        </section>
        @endif

        {{-- Included Services --}}
        @if($package->included_services_fr && count($package->included_services_fr) > 0)
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Services inclus</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            @foreach($package->included_services_fr as $service)
              <div class="flex items-center space-x-3">
                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                </div>
                <span class="text-gray-700 text-sm sm:text-base">{{ $service }}</span>
              </div>
            @endforeach
          </div>
        </section>
        @endif

        {{-- Excluded Services --}}
        @if($package->excluded_services_fr && count($package->excluded_services_fr) > 0)
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Non inclus</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            @foreach($package->excluded_services_fr as $service)
              <div class="flex items-center space-x-3">
                <div class="w-5 h-5 sm:w-6 sm:h-6 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-3 h-3 sm:w-4 sm:h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </div>
                <span class="text-gray-700 text-sm sm:text-base">{{ $service }}</span>
              </div>
            @endforeach
          </div>
        </section>
        @endif

        {{-- Gallery Section --}}
        @if($package->gallery && count($package->gallery) > 0)
        <section class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Galerie photos</h2>
          <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3 sm:gap-4">
            @foreach($package->gallery as $image)
              <div class="aspect-square rounded-lg overflow-hidden">
                <img src="{{ $image }}" alt="Galerie {{ $package->title }}" class="w-full h-full object-cover hover:scale-105 transition-transform">
              </div>
            @endforeach
          </div>
        </section>
        @endif
      </div>

      {{-- Right Column - Booking --}}
      <div class="lg:col-span-1">
        <div class="sticky top-6 sm:top-8">
          <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Réserver ce package</h3>

            {{-- Quick Info --}}
            <div class="space-y-3 sm:space-y-4 mb-4 sm:mb-6">
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600 text-sm sm:text-base">Durée</span>
                <span class="font-semibold text-gray-900 text-sm sm:text-base">{{ $package->duration_text_fr ?? $package->duration . ' jours' }}</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600 text-sm sm:text-base">Participants min/max</span>
                <span class="font-semibold text-gray-900 text-sm sm:text-base">{{ $package->min_participants }}-{{ $package->max_participants }}</span>
              </div>
              <div class="flex justify-between items-center py-2 border-b border-gray-100">
                <span class="text-gray-600 text-sm sm:text-base">Départ</span>
                <span class="font-semibold text-gray-900 text-sm sm:text-base">{{ $package->departure_city }}</span>
              </div>
            </div>

            {{-- Price Summary --}}
            <div class="bg-purple-50 rounded-lg p-3 sm:p-4 mb-4 sm:mb-6">
              <div class="flex justify-between items-center mb-2">
                <span class="text-gray-600 text-sm sm:text-base">Prix par personne</span>
                @if($package->discount_price)
                  <span class="font-bold text-purple-600 text-base sm:text-lg">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->discount_price)) }}</span>
                @else
                  <span class="font-bold text-purple-600 text-base sm:text-lg">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->price)) }}</span>
                @endif
              </div>
              @if($package->discount_price)
                <div class="flex justify-between items-center text-sm">
                  <span class="text-gray-500 line-through">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($package->price)) }}</span>
                  <span class="text-green-600 font-semibold">(-{{ round((1 - $package->discount_price / $package->price) * 100) }}%)</span>
                </div>
              @endif
            </div>

            {{-- Action Buttons --}}
            <div class="space-y-3">
              <button class="w-full bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold py-3 px-4 rounded-lg hover:shadow-lg transition-all text-sm sm:text-base">
                Réserver maintenant
              </button>
              <button class="w-full border-2 border-purple-300 text-purple-600 font-semibold py-3 px-4 rounded-lg hover:bg-purple-50 transition-all text-sm sm:text-base">
                Demander un devis personnalisé
              </button>
            </div>

            {{-- Contact Info --}}
            <div class="mt-4 sm:mt-6 pt-4 border-t border-gray-100">
              <p class="text-xs sm:text-sm text-gray-600 mb-2">Besoin d'aide ?</p>
              <div class="flex items-center space-x-2 text-sm">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                </svg>
                <a href="tel:+225XXXXXXXXX" class="text-purple-600 hover:text-purple-700 font-medium">+225 XX XX XX XX</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Similar Packages --}}
  @if($similarPackages->count() > 0)
  <section class="py-12 sm:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8 sm:mb-12 text-center">Packages similaires</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        @foreach($similarPackages as $similarPackage)
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all">
            <div class="relative">
              @php
                $similarImageUrl = $similarPackage->getFirstMediaUrl('avatar', 'small');
                $similarPlaceholder = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=250&fit=crop';
              @endphp
              <img src="{{ $similarImageUrl ?: $similarPlaceholder }}" alt="{{ $similarPackage->title }}" class="w-full h-40 sm:h-48 object-cover">
              @if($similarPackage->is_featured)
                <div class="absolute top-2 left-2">
                  <span class="px-2 py-1 bg-red-500 text-white text-xs font-bold rounded-full">⭐ Featured</span>
                </div>
              @endif
            </div>
            <div class="p-4 sm:p-6">
              <h3 class="text-lg sm:text-xl font-black mb-2 line-clamp-2">{{ $similarPackage->title }}</h3>
              <p class="text-gray-600 mb-3 sm:mb-4 text-sm line-clamp-3">{{ Str::limit($similarPackage->description_fr, 100) }}</p>
              <div class="flex items-center justify-between">
                <div>
                  @if($similarPackage->discount_price)
                    <span class="text-lg sm:text-xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($similarPackage->discount_price)) }}</span>
                    <span class="text-xs text-gray-500 line-through">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($similarPackage->price)) }}</span>
                  @else
                    <span class="text-lg sm:text-xl font-black text-purple-600">{{ \App\Helpers\CurrencyHelper::format(\App\Helpers\CurrencyHelper::convert($similarPackage->price)) }}</span>
                  @endif
                </div>
                <a href="{{ route('packages.show', $similarPackage->slug) }}" class="px-4 py-2 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition-all text-sm">
                  Voir détails
                </a>
              </div>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>
  @endif

  {{-- CTA Section --}}
  <section class="bg-gradient-to-r from-purple-600 to-amber-600 py-12 sm:py-16">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white mb-3 sm:mb-4">Vous avez des questions ?</h2>
      <p class="text-base sm:text-lg lg:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
        Notre équipe d'experts est là pour vous aider à choisir le package idéal pour votre voyage.
      </p>
      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="bg-white text-purple-600 font-bold py-3 px-6 sm:py-4 sm:px-8 rounded-xl hover:shadow-2xl transition-all text-sm sm:text-base">
          Nous contacter
        </a>
        <a href="tel:+225XXXXXXXXX" class="border-2 border-white text-white font-bold py-3 px-6 sm:py-4 sm:px-8 rounded-xl hover:bg-white hover:text-purple-600 transition-all text-sm sm:text-base">
          Appeler maintenant
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
