<?php

namespace App\Services;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\EventTicket;
use App\Models\PackageBooking;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Service de génération de documents PDF
 * 
 * Génère:
 * - E-tickets pour vols
 * - Billets d'événements avec QR code
 * - Vouchers pour packages touristiques
 * - Reçus de paiement
 */
class DocumentGeneratorService
{
    /**
     * Générer un e-ticket de vol
     * 
     * @param FlightBooking $flightBooking
     * @return string Chemin du PDF généré
     */
    public function generateFlightEticket($flightBooking)
    {
        $booking = $flightBooking->booking;
        $user = $booking->user;
        
        // Données pour le template
        $data = [
            'booking' => $booking,
            'flight_booking' => $flightBooking,
            'user' => $user,
            'pnr' => $flightBooking->pnr,
            'eticket_number' => $flightBooking->eticket_number,
            'segments' => json_decode($flightBooking->flight_segments, true),
            'passengers' => json_decode($booking->passenger_details, true),
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => $this->companyProfile(),
        ];

        // Générer le QR code de vérification
        $qrData = json_encode([
            'type' => 'flight',
            'pnr' => $flightBooking->pnr,
            'eticket' => $flightBooking->eticket_number,
            'booking_id' => $booking->id,
            'verification_code' => md5($booking->booking_number . $flightBooking->pnr)
        ]);
        
        $qrCodePath = 'qrcodes/flight_' . $booking->booking_number . '.png';
        QrCode::format('png')->size(200)->generate($qrData, storage_path('app/public/' . $qrCodePath));
        $data['qr_code_path'] = storage_path('app/public/' . $qrCodePath);

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.flight_eticket', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Sauvegarder le PDF
        $filename = 'etickets/flight_' . $booking->booking_number . '_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/' . $filename);
        $pdf->save($pdfPath);

