<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\FlightBooking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class FlightTicketsMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public FlightBooking $flightBooking;
    public array $duffelOrder;
    public array $passengers;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, FlightBooking $flightBooking, array $duffelOrder)
    {
        $this->booking = $booking;
        $this->flightBooking = $flightBooking;
        $this->duffelOrder = $duffelOrder;
        $this->passengers = $booking->passenger_details ?? [];
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✈️ Vos Billets d\'Avion - ' . $this->booking->booking_number,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.flight-tickets',
            with: [
                'booking' => $this->booking,
                'flightBooking' => $this->flightBooking,
                'duffelOrder' => $this->duffelOrder,
                'passengers' => $this->passengers,
                'bookingReference' => $this->duffelOrder['booking_reference'] ?? 'N/A',
                'slices' => $this->duffelOrder['slices'] ?? [],
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $attachments = [];

        // Ajouter les documents (e-tickets) si disponibles
        if (isset($this->duffelOrder['documents']) && is_array($this->duffelOrder['documents'])) {
            foreach ($this->duffelOrder['documents'] as $document) {
                if (isset($document['url'])) {
                    // Note: Duffel fournit des URLs pour télécharger les PDFs
                    // Vous pouvez télécharger et attacher les PDFs ici si nécessaire
                }
            }
        }

        return $attachments;
    }
}
