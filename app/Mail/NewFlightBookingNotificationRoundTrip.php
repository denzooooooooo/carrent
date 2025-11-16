<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewFlightBookingNotificationRoundTrip extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightBooking;
    public $customerEmail;
    public $customerPhone;
    public $outboundFlights;
    public $returnFlights;

    public function __construct(
        Booking $booking, 
        FlightsBooking $flightBooking, 
        string $customerEmail, 
        string $customerPhone
    ) {
        $this->booking = $booking;
        $this->flightBooking = $flightBooking;
        $this->customerEmail = $customerEmail;
        $this->customerPhone = $customerPhone;
        
        // Extraire les vols
        $flightDetails = $flightBooking->flight_details;
        $this->outboundFlights = $flightDetails['outbound_flights'] ?? [];
        $this->returnFlights = $flightDetails['return_flights'] ?? [];
    }

    public function build()
    {
        return $this->subject('🔔 Nouvelle réservation Aller-Retour - ' . $this->booking->booking_number)
                    ->view('emails.new-flight-booking-notification-roundtrip')
                    ->with([
                        'booking' => $this->booking,
                        'flightBooking' => $this->flightBooking,
                        'customerEmail' => $this->customerEmail,
                        'customerPhone' => $this->customerPhone,
                        'outboundFlights' => $this->outboundFlights,
                        'returnFlights' => $this->returnFlights,
                    ]);
    }
}