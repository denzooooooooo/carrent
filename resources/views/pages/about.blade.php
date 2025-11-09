@extends('layouts.app')

@section('title', __('About') . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4">{{ __('About Carré Premium') }}</h1>
      <p class="text-lg md:text-xl text-white/90">{{ __('Your trusted partner for all your travels') }}</p>
    </div>
  </section>

  {{-- Notre Histoire --}}
  <section class="py-8 md:py-16">
    <div class="container mx-auto px-4">
      <div class="grid lg:grid-cols-2 gap-8 md:gap-12 items-center">
        <div>
          <h2 class="text-2xl md:text-3xl lg:text-4xl font-black mb-4 md:mb-6">{{ __('Our Story') }}</h2>
          <p class="text-base md:text-lg text-gray-600 mb-3 md:mb-4">
            {{ __('Founded in 2020,') }} <span class="font-bold text-purple-600">Carré Premium</span> {{ __('was born from a passion for travel and a desire to make the booking experience simple, fast and accessible to everyone.') }}
          </p>
          <p class="text-base md:text-lg text-gray-600 mb-3 md:mb-4">
            {{ __('Based in Abidjan, Ivory Coast, we quickly became one of the leaders in online ticketing in West Africa, offering flight booking services, sporting and cultural events, as well as exclusive tourist packages.') }}
          </p>
          <p class="text-base md:text-lg text-gray-600">
            {{ __('Today, we serve thousands of satisfied customers every year and continue to innovate to offer the best possible experience.') }}
          </p>
        </div>
        <div class="relative h-64 md:h-80 lg:h-96 rounded-3xl overflow-hidden shadow-2xl">
          <img
            src="https://images.unsplash.com/photo-1556388158-158ea5ccacbd?w=800&h=600&fit=crop"
            alt="Notre équipe"
            class="w-full h-full object-cover"
          />
        </div>
      </div>
    </div>
  </section>

  {{-- Nos Valeurs --}}
  <section class="py-8 md:py-16 bg-gray-50">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-center mb-8 md:mb-12">{{ __('Our Values') }}</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 md:gap-8">
        @php
          $values = [
            [
              'icon' => '🎯',
              'title' => __('Excellence'),
              'description' => __('We are committed to providing superior quality service at every stage of your journey.')
            ],
            [
              'icon' => '🤝',
              'title' => __('Trust'),
              'description' => __('Transparency and honesty are at the heart of our relationship with our customers.')
            ],
            [
              'icon' => '💡',
              'title' => __('Innovation'),
              'description' => __('We adopt the latest technologies to constantly improve your experience.')
            ],
            [
              'icon' => '🌍',
              'title' => __('Accessibility'),
              'description' => __('Making travel accessible to everyone, everywhere and at any time.')
            ],
            [
              'icon' => '⚡',
              'title' => __('Speed'),
              'description' => __('Instant bookings and responsive 24/7 customer service.')
            ],
            [
              'icon' => '🔒',
              'title' => __('Security'),
              'description' => __('Your data and payments are protected by the best security systems.')
            ]
          ];
        @endphp

        @foreach($values as $value)
          <div class="bg-white rounded-3xl p-6 md:p-8 hover:shadow-xl transition-all">
            <div class="text-4xl md:text-5xl mb-3 md:mb-4">{{ $value['icon'] }}</div>
            <h3 class="text-xl md:text-2xl font-bold mb-2 md:mb-3">{{ $value['title'] }}</h3>
            <p class="text-sm md:text-base text-gray-600">{{ $value['description'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Nos Chiffres --}}
  <section class="py-8 md:py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-center mb-8 md:mb-12">{{ __('Carré Premium in Numbers') }}</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
        @php
          $stats = [
            ['number' => '50K+', 'label' => __('Satisfied Customers')],
            ['number' => '200+', 'label' => __('Destinations')],
            ['number' => '1000+', 'label' => __('Events')],
            ['number' => '24/7', 'label' => __('Customer Support')]
          ];
        @endphp

        @foreach($stats as $stat)
          <div class="text-center p-4 md:p-8 bg-gradient-to-br from-purple-600 to-amber-600 rounded-3xl text-white">
            <div class="text-3xl md:text-4xl lg:text-5xl font-black mb-1 md:mb-2">{{ $stat['number'] }}</div>
            <div class="text-sm md:text-base lg:text-lg font-semibold">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </section>



  {{-- Nos Partenaires --}}
  <section class="py-8 md:py-16">
    <div class="container mx-auto px-4">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-center mb-8 md:mb-12">{{ __('Our Partners') }}</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-8">
        @php
          $partners = [
            'Air France', 'Emirates', 'Turkish Airlines', 'Ethiopian Airlines',
            'Visa', 'Mastercard', 'Orange Money', 'MTN Mobile Money'
          ];
        @endphp

        @foreach($partners as $partner)
          <div class="bg-white rounded-2xl p-4 md:p-6 flex items-center justify-center shadow-lg hover:shadow-xl transition-all">
            <span class="text-sm md:text-base lg:text-lg font-bold text-gray-600">{{ $partner }}</span>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="py-8 md:py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-4 md:mb-6">{{ __('Ready to Travel with Us?') }}</h2>
      <p class="text-lg md:text-xl text-white/90 mb-6 md:mb-8">{{ __('Join thousands of satisfied travelers') }}</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('flights') }}" class="px-6 md:px-8 py-3 md:py-4 bg-white text-purple-600 font-bold rounded-xl hover:shadow-2xl transition-all text-center">
          {{ __('Book a Flight') }}
        </a>
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-xl hover:bg-white hover:text-purple-600 transition-all text-center">
          {{ __('Contact Us') }}
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
