@extends('layouts.mail')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600 to-amber-600 px-6 py-8 text-white text-center">
            <h1 class="text-2xl font-bold mb-2">
                {{ $booking->status === 'confirmed' ? 'Confirmation de réservation' : 'Réservation enregistrée' }}
            </h1>
            <p class="text-purple-100">
                {{ $booking->status === 'confirmed'
                    ? 'Votre réservation d\'événement a été confirmée'
                    : 'Votre demande est enregistrée. Le paiement finalisera la confirmation.' }}
            </p>
        </div>

        {{-- Content --}}
        <div class="p-6">
            {{-- Success Message --}}
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-green-800">Réservation confirmée !</h3>
                        <p class="text-green-700">
                            {{ $booking->status === 'confirmed'
                                ? 'Votre réservation a été confirmée avec succès.'
                                : 'Votre réservation a été créée avec succès.' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Booking Details --}}
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Détails de la réservation</h3>

                <div class="space-y-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Référence:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->booking_reference }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Événement:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->event->title }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">{{ $booking->selection_type_label }}:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->selection_label }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Nombre de places:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->quantity }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Prix unitaire:</span>
                        <span class="font-semibold text-gray-900">{{ \App\Helpers\CurrencyHelper::format($booking->unit_price) }}</span>
                    </div>

                    <div class="border-t pt-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total:</span>
                            <span class="text-purple-600">{{ \App\Helpers\CurrencyHelper::format($booking->total_price) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Customer Details --}}
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Informations client</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Nom:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->user_name }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Email:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->user_email }}</span>
                    </div>

                    <div class="flex justify-between">
                        <span class="text-gray-600">Téléphone:</span>
                        <span class="font-semibold text-gray-900">{{ $booking->user_phone }}</span>
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
                          @endswitch
                        </span>
                    </div>
                </div>
            </div>

            {{-- Event Details --}}
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-xl font-bold text-gray-900 mb-4">Détails de l'événement</h3>

                <div class="space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Date:</span>
                        <span class="font-semibold text-gray-900">{{ optional($booking->packageOption?->option_date ?? $booking->event->event_date)->format('d/m/Y') }}</span>
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

            {{-- Important Notice --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-start">
                    <div class="w-5 h-5 bg-blue-100 rounded-full flex items-center justify-center mr-3 mt-0.5">
                        <svg class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-1">Informations importantes</h4>
                        <p class="text-sm text-blue-700">
                            @if($booking->status === 'confirmed')
                                Conservez cette référence de réservation. Vos documents définitifs sont envoyés après validation du paiement.
                            @else
                                Conservez cette référence de réservation. Vous recevrez vos documents définitifs dès validation du paiement.
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="bg-gray-50 px-6 py-4 text-center">
            <p class="text-sm text-gray-600">
                Merci d'avoir choisi Carré Premium pour votre événement !
            </p>
            <p class="text-xs text-gray-500 mt-1">
                © {{ date('Y') }} Carré Premium. Tous droits réservés.
            </p>
        </div>
    </div>
</div>
@endsection
