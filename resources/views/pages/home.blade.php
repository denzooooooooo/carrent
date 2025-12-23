@extends('layouts.app')

@section('title', __('Home - Carré Premium'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  {{-- Hero Carrousel responsive (nouveau) --}}
  @include('components.home-carousel')
  {{-- Texte de bienvenue --}}
  <section class="py-12 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">Bienvenue chez Carré Premium</h2>
      <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto">Découvrez nos packages touristiques sur-mesure, nos vols privés, et notre conciergerie 24/7 pour organiser vos expériences les plus exclusives. Nos conseillers sont à votre disposition pour personnaliser chaque détail.</br>
      <blockqote class="italic text-amber-600">"{{ __('Carré Premium notre limite, le reflet de notre imagination.') }}"</blockquote
      </p>
    </div>
  </section>


  {{-- Événements à la Une - Carrousel Dynamique --}}
  @include('components.events-carousel')

  {{-- Nos Services Premium --}}
  <section class="py-24 bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-12 md:mb-16">
        <div class="inline-flex items-center space-x-2 md:space-x-3 px-8 py-3 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-black mb-4 md:mb-6 border-2 border-white/30">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>{{ __('OUR PREMIUM SERVICES') }}</span>
        </div>
        <h2 class="text-5xl md:text-6xl font-black text-white mb-4 md:mb-6 leading-tight">
          {{ __('Excellence at Your Fingertips') }}
        </h2>
        <p class="text-xl text-white/90 max-w-3xl mx-auto">
          {{ __('Discover our complete range of premium services for unforgettable experiences') }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-6 md:gap-8">
        {{-- Service Cards --}}
        <a href="{{ route('flights.index') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-3 md:mb-4">{{ __('Private Flights') }}</h3>
            <p class="text-base text-white/80 mb-4 md:mb-6">{{ __('Private jets and helicopters for your exclusive travel') }}</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>{{ __('Discover') }}</span>
              <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>

        <a href="{{ route('events') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-16 h-16 bg-gradient-to-r from-amber-500 to-pink-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-3 md:mb-4">{{ __('VIP Events') }}</h3>
            <p class="text-base text-white/80 mb-4 md:mb-6">{{ __('Exclusive access to world sports and cultural events') }}</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>{{ __('Book') }}</span>
              <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>

        <a href="{{ route('packages') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-amber-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-3 md:mb-4">{{ __('Luxury Packages') }}</h3>
            <p class="text-base text-white/80 mb-4 md:mb-6">{{ __('Tailor-made experiences: safaris, yachting, exclusive tours') }}</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>{{ __('Explore') }}</span>
              <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>

        <a href="{{ route('contact') }}" class="group">
          <div class="bg-white/10 backdrop-blur-md rounded-2xl md:rounded-3xl p-8 hover:bg-white/20 transition-all duration-300 border border-white/20 hover:border-white/40">
            <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl flex items-center justify-center mb-4 md:mb-6 group-hover:scale-110 transition-transform">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
              </svg>
            </div>
            <h3 class="text-2xl font-black text-white mb-3 md:mb-4">{{ __('Concierge') }}</h3>
            <p class="text-base text-white/80 mb-4 md:mb-6">{{ __('24/7 service to organize your most exclusive desires') }}</p>
            <div class="flex items-center text-amber-400 font-semibold">
              <span>{{ __('Contact') }}</span>
              <svg class="w-5 h-5 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
              </svg>
            </div>
          </div>
        </a>
      </div>
    </div>
  </section>

  {{-- Véhicules de Luxe --}}
  <section class="py-24 bg-gradient-to-br from-amber-900 via-amber-800 to-purple-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12 md:mb-16">
        <div class="inline-flex items-center space-x-2 md:space-x-3 px-8 py-3 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-black mb-4 md:mb-6 border-2 border-white/30">
          <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span>{{ __('PREMIUM VEHICLE RENTAL') }}</span>
        </div>
        <h2 class="text-6xl md:text-7xl font-black text-white mb-4 md:mb-6 leading-tight">
          {{ __('Drive Excellence') }}
        </h2>
        <p class="text-2xl text-white/90 max-w-3xl mx-auto">
          {{ __('Quads • Luxury Motorcycles • Sports Cars • Premium 4x4') }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
        @php
          $vehicles = [
            ['icon' => 'motorcycle', 'title' => __('Premium Quads'), 'desc' => __('Rental with or without guide'), 'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=300&fit=crop'],
            ['icon' => 'car', 'title' => __('Sports Cars'), 'desc' => __('Ferrari, Lamborghini, Porsche'), 'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&h=300&fit=crop'],
            ['icon' => 'car', 'title' => __('Luxury 4x4'), 'desc' => __('Range Rover, G-Wagon'), 'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=400&h=300&fit=crop'],
            ['icon' => 'motorcycle', 'title' => __('Premium Motorcycles'), 'desc' => __('Harley, Ducati, BMW'), 'image' => 'https://images.unsplash.com/photo-1558981806-ec527fa84c39?w=400&h=300&fit=crop']
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

              <div class="absolute top-6 left-6 w-16 h-16 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-4 border-white/30">
                @if($vehicle['icon'] === 'motorcycle')
                  <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                @else
                  <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                @endif
              </div>
            </div>

            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
              <h3 class="text-2xl font-black mb-2 group-hover:text-amber-400 transition-colors">
                {{ $vehicle['title'] }}
              </h3>
              <p class="text-white/80 text-sm mb-4">{{ $vehicle['desc'] }}</p>

              <div class="flex items-center justify-between p-3 bg-white/10 backdrop-blur-md rounded-xl border border-white/20">
                <span class="font-bold text-base">{{ __('Available') }}</span>
                <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
  <section class="py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto text-center">
        <div class="inline-flex items-center justify-center space-x-2 md:space-x-3 mb-4 md:mb-6">
          <svg class="w-10 h-10 md:w-12 md:h-12 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 mb-4 md:mb-6">
          {{ __('Need a Flight?') }}
        </h2>
        <p class="text-xl text-gray-600 mb-6 md:mb-8">
          {{ __('We also offer international flight bookings') }}
        </p>
        <a
          href="{{ route('flights.index') }}"
          class="inline-flex items-center space-x-2 md:space-x-3 px-8 py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-xl text-base"
        >
          <span>{{ __('Search for a Flight') }}</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </a>
      </div>
    </div>
  </section>

  {{-- CTA Final --}}
  <section class="py-24 bg-gradient-to-br from-purple-900 via-purple-800 to-amber-900">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-5xl md:text-6xl font-black text-white mb-6 md:mb-8">
        {{ __('Ready for an Unforgettable Experience?') }}
      </h2>
      <p class="text-2xl text-white/90 mb-8 md:mb-12 max-w-3xl mx-auto">
        {{ __('Contact our concierge to create your tailor-made experience') }}
      </p>
      <div class="flex flex-wrap justify-center gap-4 md:gap-6">
        <a
          href="{{ route('events') }}"
          class="inline-flex items-center justify-center space-x-2 px-10 py-5 bg-white text-purple-900 font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>{{ __('VIP EVENTS') }}</span>
        </a>
        <a
          href="{{ route('packages') }}"
          class="inline-flex items-center justify-center space-x-2 px-10 py-5 bg-gradient-to-r from-amber-500 to-pink-500 text-white font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
          <span>{{ __('LUXURY PACKAGES') }}</span>
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
