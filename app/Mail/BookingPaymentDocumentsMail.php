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
            'eventTickets',
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
            subject: ($this->booking->booking_type === 'event' ? 'Documents et billets - ' : 'Facture et recu de paiement - ')
                . $this->booking->booking_number,
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
                ->withMime($this->booking->invoice_mime_type);
        }

        if ($this->booking->receipt_pdf_path) {
            $attachments[] = Attachment::fromStorageDisk('public', $this->booking->receipt_pdf_path)
                ->as($this->booking->receipt_filename)
                ->withMime($this->booking->receipt_mime_type);
        }

        foreach ($this->booking->eventTickets as $ticket) {
            if ($ticket->ticket_pdf_path) {
                $attachments[] = Attachment::fromStorageDisk('public', $ticket->ticket_pdf_path)
                    ->as($ticket->document_filename)
                    ->withMime($ticket->document_mime_type);
            }
        }

        return $attachments;
    }
}
