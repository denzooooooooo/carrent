<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Http\Response;

class BookingDocumentService
{
    public function download(Booking $booking): Response
    {
        $booking->loadMissing([
            'user',
            'event',
            'eventBooking.zone',
            'eventBooking.package',
            'package',
            'packageBooking',
            'location',
            'locationBooking',
            'flightBooking',
            'payments',
        ]);

        $html = view('documents.booking-document', [
            'booking' => $booking,
            'payment' => $booking->payment,
        ])->render();

        $filename = sprintf('%s-document.html', strtolower($booking->booking_number));

        return response($html, 200, [
            'Content-Type' => 'text/html; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
