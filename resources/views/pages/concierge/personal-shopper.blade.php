@extends('layouts.app')

@section('title', 'Personal Shopper - Service d\'achat personnalisé - Carré Premium')
@section('meta_description', 'Votre personal shopper dédié pour tous vos achats. Mode, luxe, cadeaux, shopping international. Service VIP personnalisé par Carré Premium.')
@section('meta_keywords', 'personal shopper, shopping personnalisé, mode luxe, achats VIP, conciergerie shopping, Côte d\'Ivoire, Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 md:mb-4">{{ __('Personal Shopper') }}</h1>
      <p class="text-base sm:text-lg md:text-xl text-white/90">{{ __('Your personal shopping expert for all your purchases') }}</p>
    </div>
  </section>

  {{-- Services Section --}}
  <section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
          {{ __('Our Personal Shopping Services') }}
        </h2>
        <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
          {{ __('Personalized support for all your shopping needs') }}
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8 max-w-7xl mx-auto">
        @php
          $services = [
            [
              'icon' => 'fa-tshirt',
              'title' => __('Fashion & Clothing'),
              'description' => __('Style advice, complete wardrobe shopping, search for specific pieces.'),
              'features' => [__('Style analysis'), __('Personalized shopping'), __('Luxury brands'), __('Current trends')]
            ],
            [
              'icon' => 'fa-gem',
              'title' => __('Jewelry & Accessories'),
              'description' => __('Selection of jewelry, luxury watches, leather goods and high-end accessories.'),
              'features' => [__('Fine jewelry'), __('Luxury watches'), __('Designer bags'), __('Unique pieces')]
            ],
            [
              'icon' => 'fa-gift',
              'title' => __('Personalized Gifts'),
              'description' => __('Find the perfect gift for all occasions. Gift wrapping service included.'),
              'features' => [__('Original ideas'), __('All budgets'), __('Luxury wrapping'), __('Delivery possible')]
            ],
            [
              'icon' => 'fa-home',
              'title' => __('Decoration & Home'),
              'description' => __('Furniture, decoration, art... Create your dream interior with our advice.'),
              'features' => [__('Interior design'), __('High-end furniture'), __('Art objects'), __('Unique decoration')]
            ],
            [
              'icon' => 'fa-laptop',
              'title' => __('High-Tech & Gadgets'),
              'description' => __('Latest technologies, innovative gadgets, premium electronic equipment.'),
              'features' => [__('Latest releases'), __('Exclusive products'), __('Technical advice'), __('Installation')]
            ],
            [
              'icon' => 'fa-globe-americas',
              'title' => __('International Shopping'),
              'description' => __('Access shops around the world. Paris, Milan, New York, Tokyo...'),
              'features' => [__('Worldwide shops'), __('Easy import'), __('Customs handled'), __('Secure delivery')]
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
            <ul class="space-y-2">
              @foreach($service['features'] as $feature)
                <li class="flex items-center text-gray-700 text-sm md:text-base">
                  <i class="fas fa-check-circle text-purple-600 mr-2"></i>
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
  <section class="py-8 md:py-12 bg-purple-600">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4">
          {{ __('How does it work?') }}
        </h2>
        <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto">
          {{ __('A simple process for an exceptional shopping experience') }}
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8 max-w-7xl mx-auto">
        @php
          $steps = [
            ['number' => '1', 'icon' => 'fa-comments', 'title' => __('Consultation'), 'description' => __('Share your needs, tastes and budget')],
            ['number' => '2', 'icon' => 'fa-search', 'title' => __('Research'), 'description' => __('We search for the best options')],
            ['number' => '3', 'icon' => 'fa-check-circle', 'title' => __('Validation'), 'description' => __('We present you with a selection')],
            ['number' => '4', 'icon' => 'fa-shopping-cart', 'title' => __('Purchase & Delivery'), 'description' => __('We buy and deliver to your home')]
          ];
        @endphp

        @foreach($steps as $step)
          <div class="relative">
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 md:p-8 text-center hover:bg-white/20 transition-all">
              <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center text-purple-600 font-black text-2xl mx-auto mb-4">
                {{ $step['number'] }}
              </div>
              <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-purple-600 mx-auto mb-4">
                <i class="fas {{ $step['icon'] }} text-2xl"></i>
              </div>
              <h3 class="text-lg md:text-xl font-bold text-white mb-3">{{ $step['title'] }}</h3>
              <p class="text-white/80 text-sm md:text-base">{{ $step['description'] }}</p>
            </div>
            @if(!$loop->last)
              <div class="hidden lg:block absolute top-1/2 -right-4 transform -translate-y-1/2">
                <i class="fas fa-arrow-right text-white/30 text-2xl"></i>
              </div>
            @endif
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Benefits Section --}}
  <section class="py-8 md:py-12 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8 md:mb-12">
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
            {{ __('Why use a Personal Shopper?') }}
          </h2>
        </div>

        <div class="grid md:grid-cols-2 gap-4 md:gap-6">
          @php
            $benefits = [
              ['icon' => 'fa-clock', 'title' => __('Time saving'), 'description' => __('We do the shopping for you')],
              ['icon' => 'fa-lightbulb', 'title' => __('Fashion expertise'), 'description' => __('Professional advice and trends')],
              ['icon' => 'fa-tags', 'title' => __('Best prices'), 'description' => __('Access to exclusive discounts')],
              ['icon' => 'fa-globe', 'title' => __('Global access'), 'description' => __('Shopping in the best shops')],
              ['icon' => 'fa-user-check', 'title' => __('Personalized service'), 'description' => __('Dedicated attention to your tastes')],
              ['icon' => 'fa-shield-alt', 'title' => __('Secure purchases'), 'description' => __('Protected and guaranteed transactions')]
            ];
          @endphp

          @foreach($benefits as $benefit)
            <div class="flex items-start space-x-4 p-4 bg-white rounded-xl border border-gray-100">
              <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                <i class="fas {{ $benefit['icon'] }}"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 mb-1 text-sm md:text-base">{{ $benefit['title'] }}</h4>
                <p class="text-sm text-gray-600">{{ $benefit['description'] }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>
  </section>

  {{-- Pricing Section --}}
  <section class="py-8 md:py-12">
    <div class="container mx-auto px-4">
      <div class="text-center mb-8 md:mb-12">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
          {{ __('Our Packages') }}
        </h2>
        <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
          {{ __('Choose the package that suits your needs') }}
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-6 md:gap-8 max-w-6xl mx-auto">
        @php
          $plans = [
            [
              'name' => __('Essential'),
              'price' => __('On quote'),
              'description' => __('For your one-time purchases'),
              'features' => [__('Initial consultation'), __('Product search'), __('Purchase and delivery'), __('Email support'), __('Satisfaction guarantee')],
              'popular' => false
            ],
            [
              'name' => __('Premium'),
              'price' => __('On quote'),
              'description' => __('For regular follow-up'),
              'features' => [__('Everything from Essential package'), __('Dedicated advisor'), __('Monthly shopping'), __('Priority access'), __('Exclusive discounts'), __('Support 24/7')],
              'popular' => true
            ],
            [
              'name' => __('VIP'),
              'price' => __('On quote'),
              'description' => __('Ultra-personalized service'),
              'features' => [__('Everything from Premium package'), __('Unlimited shopping'), __('Access to private boutiques'), __('Exclusive events'), __('Personal styling'), __('Complete concierge')],
              'popular' => false
            ]
          ];
        @endphp

        @foreach($plans as $plan)
          <div class="relative bg-white rounded-3xl p-6 md:p-8 shadow-lg hover:shadow-2xl transition-all border {{ $plan['popular'] ? 'border-purple-600 ring-2 ring-purple-600' : 'border-gray-100' }}">
            @if($plan['popular'])
              <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                <span class="bg-purple-600 text-white px-6 py-2 rounded-full text-sm font-bold">
                  {{ __('Most popular') }}
                </span>
              </div>
            @endif

            <div class="text-center mb-6">
              <h3 class="text-xl md:text-2xl font-black text-gray-900 mb-2">{{ $plan['name'] }}</h3>
              <p class="text-gray-600 text-sm mb-4">{{ $plan['description'] }}</p>
              <div class="text-3xl md:text-4xl font-black text-purple-600">
                {{ $plan['price'] }}
              </div>
            </div>

            <ul class="space-y-3 mb-8">
              @foreach($plan['features'] as $feature)
                <li class="flex items-start text-gray-700 text-sm md:text-base">
                  <i class="fas fa-check-circle text-purple-600 mr-3 mt-1"></i>
                  <span>{{ $feature }}</span>
                </li>
              @endforeach
            </ul>

            <a href="{{ route('contact') }}" class="block w-full py-3 md:py-4 bg-purple-600 text-white font-bold rounded-xl text-center hover:bg-purple-700 transition-all text-sm md:text-base">
              {{ __('Request a quote') }}
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-12 md:py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-3 md:mb-4">{{ __('Ready for a unique shopping experience?') }}</h2>
      <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 md:mb-8">{{ __('Contact us today for your free first consultation') }}</p>
      <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-white text-purple-600 font-bold rounded-lg md:rounded-xl hover:shadow-2xl transition-all text-sm md:text-base">
          {{ __('Make an appointment') }}
        </a>
        <a href="tel:+2250101221515" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg md:rounded-xl hover:bg-white hover:text-purple-600 transition-all text-sm md:text-base">
          {{ __('Call Now') }}
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
