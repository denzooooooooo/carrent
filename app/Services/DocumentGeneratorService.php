<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\Booking;
use App\Models\FlightBooking;
use App\Models\EventTicket;
use App\Models\PackageBooking;
use Carbon\Carbon;

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
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com'
            ]
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
        $user = $booking->user;
        $seatZone = $eventTicket->seatZone;

        // Générer le QR code unique
        $qrData = json_encode([
            'ticket_id' => $eventTicket->id,
            'ticket_number' => $eventTicket->ticket_number,
            'event_id' => $event->id,
            'event_name' => $event->title_fr,
            'holder_name' => $user->first_name . ' ' . $user->last_name,
            'seat' => $seatZone ? $seatZone->zone_name_fr . ' - ' . $eventTicket->seat_number : 'Général',
            'date' => $event->event_date,
            'time' => $event->event_time,
            'validation_code' => md5($eventTicket->ticket_number . $event->id),
            'issued_at' => Carbon::now()->toIso8601String()
        ]);

        // Sauvegarder le QR code
        $qrCodePath = 'qrcodes/event_' . $eventTicket->ticket_number . '.png';
        QrCode::format('png')->size(300)->generate($qrData, storage_path('app/public/' . $qrCodePath));
        
        // Mettre à jour le chemin du QR code dans la base
        $eventTicket->update([
            'qr_code' => $qrCodePath,
            'qr_data' => $qrData
        ]);

        // Données pour le template
        $data = [
            'ticket' => $eventTicket,
            'booking' => $booking,
            'event' => $event,
            'user' => $user,
            'seat_zone' => $seatZone,
            'qr_code_path' => storage_path('app/public/' . $qrCodePath),
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com'
            ]
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.event_ticket', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Sauvegarder le PDF
        $filename = 'tickets/event_' . $eventTicket->ticket_number . '_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/' . $filename);
        $pdf->save($pdfPath);

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
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com',
                'emergency_phone' => '+225 XX XX XX XX XX'
            ]
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
        $user = $booking->user;
        $payment = $booking->payments()->latest()->first();

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
            'receipt_number' => 'REC-' . $booking->booking_number,
            'issued_date' => Carbon::now()->format('d/m/Y H:i'),
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com',
                'tax_id' => 'CI-XXXXXXXXX' // Numéro fiscal
            ]
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.payment_receipt', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Sauvegarder le PDF
        $filename = 'receipts/receipt_' . $booking->booking_number . '_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/' . $filename);
        $pdf->save($pdfPath);

        return $filename;
    }

    /**
     * Générer une facture complète
     * 
     * @param Booking $booking
     * @return string Chemin du PDF généré
     */
    public function generateInvoice($booking)
    {
        $user = $booking->user;
        $payment = $booking->payments()->latest()->first();

        // Numéro de facture
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT);

        // Données pour le template
        $data = [
            'booking' => $booking,
            'user' => $user,
            'payment' => $payment,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => Carbon::now()->format('d/m/Y'),
            'due_date' => Carbon::now()->addDays(30)->format('d/m/Y'),
            'company' => [
                'name' => 'Carré Premium SARL',
                'address' => 'Abidjan, Cocody Riviera',
                'city' => 'Abidjan',
                'country' => 'Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'facturation@carrepremium.com',
                'website' => 'www.carrepremium.com',
                'tax_id' => 'CI-XXXXXXXXX',
                'registration' => 'CI-ABJ-XXXXXXXXX'
            ]
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.invoice', $data);
        $pdf->setPaper('a4', 'portrait');
        
        // Sauvegarder le PDF
        $filename = 'invoices/invoice_' . $invoiceNumber . '_' . time() . '.pdf';
        $pdfPath = storage_path('app/public/' . $filename);
        $pdf->save($pdfPath);

        return $filename;
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
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com',
                'tax_id' => 'CI-XXXXXXXXX'
            ]
        ];

        // Générer le PDF
        $pdf = Pdf::loadView('pdf.receipt', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
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
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com'
            ]
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
            'company' => [
                'name' => 'Carré Premium',
                'address' => 'Abidjan, Côte d\'Ivoire',
                'phone' => '+225 XX XX XX XX XX',
                'email' => 'contact@carrepremium.com',
                'website' => 'www.carrepremium.com',
                'support_email' => 'support@carrepremium.com'
            ]
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
