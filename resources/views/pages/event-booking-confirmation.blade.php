@extends('layouts.app')

@section('title', 'Confirmation de réservation - ' . $booking->event->title . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="container mx-auto px-4">
    <div class="max-w-2xl mx-auto">
      {{-- Success Message --}}
      <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
          <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <div>
            <h2 class="text-lg font-semibold text-green-800">Réservation confirmée !</h2>
            <p class="text-green-700">Votre réservation a été créée avec succès.</p>
          </div>
        </div>
      </div>

      {{-- Booking Details --}}
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Détails de la réservation</h3>

        <div class="space-y-4">
          <div class="flex justify-between">
            <span class="text-gray-600">Référence:</span>
            <span class="font-semibold text-gray-900">{{ $booking->booking_number }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Événement:</span>
            <span class="font-semibold text-gray-900">{{ $booking->event->title }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Zone:</span>
            <span class="font-semibold text-gray-900">{{ $booking->seatZone->zone_name_fr ?? $booking->seatZone->zone_name_en }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Nombre de places:</span>
            <span class="font-semibold text-gray-900">{{ $booking->number_of_passengers }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Prix unitaire:</span>
            <span class="font-semibold text-gray-900">{{ \App\Helpers\CurrencyHelper::format($booking->total_amount / $booking->number_of_passengers) }}</span>
          </div>

          <div class="border-t pt-4">
            <div class="flex justify-between text-lg font-bold">
              <span>Total:</span>
              <span class="text-purple-600">{{ \App\Helpers\CurrencyHelper::format($booking->total_amount) }}</span>
            </div>
          </div>
        </div>
      </div>

      {{-- Customer Details --}}
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Informations client</h3>

        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-gray-600">Nom:</span>
            <span class="font-semibold text-gray-900">{{ $booking->passenger_details[0]['name'] ?? '' }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Email:</span>
            <span class="font-semibold text-gray-900">{{ $booking->passenger_details[0]['email'] ?? '' }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Téléphone:</span>
            <span class="font-semibold text-gray-900">{{ $booking->passenger_details[0]['phone'] ?? '' }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Statut:</span>
            <span class="px-3 py-1 rounded-full text-sm font-semibold
              @if($booking->status === 'pending') bg-yellow-100 text-yellow-800
              @elseif($booking->status === 'confirmed') bg-green-100 text-green-800
              @elseif($booking->status === 'cancelled') bg-red-100 text-red-800
              @else bg-gray-100 text-gray-800 @endif">
              @switch($booking->status)
                @case('pending') En attente @break
                @case('confirmed') Confirmée @break
                @case('cancelled') Annulée @break
                @case('completed') Terminée @break
              @endswitch
            </span>
          </div>
        </div>
      </div>

      {{-- Event Details --}}
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Détails de l'événement</h3>

        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-gray-600">Date:</span>
            <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->event->event_date)->format('l d F Y') }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Heure:</span>
            <span class="font-semibold text-gray-900">{{ $booking->event->event_time }}</span>
          </div>

          <div class="flex justify-between">
            <span class="text-gray-600">Lieu:</span>
            <span class="font-semibold text-gray-900">{{ $booking->event->venue_name }}</span>
          </div>
        </div>
      </div>

      {{-- Actions --}}
      <div class="flex flex-col sm:flex-row gap-4">
        <a href="{{ route('events.show', $booking->event->slug) }}"
           class="flex-1 bg-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors text-center">
          Retour à l'événement
        </a>
        <a href="{{ route('events') }}"
           class="flex-1 border-2 border-purple-600 text-purple-600 font-bold py-3 px-6 rounded-lg hover:bg-purple-50 transition-colors text-center">
          Voir tous les événements
        </a>
      </div>

      {{-- Important Notice --}}
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
        <div class="flex items-start">
          <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
            <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h4 class="text-sm font-semibold text-blue-800 mb-1">Informations importantes</h4>
            <p class="text-sm text-blue-700">
              Un email de confirmation vous a été envoyé. Conservez votre référence de réservation.
              Pour toute question, contactez-nous au +225 XX XX XX XX.
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
