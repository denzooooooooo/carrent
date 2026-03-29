<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\EventBooking;
use App\Models\Event;
use App\Services\EventDiscoveryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Afficher la liste des événements avec filtres.
     */
    public function index(Request $request)
    {
        $payload = app(EventDiscoveryService::class)->buildIndexPayload($request);

        return view('pages.events', $payload);
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

                if (Schema::hasTable('event_package_options')) {
                    $query->with(['options' => function ($optionsQuery) {
                        $optionsQuery->where('is_active', true)->orderBy('sort_order')->orderBy('option_date');
                    }]);
                }
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
            'zone_id' => 'nullable|exists:event_seat_zones,id',
            'package_id' => 'nullable|exists:event_packages,id',
            'package_option_id' => 'nullable|exists:event_package_options,id',
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

        if ($hasZone && $hasPackage) {
            return back()->withErrors(['error' => 'Veuillez sélectionner soit une zone de places, soit une formule, mais pas les deux.']);
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

        [$eventBooking, $booking, $requiresBankTransfer] = DB::transaction(function () use (
            $event,
            $request,
            $hasZone,
            $hasPackage,
            $firstName,
            $lastName
        ) {
            $quantity = (int) $request->quantity;
            $bookingReference = 'EVT-' . strtoupper(uniqid());
            $unitPrice = 0;
            $totalPrice = 0;
            $zoneId = null;
            $packageId = null;
            $packageOptionId = null;
            $travelDate = $event->event_date;

            if ($hasZone) {
                $zone = $event->seatZones()
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->findOrFail($request->zone_id);

                if ($quantity > $zone->available_seats) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Nombre de places demande superieur a la disponibilite.',
                    ]);
                }

                $unitPrice = (float) $zone->price;
                $totalPrice = $unitPrice * $quantity;
                $zoneId = $zone->id;

                $zone->decrement('available_seats', $quantity);
            }

            if ($hasPackage) {
                $package = $event->allPackages()
                    ->where('is_active', true)
                    ->lockForUpdate()
                    ->findOrFail($request->package_id);

                $packageId = $package->id;
                $minimumQuantity = max(1, (int) ($package->minimum_quantity ?? 1));
                $maxPerOrder = max(1, (int) ($package->max_per_order ?? 1));

                if ($quantity < $minimumQuantity) {
                    throw ValidationException::withMessages([
                        'quantity' => "La quantite minimale pour {$package->name} est de {$minimumQuantity}.",
                    ]);
                }

                if ($quantity > $maxPerOrder) {
                    throw ValidationException::withMessages([
                        'quantity' => "La quantite maximale pour {$package->name} est de {$maxPerOrder}.",
                    ]);
                }

                if ($quantity > $package->available_quantity) {
                    throw ValidationException::withMessages([
                        'quantity' => 'Nombre de formules demande superieur a la disponibilite.',
                    ]);
                }

                $packageHasOptions = Schema::hasTable('event_package_options')
                    && $package->allOptions()->where('is_active', true)->exists();

                if ($packageHasOptions) {
                    if (!$request->filled('package_option_id')) {
                        throw ValidationException::withMessages([
                            'package_option_id' => 'Veuillez selectionner une option tarifaire precise.',
                        ]);
                    }

                    $packageOption = $package->allOptions()
                        ->where('is_active', true)
                        ->lockForUpdate()
                        ->find($request->package_option_id);

                    if (!$packageOption) {
                        throw ValidationException::withMessages([
                            'package_option_id' => 'L’option selectionnee est invalide pour cette formule.',
                        ]);
                    }

                    $optionMaxPerOrder = max(1, (int) ($packageOption->max_per_order ?? $maxPerOrder));
                    if ($quantity > $optionMaxPerOrder) {
                        throw ValidationException::withMessages([
                            'quantity' => "La quantite maximale pour cette option est de {$optionMaxPerOrder}.",
                        ]);
                    }

                    if ($quantity > $packageOption->available_quantity) {
                        throw ValidationException::withMessages([
                            'quantity' => 'Nombre d’options demande superieur a la disponibilite.',
                        ]);
                    }

                    $unitPrice = (float) $packageOption->price;
                    $packageOptionId = $packageOption->id;
                    $travelDate = $packageOption->option_date ?? $travelDate;

                    $packageOption->decrement('available_quantity', $quantity);
                } else {
                    $unitPrice = (float) $package->price;
                }

                $totalPrice = $unitPrice * $quantity;
                $package->decrement('available_quantity', $quantity);
            }

            $event->decrement('available_seats', $quantity);

            $requiresBankTransfer = $totalPrice > 1500000;

            $eventBooking = EventBooking::create([
                'event_id' => $event->id,
                'zone_id' => $zoneId,
                'package_id' => $packageId,
                'package_option_id' => $packageOptionId,
                'user_name' => trim($firstName . ' ' . $lastName),
                'user_email' => $request->email,
                'user_phone' => $request->phone,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'booking_reference' => $bookingReference,
                'booking_date' => now(),
            ]);

            $booking = Booking::create([
                'booking_number' => $bookingReference,
                'user_id' => auth()->check() ? auth()->id() : null,
                'booking_type' => 'event',
                'event_id' => $event->id,
                'event_booking_id' => $eventBooking->id,
                'seat_zone_id' => $zoneId,
                'booking_date' => now(),
                'travel_date' => $travelDate,
                'number_of_passengers' => $quantity,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'passenger_details' => [[
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'type' => 'adult',
                ]],
                'total_amount' => $totalPrice,
                'currency' => 'XOF',
                'final_amount' => $totalPrice,
                'status' => $requiresBankTransfer ? 'pending_payment' : 'pending',
                'payment_status' => 'pending',
                'payment_method' => $requiresBankTransfer ? 'bank_transfer' : null,
                'notes' => $requiresBankTransfer
                    ? "Reservation evenement > 1.5M XOF. Paiement par virement bancaire.\nReference: {$bookingReference}"
                    : null,
            ]);

            return [
                $eventBooking->load(['event', 'zone', 'package', 'packageOption']),
                $booking,
                $requiresBankTransfer,
            ];
        });

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

        if ($requiresBankTransfer) {
            return redirect()->route('payment.instructions', $booking)
                ->with('info', 'Votre reservation a ete creee. Suivez les instructions de virement bancaire pour finaliser le paiement.');
        }

        return redirect()->route('payment.cinetpay.redirect', $booking)
            ->with('success', 'Votre réservation a été créée. Redirection vers le paiement sécurisé...');
    }

    /**
     * Afficher la page de confirmation de réservation.
     */
    public function bookingConfirmation(\App\Models\Booking $booking)
    {
        $booking->load(['event', 'eventBooking.zone', 'eventBooking.package', 'eventBooking.packageOption']);
        return view('pages.event-booking-confirmation', compact('booking'));
    }
}
