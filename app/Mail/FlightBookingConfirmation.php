<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightBooking;
    public $passengerName;

    public function __construct(Booking $booking, FlightsBooking $flightBooking, string $passengerName)
    {
        $this->booking = $booking;
        $this->flightBooking = $flightBooking;
        $this->passengerName = $passengerName;
    }

    public function build()
    {
        $segments = $this->flightBooking->flight_segments ?? [];
        $isRoundTrip = !empty($this->flightBooking->return_date);
        
        // ⭐ NOUVELLE LOGIQUE : Séparer en fonction de la structure des données
        $outboundSegments = [];
        $returnSegments = [];
        
        if ($isRoundTrip && count($segments) > 0) {
            // Calculer le nombre de segments par direction (divisé par 2 pour A/R)
            $halfPoint = ceil(count($segments) / 2);
            
            // Les premiers segments = ALLER, les derniers = RETOUR
            $outboundSegments = array_slice($segments, 0, $halfPoint);
            $returnSegments = array_slice($segments, $halfPoint);
            
            \Log::info('📧 Séparation segments A/R', [
                'total' => count($segments),
                'outbound' => count($outboundSegments),
                'return' => count($returnSegments)
            ]);
        } else {
            $outboundSegments = $segments;
        }

        // ⭐ VOL ALLER : Premier et dernier segment
        $firstOutbound = $outboundSegments[0] ?? null;
        $lastOutbound = $outboundSegments[count($outboundSegments) - 1] ?? null;

        // Informations DÉPART (vol aller)
        $departureCode = $firstOutbound['departure_airport']['code'] 
            ?? $firstOutbound['departure_airport']['id']
            ?? $this->flightBooking->departure_id;
            
        $departureCity = $firstOutbound['departure_airport']['name']
            ?? $departureCode;
            
        $departureTime = $firstOutbound['departure_airport']['time'] ?? null;

        // Informations ARRIVÉE (vol aller)
        $arrivalCode = $lastOutbound['arrival_airport']['code']
            ?? $lastOutbound['arrival_airport']['id']
            ?? $this->flightBooking->arrival_id;
            
        $arrivalCity = $lastOutbound['arrival_airport']['name']
            ?? $arrivalCode;
            
        $arrivalTime = $lastOutbound['arrival_airport']['time'] ?? null;

        // ⭐ VOL RETOUR : Premier segment retour
        $firstReturn = $returnSegments[0] ?? null;
        $lastReturn = $returnSegments[count($returnSegments) - 1] ?? null;
        
        $returnDepartureTime = $firstReturn['departure_airport']['time'] ?? null;
        $returnArrivalTime = $lastReturn['arrival_airport']['time'] ?? null;

        // Calculer le nombre d'escales
        $outboundStops = max(0, count($outboundSegments) - 1);
        $returnStops = max(0, count($returnSegments) - 1);

        return $this->subject('Confirmation de votre demande de réservation - ' . $this->booking->booking_number)
            ->view('emails.flight-booking-confirmation')
            ->with([
                'bookingNumber' => $this->booking->booking_number,
                'passengerName' => $this->passengerName,
                
                // ⭐ Départ (vol aller)
                'departureCode' => $departureCode,
                'departureCity' => $departureCity,
                'departureTime' => $departureTime,
                
                // ⭐ Arrivée (vol aller)
                'arrivalCode' => $arrivalCode,
                'arrivalCity' => $arrivalCity,
                'arrivalTime' => $arrivalTime,
                
                // ⭐ Dates
                'outboundDate' => $this->flightBooking->outbound_date,
                'returnDate' => $this->flightBooking->return_date,
                
                // ⭐ Retour
                'returnDepartureTime' => $returnDepartureTime,
                'returnArrivalTime' => $returnArrivalTime,
                
                // ⭐ Type de vol
                'tripType' => $this->flightBooking->trip_type,
                'isRoundTrip' => $isRoundTrip,
                
                // Autres infos
                'totalPassengers' => $this->booking->number_of_passengers,
                'seatClass' => $this->booking->seat_class,
                'totalPrice' => number_format($this->booking->final_amount, 0, ',', ' '),
                'currency' => $this->booking->currency,
                
                // ⭐ Segments
                'outboundSegments' => $outboundSegments,
                'returnSegments' => $returnSegments,
                'outboundStops' => $outboundStops,
                'returnStops' => $returnStops,
                'hasMultipleSegments' => count($segments) > 1,
            ]);
    }
}