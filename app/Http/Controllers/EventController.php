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

        // Créer la réservation
        $booking = \App\Models\EventBooking::create([
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

        // Mettre à jour les places disponibles
        $zone->decrement('available_seats', $request->quantity);

        // Envoyer l'email de confirmation
        try {
            \Log::info('Tentative d\'envoi d\'email de confirmation pour la réservation: ' . $booking->id);
            \Illuminate\Support\Facades\Mail::to($booking->user_email)->send(
                new \App\Mail\EventBookingConfirmation($booking)
            );
            \Log::info('Email de confirmation envoyé avec succès pour la réservation: ' . $booking->id);
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'envoi de l\'email de confirmation d\'événement: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
        }

        return redirect()->route('event.booking.confirmation', $booking)
            ->with('success', 'Votre réservation a été créée avec succès! Un email de confirmation vous a été envoyé.');
    }

    /**
     * Afficher la page de confirmation de réservation.
     */
    public function bookingConfirmation(\App\Models\EventBooking $booking)
    {
        $booking->load(['event', 'zone']);
        return view('pages.event-booking-confirmation', compact('booking'));
    }
}
