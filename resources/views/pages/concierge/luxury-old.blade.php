@extends('layouts.app')

@section('title', 'Conciergerie Luxe - Jets Privés & Hélicoptères - Carré Premium')
@section('meta_description', 'Voyagez avec élégance. Location de jets privés, hélicoptères et services VIP exclusifs. Carré Premium, votre conciergerie de luxe en Côte d\'Ivoire.')
@section('meta_keywords', 'jet privé, hélicoptère, conciergerie luxe, voyage VIP, aviation privée, charter, Côte d\'Ivoire, Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 md:mb-4">{{ __('Luxury Concierge') }}</h1>
      <p class="text-base sm:text-lg md:text-xl text-white/90">{{ __('Private jets, helicopters and exclusive VIP services') }}</p>
    </div>
  </section>

  {{-- Services Section --}}
  <section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
          Nos Services Exclusifs
        </h2>
        <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
          Une flotte d'appareils haut de gamme et des services personnalisés
        </p>
      </div>

      <div class="grid md:grid-cols-2 gap-6 md:gap-8 max-w-6xl mx-auto">
        @php
          $services = [
            [
              'icon' => 'fa-plane',
              'title' => 'Jets Privés',
              'description' => 'Voyagez dans le confort absolu avec notre flotte de jets privés dernière génération.',
              'features' => ['Flotte moderne', 'Équipage professionnel', 'Catering gastronomique', 'Départ flexible', 'Aéroports privés', 'Confidentialité']
            ],
            [
              'icon' => 'fa-helicopter',
              'title' => 'Hélicoptères',
              'description' => 'Évitez les embouteillages. Transferts héliportés pour vos déplacements d\'affaires.',
              'features' => ['Transferts rapides', 'Survols touristiques', 'Événements spéciaux', 'Héliports privés', 'Pilotes expérimentés', 'Sécurité maximale']
            ],
            [
              'icon' => 'fa-globe-americas',
              'title' => 'Vols Internationaux',
              'description' => 'Voyagez partout dans le monde avec nos solutions de charter international.',
              'features' => ['Destinations mondiales', 'Vols long-courrier', 'Escales organisées', 'Formalités simplifiées', 'Assistance au sol', 'Conciergerie']
            ],
            [
              'icon' => 'fa-star',
              'title' => 'Services VIP',
              'description' => 'Un service complet pour une expérience sans égal.',
              'features' => ['Accueil personnalisé', 'Salons VIP privatifs', 'Limousine', 'Bagages prioritaires', 'Assistance 24/7', 'Conciergerie sur mesure']
            ]
          ];
        @endphp

        @foreach($services as $service)
          <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg hover:shadow-2xl transition-all border border-gray-100">
            <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center text-white mb-6">
              <i class="fas {{ $service['icon'] }} text-2xl"></i>
            </div>
            <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-3">{{ $service['title'] }}</h3>
            <p class="text-gray-600 mb-6 text-sm md:text-base">{{ $service['description'] }}</p>
            <ul class="space-y-2 mb-6">
              @foreach($service['features'] as $feature)
                <li class="flex items-center text-gray-700 text-sm md:text-base">
                  <i class="fas fa-check-circle text-purple-600 mr-2"></i>
                  <span>{{ $feature }}</span>
                </li>
              @endforeach
            </ul>
            <a href="{{ route('contact') }}" class="inline-flex items-center text-purple-600 font-bold hover:gap-3 transition-all text-sm md:text-base">
              <span>Réserver maintenant</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Fleet Section --}}
  <section class="py-8 md:py-12 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
          Notre Flotte
        </h2>
        <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
          Des appareils de dernière génération pour tous vos besoins
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6 md:gap-8 max-w-6xl mx-auto">
        @php
          $fleet = [
            [
              'category' => 'Light Jets',
              'capacity' => '4-8 passagers',
              'range' => '2,500 km',
              'examples' => ['Citation CJ3', 'Phenom 300', 'Learjet 75'],
              'ideal' => 'Vols régionaux'
            ],
            [
              'category' => 'Mid-Size Jets',
              'capacity' => '8-10 passagers',
              'range' => '4,500 km',
              'examples' => ['Citation XLS+', 'Hawker 900XP', 'Gulfstream G150'],
              'ideal' => 'Vols intercontinentaux'
            ],
            [
              'category' => 'Heavy Jets',
              'capacity' => '10-16 passagers',
              'range' => '7,500+ km',
              'examples' => ['Gulfstream G650', 'Bombardier Global 6000', 'Falcon 7X'],
              'ideal' => 'Vols long-courriers'
            ]
          ];
        @endphp

        @foreach($fleet as $aircraft)
          <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg hover:shadow-2xl transition-all border border-gray-100">
            <div class="text-center mb-6">
              <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center text-white mx-auto mb-4">
                <i class="fas fa-plane text-2xl"></i>
              </div>
              <h3 class="text-xl md:text-2xl font-bold text-gray-900 mb-2">{{ $aircraft['category'] }}</h3>
              <p class="text-purple-600 font-semibold text-sm md:text-base">{{ $aircraft['ideal'] }}</p>
            </div>

            <div class="space-y-3 mb-6">
              <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600 text-sm md:text-base">Capacité</span>
                <span class="text-gray-900 font-bold text-sm md:text-base">{{ $aircraft['capacity'] }}</span>
              </div>
              <div class="flex items-center justify-between py-2 border-b border-gray-200">
                <span class="text-gray-600 text-sm md:text-base">Autonomie</span>
                <span class="text-gray-900 font-bold text-sm md:text-base">{{ $aircraft['range'] }}</span>
              </div>
            </div>

            <div class="mb-6">
              <p class="text-gray-600 text-sm mb-2">Exemples d'appareils :</p>
              <div class="space-y-1">
                @foreach($aircraft['examples'] as $example)
                  <div class="text-gray-700 text-sm">• {{ $example }}</div>
                @endforeach
              </div>
            </div>

            <a href="{{ route('contact') }}" class="block w-full py-3 bg-purple-600 text-white font-bold rounded-xl text-center hover:bg-purple-700 transition-all text-sm md:text-base">
              Demander un devis
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Why Choose Us --}}
  <section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8 md:mb-12">
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
            Pourquoi Choisir Carré Premium ?
          </h2>
        </div>

        <div class="grid md:grid-cols-2 gap-4 md:gap-6">
          @php
            $advantages = [
              ['icon' => 'fa-shield-alt', 'title' => 'Sécurité maximale', 'description' => 'Appareils certifiés et équipages qualifiés'],
              ['icon' => 'fa-clock', 'title' => 'Flexibilité totale', 'description' => 'Départ selon votre emploi du temps'],
              ['icon' => 'fa-user-tie', 'title' => 'Service personnalisé', 'description' => 'Un conseiller dédié pour chaque réservation'],
              ['icon' => 'fa-lock', 'title' => 'Confidentialité', 'description' => 'Discrétion absolue pour tous vos déplacements'],
              ['icon' => 'fa-headset', 'title' => 'Support 24/7', 'description' => 'Une équipe disponible à tout moment'],
              ['icon' => 'fa-gem', 'title' => 'Expérience premium', 'description' => 'Chaque détail pensé pour votre confort']
            ];
          @endphp

          @foreach($advantages as $advantage)
            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-100">
              <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                <i class="fas {{ $advantage['icon'] }}"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 mb-1 text-sm md:text-base">{{ $advantage['title'] }}</h4>
                <p class="text-sm text-gray-600">{{ $advantage['description'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-12 md:py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-3 md:mb-4">{{ __('Ready to take off?') }}</h2>
      <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 md:mb-8">{{ __('Contact us now to organize your next private flight') }}</p>
      <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-white text-purple-600 font-bold rounded-lg md:rounded-xl hover:shadow-2xl transition-all text-sm md:text-base">
          {{ __('Request a quote') }}
        </a>
        <a href="tel:+2250101221515" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg md:rounded-xl hover:bg-white hover:text-purple-600 transition-all text-sm md:text-base">
          {{ __('Call Now') }}
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
