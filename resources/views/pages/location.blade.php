@extends('layouts.app')

@section('title', 'Location - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-blue-600 to-cyan-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4">Location de Véhicules & Équipements</h1>
      <p class="text-lg md:text-xl text-white/90">Découvrez notre large gamme de locations : véhicules, quads, avions, bateaux et plus encore</p>
    </div>
  </section>

  {{-- Services de Location --}}
  <section class="py-8 md:py-16">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-black mb-4">Nos Services de Location</h2>
        <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto">
          Découvrez notre large gamme de services de location : véhicules terrestres, aériens et nautiques pour tous vos besoins.
          Des voitures de luxe aux quads, en passant par les avions et bateaux, nous avons tout ce qu'il vous faut.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @php
          $services = [
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>',
              'title' => 'Location Terrestre',
              'description' => 'Voitures, SUV, quads et véhicules tout-terrain pour tous vos déplacements terrestres.',
              'features' => ['Flotte variée', 'Assurance complète', 'Maintenance incluse']
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>',
              'title' => 'Location Aérienne',
              'description' => 'Avions privés, hélicoptères et drones pour vos déplacements aériens.',
              'features' => ['Pilotes certifiés', 'Maintenance rigoureuse', 'Service personnalisé']
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9m0 9c-1.657 0-3-4.03-3-9s1.343-9 3-9m0 18c1.657 0 3-4.03 3-9s-1.343-9-3-9" /></svg>',
              'title' => 'Location Nautique',
              'description' => 'Bateaux, yachts et équipements nautiques pour vos aventures marines.',
              'features' => ['Équipages qualifiés', 'Équipements de sécurité', 'Navigation côtière']
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>',
              'title' => 'Service avec Équipage',
              'description' => 'Service premium avec pilotes, capitaines et guides professionnels.',
              'features' => ['Personnel qualifié', 'Service 24/7', 'Accompagnement personnalisé']
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m0 0l-2-2m2 2l2-2m4-6v6m0 0l2 2m-2-2l-2 2" /></svg>',
              'title' => 'Location Longue Durée',
              'description' => 'Tarifs préférentiels pour les locations prolongées et projets spéciaux.',
              'features' => ['Réductions sur durée', 'Contrats flexibles', 'Support technique continu']
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>',
              'title' => 'Location Express',
              'description' => 'Service rapide pour vos besoins urgents et imprévus.',
              'features' => ['Livraison rapide', 'Procédure simplifiée', 'Disponibilité immédiate']
            ]
          ];
        @endphp

        @foreach($services as $service)
          <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center text-white mb-6">
              {!! $service['icon'] !!}
            </div>
            <h3 class="text-xl md:text-2xl font-bold mb-3 md:mb-4">{{ $service['title'] }}</h3>
            <p class="text-sm md:text-base text-gray-600 mb-4 md:mb-6">{{ $service['description'] }}</p>
            <ul class="space-y-2">
              @foreach($service['features'] as $feature)
                <li class="flex items-center text-sm text-gray-700">
                  <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ $feature }}
                </li>
              @endforeach
            </ul>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Notre Flotte --}}
  <section class="py-8 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-black mb-4">Notre Flotte & Équipements</h2>
        <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto">
          Découvrez notre sélection complète d'équipements modernes et bien entretenus : véhicules terrestres, aériens et nautiques adaptés à tous vos besoins.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @php
          $vehicles = [
            [
              'name' => 'Berline Économique',
              'image' => 'https://images.unsplash.com/photo-1549399735-cef2e2c3f638?w=400&h=300&fit=crop',
              'category' => 'Terrestre',
              'passengers' => '4 passagers',
              'transmission' => 'Manuelle',
              'features' => ['Climatisation', 'Radio', 'Siège bébé disponible']
            ],
            [
              'name' => 'Quad Aventure',
              'image' => 'https://images.unsplash.com/photo-1549399735-cef2e2c3f638?w=400&h=300&fit=crop',
              'category' => 'Terrestre',
              'passengers' => '2 passagers',
              'transmission' => 'Automatique',
              'features' => ['Moteur puissant', 'Suspension tout-terrain', 'Casque fourni']
            ],
            [
              'name' => 'Avion Léger',
              'image' => 'https://images.unsplash.com/photo-1549399735-cef2e2c3f638?w=400&h=300&fit=crop',
              'category' => 'Aérien',
              'passengers' => '4 passagers',
              'transmission' => 'Propulsion',
              'features' => ['Pilote certifié', 'Équipement de sécurité', 'Navigation GPS']
            ],
            [
              'name' => 'Bateau de Plaisance',
              'image' => 'https://images.unsplash.com/photo-1549399735-cef2e2c3f638?w=400&h=300&fit=crop',
              'category' => 'Nautique',
              'passengers' => '8 passagers',
              'transmission' => 'Moteur hors-bord',
              'features' => ['Gilets de sauvetage', 'Équipement pêche', 'Ancrage inclus']
            ],
            [
              'name' => 'SUV Premium',
              'image' => 'https://images.unsplash.com/photo-1549399735-cef2e2c3f638?w=400&h=300&fit=crop',
              'category' => 'Terrestre',
              'passengers' => '7 passagers',
              'transmission' => 'Automatique',
              'features' => ['GPS intégré', 'Portes arrière électriques', 'Toit ouvrant']
            ],
            [
              'name' => 'Hélicoptère',
              'image' => 'https://images.unsplash.com/photo-1549399735-cef2e2c3f638?w=400&h=300&fit=crop',
              'category' => 'Aérien',
              'passengers' => '6 passagers',
              'transmission' => 'Turbine',
              'features' => ['Vue panoramique', 'Atterrissage vertical', 'Service VIP']
            ]
          ];
        @endphp

        @foreach($vehicles as $vehicle)
          <div class="bg-white rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-300">
            <div class="relative h-48 md:h-56">
              <img
                src="{{ $vehicle['image'] }}"
                alt="{{ $vehicle['name'] }}"
                class="w-full h-full object-cover"
              />
              <div class="absolute top-4 left-4">
                <span class="px-3 py-1 bg-blue-600 text-white text-xs font-bold rounded-full">
                  {{ $vehicle['category'] }}
                </span>
              </div>
            </div>
            <div class="p-6">
              <h3 class="text-xl font-bold mb-2">{{ $vehicle['name'] }}</h3>
              <div class="space-y-2 mb-4">
                <div class="flex items-center text-sm text-gray-600">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                  </svg>
                  {{ $vehicle['passengers'] }}
                </div>
                <div class="flex items-center text-sm text-gray-600">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                  </svg>
                  {{ $vehicle['transmission'] }}
                </div>
              </div>
              <div class="space-y-1 mb-4">
                @foreach($vehicle['features'] as $feature)
                  <div class="flex items-center text-sm text-gray-700">
                    <svg class="w-3 h-3 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ $feature }}
                  </div>
                @endforeach
              </div>
              <button class="w-full py-3 bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                Réserver Maintenant
              </button>
            </div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Avantages --}}
  <section class="py-8 md:py-16">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-2xl md:text-3xl lg:text-4xl font-black mb-4">Pourquoi Nous Choisir ?</h2>
        <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto">
          Nous nous engageons à vous offrir le meilleur service de location de véhicules en Côte d'Ivoire.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        @php
          $advantages = [
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
              'title' => 'Véhicules Certifiés',
              'description' => 'Tous nos véhicules sont régulièrement entretenus et contrôlés.'
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" /></svg>',
              'title' => 'Prix Transparents',
              'description' => 'Pas de frais cachés, tarifs clairs et compétitifs.'
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>',
              'title' => 'Support 24/7',
              'description' => 'Notre équipe est disponible à tout moment pour vous aider.'
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>',
              'title' => 'Livraison',
              'description' => 'Service de livraison et récupération à votre convenance.'
            ]
          ];
        @endphp

        @foreach($advantages as $advantage)
          <div class="text-center p-6 bg-white rounded-3xl shadow-lg hover:shadow-2xl transition-all duration-300">
            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-cyan-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-4">
              {!! $advantage['icon'] !!}
            </div>
            <h3 class="text-lg md:text-xl font-bold mb-2">{{ $advantage['title'] }}</h3>
            <p class="text-sm md:text-base text-gray-600">{{ $advantage['description'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Contact --}}
  <section class="py-8 md:py-16 bg-gradient-to-r from-blue-600 to-cyan-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-4">Prêt à Réserver Votre Véhicule ?</h2>
      <p class="text-lg md:text-xl text-white/90 mb-6 md:mb-8">
        Contactez-nous dès maintenant pour réserver votre véhicule idéal
      </p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="tel:+2252721594258" class="px-6 md:px-8 py-3 md:py-4 bg-white text-blue-600 font-bold rounded-xl hover:shadow-2xl transition-all text-center">
          📞 +225 27 21 59 42 58
        </a>
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-blue-600 transition-all text-center">
          ✉️ Nous Contacter
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
