@extends('layouts.app')

@section('title', 'Service Visa - Assistance complète pour vos demandes de visa - Carré Premium')
@section('meta_description', 'Carré Premium vous accompagne dans toutes vos démarches de visa. Service rapide, fiable et professionnel pour tous types de visas : touristique, affaires, étudiant.')
@section('meta_keywords', 'service visa, demande visa, visa touristique, visa affaires, visa étudiant, assistance visa, Côte d\'Ivoire, Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[30vh] md:h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-white mb-2 md:mb-4">{{ __('Visa Service') }}</h1>
      <p class="text-base sm:text-lg md:text-xl text-white/90">{{ __('Complete assistance for all your visa applications') }}</p>
    </div>
  </section>

  {{-- Stats Section --}}
  <section class="py-8 md:py-12 bg-gray-50">
    <div class="container mx-auto px-4">
      <div class="grid md:grid-cols-4 gap-6">
        @php
          $stats = [
            ['icon' => 'fa-check-circle', 'number' => '5000+', 'label' => __('Visas processed')],
            ['icon' => 'fa-clock', 'number' => '48h', 'label' => __('Average time')],
            ['icon' => 'fa-globe-americas', 'number' => '150+', 'label' => __('Countries covered')],
            ['icon' => 'fa-star', 'number' => '98%', 'label' => __('Success rate')]
          ];
        @endphp

        @foreach($stats as $stat)
          <div class="bg-white rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white mb-4">
              <i class="fas {{ $stat['icon'] }} text-2xl"></i>
            </div>
            <div class="text-3xl font-black text-purple-600 mb-1">{{ $stat['number'] }}</div>
            <div class="text-gray-600 font-medium">{{ $stat['label'] }}</div>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Services Section --}}
  <section id="services" class="py-12 md:py-20">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12 md:mb-16">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-4">
          {{ __('Our Visa Services') }}
        </h2>
        <p class="text-lg md:text-xl text-gray-600 max-w-2xl mx-auto">
          {{ __('A complete range of services for all your visa needs') }}
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
        @php
          $services = [
            [
              'icon' => 'fa-plane-departure',
              'title' => __('Tourist Visa'),
              'description' => __('For your leisure and discovery trips. Complete assistance for obtaining your tourist visa.'),
              'features' => [__('Fast processing'), __('Simplified documents'), __('Personalized follow-up')]
            ],
            [
              'icon' => 'fa-briefcase',
              'title' => __('Business Visa'),
              'description' => __('For your business trips. Express service for urgent business travel.'),
              'features' => [__('Priority processing'), __('Express service'), __('24/7 assistance')]
            ],
            [
              'icon' => 'fa-graduation-cap',
              'title' => __('Student Visa'),
              'description' => __('For your studies abroad. Complete support for your student visa.'),
              'features' => [__('Personalized advice'), __('File verification'), __('Complete follow-up')]
            ],
            [
              'icon' => 'fa-users',
              'title' => __('Family Visa'),
              'description' => __('To join your loved ones. Assistance for family reunification visas.'),
              'features' => [__('Family file'), __('Support'), __('Legal advice')]
            ],
            [
              'icon' => 'fa-heartbeat',
              'title' => __('Medical Visa'),
              'description' => __('For your medical care abroad. Fast processing for medical emergencies.'),
              'features' => [__('Medical emergency'), __('Fast processing'), __('Complete assistance')]
            ],
            [
              'icon' => 'fa-passport',
              'title' => __('Transit Visa'),
              'description' => __('For your stopovers and transits. Fast service for transit visas.'),
              'features' => [__('Express service'), __('Short deadline'), __('Competitive price')]
            ]
          ];
        @endphp

        @foreach($services as $service)
          <div class="group bg-white rounded-3xl p-6 md:p-8 shadow-lg hover:shadow-2xl transition-all hover:-translate-y-2 border border-gray-100">
            <div class="w-16 h-16 bg-purple-600 rounded-2xl flex items-center justify-center text-white mb-6 group-hover:scale-110 transition-transform">
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
              <span>{{ __('Request a quote') }}</span>
              <i class="fas fa-arrow-right ml-2"></i>
            </a>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Process Section --}}
  <section class="py-12 md:py-20 bg-purple-600">
    <div class="container mx-auto px-4">
      <div class="text-center mb-12 md:mb-16">
        <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white mb-4">
          {{ __('How does it work?') }}
        </h2>
        <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto">
          {{ __('A simple and transparent process in 4 steps') }}
        </p>
      </div>

      <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 md:gap-8">
        @php
          $steps = [
            ['number' => '01', 'icon' => 'fa-file-alt', 'title' => __('Consultation'), 'description' => __('Contact us to assess your needs and get a personalized quote')],
            ['number' => '02', 'icon' => 'fa-folder-open', 'title' => __('File preparation'), 'description' => __('We guide you to gather all necessary documents')],
            ['number' => '03', 'icon' => 'fa-paper-plane', 'title' => __('Submission & Follow-up'), 'description' => __('We submit your file and ensure regular follow-up')],
            ['number' => '04', 'icon' => 'fa-check-double', 'title' => __('Reception'), 'description' => __('Collect your visa and travel with peace of mind')]
          ];
        @endphp

        @foreach($steps as $step)
          <div class="relative">
            <div class="bg-white/10 backdrop-blur-sm rounded-3xl p-6 md:p-8 text-center hover:bg-white/20 transition-all">
              <div class="text-4xl md:text-6xl font-black text-white/20 mb-4">{{ $step['number'] }}</div>
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

  {{-- Documents Section --}}
  <section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4">
      <div class="max-w-4xl mx-auto">
        <div class="text-center mb-12">
          <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-4">
            {{ __('Documents generally required') }}
          </h2>
          <p class="text-lg md:text-xl text-gray-600">
            {{ __('Indicative list of necessary documents (varies by country)') }}
          </p>
        </div>

        <div class="grid md:grid-cols-2 gap-4 md:gap-6">
          @php
            $documents = [
              ['icon' => 'fa-passport', 'title' => __('Valid passport'), 'description' => __('Minimum validity of 6 months')],
              ['icon' => 'fa-image', 'title' => __('ID photos'), 'description' => __('Format and number according to country')],
              ['icon' => 'fa-file-invoice', 'title' => __('Application form'), 'description' => __('Completed and signed')],
              ['icon' => 'fa-plane-arrival', 'title' => __('Flight reservation'), 'description' => __('Confirmed round trip')],
              ['icon' => 'fa-hotel', 'title' => __('Accommodation reservation'), 'description' => __('Hotel or invitation')],
              ['icon' => 'fa-money-bill-wave', 'title' => __('Financial proof'), 'description' => __('Recent bank statements')],
              ['icon' => 'fa-briefcase', 'title' => __('Work certificate'), 'description' => __('Or proof of activity')],
              ['icon' => 'fa-shield-alt', 'title' => __('Travel insurance'), 'description' => __('Medical coverage')]
            ];
          @endphp

          @foreach($documents as $doc)
            <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl hover:shadow-lg transition-all border border-gray-100">
              <div class="w-12 h-12 bg-purple-600 rounded-xl flex items-center justify-center text-white flex-shrink-0">
                <i class="fas {{ $doc['icon'] }}"></i>
              </div>
              <div>
                <h4 class="font-bold text-gray-900 mb-1 text-sm md:text-base">{{ $doc['title'] }}</h4>
                <p class="text-sm text-gray-600">{{ $doc['description'] }}</p>
              </div>
            </div>
          @endforeach
        </div>

        <div class="mt-8 p-4 md:p-6 bg-purple-50 border-2 border-purple-200 rounded-2xl">
          <div class="flex items-start space-x-3">
            <i class="fas fa-info-circle text-purple-600 text-xl mt-1"></i>
            <div>
              <h4 class="font-bold text-purple-900 mb-2 text-sm md:text-base">{{ __('Important note') }}</h4>
              <p class="text-purple-800 text-sm md:text-base">
                {{ __('Required documents vary by destination country and visa type. Contact us for a personalized list according to your situation.') }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- CTA Section --}}
  <section class="py-12 md:py-16 bg-gradient-to-r from-purple-600 to-amber-600">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white mb-3 md:mb-4">{{ __('Ready to start your visa application?') }}</h2>
      <p class="text-base md:text-lg lg:text-xl text-white/90 mb-6 md:mb-8">{{ __('Our team of experts is here to support you every step of the way') }}</p>
      <div class="flex flex-col sm:flex-row gap-3 md:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="px-6 md:px-8 py-3 md:py-4 bg-white text-purple-600 font-bold rounded-lg md:rounded-xl hover:shadow-2xl transition-all text-sm md:text-base">
          {{ __('Request a quote') }}
        </a>
        <a href="tel:+2252721594258" class="px-6 md:px-8 py-3 md:py-4 bg-transparent border-2 border-white text-white font-bold rounded-lg md:rounded-xl hover:bg-white hover:text-purple-600 transition-all text-sm md:text-base">
          {{ __('Call Now') }}
        </a>
      </div>
    </div>
  </section>
</div>
@endsection
