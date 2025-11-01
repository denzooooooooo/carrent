@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
            <div class="flex items-center mb-4">
                <svg class="w-12 h-12 text-green-500 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h1 class="text-2xl font-bold text-green-800">Réservation confirmée !</h1>
                    <p class="text-green-700">Numéro de réservation : <strong>{{ $booking->booking_number }}</strong></p>
                </div>
            </div>
            <p class="text-green-700">
                Votre réservation a été enregistrée avec succès. Un administrateur la traitera dans les plus brefs délais.
                Vous recevrez une confirmation par email.
            </p>
        </div>

        <!-- Détails de la réservation -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h2 class="text-xl font-semibold mb-4">Détails de votre réservation</h2>
            
            <div class="space-y-4">
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Date de réservation</span>
                    <span class="font-semibold">{{ $booking->booking_date->format('d/m/Y H:i') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Date de voyage</span>
                    <span class="font-semibold">{{ $booking->travel_date->format('d/m/Y') }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Nombre de passagers</span>
                    <span class="font-semibold">{{ $booking->number_of_passengers }}</span>
                </div>
                <div class="flex justify-between py-2 border-b">
                    <span class="text-gray-600">Classe</span>
                    <span class="font-semibold">{{ $booking->seat_class }}</span>
                </div>
                <div class="flex justify-between py-2 font-bold text-lg">
                    <span>Montant total</span>
                    <span class="text-blue-600">{{ number_format($booking->final_amount, 2) }} {{ $booking->currency }}</span>
                </div>
            </div>

            <div class="mt-6 pt-6 border-t">
                <a href="{{ route('flights') }}" class="block text-center bg-blue-600 text-white px-6 py-3 rounded font-semibold hover:bg-blue-700">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection