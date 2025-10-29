@extends('admin.layouts.app')

@section('title', 'Détails Réservation #' . $booking->booking_number)

@section('content')

    <div class="max-w-7xl mx-auto py-8">
        <div class="flex justify-between items-center mb-6 border-b pb-2">
            <h1 class="text-3xl font-bold text-dark gradient-text">Détails de la Réservation #{{ $booking->booking_number }}
            </h1>
            <a href="{{ route('admin.bookings.index') }}"
                class="py-2 px-4 rounded-lg text-white font-semibold bg-gray-500 hover:bg-gray-600 transition duration-300 shadow-md">
                <i class="fas fa-arrow-left mr-2"></i> Retour à la Liste
            </a>
        </div>

        {{-- Messages de Session (pour les actions directes depuis cette page) --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Succès!</strong>
                <span class="block sm:inline">{!! session('success') !!}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Erreur!</strong>
                <span class="block sm:inline">{!! session('error') !!}</span>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- COLONNE GAUCHE: Informations Générales --}}
            <div class="lg:col-span-2 space-y-6">

                {{-- Statuts et Montant --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                            class="fas fa-info-circle text-primary mr-2"></i> Informations Clés</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div><span class="font-bold text-gray-700">Statut:</span>
                            @php
                                $status = $booking->status;
                                $statusClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'confirmed' => 'bg-green-100 text-green-800',
                                    'cancelled' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $statusClasses[$status] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($status) }}
                            </span>
                        </div>
                        <div><span class="font-bold text-gray-700">Paiement:</span>
                            @php
                                $paymentStatus = $booking->payment_status;
                                $paymentClasses = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-primary/20 text-primary',
                                    'failed' => 'bg-red-100 text-red-800',
                                ];
                            @endphp
                            <span
                                class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full {{ $paymentClasses[$paymentStatus] ?? 'bg-gray-100 text-gray-800' }}">
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </div>
                        <div class="col-span-2"><span class="font-bold text-gray-700">Montant Final:</span>
                            <span
                                class="text-2xl font-extrabold text-primary ml-2">{{ number_format($booking->final_amount, 2, ',', ' ') }}
                                {{ $booking->currency }}</span>
                        </div>
                        <div><span class="font-bold text-gray-700">Type:</span> {{ ucfirst($booking->booking_type) }}</div>
                        <div><span class="font-bold text-gray-700">Date de Réservation:</span>
                            {{ $booking->booking_date->format('d M Y H:i') }}</div>
                        <div><span class="font-bold text-gray-700">Date de Voyage/Événement:</span>
                            {{ $booking->travel_date->format('d M Y') }}</div>
                        <div><span class="font-bold text-gray-700">Passagers:</span> {{ $booking->number_of_passengers }}
                        </div>
                    </div>
                </div>

                {{-- Détails du Service (Vol/Événement/Package) --}}
                @if ($booking->flight)
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                        <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                                class="fas fa-plane text-primary mr-2"></i> Détails du Vol</h2>
                        <p class="text-sm"><span class="font-bold">Vol #:</span> {{ $booking->flight->flight_number ?? 'N/A' }}
                        </p>
                        <p class="text-sm"><span class="font-bold">Compagnie:</span>
                            {{ $booking->flight->airline->name ?? 'N/A' }}</p>
                        <p class="text-sm"><span class="font-bold">Itinéraire:</span>
                            {{ $booking->flight->departureAirport->code ?? 'N/A' }} &rarr;
                            {{ $booking->flight->arrivalAirport->code ?? 'N/A' }}</p>
                        <p class="text-sm"><span class="font-bold">Classe:</span> {{ ucfirst($booking->seat_class) }}</p>
                        <p class="text-sm"><span class="font-bold">Sièges:</span>
                            {{ is_array($booking->seat_numbers) ? implode(', ', $booking->seat_numbers) : ($booking->seat_numbers ?? 'Non assignés') }}
                        </p>
                    </div>
                @elseif ($booking->package)
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                        <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                                class="fas fa-suitcase-rolling text-primary mr-2"></i> Détails du Package</h2>
                        <p class="text-sm"><span class="font-bold">Nom du Package:</span> {{ $booking->package->name }}</p>
                        <p class="text-sm"><span class="font-bold">Description:</span>
                            {{ Str::limit($booking->package->description ?? 'N/A', 150) }}</p>
                    </div>
                @elseif ($booking->event)
                    <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                        <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                                class="fas fa-calendar-alt text-primary mr-2"></i> Détails de l'Événement</h2>
                        <p class="text-sm"><span class="font-bold">Nom de l'Événement:</span> {{ $booking->event->name }}</p>
                        <p class="text-sm"><span class="font-bold">Zone de Siège:</span> {{ $booking->seatZone->name ?? 'N/A' }}
                        </p>
                    </div>
                @endif

                {{-- Détails des Passagers --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                            class="fas fa-users text-primary mr-2"></i> Passagers ({{ $booking->number_of_passengers }})
                    </h2>
                    @if (is_array($booking->passenger_details) && count($booking->passenger_details) > 0)
                        <ul class="space-y-3">
                            @foreach ($booking->passenger_details as $passenger)
                                <li class="p-3 bg-gray-50 rounded-lg border border-gray-100 flex justify-between items-center">
                                    <span class="font-medium text-gray-800">{{ $passenger['full_name'] ?? 'Nom Inconnu' }}</span>
                                    <span
                                        class="text-sm text-gray-600">{{ $passenger['passport_number'] ?? 'Sans Passeport' }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-sm text-gray-500">Aucun détail de passager disponible.</p>
                    @endif
                    <div class="mt-4"><span class="font-bold text-gray-700">Demandes Spéciales:</span>
                        <p class="text-sm mt-1">{{ $booking->special_requests ?? 'Aucune' }}</p>
                    </div>
                </div>

                {{-- Détails de l'Annulation --}}
                @if ($booking->status === 'cancelled')
                    <div class="bg-red-50 p-6 rounded-xl shadow-lg border border-red-200">
                        <h2 class="text-xl font-semibold mb-4 text-red-700 border-b pb-2"><i
                                class="fas fa-times-circle mr-2"></i> Annulation</h2>
                        <p class="text-sm"><span class="font-bold text-red-700">Date d'Annulation:</span>
                            {{ $booking->cancelled_at->format('d M Y H:i') }}</p>
                        <p class="text-sm mt-2"><span class="font-bold text-red-700">Raison:</span></p>
                        <p class="p-3 bg-red-100 rounded-lg mt-1 text-sm text-red-900">
                            {{ $booking->cancellation_reason ?? 'Aucune raison spécifiée.' }}</p>
                    </div>
                @endif
            </div>

            {{-- COLONNE DROITE: Client et Actions --}}
            <div class="lg:col-span-1 space-y-6">

                {{-- Infos Client --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100 sticky top-6">
                    <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                            class="fas fa-user-circle text-primary mr-2"></i> Client</h2>
                    @if ($booking->user)
                        <p class="text-lg font-bold">{{ $booking->user->name }}</p>
                        <p class="text-sm text-gray-600 mb-2">{{ $booking->user->email }}</p>
                        {{-- Assurez-vous que cette route existe --}}
                        {{-- <a href="{{ route('admin.members.show', $booking->user_id) }}"
                            class="text-sm font-semibold text-primary hover:text-purple-700 transition duration-150">
                            <i class="fas fa-external-link-alt mr-1"></i> Voir le profil Admin
                        </a> --}}
                    @else
                        <p class="text-sm text-red-500">Client supprimé ou non-enregistré.</p>
                    @endif
                </div>

                {{-- Actions Rapides --}}
                <div class="bg-white p-6 rounded-xl shadow-lg border border-gray-100">
                    <h2 class="text-xl font-semibold mb-4 text-dark border-b pb-2"><i
                            class="fas fa-cog text-primary mr-2"></i> Actions de Gestion</h2>

                    {{-- Action: Valider/Confirmer --}}
                    @if ($booking->status === 'pending')
                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST"
                            onsubmit="return confirm('Êtes-vous sûr de vouloir confirmer cette réservation ?');" class="mb-3">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="w-full py-2 px-4 rounded-lg text-white font-semibold bg-green-600 hover:bg-green-700 transition duration-300 shadow-md">
                                <i class="fas fa-check-circle mr-2"></i> VALIDER la Réservation
                            </button>
                        </form>
                    @elseif($booking->status === 'confirmed')
                        <p class="text-green-600 font-semibold mb-3"><i class="fas fa-check-double mr-2"></i> Réservation
                            Confirmée.</p>
                    @endif

                    {{-- Action: Payer (Marquer comme payé) --}}
                    @if ($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                        <form action="{{ route('admin.bookings.pay', $booking) }}" method="POST"
                            onsubmit="return confirm('Marquer manuellement cette réservation comme payée ?');" class="mb-3">
                            @csrf
                            @method('PUT')
                            <button type="submit"
                                class="w-full py-2 px-4 rounded-lg text-white font-semibold bg-indigo-600 hover:bg-indigo-700 transition duration-300 shadow-md">
                                <i class="fas fa-dollar-sign mr-2"></i> MARQUER comme Payé
                            </button>
                        </form>
                    @elseif($booking->payment_status === 'paid')
                        <p class="text-primary font-semibold mb-3"><i class="fas fa-money-bill-alt mr-2"></i> Paiement Effectué.
                        </p>
                    @endif

                    {{-- Action: Annuler --}}
                    @if ($booking->status !== 'cancelled')
                        <button type="button"
                            onclick="showCancelModal('{{ $booking->booking_number }}', '{{ route('admin.bookings.cancel', $booking) }}')"
                            class="w-full py-2 px-4 rounded-lg text-white font-semibold bg-red-600 hover:bg-red-700 transition duration-300 shadow-md">
                            <i class="fas fa-times-circle mr-2"></i> ANNULER la Réservation
                        </button>
                    @else
                        <p class="text-red-600 font-semibold"><i class="fas fa-times mr-2"></i> Réservation Annulée.</p>
                    @endif

                </div>
            </div>
        </div>


    </div>

    {{-- MODAL D'ANNULATION (Réutilisée ici) --}}

    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg mx-4">
            <h3 class="text-xl font-bold mb-4 text-red-700 border-b pb-2">Annuler la Réservation <span
                    id="modalBookingNumber" class="text-dark"></span></h3>
            <form id="cancelForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST">

                <div class="mb-4">
                    <label for="cancellation_reason_show" class="block text-sm font-medium text-gray-700 mb-1">Raison de
                        l'annulation (obligatoire)</label>
                    <textarea name="cancellation_reason" id="cancellation_reason_show" rows="4" required minlength="10"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition duration-150"
                        placeholder="Veuillez détailler clairement la raison de l'annulation..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                        onclick="document.getElementById('cancelModal').classList.add('hidden'); document.getElementById('cancelModal').classList.remove('flex');"
                        class="py-2 px-4 rounded-lg text-gray-700 font-semibold bg-gray-200 hover:bg-gray-300 transition duration-150">
                        Fermer
                    </button>
                    <button type="submit"
                        class="py-2 px-4 rounded-lg text-white font-semibold bg-red-600 hover:bg-red-700 transition duration-150 shadow-md">
                        <i class="fas fa-times-circle mr-2"></i> Confirmer l'Annulation
                    </button>
                </div>
            </form>
        </div>


    </div>

    <script>
        /**
        * Affiche la modale d'annulation et configure le formulaire.
        * @param {string} bookingNumber Le numéro de la réservation
        * @param {string} actionUrl L'URL POST vers la route d'annulation
        */
        function showCancelModal(bookingNumber, actionUrl) {
            document.getElementById('modalBookingNumber').textContent = '#' + bookingNumber;
            document.getElementById('cancelForm').action = actionUrl;
            // Utilisez la référence correcte pour la textarea dans cette vue
            const reasonInput = document.getElementById('cancellation_reason_show') || document.getElementById('cancellation_reason');
            if (reasonInput) reasonInput.value = '';

            document.getElementById(&#39; cancelModal &#39;).classList.remove(&#39; hidden &#39;);
            document.getElementById(&#39; cancelModal &#39;).classList.add(&#39; flex &#39;);
        }

        // Gère la fermeture de la modale en cliquant en dehors
        document.getElementById(&#39; cancelModal &#39;).addEventListener(&#39; click &#39;, function(event) {
            if (event.target === this) {
                this.classList.add(&#39; hidden &#39;);
                this.classList.remove(&#39; flex &#39;);
            }
        });


    </script>

@endsection