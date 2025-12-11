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
        <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ __('Join our exclusive network of hotels, restaurants, and travel agencies. Benefit from our extensive client base to promote your premium services.') }}</p>
      </div>

      <div class="grid md:grid-cols-3 gap-8 mb-12">
        @php
          $benefits = [
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" /></svg>',
              'title' => __('Access to Our Network'),
              'description' => __('Benefit from our extensive client base of VIP travelers and luxury seekers to promote your services.')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z" /></svg>',
              'title' => __('Increased Visibility'),
              'description' => __('Your establishment featured on our premium platform and recommended to our exclusive clientele.')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" /></svg>',
              'title' => __('Quality Partnerships'),
              'description' => __('Join a select network of premium establishments and benefit from collaborative marketing opportunities.')
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
        <h2 class="text-3xl font-black mb-8 text-center">{{ __('Who Can Partner With Us?') }}</h2>
        <div class="grid md:grid-cols-3 gap-6">
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3">{{ __('Hotels & Resorts') }}</h3>
            <p class="text-sm text-white/90">{{ __('Luxury hotels, boutique resorts, and premium accommodations') }}</p>
          </div>
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                <path fillRule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clipRule="evenodd" />
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3">{{ __('Restaurants') }}</h3>
            <p class="text-sm text-white/90">{{ __('Fine dining, gourmet restaurants, and exclusive culinary experiences') }}</p>
          </div>
          <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6 text-center">
            <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 16H17a1 1 0 001-1v-5a1 1 0 00-.293-.707l-2-2A1 1 0 0015 7h-1z" />
              </svg>
            </div>
            <h3 class="text-2xl font-bold mb-3">{{ __('Travel Agencies') }}</h3>
            <p class="text-sm text-white/90">{{ __('Tour operators, travel agencies, and destination management companies') }}</p>
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
              <option value="restaurant" {{ old('partnership_type') == 'restaurant' ? 'selected' : '' }}>{{ __('Restaurant') }}</option>
              <option value="travel_agency" {{ old('partnership_type') == 'travel_agency' ? 'selected' : '' }}>{{ __('Travel Agency') }}</option>
              <option value="tour_operator" {{ old('partnership_type') == 'tour_operator' ? 'selected' : '' }}>{{ __('Tour Operator') }}</option>
              <option value="event_venue" {{ old('partnership_type') == 'event_venue' ? 'selected' : '' }}>{{ __('Event Venue') }}</option>
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
