<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightBookingConfirmationRoundTrip extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightBooking;
    public $passengerName;
    public $outboundFlights;
    public $returnFlights;
    public $outboundLayovers;
    public $returnLayovers;

    public function __construct(Booking $booking, FlightsBooking $flightBooking, string $passengerName)
    {
        $this->booking = $booking;
        $this->flightBooking = $flightBooking;
        $this->passengerName = $passengerName;
        
        // Extraire les vols aller et retour
        $flightDetails = $flightBooking->flight_details;
        $this->outboundFlights = $flightDetails['outbound_flights'] ?? [];
        $this->returnFlights = $flightDetails['return_flights'] ?? [];
        $this->outboundLayovers = $flightDetails['outbound_layovers'] ?? [];
        $this->returnLayovers = $flightDetails['return_layovers'] ?? [];
    }

    public function build()
    {
        return $this->subject('✈️ Confirmation de votre réservation Aller-Retour - ' . $this->booking->booking_number)
                    ->view('emails.flight-booking-confirmation-roundtrip')
                    ->with([
                        'booking' => $this->booking,
                        'flightBooking' => $this->flightBooking,
                        'passengerName' => $this->passengerName,
                        'outboundFlights' => $this->outboundFlights,
                        'returnFlights' => $this->returnFlights,
                        'outboundLayovers' => $this->outboundLayovers,
                        'returnLayovers' => $this->returnLayovers,
                    ]);
    }
}