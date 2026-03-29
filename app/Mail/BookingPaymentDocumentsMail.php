<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingPaymentDocumentsMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->loadMissing([
            'event',
            'eventBooking.zone',
            'eventBooking.event',
            'package',
            'packageBooking.package',
            'location',
            'locationBooking',
            'flightBooking',
            'payment',
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Facture et recu de paiement - ' . $this->booking->booking_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-payment-documents',
            with: [
                'booking' => $this->booking,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->booking->invoice_pdf_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->booking->invoice_pdf_path)
                ->as($this->booking->invoice_filename)
                ->withMime('application/pdf');
        }

        if ($this->booking->receipt_pdf_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->booking->receipt_pdf_path)
                ->as($this->booking->receipt_filename)
                ->withMime('application/pdf');
        }

        return $attachments;
    }
}
