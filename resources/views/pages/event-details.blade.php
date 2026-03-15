@extends('layouts.app')

@section('title', $event->title . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50">
  {{-- Hero Section --}}
  <section class="relative">
    <div class="h-80 sm:h-96 md:h-[500px] lg:h-[600px] relative overflow-hidden">
      @php
          $imageUrl = $event->getFirstMediaUrl('avatar', 'normal');
          $placeholder = 'https://images.unsplash.com/photo-1574629810360-7efbbe195018?w=1200&h=600&fit=crop';
      @endphp
      <img src="{{ $imageUrl ?: $placeholder }}" alt="{{ $event->title }}" class="w-full h-full object-cover">
      <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

      {{-- Floating Action Button --}}
      <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-20">
        <button class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center text-white hover:bg-white/30 transition-all">
          <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </button>
      </div>
    </div>

    {{-- Event Info Overlay --}}
    <div class="absolute bottom-0 left-0 right-0 z-10">
      <div class="container mx-auto px-4 pb-6 sm:pb-8">
        <div class="max-w-4xl">
          <div class="bg-white rounded-t-2xl sm:rounded-t-3xl shadow-2xl p-4 sm:p-6 md:p-8">
            {{-- Categories --}}
            <div class="flex flex-wrap gap-2 sm:gap-3 mb-3 sm:mb-4">
              <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-purple-100 text-purple-800 text-xs sm:text-sm font-semibold rounded-full">
                {{ $event->category->name_fr ?? 'Événement' }}
              </span>
              <span class="px-3 sm:px-4 py-1.5 sm:py-2 bg-amber-100 text-amber-800 text-xs sm:text-sm font-semibold rounded-full">
                {{ $event->type->name_fr ?? 'Spectacle' }}
              </span>
            </div>

            {{-- Title --}}
            <h1 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black text-gray-900 mb-3 sm:mb-4 leading-tight mt-4 sm:mt-0">
              {{ $event->title }}
            </h1>

            {{-- Date & Location --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 mb-4 sm:mb-6">
              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                </div>
                <div class="min-w-0">
                  <p class="text-xs sm:text-sm text-gray-500 font-medium">DATE & HEURE</p>
                  <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ \Carbon\Carbon::parse($event->event_date)->format('l d F Y') }}</p>
                  <p class="text-gray-600 text-sm">{{ $event->event_time }}</p>
                </div>
              </div>

              <div class="flex items-center space-x-2 sm:space-x-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                  <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                  </svg>
                </div>
                <div class="min-w-0">
                  <p class="text-xs sm:text-sm text-gray-500 font-medium">LIEU</p>
                  <p class="text-gray-900 font-semibold text-sm sm:text-base">{{ $event->venue_name }}</p>
                  <p class="text-gray-600 text-sm">{{ $event->city }}, {{ $event->country }}</p>
                </div>
              </div>
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
              <button onclick="scrollToTickets()" class="flex-1 bg-gradient-to-r from-purple-600 to-amber-600 text-white font-bold py-3 sm:py-4 px-4 sm:px-6 rounded-lg sm:rounded-xl hover:shadow-lg transition-all duration-300 text-center text-sm sm:text-base">
                {{ __('Book now') }}
              </button>
              <button class="px-4 sm:px-6 py-3 sm:py-4 border-2 border-gray-300 text-gray-700 font-semibold rounded-lg sm:rounded-xl hover:border-purple-300 hover:text-purple-600 transition-all duration-300 text-sm sm:text-base">
                {{ __('Share') }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  {{-- Main Content --}}
  <div class="container mx-auto px-4 py-6 sm:py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
      {{-- Left Column --}}
      <div class="lg:col-span-2 space-y-6 sm:space-y-8">
        {{-- About Section --}}
        <section class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">{{ __('About the event') }}</h2>
          <div class="prose prose-base sm:prose-lg max-w-none text-gray-700 leading-relaxed">
            {!! nl2br(e($event->description_fr)) !!}
          </div>
        </section>

        {{-- Organizer Section --}}
        @if($event->organizer)
        <section class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">{{ __('Organizer') }}</h2>
          <div class="flex items-center space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <div>
              <p class="font-semibold text-gray-900">{{ $event->organizer }}</p>
              <p class="text-gray-600 text-sm">{{ __('Official organizer') }}</p>
            </div>
          </div>
        </section>
        @endif

        {{-- Venue Details --}}
        <section class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6 md:p-8">
          <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">{{ __('Event venue') }}</h2>
          <div class="flex items-start space-x-3 sm:space-x-4">
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0 mt-1">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
            <div class="flex-1">
              <h3 class="font-bold text-gray-900 text-base sm:text-lg mb-2">{{ $event->venue_name }}</h3>
              <p class="text-gray-600 mb-1 text-sm sm:text-base">{{ $event->venue_address }}</p>
              <p class="text-gray-600 text-sm sm:text-base">{{ $event->city }}, {{ $event->country }}</p>
            </div>
          </div>
        </section>
      </div>

      {{-- Right Column - Tickets & Packages --}}
      <div class="lg:col-span-1">
        <div class="sticky top-6 sm:top-8">
          <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4 sm:mb-6">{{ __('Choose your tickets') }}</h3>

            {{-- Afficher les Packages (Grilles Tarifaires) --}}
            @if(\Illuminate\Support\Facades\Schema::hasTable('event_packages') && $event->packages->count() > 0)
              <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Packages & VIP Offers') }}</h4>
                <div class="space-y-3 sm:space-y-4">
                  @foreach($event->packages as $package)
                    <div class="border-2 border-purple-200 bg-purple-50 rounded-lg sm:rounded-xl p-3 sm:p-4 hover:border-purple-400 hover:shadow-md transition-all duration-200">
                      <div class="flex justify-between items-start mb-2 sm:mb-3">
                        <div class="flex-1 min-w-0">
                          <h4 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ $package->package_name_fr }}</h4>
                          <p class="text-xs sm:text-sm text-purple-600 font-medium">{{ $package->package_code }}</p>
                        </div>
                        <div class="text-right ml-2">
                          <div class="text-xl sm:text-2xl font-bold text-purple-600">{{ \App\Helpers\CurrencyHelper::format($package->price) }}</div>
                          <div class="text-xs text-gray-500">{{ __('per person') }}</div>
                        </div>
                      </div>

                      @if($package->description_fr)
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3 line-clamp-2">{{ $package->description_fr }}</p>
                      @endif

                      @if($package->description_included_fr)
                        <div class="text-xs text-gray-500 mb-2 sm:mb-3">
                          <span class="font-medium">{{ __('Included') }}:</span> {{ $package->description_included_fr }}
                        </div>
                      @endif

                      <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                        <span>{{ $package->available_quantity }} {{ __('available') }}</span>
                        <span class="text-xs">/ {{ $package->available_quantity }}</span>
                      </div>

                      @if($package->available_quantity > 0)
                        <button class="w-full bg-purple-600 text-white font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-purple-700 transition-colors text-sm sm:text-base select-package-btn"
                                data-package-id="{{ $package->id }}"
                                data-package-name="{{ $package->package_name_fr }}"
                                data-price="{{ $package->price }}"
                                data-available="{{ $package->available_quantity }}">
                          {{ __('Select Package') }}
                        </button>
                      @else
                        <button class="w-full bg-gray-100 text-gray-400 font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg cursor-not-allowed text-sm sm:text-base" disabled>
                          {{ __('Sold out') }}
                        </button>
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            {{-- Afficher les Seat Zones (Zones de places) --}}
            @if($event->seatZones->count() > 0)
              <div class="@if($event->packages->count() > 0) pt-6 border-t border-gray-200 @endif">
                <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-3">{{ __('Seat Zones') }}</h4>
                <div class="space-y-3 sm:space-y-4">
                  @foreach($event->seatZones as $zone)
                    <div class="border border-gray-200 rounded-lg sm:rounded-xl p-3 sm:p-4 hover:border-purple-300 hover:shadow-md transition-all duration-200">
                      <div class="flex justify-between items-start mb-2 sm:mb-3">
                        <div class="flex-1 min-w-0">
                          <h4 class="font-bold text-gray-900 text-base sm:text-lg truncate">{{ $zone->zone_name }}</h4>
                          <p class="text-xs sm:text-sm text-purple-600 font-medium">{{ $zone->zone_code }}</p>
                        </div>
                        <div class="text-right ml-2">
                          <div class="text-xl sm:text-2xl font-bold text-gray-900">{{ \App\Helpers\CurrencyHelper::format($zone->price) }}</div>
                          <div class="text-xs text-gray-500">{{ __('per person') }}</div>
                        </div>
                      </div>

                      @if($zone->description)
                        <p class="text-xs sm:text-sm text-gray-600 mb-2 sm:mb-3 line-clamp-2">{{ $zone->description }}</p>
                      @endif

                      <div class="flex justify-between items-center text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                        <span>{{ $zone->available_seats }} {{ __('remaining') }}</span>
                        <span class="text-xs">/ {{ $zone->total_seats }}</span>
                      </div>

                      @if($zone->available_seats > 0)
                        <button class="w-full bg-purple-600 text-white font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg hover:bg-purple-700 transition-colors text-sm sm:text-base select-seat-btn"
                                data-zone-id="{{ $zone->id }}"
                                data-zone-name="{{ $zone->zone_name }}"
                                data-price="{{ $zone->price }}"
                                data-available="{{ $zone->available_seats }}">
                          {{ __('Select') }}
                        </button>
                      @else
                        <button class="w-full bg-gray-100 text-gray-400 font-semibold py-2.5 sm:py-3 px-3 sm:px-4 rounded-lg cursor-not-allowed text-sm sm:text-base" disabled>
                          {{ __('Sold out') }}
                        </button>
                      @endif
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            @if( ! \Illuminate\Support\Facades\Schema::hasTable('event_packages') || $event->packages->count() == 0 && $event->seatZones->count() == 0)
              <div class="text-center py-6 sm:py-8"> 
                <svg class="w-10 h-10 sm:w-12 sm:h-12 text-gray-300 mx-auto mb-3 sm:mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 font-medium text-sm sm:text-base">{{ __('Tickets coming soon') }}</p>
                <p class="text-gray-400 text-xs sm:text-sm mt-1">{{ __('Come back later') }}</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- CTA Section --}}
  <section class="bg-gradient-to-r from-purple-600 to-amber-600 py-12 sm:py-16">
    <div class="container mx-auto px-4 text-center">
      <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4">{{ __('Do you have questions?') }}</h2>
      <p class="text-lg sm:text-xl text-white/90 mb-6 sm:mb-8 max-w-2xl mx-auto">
        {{ __('Our team is here to help you choose the best seats.') }}
      </p>
      <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
        <a href="{{ route('contact') }}" class="bg-white text-purple-600 font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl hover:shadow-lg transition-all duration-300 inline-block text-sm sm:text-base">
          {{ __('Contact us') }}
        </a>
        <a href="tel:+225XXXXXXXXX" class="border-2 border-white text-white font-bold py-3 sm:py-4 px-6 sm:px-8 rounded-lg sm:rounded-xl hover:bg-white hover:text-purple-600 transition-all duration-300 inline-block text-sm sm:text-base">
          {{ __('Call now') }}
        </a>
      </div>
    </div>
  </section>
