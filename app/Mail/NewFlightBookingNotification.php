<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\FlightsBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewFlightBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $flightBooking;
    public $customerEmail;
    public $customerPhone;

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
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de réservation de vol - ' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        $segments = $this->flightBooking->flight_segments ?? [];
        $isRoundTrip = !empty($this->flightBooking->return_date);
        
        // ⭐ NOUVELLE LOGIQUE : Séparer correctement les segments
        $outboundSegments = [];
        $returnSegments = [];
        
        if ($isRoundTrip && count($segments) > 0) {
            $halfPoint = ceil(count($segments) / 2);
            $outboundSegments = array_slice($segments, 0, $halfPoint);
            $returnSegments = array_slice($segments, $halfPoint);
            
            \Log::info('📧 Admin - Séparation segments', [
                'total' => count($segments),
                'outbound' => count($outboundSegments),
                'return' => count($returnSegments)
            ]);
        } else {
            $outboundSegments = $segments;
        }

        // ⭐ VOL ALLER
        $firstOutbound = $outboundSegments[0] ?? null;
        $lastOutbound = $outboundSegments[count($outboundSegments) - 1] ?? null;

        $departureInfo = [
            'code' => $firstOutbound['departure_airport']['code'] 
                ?? $firstOutbound['departure_airport']['id']
                ?? $this->flightBooking->departure_id,
            'name' => $firstOutbound['departure_airport']['name'] 
                ?? $this->flightBooking->departure_id,
            'time' => $firstOutbound['departure_airport']['time'] ?? null,
        ];

        $arrivalInfo = [
            'code' => $lastOutbound['arrival_airport']['code']
                ?? $lastOutbound['arrival_airport']['id']
                ?? $this->flightBooking->arrival_id,
            'name' => $lastOutbound['arrival_airport']['name']
                ?? $this->flightBooking->arrival_id,
            'time' => $lastOutbound['arrival_airport']['time'] ?? null,
        ];

        // ⭐ VOL RETOUR
        $firstReturn = $returnSegments[0] ?? null;
        $lastReturn = $returnSegments[count($returnSegments) - 1] ?? null;
        
        $returnDepartureInfo = null;
        $returnArrivalInfo = null;
        
        if (!empty($returnSegments)) {
            $returnDepartureInfo = [
                'code' => $firstReturn['departure_airport']['code'] 
                    ?? $firstReturn['departure_airport']['id'] ?? '',
                'name' => $firstReturn['departure_airport']['name'] ?? '',
                'time' => $firstReturn['departure_airport']['time'] ?? null,
            ];
            
            $returnArrivalInfo = [
                'code' => $lastReturn['arrival_airport']['code']
                    ?? $lastReturn['arrival_airport']['id'] ?? '',
                'name' => $lastReturn['arrival_airport']['name'] ?? '',
                'time' => $lastReturn['arrival_airport']['time'] ?? null,
            ];
        }

        // Type de vol
        $tripTypeLabel = match($this->flightBooking->trip_type) {
            'one_way' => 'Aller simple',
            'round_trip' => 'Aller-retour',
            'multi_city' => 'Multi-destinations',
            default => $isRoundTrip ? 'Aller-retour' : 'Aller simple'
        };

        return new Content(
            view: 'emails.new-flight-booking-notification',
            with: [
                'bookingNumber' => $this->booking->booking_number,
                'bookingDate' => $this->booking->booking_date->format('d/m/Y à H:i'),
                'customerEmail' => $this->customerEmail,
                'customerPhone' => $this->customerPhone,
                'passengerDetails' => $this->booking->passenger_details,
                
                // ⭐ VOL ALLER
                'departureInfo' => $departureInfo,
                'departureCity' => $departureInfo['name'],
                'departureCode' => $departureInfo['code'],
                'departureTime' => $departureInfo['time'],
                
                'arrivalInfo' => $arrivalInfo,
                'arrivalCity' => $arrivalInfo['name'],
                'arrivalCode' => $arrivalInfo['code'],
                'arrivalTime' => $arrivalInfo['time'],
                
                // ⭐ VOL RETOUR
                'returnDepartureInfo' => $returnDepartureInfo,
                'returnArrivalInfo' => $returnArrivalInfo,
                'hasReturnFlight' => !empty($returnSegments),
                
                // Dates
                'outboundDate' => \Carbon\Carbon::parse($this->flightBooking->outbound_date)->format('d/m/Y'),
                'returnDate' => $this->flightBooking->return_date 
                    ? \Carbon\Carbon::parse($this->flightBooking->return_date)->format('d/m/Y') 
                    : null,
                
                // Type
                'tripType' => $this->flightBooking->trip_type,
                'tripTypeLabel' => $tripTypeLabel,
                'isRoundTrip' => $isRoundTrip,
                
                // Prix
                'totalPassengers' => $this->booking->number_of_passengers,
                'seatClass' => $this->booking->seat_class,
                'basePrice' => number_format($this->booking->total_amount, 0, ',', ' '),
                'taxes' => number_format($this->booking->tax_amount, 0, ',', ' '),
                'totalPrice' => number_format($this->booking->final_amount, 0, ',', ' '),
                'currency' => $this->booking->currency,
                
                // Segments
                'flightDetails' => $this->flightBooking->flight_details,
                'flightSegments' => $segments,
                'outboundSegments' => $outboundSegments,
                'returnSegments' => $returnSegments,
            ]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}