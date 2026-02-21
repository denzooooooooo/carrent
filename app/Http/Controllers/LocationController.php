<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{


    /**
     * Traiter la réservation d'une location.
     */
    public function book(Request $request, Location $location)
    {
        $request->validate([
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after:start_date',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'special_requests' => 'nullable|string|max:1000',
        ]);

        // Calculer le nombre de jours
        $startDate = \Carbon\Carbon::parse($request->start_date);
        $endDate = \Carbon\Carbon::parse($request->end_date);
        $days = $startDate->diffInDays($endDate) + 1; // +1 pour inclure le jour de fin

        // Calculer le prix total
        $totalAmount = $location->price_per_day * $days;

        // Créer la réservation générale
        $booking = \App\Models\Booking::create([
            'booking_number' => 'LOC-' . strtoupper(uniqid()),
            'user_id' => null, // Pas d'utilisateur connecté pour les réservations de location
            'booking_type' => 'location',
            'location_id' => $location->id,
            'booking_date' => now(),
            'travel_date' => $request->start_date,
            'number_of_passengers' => 1, // Pour les locations, c'est généralement 1
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'passenger_details' => [
                [
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'type' => 'adult'
                ]
            ],
            'total_amount' => $totalAmount,
            'currency' => 'XAF',
            'final_amount' => $totalAmount,
            'status' => 'pending',
            'payment_status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        // Créer l'enregistrement spécifique à la location
        $locationBooking = \App\Models\LocationBooking::create([
            'location_id' => $location->id,
            'booking_id' => $booking->id,
            'user_name' => $request->first_name . ' ' . $request->last_name,
            'user_email' => $request->email,
            'user_phone' => $request->phone,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'days' => $days,
            'unit_price' => $location->price_per_day,
            'total_price' => $totalAmount,
            'status' => 'pending',
            'special_requests' => $request->special_requests,
        ]);

        return redirect()->route('payment.checkout', $booking)
            ->with('success', 'Votre réservation de location a été créée avec succès! Veuillez procéder au paiement pour la confirmer.');
    }

    /**
     * Afficher les détails d'une location.
     */
    public function show(Location $location)
    {
        if (!$location->is_active) {
            abort(404);
        }

        return view('pages.location-details', compact('location'));
    }

    /**
     * Afficher la page de confirmation de réservation de location.
     */
    public function bookingConfirmation(\App\Models\Booking $booking)
    {
        if ($booking->booking_type !== 'location') {
            abort(404);
        }

        $booking->load(['location', 'locationBooking']);
        return view('pages.location-booking-confirmation', compact('booking'));
    }
}
