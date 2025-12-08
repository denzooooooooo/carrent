@extends('layouts.app')

@section('title', $location->name . ' - Location - Carré Premium')

@section('content')
<div class="min-h-screen bg-white">
  {{-- Hero Section --}}
  <section class="relative h-[50vh] md:h-[60vh] overflow-hidden">
    <div class="absolute inset-0">
      @php
        $imageUrl = $location->image_url;
        $placeholder = 'https://placehold.co/1200x600/4c1d95/ffffff?text=Image+Location';
      @endphp
      <img
        src="{{ $imageUrl ?: $placeholder }}"
        alt="{{ $location->name }}"
        class="w-full h-full object-cover"
        onerror="this.onerror=null;this.src='{{ $placeholder }}';"
      />
      <div class="absolute inset-0 bg-black/40"></div>
    </div>
    <div class="relative z-10 container mx-auto h-full flex items-end pb-8 px-4">
      <div class="text-white">
        <div class="flex items-center mb-2">
          <span class="px-3 py-1 bg-blue-600 text-white text-sm font-bold rounded-full">
            {{ ucfirst($location->category) }}
          </span>
        </div>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-black mb-2">{{ $location->name }}</h1>
        <div class="flex items-center text-lg md:text-xl">
          <span class="bg-gradient-to-r from-amber-300 to-pink-300 bg-clip-text text-transparent font-black">
            {{ number_format($location->price_per_day, 0, ',', ' ') }} FCFA
          </span>
          <span class="text-white/90 ml-2">par jour</span>
        </div>
      </div>
    </div>
  </section>

  {{-- Content --}}
  <section class="py-8 md:py-16">
    <div class="container mx-auto px-4">
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
        {{-- Main Content --}}
        <div class="lg:col-span-2 space-y-8">
          {{-- Description --}}
          @if($location->description)
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg">
              <h2 class="text-2xl md:text-3xl font-black mb-4">{{ __('Description') }}</h2>
              <div class="prose prose-lg max-w-none">
                {!! nl2br(e($location->description)) !!}
              </div>
            </div>
          @endif

          {{-- Specifications --}}
          <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg">
            <h2 class="text-2xl md:text-3xl font-black mb-6">{{ __('Spécifications') }}</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <div>
                  <div class="font-bold">{{ __('Capacité') }}</div>
                  <div class="text-gray-600">{{ $location->capacity }} {{ __('personne(s)') }}</div>
                </div>
              </div>
              <div class="flex items-center p-4 bg-gray-50 rounded-xl">
                <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <div>
                  <div class="font-bold">{{ __('Type') }}</div>
                  <div class="text-gray-600">{{ $location->type }}</div>
                </div>
              </div>
            </div>
          </div>

          {{-- Features --}}
          @if($location->features && count($location->features) > 0)
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg">
              <h2 class="text-2xl md:text-3xl font-black mb-6">{{ __('Équipements & Services') }}</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                @foreach($location->features as $feature)
                  <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>{{ $feature }}</span>
                  </div>
                @endforeach
              </div>
            </div>
          @endif
        </div>

        {{-- Booking Form --}}
        <div class="lg:col-span-1">
          <div class="bg-white rounded-3xl p-6 md:p-8 shadow-lg sticky top-8">
            <h2 class="text-2xl md:text-3xl font-black mb-6">{{ __('Réserver cette location') }}</h2>

            <form action="{{ route('location.book', $location) }}" method="POST" class="space-y-6">
              @csrf

              {{-- Name --}}
              <div>
                <label for="name" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Nom complet') }} *</label>
                <input type="text" id="name" name="name" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="{{ __('Votre nom complet') }}">
                @error('name')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              {{-- Email --}}
              <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Email') }} *</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="{{ __('votre@email.com') }}">
                @error('email')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              {{-- Phone --}}
              <div>
                <label for="phone" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Téléphone') }} *</label>
                <input type="tel" id="phone" name="phone" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       placeholder="{{ __('+225 XX XX XX XX XX') }}">
                @error('phone')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              {{-- Start Date --}}
              <div>
                <label for="start_date" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Date de début') }} *</label>
                <input type="date" id="start_date" name="start_date" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                @error('start_date')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              {{-- End Date --}}
              <div>
                <label for="end_date" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Date de fin') }} *</label>
                <input type="date" id="end_date" name="end_date" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                @error('end_date')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              {{-- Special Requests --}}
              <div>
                <label for="special_requests" class="block text-sm font-bold text-gray-700 mb-2">{{ __('Demandes spéciales') }}</label>
                <textarea id="special_requests" name="special_requests" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="{{ __('Précisez vos besoins particuliers...') }}"></textarea>
                @error('special_requests')
                  <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
              </div>

              {{-- Price Summary --}}
              <div class="bg-gray-50 rounded-xl p-4 space-y-2">
                <div class="flex justify-between text-sm">
                  <span>{{ __('Prix par jour') }}:</span>
                  <span>{{ number_format($location->price_per_day, 0, ',', ' ') }} FCFA</span>
                </div>
                <div class="flex justify-between text-sm">
                  <span>{{ __('Nombre de jours') }}:</span>
                  <span id="days-count">0</span>
                </div>
                <hr class="border-gray-300">
                <div class="flex justify-between font-bold text-lg">
                  <span>{{ __('Total') }}:</span>
                  <span id="total-price">0 FCFA</span>
                </div>
              </div>

              {{-- Submit Button --}}
              <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-500 to-cyan-600 text-white font-bold rounded-xl hover:shadow-lg transition-all">
                {{ __('Réserver maintenant') }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const daysCount = document.getElementById('days-count');
    const totalPrice = document.getElementById('total-price');
    const pricePerDay = {{ $location->price_per_day }};

    function calculatePrice() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (startDate && endDate && startDate < endDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // +1 pour inclure le jour de fin
            const total = daysDiff * pricePerDay;

            daysCount.textContent = daysDiff;
            totalPrice.textContent = total.toLocaleString('fr-FR') + ' FCFA';
        } else {
            daysCount.textContent = '0';
            totalPrice.textContent = '0 FCFA';
        }
    }

    startDateInput.addEventListener('change', calculatePrice);
    endDateInput.addEventListener('change', calculatePrice);

    // Set minimum end date when start date changes
    startDateInput.addEventListener('change', function() {
        if (this.value) {
            endDateInput.min = this.value;
        }
    });
});
</script>
@endsection