        return $filename;
    }

    /**
     * Générer un billet d'événement avec QR code
     * 
     * @param EventTicket $eventTicket
     * @return string Chemin du PDF généré
     */
    public function generateEventTicket($eventTicket)
    {
        $booking = $eventTicket->booking;
        $event = $eventTicket->event;
        $seatZone = $eventTicket->seatZone;
        $holderName = app(BookingAccessService::class)->contactName($booking);
        $qrData = json_encode([
            'ticket_id' => $eventTicket->id,
            'ticket_number' => $eventTicket->ticket_number,
            'event_id' => $event->id,
            'holder_name' => $holderName,
            'validation_code' => md5($eventTicket->ticket_number . $event->id),
            'issued_at' => Carbon::now()->toIso8601String(),
        ]);

        $eventTicket->update([
            'qr_code' => $eventTicket->qr_code ?: 'not-generated',
            'qr_data' => $qrData,
        ]);

        // Données pour le template
        $data = [
            'ticket' => $eventTicket,
            'booking' => $booking,
            'event' => $event,
            'seat_zone' => $seatZone,
            'holder_name' => $holderName,
            'customer_email' => app(BookingAccessService::class)->contactEmail($booking),
            'customer_phone' => $this->customerPhone($booking),
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'validation_code' => md5($eventTicket->ticket_number . $event->id),
            'company' => $this->companyProfile(),
        ];

        $filename = $this->storeRenderedDocument(
            'tickets',
            'event_' . strtolower($eventTicket->ticket_number),
            'documents.event-ticket',
            $data
        );

        // Mettre à jour le chemin du PDF
        $eventTicket->update(['ticket_pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Générer un voucher de package touristique
     * 
     * @param PackageBooking $packageBooking
     * @return string Chemin du PDF généré
     */
    public function generatePackageVoucher($packageBooking)
    {
        $booking = $packageBooking->booking;
        $package = $packageBooking->package;
        $user = $booking->user;

        // Données pour le template
        $data = [
            'package_booking' => $packageBooking,
            'booking' => $booking,
            'package' => $package,
            'user' => $user,
            'confirmation_number' => $packageBooking->confirmation_number,
            'participants' => json_decode($packageBooking->participants_details, true),
            'itinerary' => json_decode($package->itinerary_fr, true),
            'included_services' => json_decode($package->included_services_fr, true),
            'excluded_services' => json_decode($package->excluded_services_fr, true),
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => $this->companyProfile(),
        ];

        // Générer le QR code de confirmation
        $qrData = json_encode([
            'type' => 'package',
            'confirmation_number' => $packageBooking->confirmation_number,
            'package_id' => $package->id,
            'booking_id' => $booking->id,
            'travel_date' => $packageBooking->travel_date,
            'verification_code' => md5($packageBooking->confirmation_number . $package->id)
        ]);
        
        $qrCodePath = 'qrcodes/package_' . $packageBooking->confirmation_number . '.png';
        QrCode::format('png')->size(200)->generate($qrData, storage_path('app/public/' . $qrCodePath));
        $data['qr_code_path'] = storage_path('app/public/' . $qrCodePath);

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.package_voucher', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Sauvegarder le PDF
        $filename = 'vouchers/package_' . $packageBooking->confirmation_number . '_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/' . $filename);
        $pdf->save($pdfPath);

        // Mettre à jour le chemin du PDF
        $packageBooking->update(['voucher_pdf_path' => $filename]);

        return $filename;
    }

    /**
     * Générer un reçu de paiement
     * 
     * @param Booking $booking
     * @return string Chemin du PDF généré
     */
    public function generatePaymentReceipt($booking)
    {
        $booking->loadMissing([
            'user',
            'event',
            'eventBooking.zone',
            'eventBooking.event',
            'package',
            'packageBooking.package',
            'location',
            'locationBooking',
            'flightBooking',
            'payment',
            'payments',
        ]);

        $user = $booking->user;
        $payment = $booking->payments()->latest()->first();
        $receiptNumber = $booking->receipt_number ?: $this->makeReceiptNumber($booking);

        // Calculer les détails de prix
        $breakdown = [
            'Montant de base' => $booking->total_amount - $booking->tax_amount - $booking->discount_amount,
            'Taxes' => $booking->tax_amount,
            'Réduction' => -$booking->discount_amount,
            'Total' => $booking->final_amount
        ];

        // Données pour le template
        $data = [
            'booking' => $booking,
            'user' => $user,
            'payment' => $payment,
            'breakdown' => $breakdown,
            'receipt_number' => $receiptNumber,
            'customer_name' => $this->customerName($booking),
            'customer_email' => $this->customerEmail($booking),
            'customer_phone' => $this->customerPhone($booking),
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => $this->companyProfile(),
        ];

        return $this->storeRenderedDocument(
            'receipts',
            'receipt_' . strtolower($receiptNumber),
            'pdf.payment_receipt',
            $data
        );
    }

    /**
     * Générer une facture complète
     * 
     * @param Booking $booking
     * @return string Chemin du PDF généré
     */
    public function generateInvoice($booking)
    {
        $booking->loadMissing([
            'user',
            'event',
            'eventBooking.zone',
            'eventBooking.event',
            'package',
            'packageBooking.package',
            'location',
            'locationBooking',
            'flightBooking',
            'payment',
            'payments',
        ]);

        $user = $booking->user;
        $payment = $booking->payments()->latest()->first();

        // Numéro de facture
        $invoiceNumber = $booking->invoice_number ?: $this->makeInvoiceNumber($booking);

        // Données pour le template
        $data = [
            'booking' => $booking,
            'user' => $user,
            'payment' => $payment,
            'invoice_number' => $invoiceNumber,
            'customer_name' => $this->customerName($booking),
            'customer_email' => $this->customerEmail($booking),
            'customer_phone' => $this->customerPhone($booking),
            'invoice_date' => Carbon::now()->format('d/m/Y'),
            'due_date' => Carbon::now()->addDays(30)->format('d/m/Y'),
            'company' => $this->companyProfile([
                'name' => config('carre_premium.company.legal_name', config('carre_premium.company.name')),
                'email' => config('carre_premium.contact.billing_email'),
            ]),
        ];

        return $this->storeRenderedDocument(
            'invoices',
            'invoice_' . strtolower($invoiceNumber),
            'pdf.invoice',
            $data
        );
    }

    public function makeReceiptNumber(Booking $booking): string
    {
        return 'REC-' . $booking->booking_number;
    }

    public function makeInvoiceNumber(Booking $booking): string
    {
        return 'INV-' . date('Y') . '-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);
    }

    /**
     * Valider un QR code de billet d'événement
     * 
     * @param string $qrData
     * @return array
     */
    public function validateEventTicketQR(string $qrData)
    {
        try {
            $data = json_decode($qrData, true);
            
            if (!isset($data['ticket_number'])) {
                return [
                    'valid' => false,
                    'message' => 'QR code invalide'
                ];
            }

            $ticket = EventTicket::where('ticket_number', $data['ticket_number'])->first();

            if (!$ticket) {
                return [
                    'valid' => false,
                    'message' => 'Billet non trouvé'
                ];
            }

            if ($ticket->ticket_status === 'used') {
                return [
                    'valid' => false,
                    'message' => 'Billet déjà utilisé le ' . $ticket->used_at->format('d/m/Y H:i'),
                    'ticket' => $ticket
                ];
            }

            if ($ticket->ticket_status === 'cancelled') {
                return [
                    'valid' => false,
                    'message' => 'Billet annulé'
                ];
            }

            // Vérifier le code de validation
            $expectedCode = md5($ticket->ticket_number . $ticket->event_id);
            if ($data['validation_code'] !== $expectedCode) {
                return [
                    'valid' => false,
                    'message' => 'Code de validation incorrect'
                ];
            }

            return [
                'valid' => true,
                'message' => 'Billet valide',
                'ticket' => $ticket,
                'event' => $ticket->event,
                'holder' => $ticket->booking->user
            ];
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Erreur de validation: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Marquer un billet comme utilisé
     * 
     * @param EventTicket $ticket
     * @param string $scannedBy
     * @return bool
     */
    public function markTicketAsUsed(EventTicket $ticket, string $scannedBy = 'System')
    {
        return $ticket->update([
            'ticket_status' => 'used',
            'used_at' => Carbon::now(),
            'used_by' => $scannedBy
        ]);
    }

    /**
     * Générer un reçu de paiement pour une réservation
     * 
     * @param Booking $booking
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateReceipt(Booking $booking)
    {
        $user = $booking->user;
        $payment = $booking->payment;

        // Calculer les détails de prix
        $breakdown = [
            'Montant de base' => $booking->total_amount,
            'Taxes' => $booking->tax_amount,
            'Réduction' => $booking->discount_amount > 0 ? -$booking->discount_amount : 0,
            'Total' => $booking->final_amount
        ];

        // Données pour le template
        $data = [
            'booking' => $booking,
            'user' => $user,
            'payment' => $payment,
            'breakdown' => $breakdown,
            'receipt_number' => 'REC-' . $booking->booking_number,
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => $this->companyProfile(),
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.receipt', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    protected function customerName(Booking $booking): string
    {
        return app(BookingAccessService::class)->contactName($booking);
    }

    protected function customerEmail(Booking $booking): ?string
    {
        return app(BookingAccessService::class)->contactEmail($booking);
    }

    protected function customerPhone(Booking $booking): ?string
    {
        return $booking->customer_phone;
    }

    protected function companyProfile(array $overrides = []): array
    {
        return array_merge([
            'name' => config('carre_premium.company.name'),
            'address' => config('carre_premium.company.address'),
            'city' => config('carre_premium.company.city'),
            'country' => config('carre_premium.company.country'),
            'phone' => config('carre_premium.contact.landline_display'),
            'email' => config('carre_premium.contact.email'),
            'support_email' => config('carre_premium.contact.support_email'),
            'billing_email' => config('carre_premium.contact.billing_email'),
            'website' => config('carre_premium.company.website'),
            'tax_id' => config('carre_premium.company.tax_id'),
            'registration' => config('carre_premium.company.registration'),
            'emergency_phone' => config('carre_premium.contact.mobile_display'),
        ], $overrides);
    }

    protected function storeRenderedDocument(string $directory, string $basename, string $view, array $data): string
    {
        Storage::disk('public')->makeDirectory($directory);

        $suffix = time() . '_' . Str::lower(Str::random(6));

        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $path = $directory . '/' . $basename . '_' . $suffix . '.pdf';
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView($view, $data);
            $pdf->setPaper('a4', 'portrait');
            $pdf->save(storage_path('app/public/' . $path));

            return $path;
        }

        $path = $directory . '/' . $basename . '_' . $suffix . '.html';
        Storage::disk('public')->put($path, view($view, $data)->render());

        return $path;
    }

    /**
     * Générer un e-ticket pour une réservation de vol
     * 
     * @param Booking $booking
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateETicket(Booking $booking)
    {
        $user = $booking->user;
        $flightBooking = $booking->flightBooking;

        if (!$flightBooking) {
            throw new \Exception('Aucune réservation de vol trouvée');
        }

        // Données pour le template
        $data = [
            'booking' => $booking,
            'flight_booking' => $flightBooking,
            'user' => $user,
            'pnr' => $flightBooking->pnr,
            'eticket_number' => $flightBooking->eticket_number,
            'segments' => json_decode($flightBooking->flight_segments, true),
            'passengers' => json_decode($booking->passenger_details, true),
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => $this->companyProfile(),
        ];

        // Générer le QR code de vérification
        $qrData = json_encode([
            'type' => 'flight',
            'pnr' => $flightBooking->pnr,
            'eticket' => $flightBooking->eticket_number,
            'booking_id' => $booking->id,
            'verification_code' => md5($booking->booking_number . $flightBooking->pnr)
        ]);
        
        $qrCodePath = 'qrcodes/flight_' . $booking->booking_number . '.png';
        
        // Créer le dossier si nécessaire
        if (!file_exists(storage_path('app/public/qrcodes'))) {
            mkdir(storage_path('app/public/qrcodes'), 0755, true);
        }
        
        QrCode::format('png')->size(200)->generate($qrData, storage_path('app/public/' . $qrCodePath));
        $data['qr_code_path'] = storage_path('app/public/' . $qrCodePath);

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.eticket', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Générer une confirmation de réservation
     * 
     * @param Booking $booking
     * @return \Barryvdh\DomPDF\PDF
     */
    public function generateBookingConfirmation(Booking $booking)
    {
        $user = $booking->user;

        // Déterminer le type de réservation et charger les données appropriées
        $itemDetails = null;
        switch ($booking->booking_type) {
            case 'flight':
                $itemDetails = $booking->flightBooking;
                break;
            case 'event':
                $itemDetails = $booking->event;
                break;
            case 'package':
                $itemDetails = $booking->package;
                break;
        }

        // Données pour le template
        $data = [
            'booking' => $booking,
            'user' => $user,
            'item_details' => $itemDetails,
            'confirmation_number' => $booking->booking_number,
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => $this->companyProfile(),
        ];

        // Générer le QR code de confirmation
        $qrData = json_encode([
            'booking_number' => $booking->booking_number,
            'booking_type' => $booking->booking_type,
            'user_id' => $user->id,
            'verification_code' => md5($booking->booking_number . $user->email)
        ]);
        
        $qrCodePath = 'qrcodes/confirmation_' . $booking->booking_number . '.png';
        
        // Créer le dossier si nécessaire
        if (!file_exists(storage_path('app/public/qrcodes'))) {
            mkdir(storage_path('app/public/qrcodes'), 0755, true);
        }
        
        QrCode::format('png')->size(200)->generate($qrData, storage_path('app/public/' . $qrCodePath));
        $data['qr_code_path'] = storage_path('app/public/' . $qrCodePath);

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.booking_confirmation', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }
}
