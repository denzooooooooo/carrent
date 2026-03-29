<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PdfGeneratorService
{
    /**
     * Générer un reçu de paiement PDF
     */
    public function generatePaymentReceipt(Payment $payment)
    {
        $booking = $payment->booking;
        $user = $booking->user;

        $qrCodePayload = Route::has('verify.payment')
            ? route('verify.payment', $payment->transaction_id)
            : ($payment->transaction_id ?? $booking->booking_number);

        $qrCode = base64_encode(QrCode::format('png')
            ->size(150)
            ->generate($qrCodePayload));

        $data = [
            'payment' => $payment,
            'booking' => $booking,
            'user' => $user,
            'qrCode' => $qrCode,
            'company' => $this->companyProfile(),
        ];

        $pdf = Pdf::loadView('pdf.payment_receipt', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Générer une facture PDF
     */
    public function generateInvoice(Booking $booking)
    {
        $user = $booking->user;
        $payment = $booking->payment;
        
        // Calculer TVA (18% en Côte d'Ivoire)
        $subtotal = $booking->total_amount;
        $tva = $subtotal * 0.18;
        $total = $subtotal + $tva;
        
        $qrCodePayload = Route::has('verify.booking')
            ? route('verify.booking', $booking->booking_reference ?? $booking->booking_number)
            : ($booking->booking_reference ?? $booking->booking_number);

        $qrCode = base64_encode(QrCode::format('png')
            ->size(150)
            ->generate($qrCodePayload));

        $data = [
            'booking' => $booking,
            'user' => $user,
            'payment' => $payment,
            'subtotal' => $subtotal,
            'tva' => $tva,
            'total' => $total,
            'qrCode' => $qrCode,
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad($booking->id, 6, '0', STR_PAD_LEFT),
            'company' => $this->companyProfile([
                'name' => config('carre_premium.company.legal_name', config('carre_premium.company.name')),
                'email' => config('carre_premium.contact.billing_email'),
            ]),
        ];

        $pdf = Pdf::loadView('pdf.invoice', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Générer un billet électronique PDF (e-ticket)
     */
    public function generateETicket($flightBooking)
    {
        $booking = $flightBooking->booking;
        $user = $booking->user;
        $flightData = json_decode($flightBooking->flight_data, true);
        
        // QR Code avec PNR
        $qrCode = base64_encode(QrCode::format('png')
            ->size(200)
            ->generate($flightBooking->pnr));

        // Code-barres pour le PNR
        $barcode = base64_encode(QrCode::format('png')
            ->size(300, 80)
            ->generate($flightBooking->pnr));

        $data = [
            'flightBooking' => $flightBooking,
            'booking' => $booking,
            'user' => $user,
            'flightData' => $flightData,
            'passengers' => json_decode($flightBooking->passengers_data, true),
            'segments' => $flightData['itineraries'][0]['segments'] ?? [],
            'qrCode' => $qrCode,
            'barcode' => $barcode,
            'pnr' => $flightBooking->pnr
        ];

        $pdf = Pdf::loadView('pdf.e-ticket', $data);
        $pdf->setPaper('a4', 'portrait');
        
        return $pdf;
    }

    /**
     * Générer un rapport comptable PDF
     */
    public function generateAccountingReport($startDate, $endDate)
    {
        $bookings = Booking::with(['user', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->get();

        $totalRevenue = $bookings->sum('total_amount');
        $totalCommission = $bookings->sum('commission_amount');
        $totalNet = $totalRevenue - $totalCommission;
        $totalTva = $totalRevenue * 0.18;

        $data = [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'bookings' => $bookings,
            'summary' => [
                'total_revenue' => $totalRevenue,
                'total_commission' => $totalCommission,
                'total_net' => $totalNet,
                'total_tva' => $totalTva,
                'count' => $bookings->count()
            ],
            'byType' => [
                'flight' => $bookings->where('booking_type', 'flight')->sum('total_amount'),
                'event' => $bookings->where('booking_type', 'event')->sum('total_amount'),
                'package' => $bookings->where('booking_type', 'package')->sum('total_amount'),
            ]
        ];

        $pdf = Pdf::loadView('pdf.accounting-report', $data);
        $pdf->setPaper('a4', 'landscape');
        
        return $pdf;
    }

    /**
     * Télécharger un PDF
     */
    public function download($pdf, $filename)
    {
        return $pdf->download($filename);
    }

    /**
     * Afficher un PDF dans le navigateur
     */
    public function stream($pdf)
    {
        return $pdf->stream();
    }

    /**
     * Sauvegarder un PDF
     */
    public function save($pdf, $path)
    {
        return $pdf->save($path);
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
            'website' => config('carre_premium.company.website'),
            'tax_id' => config('carre_premium.company.tax_id'),
            'registration' => config('carre_premium.company.registration'),
        ], $overrides);
    }
}
