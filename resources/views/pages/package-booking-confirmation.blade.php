@extends('layouts.app')

@section('title', 'Confirmation de réservation - ' . $booking->booking_reference . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8 sm:py-12">
  <div class="container mx-auto px-4">
    {{-- Success Header --}}
    <div class="max-w-4xl mx-auto mb-8">
      <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 text-center">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-6">
          <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
        </div>
        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-gray-900 mb-2 sm:mb-4">
          Réservation confirmée !
        </h1>
        <p class="text-base sm:text-lg text-gray-600 mb-4 sm:mb-6">
          Votre réservation de package a été enregistrée avec succès.
        </p>
        <div class="bg-purple-50 rounded-lg p-4 sm:p-6 inline-block">
          <p class="text-sm text-gray-600 mb-1">Numéro de réservation</p>
          <p class="text-xl sm:text-2xl font-black text-purple-600">{{ $booking->booking_reference }}</p>
        </div>
      </div>
    </div>

    {{-- Booking Details --}}
    <div class="max-w-4xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-6 sm:gap-8">
      {{-- Package Information --}}
      <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Détails du package</h2>

        {{-- Package Image --}}
        @php
          $packageImage = $booking->package->getFirstMediaUrl('avatar', 'normal');
          $placeholder = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=400&h=250&fit=crop';
        @endphp
        <div class="mb-4 sm:mb-6">
          <img src="{{ $packageImage ?: $placeholder }}" alt="{{ $booking->package->title }}"
               class="w-full h-40 sm:h-48 object-cover rounded-lg">
        </div>

        {{-- Package Details --}}
        <div class="space-y-3 sm:space-y-4"> 
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">{{ $booking->package->title }}</h3>
            <p class="text-gray-600 text-sm sm:text-base">{{ $booking->package->destination }}</p>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="text-sm text-gray-600">{{ $booking->package->duration_text_fr ?? $booking->package->duration . ' jours' }}</span>
            </div>
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span class="text-sm text-gray-600">{{ $booking->package->departure_city }}</span>
            </div>
          </div>

          @if($booking->special_requests)
          <div class="bg-gray-50 rounded-lg p-3 sm:p-4">
            <h4 class="font-semibold text-gray-900 mb-2">Demandes spéciales</h4>
            <p class="text-sm text-gray-600">{{ $booking->special_requests }}</p>
          </div>
          @endif
        </div>
      </div>

      {{-- Booking Information --}}
      <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Informations de réservation</h2>

        <div class="space-y-4 sm:space-y-6">
          {{-- Travel Details --}}
          <div>
            <h3 class="font-semibold text-gray-900 mb-3">Détails du voyage</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Date de départ</span>
                <span class="font-semibold">{{ \Carbon\Carbon::parse($booking->departure_date)->format('d/m/Y') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Nombre de participants</span>
                <span class="font-semibold">{{ $booking->participants }} personne{{ $booking->participants > 1 ? 's' : '' }}</span>
              </div>
            </div>
          </div>

          {{-- Customer Information --}}
          <div>
            <h3 class="font-semibold text-gray-900 mb-3">Informations client</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Nom</span>
                <span class="font-semibold">{{ $booking->user_name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Email</span>
                <span class="font-semibold">{{ $booking->user_email }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Téléphone</span>
                <span class="font-semibold">{{ $booking->user_phone }}</span>
              </div>
            </div>
          </div>

          {{-- Pricing --}}
          <div class="border-t pt-4">
            <h3 class="font-semibold text-gray-900 mb-3">Récapitulatif des prix</h3>
            <div class="space-y-2">
              <div class="flex justify-between">
                <span class="text-gray-600">Prix / personne</span>
                <span class="font-semibold">{{ \App\Helpers\CurrencyHelper::format($booking->unit_price) }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Nombre de participants</span>
                <span class="font-semibold">{{ $booking->participants }}</span>
              </div>
              <div class="flex justify-between text-lg font-bold text-purple-600 border-t pt-2">
                <span>Total</span>
                <span>{{ \App\Helpers\CurrencyHelper::format($booking->total_price) }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    {{-- Next Steps --}}
    <div class="max-w-4xl mx-auto mt-8">
      <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8">
        <h2 class="text-xl sm:text-2xl font-bold text-gray-900 mb-4 sm:mb-6">Prochaines étapes</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
          <div class="text-center">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Confirmation par email</h3>
            <p class="text-sm text-gray-600">Vous recevrez un email de confirmation avec tous les détails de votre réservation.</p>
          </div>

          <div class="text-center">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Validation du paiement</h3>
            <p class="text-sm text-gray-600">Notre équipe vérifiera votre paiement et vous contactera pour confirmer les détails.</p>
          </div>

          <div class="text-center">
            <div class="w-12 h-12 sm:w-16 sm:h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
              <svg class="w-6 h-6 sm:w-8 sm:h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
              </svg>
            </div>
            <h3 class="font-semibold text-gray-900 mb-2">Support client</h3>
            <p class="text-sm text-gray-600">Notre équipe est disponible pour répondre à toutes vos questions.</p>
          </div>
        </div>
      </div>
    </div>

    {{-- Actions --}}
    <div class="max-w-4xl mx-auto mt-8">
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="{{ route('packages') }}" class="bg-purple-600 text-white font-bold py-3 px-6 sm:py-4 sm:px-8 rounded-xl hover:bg-purple-700 transition-all text-center">
          Voir d'autres packages
        </a>
        <a href="{{ route('contact') }}" class="border-2 border-purple-300 text-purple-600 font-bold py-3 px-6 sm:py-4 sm:px-8 rounded-xl hover:bg-purple-50 transition-all text-center">
          Nous contacter
        </a>
        <a href="{{ config('carre_premium.contact.mobile_link') }}" class="border-2 border-purple-300 text-purple-600 font-bold py-3 px-6 sm:py-4 sm:px-8 rounded-xl hover:bg-purple-50 transition-all text-center">
          Appeler maintenant
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
