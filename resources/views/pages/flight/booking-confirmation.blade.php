@extends('layouts.app')

@section('title', 'Confirmation de réservation - Carré Premium')

@section('content')
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="container mx-auto px-4 py-8">
            <div class="max-w-2xl mx-auto">
                <!-- En-tête de confirmation -->
                <div class="text-center mb-8">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Réservation confirmée !</h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Votre vol a été réservé avec succès
                    </p>
                </div>

                <!-- Carte de confirmation -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                    <div class="border-b border-gray-200 dark:border-gray-600 pb-4 mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                                    Référence : {{ $booking_reference }}
                                </h2>
                                <p class="text-gray-600 dark:text-gray-400">
                                    Réservé le {{ $booking_date }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-2xl font-bold text-green-600">
                                    {{ $flight_details['price'] }} €
                                </p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Paiement confirmé</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Compagnie aérienne</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $flight_details['airline'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Passagers</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $flight_details['passenger_count'] }} personne(s)</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Email de contact</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $flight_details['contact_email'] }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Téléphone</p>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $flight_details['contact_phone'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prochaines étapes -->
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-3">Prochaines étapes</h3>
                    <ul class="space-y-2 text-blue-800 dark:text-blue-200">
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Vous recevrez un email de confirmation sous 24h</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Présentez votre pièce d'identité à l'embarquement</span>
                        </li>
                        <li class="flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span>Enregistrement en ligne disponible 24h avant le vol</span>
                        </li>
                    </ul>
                </div>

                <!-- Actions -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('flights') }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                        Nouvelle recherche
                    </a>
                    <button onclick="window.print()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors text-center">
                        Imprimer la confirmation
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection