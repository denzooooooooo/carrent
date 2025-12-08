@extends('layouts.app')

@section('title', __('Partenariats - Carré Premium - Conciergerie privée') . ' - Carré Premium')
@section('meta_description', 'Découvrez nos opportunités de partenariat avec Carré Premium. Devenez partenaire de notre conciergerie privée en Côte d\'Ivoire pour des services premium de voyages de luxe, événements VIP et packages touristiques.')
@section('meta_keywords', 'partenariat, partenariat entreprise, conciergerie privée, Côte d\'Ivoire, services premium, voyages luxe, événements VIP, Carré Premium')
@section('og_title', __('Partenariats - Carré Premium - Conciergerie privée') . ' - Carré Premium')
@section('og_description', 'Rejoignez notre réseau de partenaires et bénéficiez de commissions attractives sur les réservations de voyages de luxe et événements exclusifs en Côte d\'Ivoire.')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-4">{{ __('Partnerships') }}</h1>
      <p class="text-xl text-white/90">{{ __('Join our network of partners') }}</p>
    </div>
  </section>

  {{-- Partnership Benefits --}}
  <section class="py-12">
    <div class="container mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-4xl font-black mb-4">{{ __('Why Partner with Us?') }}</h2>
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('Become part of our premium concierge network and benefit from attractive commissions on luxury travel bookings and exclusive events in Ivory Coast.') }}</p>
      </div>

      <div class="grid md:grid-cols-3 gap-8 mb-12">
        @php
          $benefits = [
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" /></svg>',
              'title' => __('Attractive Commissions'),
              'description' => __('Earn competitive commissions on every booking made through your referrals.')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" /></svg>',
              'title' => __('Premium Services'),
              'description' => __('Access to our complete range of luxury concierge services and VIP experiences.')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
              'title' => __('Dedicated Support'),
              'description' => __('Personal account manager and 24/7 support for all your partnership needs.')
            ]
          ];
        @endphp

        @foreach($benefits as $benefit)
          <div class="bg-white rounded-3xl p-8 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2 text-center">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-amber-600 rounded-2xl flex items-center justify-center text-white mb-6 mx-auto">
              {!! $benefit['icon'] !!}
            </div>
            <h3 class="text-2xl font-bold mb-4">{{ $benefit['title'] }}</h3>
            <p class="text-gray-600">{{ $benefit['description'] }}</p>
          </div>
        @endforeach
      </div>

      {{-- Partnership Types --}}
      <div class="bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-8 shadow-xl text-white mb-12">
        <h2 class="text-3xl font-black mb-8 text-center">{{ __('Partnership Types') }}</h2>
        <div class="grid md:grid-cols-2 gap-8">
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
            <h3 class="text-2xl font-bold mb-4">{{ __('Business Partners') }}</h3>
            <ul class="space-y-2 text-sm">
              <li>• Hotels & Resorts</li>
              <li>• Travel Agencies</li>
              <li>• Event Planners</li>
              <li>• Corporate Companies</li>
              <li>• Luxury Brands</li>
            </ul>
          </div>
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
            <h3 class="text-2xl font-bold mb-4">{{ __('Individual Partners') }}</h3>
            <ul class="space-y-2 text-sm">
              <li>• Travel Influencers</li>
              <li>• Event Coordinators</li>
              <li>• Luxury Consultants</li>
              <li>• VIP Concierge Services</li>
              <li>• Personal Shoppers</li>
            </ul>
          </div>
        </div>
      </div>

      {{-- Partnership Form --}}
      <div class="bg-white rounded-3xl p-8 shadow-xl">
        <h2 class="text-3xl font-black mb-6 text-center">{{ __('Become a Partner') }}</h2>
        <p class="text-center text-gray-600 mb-8">{{ __('Fill out the form below and our partnership team will contact you within 24 hours.') }}</p>

        @if(session('success'))
          <div class="mb-6 p-4 bg-green-50 border-2 border-green-500 rounded-xl">
            <div class="flex items-center space-x-3">
              <svg class="w-6 h-6 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
              </svg>
              <p class="font-bold text-green-700">{{ session('success') }}</p>
            </div>
          </div>
        @endif

        <form method="POST" action="{{ route('contact.store') }}" class="space-y-6">
          @csrf
          <div class="grid md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Company Name') }} *</label>
              <input
                type="text"
                name="company_name"
                value="{{ old('company_name') }}"
                placeholder="{{ __('Your company name') }}"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                required
              />
              @error('company_name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Contact Person') }} *</label>
              <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="{{ __('Full name') }}"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                required
              />
              @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div class="grid md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Email') }} *</label>
              <input
                type="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="contact@company.com"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                required
              />
              @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Phone') }} *</label>
              <input
                type="tel"
                name="phone"
                value="{{ old('phone') }}"
                placeholder="+225 27 21 59 42 58"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                required
              />
              @error('phone')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>
          </div>

          <div>
            <label class="block text-sm font-bold mb-2">{{ __('Partnership Type') }} *</label>
            <select
              name="partnership_type"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
              required
            >
              <option value="" {{ old('partnership_type') == '' ? 'selected' : '' }}>{{ __('Select partnership type') }}</option>
              <option value="hotel" {{ old('partnership_type') == 'hotel' ? 'selected' : '' }}>{{ __('Hotel & Resort') }}</option>
              <option value="travel_agency" {{ old('partnership_type') == 'travel_agency' ? 'selected' : '' }}>{{ __('Travel Agency') }}</option>
              <option value="event_planner" {{ old('partnership_type') == 'event_planner' ? 'selected' : '' }}>{{ __('Event Planner') }}</option>
              <option value="corporate" {{ old('partnership_type') == 'corporate' ? 'selected' : '' }}>{{ __('Corporate Company') }}</option>
              <option value="luxury_brand" {{ old('partnership_type') == 'luxury_brand' ? 'selected' : '' }}>{{ __('Luxury Brand') }}</option>
              <option value="influencer" {{ old('partnership_type') == 'influencer' ? 'selected' : '' }}>{{ __('Travel Influencer') }}</option>
              <option value="other" {{ old('partnership_type') == 'other' ? 'selected' : '' }}>{{ __('Other') }}</option>
            </select>
            @error('partnership_type')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-bold mb-2">{{ __('Company Website') }}</label>
            <input
              type="url"
              name="website"
              value="{{ old('website') }}"
              placeholder="https://www.company.com"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
            />
            @error('website')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label class="block text-sm font-bold mb-2">{{ __('Tell us about your business') }} *</label>
            <textarea
              name="message"
              placeholder="{{ __('Describe your company, services, and why you want to partner with Carré Premium...') }}"
              rows="6"
              class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none resize-none"
              required
            >{{ old('message') }}</textarea>
            @error('message')
              <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <div class="text-center">
            <button
              type="submit"
              class="px-8 py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2 mx-auto"
            >
              <span>{{ __('Submit Partnership Application') }}</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </button>
          </div>
        </form>
      </div>

      {{-- Contact Info --}}
      <div class="mt-12 bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-8 shadow-xl text-white">
        <h2 class="text-3xl font-black mb-6 text-center">{{ __('Contact Our Partnership Team') }}</h2>
        <div class="grid md:grid-cols-3 gap-6 text-center">
          <div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
              </svg>
            </div>
            <h3 class="font-bold mb-2">{{ __('Phone') }}</h3>
            <p>+225 27 21 59 42 58</p>
          </div>
          <div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
              </svg>
            </div>
            <h3 class="font-bold mb-2">{{ __('Email') }}</h3>
            <p>partnerships@carrepremium.com</p>
          </div>
          <div>
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clipRule="evenodd" />
              </svg>
            </div>
            <h3 class="font-bold mb-2">{{ __('Address') }}</h3>
            <p>Abidjan, Côte d'Ivoire</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
