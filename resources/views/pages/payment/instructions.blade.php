@extends('layouts.app')

@section('title', 'Instructions de paiement - ' . $booking->booking_number . ' - Carré Premium')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto">
      {{-- Header --}}
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Instructions de paiement</h1>
            <p class="text-gray-600 mt-1">Référence: <span class="font-semibold">{{ $booking->booking_number }}</span></p>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-500">Montant à payer</p>
            <p class="text-2xl font-bold text-purple-600">{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Booking Details --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Détails de la réservation</h2>

          @if($booking->booking_type === 'event')
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Événement:</span>
                <span class="font-semibold text-gray-900">{{ $booking->event->title_fr }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Zone:</span>
                <span class="font-semibold text-gray-900">{{ $booking->seatZone->zone_name_fr }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Nombre de places:</span>
                <span class="font-semibold text-gray-900">{{ $booking->number_of_passengers }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Date:</span>
                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->event->event_date)->format('d/m/Y') }}</span>
              </div>
            </div>
          @elseif($booking->booking_type === 'package')
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Package:</span>
                <span class="font-semibold text-gray-900">{{ $booking->package->title_fr }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Destination:</span>
                <span class="font-semibold text-gray-900">{{ $booking->package->destination }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Participants:</span>
                <span class="font-semibold text-gray-900">{{ $booking->number_of_passengers }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Date de départ:</span>
                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->travel_date)->format('d/m/Y') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Durée:</span>
                <span class="font-semibold text-gray-900">{{ $booking->package->duration }} jours</span>
              </div>
            </div>
          @elseif($booking->booking_type === 'location')
            <div class="space-y-4">
              <div class="flex justify-between">
                <span class="text-gray-600">Location:</span>
                <span class="font-semibold text-gray-900">{{ $booking->location->name }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Période:</span>
                <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($booking->travel_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($booking->locationBooking->end_date)->format('d/m/Y') }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Durée:</span>
                <span class="font-semibold text-gray-900">{{ $booking->locationBooking->days }} jours</span>
              </div>
            </div>
          @endif
        </div>

        {{-- Payment Instructions --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Méthodes de paiement disponibles</h2>

          <div class="space-y-6">
            {{-- Mobile Money --}}
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                <span class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center mr-3">
                  📱
                </span>
                Mobile Money
              </h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                  <span>Orange Money:</span>
                  <span class="font-mono font-semibold">+225 07 79 28 49 25</span>
                </div>
               
              </div>
            </div>

            {{-- Bank Transfer --}}
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                <span class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                  🏦
                </span>
                Virement bancaire
              </h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                  <span>Banque:</span>
                  <span class="font-semibold">NSIA Banque</span>
                </div>
                <div class="flex justify-between">
                  <span>IBAN:</span>
                  <span class="font-mono font-semibold">CI042 0121 2033 0249 02001 </span>
                </div>
                <div class="flex justify-between">
                  <span>Nom du bénéficiaire:</span>
                  <span class="font-semibold">Carré Premium</span>
                </div>
              </div>
            </div>

            {{-- Wave --}}
            <div class="border border-gray-200 rounded-lg p-4">
              <h3 class="font-semibold text-gray-900 mb-3 flex items-center">
                <span class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                  🌊
                </span>
                Wave
              </h3>
              <div class="space-y-2 text-sm text-gray-600">
                <div class="flex justify-between">
                  <span>Numéro Wave:</span>
                  <span class="font-mono font-semibold">+225 01 01 22 15 15</span>
                </div>
                <div class="flex justify-between">
                  <span>Nom:</span>
                  <span class="font-semibold">Carré Premium</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      {{-- Instructions --}}
      <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
        <div class="flex items-start">
          <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div>
            <h4 class="text-lg font-semibold text-blue-800 mb-2">Instructions importantes</h4>
            <ol class="text-sm text-blue-700 space-y-1 list-decimal list-inside">
              <li>Choisissez votre méthode de paiement préférée parmi celles proposées ci-dessus</li>
              <li>Effectuez le paiement du montant exact: <strong>{{ \App\Helpers\CurrencyHelper::format($booking->final_amount) }}</strong></li>
              <li>Prenez une capture d'écran ou une photo de la preuve de paiement</li>
              <li>Envoyez la preuve de paiement par email à: <strong>payments@carrepremium.ci</strong></li>
              <li>Mentionnez votre numéro de réservation: <strong>{{ $booking->booking_number }}</strong> dans l'objet de l'email</li>
              <li>Notre équipe traitera votre paiement dans les plus brefs délais (24-48h)</li>
              <li>Vous recevrez votre ticket/récu par email une fois le paiement confirmé</li>
            </ol>
          </div>
        </div>
      </div>

      {{-- Contact Info --}}
      <div class="bg-green-50 border border-green-200 rounded-lg p-6 mt-6">
        <div class="flex items-start">
          <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
            <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
          </div>
          <div>
            <h4 class="text-lg font-semibold text-green-800 mb-2">Support paiement</h4>
            <p class="text-sm text-green-700 mb-2">
              Besoin d'aide ? Contactez notre équipe support paiement
            </p>
            <div class="flex flex-col sm:flex-row gap-2 text-sm">
              <a href="mailto:payments@carrepremium.ci" class="text-green-600 hover:text-green-700 font-medium">
                📧 payments@carrepremium.ci
              </a>
              <span class="hidden sm:inline text-green-600">•</span>
              <a href="tel:+2252721594258" class="text-green-600 hover:text-green-700 font-medium">
                📞 +225 27 21 59 42 58
              </a>
            </div>
          </div>
        </div>
      </div>

      {{-- Action Buttons --}}
      <div class="flex flex-col sm:flex-row gap-4 mt-6">
        <a href="{{ route('home') }}" class="flex-1 bg-gray-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-gray-700 transition-colors text-center">
          Retour à l'accueil
        </a>
        <a href="mailto:payments@carrepremium.ci?subject=Preuve de paiement - {{ $booking->booking_number }}" class="flex-1 bg-purple-600 text-white font-bold py-3 px-6 rounded-lg hover:bg-purple-700 transition-colors text-center">
          Envoyer la preuve de paiement
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