</div>

{{-- Seat/Package Selection Modal --}}
<div id="seatModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
  <div class="flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
      <div class="p-6">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-xl font-bold text-gray-900" id="modalTitle">{{ __('Select your seats') }}</h3>
          <button id="closeModal" class="text-gray-400 hover:text-gray-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>

        <div id="modalContent">
          <div class="mb-6">
            <h4 class="font-semibold text-gray-900 mb-2" id="selectedZoneName"></h4>
            <p class="text-gray-600 text-sm mb-4" id="selectedZonePrice"></p>
          </div>

          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2" id="quantityLabel">{{ __('Number of seats') }}</label>
            <div class="flex items-center space-x-3">
              <button id="decreaseQty" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                </svg>
              </button>
              <span id="quantity" class="text-xl font-bold text-gray-900 min-w-[3rem] text-center">1</span>
              <button id="increaseQty" class="w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
              </button>
            </div>
            <p class="text-xs text-gray-500 mt-2">{{ __('Maximum:') }} <span id="maxAvailable"></span></p>
          </div>

          <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="flex justify-between items-center mb-2">
              <span class="text-gray-600" id="pricePerItemLabel">{{ __('Price per seat:') }}</span>
              <span id="unitPrice" class="font-semibold"></span>
            </div>
            <div class="flex justify-between items-center text-lg font-bold">
              <span>{{ __('Total:') }}</span>
              <span id="totalPrice" class="text-purple-600"></span>
            </div>
          </div>

          <form id="bookingForm" method="POST" action="{{ route('event.book', $event) }}">
            @csrf
            <input type="hidden" name="zone_id" id="zoneIdInput" value="">
            <input type="hidden" name="package_id" id="packageIdInput" value="">
            <input type="hidden" name="quantity" id="quantityInput" value="1">

            <div class="space-y-4 mb-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Full name') }}</label>
                <input type="text" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Email') }}</label>
                <input type="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">{{ __('Phone') }}</label>
                <input type="tel" name="phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
              </div>
            </div>

            <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 transition-colors">
              {{ __('Confirm booking') }}
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
window.currentCurrency = '{{ session('currency', 'XOF') }}';

