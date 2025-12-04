@extends('layouts.app')

@section('title', __('Contactez Carré Premium - Conciergerie privée') . ' - Carré Premium')
@section('meta_description', 'Contactez Carré Premium, votre conciergerie privée en Côte d\'Ivoire. Réservations 24/7, service client premium pour vos voyages de luxe, événements VIP et packages touristiques.')
@section('meta_keywords', 'contact, conciergerie privée, Côte d\'Ivoire, service client, réservations, voyages luxe, événements VIP, Carré Premium')
@section('og_title', __('Contactez Carré Premium - Conciergerie privée') . ' - Carré Premium')
@section('og_description', 'Notre équipe est disponible 24/7 pour vous accompagner dans vos projets de voyages de luxe et événements exclusifs en Côte d\'Ivoire.')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero --}}
  <section class="relative h-[40vh] bg-gradient-to-r from-purple-600 to-amber-600 overflow-hidden">
    <div class="absolute inset-0 bg-black/20"></div>
    <div class="relative z-10 container mx-auto h-full flex flex-col justify-center px-4">
      <h1 class="text-5xl font-black text-white mb-4">{{ __('Contact Us') }}</h1>
      <p class="text-xl text-white/90">{{ __('Our team is available 24/7') }}</p>
    </div>
  </section>

  {{-- Contact Info Cards --}}
  <section class="py-12">
    <div class="container mx-auto">
      <div class="grid md:grid-cols-4 gap-6 -mt-20 relative z-20 mb-12">
        @php
          $contactCards = [
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" /></svg>',
              'title' => __('Phone'),
              'info' => __('Landline: +225 27 21 59 42 58<br>Mobile: +225 01 01 22 15 15'),
              'subinfo' => __('Mon-Sun: 24/7')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" /><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" /></svg>',
              'title' => __('Email'),
              'info' => 'infos@carrepremium.com',
              'subinfo' => __('Response within 24h')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path fillRule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clipRule="evenodd" /></svg>',
              'title' => __('Address'),
              'info' => 'Abidjan Marcory Biétry Boulevard de Marseille, Côte d\'Ivoire',
              'subinfo' => __('Ivory Coast')
            ],
            [
              'icon' => '<svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z" /><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z" /></svg>',
              'title' => 'WhatsApp',
              'info' => '+225 01 01 22 15 15',
              'subinfo' => __('Live chat')
            ]
          ];
        @endphp

        @foreach($contactCards as $card)
          <div class="bg-white rounded-3xl p-6 shadow-xl hover:shadow-2xl transition-all hover:-translate-y-2">
            <div class="w-16 h-16 bg-gradient-to-r from-purple-600 to-amber-600 rounded-2xl flex items-center justify-center text-white mb-4">
              {!! $card['icon'] !!}
            </div>
            <h3 class="text-lg font-bold mb-2">{{ $card['title'] }}</h3>
            <p class="text-purple-600 font-semibold mb-1">{!! $card['info'] !!}</p>
            <p class="text-sm text-gray-600">{{ $card['subinfo'] }}</p>
          </div>
        @endforeach
      </div>

      <div class="grid lg:grid-cols-2 gap-12">
        {{-- Contact Form --}}
        <div class="bg-white rounded-3xl p-8 shadow-xl">
          <h2 class="text-3xl font-black mb-6">{{ __('Send us a Message') }}</h2>

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
            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Full name') }} *</label>
              <input
                type="text"
                name="name"
                value="{{ old('name') }}"
                placeholder="{{ __('Your name') }}"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                required
              />
              @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div class="grid md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-bold mb-2">{{ __('Email') }} *</label>
                <input
                  type="email"
                  name="email"
                  value="{{ old('email') }}"
                  placeholder="infos@carrepremium.com"
                  class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                  required
                />
                @error('email')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>

              <div>
                <label class="block text-sm font-bold mb-2">{{ __('Phone') }}</label>
                <input
                  type="tel"
                  name="phone"
                  value="{{ old('phone') }}"
                  placeholder="+225 27 21 59 42 58 ou +225 01 01 22 15 15"
                  class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                />
                @error('phone')
                  <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>

            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Subject') }} *</label>
              <select
                name="subject"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none"
                required
              >
                <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>{{ __('General question') }}</option>
                <option value="booking" {{ old('subject') == 'booking' ? 'selected' : '' }}>{{ __('Booking') }}</option>
                <option value="payment" {{ old('subject') == 'payment' ? 'selected' : '' }}>{{ __('Payment') }}</option>
                <option value="cancellation" {{ old('subject') == 'cancellation' ? 'selected' : '' }}>{{ __('Cancellation') }}</option>
                <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>{{ __('Complaint') }}</option>
                <option value="partnership" {{ old('subject') == 'partnership' ? 'selected' : '' }}>{{ __('Partnership') }}</option>
              </select>
              @error('subject')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <div>
              <label class="block text-sm font-bold mb-2">{{ __('Message') }} *</label>
              <textarea
                name="message"
                placeholder="{{ __('Describe your request...') }}"
                rows="6"
                class="w-full px-4 py-3 rounded-xl border-2 border-gray-200 bg-white focus:border-purple-600 focus:outline-none resize-none"
                required
              >{{ old('message') }}</textarea>
              @error('message')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
              @enderror
            </div>

            <button
              type="submit"
              class="w-full py-4 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2"
            >
              <span>{{ __('Send Message') }}</span>
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
              </svg>
            </button>
          </form>
        </div>

        {{-- FAQ & Info --}}
        <div class="space-y-6">
          {{-- FAQ --}}
          <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h2 class="text-3xl font-black mb-6">{{ __('Frequently Asked Questions') }}</h2>

            <div class="space-y-4">
              @php
                $faqs = [
                  [
                    'q' => __('How to book a flight?'),
                    'a' => __('Search for your flight, select your options, fill in passenger information and proceed to secure payment.')
                  ],
                  [
                    'q' => __('Can I cancel my booking?'),
                    'a' => __('Yes, according to fare conditions. Cancellation fees vary depending on the type of ticket.')
                  ],
                  [
                    'q' => __('What payment methods do you accept?'),
                    'a' => __('Credit card, Mobile Money (Orange Money, MTN Money, Moov Money), bank transfer and PayPal.')
                  ],
                  [
                    'q' => __('How do I receive my ticket?'),
                    'a' => __('Your e-ticket will be sent by email immediately after payment confirmation.')
                  ]
                ];
              @endphp

              @foreach($faqs as $faq)
                <details class="group">
                  <summary class="flex items-center justify-between cursor-pointer p-4 bg-gray-50 rounded-xl hover:bg-purple-50 transition-colors">
                    <span class="font-bold">{{ $faq['q'] }}</span>
                    <svg class="w-5 h-5 transform group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </summary>
                  <p class="mt-3 px-4 text-gray-600">{{ $faq['a'] }}</p>
                </details>
              @endforeach
            </div>
          </div>

          {{-- Horaires --}}
          <div class="bg-gradient-to-r from-purple-600 to-amber-600 rounded-3xl p-8 shadow-xl text-white">
            <h3 class="text-2xl font-black mb-4">{{ __('Opening Hours') }}</h3>
            <div class="space-y-3">
              <div class="flex justify-between items-center pb-3 border-b border-white/20">
                <span class="font-semibold">{{ __('Monday - Friday') }}</span>
                <span>08:00 - 20:00</span>
              </div>
              <div class="flex justify-between items-center pb-3 border-b border-white/20">
                <span class="font-semibold">{{ __('Saturday') }}</span>
                <span>09:00 - 18:00</span>
              </div>
              <div class="flex justify-between items-center pb-3 border-b border-white/20">
                <span class="font-semibold">{{ __('Sunday') }}</span>
                <span>10:00 - 16:00</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="font-semibold">{{ __('Emergencies') }}</span>
                <span class="font-bold">24/7</span>
              </div>
            </div>
          </div>

          {{-- Social Media --}}
          <div class="bg-white rounded-3xl p-8 shadow-xl">
            <h3 class="text-2xl font-black mb-4">{{ __('Follow Us') }}</h3>
            <div class="grid grid-cols-3 gap-4">
              @php
                $socials = [
                  ['name' => 'Facebook', 'icon' => 'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z', 'color' => 'bg-blue-600', 'url' => 'https://www.facebook.com/agence.carrepremium'],
                  ['name' => 'Instagram', 'icon' => 'M12 2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 011.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 01-1.153 1.772 4.915 4.915 0 01-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 01-1.772-1.153 4.904 4.904 0 01-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 011.153-1.772A4.897 4.897 0 015.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2z', 'color' => 'bg-pink-600', 'url' => 'https://www.instagram.com/carre.premium'],
                  ['name' => 'LinkedIn', 'icon' => 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z', 'color' => 'bg-blue-700', 'url' => 'https://www.linkedin.com/company/carre-premium']
                ];
              @endphp

              @foreach($socials as $social)
                <a
                  href="{{ $social['url'] ?? '#' }}"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="{{ $social['color'] }} w-full aspect-square rounded-xl flex items-center justify-center text-white hover:scale-110 transition-transform"
                  aria-label="{{ $social['name'] }}"
                >
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="{{ $social['icon'] }}" />
                  </svg>
                </a>
              @endforeach
            </div>
          </div>
        </div>
      </div>

      {{-- Map --}}
      <div class="mt-12 bg-white rounded-3xl p-8 shadow-xl">
        <h2 class="text-3xl font-black mb-6">{{ __('Our Location') }}</h2>
        <div class="aspect-video bg-gray-200 rounded-2xl overflow-hidden">
          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3972.2!2d-4.0!3d5.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNcKwMTgnMDAuMCJOIDTCsDAwJzAwLjAiVw!5e0!3m2!1sfr!2sci!4v1234567890&q=Abidjan+Marcory+Biétry+Boulevard+de+Marseille,+Côte+d'Ivoire"
            width="100%"
            height="100%"
            style="border: 0"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
          ></iframe>
        </div>
      </div>
    </div>
  </section>
</div>
@endsection
