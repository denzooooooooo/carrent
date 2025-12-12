@extends('layouts.app')

@section('title', 'Personal Shopper - Service d\'achat personnalisé - Carré Premium')
@section('meta_description', 'Votre personal shopper dédié pour tous vos achats. Mode, luxe, cadeaux, shopping international. Service VIP personnalisé par Carré Premium.')
@section('meta_keywords', 'personal shopper, shopping personnalisé, mode luxe, achats VIP, conciergerie shopping, Côte d\'Ivoire, Carré Premium')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-pink-50 via-white to-purple-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
  {{-- Hero Section --}}
  <section class="relative h-[70vh] bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iODAiIGhlaWdodD0iODAiIHZpZXdCb3g9IjAgMCA4MCA4MCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48ZyBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxnIGZpbGw9IiNmZmYiIGZpbGwtb3BhY2l0eT0iMC4xIj48cGF0aCBkPSJNMCAwaDgwdjgwSDB6Ii8+PC9nPjwvZz48L3N2Zz4=')] opacity-10"></div>
    
    <div class="relative z-10 container mx-auto h-full flex items-center px-4">
      <div class="max-w-4xl">
        <div class="inline-flex items-center space-x-3 bg-white/20 backdrop-blur-sm px-6 py-3 rounded-full mb-8">
          <i class="fas fa-shopping-bag text-white text-xl"></i>
          <span class="text-white font-bold">Service Exclusif</span>
        </div>
        
        <h1 class="text-6xl md:text-7xl font-black text-white mb-6 leading-tight">
          Personal Shopper
          <span class="block text-pink-200">à votre service</span>
        </h1>
        
        <p class="text-2xl text-white/90 mb-8 leading-relaxed max-w-3xl">
          Votre expert shopping personnel pour tous vos achats. Mode, luxe, cadeaux... Nous trouvons et achetons pour vous, partout dans le monde.
        </p>

        <div class="flex flex-wrap gap-4">
          <a href="#services" class="px-8 py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl transition-all hover:scale-105">
            Découvrir le service
          </a>
          <a href="{{ route('contact') }}" class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white font-bold rounded-xl border-2 border-white hover:bg-white hover:text-purple-600 transition-all">
            Prendre rendez-vous
          </a>
        </div>

        {{-- Quick Stats --}}
        <div class="grid grid-cols-3 gap-6 mt-12 max-w-2xl">
          @php
            $quickStats = [
              ['icon' => 'fa-globe', 'text' => 'Shopping International'],
              ['icon' => 'fa-gift', 'text' => 'Cadeaux Personnalisés'],
              ['icon' => 'fa-star', 'text' => 'Service VIP']
            ];
          @endphp
          @foreach($quickStats as $stat)
            <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-sm rounded-xl p-4">
              <i class="fas {{ $stat['icon'] }} text-white text-2xl"></i>
              <span class="text-white font-semibold text-sm">{{ $stat['text'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- Services Section --}}
  <section id="services" class="py-20">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
          Nos Services de <span class="text-pink-600">Personal Shopping</span>
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
          Un accompagnement personnalisé pour tous vos besoins d'achat
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
        @php
          $services = [
            [
              'icon' => 'fa-tshirt',
              'title' => 'Mode & Vêtements',
              'description' => 'Conseil en style, shopping de garde-robe complète, recherche de pièces spécifiques.',
              'features' => ['Analyse de style', 'Shopping personnalisé', 'Marques de luxe', 'Tendances actuelles'],
              'color' => 'from-pink-500 to-rose-500'
            ],
            [
              'icon' => 'fa-gem',
              'title' => 'Bijoux & Accessoires',
              'description' => 'Sélection de bijoux, montres de luxe, maroquinerie et accessoires haut de gamme.',
              'features' => ['Joaillerie fine', 'Montres de luxe', 'Sacs de créateurs', 'Pièces uniques'],
              'color' => 'from-purple-500 to-indigo-500'
            ],
            [
              'icon' => 'fa-gift',
              'title' => 'Cadeaux Personnalisés',
              'description' => 'Trouvez le cadeau parfait pour toutes les occasions. Service emballage inclus.',
              'features' => ['Idées originales', 'Tous budgets', 'Emballage luxe', 'Livraison possible'],
              'color' => 'from-red-500 to-pink-500'
            ],
            [
              'icon' => 'fa-home',
              'title' => 'Décoration & Maison',
              'description' => 'Mobilier, décoration, art... Créez l\'intérieur de vos rêves avec nos conseils.',
              'features' => ['Design d\'intérieur', 'Mobilier haut de gamme', 'Objets d\'art', 'Décoration unique'],
              'color' => 'from-amber-500 to-orange-500'
            ],
            [
              'icon' => 'fa-laptop',
              'title' => 'High-Tech & Gadgets',
              'description' => 'Dernières technologies, gadgets innovants, équipements électroniques premium.',
              'features' => ['Dernières sorties', 'Produits exclusifs', 'Conseil technique', 'Installation'],
              'color' => 'from-blue-500 to-cyan-500'
            ],
            [
              'icon' => 'fa-globe-americas',
              'title' => 'Shopping International',
              'description' => 'Accédez aux boutiques du monde entier. Paris, Milan, New York, Tokyo...',
              'features' => ['Boutiques mondiales', 'Import facilité', 'Douanes gérées', 'Livraison sécurisée'],
              'color' => 'from-green-500 to-emerald-500'
            ]
          ];
        @endphp

        @foreach($services as $service)
          <div class="group bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r {{ $service['color'] }} rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
              <i class="fas {{ $service['icon'] }} text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $service['title'] }}</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">{{ $service['description'] }}</p>
            <ul class="space-y-2">
              @foreach($service['features'] as $feature)
                <li class="flex items-center text-gray-700 dark:text-gray-300 text-sm">
                  <i class="fas fa-check-circle text-pink-500 mr-2"></i>
                  <span>{{ $feature }}</span>
                </li>
              @endforeach
            </ul>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- How It Works --}}
  <section class="py-20 bg-gradient-to-r from-purple-600 via-pink-600 to-rose-600">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-black text-white mb-4">
          Comment ça fonctionne ?
        </h2>
        <p class="text-xl text-white/90 max-w-2xl mx-auto">
          Un processus simple pour une expérience shopping exceptionnelle
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8 max-w-7xl mx-auto">
        @php
          $steps = [
            [
              'number' => '1',
              'icon' => 'fa-comments',
              'title' => 'Consultation',
              'description' => 'Partagez vos besoins, goûts et budget lors d\'un entretien personnalisé'
            ],
            [
              'number' => '2',
              'icon' => 'fa-search',
              'title' => 'Recherche',
              'description' => 'Nous recherchons les meilleures options selon vos critères'
            ],
            [
              'number' => '3',
              'icon' => 'fa-check-circle',
              'title' => 'Validation',
              'description' => 'Nous vous présentons une sélection pour votre approbation'
            ],
            [
              'number' => '4',
              'icon' => 'fa-shopping-cart',
              'title' => 'Achat & Livraison',
              'description' => 'Nous achetons et livrons directement chez vous'
            ]
          ];
        @endphp

        @foreach($steps as $step)
          <div class="relative">
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-8 text-center hover:bg-white/20 transition-all">
              <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-purple-600 font-black text-2xl mx-auto mb-4">
                {{ $step['number'] }}
              </div>
              <div class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-500 rounded-2xl flex items-center justify-center text-white mx-auto mb-4">
                <i class="fas {{ $step['icon'] }} text-2xl"></i>
              </div>
              <h3 class="text-xl font-bold text-white mb-3">{{ $step['title'] }}</h3>
              <p class="text-white/80 text-sm">{{ $step['description'] }}</p>
            </div>
            @if(!$loop->last)
              <div class="hidden lg:block absolute top-1/2 -right-4 transform -translate-y-1/2 z-10">
                <i class="fas fa-arrow-right text-white/30 text-2xl"></i>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Benefits Section --}}
  <section class="py-20">
    <div class="container mx-auto px-4">
      <div class="max-w-6xl mx-auto">
        <div class="grid lg:grid-cols-2 gap-16 items-center">
          <div>
            <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-6">
              Pourquoi utiliser un
              <span class="block text-pink-600">Personal Shopper ?</span>
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-400 mb-8">
              Gagnez du temps et profitez d'une expertise professionnelle pour tous vos achats
            </p>

            <div class="space-y-6">
              @php
                $benefits = [
                  ['icon' => 'fa-clock', 'title' => 'Gain de temps', 'description' => 'Nous faisons le shopping pour vous, vous gagnez des heures précieuses'],
                  ['icon' => 'fa-lightbulb', 'title' => 'Expertise mode', 'description' => 'Conseils professionnels et connaissance des tendances'],
                  ['icon' => 'fa-tags', 'title' => 'Meilleurs prix', 'description' => 'Accès à des réductions et offres exclusives'],
                  ['icon' => 'fa-globe', 'title' => 'Accès mondial', 'description' => 'Shopping dans les meilleures boutiques du monde'],
                  ['icon' => 'fa-user-check', 'title' => 'Service personnalisé', 'description' => 'Une attention dédiée à vos goûts et besoins'],
                  ['icon' => 'fa-shield-alt', 'title' => 'Achats sécurisés', 'description' => 'Transactions protégées et garanties']
                ];
              @endphp

              @foreach($benefits as $benefit)
                <div class="flex items-start space-x-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-xl hover:shadow-lg transition-all">
                  <div class="w-12 h-12 bg-gradient-to-r from-pink-500 to-purple-500 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                    <i class="fas {{ $benefit['icon'] }}"></i>
                  </div>
                  <div>
                    <h4 class="font-bold text-gray-900 dark:text-white mb-1">{{ $benefit['title'] }}</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $benefit['description'] }}</p>
                  </div>
                </div>
              @endforeach
            </div>
          </div>

          <div class="relative">
            <div class="bg-gradient-to-br from-pink-100 to-purple-100 dark:from-pink-900/20 dark:to-purple-900/20 rounded-3xl p-12">
              <div class="text-center">
                <i class="fas fa-shopping-bag text-6xl text-pink-600 mb-6"></i>
                <h3 class="text-3xl font-black text-gray-900 dark:text-white mb-4">
                  Votre Shopping,<br/>Notre Passion
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-8">
                  Laissez-nous transformer votre expérience d'achat en un moment de plaisir et de découverte
                </p>
                <div class="space-y-4">
                  <div class="flex items-center justify-center space-x-3 bg-white dark:bg-gray-800 rounded-xl p-4">
                    <i class="fas fa-star text-yellow-500 text-xl"></i>
                    <span class="font-bold text-gray-900 dark:text-white">Service 5 étoiles</span>
                  </div>
                  <div class="flex items-center justify-center space-x-3 bg-white dark:bg-gray-800 rounded-xl p-4">
                    <i class="fas fa-heart text-red-500 text-xl"></i>
                    <span class="font-bold text-gray-900 dark:text-white">100% Satisfaction</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Pricing Section --}}
  <section class="py-20 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
      <div class="text-center mb-16">
        <h2 class="text-4xl md:text-5xl font-black text-gray-900 dark:text-white mb-4">
          Nos Formules
        </h2>
        <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
          Choisissez la formule qui correspond à vos besoins
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8 max-w-6xl mx-auto">
        @php
          $plans = [
            [
              'name' => 'Essentiel',
              'price' => 'Sur devis',
              'description' => 'Pour vos achats ponctuels',
              'features' => [
                'Consultation initiale',
                'Recherche de produits',
                'Achat et livraison',
                'Support par email',
                'Garantie satisfaction'
              ],
              'color' => 'from-blue-500 to-cyan-500',
              'popular' => false
            ],
            [
              'name' => 'Premium',
              'price' => 'Sur devis',
              'description' => 'Pour un suivi régulier',
              'features' => [
                'Tout de la formule Essentiel',
                'Conseiller dédié',
                'Shopping mensuel',
                'Accès prioritaire',
                'Réductions exclusives',
                'Support 24/7'
              ],
              'color' => 'from-pink-500 to-purple-500',
              'popular' => true
            ],
            [
              'name' => 'VIP',
              'price' => 'Sur devis',
              'description' => 'Service ultra-personnalisé',
              'features' => [
                'Tout de la formule Premium',
                'Shopping illimité',
                'Accès boutiques privées',
                'Événements exclusifs',
                'Styling personnel',
                'Conciergerie complète'
              ],
              'color' => 'from-yellow-500 to-amber-500',
              'popular' => false
            ]
          ];
        @endphp

        @foreach($plans as $plan)
          <div class="relative bg-white dark:bg-gray-800 rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all {{ $plan['popular'] ? 'ring-4 ring-pink-500 transform scale-105' : '' }}">
            @if($plan['popular'])
              <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                <span class="bg-gradient-to-r from-pink-500 to-purple-500 text-white px-6 py-2 rounded-full text-sm font-bold">
                  Plus populaire
                </span>
              </div>
            @endif

            <div class="text-center mb-6">
              <h3 class="text-2xl font-black text-gray-900 dark:text-white mb-2">{{ $plan['name'] }}</h3>
              <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">{{ $plan['description'] }}</p>
              <div class="text-4xl font-black bg-gradient-to-r {{ $plan['color'] }} bg-clip-text text-transparent">
                {{ $plan['price'] }}
              </div>
            </div>

            <ul class="space-y-3 mb-8">
              @foreach($plan['features'] as $feature)
                <li class="flex items-start text-gray-700 dark:text-gray-300">
                  <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                  <span>{{ $feature }}</span>
                </li>
              @endforeach
            </ul>

            <a href="{{ route('contact') }}" class="block w-full py-4 bg-gradient-to-r {{ $plan['color'] }} text-white font-bold rounded-xl text-center hover:shadow-xl transition-all">
              Demander un devis
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-20 bg-gradient-to-r from-pink-600 via-purple-600 to-indigo-600">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto text-center">
        <i class="fas fa-shopping-bag text-6xl text-white/20 mb-6"></i>
        <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
          Prêt pour une expérience shopping unique ?
        </h2>
        <p class="text-xl text-white/90 mb-8">
          Contactez-nous dès aujourd'hui pour votre première consultation gratuite
        </p>
        <div class="flex flex-wrap justify-center gap-4">
          <a href="{{ route('contact') }}" class="px-10 py-5 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl transition-all hover:scale-105">
            <i class="fas fa-calendar-check mr-2"></i>
            Prendre rendez-vous
          </a>
          <a href="tel:+2250101221515" class="px-10 py-5 bg-white/20 backdrop-blur-sm text-white font-bold rounded-xl border-2 border-white hover:bg-white hover:text-purple-600 transition-all">
            <i class="fas fa-phone mr-2"></i>
            +225 01 01 22 15 15
          </a>
        </div>
        <p class="text-white/80 mt-8">
          <i class="fas fa-gift mr-2"></i>
          Première consultation offerte
        </p>
      </div>
    </div>
  </section>
</div>
@endsection
