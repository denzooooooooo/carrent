@extends('admin.layouts.app')

@section('title', 'Gestion des Réservations')

@section('content')

    <div class="max-w-7xl mx-auto py-8">
        <h1 class="text-3xl font-bold mb-8 text-dark gradient-text border-b pb-2">Liste des Réservations Clients</h1>

        {{-- Messages de Session --}}
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
        @if (session('warning'))
            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Attention!</strong>
                <span class="block sm:inline">{!! session('warning') !!}</span>
            </div>
        @endif

        {{-- Filtres --}}
        <div class="bg-white p-6 rounded-xl shadow-lg mb-8 border border-gray-100">
            <h2 class="text-xl font-semibold mb-4 text-gray-800">Filtrer les Réservations</h2>
            <form action="{{ route('admin.bookings.index') }}" method="GET"
                class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Statut de la
                        Réservation</label>
                    <select name="status" id="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
                        <option value="all">Tous les Statuts</option>
                        @foreach ($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Statut du
                        Paiement</label>
                    <select name="payment_status" id="payment_status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary transition duration-150">
                        <option value="all">Tous les Paiements</option>
                        @foreach ($paymentStatuses as $key => $label)
                            <option value="{{ $key }}" {{ request('payment_status') === $key ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex space-x-2">
                    <button type="submit"
                        class="flex-1 py-2 px-4 rounded-lg text-white font-semibold bg-primary hover:bg-purple-700 transition duration-300 shadow-md">
                        <i class="fas fa-filter mr-2"></i> Filtrer
                    </button>
                    <a href="{{ route('admin.bookings.index') }}"
                        class="py-2 px-4 rounded-lg text-gray-700 font-semibold bg-gray-200 hover:bg-gray-300 transition duration-300 shadow-md">
                        <i class="fas fa-redo-alt"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>

        {{-- Tableau des Réservations --}}
        <div class="bg-white p-6 rounded-xl shadow-2xl border border-gray-100 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Réservation #
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Client
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Service
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Montant Final
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Statut
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paiement
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                    class="text-primary hover:underline font-bold">
                                    {{ $booking->booking_number }}
                                </a>
                                <div class="text-xs text-gray-500">{{ $booking->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $booking->user->name ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                <div class="font-semibold">{{ $booking->booking_type }}</div>
                                @if ($booking->flight)
                                    <div class="text-xs text-gray-500">Vol #{{ $booking->flight->flight_number ?? 'N/A' }}</div>
                                @elseif($booking->package)
                                    <div class="text-xs text-gray-500">Package: {{ $booking->package->name ?? 'N/A' }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">
                                {{ number_format($booking->final_amount, 2, ',', ' ') }} {{ $booking->currency }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-green-100 text-green-800',
                                        'cancelled' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$booking->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statuses[$booking->status] ?? ucfirst($booking->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm">
                                @php
                                    $paymentClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'paid' => 'bg-primary/20 text-primary',
                                        'failed' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span
                                    class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $paymentClasses[$booking->payment_status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ ucfirst($booking->payment_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end space-x-2">
                                    {{-- Voir Détails --}}
                                    <a href="{{ route('admin.bookings.show', $booking) }}" title="Voir les détails"
                                        class="text-primary hover:text-purple-700 p-2 rounded-full hover:bg-gray-100 transition duration-150">
                                        <i class="fas fa-eye"></i>
                                    </a>

                                    {{-- Action: Valider/Confirmer --}}
                                    @if ($booking->status === 'pending')
                                        <form action="{{ route('admin.bookings.confirm', $booking) }}" method="POST"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir confirmer cette réservation ?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" title="Confirmer la réservation (Validation)"
                                                class="text-green-600 hover:text-green-800 p-2 rounded-full hover:bg-green-100 transition duration-150">
                                                <i class="fas fa-check-circle"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Action: Payer (Marquer comme payé) --}}
                                    @if ($booking->payment_status !== 'paid' && $booking->status !== 'cancelled')
                                        <form action="{{ route('admin.bookings.pay', $booking) }}" method="POST"
                                            onsubmit="return confirm('Marquer manuellement cette réservation comme payée ?');">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" title="Marquer comme payé (Manuel)"
                                                class="text-indigo-600 hover:text-indigo-800 p-2 rounded-full hover:bg-indigo-100 transition duration-150">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                        </form>
                                    @endif

                                    {{-- Action: Annuler --}}
                                    @if ($booking->status !== 'cancelled')
                                        <button type="button"
                                            onclick="showCancelModal('{{ $booking->booking_number }}', '{{ route('admin.bookings.cancel', $booking) }}')"
                                            title="Annuler la réservation"
                                            class="text-red-600 hover:text-red-800 p-2 rounded-full hover:bg-red-100 transition duration-150">
                                            <i class="fas fa-times-circle"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                Aucune réservation trouvée avec les filtres actuels.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        </div>


    </div>

    {{-- MODAL D'ANNULATION (Utilisé par le JS ci-dessous) --}}

    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-75 hidden items-center justify-center z-50">
        <div class="bg-white rounded-xl shadow-2xl p-6 w-full max-w-lg mx-4">
            <h3 class="text-xl font-bold mb-4 text-red-700 border-b pb-2">Annuler la Réservation <span
                    id="modalBookingNumber" class="text-dark"></span></h3>
            <form id="cancelForm" method="POST">
                @csrf
                <input type="hidden" name="_method" value="POST"> {{-- Utilise POST comme défini dans les routes --}}

                <div class="mb-4">
                    <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-1">Raison de
                        l'annulation (obligatoire)</label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="4" required minlength="10"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-red-500 focus:border-red-500 transition duration-150 @error('cancellation_reason') border-red-500 @enderror"
                        placeholder="Veuillez détailler clairement la raison de l'annulation..."></textarea>
                    @error('cancellation_reason')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
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
            document.getElementById('cancellation_reason').value = ''; // Réinitialiser le champ
            document.getElementById('cancelModal').classList.remove('hidden');
            document.getElementById('cancelModal').classList.add('flex');
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