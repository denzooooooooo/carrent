<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements avec filtres.
     */
    public function index(Request $request)
    {
        $query = Event::where('is_active', true)
            ->with(['category', 'type']);

        // Filtre par catégorie/type d'événement
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filtre par lieu/venue - Recherche partielle (LIKE)
        if ($request->filled('venue')) {
            $query->where('venue_name', 'like', '%' . $request->venue . '%');
        }

        // Filtre par ville - Recherche partielle
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filtre par pays - Recherche partielle
        if ($request->filled('country')) {
            $query->where('country', 'like', '%' . $request->country . '%');
        }

        // Filtre par prix minimum
        if ($request->filled('min_price')) {
            $query->where('min_price', '>=', $request->min_price);
        }

        // Filtre par prix maximum
        if ($request->filled('max_price')) {
            $query->where('max_price', '<=', $request->max_price);
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        // Filtre par date de début
        if ($request->filled('start_date')) {
            $query->whereDate('event_date', '>=', $request->start_date);
        }

        // Filtre par date de fin
        if ($request->filled('end_date')) {
            $query->whereDate('event_date', '<=', $request->end_date);
        }

        $events = $query->orderBy('event_date', 'asc')
            ->paginate(12);

        // Récupérer les catégories pour les filtres
        $categories = \App\Models\EventCategory::where('is_active', true)->get();
        
        // Récupérer les villes uniques pour le filtre
        $cities = \App\Models\Event::where('is_active', true)
            ->whereNotNull('city')
            ->distinct()
            ->pluck('city')
            ->sort()
            ->values();
            
        // Récupérer les pays uniques pour le filtre
        $countries = \App\Models\Event::where('is_active', true)
            ->whereNotNull('country')
            ->distinct()
            ->pluck('country')
            ->sort()
            ->values();
            
        // Récupérer les lieux uniques pour le filtre
        $venues = \App\Models\Event::where('is_active', true)
            ->whereNotNull('venue_name')
            ->distinct()
            ->pluck('venue_name')
            ->sort()
            ->values();

        return view('pages.events', compact('events', 'categories', 'cities', 'countries', 'venues'));
    }

    /**
     * Afficher les détails d'un événement avec les zones de sièges disponibles et les packages.
     */
    public function show($slug)
    {
        $relations = ['seatZones' => function($query) {
            $query->where('is_active', true)->orderBy('price');
        }, 'category', 'type', 'series'];

        // Only load packages if the table exists
        if (Schema::hasTable('event_packages')) {
            $relations['packages'] = function($query) {
                $query->where('is_active', true)->orderBy('sort_order');
            };
        }

        $event = Event::with($relations)
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return view('pages.event-details', compact('event'));
    }

    /**
     * Traiter la réservation d'un événement (sieges ou packages).
     */
    public function book(Request $request, Event $event)
    {
        // Validation de base
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Determiner si c'est une reservation de siege ou de package
        $hasZone = $request->filled('zone_id');
        $hasPackage = $request->filled('package_id');

        // Check if packages table exists before allowing package booking
        if ($hasPackage && !Schema::hasTable('event_packages')) {
            return back()->withErrors(['error' => 'Les packages ne sont pas disponibles actuellement. Veuillez contacter le support.']);
        }

        if (!$hasZone && !$hasPackage) {
            return back()->withErrors(['error' => 'Veuillez sélectionner une zone de places ou un package.']);
        }

        // Traiter le nom complet en first_name et last_name
        $fullName = $request->input('name', '');
        $nameParts = explode(' ', trim($fullName), 2);
        $firstName = $nameParts[0] ?? $request->input('first_name', '');
        $lastName = $nameParts[1] ?? $request->input('last_name', '');

        // Fallback aux champs individuels si present
        if ($request->filled('first_name')) {
            $firstName = $request->first_name;
        }
        if ($request->filled('last_name')) {
            $lastName = $request->last_name;
        }

        if (empty($firstName)) {
            return back()->withErrors(['name' => 'Le nom est requis.']);
        }

        $unitPrice = 0;
        $totalPrice = 0;
        $bookingReference = 'EVT-' . strtoupper(uniqid());
        $zoneId = null;
        $packageId = null;

        // Cas 1: Reservation de siege (zone)
        if ($hasZone) {
            $request->validate([
                'zone_id' => 'required|exists:event_seat_zones,id',
            ]);

            $zone = $event->seatZones()->findOrFail($request->zone_id);

            // Verifier la disponibilite
            if ($request->quantity > $zone->available_seats) {
                return back()->withErrors(['quantity' => 'Nombre de places demande superieur a la disponibilite.']);
            }

            $unitPrice = $zone->price;
            $totalPrice = $zone->price * $request->quantity;
            $zoneId = $zone->id;

            // Mettre a jour les places disponibles
            $zone->decrement('available_seats', $request->quantity);
        }
 
// Cas 2: Reservation de package
        if ($hasPackage) {
            $request->validate([
                'package_id' => 'required|exists:event_packages,id',
            ]);

            $package = $event->packages()->findOrFail($request->package_id);

            // Verifier la disponibilite
            if ($request->quantity > $package->available_quantity) {
                return back()->withErrors(['quantity' => 'Nombre de packages demandé supérieur à la disponibilité.']);
            }

            $unitPrice = $package->price;
            $totalPrice = $package->price * $request->quantity;
            
            // ✅ SOLUTION GROS MONTANTS: Virement bancaire pour >1.5M XOF
            if ($totalPrice > 1500000) {
                // Créer booking avec payment_method='bank_transfer'
                $booking = \App\Models\Booking::create([
                    'booking_number' => $bookingReference,
                    'user_id' => auth()->check() ? auth()->id() : null,
                    'booking_type' => 'event',
                    'event_id' => $event->id,
                    'event_booking_id' => $eventBooking->id ?? null,
                    'seat_zone_id' => $zoneId,
                    'booking_date' => now(),
                    'travel_date' => $event->event_date,
                    'number_of_passengers' => $request->quantity,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'passenger_details' => [[
                        'first_name' => $firstName, 'last_name' => $lastName,
                        'email' => $request->email, 'phone' => $request->phone, 'type' => 'adult'
                    ]],
                    'total_amount' => $totalPrice, 'currency' => 'XOF', 'final_amount' => $totalPrice,
                    'status' => 'pending_payment', 'payment_status' => 'pending',
                    'payment_method' => 'bank_transfer', // ✅ Virement pour gros montants
                    'notes' => "VIP Package >1.5M XOF. Paiement par virement bancaire.\nRIB: [INSÉRER RIB COMPTE]\nRéf: {$bookingReference}"
                ]);
                
                return redirect()->route('payment.instructions', $booking)
                    ->with('info', 'Réservation VIP créée! Suivez les instructions de virement bancaire.');
            }

            $packageId = $package->id;

            // Mettre a jour la quantite disponible
            $package->decrement('available_quantity', $request->quantity);
        }

        // Creer la reservation d'evenement
        $eventBooking = \App\Models\EventBooking::create([
            'event_id' => $event->id,
            'zone_id' => $zoneId,
            'package_id' => $packageId,
            'user_name' => $firstName . ' ' . $lastName,
            'user_email' => $request->email,
            'user_phone' => $request->phone,
            'quantity' => $request->quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'booking_reference' => $bookingReference,
        ]);

        // Creer l'enregistrement general de reservation pour l'admin
        $booking = \App\Models\Booking::create([
            'booking_number' => $bookingReference,
            'user_id' => auth()->check() ? auth()->id() : null,
            'booking_type' => 'event',
            'event_id' => $event->id,
            'event_booking_id' => $eventBooking->id,
            'seat_zone_id' => $zoneId,
            'booking_date' => now(),
            'travel_date' => $event->event_date,
            'number_of_passengers' => $request->quantity,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'passenger_details' => [
                [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'type' => 'adult'
                ]
            ],
            'total_amount' => $totalPrice,
            'currency' => 'XOF',
            'final_amount' => $totalPrice,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Envoyer l'email de confirmation
        try {
            \Log::info('Tentative d\'envoi d\'email de confirmation pour la reservation: ' . $eventBooking->id);
            \Illuminate\Support\Facades\Mail::to($eventBooking->user_email)->send(
                new \App\Mail\EventBookingConfirmation($eventBooking)
            );
            \Log::info('Email de confirmation envoye avec succes pour la reservation: ' . $eventBooking->id);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de confirmation d\'evenement: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return redirect()->route('payment.cinetpay.redirect', $booking)
            ->with('success', 'Votre réservation a été créée. Redirection vers le paiement sécurisé...');
    }

    /**
     * Afficher la page de confirmation de réservation.
     */
    public function bookingConfirmation(\App\Models\Booking $booking)
    {
        $booking->load(['event', 'eventBooking.zone']);
        return view('pages.event-booking-confirmation', compact('booking'));
    }
}