document.addEventListener('DOMContentLoaded', function() {
  const modal = document.getElementById('seatModal');
  const closeModal = document.getElementById('closeModal');
  const selectSeatButtons = document.querySelectorAll('.select-seat-btn');
  const selectPackageButtons = document.querySelectorAll('.select-package-btn');
  const decreaseBtn = document.getElementById('decreaseQty');
  const increaseBtn = document.getElementById('increaseQty');
  const quantitySpan = document.getElementById('quantity');
  const quantityInput = document.getElementById('quantityInput');
  const zoneIdInput = document.getElementById('zoneIdInput');
  const packageIdInput = document.getElementById('packageIdInput');
  const modalTitle = document.getElementById('modalTitle');
  const selectedItemName = document.getElementById('selectedZoneName');
  const selectedItemPrice = document.getElementById('selectedZonePrice');

  let currentItem = null;
  let currentQuantity = 1;
  let maxAvailable = 0;
  let isPackage = false;

  // Open modal for Seat Zones
  selectSeatButtons.forEach(button => {
    button.addEventListener('click', function() {
      const zoneId = this.dataset.zoneId;
      const zoneName = this.dataset.zoneName;
      const price = parseFloat(this.dataset.price);
      maxAvailable = parseInt(this.dataset.available);

      isPackage = false;
      currentItem = { id: zoneId, name: zoneName, price: price, type: 'seat' };
      currentQuantity = 1;

      // Update modal for seat
      modalTitle.textContent = '{{ __("Select your seats") }}';
      selectedItemName.textContent = zoneName;
      selectedItemPrice.textContent = formatPrice(price) + ' {{ __("per place") }}';
      document.getElementById('unitPrice').textContent = formatPrice(price);
      document.getElementById('maxAvailable').textContent = maxAvailable;
      document.getElementById('quantityLabel').textContent = '{{ __("Number of seats") }}';
      updateTotal();

      // Set form inputs
      zoneIdInput.value = zoneId;
      packageIdInput.value = '';
      quantityInput.value = currentQuantity;

      modal.classList.remove('hidden');
    });
  });

  // Open modal for Packages
  selectPackageButtons.forEach(button => {
    button.addEventListener('click', function() {
      const packageId = this.dataset.packageId;
      const packageName = this.dataset.packageName;
      const price = parseFloat(this.dataset.price);
      maxAvailable = parseInt(this.dataset.available);

      // ✅ NOUVEAU: Limite CinetPay 1.5M → Quantity max basée sur prix
      const maxCinetpayAmount = 1500000;
      const maxQuantityCinetpay = Math.floor(maxCinetpayAmount / price);
      maxAvailable = Math.min(maxAvailable, maxQuantityCinetpay);

      if (maxQuantityCinetpay < 1) {
        alert(`Package ${packageName} trop cher pour paiement en ligne (max 1.5M XOF). Contactez admin@carrepremium.ci`);
        return;
      }

      isPackage = true;
      currentItem = { id: packageId, name: packageName, price: price, type: 'package' };
      currentQuantity = 1;

      // Update modal for package
      modalTitle.textContent = '{{ __("Select Package") }}';
      selectedItemName.textContent = packageName;
      selectedItemPrice.textContent = formatPrice(price) + ' {{ __("per person") }}';
      document.getElementById('unitPrice').textContent = formatPrice(price);
      document.getElementById('maxAvailable').textContent = maxAvailable;
      if (maxQuantityCinetpay < parseInt(this.dataset.available)) {
        document.getElementById('maxAvailable').innerHTML = `<span title="Limite paiement en ligne">${maxAvailable}</span> (paiement en ligne)`;
      }
      document.getElementById('quantityLabel').textContent = '{{ __("Number of packages") }}';
      updateTotal();

      // Set form inputs
      packageIdInput.value = packageId;
      zoneIdInput.value = '';
      quantityInput.value = currentQuantity;

      modal.classList.remove('hidden');
    });
  });

  // Close modal
  closeModal.addEventListener('click', function() {
    modal.classList.add('hidden');
  });

  modal.addEventListener('click', function(e) {
    if (e.target === modal) {
      modal.classList.add('hidden');
    }
  });

  // Quantity controls
  decreaseBtn.addEventListener('click', function() {
    if (currentQuantity > 1) {
      currentQuantity--;
      updateQuantity();
    }
  });

  increaseBtn.addEventListener('click', function() {
    if (currentQuantity < maxAvailable) {
      currentQuantity++;
      updateQuantity();
    }
  });

  function updateQuantity() {
    quantitySpan.textContent = currentQuantity;
    quantityInput.value = currentQuantity;
    updateTotal();
  }

  function updateTotal() {
    if (currentItem) {
      const total = currentItem.price * currentQuantity;
      document.getElementById('totalPrice').textContent = formatPrice(total);
    }
  }

  function formatPrice(price) {
    const currency = window.currentCurrency || 'XOF';
    const locale = 'fr-FR';
    return new Intl.NumberFormat(locale, {
      style: 'currency',
      currency: currency,
      minimumFractionDigits: currency === 'XOF' ? 0 : 2
    }).format(price);
  }
});
</script>

@endsection
