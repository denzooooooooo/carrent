<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

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

        // Filtre par lieu/venue
        if ($request->filled('venue')) {
            $query->where('venue_name', 'like', '%' . $request->venue . '%');
        }

        // Filtre par date
        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        $events = $query->orderBy('event_date', 'asc')
            ->paginate(12);

        // Récupérer les catégories pour les filtres
        $categories = \App\Models\EventCategory::where('is_active', true)->get();

        return view('pages.events', compact('events', 'categories'));
    }

    /**
     * Afficher les détails d'un événement avec les zones de sièges disponibles.
     */
    public function show($slug)
    {
        $event = Event::with(['seatZones' => function($query) {
            $query->where('is_active', true)->orderBy('price');
        }, 'category', 'type'])
        ->where('slug', $slug)
        ->where('is_active', true)
        ->firstOrFail();

        return view('pages.event-details', compact('event'));
    }

    /**
     * Traiter la réservation d'un événement.
     */
    public function book(Request $request, Event $event)
    {
        $request->validate([
            'zone_id' => 'required|exists:event_seat_zones,id',
            'quantity' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $zone = $event->seatZones()->findOrFail($request->zone_id);

        // Vérifier la disponibilité
        if ($request->quantity > $zone->available_seats) {
            return back()->withErrors(['quantity' => 'Nombre de places demandé supérieur à la disponibilité.']);
        }

        // Créer la réservation d'événement
        $eventBooking = \App\Models\EventBooking::create([
            'event_id' => $event->id,
            'zone_id' => $zone->id,
            'user_name' => $request->name,
            'user_email' => $request->email,
            'user_phone' => $request->phone,
            'quantity' => $request->quantity,
            'unit_price' => $zone->price,
            'total_price' => $zone->price * $request->quantity,
            'status' => 'pending',
            'booking_reference' => 'EVT-' . strtoupper(uniqid()),
        ]);

        // Créer l'enregistrement général de réservation pour l'admin
        $booking = \App\Models\Booking::create([
            'booking_number' => $eventBooking->booking_reference,
            'user_id' => null, // Pas d'utilisateur connecté pour les réservations d'événements
            'booking_type' => 'event',
            'event_id' => $event->id,
            'event_booking_id' => $eventBooking->id,
            'seat_zone_id' => $zone->id,
            'booking_date' => now(),
            'travel_date' => $event->event_date,
            'number_of_passengers' => $request->quantity,
            'passenger_details' => [
                [
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'type' => 'adult'
                ]
            ],
            'total_amount' => $eventBooking->total_price,
            'currency' => 'XAF', // Devise par défaut
            'final_amount' => $eventBooking->total_price,
            'status' => 'pending',
            'payment_status' => 'pending',
        ]);

        // Mettre à jour les places disponibles
        $zone->decrement('available_seats', $request->quantity);

        // Envoyer l'email de confirmation
        try {
            \Log::info('Tentative d\'envoi d\'email de confirmation pour la réservation: ' . $eventBooking->id);
            \Illuminate\Support\Facades\Mail::to($eventBooking->user_email)->send(
                new \App\Mail\EventBookingConfirmation($eventBooking)
            );
            \Log::info('Email de confirmation envoyé avec succès pour la réservation: ' . $eventBooking->id);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de confirmation d\'événement: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return redirect()->route('payment.checkout', $booking)
            ->with('success', 'Votre réservation a été créée. Veuillez procéder au paiement pour la confirmer.');
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
