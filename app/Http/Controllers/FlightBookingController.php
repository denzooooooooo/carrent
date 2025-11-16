<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FlightBookingController extends Controller
{
    // ============================================
    // AFFICHAGE DES DÉTAILS PAR TYPE DE VOL
    // ============================================

    /**
     * Affiche les détails d'un vol ALLER SIMPLE
     */
    /* public function detailsOneWay(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'departure_id' => 'nullable|string',
            'arrival_id' => 'nullable|string',
            'outbound_date' => 'nullable|date',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'travel_class' => 'nullable|string',
        ]);

        try {
            Log::info('🔍 Récupération détails vol ALLER SIMPLE', [
                'booking_token' => substr($validated['booking_token'], 0, 50) . '...',
                'price' => $validated['price'],
            ]);

            // Récupérer les détails complets du vol
            $flightData = $this->fetchFlightDetails([
                'booking_token' => $validated['booking_token'],
                'currency' => $validated['currency'],
            ]);

            if (!$flightData) {
                return $this->returnDetailsError(
                    'pages.flight.flight-details-one-way',
                    'Les détails du vol ne sont plus disponibles. Veuillez effectuer une nouvelle recherche.',
                    $validated
                );
            }

            // Extraire le vol sélectionné
            $selectedFlight = $this->extractSelectedFlight($flightData);

            if (!$selectedFlight) {
                return $this->returnDetailsError(
                    'flight-details-one-way',
                    'Aucun vol trouvé avec ce token. Le vol n\'est peut-être plus disponible.',
                    $validated
                );
            }

            // Ajouter le prix si absent
            if (!isset($selectedFlight['price'])) {
                $selectedFlight['price'] = $validated['price'];
            }

            Log::info('✅ Détails vol ALLER SIMPLE récupérés', [
                'airline' => $selectedFlight['flights'][0]['airline'] ?? 'N/A',
                'price' => $selectedFlight['price'],
            ]);

            return view('pages.flight.flight-details-one-way', [
                'error' => null,
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $flightData['booking_options'] ?? [],
                'searchParams' => $validated,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur détails vol ALLER SIMPLE', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->returnDetailsError(
                'flight-details-one-way',
                'Une erreur est survenue lors du chargement : ' . $e->getMessage(),
                $validated
            );
        }
    } */

    public function detailsOneWay(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'price' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'departure_id' => 'nullable|string',
            'arrival_id' => 'nullable|string',
            'outbound_date' => 'nullable|date',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'travel_class' => 'nullable|string',
        ]);

        try {
            Log::info('🔍 Récupération détails vol ALLER SIMPLE', [
                'booking_token' => substr($validated['booking_token'], 0, 50) . '...',
                'price' => $validated['price'],
            ]);

            // ⭐ ESSAYER D'ABORD DE RÉCUPÉRER DEPUIS LA SESSION
            $selectedFlight = $this->getFlightFromSession($validated['booking_token']);

            // Si trouvé en session, on l'utilise directement
            if ($selectedFlight) {
                Log::info('✅ Vol trouvé en session');

                // Ajouter le prix si absent
                if (!isset($selectedFlight['price'])) {
                    $selectedFlight['price'] = $validated['price'];
                }

                return view('pages.flight.flight-details-one-way', [
                    'error' => null,
                    'selectedFlight' => $selectedFlight,
                    'bookingOptions' => [], // Options vides pour l'instant
                    'searchParams' => $validated,
                ]);
            }

            // ⭐ SINON, ESSAYER L'API (avec departure_token si c'est ça qu'on a)
            Log::info('⚠️ Vol non trouvé en session, tentative API...');

            $flightData = $this->fetchFlightDetails([
                'booking_token' => $validated['booking_token'],
                'currency' => $validated['currency'],
                'hl' => 'fr',
                'gl' => 'ci',
            ]);

            if (!$flightData) {
                return $this->returnDetailsError(
                    'pages.flight.flight-details-one-way',
                    'Les détails du vol ne sont plus disponibles. Veuillez effectuer une nouvelle recherche.',
                    $validated
                );
            }

            $selectedFlight = $this->extractSelectedFlight($flightData);

            if (!$selectedFlight) {
                return $this->returnDetailsError(
                    'pages.flight.flight-details-one-way',
                    'Aucun vol trouvé avec ce token. Le vol n\'est peut-être plus disponible.',
                    $validated
                );
            }

            if (!isset($selectedFlight['price'])) {
                $selectedFlight['price'] = $validated['price'];
            }

            Log::info('✅ Détails vol ALLER SIMPLE récupérés via API');

            return view('pages.flight.flight-details-one-way', [
                'error' => null,
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $flightData['booking_options'] ?? [],
                'searchParams' => $validated,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur détails vol ALLER SIMPLE', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->returnDetailsError(
                'pages.flight.flight-details-one-way',
                'Une erreur est survenue lors du chargement : ' . $e->getMessage(),
                $validated
            );
        }
    }


    /**
     * Affiche les détails d'un vol (méthode générique - fallback)
     * @deprecated Utiliser detailsOneWay() ou detailsRoundTrip() à la place
     */
    public function details(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'departure_id' => 'nullable|string',
            'arrival_id' => 'nullable|string',
            'outbound_date' => 'nullable|date',
            'return_date' => 'nullable|date',
            'currency' => 'nullable|string|size:3',
        ]);

        try {
            Log::info('🔍 Récupération détails vol GÉNÉRIQUE', [
                'booking_token' => substr($validated['booking_token'], 0, 50) . '...',
            ]);

            $params = [
                'booking_token' => $validated['booking_token'],
                'currency' => $validated['currency'] ?? 'EUR',
            ];

            if (!empty($validated['departure_id'])) {
                $params['departure_id'] = strtoupper($validated['departure_id']);
            }
            if (!empty($validated['arrival_id'])) {
                $params['arrival_id'] = strtoupper($validated['arrival_id']);
            }
            if (!empty($validated['outbound_date'])) {
                $params['outbound_date'] = $validated['outbound_date'];
            }

            // Déterminer le type de vol
            if (!empty($validated['return_date'])) {
                $params['type'] = 1; // Round trip
                $params['return_date'] = $validated['return_date'];
            } elseif (!empty($validated['departure_id']) && !empty($validated['arrival_id'])) {
                $params['type'] = 2; // One way
            }

            $flightData = $this->fetchFlightDetails($params);

            if (!$flightData) {
                return $this->returnDetailsError(
                    'pages.flight.details',
                    'Impossible de récupérer les détails du vol.',
                    $validated
                );
            }

            $selectedFlight = $flightData['selected_flights'][0] ?? null;
            $bookingOptions = $flightData['booking_options'] ?? [];

            return view('pages.flight.details', [
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $bookingOptions,
                'error' => null,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Exception détails vol GÉNÉRIQUE', [
                'message' => $e->getMessage()
            ]);

            return $this->returnDetailsError(
                'pages.flight.details',
                'Une erreur est survenue.',
                $validated
            );
        }
    }

    /**
     * Récupère un vol spécifique depuis les résultats en session
     */
    private function getFlightFromSession($bookingToken): ?array
    {
        $sessionData = session('flight_search_results');

        if (!$sessionData || empty($sessionData['results'])) {
            return null;
        }

        // Vérifier que les données ne sont pas trop anciennes (30 min max)
        if (now()->timestamp - ($sessionData['timestamp'] ?? 0) > 1800) {
            session()->forget('flight_search_results');
            return null;
        }

        $results = $sessionData['results'];
        $allFlights = array_merge(
            $results['best_flights'] ?? [],
            $results['other_flights'] ?? []
        );

        // Chercher le vol correspondant au booking_token
        foreach ($allFlights as $flight) {
            if (isset($flight['booking_token']) && $flight['booking_token'] === $bookingToken) {
                return $flight;
            }
            // Chercher aussi par departure_token (au cas où)
            if (isset($flight['departure_token']) && $flight['departure_token'] === $bookingToken) {
                return $flight;
            }
        }

        return null;
    }

    // ============================================
    // ENREGISTREMENT DES RÉSERVATIONS
    // ============================================
    /**
     * Enregistre une réservation de vol (VERSION AVEC LOGS COMPLETS)
     */
    public function store(Request $request)
    {
        // ✅ LOG 1 : Toutes les données reçues
        \Log::info('========== DÉBUT RÉSERVATION ==========');
        \Log::info('📥 Données POST reçues', [
            'all_input' => $request->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'ip' => $request->ip(),
        ]);

        // ✅ LOG 2 : Vérifier les champs critiques
        \Log::info('🔍 Vérification champs critiques', [
            'has_booking_token' => $request->has('booking_token'),
            'has_departure_id' => $request->has('departure_id'),
            'has_passenger_names' => $request->has('passenger_names'),
            'booking_token_value' => $request->booking_token,
            'passenger_names_count' => is_array($request->passenger_names) ? count($request->passenger_names) : 0,
            'passenger_emails_count' => is_array($request->passenger_emails) ? count($request->passenger_emails) : 0,
            'passenger_phones_count' => is_array($request->passenger_phones) ? count($request->passenger_phones) : 0,
        ]);

        // Décoder les données JSON si nécessaire
        $flightDetails = $this->decodeJsonField($request->flight_details);
        $bookingOptions = $this->decodeJsonField($request->booking_options);

        // ✅ LOG 3 : Données décodées
        \Log::info('📦 Données JSON décodées', [
            'flight_details_exists' => !is_null($flightDetails),
            'flight_details_type' => gettype($flightDetails),
            'booking_options_exists' => !is_null($bookingOptions),
            'booking_options_type' => gettype($bookingOptions),
        ]);

        try {
            // ✅ LOG 4 : Avant validation
            \Log::info('🔐 Début de la validation...');

            $validated = $request->validate([
                'booking_token' => 'required|string',
                'departure_id' => 'required|string',
                'arrival_id' => 'required|string',
                'outbound_date' => 'required|date',
                'return_date' => 'nullable|date',
                'base_price' => 'required|numeric|min:0',
                'taxes' => 'required|numeric|min:0',
                'final_price' => 'required|numeric|min:0',
                'currency' => 'required|string|size:3',
                'adults' => 'required|integer|min:1',
                'children' => 'required|integer|min:0',
                'infants' => 'required|integer|min:0',
                'travel_class' => 'nullable|string', // ⭐ CHANGÉ : nullable au lieu de required
                'passenger_names' => 'required|array|min:1',
                'passenger_names.*' => 'required|string|max:255',
                'passenger_emails' => 'required|array|min:1',
                'passenger_emails.*' => 'nullable|email',
                'passenger_phones' => 'required|array|min:1',
                'passenger_phones.*' => 'nullable|string',
                'trip_type' => 'nullable|string|in:one_way,round_trip,multi_city',
            ]);

            // ⭐ Ajouter travel_class si absent
            $validated['travel_class'] = $validated['travel_class']
                ?? $flightDetails['flights'][0]['travel_class']
                ?? 'ECONOMY';

            $validated['flight_details'] = $flightDetails;
            $validated['booking_options'] = $bookingOptions;

            // ✅ LOG 5 : Validation réussie
            \Log::info('✅ VALIDATION RÉUSSIE', [
                'validated_keys' => array_keys($validated),
                'travel_class' => $validated['travel_class'],
                'trip_type' => $validated['trip_type'] ?? 'one_way',
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // ✅ LOG 6 : Erreurs de validation détaillées
            \Log::error('❌ ERREUR DE VALIDATION', [
                'errors' => $e->errors(),
                'failed_rules' => $e->validator->failed(),
                'input_except_sensitive' => $request->except(['password']),
            ]);

            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Erreur de validation : ' . implode(', ', array_keys($e->errors())));
        } catch (\Exception $e) {
            // ✅ LOG 7 : Erreur inattendue pendant validation
            \Log::error('❌ ERREUR INATTENDUE PENDANT VALIDATION', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Erreur technique : ' . $e->getMessage())
                ->withInput();
        }

        try {
            // ✅ LOG 8 : Début transaction
            \Log::info('💾 Début de la transaction DB...');
            \DB::beginTransaction();

            // Générer un numéro de réservation unique
            $bookingNumber = $this->generateBookingNumber();
            $totalPassengers = $validated['adults'] + $validated['children'] + $validated['infants'];

            // ✅ LOG 9 : Booking number généré
            \Log::info('🎫 Booking number généré', [
                'booking_number' => $bookingNumber,
                'total_passengers' => $totalPassengers,
            ]);

            // Préparer les détails des passagers
            $passengerDetails = $this->preparePassengerDetails(
                $validated['passenger_names'],
                $validated['passenger_emails'],
                $validated['passenger_phones'],
                $validated['adults'],
                $validated['children'],
                $validated['infants']
            );

            // ✅ LOG 10 : Passagers préparés
            \Log::info('👥 Passagers préparés', [
                'passenger_count' => count($passengerDetails),
                'first_passenger' => $passengerDetails[0] ?? null,
            ]);

            // Récupérer l'email et le téléphone du passager principal
            $mainPassengerEmail = $validated['passenger_emails'][0];
            $mainPassengerPhone = $validated['passenger_phones'][0];
            $mainPassengerName = $validated['passenger_names'][0];

            // ✅ LOG 11 : Avant création Booking
            \Log::info('📝 Création de la réservation principale...');

            $booking = Booking::create([
                'booking_number' => $bookingNumber,
                'user_id' => auth()->id(),
                'booking_type' => 'flight',
                'booking_date' => now(),
                'travel_date' => $validated['outbound_date'],
                'number_of_passengers' => $totalPassengers,
                'passenger_details' => $passengerDetails,
                'seat_class' => $validated['travel_class'],
                'total_amount' => $validated['base_price'],
                'currency' => $validated['currency'],
                'tax_amount' => $validated['taxes'],
                'final_amount' => $validated['final_price'],
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);

            // ✅ LOG 12 : Booking créé
            \Log::info('✅ Booking créé', [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number,
            ]);

            // Préparer les segments de vol
            $flightSegments = $this->prepareFlightSegments($validated['flight_details']);

            // ✅ LOG 13 : Segments préparés
            \Log::info('✈️ Segments de vol préparés', [
                'segment_count' => count($flightSegments),
            ]);

            // ✅ LOG 14 : Avant création FlightsBooking
            \Log::info('📝 Création de FlightsBooking...');

            $flightBooking = FlightsBooking::create([
                'booking_id' => $booking->id,
                'booking_token' => $validated['booking_token'],
                'departure_token' => $request->departure_token,
                'departure_id' => strtoupper($validated['departure_id']),
                'arrival_id' => strtoupper($validated['arrival_id']),
                'outbound_date' => $validated['outbound_date'],
                'return_date' => $validated['return_date'] ?? null,
                'trip_type' => $validated['trip_type'] ?? 'one_way',
                'flight_details' => $validated['flight_details'],
                'flight_segments' => $flightSegments,
                'passenger_info' => $passengerDetails,
                'booking_options' => $validated['booking_options'],
                'base_price' => $validated['base_price'],
                'taxes' => $validated['taxes'],
                'final_price' => $validated['final_price'],
                'currency' => $validated['currency'],
                'ticket_status' => 'pending',
            ]);

            // ✅ LOG 15 : FlightsBooking créé
            \Log::info('✅ FlightsBooking créé', [
                'flight_booking_id' => $flightBooking->id,
            ]);

            \DB::commit();

            // ✅ LOG 16 : Transaction commit
            \Log::info('✅ Transaction DB commit avec succès');

            // ========================================
            // ENVOI DES EMAILS
            // ========================================

            try {
                \Log::info('📧 Début envoi emails...');

                // ⭐ DÉTERMINER SI C'EST UN ALLER-RETOUR OU ALLER SIMPLE
                $isRoundTrip = $validated['trip_type'] === 'round_trip' && !empty($validated['return_date']);

                if ($isRoundTrip) {
                    // 1. Email client ALLER-RETOUR
                    \Mail::to($mainPassengerEmail)->send(
                        new \App\Mail\FlightBookingConfirmationRoundTrip($booking, $flightBooking, $mainPassengerName)
                    );

                    \Log::info('📧 Email client A/R envoyé', ['to' => $mainPassengerEmail]);

                    // 2. Email entreprise ALLER-RETOUR
                    $companyEmail = env('COMPANY_EMAIL', 'reservations@votresociete.com');
                    \Mail::to($companyEmail)->send(
                        new \App\Mail\NewFlightBookingNotificationRoundTrip($booking, $flightBooking, $mainPassengerEmail, $mainPassengerPhone)
                    );

                    \Log::info('📧 Email entreprise A/R envoyé', ['to' => $companyEmail]);

                } else {
                    // 1. Email client ALLER SIMPLE (vos emails existants)
                    \Mail::to($mainPassengerEmail)->send(
                        new \App\Mail\FlightBookingConfirmation($booking, $flightBooking, $mainPassengerName)
                    );

                    \Log::info('📧 Email client envoyé', ['to' => $mainPassengerEmail]);

                    // 2. Email entreprise ALLER SIMPLE
                    $companyEmail = env('COMPANY_EMAIL', 'reservations@votresociete.com');
                    \Mail::to($companyEmail)->send(
                        new \App\Mail\NewFlightBookingNotification($booking, $flightBooking, $mainPassengerEmail, $mainPassengerPhone)
                    );

                    \Log::info('📧 Email entreprise envoyé', ['to' => $companyEmail]);
                }

            } catch (\Exception $e) {
                \Log::error('⚠️ Erreur envoi emails (réservation OK)', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                ]);
            }

            // ✅ LOG 17 : Succès complet
            \Log::info('========== ✅ RÉSERVATION TERMINÉE AVEC SUCCÈS ==========', [
                'booking_id' => $booking->id,
                'booking_number' => $bookingNumber,
                'main_passenger' => $mainPassengerName,
                'email' => $mainPassengerEmail,
                'total' => $validated['final_price'],
            ]);

            return redirect()->route('flights.booking.success', $booking->id)
                ->with('success', 'Votre demande de réservation a été enregistrée ! Numéro : ' . $bookingNumber);

        } catch (\Exception $e) {
            \DB::rollBack();

            // ✅ LOG 18 : Erreur pendant la création
            \Log::error('========== ❌ ERREUR CRÉATION RÉSERVATION ==========', [
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return back()
                ->with('error', 'Erreur lors de la réservation : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Page de confirmation de réservation
     */
    public function confirmation($bookingId)
    {
        try {
            $booking = Booking::with('flightBooking')->findOrFail($bookingId);

            // Vérifier les permissions
            if (auth()->id() !== $booking->user_id && !auth()->user()->hasRole('admin')) {
                abort(403, 'Accès non autorisé à cette réservation');
            }

            Log::info('📄 Affichage confirmation réservation', [
                'booking_id' => $bookingId,
                'booking_number' => $booking->booking_number,
            ]);

            return view('pages.flight.booking-confirmation', compact('booking'));

        } catch (\Exception $e) {
            Log::error('❌ Erreur affichage confirmation', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('flights.index')
                ->with('error', 'Réservation introuvable.');
        }
    }

    /**
     * Redirection vers un site de réservation externe
     */
    public function redirect(Request $request)
    {
        $validated = $request->validate([
            'booking_url' => 'required|url',
            'price' => 'required|numeric',
            'booking_provider' => 'required|string',
        ]);

        Log::info('🔗 Redirection vers booking externe', [
            'provider' => $validated['booking_provider'],
            'price' => $validated['price'],
        ]);

        // Enregistrer l'événement de redirection (optionnel)
        if (auth()->check()) {
            Log::info('👤 Utilisateur redirigé', [
                'user_id' => auth()->id(),
                'provider' => $validated['booking_provider'],
            ]);
        }

        return redirect()->away($validated['booking_url']);
    }

    // ============================================
    // MÉTHODES PRIVÉES UTILITAIRES
    // ============================================

    /**
     * Récupère les détails d'un vol depuis l'API SerpAPI
     */
    private function fetchFlightDetails(array $params): ?array
    {
        $apiKey = env('SERPAPI_KEY');
        if (empty($apiKey)) {
            Log::error('❌ SERPAPI_KEY non configurée');
            return null;
        }

        $params['engine'] = 'google_flights';
        $params['api_key'] = $apiKey;
        $params['hl'] = 'fr';
        $params['gl'] = 'ci';

        try {
            $response = Http::timeout(30)->get('https://serpapi.com/search.json', $params);

            if (!$response->successful()) {
                Log::error('❌ Erreur API SerpAPI', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return null;
            }

            return $response->json();

        } catch (\Exception $e) {
            Log::error('❌ Exception lors de l\'appel API', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Extrait le vol sélectionné des résultats API
     */
    private function extractSelectedFlight(?array $flightData): ?array
    {
        if (!$flightData) {
            return null;
        }

        // Essayer d'abord selected_flights
        if (!empty($flightData['selected_flights'][0])) {
            return $flightData['selected_flights'][0];
        }

        // Sinon, chercher dans best_flights
        if (!empty($flightData['best_flights'][0])) {
            return $flightData['best_flights'][0];
        }

        // En dernier recours, other_flights
        if (!empty($flightData['other_flights'][0])) {
            return $flightData['other_flights'][0];
        }

        return null;
    }

    /**
     * Retourne une vue avec une erreur
     */
    private function returnDetailsError(string $view, string $errorMessage, array $searchParams)
    {
        return view($view, [
            'error' => $errorMessage,
            'selectedFlight' => null,
            'bookingOptions' => [],
            'searchParams' => $searchParams,
        ]);
    }

    /**
     * Décode un champ JSON (si c'est une string)
     */
    private function decodeJsonField($field)
    {
        if (is_string($field)) {
            return json_decode($field, true);
        }
        return $field;
    }

    /**
     * Génère un numéro de réservation unique
     */
    private function generateBookingNumber(): string
    {
        do {
            $bookingNumber = 'FL' . strtoupper(Str::random(8));
        } while (Booking::where('booking_number', $bookingNumber)->exists());

        return $bookingNumber;
    }

    /**
     * Prépare les détails des passagers
     */
    private function preparePassengerDetails(
        array $names,
        array $emails,
        array $phones,
        int $adults,
        int $children,
        int $infants
    ): array {
        $passengerDetails = [];
        $totalPassengers = $adults + $children + $infants;

        for ($i = 0; $i < $totalPassengers; $i++) {
            if ($i < $adults) {
                $type = 'adult';
            } elseif ($i < ($adults + $children)) {
                $type = 'child';
            } else {
                $type = 'infant';
            }

            $passengerDetails[] = [
                'type' => $type,
                'name' => $names[$i] ?? '',
                'email' => $emails[$i] ?? null,
                'phone' => $phones[$i] ?? null,
            ];
        }

        return $passengerDetails;
    }

    /**
     * Prépare les segments de vol à partir des flight_details
     */
    /* private function prepareFlightSegments(array $flightDetails): array
    {
        $flightSegments = [];

        if (!isset($flightDetails['flights']) || !is_array($flightDetails['flights'])) {
            return $flightSegments;
        }

        foreach ($flightDetails['flights'] as $segment) {
            $flightSegments[] = [
                'airline' => $segment['airline'] ?? '',
                'flight_number' => $segment['flight_number'] ?? '',
                'departure_airport' => [
                    'code' => $segment['departure_airport']['id'] ?? '',
                    'name' => $segment['departure_airport']['name'] ?? '',
                    'time' => $segment['departure_airport']['time'] ?? '',
                ],
                'arrival_airport' => [
                    'code' => $segment['arrival_airport']['id'] ?? '',
                    'name' => $segment['arrival_airport']['name'] ?? '',
                    'time' => $segment['arrival_airport']['time'] ?? '',
                ],
                'duration' => $segment['duration'] ?? 0,
                'aircraft' => $segment['airplane'] ?? $segment['aircraft'] ?? '',
                'travel_class' => $segment['travel_class'] ?? '',
            ];
        }

        return $flightSegments;
    } */

    /**
     * Page de succès après réservation
     */
    public function bookingSuccess($bookingId)
    {
        try {
            $booking = Booking::with('flightBooking')->findOrFail($bookingId);

            Log::info('📄 Affichage page succès réservation', [
                'booking_id' => $bookingId,
                'booking_number' => $booking->booking_number,
            ]);

            return view('pages.flight.booking-success', compact('booking'));

        } catch (\Exception $e) {
            Log::error('❌ Erreur affichage page succès', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage()
            ]);

            return redirect()->route('flights.index')
                ->with('error', 'Réservation introuvable.');
        }
    }



    // ============================================
// NOUVELLES MÉTHODES POUR ALLER-RETOUR UNIQUEMENT
// ============================================

    /**
     * Affiche les détails d'un vol ALLER-RETOUR
     */
    /**
     * Affiche les détails d'un vol ALLER-RETOUR (VERSION FINALE CORRIGÉE)
     * Cette méthode reçoit le booking_token qui contient DÉJÀ aller + retour combinés
     */
    public function detailsRoundTrip(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'currency' => 'required|string|size:3',
            'departure_id' => 'required|string',
            'arrival_id' => 'required|string',
            'outbound_date' => 'required|date',
            'return_date' => 'required|date',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'travel_class' => 'nullable|string',
        ]);

        try {
            Log::info('🔍 Récupération détails vol ALLER-RETOUR', [
                'booking_token' => substr($validated['booking_token'], 0, 50) . '...',
            ]);

            // ⭐ Essayer session d'abord
            $flightData = $this->getFlightFromSession($validated['booking_token']);

            // Si pas en session, appeler API avec le booking_token
            if (!$flightData) {
                Log::info('⚠️ Vol A/R non trouvé en session, tentative API...');

                $params = [
                    'engine' => 'google_flights',
                    'booking_token' => $validated['booking_token'],
                    'departure_id' => strtoupper($validated['departure_id']),  // ⭐ AJOUTÉ
                    'arrival_id' => strtoupper($validated['arrival_id']),      // ⭐ AJOUTÉ
                    'outbound_date' => $validated['outbound_date'],            // ⭐ AJOUTÉ
                    'return_date' => $validated['return_date'],                // ⭐ AJOUTÉ
                    'type' => 1,                                                // ⭐ AJOUTÉ (1 = round trip)
                    'adults' => $validated['adults'] ?? 1,                     // ⭐ AJOUTÉ
                    'currency' => $validated['currency'],
                    'hl' => 'fr',
                    'gl' => 'ci',
                ];

                $apiResponse = $this->fetchFlightDetails($params);

                if (!$apiResponse) {
                    return $this->returnRoundTripError(
                        'Les détails du vol ne sont plus disponibles.',
                        $validated
                    );
                }

                // ⭐ L'API retourne "selected_flights" qui contient 2 objets : aller + retour
                $selectedFlights = $apiResponse['selected_flights'] ?? [];

                if (empty($selectedFlights) || count($selectedFlights) < 2) {
                    return $this->returnRoundTripError(
                        'Données incomplètes (aller ou retour manquant).',
                        $validated
                    );
                }

                // ⭐ Séparer aller et retour
                $outboundFlight = $selectedFlights[0]; // Premier = Aller
                $returnFlight = $selectedFlights[1];   // Deuxième = Retour

                $flightData = [
                    'outbound' => $outboundFlight,
                    'return' => $returnFlight,
                    'total_price' => $apiResponse['booking_options'][0]['together']['price'] ??
                        ($outboundFlight['price'] ?? 0) + ($returnFlight['price'] ?? 0),
                    'currency' => $validated['currency'],
                    'booking_token' => $validated['booking_token'],
                ];
            }

            // ⭐ Préparer les données pour la vue
            $roundTripData = $this->formatRoundTripForDisplay($flightData, $validated);

            Log::info('✅ Détails vol ALLER-RETOUR récupérés', [
                'outbound_segments' => count($roundTripData['outbound_flights']),
                'return_segments' => count($roundTripData['return_flights']),
                'total_price' => $roundTripData['total_price'],
            ]);

            return view('pages.flight.flight-details-round-trip', [
                'error' => null,
                'roundTripData' => $roundTripData,
                'bookingOptions' => $flightData['booking_options'] ?? [],
                'searchParams' => $validated,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur détails vol ALLER-RETOUR', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->returnRoundTripError(
                'Une erreur est survenue : ' . $e->getMessage(),
                $validated
            );
        }
    }

    /**
     * ⭐ NOUVELLE MÉTHODE : Formate les données aller-retour pour la vue
     */
    private function formatRoundTripForDisplay(array $flightData, array $searchParams): array
    {
        $outbound = $flightData['outbound'] ?? [];
        $return = $flightData['return'] ?? [];

        return [
            'airline' => $outbound['airline'] ?? 'Multiple',
            'total_price' => $flightData['total_price'] ?? 0,
            'currency' => $flightData['currency'] ?? $searchParams['currency'],

            // ⭐ VOL ALLER
            'outbound_flights' => $this->formatFlightSegments($outbound['flights'] ?? []),
            'outbound_layovers' => $this->formatLayovers($outbound['layovers'] ?? []),
            'outbound_duration' => $outbound['total_duration'] ?? 0,
            'outbound_carbon' => $outbound['carbon_emissions'] ?? null,

            // ⭐ VOL RETOUR
            'return_flights' => $this->formatFlightSegments($return['flights'] ?? []),
            'return_layovers' => $this->formatLayovers($return['layovers'] ?? []),
            'return_duration' => $return['total_duration'] ?? 0,
            'return_carbon' => $return['carbon_emissions'] ?? null,

            // Autres infos
            'booking_token' => $flightData['booking_token'] ?? null,
            'extensions' => array_merge(
                $outbound['extensions'] ?? [],
                $return['extensions'] ?? []
            ),
        ];
    }

    /**
     * Formate les segments de vol avec informations complètes
     */
    private function formatFlightSegments(array $segments): array
    {
        return array_map(function ($segment) {
            return [
                'airline' => $segment['airline'] ?? '',
                'airline_logo' => $segment['airline_logo'] ?? '',
                'flight_number' => $segment['flight_number'] ?? '',
                'departure_airport' => [
                    'id' => $segment['departure_airport']['id'] ?? '',
                    'name' => $segment['departure_airport']['name'] ?? '',
                    'time' => $segment['departure_airport']['time'] ?? '',
                ],
                'arrival_airport' => [
                    'id' => $segment['arrival_airport']['id'] ?? '',
                    'name' => $segment['arrival_airport']['name'] ?? '',
                    'time' => $segment['arrival_airport']['time'] ?? '',
                ],
                'duration' => $segment['duration'] ?? 0,
                'aircraft' => $segment['airplane'] ?? $segment['aircraft'] ?? '',
                'travel_class' => $segment['travel_class'] ?? '',
                'legroom' => $segment['legroom'] ?? '',
                'extensions' => $segment['extensions'] ?? [],
                'overnight' => $segment['overnight'] ?? false,
            ];
        }, $segments);
    }

    /**
     * Prépare les segments de vol à partir des flight_details
     * Gère à la fois les vols aller simple et aller-retour
     */
    private function prepareFlightSegments(array $flightDetails): array
    {
        $flightSegments = [];

        // ⭐ CAS 1: Vol ALLER-RETOUR (outbound_flights + return_flights)
        if (isset($flightDetails['outbound_flights']) && is_array($flightDetails['outbound_flights'])) {
            \Log::info('📦 Préparation segments ALLER-RETOUR', [
                'outbound_count' => count($flightDetails['outbound_flights']),
                'return_count' => count($flightDetails['return_flights'] ?? []),
            ]);

            // Traiter les vols ALLER
            foreach ($flightDetails['outbound_flights'] as $segment) {
                $flightSegments[] = $this->formatSegment($segment);
            }

            // Traiter les vols RETOUR
            if (isset($flightDetails['return_flights']) && is_array($flightDetails['return_flights'])) {
                foreach ($flightDetails['return_flights'] as $segment) {
                    $flightSegments[] = $this->formatSegment($segment);
                }
            }

            \Log::info('✅ Segments A/R préparés', [
                'total_segments' => count($flightSegments),
            ]);

            return $flightSegments;
        }

        // ⭐ CAS 2: Vol ALLER SIMPLE (flights)
        if (isset($flightDetails['flights']) && is_array($flightDetails['flights'])) {
            \Log::info('📦 Préparation segments ALLER SIMPLE', [
                'flights_count' => count($flightDetails['flights']),
            ]);

            foreach ($flightDetails['flights'] as $segment) {
                $flightSegments[] = $this->formatSegment($segment);
            }

            \Log::info('✅ Segments aller simple préparés', [
                'total_segments' => count($flightSegments),
            ]);

            return $flightSegments;
        }

        \Log::warning('⚠️ Aucun segment de vol trouvé dans flight_details', [
            'keys' => array_keys($flightDetails),
        ]);

        return $flightSegments;
    }

    /**
     * Formate un segment de vol individuel
     */
    private function formatSegment(array $segment): array
    {
        return [
            'airline' => $segment['airline'] ?? '',
            'airline_logo' => $segment['airline_logo'] ?? '',
            'flight_number' => $segment['flight_number'] ?? '',
            'departure_airport' => [
                'code' => $segment['departure_airport']['id'] ?? $segment['departure_airport']['code'] ?? '',
                'name' => $segment['departure_airport']['name'] ?? '',
                'time' => $segment['departure_airport']['time'] ?? '',
                'city' => $segment['departure_airport']['city'] ?? null,
            ],
            'arrival_airport' => [
                'code' => $segment['arrival_airport']['id'] ?? $segment['arrival_airport']['code'] ?? '',
                'name' => $segment['arrival_airport']['name'] ?? '',
                'time' => $segment['arrival_airport']['time'] ?? '',
                'city' => $segment['arrival_airport']['city'] ?? null,
            ],
            'duration' => $segment['duration'] ?? $segment['duration_minutes'] ?? 0,
            'aircraft' => $segment['airplane'] ?? $segment['aircraft'] ?? '',
            'travel_class' => $segment['travel_class'] ?? '',
            'legroom' => $segment['legroom'] ?? '',
            'extensions' => $segment['extensions'] ?? [],
            'overnight' => $segment['overnight'] ?? false,
        ];
    }

    /**
     * Formate les escales
     */
    private function formatLayovers(array $layovers): array
    {
        return array_map(function ($layover) {
            return [
                'name' => $layover['name'] ?? '',
                'id' => $layover['id'] ?? '',
                'duration' => $layover['duration'] ?? 0,
                'overnight' => $layover['overnight'] ?? false,
            ];
        }, $layovers);
    }

    /**
     * Convertit le format de classe de voyage pour l'API
     */
    private function convertTravelClass($class): int
    {
        $mapping = [
            'ECONOMY' => 1,
            'PREMIUM_ECONOMY' => 2,
            'BUSINESS' => 3,
            'FIRST' => 4,
        ];

        return $mapping[strtoupper($class)] ?? 1;
    }

    /**
     * Récupère un vol aller-retour depuis la session (NOUVELLE MÉTHODE)
     */
    private function getRoundTripFromSession($bookingToken): ?array
    {
        $sessionData = session('flight_search_results');

        if (!$sessionData || empty($sessionData['results'])) {
            return null;
        }

        // Vérifier que les données ne sont pas trop anciennes
        if (now()->timestamp - ($sessionData['timestamp'] ?? 0) > 1800) {
            session()->forget('flight_search_results');
            return null;
        }

        $results = $sessionData['results'];
        $allFlights = array_merge(
            $results['best_flights'] ?? [],
            $results['other_flights'] ?? []
        );

        foreach ($allFlights as $flight) {
            if (isset($flight['booking_token']) && $flight['booking_token'] === $bookingToken) {
                return $flight;
            }
            if (isset($flight['departure_token']) && $flight['departure_token'] === $bookingToken) {
                return $flight;
            }
        }

        return null;
    }

    /**
     * Extrait les données d'un vol aller-retour depuis l'API (NOUVELLE MÉTHODE)
     */
    private function extractRoundTripData(?array $apiResponse): ?array
    {
        if (!$apiResponse) {
            return null;
        }

        // Essayer selected_flights en premier
        if (!empty($apiResponse['selected_flights'][0])) {
            return $apiResponse['selected_flights'][0];
        }

        // Sinon best_flights
        if (!empty($apiResponse['best_flights'][0])) {
            return $apiResponse['best_flights'][0];
        }

        // En dernier recours other_flights
        if (!empty($apiResponse['other_flights'][0])) {
            return $apiResponse['other_flights'][0];
        }

        return null;
    }

    /**
     * Retourne une vue d'erreur pour aller-retour (NOUVELLE MÉTHODE)
     */
    private function returnRoundTripError(string $errorMessage, array $searchParams)
    {
        return view('pages.flight.flight-details-round-trip', [
            'error' => $errorMessage,
            'roundTripData' => null,
            'bookingOptions' => [],
            'searchParams' => $searchParams,
        ]);
    }


    /**
     * Affiche les détails complets d'un vol multi-ville
     */
    public function detailsMultiCity(Request $request)
    {
        $validated = $request->validate([
            'booking_token' => 'required|string',
            'total_price' => 'required|numeric',
            'currency' => 'required|string|size:3',
            'multi_city_json' => 'required|json',
            'selected_segments' => 'nullable|json',
            'adults' => 'nullable|integer|min:1',
            'children' => 'nullable|integer|min:0',
            'infants' => 'nullable|integer|min:0',
            'travel_class' => 'nullable|string',
        ]);

        try {
            Log::info('🔍 Récupération détails vol MULTI-VILLE', [
                'booking_token' => substr($validated['booking_token'], 0, 50) . '...',
                'total_price' => $validated['total_price'],
            ]);

            // Récupérer les détails complets avec le booking_token final
            $params = [
                'booking_token' => $validated['booking_token'],
                'currency' => $validated['currency'],
                'hl' => 'fr',
                'gl' => 'ci',
            ];

            $flightData = $this->fetchFlightDetails($params);

            if (!$flightData) {
                return $this->returnMultiCityError(
                    'Les détails du vol ne sont plus disponibles. Veuillez effectuer une nouvelle recherche.',
                    $validated
                );
            }

            // Extraire les données sélectionnées
            $selectedFlight = $this->extractSelectedFlight($flightData);

            if (!$selectedFlight) {
                return $this->returnMultiCityError(
                    'Aucun vol trouvé avec ce token. Le vol n\'est peut-être plus disponible.',
                    $validated
                );
            }

            // Ajouter les informations du formulaire
            if (!isset($selectedFlight['price'])) {
                $selectedFlight['price'] = $validated['total_price'];
            }

            // Décoder les segments sélectionnés
            $selectedSegments = json_decode($validated['selected_segments'] ?? '[]', true);
            $multiCityData = json_decode($validated['multi_city_json'], true);

            Log::info('✅ Détails vol MULTI-VILLE récupérés', [
                'segments_count' => count($selectedFlight['flights'] ?? []),
                'price' => $selectedFlight['price'],
            ]);

            return view('pages.flight.flight-details-multi-city', [
                'error' => null,
                'selectedFlight' => $selectedFlight,
                'bookingOptions' => $flightData['booking_options'] ?? [],
                'searchParams' => $validated,
                'multiCityData' => $multiCityData,
                'selectedSegments' => $selectedSegments,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Erreur détails vol MULTI-VILLE', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return $this->returnMultiCityError(
                'Une erreur est survenue lors du chargement : ' . $e->getMessage(),
                $validated
            );
        }
    }

    /**
     * Retourne une vue d'erreur pour multi-ville
     */
    private function returnMultiCityError(string $errorMessage, array $searchParams)
    {
        return view('pages.flight.flight-details-multi-city', [
            'error' => $errorMessage,
            'selectedFlight' => null,
            'bookingOptions' => [],
            'searchParams' => $searchParams,
            'multiCityData' => json_decode($searchParams['multi_city_json'] ?? '[]', true),
            'selectedSegments' => json_decode($searchParams['selected_segments'] ?? '[]', true),
        ]);
    }
}