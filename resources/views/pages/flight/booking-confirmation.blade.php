@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-3xl mx-auto">
        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-2 border-green-200 rounded-2xl p-8 mb-8 shadow-lg">
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
        <div class="bg-white rounded-2xl shadow-3xl p-8 border-2 border-purple-100">
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

            <div class="mt-8 pt-8 border-t-2 border-purple-200">
                <a href="{{ route('flights') }}" class="block text-center bg-gradient-to-r from-purple-600 to-purple-700 text-white px-8 py-4 rounded-xl font-black shadow-lg hover:from-purple-700 hover:to-purple-800 transition-all duration-200">
                    Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
</div>
@endsection