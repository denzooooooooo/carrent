@extends('layouts.app')

@section('title', __('Home - Carré Premium'))

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  {{-- Hero Carrousel responsive (nouveau) --}}
  @include('components.home-carousel')
  {{-- Texte de bienvenue --}}
  <section class="py-12 bg-white dark:bg-gray-900">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 dark:text-white mb-4">{{ __('Welcome to Carré Premium') }}</h2>
      <p class="text-lg text-gray-700 dark:text-gray-300 max-w-3xl mx-auto">{{ __('Discover our tailor-made tourist packages, private flights, and 24/7 concierge service to organize your most exclusive experiences. Our advisors are at your disposal to personalize every detail.') }}</br>
      <blockquote class="italic text-amber-600">"{{ __('Carré Premium, our limit is the reflection of our imagination.') }}"</blockquote>
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
          {{ __('Private Jets • Luxury Cars • Sports Cars • Premium 4x4') }}
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-4 gap-4 md:gap-6">
        @php
          $vehicles = [
            ['icon' => 'motorcycle', 'title' => __('Premium Vehicles'), 'desc' => __('Rental with or without guide'), 'image' => 'https://i.pinimg.com/736x/c0/7a/ca/c07acad260e24f8cd0868d5d9c6169b5.jpg'],
            ['icon' => 'car', 'title' => __('Sports Cars'), 'desc' => __('Ferrari, Lamborghini, Porsche'), 'image' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=400&h=300&fit=crop'],
            ['icon' => 'car', 'title' => __('Luxury 4x4'), 'desc' => __('Range Rover, G-Wagon'), 'image' => 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=400&h=300&fit=crop'],
            ['icon' => 'motorcycle', 'title' => __('Premium Flights'), 'desc' => __('Private jet rental for your travels'), 'image' => 'https://i.pinimg.com/1200x/22/5f/4d/225f4d17aa8a81f488d0794c0e4fdb80.jpg']
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

{{-- WhatsApp Floating Chat Button --}}
<a
  href="https://wa.me/+2250101221515" 
  target="_blank"
  class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-20 h-20 bg-green-500 hover:bg-green-600 rounded-full shadow-2xl transition-transform transform hover:scale-110"
  aria-label="Contact WhatsApp"
>
  <svg
    xmlns="http://www.w3.org/2000/svg"
    viewBox="0 0 32 32"
    fill="white"
    class="w-12 h-12"
  >
    <path d="M19.11 17.47c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.66.15-.2.3-.76.97-.93 1.17-.17.2-.34.22-.64.07-.3-.15-1.25-.46-2.38-1.47-.88-.79-1.47-1.77-1.64-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.34.44-.51.15-.17.2-.3.3-.51.1-.2.05-.37-.02-.52-.07-.15-.66-1.6-.9-2.2-.24-.57-.48-.5-.66-.5h-.57c-.2 0-.52.07-.8.37s-1.05 1.02-1.05 2.5 1.08 2.9 1.23 3.1c.15.2 2.12 3.23 5.14 4.53.72.31 1.28.5 1.72.64.72.23 1.37.2 1.88.12.57-.08 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.08-.12-.28-.2-.58-.35z"/>
    <path d="M16.04 2.003c-7.732 0-14.037 6.305-14.037 14.037 0 2.48.66 4.9 1.9 7.03L2 30l7.1-1.86c2.07 1.13 4.4 1.72 6.94 1.72h.01c7.73 0 14.04-6.31 14.04-14.04 0-3.75-1.46-7.27-4.11-9.92-2.65-2.65-6.17-4.1-9.94-4.1zm0 25.66c-2.11 0-4.17-.56-5.96-1.62l-.43-.26-4.22 1.11 1.13-4.11-.28-.44c-1.13-1.83-1.73-3.94-1.73-6.12 0-6.35 5.17-11.52 11.52-11.52 3.08 0 5.97 1.2 8.15 3.37 2.18 2.18 3.38 5.07 3.38 8.15 0 6.35-5.17 11.52-11.52 11.52z"/>
  </svg>
</a>

@endsection
