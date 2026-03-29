<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\EventTicket;
use Illuminate\Support\Facades\Storage;

class BookingFulfillmentService
{
    public function __construct(
        protected DocumentGeneratorService $documents,
        protected BookingAccessService $bookingAccessService,
    ) {
    }

    public function ensureDocuments(Booking $booking): Booking
    {
        $booking->loadMissing([
            'user',
            'event',
            'eventBooking.zone',
            'eventBooking.event',
            'eventBooking.package',
            'eventBooking.packageOption',
            'package',
            'packageBooking.package',
            'location',
            'locationBooking',
            'flightBooking',
            'payment',
            'payments',
            'eventTickets',
        ]);

        $updates = [];

        if (blank($booking->invoice_number)) {
            $booking->invoice_number = $this->documents->makeInvoiceNumber($booking);
            $updates['invoice_number'] = $booking->invoice_number;
        }

        if (blank($booking->invoice_pdf_path) || !$this->fileExists($booking->invoice_pdf_path)) {
            $updates['invoice_pdf_path'] = $this->documents->generateInvoice($booking);
            $booking->invoice_pdf_path = $updates['invoice_pdf_path'];
        }

        if ($this->shouldGenerateReceipt($booking)) {
            if (blank($booking->receipt_number)) {
                $booking->receipt_number = $this->documents->makeReceiptNumber($booking);
                $updates['receipt_number'] = $booking->receipt_number;
            }

            if (blank($booking->receipt_pdf_path) || !$this->fileExists($booking->receipt_pdf_path)) {
                $updates['receipt_pdf_path'] = $this->documents->generatePaymentReceipt($booking);
                $booking->receipt_pdf_path = $updates['receipt_pdf_path'];
            }
        }

        if ($updates !== []) {
            $updates['documents_generated_at'] = now();
            $booking->update($updates);
            $booking->refresh();
        }

        $this->ensureEventTickets($booking);

        return $booking->loadMissing([
            'user',
            'event',
            'eventBooking.zone',
            'eventBooking.event',
            'eventBooking.package',
            'eventBooking.packageOption',
            'package',
            'packageBooking.package',
            'location',
            'locationBooking',
            'flightBooking',
            'payment',
            'payments',
            'eventTickets',
        ]);
    }

    public function ensureEventTickets(Booking $booking): Booking
    {
        $booking->loadMissing([
            'event',
            'eventBooking.zone',
            'eventBooking.event',
            'eventTickets',
        ]);

        if (!$this->shouldGenerateEventTickets($booking)) {
            return $booking;
        }

        $targetCount = max(1, (int) $booking->number_of_passengers);
        $eventId = $booking->event_id ?: $booking->eventBooking?->event_id;
        $zoneId = $booking->seat_zone_id ?: $booking->eventBooking?->zone_id;
        $unitPrice = (float) ($booking->eventBooking?->unit_price ?: ($booking->final_amount / $targetCount));
        $existingCount = $booking->eventTickets->count();

        for ($index = $existingCount + 1; $index <= $targetCount; $index++) {
            EventTicket::create([
                'booking_id' => $booking->id,
                'event_id' => $eventId,
                'ticket_number' => EventTicket::generateTicketNumber(),
                'qr_code' => 'not-generated',
                'qr_data' => json_encode([
                    'booking_number' => $booking->booking_number,
                    'ticket_index' => $index,
                    'validation_code' => md5($booking->booking_number . '-' . $index),
                ]),
                'seat_zone_id' => $zoneId,
                'seat_number' => $zoneId ? 'PASS-' . str_pad((string) $index, 2, '0', STR_PAD_LEFT) : null,
                'base_price' => $unitPrice,
                'margin_amount' => 0,
                'final_price' => $unitPrice,
                'ticket_status' => 'valid',
            ]);
        }

        $booking->load('eventTickets', 'event', 'eventBooking.zone');

        foreach ($booking->eventTickets as $ticket) {
            if (blank($ticket->ticket_pdf_path) || !$this->fileExists($ticket->ticket_pdf_path)) {
                $ticket->update([
                    'ticket_pdf_path' => $this->documents->generateEventTicket($ticket),
                ]);
            }
        }

        return $booking->refresh()->load('eventTickets');
    }

    protected function shouldGenerateReceipt(Booking $booking): bool
    {
        return $booking->payment_status === 'paid'
            || $booking->payments()->where('status', 'completed')->exists();
    }

    protected function shouldGenerateEventTickets(Booking $booking): bool
    {
        return $booking->booking_type === 'event'
            && $booking->eventBooking !== null
            && $booking->payment_status === 'paid'
            && in_array($booking->status, ['confirmed', 'completed'], true);
    }

    protected function fileExists(?string $path): bool
    {
        return filled($path) && Storage::disk('public')->exists($path);
    }
}
