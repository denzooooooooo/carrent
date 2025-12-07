<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PackageBookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $passengerName;

    public function __construct(Booking $booking, string $passengerName)
    {
        $this->booking = $booking;
        $this->passengerName = $passengerName;
    }

    public function build()
    {
        return $this->subject('Confirmation de votre réservation de package - ' . $this->booking->booking_number)
            ->view('emails.package-booking-confirmation')
            ->with([
                'bookingNumber' => $this->booking->booking_number,
                'passengerName' => $this->passengerName,
                'packageName' => $this->booking->package ? $this->booking->package->title : 'Package touristique',
                'travelDate' => $this->booking->travel_date,
                'numberOfPassengers' => $this->booking->number_of_passengers,
                'totalPrice' => number_format($this->booking->final_amount, 0, ',', ' '),
                'currency' => $this->booking->currency,
            ]);
    }
}
