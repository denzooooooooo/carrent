@extends('layouts.app')

@section('title', 'Accueil - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900">
  {{-- Hero Carrousel avec icônes SVG professionnelles --}}
  @include('components.services-carousel')

  {{-- Événements à la Une --}}
  <section class="py-24 bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 relative overflow-hidden">
    <div class="absolute inset-0 opacity-20">
      <div class="absolute top-20 left-20 w-96 h-96 bg-amber-500 rounded-full filter blur-3xl animate-pulse"></div>
      <div class="absolute bottom-20 right-20 w-96 h-96 bg-pink-500 rounded-full filter blur-3xl animate-pulse" style="animation-delay: 2s"></div>
    </div>

    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-16">
        <div class="inline-flex items-center space-x-3 px-8 py-3 bg-gradient-to-r from-amber-500 to-pink-500 text-white rounded-full text-sm font-black mb-6 shadow-2xl animate-pulse">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>ÉVÉNEMENTS SPORTIFS & CULTURELS EXCLUSIFS</span>
        </div>
        <h2 class="text-6xl md:text-7xl font-black text-white mb-6 leading-tight">
          Événements à Ne Pas Manquer
        </h2>
        <p class="text-2xl text-gray-300 max-w-3xl mx-auto">
          Accédez aux plus grands événements sportifs et culturels du monde
        </p>
      </div>

      <div class="grid md:grid-cols-4 gap-6 mb-12">
        {{-- Événements simulés pour l'exemple --}}
        @for ($i = 0; $i < 8; $i++)
          <a
            href="{{ route('events.index') }}"
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
          href="{{ route('events.index') }}"
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

  {{-- Packages Premium --}}
  <section class="py-24 bg-white dark:bg-gray-800 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-96 h-96 bg-purple-200 dark:bg-purple-900 rounded-full filter blur-3xl opacity-20"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-amber-200 dark:bg-amber-900 rounded-full filter blur-3xl opacity-20"></div>

    <div class="container mx-auto px-4 relative z-10">
      <div class="text-center mb-16">
        <div class="inline-flex items-center space-x-3 px-8 py-3 bg-gradient-to-r from-purple-600 to-amber-600 text-white rounded-full text-sm font-black mb-6 shadow-lg">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
          <span>PACKAGES PREMIUM & LOCATION DE LUXE</span>
        </div>
        <h2 class="text-6xl md:text-7xl font-black text-gray-900 dark:text-white mb-6 leading-tight">
          Expériences de Luxe<br />
          <span class="bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
            Sur Mesure
          </span>
        </h2>
        <p class="text-2xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
          Hélicoptères • Jets Privés • Quads • Safaris • Yachts • Voitures de Luxe
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8 mb-16">
        @php
          $services = [
            [
              'icon' => 'helicopter',
              'title' => 'Tours en Hélicoptère',
              'description' => 'Survolez les plus beaux paysages en hélicoptère privé avec champagne à bord',
              'features' => ['Pilote professionnel', 'Champagne à bord', 'Photos HD incluses', 'Durée flexible'],
              'price' => 'À partir de 500,000 XOF',
              'image' => 'https://images.unsplash.com/photo-1589519160732-57fc498494f8?w=600&h=400&fit=crop',
              'badge' => 'POPULAIRE'
            ],
            [
              'icon' => 'motorcycle',
              'title' => 'Location Quads & Motos',
              'description' => 'Explorez en toute liberté avec nos véhicules premium tout-terrain',
              'features' => ['Équipement complet', 'Assurance incluse', 'Guide disponible', 'Carburant inclus'],
              'price' => 'À partir de 75,000 XOF/jour',
              'image' => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=600&h=400&fit=crop',
              'badge' => 'NOUVEAU'
            ],
            [
              'icon' => 'jet',
              'title' => 'Jets Privés',
              'description' => 'Voyagez en jet privé vers vos destinations de rêve en toute exclusivité',
              'features' => ['Service VIP complet', 'Flexibilité totale', 'Confort absolu', 'Catering premium'],
              'price' => 'Sur devis personnalisé',
              'image' => 'https://images.unsplash.com/photo-1540962351504-03099e0a754b?w=600&h=400&fit=crop',
              'badge' => 'LUXE'
            ]
          ];
        @endphp

        @foreach($services as $service)
          <div
            class="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-purple-500/50 transition-all duration-700 hover:-translate-y-6 bg-white dark:bg-gray-900"
          >
            {{-- Badge --}}
            <div class="absolute top-6 right-6 z-10 px-4 py-2 bg-gradient-to-r from-red-500 to-pink-500 text-white rounded-full text-xs font-black shadow-xl">
              {{ $service['badge'] }}
            </div>

            <div class="aspect-[4/3] overflow-hidden relative">
              <img
                src="{{ $service['image'] }}"
                alt="{{ $service['title'] }}"
                class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>

              <div class="absolute top-6 left-6 w-20 h-20 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border-4 border-white/30 group-hover:scale-110 transition-transform">
                @if($service['icon'] === 'helicopter')
                  <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                  </svg>
                @elseif($service['icon'] === 'motorcycle')
                  <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                @else
                  <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                  </svg>
                @endif
              </div>
            </div>

            <div class="p-8">
              <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4 group-hover:text-purple-600 transition-colors">
                {{ $service['title'] }}
              </h3>
              <p class="text-gray-600 dark:text-gray-400 mb-6 text-lg">
                {{ $service['description'] }}
              </p>

              <div class="space-y-3 mb-6">
                @foreach($service['features'] as $feature)
                  <div class="flex items-center text-gray-700 dark:text-gray-300">
                    <svg class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="font-semibold">{{ $feature }}</span>
                  </div>
                @endforeach
              </div>

              <div class="mb-6 p-5 bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/30 dark:to-amber-900/30 rounded-2xl border-2 border-purple-200 dark:border-purple-700">
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-2 font-semibold">Tarif</div>
                <div class="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                  {{ $service['price'] }}
                </div>
              </div>

              <a
                href="{{ route('packages.index') }}"
                class="flex items-center justify-center space-x-2 w-full py-4 bg-gradient-to-r from-purple-600 via-purple-700 to-amber-600 text-white font-black text-center rounded-2xl hover:shadow-2xl transition-all duration-300 transform group-hover:scale-105"
              >
                <span>RÉSERVER MAINTENANT</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </a>
            </div>
          </div>
        @endforeach
      </div>

      {{-- Packages de l'API --}}
      <div class="grid md:grid-cols-3 gap-8">
        {{-- Packages simulés --}}
        @for ($i = 0; $i < 3; $i++)
          <a
            href="{{ route('packages.index') }}"
            class="group relative rounded-3xl overflow-hidden shadow-2xl hover:shadow-amber-500/50 transition-all duration-700 hover:-translate-y-6 bg-white dark:bg-gray-900"
          >
            <div class="aspect-[4/3] overflow-hidden relative">
              <img
                src="https://images.unsplash.com/photo-{{ 1540962351504 + $i }}-03099e0a754b?w=600&h=400&fit=crop"
                alt="Package Premium {{ $i + 1 }}"
                class="w-full h-full object-cover group-hover:scale-125 transition-transform duration-1000"
              />
              <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>

              <div class="absolute top-4 right-4 px-4 py-2 bg-gradient-to-r from-purple-600 to-amber-600 text-white rounded-full text-xs font-bold shadow-xl">
                PREMIUM
              </div>
            </div>

            <div class="p-6">
              <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-3 group-hover:text-purple-600 transition-colors">
                Package Premium {{ $i + 1 }}
              </h3>
              <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">
                Découvrez une expérience unique et exclusive avec notre package premium personnalisé.
              </p>

              <div class="mb-4 p-4 bg-gradient-to-r from-purple-50 to-amber-50 dark:from-purple-900/20 dark:to-amber-900/20 rounded-2xl">
                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">À partir de</div>
                <div class="text-3xl font-black bg-gradient-to-r from-purple-600 to-amber-600 bg-clip-text text-transparent">
                  Sur devis
                </div>
              </div>

              <button class="flex items-center justify-center space-x-2 w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-2xl hover:shadow-2xl transition-all duration-300 transform group-hover:scale-105">
                <span>Découvrir</span>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
              </button>
            </div>
          </a>
        @endfor
      </div>
    </div>
  </section>

  {{-- Véhicules de Luxe --}}
  <section class="py-24 bg-gradient-to-br from-amber-900 via-amber-800 to-purple-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <div class="inline-flex items-center space-x-3 px-8 py-3 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-black mb-6 border-2 border-white/30">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          <span>LOCATION DE VÉHICULES PREMIUM</span>
        </div>
        <h2 class="text-6xl md:text-7xl font-black text-white mb-6 leading-tight">
          Conduisez l'Excellence
        </h2>
        <p class="text-2xl text-white/90 max-w-3xl mx-auto">
          Quads • Motos de Luxe • Voitures de Sport • 4x4 Premium
        </p>
      </div>

      <div class="grid md:grid-cols-4 gap-6">
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
            href="{{ route('packages.index') }}"
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
                <span class="font-bold">Disponible</span>
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

  {{-- Vols - Section secondaire --}}
  <section class="py-20 bg-gray-100 dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto text-center">
        <div class="inline-flex items-center justify-center space-x-3 mb-6">
          <svg class="w-12 h-12 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </div>
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
          Besoin d'un Vol ?
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
          Nous proposons également la réservation de vols internationaux
        </p>
        <a
          href="{{ route('flights.index') }}"
          class="inline-flex items-center space-x-2 px-8 py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-full hover:scale-105 transition-transform shadow-xl"
        >
          <span>Rechercher un Vol</span>
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
      <h2 class="text-5xl md:text-6xl font-black text-white mb-8">
        Prêt pour une Expérience Inoubliable ?
      </h2>
      <p class="text-2xl text-white/90 mb-12 max-w-3xl mx-auto">
        Contactez notre conciergerie pour créer votre expérience sur mesure
      </p>
      <div class="flex flex-wrap justify-center gap-6">
        <a
          href="{{ route('events.index') }}"
          class="inline-flex items-center space-x-2 px-10 py-5 bg-white text-purple-900 font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
          </svg>
          <span>ÉVÉNEMENTS VIP</span>
        </a>
        <a
          href="{{ route('packages.index') }}"
          class="inline-flex items-center space-x-2 px-10 py-5 bg-gradient-to-r from-amber-500 to-pink-500 text-white font-black text-lg rounded-full hover:scale-110 transition-all duration-300 shadow-2xl"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
          <span>PACKAGES LUXE</span>
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
