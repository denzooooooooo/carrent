<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document de réservation {{ $booking->booking_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #1f2937;
            margin: 40px;
            line-height: 1.5;
        }
        .header, .section {
            margin-bottom: 32px;
        }
        .header h1 {
            margin: 0 0 8px;
            font-size: 28px;
        }
        .muted {
            color: #6b7280;
        }
        .card {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
            vertical-align: top;
        }
        .amount {
            font-weight: 700;
            font-size: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Document de réservation</h1>
        <p class="muted">Carré Premium</p>
        <p class="muted">Référence: {{ $booking->booking_number }}</p>
    </div>

    <div class="section card">
        <h2>Client</h2>
        <table>
            <tr>
                <th>Nom</th>
                <td>{{ $booking->customer_name }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $booking->customer_email ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <th>Téléphone</th>
                <td>{{ $booking->customer_phone ?? 'Non renseigné' }}</td>
            </tr>
        </table>
    </div>

    <div class="section card">
        <h2>Réservation</h2>
        <table>
            <tr>
                <th>Type</th>
                <td>{{ ucfirst($booking->booking_type) }}</td>
            </tr>
            <tr>
                <th>Produit</th>
                <td>{{ $booking->title }}</td>
            </tr>
            <tr>
                <th>Date de réservation</th>
                <td>{{ optional($booking->booking_date)->format('d/m/Y H:i') ?? optional($booking->created_at)->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Voyage / événement</th>
                <td>{{ optional($booking->travel_date)->format('d/m/Y') ?? 'Non renseigné' }}</td>
            </tr>
            <tr>
                <th>Statut</th>
                <td>{{ ucfirst($booking->status) }}</td>
            </tr>
            <tr>
                <th>Paiement</th>
                <td>{{ ucfirst(str_replace('_', ' ', $booking->payment_status)) }}</td>
            </tr>
            <tr>
                <th>Méthode</th>
                <td>{{ $booking->payment_method_label }}</td>
            </tr>
        </table>
    </div>

    @if($booking->booking_type === 'flight' && $booking->flightBooking)
        <div class="section card">
            <h2>Détails du vol</h2>
            <table>
                <tr>
                    <th>Vol</th>
                    <td>{{ $booking->flightBooking->flight_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Compagnie</th>
                    <td>{{ $booking->flightBooking->airline ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Itinéraire</th>
                    <td>{{ $booking->flightBooking->departure_airport ?? 'N/A' }} -> {{ $booking->flightBooking->arrival_airport ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>PNR</th>
                    <td>{{ $booking->flightBooking->duffel_booking_reference ?? $booking->flightBooking->pnr ?? 'En attente' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if($booking->booking_type === 'event' && $booking->eventBooking)
        <div class="section card">
            <h2>Détails de l'événement</h2>
            <table>
                <tr>
                    <th>Événement</th>
                    <td>{{ $booking->event?->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Zone / Package</th>
                    <td>{{ $booking->eventBooking->zone?->zone_name ?? $booking->eventBooking->package?->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Quantité</th>
                    <td>{{ $booking->eventBooking->quantity }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if($booking->booking_type === 'package' && $booking->packageBooking)
        <div class="section card">
            <h2>Détails du package</h2>
            <table>
                <tr>
                    <th>Package</th>
                    <td>{{ $booking->package?->title ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Participants</th>
                    <td>{{ $booking->packageBooking->participants_count }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ optional($booking->packageBooking->travel_date)->format('d/m/Y') ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    @endif

    @if($booking->booking_type === 'location' && $booking->locationBooking)
        <div class="section card">
            <h2>Détails de la location</h2>
            <table>
                <tr>
                    <th>Bien</th>
                    <td>{{ $booking->location?->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Séjour</th>
                    <td>
                        {{ optional($booking->locationBooking->start_date)->format('d/m/Y') ?? 'N/A' }}
                        au
                        {{ optional($booking->locationBooking->end_date)->format('d/m/Y') ?? 'N/A' }}
                    </td>
                </tr>
                <tr>
                    <th>Durée</th>
                    <td>{{ $booking->locationBooking->days }} jour(s)</td>
                </tr>
            </table>
        </div>
    @endif

    <div class="section card">
        <h2>Montants</h2>
        <table>
            <tr>
                <th>Montant de base</th>
                <td>{{ number_format((float) $booking->total_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
            </tr>
            @if((float) $booking->discount_amount > 0)
                <tr>
                    <th>Réduction</th>
                    <td>-{{ number_format((float) $booking->discount_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                </tr>
            @endif
            @if((float) $booking->tax_amount > 0)
                <tr>
                    <th>Taxes</th>
                    <td>{{ number_format((float) $booking->tax_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
                </tr>
            @endif
            <tr>
                <th>Total</th>
                <td class="amount">{{ number_format((float) $booking->final_amount, 0, ',', ' ') }} {{ $booking->currency }}</td>
            </tr>
        </table>
    </div>

    @if($payment)
        <div class="section card">
            <h2>Dernier paiement enregistré</h2>
            <table>
                <tr>
                    <th>Transaction</th>
                    <td>{{ $payment->transaction_id }}</td>
                </tr>
                <tr>
                    <th>Prestataire</th>
                    <td>{{ $payment->payment_provider ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Statut</th>
                    <td>{{ ucfirst($payment->status) }}</td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>{{ optional($payment->payment_date)->format('d/m/Y H:i') ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    @endif
</body>
</html>
