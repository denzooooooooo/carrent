@extends('layouts.app')

@section('title', 'Accueil - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  {{-- Hero Carrousel avec icônes SVG professionnelles --}}
  @include('components.services-carousel')

  {{-- Événements à la Une --}}
  <section class="py-16 md:py-20 lg:py-24 bg-white relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
      <div class="absolute top-20 left-20 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-20 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-16">
        <div class="inline-flex items-center space-x-2 md:space-x-3 px-6 md:px-8 py-2 md:py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-xs md:text-sm font-black mb-4 md:mb-6 shadow-2xl animate-pulse">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>ÉVÉNEMENTS SPORTIFS & CULTURELS EXCLUSIFS</span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-gray-900 mb-4 md:mb-6 leading-tight">
          Événements à Ne Pas Manquer
        </h2>
        <p class="text-base sm:text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
          Accédez aux plus grands événements sportifs et culturels du monde
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-12">
        {{-- Événements simulés pour l'exemple --}}
        @for ($i = 0; $i < 8; $i++)
          <a
            href="{{ route('events') }}"
            class="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-700 hover:-translate-y-6"
          >
            <div class="aspect-[3/4] overflow-hidden relative">
              <img
                src="https://images.unsplash.com/photo-146189683{{ 6934 + $i }}-ffe607ba8211?w=400&h=600&fit=crop"
                alt="Événement Exclusif {{ $i + 1 }}"
                class="w-full h-full object-cover group-hover:scale-125 transition-all duration-1000"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black via-black/60 to-transparent"></div>

              <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000"></div>

              <div class="absolute top-4 right-4 px-4 py-2 bg-gradient-to-r from-amber-500 to-pink-500 rounded-full text-xs font-black uppercase text-white shadow-2xl">
                VIP
              </div>

              @if($i < 2)
                <div class="absolute top-4 left-4 inline-flex items-center space-x-1 px-3 py-1 bg-red-500 rounded-full text-xs font-black text-white shadow-xl animate-pulse">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                  </svg>
                  <span>HOT</span>
                </div>
              @endif
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
              <h3 class="text-xl font-black mb-3 group-hover:text-amber-400 transition-colors line-clamp-2">
                Événement Exclusif {{ $i + 1 }}
              </h3>

              <div class="space-y-2 mb-4">
                <div class="inline-flex items-center space-x-2 text-sm bg-white/10 backdrop-blur-md rounded-full px-3 py-2">
                  <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  <span className="font-semibold">Date à venir</span>
                </div>
              </div>

              <div class="flex items-center justify-between p-3 bg-gradient-to-r from-amber-500/30 to-pink-500/30 backdrop-blur-md rounded-2xl border border-amber-400/50">
                <span class="text-lg font-black bg-gradient-to-r from-amber-300 to-pink-300 bg-clip-text text-transparent">
                  Sur demande
                </span>
                <svg class="w-5 h-5 text-amber-400 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </div>
            </div>
          </a>
        @endfor
      </div>

      <div class="text-center">
        <a
          href="{{ route('events') }}"
          class="inline-flex items-center space-x-3 px-12 py-6 bg-gradient-to-r from-amber-500 via-pink-500 to-purple-500 text-white font-black text-xl rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <span>VOIR TOUS LES ÉVÉNEMENTS</span>
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
          </svg>
        </a>
      </div>
    </div>
  </section>

  {{-- Nos Services Premium --}}
  <section class="py-24 bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-12 md:mb-16">
        <div class="inline-flex items-center space-x-2 md:space-x-3 px-6 md:px-8 py-2 md:py-3 bg-white/20 backdrop-blur-md text-white rounded-full text-xs md:text-sm font-black mb-4 md:mb-6 border-2 border-white/30">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>NOS SERVICES PREMIUM</span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 md:mb-6 leading-tight">
          L'Excellence à Votre Portée
        </h2>
        <p class="text-base sm:text-lg md:text-xl text-white/90 max-w-3xl mx-auto">
          Découvrez notre gamme complète de services premium pour des expériences inoubliables
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        {{-- Service Cards --}}
        <a href="{{ route('flights') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-6 md:p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white mb-3 md:mb-4">Vols Privés</h3>
            <p class="text-sm md:text-base text-white/80 mb-4 md:mb-6">Jets privés et hélicoptères pour vos déplacements exclusifs</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>Découvrir</span>
              <svg class="w-4 h-4 md:w-5 md:h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>

        <a href="{{ route('events') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-6 md:p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-amber-500 to-pink-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white mb-3 md:mb-4">Événements VIP</h3>
            <p class="text-sm md:text-base text-white/80 mb-4 md:mb-6">Accès exclusif aux événements sportifs et culturels mondiaux</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>Réserver</span>
              <svg class="w-4 h-4 md:w-5 md:h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>

        <a href="{{ route('packages') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-6 md:p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-purple-500 to-amber-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white mb-3 md:mb-4">Packages Luxe</h3>
            <p class="text-sm md:text-base text-white/80 mb-4 md:mb-6">Expériences sur mesure : safaris, yachting, circuits exclusifs</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>Explorer</span>
              <svg class="w-4 h-4 md:w-5 md:h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>

        <a href="{{ route('contact') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-6 md:p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-12 h-12 md:w-16 md:h-16 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-6 h-6 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <h3 class="text-xl md:text-2xl font-black text-white mb-3 md:mb-4">Conciergerie</h3>
            <p class="text-sm md:text-base text-white/80 mb-4 md:mb-6">Service 24/7 pour organiser vos désirs les plus exclusifs</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>Contacter</span>
              <svg class="w-4 h-4 md:w-5 md:h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  {{-- Véhicules de Luxe --}}
  <section class="py-16 md:py-20 lg:py-24 bg-gradient-to-br from-amber-900 via-amber-800 to-purple-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12 md:mb-16">
        <div class="inline-flex items-center space-x-2 md:space-x-3 px-6 md:px-8 py-2 md:py-3 bg-white/20 backdrop-blur-md text-white rounded-full text-xs md:text-sm font-black mb-4 md:mb-6 border-2 border-white/30">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span>LOCATION DE VÉHICULES PREMIUM</span>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-black text-white mb-4 md:mb-6 leading-tight">
          Conduisez l'Excellence
        </h2>
        <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-white/90 max-w-3xl mx-auto">
          Quads • Motos de Luxe • Voitures de Sport • 4x4 Premium
        </p>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        @php
          $vehicles = [
            ['icon' => 'motorcycle', 'title' => 'Quads Premium', 'desc' => 'Location avec ou sans guide', 'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop'],
            ['icon' => 'car', 'title' => 'Voitures de Sport', 'desc' => 'Ferrari, Lamborghini, Porsche', 'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&h=300&fit=crop'],
            ['icon' => 'car', 'title' => '4x4 de Luxe', 'desc' => 'Range Rover, G-Wagon', 'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=400&h=300&fit=crop'],
            ['icon' => 'motorcycle', 'title' => 'Motos Premium', 'desc' => 'Harley, Ducati, BMW', 'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=300&fit=crop']
          ];
        @endphp

        @foreach($vehicles as $vehicle)
          <a
            href="{{ route('packages') }}"
            class="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-500 hover:-translate-y-4"
          >
            <div class="aspect-square overflow-hidden relative">
              <img
                src="{{ $vehicle['image'] }}"
                alt="{{ $vehicle['title'] }}"
                class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black via-black/50 to-transparent"></div>

              <div class="absolute top-4 md:top-6 left-4 md:left-6 w-12 h-12 md:w-16 md:h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-4 border-white/30">
                @if($vehicle['icon'] === 'motorcycle')
                  <svg class="w-6 h-6 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                @else
                  <svg class="w-6 h-6 md:w-10 md:h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                @endif
              </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-4 md:p-6 text-white">
              <h3 class="text-lg md:text-2xl font-black mb-1 md:mb-2 group-hover:text-amber-400 transition-colors">
                {{ $vehicle['title'] }}
              </h3>
              <p class="text-white/80 text-xs md:text-sm mb-3 md:mb-4">{{ $vehicle['desc'] }}</p>

              <div class="flex items-center justify-between p-2 md:p-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/20">
                <span class="font-bold text-sm md:text-base">Disponible</span>
                <svg class="w-4 h-4 md:w-5 md:h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Flights Section --}}
  <section class="py-16 md:py-20 lg:py-24 bg-white">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto text-center">
        <div class="inline-flex items-center justify-center space-x-2 md:space-x-3 mb-4 md:mb-6">
          <svg class="w-10 h-10 md:w-12 md:h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </div>
        <h2 class="text-3xl sm:text-4xl md:text-5xl font-black text-gray-900 mb-4 md:mb-6">
          Besoin d'un Vol ?
        </h2>
        <p class="text-base sm:text-lg md:text-xl text-gray-600 mb-6 md:mb-8">
          Nous proposons également la réservation de vols internationaux
        </p>
        <a
          href="{{ route('flights') }}"
          class="inline-flex items-center space-x-2 md:space-x-3 px-6 md:px-8 py-3 md:py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-xl text-sm md:text-base"
        >
          <span>Rechercher un Vol</span>
          <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </a>
      </div>
    </div>
  </section>

  {{-- CTA Final --}}
  <section class="py-16 md:py-20 lg:py-24 bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white mb-6 md:mb-8">
        Prêt pour une Expérience Inoubliable ?
      </h2>
      <p class="text-base sm:text-lg md:text-xl lg:text-2xl text-white/90 mb-8 md:mb-12 max-w-3xl mx-auto">
        Contactez notre conciergerie pour créer votre expérience sur mesure
      </p>
      <div class="flex flex-col sm:flex-row justify-center gap-4 md:gap-6">
        <a
          href="{{ route('events') }}"
          class="inline-flex items-center justify-center space-x-2 px-6 md:px-10 py-3 md:py-5 bg-white text-purple-900 font-black text-base md:text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>ÉVÉNEMENTS VIP</span>
        </a>
        <a
          href="{{ route('packages') }}"
          class="inline-flex items-center justify-center space-x-2 px-6 md:px-10 py-3 md:py-5 bg-gradient-to-r from-amber-500 to-pink-500 text-white font-black text-base md:text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
          <span>PACKAGES LUXE</span>
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
